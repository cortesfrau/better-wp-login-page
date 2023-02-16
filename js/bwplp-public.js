jQuery(function($) {

  $(document).ready(function() {

    //------------------------------//
    //--- Center Form Vertically ---//
    //------------------------------//
    var form    = $('#login');
    var offset = form.outerHeight()/2;

    form.css({
      'top' : 'calc(50vh - ' + offset + 'px)',
    });


  });

});
