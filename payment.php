<?php

declare(strict_types=1);

session_start();

require __DIR__ . '/includes/functions.php';
require __DIR__ . '/includes/database.php';

$currentPage     = 'payment.php';
$pageTitle       = 'Payment — Liminal Studio';
$pageDescription = 'Complete your payment to confirm your studio session.';

/*
|--------------------------------------------------------------------------
| Get Booking Code
|--------------------------------------------------------------------------
|
| process_booking.php redirects to:
| payment.php?booking=LIM-XXXXXXXX-XXXXXX
|
*/

$bookingCode = trim((string) ($_GET['booking'] ?? ''));

if ($bookingCode === '') {
    $_SESSION['flash_error'] = 'Booking code is missing.';
    header('Location: booking.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/

try {
    $pdo = getDbConnection();
} catch (Throwable $e) {
    error_log('Payment database error: ' . $e->getMessage());

    $_SESSION['flash_error'] =
        'Unable to connect to the database. Please try again.';

    header('Location: booking.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Get Booking By Booking Code
|--------------------------------------------------------------------------
*/

try {
    $stmt = $pdo->prepare("
        SELECT
            id,
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
        FROM bookings
        WHERE booking_code = :booking_code
        LIMIT 1
    ");

    $stmt->execute([
        ':booking_code' => $bookingCode,
    ]);

    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (Throwable $e) {
    error_log('Get booking error: ' . $e->getMessage());

    $_SESSION['flash_error'] =
        'Unable to load your booking. Please try again.';

    header('Location: booking.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Booking Not Found
|--------------------------------------------------------------------------
*/

if (!$booking) {
    $_SESSION['flash_error'] = 'Booking not found.';
    header('Location: booking.php');
    exit;
}

$bookingId = (int) $booking['id'];

/*
|--------------------------------------------------------------------------
| Check Booking Status
|--------------------------------------------------------------------------
*/

if (
    isset($booking['status']) &&
    !in_array(
        $booking['status'],
        ['pending', 'waiting_payment'],
        true
    )
) {
    /*
     * If the booking is already confirmed or otherwise processed,
     * don't allow another payment submission.
     */

    if ($booking['status'] === 'confirmed') {
        header(
            'Location: confirmation.php?id=' .
            urlencode((string) $bookingId)
        );
        exit;
    }
}

/*
|--------------------------------------------------------------------------
| Check Existing Payment
|--------------------------------------------------------------------------
*/

$existingPayment = null;

try {
    $existingPayment = getLatestPaymentForBooking(
        $pdo,
        $bookingId
    );
} catch (Throwable $e) {
    /*
     * Do not crash payment.php if payment helper is unavailable.
     * The error is logged for debugging.
     */

    error_log(
        'Get existing payment error: ' .
        $e->getMessage()
    );
}

/*
|--------------------------------------------------------------------------
| Payment Already Submitted
|--------------------------------------------------------------------------
*/

if ($existingPayment) {
    header(
        'Location: confirmation.php?id=' .
        urlencode((string) $bookingId)
    );
    exit;
}

/*
|--------------------------------------------------------------------------
| Flash Error
|--------------------------------------------------------------------------
*/

$flashError = getFlashError();

/*
|--------------------------------------------------------------------------
| Page
|--------------------------------------------------------------------------
*/

require __DIR__ . '/includes/header.php';

?>

<main>

    <section class="relative overflow-hidden pt-40 pb-20 md:pt-48">

        <!-- Background Glow -->
        <div
            class="pointer-events-none absolute -top-40 left-1/2 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-violet-600/15 blur-[140px]"
        ></div>

        <div class="relative mx-auto max-w-[1000px] px-6">

            <!-- ==========================================================
                 HEADER
                 ========================================================== -->

            <div
                class="text-center"
                data-animate="fade-up"
            >

                <span
                    class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.03] px-4 py-1.5 text-xs font-semibold tracking-wide text-violet-300"
                >
                    <i class="fa-solid fa-credit-card"></i>
                    PAYMENT
                </span>

                <h1
                    class="mt-6 font-display text-3xl font-extrabold tracking-tight text-white sm:text-4xl md:text-5xl"
                >
                    Complete Your Payment
                </h1>

                <p
                    class="mx-auto mt-4 max-w-lg text-base text-zinc-400 md:text-lg"
                >
                    Almost there — confirm your session by completing payment below.
                </p>

            </div>

            <!-- ==========================================================
                 FLASH ERROR
                 ========================================================== -->

            <?php if ($flashError): ?>

                <div
                    class="mx-auto mt-8 max-w-lg rounded-xl border border-red-500/20 bg-red-500/[0.06] px-4 py-3 text-center text-sm text-red-300"
                    data-animate="fade-up"
                >

                    <i class="fa-solid fa-circle-exclamation mr-1.5"></i>

                    <?= e($flashError) ?>

                </div>

            <?php endif; ?>


            <!-- ==========================================================
                 CONTENT
                 ========================================================== -->

            <div
                class="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-[1fr_1.2fr]"
            >

                <!-- ======================================================
                     BOOKING DETAILS
                     ====================================================== -->

                <div
                    class="h-fit rounded-3xl border border-white/10 bg-card/80 p-7 shadow-glow-sm backdrop-blur-xl"
                    data-animate="fade-up"
                >

                    <p
                        class="text-xs font-semibold uppercase tracking-wider text-violet-300"
                    >
                        Booking ID
                    </p>

                    <p
                        class="mt-1.5 font-display text-xl font-extrabold text-white"
                    >
                        #<?= e($booking['booking_code']) ?>
                    </p>


                    <!-- CUSTOMER INFORMATION -->

                    <div
                        class="mt-6 space-y-4 border-t border-white/10 pt-6"
                    >

                        <!-- Name -->
                        <div
                            class="flex items-center justify-between gap-4 text-sm"
                        >

                            <span
                                class="flex items-center gap-2 text-ink-secondary"
                            >
                                <i class="fa-solid fa-user w-4"></i>
                                Name
                            </span>

                            <span
                                class="text-right font-medium text-white"
                            >
                                <?= e($booking['customer_name']) ?>
                            </span>

                        </div>


                        <!-- Email -->
                        <div
                            class="flex items-center justify-between gap-4 text-sm"
                        >

                            <span
                                class="flex items-center gap-2 text-ink-secondary"
                            >
                                <i class="fa-solid fa-envelope w-4"></i>
                                Email
                            </span>

                            <span
                                class="max-w-[220px] truncate text-right font-medium text-white"
                            >
                                <?= e($booking['email']) ?>
                            </span>

                        </div>


                        <!-- Phone -->
                        <div
                            class="flex items-center justify-between gap-4 text-sm"
                        >

                            <span
                                class="flex items-center gap-2 text-ink-secondary"
                            >
                                <i class="fa-solid fa-phone w-4"></i>
                                Phone
                            </span>

                            <span
                                class="text-right font-medium text-white"
                            >
                                <?= e($booking['phone']) ?>
                            </span>

                        </div>

                    </div>


                    <!-- BOOKING INFORMATION -->

                    <div
                        class="mt-6 space-y-4 border-t border-white/10 pt-6"
                    >

                        <!-- Date -->
                        <div
                            class="flex items-center justify-between gap-4 text-sm"
                        >

                            <span
                                class="flex items-center gap-2 text-ink-secondary"
                            >
                                <i class="fa-solid fa-calendar-days w-4"></i>
                                Date
                            </span>

                            <span
                                class="text-right font-medium text-white"
                            >
                                <?= e(
                                    formatDateLong(
                                        $booking['booking_date']
                                    )
                                ) ?>
                            </span>

                        </div>


                        <!-- Time -->
                        <div
                            class="flex items-center justify-between gap-4 text-sm"
                        >

                            <span
                                class="flex items-center gap-2 text-ink-secondary"
                            >
                                <i class="fa-solid fa-clock w-4"></i>
                                Time
                            </span>

                            <span
                                class="text-right font-medium text-white"
                            >
                                <?= e(
                                    substr(
                                        $booking['start_time'],
                                        0,
                                        5
                                    )
                                ) ?>

                                —

                                <?= e(
                                    substr(
                                        $booking['end_time'],
                                        0,
                                        5
                                    )
                                ) ?>
                            </span>

                        </div>


                        <!-- Duration -->
                        <div
                            class="flex items-center justify-between gap-4 text-sm"
                        >

                            <span
                                class="flex items-center gap-2 text-ink-secondary"
                            >
                                <i class="fa-solid fa-hourglass-half w-4"></i>
                                Duration
                            </span>

                            <span
                                class="font-medium text-white"
                            >

                                <?= (int) $booking['duration'] ?>

                                Hour<?= (int) $booking['duration'] > 1 ? 's' : '' ?>

                            </span>

                        </div>

                    </div>


                    <!-- TOTAL -->

                    <div
                        class="mt-6 flex items-center justify-between border-t border-white/10 pt-5"
                    >

                        <span
                            class="text-sm font-semibold text-white"
                        >
                            Total
                        </span>

                        <span
                            class="font-display text-2xl font-extrabold text-white"
                        >
                            <?= e(
                                formatRupiah(
                                    (int) $booking['total_price']
                                )
                            ) ?>
                        </span>

                    </div>

                </div>


                <!-- ======================================================
                     PAYMENT FORM
                     ====================================================== -->

                <div
                    class="rounded-3xl border border-white/10 bg-white/[0.02] p-7 md:p-9"
                    data-animate="fade-up"
                    data-delay="120"
                >

                    <form
                        id="payment-form"
                        action="process_payment.php"
                        method="POST"
                        enctype="multipart/form-data"
                        novalidate
                    >

                        <!-- CSRF -->
                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= e(csrfToken()) ?>"
                        >


                        <!-- BOOKING ID -->
                        <input
                            type="hidden"
                            name="booking_id"
                            value="<?= $bookingId ?>"
                        >


                        <!-- BOOKING CODE -->
                        <input
                            type="hidden"
                            name="booking_code"
                            value="<?= e($booking['booking_code']) ?>"
                        >


                        <!-- ==================================================
                             PAYMENT METHOD
                             ================================================== -->

                        <p class="text-sm font-medium text-white">
                            Payment Method
                        </p>

                        <div class="mt-3 grid grid-cols-2 gap-3">

                            <!-- BANK TRANSFER -->

                            <label
                                class="payment-method-opt cursor-pointer rounded-xl border border-violet-500/40 bg-violet-500/[0.06] px-4 py-3.5 text-center transition-all"
                            >

                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="Bank Transfer"
                                    class="sr-only"
                                    checked
                                >

                                <i
                                    class="fa-solid fa-building-columns text-violet-300"
                                ></i>

                                <p
                                    class="mt-1.5 text-sm font-medium text-white"
                                >
                                    Bank Transfer
                                </p>

                            </label>


                            <!-- QRIS -->

                            <label
                                class="payment-method-opt cursor-pointer rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3.5 text-center transition-all"
                            >

                                <input
                                    type="radio"
                                    name="payment_method"
                                    value="QRIS"
                                    class="sr-only"
                                >

                                <i
                                    class="fa-solid fa-qrcode text-violet-300"
                                ></i>

                                <p
                                    class="mt-1.5 text-sm font-medium text-white"
                                >
                                    QRIS
                                </p>

                            </label>

                        </div>


                        <!-- ==================================================
                             BANK DETAILS
                             ================================================== -->

                        <div
                            id="bank-details"
                            class="mt-5 rounded-xl border border-white/10 bg-white/[0.02] p-5"
                        >

                            <div
                                class="flex items-center justify-between text-sm"
                            >

                                <span class="text-ink-secondary">
                                    Bank
                                </span>

                                <span class="font-medium text-white">
                                    BCA
                                </span>

                            </div>


                            <div
                                class="mt-2 flex items-center justify-between text-sm"
                            >

                                <span class="text-ink-secondary">
                                    Account Number
                                </span>

                                <span class="font-medium text-white">
                                    1234567890
                                </span>

                            </div>


                            <div
                                class="mt-2 flex items-center justify-between text-sm"
                            >

                                <span class="text-ink-secondary">
                                    Account Name
                                </span>

                                <span class="font-medium text-white">
                                    Liminal Studio
                                </span>

                            </div>

                        </div>


                        <!-- ==================================================
                             QRIS
                             ================================================== -->

                        <div
                            id="qris-details"
                            class="mt-5 hidden rounded-xl border border-white/10 bg-white/[0.02] p-5 text-center"
                        >

                            <div
                                class="mx-auto flex h-40 w-40 items-center justify-center rounded-lg bg-white/[0.04] text-zinc-600"
                            >

                                <img src="assets/images/qris.png" alt="" class="h-full w-full rounded">

                            </div>

                            <p
                                class="mt-3 text-xs text-ink-secondary"
                            >
                                Scan with any QRIS-supported e-wallet or banking app.
                            </p>

                        </div>


                        <!-- ==================================================
                             PAYMENT PROOF
                             ================================================== -->

                        <div class="mt-6">

                            <label
                                for="proof"
                                class="block text-sm font-medium text-white"
                            >
                                Payment Proof
                            </label>

                            <label
                                for="proof"
                                class="mt-2 flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border border-dashed border-white/15 bg-white/[0.02] px-4 py-8 text-center transition-colors hover:border-violet-500/40"
                            >

                                <i
                                    class="fa-solid fa-cloud-arrow-up text-2xl text-violet-400"
                                ></i>

                                <span
                                    id="proof-filename"
                                    class="text-sm text-ink-secondary"
                                >
                                    Click to upload JPG, PNG, or PDF (max 5MB)
                                </span>

                            </label>


                            <input
                                type="file"
                                id="proof"
                                name="proof"
                                accept=".jpg,.jpeg,.png,.pdf"
                                required
                                class="sr-only"
                            >


                            <p
                                id="proof-error"
                                class="mt-1.5 hidden text-xs text-red-400"
                            ></p>

                        </div>


                        <!-- ==================================================
                             SUBMIT
                             ================================================== -->

                        <button
                            type="submit"
                            class="mt-7 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-purple-500 via-violet-500 to-indigo-500 px-6 py-3.5 text-sm font-semibold text-white shadow-glow-sm transition-transform hover:scale-[1.01]"
                        >

                            Confirm Payment

                            <i
                                class="fa-solid fa-circle-check text-xs"
                            ></i>

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </section>

</main>


<!-- ==============================================================
     PAYMENT METHOD SCRIPT
     ============================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const paymentOptions =
        document.querySelectorAll('.payment-method-opt');

    const bankDetails =
        document.getElementById('bank-details');

    const qrisDetails =
        document.getElementById('qris-details');

    const proofInput =
        document.getElementById('proof');

    const proofFilename =
        document.getElementById('proof-filename');

    const paymentForm =
        document.getElementById('payment-form');

    const proofError =
        document.getElementById('proof-error');


    /*
    |--------------------------------------------------------------------------
    | Payment Method
    |--------------------------------------------------------------------------
    */

    function updatePaymentMethod() {

        const selected =
            document.querySelector(
                'input[name="payment_method"]:checked'
            );

        if (!selected) {
            return;
        }

        paymentOptions.forEach(function (option) {

            const radio =
                option.querySelector('input[type="radio"]');

            if (radio && radio.checked) {

                option.classList.remove(
                    'border-white/10',
                    'bg-white/[0.02]'
                );

                option.classList.add(
                    'border-violet-500/40',
                    'bg-violet-500/[0.06]'
                );

            } else {

                option.classList.remove(
                    'border-violet-500/40',
                    'bg-violet-500/[0.06]'
                );

                option.classList.add(
                    'border-white/10',
                    'bg-white/[0.02]'
                );

            }

        });


        if (selected.value === 'QRIS') {

            bankDetails.classList.add('hidden');
            qrisDetails.classList.remove('hidden');

        } else {

            bankDetails.classList.remove('hidden');
            qrisDetails.classList.add('hidden');

        }

    }


    paymentOptions.forEach(function (option) {

        const radio =
            option.querySelector('input[type="radio"]');

        if (radio) {

            radio.addEventListener(
                'change',
                updatePaymentMethod
            );

        }

    });


    updatePaymentMethod();


    /*
    |--------------------------------------------------------------------------
    | File Name
    |--------------------------------------------------------------------------
    */

    if (proofInput) {

        proofInput.addEventListener(
            'change',
            function () {

                if (
                    this.files &&
                    this.files.length > 0
                ) {

                    proofFilename.textContent =
                        this.files[0].name;

                    proofFilename.classList.remove(
                        'text-ink-secondary'
                    );

                    proofFilename.classList.add(
                        'text-white'
                    );

                } else {

                    proofFilename.textContent =
                        'Click to upload JPG, PNG, or PDF (max 5MB)';

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Client-side File Validation
    |--------------------------------------------------------------------------
    */

    if (paymentForm) {

        paymentForm.addEventListener(
            'submit',
            function (event) {

                if (!proofInput) {
                    return;
                }

                proofError.classList.add('hidden');
                proofError.textContent = '';

                if (
                    !proofInput.files ||
                    proofInput.files.length === 0
                ) {

                    event.preventDefault();

                    proofError.textContent =
                        'Please upload your payment proof.';

                    proofError.classList.remove(
                        'hidden'
                    );

                    return;
                }


                const file =
                    proofInput.files[0];

                const maxSize =
                    5 * 1024 * 1024;

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'application/pdf'
                ];


                if (file.size > maxSize) {

                    event.preventDefault();

                    proofError.textContent =
                        'File size must not exceed 5MB.';

                    proofError.classList.remove(
                        'hidden'
                    );

                    return;
                }


                if (
                    file.type &&
                    !allowedTypes.includes(file.type)
                ) {

                    event.preventDefault();

                    proofError.textContent =
                        'Only JPG, PNG, or PDF files are allowed.';

                    proofError.classList.remove(
                        'hidden'
                    );

                    return;
                }

            }
        );

    }

});

</script>


<?php

require __DIR__ . '/includes/footer.php';

?>