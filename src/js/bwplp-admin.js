// Color Picker Alpha Channel
import './inc/wp-color-picker-alpha';


jQuery(function($) {

  $(document).ready(function() {


    /**
     * Color Picker
     */
    $('.color-picker').wpColorPicker();


    /**
     * Media Upload Button
     */

    // The Upload button
    $('.bwplp-upload-image-btn').click(function() {
      var send_attachment_bkp = wp.media.editor.send.attachment;
      var button = $(this);
      wp.media.editor.send.attachment = function(props, attachment) {
        $(button).parent().prev().attr('src', attachment.url);
        $(button).prev().val(attachment.id);
        wp.media.editor.send.attachment = send_attachment_bkp;

      };
      wp.media.editor.open(button);
      return false;
    });

    // The Remove button
    $('.bwplp-remove-image-btn').click(function() {
      var answer = confirm('¿Estás seguro/a de que quieres desvincular la imagen?');
      if (answer === true) {
        var src = $(this).parent().prev().attr('data-src');
        $(this).parent().prev().attr('src', src);
        $(this).prev().prev().val('');
      }
      return false;
    });

  });

});
