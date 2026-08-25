<?php
declare(strict_types=1);
$currentPage      = 'index.php';
$pageTitle        = 'Liminal Studio — Your Space. Your Sound.';
$pageDescription  = 'Professional band rehearsal space built for musicians who take their sound seriously.';
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';
?>

<main>

  <!-- HERO -->
  <section class="relative overflow-hidden pt-40 pb-28 md:pt-48 md:pb-36">
    <div class="pointer-events-none absolute -top-40 left-1/2 h-[560px] w-[900px] -translate-x-1/2 rounded-full bg-violet-600/20 blur-[140px]"></div>
    <div class="pointer-events-none absolute top-1/3 right-0 h-[400px] w-[400px] rounded-full bg-indigo-600/20 blur-[120px]"></div>

    <div class="relative mx-auto grid max-w-[1280px] grid-cols-1 items-center gap-16 px-6 lg:grid-cols-2">

      <div data-animate="fade-up">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.03] px-4 py-1.5 text-xs font-semibold tracking-wide text-violet-300">
          <i class="fa-solid fa-circle text-[6px] text-violet-400"></i>
          PROFESSIONAL BAND STUDIO
        </span>

        <h1 class="mt-6 font-display text-4xl font-extrabold leading-[1.05] tracking-tight text-white sm:text-5xl md:text-6xl lg:text-7xl">
          Make Your Sound.<br>
          <span class="bg-gradient-to-r from-purple-400 via-violet-400 to-indigo-400 bg-clip-text text-transparent">Own Your Session.</span>
        </h1>

        <p class="mt-6 max-w-lg text-base text-zinc-400 md:text-lg">
          Practice, create, and perform in a professional studio built for musicians who take their sound seriously.
        </p>

        <div class="mt-9 flex flex-col gap-4 sm:flex-row sm:items-center">
          <a href="booking.php" class="group inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-purple-500 via-violet-500 to-indigo-500 px-6 py-3.5 text-sm font-semibold text-white shadow-glow transition-transform hover:scale-[1.02]">
            Book Your Session
            <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-0.5"></i>
          </a>
          <a href="studio.php" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/10 bg-white/[0.03] px-6 py-3.5 text-sm font-semibold text-white hover:bg-white/[0.06] transition-colors">
            Explore Studio
          </a>
        </div>

        <p class="mt-7 text-xs text-ink-secondary">
          Flexible booking &nbsp;•&nbsp; Professional equipment &nbsp;•&nbsp; Easy payment
        </p>
      </div>

      <!-- HERO VISUAL: booking schedule mockup -->
      <div class="relative" data-animate="fade-up" data-delay="150">
        <div class="pointer-events-none absolute -inset-6 rounded-[2rem] bg-gradient-to-br from-purple-600/20 via-violet-600/10 to-indigo-600/20 blur-2xl"></div>

        <div class="relative rounded-3xl border border-white/10 bg-card/80 p-6 shadow-glow backdrop-blur-xl">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-semibold uppercase tracking-wider text-ink-secondary">Today's Schedule</p>
              <p class="mt-1 text-sm font-medium text-white">Tuesday, 25 August</p>
            </div>
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/[0.05] text-violet-400">
              <i class="fa-solid fa-calendar-days"></i>
            </span>
          </div>

          <div class="mt-6 space-y-3">
            <div class="flex items-center justify-between rounded-xl border border-white/5 bg-white/[0.02] px-4 py-3.5">
              <div>
                <p class="text-sm font-medium text-white">10:00 — 12:00</p>
                <p class="text-xs text-ink-secondary">2 hour session</p>
              </div>
              <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-400">Available</span>
            </div>
            <div class="flex items-center justify-between rounded-xl border border-white/5 bg-white/[0.02] px-4 py-3.5">
              <div>
                <p class="text-sm font-medium text-white">14:00 — 16:00</p>
                <p class="text-xs text-ink-secondary">2 hour session</p>
              </div>
              <span class="rounded-full bg-white/[0.06] px-3 py-1 text-xs font-medium text-ink-secondary">Booked</span>
            </div>
            <div class="flex items-center justify-between rounded-xl border border-violet-500/20 bg-violet-500/[0.06] px-4 py-3.5">
              <div>
                <p class="text-sm font-medium text-white">19:00 — 21:00</p>
                <p class="text-xs text-ink-secondary">2 hour session</p>
              </div>
              <span class="rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-medium text-emerald-400">Available</span>
            </div>
          </div>
        </div>

        <!-- Floating card -->
        <div class="absolute -bottom-8 -left-6 hidden w-56 rounded-2xl border border-white/10 bg-card/95 p-4 shadow-glow-sm backdrop-blur-xl sm:block">
          <p class="text-[10px] font-semibold uppercase tracking-wider text-violet-300">Next Session</p>
          <p class="mt-1.5 text-sm font-semibold text-white">19:00 — 21:00</p>
          <div class="mt-2 flex items-center gap-1.5 text-xs text-emerald-400">
            <i class="fa-solid fa-circle text-[6px]"></i> Available
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- STATISTICS -->
  <section class="border-y border-white/5 bg-base-soft py-16" data-animate="fade-up">
    <div class="mx-auto max-w-[1280px] px-6">
      <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-6 text-center">
          <p class="font-display text-3xl font-extrabold text-white md:text-4xl">100+</p>
          <p class="mt-1 text-xs text-ink-secondary">Sessions</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-6 text-center">
          <p class="font-display text-3xl font-extrabold text-white md:text-4xl">4.9/5</p>
          <p class="mt-1 text-xs text-ink-secondary">Customer Rating</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-6 text-center">
          <p class="font-display text-3xl font-extrabold text-white md:text-4xl">24/7</p>
          <p class="mt-1 text-xs text-ink-secondary">Online Booking</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-6 text-center">
          <p class="font-display text-3xl font-extrabold text-white md:text-4xl">Pro</p>
          <p class="mt-1 text-xs text-ink-secondary">Equipment</p>
        </div>
      </div>
    </div>
  </section>

  <!-- FEATURES -->
  <section class="py-24 md:py-32">
    <div class="mx-auto max-w-[1280px] px-6">
      <div class="max-w-2xl" data-animate="fade-up">
        <h2 class="font-display text-3xl font-extrabold leading-tight tracking-tight text-white md:text-5xl">
          Everything your band needs,<br>in one room.
        </h2>
        <p class="mt-4 text-base text-zinc-400 md:text-lg">
          From rehearsals to creative sessions, Liminal Studio gives your band the space and equipment to focus on the music.
        </p>
      </div>

      <div class="mt-14 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="group rounded-2xl border border-white/10 bg-white/[0.02] p-7 transition-all hover:-translate-y-1 hover:border-violet-500/30" data-animate="fade-up">
          <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/[0.04] text-violet-400 transition-colors group-hover:bg-violet-500/10">
            <i class="fa-solid fa-sliders"></i>
          </span>
          <h3 class="mt-5 text-base font-semibold text-white">Professional Equipment</h3>
          <p class="mt-2 text-sm text-ink-secondary">Reliable equipment ready for every rehearsal.</p>
        </div>
        <div class="group rounded-2xl border border-white/10 bg-white/[0.02] p-7 transition-all hover:-translate-y-1 hover:border-violet-500/30" data-animate="fade-up" data-delay="80">
          <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/[0.04] text-violet-400 transition-colors group-hover:bg-violet-500/10">
            <i class="fa-solid fa-couch"></i>
          </span>
          <h3 class="mt-5 text-base font-semibold text-white">Comfortable Space</h3>
          <p class="mt-2 text-sm text-ink-secondary">A dedicated room designed for long practice sessions.</p>
        </div>
        <div class="group rounded-2xl border border-white/10 bg-white/[0.02] p-7 transition-all hover:-translate-y-1 hover:border-violet-500/30" data-animate="fade-up" data-delay="160">
          <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/[0.04] text-violet-400 transition-colors group-hover:bg-violet-500/10">
            <i class="fa-solid fa-calendar-check"></i>
          </span>
          <h3 class="mt-5 text-base font-semibold text-white">Easy Booking</h3>
          <p class="mt-2 text-sm text-ink-secondary">Check availability and reserve your session online.</p>
        </div>
        <div class="group rounded-2xl border border-white/10 bg-white/[0.02] p-7 transition-all hover:-translate-y-1 hover:border-violet-500/30" data-animate="fade-up" data-delay="240">
          <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/[0.04] text-violet-400 transition-colors group-hover:bg-violet-500/10">
            <i class="fa-solid fa-clock"></i>
          </span>
          <h3 class="mt-5 text-base font-semibold text-white">Flexible Schedule</h3>
          <p class="mt-2 text-sm text-ink-secondary">Choose the time that works best for your band.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- STUDIO PREVIEW -->
  <section class="py-24 md:py-32">
    <div class="mx-auto grid max-w-[1280px] grid-cols-1 items-center gap-14 px-6 lg:grid-cols-2">
      <div class="relative order-2 lg:order-1" data-animate="fade-up">
        <div class="pointer-events-none absolute -inset-4 rounded-[2rem] bg-violet-600/10 blur-2xl"></div>
        <div class="relative aspect-[4/5] overflow-hidden rounded-3xl border border-white/10">
          <img src="assets/images/wwstd.png" alt="" class="w-full h-full object-cover">
        </div>
      </div>

      <div class="order-1 lg:order-2" data-animate="fade-up" data-delay="120">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/[0.03] px-4 py-1.5 text-xs font-semibold tracking-wide text-violet-300">THE SPACE</span>
        <h2 class="mt-5 font-display text-3xl font-extrabold leading-tight tracking-tight text-white md:text-5xl">
          Your sound deserves<br>the right space.
        </h2>
        <p class="mt-4 text-base text-zinc-400 md:text-lg">
          A single, purpose-built rehearsal room with acoustic treatment, climate control, and gear maintained to a professional standard — so you can walk in and start playing.
        </p>

        <ul class="mt-7 space-y-3">
          <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Professional drum kit</li>
          <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Guitar amplifier</li>
          <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Bass amplifier</li>
          <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Microphones</li>
          <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Mixing console</li>
          <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Air conditioning</li>
        </ul>

        <a href="studio.php" class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-white hover:text-violet-300 transition-colors">
          Explore Studio <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- BOOKING PREVIEW -->
  <section class="py-24 md:py-32">
    <div class="mx-auto grid max-w-[1280px] grid-cols-1 items-center gap-14 px-6 lg:grid-cols-2">
      <div data-animate="fade-up">
        <h2 class="font-display text-3xl font-extrabold leading-tight tracking-tight text-white md:text-5xl">
          Book your session<br>without the back and forth.
        </h2>
        <p class="mt-4 text-base text-zinc-400 md:text-lg">
          Choose your date, pick your time, and get your rehearsal space ready.
        </p>
        <a href="booking.php" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-purple-500 via-violet-500 to-indigo-500 px-6 py-3.5 text-sm font-semibold text-white shadow-glow-sm transition-transform hover:scale-[1.02]">
          Book This Time <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>

      <div class="relative" data-animate="fade-up" data-delay="120">
        <div class="pointer-events-none absolute -inset-6 rounded-[2rem] bg-indigo-600/10 blur-2xl"></div>
        <div class="relative rounded-3xl border border-white/10 bg-card/80 p-6 shadow-glow-sm backdrop-blur-xl">
          <div class="flex items-center justify-between">
            <p class="text-sm font-semibold text-white">August 2026</p>
            <div class="flex gap-1.5 text-ink-secondary">
              <i class="fa-solid fa-chevron-left text-xs"></i>
              <i class="fa-solid fa-chevron-right text-xs"></i>
            </div>
          </div>

          <div class="mt-5 grid grid-cols-7 gap-1.5 text-center text-[11px] text-ink-secondary">
            <span>MON</span><span>TUE</span><span>WED</span><span>THU</span><span>FRI</span><span>SAT</span><span>SUN</span>
          </div>
          <div class="mt-2 grid grid-cols-7 gap-1.5 text-center text-sm">
            <span class="rounded-lg py-2 text-zinc-500">24</span>
            <span class="rounded-lg bg-gradient-to-br from-purple-500 to-indigo-500 py-2 font-semibold text-white">25</span>
            <span class="rounded-lg py-2 text-zinc-300">26</span>
            <span class="rounded-lg py-2 text-zinc-300">27</span>
            <span class="rounded-lg py-2 text-zinc-300">28</span>
            <span class="rounded-lg py-2 text-zinc-300">29</span>
            <span class="rounded-lg py-2 text-zinc-300">30</span>
          </div>

          <div class="mt-6 border-t border-white/10 pt-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-ink-secondary">25 August — Available Times</p>
            <div class="mt-3 flex flex-wrap gap-2">
              <span class="rounded-lg border border-violet-500/30 bg-violet-500/10 px-3 py-2 text-xs font-medium text-violet-300">10:00 — 11:00</span>
              <span class="rounded-lg border border-white/10 bg-white/[0.02] px-3 py-2 text-xs font-medium text-zinc-300">14:00 — 15:00</span>
              <span class="rounded-lg border border-white/5 bg-white/[0.01] px-3 py-2 text-xs font-medium text-zinc-600 line-through">19:00 — 20:00</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- PRICING PREVIEW -->
  <section class="py-24 md:py-32">
    <div class="mx-auto max-w-[1280px] px-6">
      <div class="text-center" data-animate="fade-up">
        <h2 class="font-display text-3xl font-extrabold leading-tight tracking-tight text-white md:text-5xl">
          Simple pricing.<br>No surprises.
        </h2>
      </div>

      <div class="mx-auto mt-12 max-w-md" data-animate="fade-up" data-delay="120">
        <div class="rounded-3xl border border-violet-500/20 bg-gradient-to-b from-white/[0.04] to-white/[0.01] p-8 shadow-glow">
          <p class="text-xs font-semibold uppercase tracking-wider text-violet-300">Studio Session</p>
          <div class="mt-3 flex items-baseline gap-1.5">
            <span class="font-display text-4xl font-extrabold text-white">Rp75.000</span>
            <span class="text-sm text-ink-secondary">/ hour</span>
          </div>

          <ul class="mt-7 space-y-3">
            <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Full studio access</li>
            <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Drum kit</li>
            <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Guitar amplifier</li>
            <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Bass amplifier</li>
            <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Microphone</li>
            <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Mixer</li>
            <li class="flex items-center gap-3 text-sm text-zinc-300"><i class="fa-solid fa-check text-violet-400"></i> Air conditioning</li>
          </ul>

          <a href="booking.php" class="mt-8 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-purple-500 via-violet-500 to-indigo-500 px-6 py-3.5 text-sm font-semibold text-white transition-transform hover:scale-[1.02]">
            Book Now <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- TESTIMONIALS -->
  <section class="py-24 md:py-32">
    <div class="mx-auto max-w-[1280px] px-6">
      <h2 class="text-center font-display text-3xl font-extrabold tracking-tight text-white md:text-5xl" data-animate="fade-up">
        Loved by musicians.
      </h2>

      <div class="mt-14 grid grid-cols-1 gap-5 md:grid-cols-3">
        <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-7" data-animate="fade-up">
          <div class="flex gap-1 text-violet-400">
            <i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i>
          </div>
          <p class="mt-4 text-sm leading-relaxed text-zinc-300">"Joss wis, gae latihan arek arek smk 5, cek iso juara lomba musik"</p>
          <div class="mt-6 flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 text-xs font-semibold text-white"><img src="assets/images/pakafif.png" alt="" class="rounded-full h-full w-full object-cov"></span>
            <div>
              <p class="text-sm font-medium text-white">Afif Subhan</p>
              <p class="text-xs text-ink-secondary">Independent Musician</p>
            </div>
          </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-7" data-animate="fade-up" data-delay="100">
          <div class="flex gap-1 text-violet-400">
            <i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i>
          </div>
          <p class="mt-4 text-sm leading-relaxed text-zinc-300">"Sound treatment-nya kerasa banget, latihan jadi lebih fokus tanpa gangguan."</p>
          <div class="mt-6 flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-indigo-500 text-xs font-semibold text-white"><img src="assets/images/dewa.jpeg" alt="" class="rounded-full h-full w-full object-cov"></span>
            <div>
              <p class="text-sm font-medium text-white">Dewa</p>
              <p class="text-xs text-ink-secondary">Bassist, Suzo Band</p>
            </div>
          </div>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-7" data-animate="fade-up" data-delay="200">
          <div class="flex gap-1 text-violet-400">
            <i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i><i class="fa-solid fa-star text-xs"></i>
          </div>
          <p class="mt-4 text-sm leading-relaxed text-zinc-300">"Jadwal fleksibel dan bisa dicek langsung online, gak perlu ribet chat sana-sini."</p>
          <div class="mt-6 flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-purple-500 to-violet-500 text-xs font-semibold text-white"><img src="assets/images/deril.jpeg" alt="" class="rounded-full h-full w-full object-cov"></span>
            <div>
              <p class="text-sm font-medium text-white">Deril</p>
              <p class="text-xs text-ink-secondary">Drummer, Studio Session Player</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FINAL CTA -->
  <section class="relative mx-6 mb-24 overflow-hidden rounded-[2.5rem] border border-white/10 bg-gradient-to-br from-purple-900/40 via-violet-900/30 to-indigo-950/50 px-6 py-20 text-center md:mx-auto md:max-w-[1200px]" data-animate="fade-up">
    <div class="pointer-events-none absolute -top-24 left-1/2 h-[400px] w-[700px] -translate-x-1/2 rounded-full bg-violet-600/25 blur-[130px]"></div>
    <div class="relative">
      <h2 class="font-display text-3xl font-extrabold tracking-tight text-white md:text-5xl">Ready to make some noise?</h2>
      <p class="mx-auto mt-4 max-w-md text-base text-zinc-300">Reserve your session and get the room ready for your next rehearsal.</p>
      <a href="booking.php" class="mt-8 inline-flex items-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-semibold text-black transition-transform hover:scale-[1.03]">
        Book Your Session <i class="fa-solid fa-arrow-right text-xs"></i>
      </a>
    </div>
  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
