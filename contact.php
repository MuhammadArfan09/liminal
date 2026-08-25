<?php
require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/navbar.php';

?>

<main>
  <section class="relative overflow-hidden pt-40 pb-16 md:pt-48">
    <div class="pointer-events-none absolute -top-40 left-1/2 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-violet-600/15 blur-[140px]"></div>
    <div class="relative mx-auto max-w-[1280px] px-6 text-center" data-animate="fade-up">
      <h1 class="font-display text-4xl font-extrabold tracking-tight text-white sm:text-5xl md:text-6xl">Let's talk.</h1>
      <p class="mx-auto mt-5 max-w-xl text-base text-zinc-400 md:text-lg">Have questions about booking or the studio? We're here to help.</p>
    </div>
  </section>

  <section class="py-16 md:py-24">
    <div class="mx-auto max-w-[1280px] px-6">
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
        <a href="https://wa.me/6282297659482" target="_blank" rel="noopener" class="rounded-2xl border border-white/10 bg-white/[0.02] p-6 transition-colors hover:border-violet-500/30" data-animate="fade-up">
          <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/[0.04] text-violet-400"><i class="fa-brands fa-whatsapp"></i></span>
          <p class="mt-4 text-sm font-semibold text-white">WhatsApp</p>
          <p class="mt-1 text-xs text-ink-secondary">+62 822-9765-9482</p>
        </a>
        <a href="https://instagram.com/liminalbandasik" target="_blank" rel="noopener" class="rounded-2xl border border-white/10 bg-white/[0.02] p-6 transition-colors hover:border-violet-500/30" data-animate="fade-up" data-delay="60">
          <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/[0.04] text-violet-400"><i class="fa-brands fa-instagram"></i></span>
          <p class="mt-4 text-sm font-semibold text-white">Instagram</p>
          <p class="mt-1 text-xs text-ink-secondary">@liminalbandasik</p>
        </a>
        <a href="mailto:arfanmuhammad0105@gmail.com" class="rounded-2xl border border-white/10 bg-white/[0.02] p-6 transition-colors hover:border-violet-500/30" data-animate="fade-up" data-delay="120">
          <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/[0.04] text-violet-400"><i class="fa-solid fa-envelope"></i></span>
          <p class="mt-4 text-sm font-semibold text-white">Email</p>
          <p class="mt-1 text-xs text-ink-secondary">arfanmuhammad0105@gmail.com</p>
        </a>
        <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-6" data-animate="fade-up" data-delay="180">
          <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/[0.04] text-violet-400"><i class="fa-solid fa-location-dot"></i></span>
          <p class="mt-4 text-sm font-semibold text-white">Location</p>
          <p class="mt-1 text-xs text-ink-secondary">Malang, East Java</p>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-6" data-animate="fade-up" data-delay="240">
          <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/[0.04] text-violet-400"><i class="fa-solid fa-clock"></i></span>
          <p class="mt-4 text-sm font-semibold text-white">Operating Hours</p>
          <p class="mt-1 text-xs text-ink-secondary">10:00 — 23:00 Daily</p>
        </div>
      </div>
    </div>
  </section>

  <section class="py-8 md:py-16">
    <div class="mx-auto grid max-w-[1280px] grid-cols-1 gap-10 px-6 lg:grid-cols-2">

      <!-- CONTACT FORM -->
      <div class="rounded-3xl border border-white/10 bg-white/[0.02] p-8" data-animate="fade-up">
        <h2 class="font-display text-2xl font-extrabold text-white">Send a message</h2>

        <?php if ($formSuccess === '1'): ?>
          <div class="mt-5 flex items-center gap-3 rounded-xl border border-emerald-500/20 bg-emerald-500/[0.06] px-4 py-3 text-sm text-emerald-300">
            <i class="fa-solid fa-circle-check"></i> Your message has been sent. We'll reply as soon as possible.
          </div>
        <?php endif; ?>

        <form id="contact-form" action="process_contact.php" method="POST" class="mt-6 space-y-5" novalidate>
          <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">

          <div>
            <label for="name" class="block text-sm font-medium text-white">Name</label>
            <input type="text" id="name" name="name" required
              class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/20"
              placeholder="Your full name">
            <p class="error-message mt-1.5 hidden text-xs text-red-400"></p>
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-white">Email</label>
            <input type="email" id="email" name="email" required
              class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/20"
              placeholder="you@email.com">
            <p class="error-message mt-1.5 hidden text-xs text-red-400"></p>
          </div>

          <div>
            <label for="phone" class="block text-sm font-medium text-white">Phone</label>
            <input type="tel" id="phone" name="phone" required
              class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/20"
              placeholder="08xx-xxxx-xxxx">
            <p class="error-message mt-1.5 hidden text-xs text-red-400"></p>
          </div>

          <div>
            <label for="subject" class="block text-sm font-medium text-white">Subject</label>
            <input type="text" id="subject" name="subject" required
              class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/20"
              placeholder="What's this about?">
            <p class="error-message mt-1.5 hidden text-xs text-red-400"></p>
          </div>

          <div>
            <label for="message" class="block text-sm font-medium text-white">Message</label>
            <textarea id="message" name="message" rows="4" required
              class="mt-2 w-full rounded-xl border border-white/10 bg-white/[0.02] px-4 py-3 text-sm text-white placeholder-zinc-600 focus:border-violet-500/50 focus:outline-none focus:ring-2 focus:ring-violet-500/20"
              placeholder="Tell us more..."></textarea>
            <p class="error-message mt-1.5 hidden text-xs text-red-400"></p>
          </div>

          <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-purple-500 via-violet-500 to-indigo-500 px-6 py-3.5 text-sm font-semibold text-white transition-transform hover:scale-[1.01]">
            Send Message <i class="fa-solid fa-arrow-right text-xs"></i>
          </button>
        </form>
      </div>

      <!-- MAP -->
      <div class="flex flex-col gap-6" data-animate="fade-up" data-delay="120">
        <div class="relative flex-1 overflow-hidden rounded-3xl border border-white/10 bg-white/[0.02]">
          <div class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-zinc-600">
        
         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.9397557371203!2d112.60600017394145!3d-7.901363078621217!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6290312a23851%3A0x50707e87278e6880!2sWukWuk%20Studio!5e0!3m2!1sid!2sid!4v1787630426792!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" class="w-full h-full object-cover"></iframe>
          </div>
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/[0.02] p-6">
          <p class="text-sm font-semibold text-white">Studio Liminal</p>
          <p class="mt-2 text-sm text-ink-secondary">Perumahan Jl. Griya Permata Alam No.mor 1 blok RA, Perun Gpa, Ngijo, Kec. Karang Ploso, Kabupaten Malang, Jawa Timur 65152</p>
        </div>
      </div>
    </div>
  </section>

</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
