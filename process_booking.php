<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/database.php';

/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

function redirectBack(string $message): never
{
    $_SESSION['flash_error'] = $message;

    header('Location: booking.php');
    exit;
}

function redirectTo(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/*
|--------------------------------------------------------------------------
| Only POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectTo('booking.php');
}

/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

$csrfToken = $_POST['csrf_token'] ?? '';

if (
    !is_string($csrfToken) ||
    !verifyCsrfToken($csrfToken)
) {
    redirectBack(
        'Your session has expired. Please refresh the page and try again.'
    );
}

/*
|--------------------------------------------------------------------------
| Get form data
|--------------------------------------------------------------------------
*/

$customerName = trim((string) ($_POST['customer_name'] ?? ''));
$phone        = trim((string) ($_POST['phone'] ?? ''));
$email        = trim((string) ($_POST['email'] ?? ''));
$bookingDate  = trim((string) ($_POST['booking_date'] ?? ''));
$startTime    = trim((string) ($_POST['start_time'] ?? ''));
$duration     = (int) ($_POST['duration'] ?? 0);
$notes        = trim((string) ($_POST['notes'] ?? ''));

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

if ($customerName === '') {
    redirectBack('Please enter your full name.');
}

if (mb_strlen($customerName) > 120) {
    redirectBack('Your name is too long.');
}

if ($phone === '') {
    redirectBack('Please enter your WhatsApp number.');
}

if (mb_strlen($phone) > 30) {
    redirectBack('Your WhatsApp number is too long.');
}

if (
    $email === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {
    redirectBack('Please enter a valid email address.');
}

if (mb_strlen($email) > 160) {
    redirectBack('Your email address is too long.');
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $bookingDate)) {
    redirectBack('Please select a valid booking date.');
}

if (!preg_match('/^\d{2}:\d{2}$/', $startTime)) {
    redirectBack('Please select a valid start time.');
}

if ($duration < 1 || $duration > 4) {
    redirectBack('Please select a valid duration.');
}

if (mb_strlen($notes) > 500) {
    redirectBack('Notes cannot exceed 500 characters.');
}

/*
|--------------------------------------------------------------------------
| Booking date
|--------------------------------------------------------------------------
*/

try {
    $date = new DateTimeImmutable($bookingDate);
} catch (Throwable $e) {
    redirectBack('Invalid booking date.');
}

$today = new DateTimeImmutable('today');

if ($date < $today) {
    redirectBack('You cannot book a date in the past.');
}

/*
|--------------------------------------------------------------------------
| Studio configuration
|--------------------------------------------------------------------------
*/

$studioOpenHour  = 10;
$studioCloseHour = 23;
$pricePerHour    = 75000;

/*
|--------------------------------------------------------------------------
| Validate start time
|--------------------------------------------------------------------------
*/

try {
    $startDateTime = new DateTimeImmutable(
        $bookingDate . ' ' . $startTime . ':00'
    );
} catch (Throwable $e) {
    redirectBack('Invalid booking time.');
}

/*
|--------------------------------------------------------------------------
| Validate opening hours
|--------------------------------------------------------------------------
*/

$openDateTime = new DateTimeImmutable(
    $bookingDate . ' ' .
    sprintf('%02d:00:00', $studioOpenHour)
);

$closeDateTime = new DateTimeImmutable(
    $bookingDate . ' ' .
    sprintf('%02d:00:00', $studioCloseHour)
);

if ($startDateTime < $openDateTime) {
    redirectBack(
        'The studio opens at ' .
        sprintf('%02d:00', $studioOpenHour) .
        '.'
    );
}

/*
|--------------------------------------------------------------------------
| Calculate end time
|--------------------------------------------------------------------------
*/

$endDateTime = $startDateTime->modify("+{$duration} hours");

/*
|--------------------------------------------------------------------------
| Cannot exceed closing time
|--------------------------------------------------------------------------
*/

if ($endDateTime > $closeDateTime) {
    redirectBack(
        'Your session would end after the studio closes at ' .
        sprintf('%02d:00', $studioCloseHour) .
        '. Please choose an earlier time or shorter duration.'
    );
}

/*
|--------------------------------------------------------------------------
| Cannot book in the past
|--------------------------------------------------------------------------
*/

if ($startDateTime <= new DateTimeImmutable()) {
    redirectBack(
        'Please choose a future booking time.'
    );
}

/*
|--------------------------------------------------------------------------
| Format time
|--------------------------------------------------------------------------
*/

$startTimeDb = $startDateTime->format('H:i:s');
$endTimeDb   = $endDateTime->format('H:i:s');

/*
|--------------------------------------------------------------------------
| Calculate total
|--------------------------------------------------------------------------
*/

$totalPrice = $pricePerHour * $duration;

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/

try {

    $pdo = getDbConnection();

    $pdo->beginTransaction();

    /*
    |--------------------------------------------------------------------------
    | Prevent simultaneous booking race conditions
    |--------------------------------------------------------------------------
    |
    | Lock all booking checks for the same date.
    |
    */

    $lock = $pdo->prepare("
        SELECT pg_advisory_xact_lock(
            hashtext(:lock_key)
        )
    ");

    $lock->execute([
        ':lock_key' => 'liminal_booking_' . $bookingDate
    ]);

    /*
    |--------------------------------------------------------------------------
    | Check overlapping bookings
    |--------------------------------------------------------------------------
    */

    $checkBooking = $pdo->prepare("
        SELECT
            id,
            booking_code
        FROM bookings
        WHERE booking_date = :booking_date
        AND status IN (
            'pending',
            'waiting_payment',
            'confirmed'
        )
        AND start_time < :new_end_time
        AND end_time > :new_start_time
        LIMIT 1
    ");

    $checkBooking->execute([
        ':booking_date'   => $bookingDate,
        ':new_start_time' => $startTimeDb,
        ':new_end_time'   => $endTimeDb,
    ]);

    $existingBooking = $checkBooking->fetch(PDO::FETCH_ASSOC);

    if ($existingBooking) {

        $pdo->rollBack();

        redirectBack(
            'This time slot is already booked. Please choose another time.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Generate booking code
    |--------------------------------------------------------------------------
    */

    $bookingCode =
        'LIM-' .
        $date->format('Ymd') .
        '-' .
        strtoupper(bin2hex(random_bytes(3)));

    /*
    |--------------------------------------------------------------------------
    | Insert booking
    |--------------------------------------------------------------------------
    */

    $insertBooking = $pdo->prepare("
        INSERT INTO bookings (
            booking_code,
            customer_name,
            phone,
            email,
            booking_date,
            start_time,
            end_time,
            duration,
            total_price,
            notes,
            status
        )
        VALUES (
            :booking_code,
            :customer_name,
            :phone,
            :email,
            :booking_date,
            :start_time,
            :end_time,
            :duration,
            :total_price,
            :notes,
            'waiting_payment'
        )
        RETURNING id
    ");

    $insertBooking->execute([
        ':booking_code'  => $bookingCode,
        ':customer_name' => $customerName,
        ':phone'         => $phone,
        ':email'         => $email,
        ':booking_date'  => $bookingDate,
        ':start_time'    => $startTimeDb,
        ':end_time'      => $endTimeDb,
        ':duration'      => $duration,
        ':total_price'   => $totalPrice,
        ':notes'         => $notes !== '' ? $notes : null,
    ]);

    $bookingId = (int) $insertBooking->fetchColumn();

    /*
    |--------------------------------------------------------------------------
    | Commit
    |--------------------------------------------------------------------------
    */

    $pdo->commit();

    /*
    |--------------------------------------------------------------------------
    | Save booking session
    |--------------------------------------------------------------------------
    */

    $_SESSION['booking_id']   = $bookingId;
    $_SESSION['booking_code'] = $bookingCode;

    /*
    |--------------------------------------------------------------------------
    | Redirect to payment
    |--------------------------------------------------------------------------
    */

    header(
        'Location: payment.php?booking=' .
        urlencode($bookingCode)
    );

    exit;

} catch (Throwable $e) {

    /*
    |--------------------------------------------------------------------------
    | Rollback
    |--------------------------------------------------------------------------
    */

    if (
        isset($pdo) &&
        $pdo instanceof PDO &&
        $pdo->inTransaction()
    ) {
        $pdo->rollBack();
    }

    /*
    |--------------------------------------------------------------------------
    | Log error
    |--------------------------------------------------------------------------
    */

    error_log(
        'Booking process error: ' .
        $e->getMessage()
    );

    /*
    |--------------------------------------------------------------------------
    | User-safe error
    |--------------------------------------------------------------------------
    */

    redirectBack(
        'Something went wrong while processing your booking. Please try again.'
    );
}