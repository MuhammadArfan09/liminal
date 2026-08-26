<?php
declare(strict_types=1);
$currentPage      = 'studio.php';
$pageTitle        = 'Studio — Liminal Studio';
$pageDescription  = 'Everything your band needs for a productive rehearsal — equipment, gallery, and studio details.';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>

<main>

  <!-- HERO -->
  <section class="relative overflow-hidden pt-40 pb-16 md:pt-48">
    <div class="pointer-events-none absolute -top-40 left-1/2 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-violet-600/15 blur-[140px]"></div>
    <div class="relative mx-auto max-w-[1280px] px-6 text-center" data-animate="fade-up">
      <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.03] px-4 py-1.5 text-xs font-semibold tracking-wide text-violet-300">THE SPACE</span>
      <h1 class="mt-6 font-display text-4xl font-extrabold leading-tight tracking-tight text-white sm:text-5xl md:text-6xl">Built for your sound.</h1>
      <p class="mx-auto mt-5 max-w-xl text-base text-zinc-400 md:text-lg">Everything your band needs for a productive rehearsal.</p>
    </div>
  </section>

  <!-- MAIN IMAGE -->
  <section class="px-6" data-animate="fade-up">
    <div class="relative mx-auto aspect-[21/9] max-w-[1280px] overflow-hidden rounded-3xl border border-white/10">
        <img src="assets/images/wwstd.png" alt="" class="w-full h-full object-cover">
    </div>
  </section>

  <!-- EQUIPMENT -->
  <section class="py-24 md:py-32">
    <div class="mx-auto max-w-[1280px] px-6">
      <div class="max-w-2xl" data-animate="fade-up">
        <h2 class="font-display text-3xl font-extrabold tracking-tight text-white md:text-5xl">Studio equipment.</h2>
        <p class="mt-4 text-base text-zinc-400 md:text-lg">Maintained, tuned, and ready every time you walk in.</p>
      </div>

      <div class="mt-14 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <?php
        $equipment = [
          [
          'icon' => 'fa-drum',
          'title' => 'Drum Kit',
          'desc' => 'A full acoustic kit, tuned and ready for every session.'
          ],
          [
          'icon' => 'fa-guitar', 
          'title' => 'Guitar Amplifier',
          'desc' => 'Reliable tube and solid-state amps for any tone.'
          ],
          ['icon' => 'fa-volume-high', 'title' => 'Bass Amplifier', 'desc' => 'Deep, clean low-end that holds the room together.'],
          ['icon' => 'fa-microphone', 'title' => 'Microphone', 'desc' => 'Vocal and instrument mics for rehearsal and recording.'],
          ['icon' => 'fa-sliders', 'title' => 'Mixer', 'desc' => 'A dedicated console for balancing your full band mix.'],
          ['icon' => 'fa-volume-high', 'title' => 'Monitor Speaker', 'desc' => 'Clear monitoring so every player hears themselves.'],
        ];
        foreach ($equipment as $i => $item): ?>
        <div class="group rounded-2xl border border-white/10 bg-white/[0.02] p-7 transition-all hover:-translate-y-1 hover:border-violet-500/30" data-animate="fade-up" data-delay="<?= $i * 60 ?>">
          <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/[0.04] text-violet-400 transition-colors group-hover:bg-violet-500/10">
            <i class="fa-solid <?= e($item['icon']) ?>"></i>
          </span>
          <h3 class="mt-5 text-base font-semibold text-white"><?= e($item['title']) ?></h3>
          <p class="mt-2 text-sm text-ink-secondary"><?= e($item['desc']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- STUDIO DETAILS -->
  <section class="py-24 md:py-32">
    <div class="mx-auto max-w-[1280px] px-6">
      <div class="rounded-3xl border border-white/10 bg-white/[0.02] p-8 md:p-12" data-animate="fade-up">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
          <div class="flex items-start gap-4">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/[0.04] text-violet-400"><i class="fa-solid fa-users"></i></span>
            <div><p class="text-xs uppercase tracking-wider text-ink-secondary">Capacity</p><p class="mt-1 text-sm font-medium text-white">Up to 8 musicians</p></div>
          </div>
          <div class="flex items-start gap-4">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/[0.04] text-violet-400"><i class="fa-solid fa-clock"></i></span>
            <div><p class="text-xs uppercase tracking-wider text-ink-secondary">Operating Hours</p><p class="mt-1 text-sm font-medium text-white">10:00 — 23:00</p></div>
          </div>
          <div class="flex items-start gap-4">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/[0.04] text-violet-400"><i class="fa-solid fa-door-open"></i></span>
            <div><p class="text-xs uppercase tracking-wider text-ink-secondary">Room Type</p><p class="mt-1 text-sm font-medium text-white">Band Rehearsal Room</p></div>
          </div>
          <div class="flex items-start gap-4">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/[0.04] text-violet-400"><i class="fa-solid fa-wind"></i></span>
            <div><p class="text-xs uppercase tracking-wider text-ink-secondary">Air Conditioning</p><p class="mt-1 text-sm font-medium text-white">Available</p></div>
          </div>
          <div class="flex items-start gap-4">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/[0.04] text-violet-400"><i class="fa-solid fa-volume-xmark"></i></span>
            <div><p class="text-xs uppercase tracking-wider text-ink-secondary">Sound Treatment</p><p class="mt-1 text-sm font-medium text-white">Professional acoustic treatment</p></div>
          </div>
        </div>

        <a href="booking.php" class="mt-10 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-purple-500 via-violet-500 to-indigo-500 px-6 py-3.5 text-sm font-semibold text-white shadow-glow-sm transition-transform hover:scale-[1.02]">
          Book This Studio <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>
    </div>
  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
