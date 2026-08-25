<?php
declare(strict_types=1);
$currentPage = $currentPage ?? basename($_SERVER['PHP_SELF']);

function navLink(string $href, string $label, string $current): string
{
    $active = $current === $href;
    $classes = $active
        ? 'text-white'
        : 'text-ink-secondary hover:text-white transition-colors';
    return sprintf(
        '<a href="%s" class="%s text-sm font-medium">%s</a>',
        e($href),
        $classes,
        e($label)
    );
}

##

?>
<header id="site-navbar" class="fixed top-0 inset-x-0 z-50 transition-all duration-300">
  <div class="mx-auto max-w-[1280px] px-6">
    <div id="navbar-inner" class="mt-4 flex items-center justify-between rounded-2xl border border-white/10 bg-base/70 backdrop-blur-xl px-5 py-3 shadow-lg shadow-black/20 transition-all duration-300">

      <a href="index.php" class="flex items-center gap-2.5">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-purple-500 via-violet-500 to-indigo-500 shadow-glow-sm">
          <img src="assets/images/liminallogo.jpeg" alt=""  class="h-full w-full rounded-lg" >
        </span>
        <span class="font-display text-[15px] font-extrabold tracking-tight text-white">LIMINAL <span class="font-semibold text-ink-secondary">STUDIO</span></span>
      </a>

      <nav class="hidden md:flex items-center gap-8">
        <?= navLink('index.php', 'Home', $currentPage) ?>
        <?= navLink('studio.php', 'Studio', $currentPage) ?>
        <?= navLink('pricing.php', 'Pricing', $currentPage) ?>
        <?= navLink('contact.php', 'Contact', $currentPage) ?>
      </nav>

      <div class="hidden md:block">
        <a href="booking.php" class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-purple-500 via-violet-500 to-indigo-500 px-4 py-2.5 text-sm font-semibold text-white shadow-glow-sm transition-transform hover:scale-[1.03]">
          Book Now
          <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-0.5"></i>
        </a>
      </div>

      <button id="mobile-menu-btn" aria-label="Open navigation" aria-expanded="false" aria-controls="mobile-menu" class="md:hidden flex h-9 w-9 items-center justify-center rounded-lg border border-white/10 text-white">
        <i class="fa-solid fa-bars" id="mobile-menu-icon"></i>
      </button>
    </div>

    <nav id="mobile-menu" class="md:hidden hidden mt-2 rounded-2xl border border-white/10 bg-base/95 backdrop-blur-xl px-5 py-5 shadow-lg">
      <div class="flex flex-col gap-1">
        <a href="index.php" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-ink-secondary hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-house w-4 text-violet-400"></i>Home</a>
        <a href="studio.php" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-ink-secondary hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-guitar w-4 text-violet-400"></i>Studio</a>
        <a href="pricing.php" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-ink-secondary hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-tag w-4 text-violet-400"></i>Pricing</a>
        <a href="contact.php" class="flex items-center gap-3 rounded-lg px-3 py-3 text-sm font-medium text-ink-secondary hover:bg-white/5 hover:text-white transition-colors"><i class="fa-solid fa-envelope w-4 text-violet-400"></i>Contact</a>
        <a href="booking.php" class="mt-2 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-purple-500 via-violet-500 to-indigo-500 px-4 py-3 text-sm font-semibold text-white">
          <i class="fa-solid fa-calendar-check"></i> Book Now
        </a>
      </div>
    </nav>
  </div>
  
  <script>
document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = document.getElementById('mobile-menu-icon');

    if (!menuBtn || !mobileMenu || !menuIcon) return;

    menuBtn.addEventListener('click', () => {
        const isOpen = !mobileMenu.classList.contains('hidden');

        mobileMenu.classList.toggle('hidden');

        menuBtn.setAttribute('aria-expanded', String(!isOpen));

        if (!isOpen) {
            // Menu terbuka → icon bars menjadi X
            menuIcon.classList.remove('fa-bars');
            menuIcon.classList.add('fa-xmark');
        } else {
            // Menu tertutup → kembali ke bars
            menuIcon.classList.remove('fa-xmark');
            menuIcon.classList.add('fa-bars');
        }
    });

    // Tutup menu ketika klik link
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');

            menuBtn.setAttribute('aria-expanded', 'false');

            menuIcon.classList.remove('fa-xmark');
            menuIcon.classList.add('fa-bars');
        });
    });
});
</script>
</header>
