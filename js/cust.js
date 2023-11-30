jQuery(function($) {

  $('.upload_custom_image_button').click(function(e) {
      e.preventDefault();

      var file_frame = wp.media.frames.file_frame = wp.media({
          title: 'Выбрать изображение',
          button: {
              text: 'Использовать изображение'
          },
          multiple: false
      });

      file_frame.on('select', function() {
          var attachment = file_frame.state().get('selection').first().toJSON();
          $('#custom_product_image_wrapper').show();
          $('#custom_product_image_wrapper img').attr('src', attachment.url);
          $('input#_custom_product_image_id').val(attachment.id);
          $('.upload_custom_image_button').hide();
      });

      file_frame.open();
  });

  $('.remove_custom_image_button').click(function(e) {
      e.preventDefault();
      $('#custom_product_image_wrapper').hide();
      $('#custom_product_image_wrapper img').attr('src', '');
      $('input#_custom_product_image_id').val('');
      $('.upload_custom_image_button').show();
  });

  function clearCustomFields() {
      $('#custom_product_image_wrapper').hide();
      $('#custom_product_image_wrapper img').attr('src', '');
      $('input#_custom_product_image_id').val('');
      $('.upload_custom_image_button').show();
      $('input#_custom_product_date').val('');
      $('select#_custom_product_type').val('');
  }

  if ($('.custom_fields_group .clear_custom_fields_button').length === 0) {
    $('.custom_fields_group').append('<button type="button" class="button clear_custom_fields_button">Очистить все поля</button>');
}

  $('.clear_custom_fields_button').click(function(e) {
      e.preventDefault();
      clearCustomFields();
  });

  $('#publishing-action').hide();

    var buttonText = $('#original_publish').val() === 'Обновить' ? 'Update' : 'Submit';

    $('#publishing-action').after('<div id="custom-publishing-action"><button type="button" id="custom-publish" class="button button-primary button-large">' + buttonText + '</button></div>');

    $('#custom-publish').click(function(e) {
        e.preventDefault();
        $('#publish').click();
    });


});

document.addEventListener('DOMContentLoaded', function() {
    var images = document.querySelectorAll('img.woocommerce-placeholder.wp-post-image');
    images.forEach(function(img) {
        img.style.display = 'none';
    });
});