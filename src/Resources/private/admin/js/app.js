import $ from "jquery";
import './setono-shop-the-look-slug';

$(document).ready(() => {
  $(document).previewUploadedImage('#setono_sylius_shop_the_look_look_images');
  $(document).lookSlugGenerator();
});
