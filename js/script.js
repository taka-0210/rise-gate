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
  .brand-map-section .brand-journey,
  .sme-focus-closing
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

const masterMapMarkers = document.querySelectorAll('.japan-map__marker');

if (masterMapMarkers.length > 0) {
  const closeMasterMapPopups = (exceptMarker = null) => {
    masterMapMarkers.forEach((marker) => {
      if (marker === exceptMarker) {
        return;
      }

      marker.classList.remove('is-active');
      marker.setAttribute('aria-expanded', 'false');
    });
  };

  const placeMasterMapPopup = (marker) => {
    const popup = marker.querySelector('.master-map-popup');

    if (!popup) {
      return;
    }

    marker.classList.remove('is-popup-below');

    const markerRect = marker.getBoundingClientRect();
    const popupHeight = popup.offsetHeight || 0;
    const viewportGap = 16;
    const aboveTop = markerRect.top - popupHeight - 18;
    const belowBottom = markerRect.bottom + popupHeight + 18;

    if (aboveTop < viewportGap && belowBottom <= window.innerHeight - viewportGap) {
      marker.classList.add('is-popup-below');
    }
  };

  masterMapMarkers.forEach((marker) => {
    marker.setAttribute('aria-expanded', 'false');

    marker.addEventListener('mouseenter', () => {
      placeMasterMapPopup(marker);
    });

    marker.addEventListener('focus', () => {
      placeMasterMapPopup(marker);
    });

    marker.addEventListener('click', (event) => {
      const clickedLink = event.target.closest('a');

      if (clickedLink) {
        event.stopPropagation();
        return;
      }

      event.stopPropagation();

      const isActive = marker.classList.contains('is-active');
      closeMasterMapPopups(marker);

      marker.classList.toggle('is-active', !isActive);
      marker.setAttribute('aria-expanded', String(!isActive));

      if (!isActive) {
        placeMasterMapPopup(marker);
      }
    });

    const popup = marker.querySelector('.master-map-popup');

    if (popup) {
      popup.addEventListener('click', (event) => {
        event.stopPropagation();
      });
    }
  });

  document.addEventListener('click', () => {
    closeMasterMapPopups();
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
      closeMasterMapPopups();
    }
  });

  window.addEventListener('resize', () => {
    masterMapMarkers.forEach((marker) => {
      if (marker.classList.contains('is-active')) {
        placeMasterMapPopup(marker);
      }
    });
  });
}
