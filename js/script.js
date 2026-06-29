const navToggle = document.querySelector('.nav-toggle');
const globalNav = document.querySelector('.global-nav');

if (navToggle && globalNav) {
  navToggle.addEventListener('click', () => {
    const isOpen = globalNav.classList.toggle('is-open');
    navToggle.setAttribute('aria-expanded', String(isOpen));
  });

  globalNav.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
      globalNav.classList.remove('is-open');
      navToggle.setAttribute('aria-expanded', 'false');
    });
  });

  document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape') {
      return;
    }

    globalNav.classList.remove('is-open');
    navToggle.setAttribute('aria-expanded', 'false');
  });
}

const revealTargets = document.querySelectorAll(`
  main > section:not(.home-hero):not(.page-hero):not(.brand-map-section),
  .brand-map-section .section-heading,
  .brand-map-section .brand-journey
`);

if (revealTargets.length > 0) {
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (prefersReducedMotion || !('IntersectionObserver' in window)) {
    revealTargets.forEach((target) => {
      target.classList.add('is-visible');
    });
  } else {
    revealTargets.forEach((target) => {
      target.classList.add('reveal-section');
    });

    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) {
          return;
        }

        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    }, {
      rootMargin: '0px 0px -35% 0px',
      threshold: 0.01,
    });

    revealTargets.forEach((target) => {
      revealObserver.observe(target);
    });
  }
}
