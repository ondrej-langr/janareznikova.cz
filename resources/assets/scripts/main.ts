import blazy from 'blazy';
import Alpine from 'alpinejs';

import KeenSlider from 'keen-slider';
import 'keen-slider/keen-slider.css';

import '../styles/main.scss';
import { init } from './lib/photoswipe';

new blazy({ selector: '.lazy' });

window.Alpine = Alpine;

Alpine.data('dropdown', () => ({
  open: false,

  toggle() {
    this.open = !this.open;
  },
}));

const ACTIVE_CLASSNAME = 'active';
const AUTOPLAY_INTERVAL = 10 * 1000; // 10s

Alpine.data('smallSlider', () => ({
  slider: null,

  init() {
    const itemsRoot = this.$refs.root;

    this.slider = new KeenSlider(itemsRoot, {
      loop: true,
      rubberband: true,
      mode: 'snap',
      created({ slides }) {
        slides[0].classList.add(ACTIVE_CLASSNAME);
      },
      // px-8 opacity-60
      slideChanged(slider) {
        const { animator, slides } = slider;
        const { rel } = slider.track.details;

        slides.map((item: HTMLElement) =>
          item.classList.remove(ACTIVE_CLASSNAME)
        );

        if (animator.targetIdx !== null) {
          slides[rel]?.classList.add(ACTIVE_CLASSNAME);
        }
      },
      slides: {
        perView: 1,
        spacing: 50,
      },
      breakpoints: {
        '(min-width: 768px)': {
          slides: { perView: 2, spacing: 50, origin: 'center' },
        },
        '(min-width: 1000px)': {
          slides: { perView: 3, spacing: 50, origin: 'center' },
        },
      },
    });
  },
}));

Alpine.data('fullscreenSlider', () => ({
  slider: null,
  currentIndex: 0,

  init() {
    const itemsRoot = this.$refs.root;
    const that = this;

    this.slider = new KeenSlider(itemsRoot, {
      loop: true,
      defaultAnimation: {
        duration: 500,
      },
      slideChanged(slider) {
        const { rel } = slider.track.details;
        that.currentIndex = rel;
      },
      detailsChanged: (s) => {
        s.slides.forEach((element: HTMLElement, idx: number) => {
          element.style.opacity = s.track.details.slides[idx].portion;
        });
      },
      renderMode: 'custom',
      slides: {
        perView: 1,
      },
    });

    setInterval(() => {
      if (this.slider) {
        this.slider.next();
      }
    }, AUTOPLAY_INTERVAL);
  },
}));

(() => {
  Alpine.start();

  init({
    element: document.querySelector('.gallery-photoswipe')!,
  }).init();
})();
