<?php
declare(strict_types=1);
require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/database.php';

$currentPage      = 'confirmation.php';
$pageTitle        = 'Booking Confirmed — Liminal Studio';
$pageDescription  = 'Your studio session has been booked.';

$bookingId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$pdo       = getDbConnection();
$booking   = $bookingId > 0 ? getBookingById($pdo, $bookingId) : null;

if (!$booking) {
    header('Location: booking.php');
    exit;
}

$payment = getLatestPaymentForBooking($pdo, $bookingId);

$statusCopy = [
    'pending'  => ['label' => 'Pending Review', 'text' => 'Payment is being reviewed.', 'color' => 'amber'],
    'approved' => ['label' => 'Confirmed', 'text' => 'Payment confirmed.', 'color' => 'emerald'],
    'rejected' => ['label' => 'Rejected', 'text' => 'Payment rejected. Please contact the studio.', 'color' => 'red'],
];
$paymentStatus = $payment['status'] ?? 'pending';
$statusInfo    = $statusCopy[$paymentStatus] ?? $statusCopy['pending'];

require __DIR__ . '/includes/header.php';
?>

<main>
  <section class="relative overflow-hidden pt-40 pb-24 md:pt-48">
    <div class="pointer-events-none absolute -top-40 left-1/2 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-emerald-500/10 blur-[140px]"></div>

    <div class="relative mx-auto max-w-[640px] px-6 text-center" data-animate="fade-up">
      <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10 text-3xl text-emerald-400">
        <i class="fa-solid fa-circle-check"></i>
      </span>
      <h1 class="mt-6 font-display text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Booking Confirmed</h1>
      <p class="mt-3 text-base text-zinc-400">Your session has been successfully booked.</p>


      <a href="resi.php?id=<?= (int) $booking['id'] ?>" class="mt-8 inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/[0.03] px-6 py-3.5 text-sm font-semibold text-white hover:bg-white/[0.06] transition-colors">
       <i class="fa-solid fa-download"></i> Download PDF
      </a>

      <div class="mt-10 rounded-3xl border border-white/10 bg-white/[0.02] p-7 text-left md:p-9">
        <div class="flex items-center justify-between">
          <p class="text-xs font-semibold uppercase tracking-wider text-violet-300">Booking ID</p>
          <span class="rounded-full bg-<?= e($statusInfo['color']) ?>-400/10 px-3 py-1 text-xs font-medium text-<?= e($statusInfo['color']) ?>-400"><?= e($statusInfo['label']) ?></span>
        </div>
        <p class="mt-1.5 font-display text-xl font-extrabold text-white">#<?= e($booking['booking_code']) ?></p>

        <div class="mt-6 space-y-4 border-t border-white/10 pt-6">
          <div class="flex items-center justify-between text-sm">
            <span class="flex items-center gap-2 text-ink-secondary"><i class="fa-solid fa-user w-4"></i>Name</span>
            <span class="font-medium text-white"><?= e($booking['customer_name']) ?></span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <span class="flex items-center gap-2 text-ink-secondary"><i class="fa-solid fa-calendar-days w-4"></i>Date</span>
            <span class="font-medium text-white"><?= e(formatDateLong($booking['booking_date'])) ?></span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <span class="flex items-center gap-2 text-ink-secondary"><i class="fa-solid fa-clock w-4"></i>Time</span>
            <span class="font-medium text-white"><?= e(substr($booking['start_time'], 0, 5)) ?> — <?= e(substr($booking['end_time'], 0, 5)) ?></span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <span class="flex items-center gap-2 text-ink-secondary"><i class="fa-solid fa-hourglass-half w-4"></i>Duration</span>
            <span class="font-medium text-white"><?= (int) $booking['duration'] ?> Hour<?= (int) $booking['duration'] > 1 ? 's' : '' ?></span>
          </div>
          <div class="flex items-center justify-between text-sm">
            <span class="flex items-center gap-2 text-ink-secondary"><i class="fa-solid fa-credit-card w-4"></i>Payment</span>
            <span class="font-medium text-white"><?= e($statusInfo['label']) ?></span>
          </div>
        </div>

        <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-5">
          <span class="text-sm font-semibold text-white">Total</span>
          <span class="font-display text-2xl font-extrabold text-white"><?= e(formatRupiah((int) $booking['total_price'])) ?></span>
        </div>

        <div class="mt-6 rounded-xl border border-<?= e($statusInfo['color']) ?>-500/20 bg-<?= e($statusInfo['color']) ?>-500/[0.06] px-4 py-3 text-sm text-<?= e($statusInfo['color']) ?>-300">
          <i class="fa-solid fa-circle-info mr-1.5"></i><?= e($statusInfo['text']) ?>
        </div>
      </div>

      <a href="index.php" class="mt-8 inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/[0.03] px-6 py-3.5 text-sm font-semibold text-white hover:bg-white/[0.06] transition-colors">
        <i class="fa-solid fa-house text-xs"></i> Back to Home
      </a>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; 

?>
