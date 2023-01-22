import blazy from 'blazy';
import Alpine from 'alpinejs';

import KeenSlider from 'keen-slider';
import 'keen-slider/keen-slider.css';

import '../styles/main.scss';

new blazy({ selector: '.lazy' });

window.Alpine = Alpine;

Alpine.data('dropdown', () => ({
  open: false,

  toggle() {
    this.open = !this.open;
  },
}));

Alpine.data('slider', () => ({
  slider: null,

  init() {
    const itemsRoot = this.$refs.root;

    this.slider = new KeenSlider(itemsRoot, {
      loop: true,
      // px-8 opacity-60
      slideChanged(s) {
        console.log(s);
      },

      slides: {
        perView: 1,
        spacing: 50,
      },
      breakpoints: {
        '(min-width: 700px)': {
          slides: { perView: 3, spacing: 50 },
        },
      },
    });
  },
}));

(() => {
  Alpine.start();
})();
