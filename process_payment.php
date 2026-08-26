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
| Balik ke payment.php pakai booking_code, BUKAN id
|--------------------------------------------------------------------------
|
| payment.php cuma paham parameter ?booking=KODE (lihat baris
| "$bookingCode = trim((string) ($_GET['booking'] ?? ''));" di sana).
| Kalau di sini kita redirect pakai ?id=..., payment.php akan
| menganggap booking code-nya kosong dan melempar user balik ke
| booking.php — makanya sebelumnya selalu muncul "Booking code is
| missing." Jadi simpan URL retry-nya di satu variabel di sini,
| supaya dipakai konsisten di semua redirect error di bawah.
|
*/

$paymentRetryUrl = 'payment.php?booking=' . urlencode((string) $booking['booking_code']);

if (!in_array(
    $paymentMethod,
    ['Bank Transfer', 'QRIS'],
    true
)) {
    redirectWithError(
        $paymentRetryUrl,
        'Please select a payment method.'
    );
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
        $paymentRetryUrl,
        'Please upload your payment proof.'
    );
}

if (
    $_FILES['proof']['error'] !== UPLOAD_ERR_OK
) {
    /*
    |--------------------------------------------------------------------------
    | File terlalu besar untuk php.ini (bukan cuma "lupa upload")
    |--------------------------------------------------------------------------
    |
    | UPLOAD_ERR_INI_SIZE / UPLOAD_ERR_FORM_SIZE artinya PHP sendiri
    | (bukan validasi kita di UPLOAD_MAX_BYTES) yang menolak filenya
    | sebelum sempat sampai ke kode kita — biasanya karena
    | upload_max_filesize atau post_max_size di php.ini server lebih
    | kecil dari 5MB yang kita janjikan di UI. Ini penyebab paling
    | umum kalau user bilang "sudah upload PNG tapi tetap gagal":
    | filenya valid, cuma kelewat besar buat batas server.
    */
    $phpUploadErrorCode = $_FILES['proof']['error'];
    error_log('Payment proof upload rejected by PHP before validation: error code ' . $phpUploadErrorCode);

    $message = in_array($phpUploadErrorCode, [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)
        ? 'Your file is too large for this server to accept. Please use a smaller image (under 5MB).'
        : 'Please upload your payment proof.';

    redirectWithError(
        $paymentRetryUrl,
        $message
    );
}

/*
|--------------------------------------------------------------------------
| Upload Payment Proof
|--------------------------------------------------------------------------
*/

$storedFilename = handleProofUpload(
    $_FILES['proof'],
    $uploadFailReason
);

if ($storedFilename === null) {
    error_log('Payment proof upload failed: ' . ($uploadFailReason ?? 'unknown'));

    redirectWithError(
        $paymentRetryUrl,
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
        $paymentRetryUrl,
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