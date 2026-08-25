<?php
declare(strict_types=1);
$currentPage      = 'pricing.php';
$pageTitle        = 'Pricing — Liminal Studio';
$pageDescription  = 'Straightforward, hourly pricing for your rehearsal session. Pay only for the time you need.';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>

<main>

  <section class="relative overflow-hidden pt-40 pb-16 md:pt-48">
    <div class="pointer-events-none absolute -top-40 left-1/2 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-violet-600/15 blur-[140px]"></div>
    <div class="relative mx-auto max-w-[1280px] px-6 text-center" data-animate="fade-up">
      <h1 class="font-display text-4xl font-extrabold tracking-tight text-white sm:text-5xl md:text-6xl">Straightforward pricing.</h1>
      <p class="mx-auto mt-5 max-w-xl text-base text-zinc-400 md:text-lg">Pay only for the time you need.</p>
    </div>
  </section>

  <section class="py-16 md:py-24">
    <div class="mx-auto max-w-2xl px-6" data-animate="fade-up">
      <div class="rounded-3xl border border-violet-500/20 bg-gradient-to-b from-white/[0.04] to-white/[0.01] p-8 shadow-glow md:p-10">
        <p class="text-xs font-semibold uppercase tracking-wider text-violet-300">Studio Session</p>
        <div class="mt-3 flex items-baseline gap-1.5">
          <span class="font-display text-4xl font-extrabold text-white">Rp75.000</span>
          <span class="text-sm text-ink-secondary">/ hour</span>
        </div>

        <p class="mt-6 text-sm font-medium text-white">Choose your duration</p>
        <div id="duration-options" class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
          <button type="button" class="duration-btn rounded-xl border border-white/10 bg-white/[0.02] px-4 py-4 text-center transition-all" data-hours="1">
            <span class="block text-sm font-semibold text-white">1 Hour</span>
            <span class="block mt-1 text-xs text-ink-secondary">Rp75.000</span>
          </button>
          <button type="button" class="duration-btn rounded-xl border border-white/10 bg-white/[0.02] px-4 py-4 text-center transition-all" data-hours="2">
            <span class="block text-sm font-semibold text-white">2 Hours</span>
            <span class="block mt-1 text-xs text-ink-secondary">Rp150.000</span>
          </button>
          <button type="button" class="duration-btn rounded-xl border border-white/10 bg-white/[0.02] px-4 py-4 text-center transition-all" data-hours="3">
            <span class="block text-sm font-semibold text-white">3 Hours</span>
            <span class="block mt-1 text-xs text-ink-secondary">Rp225.000</span>
          </button>
          <button type="button" class="duration-btn rounded-xl border border-white/10 bg-white/[0.02] px-4 py-4 text-center transition-all" data-hours="4">
            <span class="block text-sm font-semibold text-white">4 Hours</span>
            <span class="block mt-1 text-xs text-ink-secondary">Rp300.000</span>
          </button>
        </div>

        <div class="mt-7 rounded-2xl border border-white/10 bg-white/[0.02] p-5">
          <div class="flex items-center justify-between text-sm">
            <span class="text-ink-secondary">Selected duration</span>
            <span id="summary-duration" class="font-medium text-white">1 Hour</span>
          </div>
          <div class="mt-3 flex items-center justify-between border-t border-white/10 pt-3">
            <span class="text-sm font-semibold text-white">Total</span>
            <span id="summary-total" class="font-display text-xl font-extrabold text-white">Rp75.000</span>
          </div>
        </div>

        <ul class="mt-7 space-y-3">
          <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Full studio access</li>
          <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Drum kit, guitar &amp; bass amplifier</li>
          <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Microphone &amp; mixer</li>
          <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Air conditioning</li>
        </ul>

        <a id="book-session-link" href="booking.php?duration=1" class="mt-8 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-purple-500 via-violet-500 to-indigo-500 px-6 py-3.5 text-sm font-semibold text-white transition-transform hover:scale-[1.02]">
          Book This Session <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>
    </div>
  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
