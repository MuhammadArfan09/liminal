<?php

declare(strict_types=1);

$currentPage = 'booking.php';

$pageTitle = 'Reserve Your Session — Liminal Studio';

$pageDescription = "Choose your date and time. We'll take care of the rest.";

require __DIR__ . '/includes/header.php';


$flashError = getFlashError();

$studioOpenHour  = defined('STUDIO_OPEN_HOUR') ? STUDIO_OPEN_HOUR : 10;
$studioCloseHour = defined('STUDIO_CLOSE_HOUR') ? STUDIO_CLOSE_HOUR : 23;
$pricePerHour    = 75000;

$presetDuration = isset($_GET['duration'])
    ? max(1, min(4, (int) $_GET['duration']))
    : 1;

/*
|--------------------------------------------------------------------------
| Generate available starting hours
|--------------------------------------------------------------------------
| Example:
| 10:00, 11:00, 12:00 ... 22:00
*/
$startOptions = [];

for ($hour = $studioOpenHour; $hour < $studioCloseHour; $hour++) {
    $startOptions[] = sprintf('%02d:00', $hour);
}

$today = date('Y-m-d');

?>

<main>

    <!-- HERO -->
    <section class="relative overflow-hidden pt-40 pb-20 md:pt-48 mt-1" >

        <div
            class="pointer-events-none absolute -top-40 left-1/2 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-violet-600/15 blur-[140px]"
        ></div>

        <div class="relative mx-auto max-w-[1280px] px-6">

            <!-- HEADER -->
            <div
                class="text-center"
                data-animate="fade-up"
            >

                <span
                    class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.03] px-4 py-1.5 text-xs font-semibold tracking-wide text-violet-300"
                >
                    <i class="fa-solid fa-calendar-check"></i>
                    BOOKING
                </span>

                <h1
                    class="mt-6 font-display text-3xl font-extrabold tracking-tight text-white sm:text-4xl md:text-5xl"
                >
                    Reserve Your Session
                </h1>

                <p
                    class="mx-auto mt-4 max-w-lg text-base text-zinc-400 md:text-lg"
                >
                    Choose your date and time. We'll take care of the rest.
                </p>

            </div>


            <!-- FLASH ERROR -->
            <?php if ($flashError): ?>

                <div
                    class="mx-auto mt-8 max-w-lg rounded-xl border border-red-500/20 bg-red-500/[0.06] px-4 py-3 text-center text-sm text-red-300"
                    data-animate="fade-up"
                >
                    <i class="fa-solid fa-circle-exclamation mr-1.5"></i>
                    <?= e($flashError) ?>
                </div>

            <?php endif; ?>


            <!-- CONTENT -->
            <div class="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-[1.3fr_0.7fr]">

                <!-- =====================================================
                     BOOKING FORM
                ====================================================== -->
                <div
                    class="rounded-3xl border border-white/10 bg-white/[0.02] p-7 md:p-9"
                    data-animate="fade-up"
                >

                    <form
                        id="booking-form"
                        action="process_booking.php"
                        method="POST"
                        novalidate
                    >

                        <!-- CSRF -->
                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= e(csrfToken()) ?>"
                        >

                        <!-- =========================
                             CUSTOMER INFORMATION
                        ========================== -->
                        <div class="mb-8">

                            <div class="mb-5">
                                <h2 class="text-lg font-semibold text-white">
                                    Customer Information
                                </h2>

                                <p class="mt-1 text-sm text-zinc-500">
                                    Enter your contact details for the booking confirmation.
                                </p>
                            </div>


                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                                <!-- NAME -->
                                <div class="sm:col-span-2">

                                    <label
                                        for="customer_name"
                                        class="block text-sm font-medium text-white"
                                    >
                                        Full Name
                                    </label>

                                    <input
                                        type="text"
                                        id="customer_name"
                                        name="customer_name"
                                        required
                                        autocomplete="name"
                                        maxlength="100"
                                        placeholder="e.g. Arfan Pratama"
                                        class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-white placeholder-zinc-600 outline-none transition focus:border-violet-500/50 focus:ring-2 focus:ring-violet-500/20"
                                    >

                                    <p class="error-message mt-1.5 hidden text-xs text-red-400"></p>

                                </div>


                                <!-- PHONE -->
                                <div>

                                    <label
                                        for="phone"
                                        class="block text-sm font-medium text-white"
                                    >
                                        WhatsApp Number
                                    </label>

                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        required
                                        autocomplete="tel"
                                        maxlength="20"
                                        placeholder="08xx-xxxx-xxxx"
                                        class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-white placeholder-zinc-600 outline-none transition focus:border-violet-500/50 focus:ring-2 focus:ring-violet-500/20"
                                    >

                                    <p class="error-message mt-1.5 hidden text-xs text-red-400"></p>

                                </div>


                                <!-- EMAIL -->
                                <div>

                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-white"
                                    >
                                        Email
                                    </label>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        required
                                        autocomplete="email"
                                        maxlength="150"
                                        placeholder="you@email.com"
                                        class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-white placeholder-zinc-600 outline-none transition focus:border-violet-500/50 focus:ring-2 focus:ring-violet-500/20"
                                    >

                                    <p class="error-message mt-1.5 hidden text-xs text-red-400"></p>

                                </div>

                            </div>

                        </div>


                        <!-- =========================
                             SESSION DETAILS
                        ========================== -->
                        <div>

                            <div class="mb-5">
                                <h2 class="text-lg font-semibold text-white">
                                    Session Details
                                </h2>

                                <p class="mt-1 text-sm text-zinc-500">
                                    Choose when you want to use the studio.
                                </p>
                            </div>


                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                                <!-- DATE -->
                                <div>

                                    <label
                                        for="booking_date"
                                        class="block text-sm font-medium text-white"
                                    >
                                        Booking Date
                                    </label>

                                    <input
                                        type="date"
                                        id="booking_date"
                                        name="booking_date"
                                        required
                                        min="<?= e($today) ?>"
                                        class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-white outline-none transition focus:border-violet-500/50 focus:ring-2 focus:ring-violet-500/20"
                                    >

                                    <p class="error-message mt-1.5 hidden text-xs text-red-400"></p>

                                </div>


                                <!-- START TIME -->
                                <div>

                                    <label
                                        for="start_time"
                                        class="block text-sm font-medium text-white"
                                    >
                                        Start Time
                                    </label>

                                    <select
                                        id="start_time"
                                        name="start_time"
                                        required
                                        class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-white outline-none transition focus:border-violet-500/50 focus:ring-2 focus:ring-violet-500/20"
                                    >

                                        <option value="" selected disabled>
                                            Select a time
                                        </option>

                                        <?php foreach ($startOptions as $time): ?>

                                            <option value="<?= e($time) ?>">
                                                <?= e($time) ?>
                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <p
                                        id="availability-hint"
                                        class="mt-1.5 text-xs text-zinc-500"
                                    >
                                        Studio open
                                        <?= sprintf('%02d:00', $studioOpenHour) ?>
                                        —
                                        <?= sprintf('%02d:00', $studioCloseHour) ?>.
                                    </p>

                                </div>


                                <!-- DURATION -->
                                <div class="sm:col-span-2">

                                    <label class="block text-sm font-medium text-white">
                                        Duration
                                    </label>

                                    <div
                                        id="duration-select"
                                        class="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-4"
                                    >

                                        <?php for ($hours = 1; $hours <= 4; $hours++): ?>

                                            <button
                                                type="button"
                                                data-hours="<?= $hours ?>"
                                                class="duration-opt rounded-xl border border-white/10 bg-white/[0.02] py-3 text-sm font-medium text-zinc-300 transition-all hover:border-violet-500/40 hover:bg-violet-500/5 hover:text-white"
                                            >
                                                <?= $hours ?> Hour<?= $hours > 1 ? 's' : '' ?>
                                            </button>

                                        <?php endfor; ?>

                                    </div>

                                    <input
                                        type="hidden"
                                        id="duration"
                                        name="duration"
                                        value="<?= e((string) $presetDuration) ?>"
                                    >

                                    <p
                                        id="duration-error"
                                        class="mt-1.5 hidden text-xs text-red-400"
                                    ></p>

                                </div>


                                <!-- NOTES -->
                                <div class="sm:col-span-2">

                                    <label
                                        for="notes"
                                        class="block text-sm font-medium text-white"
                                    >
                                        Notes
                                        <span class="font-normal text-zinc-500">
                                            (optional)
                                        </span>
                                    </label>

                                    <textarea
                                        id="notes"
                                        name="notes"
                                        rows="3"
                                        maxlength="500"
                                        placeholder="Anything we should know before your session?"
                                        class="mt-2 w-full resize-none rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-white placeholder-zinc-600 outline-none transition focus:border-violet-500/50 focus:ring-2 focus:ring-violet-500/20"
                                    ></textarea>

                                    <div class="mt-1 flex justify-end">
                                        <span
                                            id="notes-counter"
                                            class="text-xs text-zinc-600"
                                        >
                                            0/500
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- SLOT WARNING -->
                        <div
                            id="slot-warning"
                            class="mt-5 hidden rounded-xl border border-red-500/20 bg-red-500/[0.06] px-4 py-3 text-sm text-red-300"
                        >
                            <i class="fa-solid fa-circle-exclamation mr-1.5"></i>
                            This time overlaps an existing booking. Please choose another slot.
                        </div>


                        <!-- SUBMIT -->
                        <button
                            type="submit"
                            id="booking-submit"
                            class="mt-7 flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-purple-500 via-violet-500 to-indigo-500 px-6 py-3.5 text-sm font-semibold text-white shadow-glow-sm transition hover:scale-[1.01] disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:scale-100"
                        >
                            <span>Continue to Payment</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>

                    </form>

                </div>


                <!-- =====================================================
                     BOOKING SUMMARY
                ====================================================== -->
                <div
                    class="h-fit rounded-3xl border border-white/10 bg-card/80 p-7 shadow-glow-sm backdrop-blur-xl lg:sticky lg:top-28"
                    data-animate="fade-up"
                    data-delay="120"
                >

                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-xs font-semibold uppercase tracking-wider text-violet-300">
                                Booking Summary
                            </p>

                            <h2 class="mt-1 text-lg font-semibold text-white">
                                Your Session
                            </h2>

                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-500/10 text-violet-300">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>

                    </div>


                    <div class="mt-6 space-y-5">

                        <!-- NAME -->
                        <div class="flex items-start justify-between gap-4 text-sm">

                            <span class="flex items-center gap-2 text-zinc-500">
                                <i class="fa-solid fa-user w-4"></i>
                                Name
                            </span>

                            <span
                                id="sum-name"
                                class="max-w-[180px] text-right font-medium text-white"
                            >
                                —
                            </span>

                        </div>


                        <!-- DATE -->
                        <div class="flex items-start justify-between gap-4 text-sm">

                            <span class="flex items-center gap-2 text-zinc-500">
                                <i class="fa-solid fa-calendar-days w-4"></i>
                                Date
                            </span>

                            <span
                                id="sum-date"
                                class="text-right font-medium text-white"
                            >
                                —
                            </span>

                        </div>


                        <!-- TIME -->
                        <div class="flex items-start justify-between gap-4 text-sm">

                            <span class="flex items-center gap-2 text-zinc-500">
                                <i class="fa-solid fa-clock w-4"></i>
                                Time
                            </span>

                            <span
                                id="sum-time"
                                class="text-right font-medium text-white"
                            >
                                —
                            </span>

                        </div>


                        <!-- DURATION -->
                        <div class="flex items-center justify-between text-sm">

                            <span class="flex items-center gap-2 text-zinc-500">
                                <i class="fa-solid fa-hourglass-half w-4"></i>
                                Duration
                            </span>

                            <span
                                id="sum-duration"
                                class="font-medium text-white"
                            >
                                1 Hour
                            </span>

                        </div>


                        <!-- RATE -->
                        <div class="flex items-center justify-between text-sm">

                            <span class="flex items-center gap-2 text-zinc-500">
                                <i class="fa-solid fa-tag w-4"></i>
                                Rate
                            </span>

                            <span class="font-medium text-white">
                                Rp75.000 / hour
                            </span>

                        </div>

                    </div>


                    <!-- TOTAL -->
                    <div class="mt-7 border-t border-white/10 pt-5">

                        <div class="flex items-end justify-between">

                            <div>

                                <p class="text-xs text-zinc-500">
                                    Total payment
                                </p>

                                <p class="mt-1 text-sm font-semibold text-white">
                                    Studio Session
                                </p>

                            </div>

                            <span
                                id="sum-total"
                                class="font-display text-2xl font-extrabold text-white"
                            >
                                Rp75.000
                            </span>

                        </div>

                    </div>


                    <!-- INFO -->
                    <div class="mt-6 rounded-xl border border-white/5 bg-white/[0.02] p-4">

                        <div class="flex gap-3">

                            <i class="fa-solid fa-circle-info mt-0.5 text-sm text-violet-300"></i>

                            <div>

                                <p class="text-xs font-medium text-white">
                                    Before you continue
                                </p>

                                <p class="mt-1 text-xs leading-relaxed text-zinc-500">
                                    Please make sure your date, time, and duration are correct.
                                    Your booking will be checked for availability before payment.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>


