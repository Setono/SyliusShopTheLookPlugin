import jQuery from 'jquery';

// @see https://swiperjs.com/api/#custom-build
import {Swiper, Navigation} from 'swiper/js/swiper.esm'
Swiper.use([Navigation]);

(function( $ ){
  "use strict";

  $(document).ready(function() {
    $('.setono-shop-the-look-carousel-container').each(function() {
      const swiperClass = '.setono-shop-the-look-carousel-container-' + $(this).data('part-id');
      new Swiper(swiperClass, {
        direction: 'horizontal',
        autoHeight: true,
        centeredSlides: true,
        spaceBetween: 0,
        slidesPerView: 1,
        loop: true,
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
      });
    });
  });

})( jQuery );
