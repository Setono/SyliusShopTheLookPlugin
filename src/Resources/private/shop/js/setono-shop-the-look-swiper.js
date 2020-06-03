import jQuery from 'jquery';

// @see https://swiperjs.com/api/#custom-build
import {Swiper, Navigation} from 'swiper/js/swiper.esm'
Swiper.use([Navigation]);

(function( $ ){
  "use strict";

  $(document).ready(function() {
    const formatPrice = function(amount){
      const currencySymbol = $('.setono-shop-the-look-parts').data('currency-symbol');
      return currencySymbol + (amount / 100).toFixed(2);
    };

    $('.setono-shop-the-look-carousel-container').each(function() {
      const swiperClass = '.setono-shop-the-look-carousel-container-' + $(this).data('part-id');
      const swiper = new Swiper(swiperClass, {
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
        on: {
          slideChangeTransitionEnd: function () {
            let lookPrice = 0;
            let lookDiscount = 0;
            let lookTotal = 0;

            $('.swiper-container-initialized').each(function(){
              const activeProduct = $('.swiper-slide-active', this);

              let productPrice = $(activeProduct).data('product-price');
              let productDiscount = $(activeProduct).data('product-discount');

              lookPrice += productPrice;
              lookDiscount += productDiscount;
              lookTotal += productPrice - productDiscount;
            });

            $('.setono-look-price').html(formatPrice(lookPrice));
            $('.setono-look-discount').html(formatPrice(lookDiscount));
            $('.setono-look-total').html(formatPrice(lookTotal));
          }
        },
      });
    });
  });

})( jQuery );
