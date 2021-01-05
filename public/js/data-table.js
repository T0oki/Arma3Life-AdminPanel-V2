(function($) {
  'use strict';
  $(function() {
    $('#order-listing').DataTable({
      "aLengthMenu": [[12, 52, 50, -1], [12, 25, 50, "All"]],
      "iDisplayLength": 12
    });
  });
  $(function() {
    $('#order-listing-2').DataTable({
      "aLengthMenu": [[12, 52, 50, -1], [12, 25, 50, "All"]],
      "iDisplayLength": 12
    });
  });
  $(function() {
    $('#order-listing-3').DataTable({
      "aLengthMenu": [[12, 52, 50, -1], [12, 25, 50, "All"]],
      "iDisplayLength": 12
    });
  });
})(jQuery);