<script>
document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('booking-form');

    const nameInput = document.getElementById('customer_name');
    const dateInput = document.getElementById('booking_date');
    const timeInput = document.getElementById('start_time');
    const durationInput = document.getElementById('duration');
    const notesInput = document.getElementById('notes');

    const durationButtons = document.querySelectorAll('.duration-opt');

    const sumName = document.getElementById('sum-name');
    const sumDate = document.getElementById('sum-date');
    const sumTime = document.getElementById('sum-time');
    const sumDuration = document.getElementById('sum-duration');
    const sumTotal = document.getElementById('sum-total');

    const slotWarning = document.getElementById('slot-warning');
    const notesCounter = document.getElementById('notes-counter');
    const submitButton = document.getElementById('booking-submit');

    const pricePerHour = <?= (int) $pricePerHour ?>;
    const studioCloseHour = <?= (int) $studioCloseHour ?>;


    /*
    |--------------------------------------------------------------------------
    | Format Rupiah
    |--------------------------------------------------------------------------
    */
    function formatRupiah(value) {

        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(value);

    }


    /*
    |--------------------------------------------------------------------------
    | Duration selection
    |--------------------------------------------------------------------------
    */
    function updateDurationButtons() {

        const selectedDuration = Number(durationInput.value);

        durationButtons.forEach(button => {

            const hours = Number(button.dataset.hours);

            if (hours === selectedDuration) {

                button.classList.remove(
                    'border-white/10',
                    'bg-white/[0.02]',
                    'text-zinc-300'
                );

                button.classList.add(
                    'border-violet-500/50',
                    'bg-violet-500/10',
                    'text-white',
                    'ring-2',
                    'ring-violet-500/20'
                );

            } else {

                button.classList.remove(
                    'border-violet-500/50',
                    'bg-violet-500/10',
                    'text-white',
                    'ring-2',
                    'ring-violet-500/20'
                );

                button.classList.add(
                    'border-white/10',
                    'bg-white/[0.02]',
                    'text-zinc-300'
                );

            }

        });

    }


    durationButtons.forEach(button => {

        button.addEventListener('click', () => {

            durationInput.value = button.dataset.hours;

            updateSummary();
            updateDurationButtons();
            validateTime();

        });

    });


    /*
    |--------------------------------------------------------------------------
    | Summary
    |--------------------------------------------------------------------------
    */
    function updateSummary() {

        const duration = Number(durationInput.value) || 1;

        sumName.textContent = nameInput.value.trim() || '—';

        if (dateInput.value) {

            const date = new Date(dateInput.value + 'T00:00:00');

            sumDate.textContent = date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });

        } else {

            sumDate.textContent = '—';

        }


        if (timeInput.value) {

            const startHour = Number(timeInput.value.split(':')[0]);
            const endHour = startHour + duration;

            if (endHour <= studioCloseHour) {

                sumTime.textContent =
                    `${timeInput.value} – ${String(endHour).padStart(2, '0')}:00`;

            } else {

                sumTime.textContent = `${timeInput.value} – Invalid`;

            }

        } else {

            sumTime.textContent = '—';

        }


        sumDuration.textContent =
            `${duration} Hour${duration > 1 ? 's' : ''}`;

        sumTotal.textContent =
            formatRupiah(duration * pricePerHour);

    }


    /*
    |--------------------------------------------------------------------------
    | Validate selected time + duration
    |--------------------------------------------------------------------------
    */
    function validateTime() {

        if (!timeInput.value) {
            slotWarning.classList.add('hidden');
            submitButton.disabled = false;
            return true;
        }

        const startHour = Number(timeInput.value.split(':')[0]);
        const duration = Number(durationInput.value);

        const endHour = startHour + duration;

        if (endHour > studioCloseHour) {

            slotWarning.innerHTML = `
                <i class="fa-solid fa-circle-exclamation mr-1.5"></i>
                This session would end after studio closing time.
                Please choose an earlier start time or shorter duration.
            `;

            slotWarning.classList.remove('hidden');

            submitButton.disabled = true;

            return false;

        }

        slotWarning.classList.add('hidden');
        submitButton.disabled = false;

        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | Notes counter
    |--------------------------------------------------------------------------
    */
    function updateNotesCounter() {

        const length = notesInput.value.length;

        notesCounter.textContent = `${length}/500`;

    }


    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */
    nameInput.addEventListener('input', updateSummary);

    dateInput.addEventListener('change', updateSummary);

    timeInput.addEventListener('change', () => {

        updateSummary();
        validateTime();

    });

    notesInput.addEventListener('input', updateNotesCounter);


    /*
    |--------------------------------------------------------------------------
    | Form validation
    |--------------------------------------------------------------------------
    */
    form.addEventListener('submit', event => {

        if (!form.checkValidity()) {

            event.preventDefault();

            form.reportValidity();

            return;

        }

        if (!validateTime()) {

            event.preventDefault();

            return;

        }

        submitButton.disabled = true;

        submitButton.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin"></i>
            Checking availability...
        `;

    });


    /*
    |--------------------------------------------------------------------------
    | Initial state
    |--------------------------------------------------------------------------
    */
    updateDurationButtons();
    updateSummary();
    updateNotesCounter();
    validateTime();

});
</script>


<?php require __DIR__ . '/includes/footer.php'; ?>