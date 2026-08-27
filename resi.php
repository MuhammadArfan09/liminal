<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/database.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$bookingId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$pdo       = getDbConnection();
$booking   = $bookingId > 0 ? getBookingById($pdo, $bookingId) : null;

if (!$booking) {
    die('Booking tidak ditemukan.');
}
$payment = getLatestPaymentForBooking($pdo, $bookingId);

$statusCopy = [
    'pending'  => 'Menunggu Verifikasi',
    'approved' => 'Lunas / Terkonfirmasi',
    'rejected' => 'Ditolak',
];
$paymentStatus = $payment['status'] ?? 'pending';
$statusLabel   = $statusCopy[$paymentStatus] ?? $statusCopy['pending'];

$bookingCode = e($booking['booking_code']);
$customer    = e($booking['customer_name']);
$phone       = e($booking['phone'] ?? '-');
$date        = e(formatDateLong($booking['booking_date']));
$startTime   = e(substr($booking['start_time'], 0, 5));
$endTime     = e(substr($booking['end_time'], 0, 5));
$duration    = (int) $booking['duration'];
$total       = e(formatRupiah((int) $booking['total_price']));
$method      = e($payment['payment_method'] ?? '-');
$issuedAt    = date('d F Y, H:i') . ' WIB';

$html = <<<HTML
<html>
<head>
<meta charset="UTF-8">
</head>
<body>

    <h1>LIMINAL STUDIO</h1>
    <p>Studio Latihan Band Profesional</p>

    <h2>RESI</h2>
    <p>No. Booking: {$bookingCode}</p>
    <p>Status: {$statusLabel}</p>

    <hr>

    <h3>Detail Pemesan</h3>
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <tr>
            <td>Nama</td>
            <td>{$customer}</td>
        </tr>
        <tr>
            <td>Nomor WhatsApp</td>
            <td>{$phone}</td>
        </tr>
    </table>

    <h3>Detail Sesi</h3>
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <tr>
            <td>Tanggal Booking</td>
            <td>{$date}</td>
        </tr>
        <tr>
            <td>Jam Sesi</td>
            <td>{$startTime} - {$endTime}</td>
        </tr>
        <tr>
            <td>Durasi</td>
            <td>{$duration} Jam</td>
        </tr>
    </table>

    <h3>Detail Pembayaran</h3>
    <table border="1" cellpadding="6" cellspacing="0" width="100%">
        <tr>
            <td>Metode Pembayaran</td>
            <td>{$method}</td>
        </tr>
        <tr>
            <td>Status</td>
            <td>{$statusLabel}</td>
        </tr>
        <tr>
            <td>Total Pembayaran</td>
            <td>{$total}</td>
        </tr>
    </table>

    <p>Resi ini dibuat otomatis oleh sistem pada {$issuedAt}.</p>
    <p>Simpan resi ini sebagai bukti booking. Untuk pertanyaan, hubungi tim Liminal Studio melalui halaman Kontak.</p>

</body>
</html>
HTML;

$options = new Options();
$options->set('isRemoteEnabled', false);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A6', 'portrait');
$dompdf->render();

$filename = 'Resi-' . $booking['booking_code'] . '.pdf';

$dompdf->stream($filename, ['Attachment' => true]);