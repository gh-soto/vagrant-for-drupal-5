function getCoupon() {
  $('#coupon-message').remove();

  var code = $('#edit-panes-coupon-code');
  $.get(Drupal.settings.base_path + "?q=cart/checkout/coupon/" + encodeURIComponent(code.val()), {}, function(coupon) {
    if (coupon.valid) {
      if (window.set_line_item) {
        set_line_item('coupon', coupon.title, -coupon.amount, 2);
      }
    }
    else {
      code.parent().next().after('<div id="coupon-message">' + coupon.message + '</div>');
      if (window.remove_line_item) {
        remove_line_item('coupon');
      }
    }

    if (window.getTax) {
      getTax();
    }
    else if (window.render_line_items) {
      render_line_items();
    }
  }, 'json');
}
