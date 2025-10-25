$(document).ready(function () {
  $('ul.tabs li').click(function () {
    var tab_id = $(this).attr('data-tab');

    $('ul.tabs li').removeClass('current');
    $('.tab-content').removeClass('current');

    $(this).addClass('current');
    $('#' + tab_id).addClass('current');
  });

  // 관내 영역 열기
  $('.local_array').map((index, row) => {
    if ($(row).is(':checked') == true) {
      let local_code = row.value;
      $(`.detail_local_array.${local_code}`).css('display', '');
    }
  });
  $('.local_except_array').map((index, row) => {
    if ($(row).is(':checked') == true) {
      let local_code = row.value;
      $(`.detail_local_except_array.${local_code}`).css('display', '');
    }
  });
});

$(function () {
  $('#content').keyup(function (e) {
    var content = $(this).val();

    $('#counter').html(content.length);
  });
  $('#content').keyup();
});

// 종목 전체 선택 및 해제
function all_check(this_obj, class_name) {
  var is_checked_all_btn = $(this_obj);

  if (is_checked_all_btn.prop('checked')) {
    $(is_checked_all_btn).prop('checked', true);

    $('.' + class_name).each(function (index, row) {
      if ($(row).prop('checked', false)) {
        if ($('.' + $(row).val() + ' input[type=checkbox]:checked').length < 1) {
          $(row).prop('checked', true);
        }
      }
    });
  } else {
    $('.' + class_name).prop('checked', false);
  }
}

// 완료
function local_part_pop_ok() {
  let local_array = $('.local_array');
  let local_except_array = $('.local_except_array');
  let detail_local_array = $('.detail_local_array');
  let detail_local_except_array = $('.detail_local_except_array');
  let part_array = $('.part_array');
  let part_except_array = $('.part_except_array');
  let part_and_or = $('.part_and_or');

  let part_except_and_or = $('.part_except_and_or');
  let local_array_res = '';
  let local_except_array_res = '';
  let detail_local_array_res = '';
  let detail_local_except_array_res = '';
  let part_array_res = '';
  let part_except_array_res = '';
  let part_and_or_res = '';
  let part_except_and_or_res = '';

  $.each(part_and_or, function (index, _and_or_row) {
    if ($(_and_or_row).is(':checked')) {
      part_and_or_res = $(_and_or_row).val();
    }
  });

  $.each(part_except_and_or, function (index, _except_and_or_row) {
    if ($(_except_and_or_row).is(':checked')) {
      part_except_and_or_res = $(_except_and_or_row).val();
    }
  });

  $.each(local_array, function (index, _local_row) {
    if ($(_local_row).is(':checked')) {
      local_array_res += $(_local_row).val() + '/';
    }
  });

  $.each(local_except_array, function (index, _local_except_row) {
    if ($(_local_except_row).is(':checked')) {
      local_except_array_res += $(_local_except_row).val() + '/';
    }
  });

  $.each(detail_local_array, function (index, _local_row) {
    if ($(_local_row).is(':checked')) {
      detail_local_array_res += $(_local_row).val() + '/';
    }
  });

  $.each(detail_local_except_array, function (index, _local_except_row) {
    if ($(_local_except_row).is(':checked')) {
      detail_local_except_array_res += $(_local_except_row).val() + '/';
    }
  });

  $.each(part_array, function (index, _part_row) {
    if ($(_part_row).is(':checked')) {
      part_array_res += $(_part_row).val() + '/';
    }
  });

  $.each(part_except_array, function (index, _part_except_row) {
    if ($(_part_except_row).is(':checked')) {
      part_except_array_res += $(_part_except_row).val() + '/';
    }
  });

  // 부모창에 적용
  opener.document.getElementById('part_array').value = part_array_res;
  opener.document.getElementById('part_except_array').value = part_except_array_res;
  opener.document.getElementById('local_array').value = local_array_res;
  opener.document.getElementById('local_except_array').value = local_except_array_res;
  opener.document.getElementById('detail_local_array').value = detail_local_array_res;
  opener.document.getElementById('detail_local_except_array').value = detail_local_except_array_res;
  opener.document.getElementById('part_and_or').value = part_and_or_res;
  opener.document.getElementById('part_except_and_or').value = part_except_and_or_res;

  window.close();
}

// 초기화
function local_part_reset() {
  var checkbox_array = $('.table-ck input[type=checkbox]');

  $.each(checkbox_array, function (index, row) {
    $(row).attr('checked', false);
  });

  $.each($('.part_and_or'), function (index, row) {
    $(row).attr('checked', false);
  });

  $.each($('.part_except_and_or'), function (index, row) {
    $(row).attr('checked', false);
  });

  $('#part_and').attr('checked', true);
  $('#part_except_or').attr('checked', true);
}

// 체크박스 선택
function click_checkbox(this_obj, part_code) {
  if ($(this_obj).prop('checked') == true) {
    if ($('.' + part_code + ' input[type=checkbox]:checked').length <= 1) {
      $(this_obj).prop('checked', true);
    } else {
      event.preventDefault();
      return;
    }

    if ($(this_obj).hasClass('local_array')) {
      // 관내 추가
      $(`.detail_local_array.${part_code}`).css('display', '');
      $(`.detail_local_array.${part_code} input[type='checkbox']`).prop('checked', false);
    }
    if ($(this_obj).hasClass('local_except_array')) {
      // 관내 추가
      $(`.detail_local_except_array.${part_code}`).css('display', '');
      $(`.detail_local_except_array.${part_code} input[type='checkbox']`).prop('checked', false);
    }
  } else if ($(this_obj).prop('checked') == false) {
    $(this_obj).prop('checked', false);

    if ($(this_obj).hasClass('local_array')) {
      // 관내 삭제
      $(`.detail_local_array.${part_code}`).css('display', 'none');
      $(`.detail_local_array.${part_code} input[type='checkbox']`).prop('checked', false);
    }
    if ($(this_obj).hasClass('local_except_array')) {
      // 관내 삭제
      $(`.detail_local_except_array.${part_code}`).css('display', 'none');
      $(`.detail_local_except_array.${part_code} input[type='checkbox']`).prop('checked', false);
    }
  }
}
