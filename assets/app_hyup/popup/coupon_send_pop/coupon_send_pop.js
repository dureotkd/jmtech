function service_type_change(user_no, service_type) {
  location.href = '/Adm/Okems/Popup/coupon_send_pop?user_no=' + user_no + '&service_type=' + service_type;
  return;
}

function send_coupon() {
  var is_debug = $('#debug').prop('checked');
  var ajax_url = '/Popup/coupon_send_pop/send_coupon_ajax';
  var ajax_param = $('#coupon_send_form').serialize();

  if (is_debug == true) {
    location.href = ajax_url + '?' + ajax_param;
    return;
  }

  $.ajax({
    async: false,
    url: ajax_url,
    type: 'POST',
    data: ajax_param,
    dataType: 'JSON',
    success: function (res) {
      alert(res.msg);

      if (res.code == 'success') {
        opener.parent.location.reload();
        window.close();
      }
    },
    error: function (xhr, status, error) {
      alert('status : ' + xhr.status + ' error : ' + error);
    },
  });
}

function click_coupon(coupon_seq) {
  $('#coupon_seq_' + coupon_seq).prop('checked', true);
}
