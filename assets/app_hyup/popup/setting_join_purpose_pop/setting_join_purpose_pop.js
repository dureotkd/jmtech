$(document).ready(function () {
  $('ul.tabs li').click(function () {
    var tab_id = $(this).attr('data-tab');

    $('ul.tabs li').removeClass('current');
    $('.tab-content').removeClass('current');

    $(this).addClass('current');
    $('#' + tab_id).addClass('current');
  });
});

// 완료
function join_purpose_pop_ok() {
  let join_purpose_array = $('.join_purpose_array');
  let join_purpose_except_array = $('.join_purpose_except_array');
  let join_purpose_detail_array = $('.join_purpose_detail_array');
  let join_purpose_detail_except_array = $('.join_purpose_detail_except_array');
  let join_purpose_and_or = $('.join_purpose_and_or');
  let join_purpose_except_and_or = $('.join_purpose_except_and_or');

  let join_purpose_array_res = '';
  let join_purpose_except_array_res = '';
  let join_purpose_detail_array_res = '';
  let join_purpose_detail_except_array_res = '';
  let join_purpose_and_or_res = '';
  let join_purpose_except_and_or_res = '';

  $.each(join_purpose_and_or, function (index, _and_or_row) {
    if ($(_and_or_row).is(':checked')) {
      join_purpose_and_or_res = $(_and_or_row).val();
    }
  });

  $.each(join_purpose_except_and_or, function (index, _except_and_or_row) {
    if ($(_except_and_or_row).is(':checked')) {
      join_purpose_except_and_or_res = $(_except_and_or_row).val();
    }
  });

  $.each(join_purpose_array, function (index, _join_purpose_row) {
    if ($(_join_purpose_row).is(':checked')) {
      join_purpose_array_res += $(_join_purpose_row).val() + '/';
    }
  });

  $.each(join_purpose_except_array, function (index, _join_purpose_except_row) {
    if ($(_join_purpose_except_row).is(':checked')) {
      join_purpose_except_array_res += $(_join_purpose_except_row).val() + '/';
    }
  });

  $.each(join_purpose_detail_array, function (index, _join_purpose_detail_row) {
    if ($(_join_purpose_detail_row).is(':checked')) {
      join_purpose_detail_array_res += $(_join_purpose_detail_row).val() + '/';
    }
  });

  $.each(join_purpose_detail_except_array, function (index, _join_purpose_detail_except_row) {
    if ($(_join_purpose_detail_except_row).is(':checked')) {
      join_purpose_detail_except_array_res += $(_join_purpose_detail_except_row).val() + '/';
    }
  });

  // 부모창에 적용
  opener.document.getElementById('join_purpose_array').value = join_purpose_array_res;
  opener.document.getElementById('join_purpose_except_array').value = join_purpose_except_array_res;
  opener.document.getElementById('join_purpose_detail_array').value = join_purpose_detail_array_res;
  opener.document.getElementById('join_purpose_detail_except_array').value = join_purpose_detail_except_array_res;
  opener.document.getElementById('join_purpose_and_or').value = join_purpose_and_or_res;
  opener.document.getElementById('join_purpose_except_and_or').value = join_purpose_except_and_or_res;

  window.close();
}

// 초기화
function join_purpose_reset() {
  var checkbox_array = $('.popup-wrap input[type=checkbox]');

  $.each(checkbox_array, function (index, row) {
    $(row).prop('checked', false);
  });

  $.each($('.join_purpose_and_or'), function (index, row) {
    $(row).prop('checked', false);
  });

  $.each($('.join_purpose_except_and_or'), function (index, row) {
    $(row).prop('checked', false);
  });

  $('#join_purpose_and').prop('checked', true);
  $('#join_purpose_except_or').prop('checked', true);
}

// 체크박스 선택
function click_join_purpose(this_obj, excpt_yn) {
  if ($(this_obj).prop('checked') == false) {
    if (excpt_yn == 'N') {
      $('.join_purpose_' + this_obj.value).prop('checked', false);
    } else {
      $('.join_purpose_except_' + this_obj.value).prop('checked', false);
    }
  }
}

// 체크박스 선택
function click_join_purpose_detail(this_obj, join_purpose, excpt_yn) {
  if ($(this_obj).prop('checked') == true) {
    if (excpt_yn == 'N') {
      $('#join_purpose_' + join_purpose).prop('checked', true);
    } else {
      $('#join_purpose_except_' + join_purpose).prop('checked', true);
    }
  }
}
