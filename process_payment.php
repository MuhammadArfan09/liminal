<?php

declare(strict_types=1);

require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: booking.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
    redirectWithError(
        'booking.php',
        'Your session expired. Please try again.'
    );
}

/*
|--------------------------------------------------------------------------
| Get Form Data
|--------------------------------------------------------------------------
*/

$bookingId = (int) ($_POST['booking_id'] ?? 0);

$paymentMethod = trim(
    (string) ($_POST['payment_method'] ?? '')
);

if ($bookingId <= 0) {
    redirectWithError(
        'booking.php',
        'Invalid booking.'
    );
}

if (!in_array(
    $paymentMethod,
    ['Bank Transfer', 'QRIS'],
    true
)) {
    redirectWithError(
        'payment.php?id=' . $bookingId,
        'Please select a payment method.'
    );
}

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/

try {
    $pdo = getDbConnection();

    $booking = getBookingById(
        $pdo,
        $bookingId
    );

} catch (Throwable $e) {

    error_log(
        'Database error: ' .
        $e->getMessage()
    );

    redirectWithError(
        'booking.php',
        'Unable to connect to the database.'
    );
}

/*
|--------------------------------------------------------------------------
| Booking Not Found
|--------------------------------------------------------------------------
*/

if (!$booking) {
    header('Location: booking.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Payment Proof
|--------------------------------------------------------------------------
*/

if (
    !isset($_FILES['proof']) ||
    !is_array($_FILES['proof'])
) {
    redirectWithError(
        'payment.php?id=' . $bookingId,
        'Please upload your payment proof.'
    );
}

if (
    $_FILES['proof']['error'] !== UPLOAD_ERR_OK
) {
    redirectWithError(
        'payment.php?id=' . $bookingId,
        'Please upload your payment proof.'
    );
}

/*
|--------------------------------------------------------------------------
| Upload Payment Proof
|--------------------------------------------------------------------------
*/

$storedFilename = handleProofUpload(
    $_FILES['proof']
);

if ($storedFilename === null) {
    redirectWithError(
        'payment.php?id=' . $bookingId,
        'Upload failed. Please use a JPG, PNG, or PDF under 5MB.'
    );
}

/*
|--------------------------------------------------------------------------
| Save Payment
|--------------------------------------------------------------------------
*/

try {

    $pdo->beginTransaction();

    /*
    |--------------------------------------------------------------------------
    | Insert Payment
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        INSERT INTO payments (
            booking_id,
            payment_method,
            amount,
            proof,
            status,
            created_at,
            updated_at
        )
        VALUES (
            :booking_id,
            :payment_method,
            :amount,
            :proof,
            'pending',
            NOW(),
            NOW()
        )
    ");

    $stmt->execute([
        ':booking_id' =>
            $bookingId,

        ':payment_method' =>
            $paymentMethod,

        ':amount' =>
            (int) $booking['total_price'],

        ':proof' =>
            $storedFilename,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Update Booking Status
    |--------------------------------------------------------------------------
    */

    $update = $pdo->prepare("
        UPDATE bookings
        SET
            status = 'waiting_payment',
            updated_at = NOW()
        WHERE id = :id
    ");

    $update->execute([
        ':id' => $bookingId,
    ]);

    /*
    |--------------------------------------------------------------------------
    | Commit
    |--------------------------------------------------------------------------
    */

    $pdo->commit();

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Uploaded File
    |--------------------------------------------------------------------------
    |
    | Kalau database gagal menyimpan payment,
    | hapus file yang tadi sudah di-upload supaya
    | tidak meninggalkan file sampah.
    |
    */

    if (
        $storedFilename !== null
    ) {
        $possiblePaths = [
            __DIR__ . '/uploads/payment/' . $storedFilename,
            __DIR__ . '/uploads/' . $storedFilename,
        ];

        foreach ($possiblePaths as $filePath) {
            if (is_file($filePath)) {
                @unlink($filePath);
            }
        }
    }

    error_log(
        'Payment recording failed: ' .
        $e->getMessage()
    );

    redirectWithError(
        'payment.php?id=' . $bookingId,
        'Something went wrong while recording your payment. Please try again.'
    );
}

/*
|--------------------------------------------------------------------------
| Success
|--------------------------------------------------------------------------
*/

header(
    'Location: confirmation.php?id=' .
    $bookingId
);

exit;