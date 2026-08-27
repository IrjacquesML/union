document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-nav-toggle]');
  const nav = document.querySelector('[data-nav]');
  if (toggle && nav) {
    toggle.addEventListener('click', () => nav.classList.toggle('open'));
  }

  const header = document.querySelector('.site-header');
  if (header && document.body.classList.contains('is-home')) {
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 20);
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  const root = document.querySelector('[data-carousel]');
  if (!root) return;

  const slides = Array.from(root.querySelectorAll('.carousel-slide'));
  const dots = Array.from(root.querySelectorAll('[data-carousel-goto]'));
  if (slides.length < 2) return;

  let index = 0;
  let timer = null;
  const delay = 6500;

  const show = (next) => {
    slides[index].classList.remove('is-active');
    if (dots[index]) dots[index].classList.remove('is-active');
    index = (next + slides.length) % slides.length;
    slides[index].classList.add('is-active');
    if (dots[index]) dots[index].classList.add('is-active');
  };

  const start = () => {
    stop();
    timer = window.setInterval(() => show(index + 1), delay);
  };

  const stop = () => {
    if (timer) window.clearInterval(timer);
    timer = null;
  };

  root.querySelector('[data-carousel-prev]')?.addEventListener('click', () => {
    show(index - 1);
    start();
  });
  root.querySelector('[data-carousel-next]')?.addEventListener('click', () => {
    show(index + 1);
    start();
  });
  dots.forEach((dot) => {
    dot.addEventListener('click', () => {
      show(Number(dot.getAttribute('data-carousel-goto')) || 0);
      start();
    });
  });

  let touchX = 0;
  let touchY = 0;
  root.addEventListener('touchstart', (event) => {
    const t = event.changedTouches[0];
    if (!t) return;
    touchX = t.clientX;
    touchY = t.clientY;
    stop();
  }, { passive: true });
  root.addEventListener('touchend', (event) => {
    const t = event.changedTouches[0];
    if (t) {
      const dx = t.clientX - touchX;
      const dy = t.clientY - touchY;
      if (Math.abs(dx) > 48 && Math.abs(dx) > Math.abs(dy)) {
        show(index + (dx < 0 ? 1 : -1));
      }
    }
    start();
  }, { passive: true });

  window.addEventListener('keydown', (event) => {
    if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') return;
    const tag = (event.target && event.target.tagName) || '';
    if (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT') return;
    show(index + (event.key === 'ArrowRight' ? 1 : -1));
    start();
  });

  root.addEventListener('mouseenter', stop);
  root.addEventListener('mouseleave', start);
  start();
});
