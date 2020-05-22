const updateSlug = function updateSlug(element) {
  const slugInput = element.parents('.content').find('[name*="[slug]"]');
  const loadableParent = slugInput.parents('.field.loadable');

  if (slugInput.attr('readonly') === 'readonly') {
    return;
  }

  loadableParent.addClass('loading');

  $.ajax({
    type: 'GET',
    url: slugInput.attr('data-url'),
    data: { name: element.val() },
    dataType: 'json',
    accept: 'application/json',
    success(response) {
      slugInput.val(response.slug);
      if (slugInput.parents('.field').hasClass('error')) {
        slugInput.parents('.field').removeClass('error');
        slugInput.parents('.field').find('.sylius-validation-error').remove();
      }
      loadableParent.removeClass('loading');
    },
  });
};

const toggleSlugModification = function toggleSlugModification(button, slugInput) {
  if (slugInput.attr('readonly')) {
    slugInput.removeAttr('readonly');
    button.html('<i class="unlock icon"></i>');
  } else {
    slugInput.attr('readonly', 'readonly');
    button.html('<i class="lock icon"></i>');
  }
};

$.fn.extend({
  lookSlugGenerator() {
    let timeout;

    $('[name*="setono_sylius_shop_the_look_look[translations]"][name*="[name]"]').on('input', (event) => {
      clearTimeout(timeout);
      const element = $(event.currentTarget);

      timeout = setTimeout(() => {
        updateSlug(element);
      }, 1000);
    });

    $('.toggle-look-slug-modification').on('click', (event) => {
      event.preventDefault();
      toggleSlugModification($(event.currentTarget), $(event.currentTarget).siblings('input'));
    });
  },
});
