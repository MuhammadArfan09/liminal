/**
 * Liminal Studio — main.js
 * Vanilla JavaScript only. No frameworks, no jQuery.
 */
(function () {
  'use strict';

  const RATE_PER_HOUR = 75000;

  /* ---------------------------------------------------------------- */
  /* Utilities                                                         */
  /* ---------------------------------------------------------------- */
  function formatRupiah(amount) {
    return 'Rp' + amount.toLocaleString('id-ID');
  }

  function formatDateLong(dateStr) {
    if (!dateStr) return '—';
    const d = new Date(dateStr + 'T00:00:00');
    if (isNaN(d.getTime())) return '—';
    return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
  }

  function addHoursToTime(time, hours) {
    const [h, m] = time.split(':').map(Number);
    const end = (h + hours) % 24;
    return String(end).padStart(2, '0') + ':' + String(m).padStart(2, '0');
  }

  /* ---------------------------------------------------------------- */
  /* Navbar: sticky shrink-on-scroll                                   */
  /* ---------------------------------------------------------------- */
  const navbarInner = document.getElementById('navbar-inner');
  if (navbarInner) {
    const onScroll = () => {
      if (window.scrollY > 20) {
        navbarInner.classList.add('shadow-xl', 'shadow-black/30');
        navbarInner.classList.remove('bg-base/70');
        navbarInner.classList.add('bg-base/90');
      } else {
        navbarInner.classList.remove('shadow-xl', 'shadow-black/30');
        navbarInner.classList.add('bg-base/70');
        navbarInner.classList.remove('bg-base/90');
      }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ---------------------------------------------------------------- */
  /* Mobile menu                                                       */
  /* ---------------------------------------------------------------- */
  const menuBtn = document.getElementById('mobile-menu-btn');
  const menu = document.getElementById('mobile-menu');
  const menuIcon = document.getElementById('mobile-menu-icon');

  if (menuBtn && menu && menuIcon) {
    menuBtn.addEventListener('click', () => {
      const isOpen = !menu.classList.contains('hidden');
      menu.classList.toggle('hidden');
      menuBtn.setAttribute('aria-expanded', String(!isOpen));
      menuIcon.classList.toggle('fa-bars', isOpen);
      menuIcon.classList.toggle('fa-xmark', !isOpen);
    });
  }

  /* ---------------------------------------------------------------- */
  /* Scroll reveal animations (fade-up)                                */
  /* ---------------------------------------------------------------- */
  const animatedEls = document.querySelectorAll('[data-animate="fade-up"]');
  if (animatedEls.length && 'IntersectionObserver' in window) {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    animatedEls.forEach((el) => {
      if (!prefersReducedMotion) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(16px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        const delay = el.getAttribute('data-delay');
        if (delay) el.style.transitionDelay = delay + 'ms';
      }
    });

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.1, rootMargin: '0px 0px -60px 0px' }
    );

    animatedEls.forEach((el) => observer.observe(el));
  }

  /* ---------------------------------------------------------------- */
  /* FAQ accordion (if present on a page)                              */
  /* ---------------------------------------------------------------- */
  document.querySelectorAll('[data-faq-trigger]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const panel = document.getElementById(trigger.getAttribute('data-faq-trigger'));
      if (!panel) return;
      const isOpen = !panel.classList.contains('hidden');
      panel.classList.toggle('hidden');
      trigger.setAttribute('aria-expanded', String(!isOpen));
      const icon = trigger.querySelector('.faq-icon');
      if (icon) icon.classList.toggle('rotate-180', !isOpen);
    });
  });

  /* ---------------------------------------------------------------- */
  /* Pricing calculator (pricing.php)                                  */
  /* ---------------------------------------------------------------- */
  const durationOptions = document.getElementById('duration-options');
  if (durationOptions) {
    const summaryDuration = document.getElementById('summary-duration');
    const summaryTotal = document.getElementById('summary-total');
    const bookLink = document.getElementById('book-session-link');
    const buttons = durationOptions.querySelectorAll('.duration-btn');

    function selectDuration(hours) {
      buttons.forEach((btn) => {
        const active = Number(btn.dataset.hours) === hours;
        btn.classList.toggle('border-violet-500/50', active);
        btn.classList.toggle('bg-violet-500/10', active);
        btn.classList.toggle('border-white/10', !active);
        btn.classList.toggle('bg-white/[0.02]', !active);
      });
      if (summaryDuration) summaryDuration.textContent = hours + (hours > 1 ? ' Hours' : ' Hour');
      if (summaryTotal) summaryTotal.textContent = formatRupiah(hours * RATE_PER_HOUR);
      if (bookLink) bookLink.href = 'booking.php?duration=' + hours;
    }

    buttons.forEach((btn) => {
      btn.addEventListener('click', () => selectDuration(Number(btn.dataset.hours)));
    });

    selectDuration(1);
  }

  /* ---------------------------------------------------------------- */
  /* Booking page: duration select, availability check, live summary   */
  /* ---------------------------------------------------------------- */
  const bookingForm = document.getElementById('booking-form');
  if (bookingForm) {
    const nameInput = document.getElementById('customer_name');
    const dateInput = document.getElementById('booking_date');
    const startSelect = document.getElementById('start_time');
    const durationHidden = document.getElementById('duration');
    const durationButtons = document.querySelectorAll('#duration-select .duration-opt');
    const slotWarning = document.getElementById('slot-warning');
    const availabilityHint = document.getElementById('availability-hint');
    const submitBtn = document.getElementById('booking-submit');

    const sumName = document.getElementById('sum-name');
    const sumDate = document.getElementById('sum-date');
    const sumTime = document.getElementById('sum-time');
    const sumDuration = document.getElementById('sum-duration');
    const sumTotal = document.getElementById('sum-total');

    let bookedSlots = [];

    function currentDuration() {
      return Number(durationHidden.value || 1);
    }

    function highlightDuration(hours) {
      durationButtons.forEach((btn) => {
        const active = Number(btn.dataset.hours) === hours;
        btn.classList.toggle('border-violet-500/50', active);
        btn.classList.toggle('bg-violet-500/10', active);
        btn.classList.toggle('text-violet-200', active);
        btn.classList.toggle('border-white/10', !active);
        btn.classList.toggle('bg-white/[0.02]', !active);
      });
    }

    function overlaps(startTime, hours) {
      const endTime = addHoursToTime(startTime, hours);
      return bookedSlots.some((slot) => !(endTime <= slot.start_time || startTime >= slot.end_time));
    }

    function refreshStartTimeOptions() {
      const hours = currentDuration();
      let hasConflict = false;

      Array.from(startSelect.options).forEach((opt) => {
        if (!opt.value) return;
        const conflict = overlaps(opt.value, hours);
        opt.disabled = conflict;
        opt.textContent = conflict ? opt.value + ' (Booked)' : opt.value;
        if (conflict && opt.value === startSelect.value) hasConflict = true;
      });

      slotWarning.classList.toggle('hidden', !hasConflict);
      submitBtn.disabled = hasConflict;
      submitBtn.classList.toggle('opacity-50', hasConflict);
      submitBtn.classList.toggle('cursor-not-allowed', hasConflict);

      updateSummary();
    }

    function fetchAvailability() {
      if (!dateInput.value) return;
      availabilityHint.textContent = 'Checking availability…';

      fetch('check_availability.php?date=' + encodeURIComponent(dateInput.value))
        .then((res) => res.json())
        .then((data) => {
          bookedSlots = data.booked || [];
          availabilityHint.textContent = bookedSlots.length
            ? bookedSlots.length + ' slot(s) already booked on this date.'
            : 'Studio open 10:00 — 23:00. All slots available.';
          refreshStartTimeOptions();
        })
        .catch(() => {
          availabilityHint.textContent = 'Studio open 10:00 — 23:00.';
        });
    }

    function updateSummary() {
      const hours = currentDuration();
      sumName.textContent = nameInput.value.trim() || '—';
      sumDate.textContent = dateInput.value ? formatDateLong(dateInput.value) : '—';
      sumTime.textContent = startSelect.value ? startSelect.value + ' — ' + addHoursToTime(startSelect.value, hours) : '—';
      sumDuration.textContent = hours + (hours > 1 ? ' Hours' : ' Hour');
      sumTotal.textContent = formatRupiah(hours * RATE_PER_HOUR);
    }

    durationButtons.forEach((btn) => {
      btn.addEventListener('click', () => {
        durationHidden.value = btn.dataset.hours;
        highlightDuration(Number(btn.dataset.hours));
        refreshStartTimeOptions();
      });
    });

    dateInput.addEventListener('change', fetchAvailability);
    startSelect.addEventListener('change', updateSummary);
    nameInput.addEventListener('input', updateSummary);

    highlightDuration(currentDuration());
    updateSummary();

    /* Client-side validation (server always re-validates) */
    bookingForm.addEventListener('submit', (e) => {
      let valid = true;
      const requiredFields = [
        { el: nameInput, check: (v) => v.trim().length > 0, msg: 'Please enter your full name.' },
        { el: document.getElementById('phone'), check: (v) => /^[\d\s+\-()]{8,20}$/.test(v.trim()), msg: 'Please enter a valid WhatsApp number.' },
        { el: document.getElementById('email'), check: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()), msg: 'Please enter a valid email address.' },
        { el: dateInput, check: (v) => v.trim().length > 0, msg: 'Please choose a booking date.' },
        { el: startSelect, check: (v) => v.trim().length > 0, msg: 'Please choose a start time.' },
      ];

      requiredFields.forEach(({ el, check, msg }) => {
        const errorEl = el.parentElement.querySelector('.error-message');
        if (!check(el.value)) {
          valid = false;
          if (errorEl) {
            errorEl.textContent = msg;
            errorEl.classList.remove('hidden');
          }
          el.classList.add('border-red-500/50');
        } else if (errorEl) {
          errorEl.classList.add('hidden');
          el.classList.remove('border-red-500/50');
        }
      });

      if (overlaps(startSelect.value, currentDuration())) {
        valid = false;
        slotWarning.classList.remove('hidden');
      }

      if (!valid) e.preventDefault();
    });
  }

  /* ---------------------------------------------------------------- */
  /* Payment page: method toggle + file upload label                   */
  /* ---------------------------------------------------------------- */
  const paymentForm = document.getElementById('payment-form');
  if (paymentForm) {
    const methodOpts = document.querySelectorAll('.payment-method-opt');
    const bankDetails = document.getElementById('bank-details');
    const qrisDetails = document.getElementById('qris-details');

    methodOpts.forEach((label) => {
      const input = label.querySelector('input[type="radio"]');
      label.addEventListener('click', () => {
        methodOpts.forEach((l) => {
          l.classList.remove('border-violet-500/40', 'bg-violet-500/[0.06]');
          l.classList.add('border-white/10', 'bg-white/[0.02]');
        });
        label.classList.add('border-violet-500/40', 'bg-violet-500/[0.06]');
        label.classList.remove('border-white/10', 'bg-white/[0.02]');

        const isBank = input.value === 'Bank Transfer';
        bankDetails.classList.toggle('hidden', !isBank);
        qrisDetails.classList.toggle('hidden', isBank);
      });
    });

    const proofInput = document.getElementById('proof');
    const proofLabel = document.getElementById('proof-filename');
    if (proofInput && proofLabel) {
      proofInput.addEventListener('change', () => {
        const file = proofInput.files[0];
        proofLabel.textContent = file ? file.name : 'Click to upload JPG, PNG, or PDF (max 5MB)';
      });
    }

    paymentForm.addEventListener('submit', (e) => {
      const errorEl = proofInput.parentElement.parentElement.querySelector('.error-message');
      if (!proofInput.files.length) {
        e.preventDefault();
        if (errorEl) {
          errorEl.textContent = 'Please upload your payment proof.';
          errorEl.classList.remove('hidden');
        }
        return;
      }
      const file = proofInput.files[0];
      const maxBytes = 5 * 1024 * 1024;
      const allowed = ['image/jpeg', 'image/png', 'application/pdf'];
      if (file.size > maxBytes || !allowed.includes(file.type)) {
        e.preventDefault();
        if (errorEl) {
          errorEl.textContent = 'File must be JPG, PNG, or PDF under 5MB.';
          errorEl.classList.remove('hidden');
        }
      } else if (errorEl) {
        errorEl.classList.add('hidden');
      }
    });
  }

  /* ---------------------------------------------------------------- */
  /* Contact form validation                                           */
  /* ---------------------------------------------------------------- */
  const contactForm = document.getElementById('contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      let valid = true;
      const fields = [
        { el: document.getElementById('name'), check: (v) => v.trim().length > 0, msg: 'Please enter your name.' },
        { el: document.getElementById('email'), check: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()), msg: 'Please enter a valid email address.' },
        { el: document.getElementById('phone'), check: (v) => /^[\d\s+\-()]{8,20}$/.test(v.trim()), msg: 'Please enter a valid phone number.' },
        { el: document.getElementById('subject'), check: (v) => v.trim().length > 0, msg: 'Please enter a subject.' },
        { el: document.getElementById('message'), check: (v) => v.trim().length > 0, msg: 'Please enter a message.' },
      ];

      fields.forEach(({ el, check, msg }) => {
        const errorEl = el.parentElement.querySelector('.error-message');
        if (!check(el.value)) {
          valid = false;
          if (errorEl) {
            errorEl.textContent = msg;
            errorEl.classList.remove('hidden');
          }
          el.classList.add('border-red-500/50');
        } else if (errorEl) {
          errorEl.classList.add('hidden');
          el.classList.remove('border-red-500/50');
        }
      });

      if (!valid) e.preventDefault();
    });
  }
})();
