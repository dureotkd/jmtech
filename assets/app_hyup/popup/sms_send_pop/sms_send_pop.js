$(document).ready(function () {
  // window.open 함수를 통해 width=1070px,height=780px 로 팝업을 연것과 동일한 사이즈로 팝업을 조절
  window.resizeTo(1086, 848);

  check_phone_number(0, $('#send_tal_type').val());

  $('[data-toggle="tooltip"]').tooltip();

  add_contact_number(); // 협매 관리 연락처 추가
});

function select_category(seq) {
  $('.template-name').removeClass('on2');
  $('.category-con').css('display', 'none');

  $('#category_' + seq).addClass('on2');
  $('#content_' + seq).css('display', '');

  $('#category_seq').val(seq);
}

function select_content(obj) {
  var template_txt = $(obj).next().text();
  var template_seq = $(obj).data('seq');
  var attach_file_no = $(obj).data('attach_file_no');
  set_attach_file_preview(attach_file_no);

  $('textarea[name=content]').val(template_txt);
  getByte(template_txt);
}

// 협매 관리 연락처 추가
function add_contact_number() {
  let manage_contact_data = $("input[name='manage_contact_data']").val();

  if (manage_contact_data != undefined && manage_contact_data != '') {
    manage_contact_data = JSON.parse(manage_contact_data);

    let user_no = manage_contact_data.user_no;
    let manage_contact = manage_contact_data.manage_contact;
    let company_num = manage_contact_data.company_num;

    // 슬래시로 나누고 빈 배열 제거
    let manage_concat_array = manage_contact.split('/').filter(function (item) {
      return item !== null && item !== undefined && item !== '';
    });
    $.each(manage_concat_array, function (index, manage_concat) {
      add_member('okems', company_num, user_no, '관리연락처', '', manage_concat);
    });
  }
}

function add_member(site, company_num, user_no, name, phone, contect_phone) {
  var idx = parseInt($('#idx').val());
  var send_tal_type = $('#send_tal_type').val();
  console.log(send_tal_type);

  var member_html = '<tr class="sms-member" id="send_member_' + idx + '">';
  member_html += '<td class="s-table-con" style="border-left: 1px solid #fff;">';
  member_html += '<input type="checkbox" name="member_idx[]" value="' + idx + '" checked>';
  member_html += '<input type="hidden" name="site[' + idx + ']" value="' + site + '">';
  member_html += '<input type="hidden" name="company_num[' + idx + ']" value="' + company_num + '">';
  member_html += '<input type="hidden" name="member_user_no[' + idx + ']" value="' + user_no + '">';
  member_html += '</td>';
  member_html += '<td class="s-table-con" style="width: 80px;">' + name;
  member_html += '</td>';
  member_html += '<td class="s-table-con">';
  member_html += '<input type="text" class="input-tb mobile" name="receiveMobile[' + idx + ']" ' + (send_tal_type != 'mobile' ? 'style="display: none;"' : '') + ' onkeydown="check_phone_number(\'' + idx + "', 'mobile');\" onkeyup=\"check_phone_number('" + idx + "', 'mobile');\" value=\"" + phone + '">';
  member_html += '<input type="text" class="input-tb contect-phone" name="receiveContectPhone[' + idx + ']" ' + (send_tal_type != 'contect_phone' ? 'style="display: none;"' : '') + ' onkeydown="check_phone_number(\'' + idx + "', 'contect_phone');\" onkeyup=\"check_phone_number('" + idx + "', 'contect_phone');\" value=\"" + contect_phone + '">';
  member_html += '<input type="text" class="input-tb pic-phone" name="receivePicPhone[' + idx + ']" ' + (send_tal_type != 'pic_phone' ? 'style="display: none;"' : '') + ' onkeydown="check_phone_number(\'' + idx + "', 'pic_phone');\" onkeyup=\"check_phone_number('" + idx + "', 'pic_phone');\" value=\"" + contect_phone + '">';
  member_html += '<img src="/assets/adm/okems/popup/img/close-icon.png" alt="이미지" class="close-btn" style="margin-bottom: 2px;" onclick="remove_member(' + idx + ');">';
  member_html += '</td>';
  member_html += '</tr>';

  $('.num-table tbody').append(member_html);
  $('#idx').val(idx + 1);
  check_phone_number(idx, send_tal_type);
  get_member_cnt();
}

function remove_member(idx) {
  $('#send_member_' + idx).remove();
  get_member_cnt();
}

function get_member_cnt() {
  var member_cnt = $("input[name='member_idx[]']").length;

  $('#sms_member_cnt').text(member_cnt);
}

function select_send_tal_type(send_tal_type) {
  check_phone_number(0, send_tal_type);
  console.log(send_tal_type);
  if (send_tal_type === 'mobile') {
    // 전화번호
    $('.mobile').css('display', '');
    $('.contect-phone').css('display', 'none');
    $('.pic-phone').css('display', 'none');
  } else if (send_tal_type === 'contect_phone') {
    // 협정연락처
    $('.contect-phone').css('display', '');
    $('.mobile').css('display', 'none');
    $('.pic-phone').css('display', 'none');
  } else if (send_tal_type === 'pic_phone') {
    // 협정연락처
    $('.pic-phone').css('display', '');
    $('.mobile').css('display', 'none');
    $('.contect-phone').css('display', 'none');
  }
}

function check_all_member(obj) {
  if ($(obj).is(':checked')) {
    $("input[name='member_idx[]']").prop('checked', true);
  } else {
    $("input[name='member_idx[]']").prop('checked', false);
  }
}

function sms_send_proc() {
  var ajax_url = '/Adm/Okems/Popup/sms_send_pop/sms_send_proc';
  var ajax_param = $('#sms_frm').serialize();

  var senderNumber = $("input[name='senderNumber']").val().replace(/-/gi, '');
  var member_idx = $("input[name='member_idx[]']");
  var send_tal_type = $("select[name='send_tal_type']").val();

  if (checkNull($('textarea[name=content]'))) {
    alert('전송할 메시지를 입력하세요.');
    $('textarea[name=content]').focus();
    return;
  }

  if (checkNull($("input[name='senderNumber']"))) {
    alert('발송번호를 입력하세요.');
    $("input[name='senderNumber']").focus();
    return;
  }

  if ($("input[name='member_idx[]']").length < 1) {
    alert('발신번호가 존재하지 않습니다.\n발신번호를 1개 이상 추가해주세요.');
    return;
  }

  if ($("input[name='member_idx[]']:checked").length < 1) {
    alert('발신번호가 체크된 회원이 없습니다.\n발신번호를 1개 이상 체크해주세요.');
    return;
  }

  if (!isNumber(senderNumber)) {
    alert("발신번호는 숫자와 '-'만 입력할 수 있습니다.");
    return;
  }

  var is_phone_number = 'Y';
  var regexMdn = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/;
  $(member_idx).each(function (i, obj) {
    var phone_number = '';
    var val = $(obj).val();

    if (send_tal_type == 'mobile') {
      phone_number = $.trim($("input[name='receiveMobile[" + val + "]']").val()).replace(/-/g, '');
    } else if (send_tal_type == 'contect_phone') {
      phone_number = $.trim($("input[name='receiveContectPhone[" + val + "]']").val()).replace(/-/g, '');
    } else if (send_tal_type == 'pic_phone') {
      phone_number = $.trim($("input[name='receivePicPhone[" + val + "]']").val()).replace(/-/g, '');
    }

    if (phone_number == '') {
      alert(i + 1 + '번의 발신번호를 입력하세요.');
      is_phone_number = 'N';
      return false;
    }

    if (!isNumber(phone_number)) {
      alert(i + 1 + "번의 발신번호가 잘못되었습니다. 숫자와 '-'만 입력할 수 있습니다.");
      is_phone_number = 'N';
      return false;
    }

    if (!regexMdn.test(phone_number)) {
      alert(i + 1 + '번은 잘못된 전화번호입니다.');
      is_phone_number = 'N';
      return false;
    }

    if (phone_number.substring(0, 3) == '010') {
      //010은 가운데 번호 3자리 미허용 글자수 11
      if (phone_number.length != 11) {
        alert(i + 1 + '번은 잘못된 전화번호입니다.');
        is_phone_number = 'N';
        return false;
      }
    } else {
      //010 제외는 가운데 번호 3자리 허용 글자수 10 또는 11
      if (phone_number.length != 10 && phone_number.length != 11) {
        alert(i + 1 + '번은 잘못된 전화번호입니다.');
        is_phone_number = 'N';
        return false;
      }
    }
  });

  if (is_phone_number == 'Y' && confirm('문자(총' + $("input[name='member_idx[]']:checked").length + '건)를 전송하시겠습니까?')) {
    $.ajax({
      async: false,
      url: ajax_url,
      type: 'POST',
      data: ajax_param,
      dataType: 'JSON',
      success: function (res) {
        alert(res.msg);
      },
      error: function (xhr, status, error) {
        alert('status : ' + xhr.status + ' error : ' + error);
      },
    });
  }
}

function open_message_member_search_pop() {
  var pop_url = '/Adm/Okems/Popup/message_member_search_pop';
  var pop_name = 'member_search_pop';
  var pop_opt = 'width=830px,height=480px';

  window.open(pop_url, pop_name, pop_opt);
}

function open_sms_template_mamage_pop() {
  var pop_url = '/Adm/Okems/Popup/sms_template_mamage_pop';
  var pop_name = 'sms_template_mamage_pop';
  var pop_opt = 'width=770px,height=800px';
  var category_seq = $('#category_seq').val();

  pop_url += '?category_seq=' + category_seq;

  window.open(pop_url, pop_name, pop_opt);
}

/**
 * 글자의 바이트 수 추출
 * @param str
 * @returns {Number}
 */
function getByte(str) {
  var resultSize = 0;
  if (str == null) {
    return 0;
  }

  for (var i = 0; i < str.length; i++) {
    var c = escape(str.charAt(i));
    if (c.length == 1) {
      resultSize++;
    } else if (c.indexOf('%u') != -1) {
      resultSize += 2;
    } else {
      resultSize++;
    }
  }

  $('.byte-ck span').text(resultSize);
  if (resultSize > 80) {
    $('.byte-ck span').css('color', '#fdff30');
    $('.byte-ck p').css('color', '#fdff30');
  } else {
    $('.byte-ck span').css('color', '#fff');
    $('.byte-ck p').css('color', '#fff');
  }
}

//빈값 체크 이벤트 (null : true, not null : false)
function checkNull(obj) {
  if ($(obj).val().replace(/\s|　/gi, '') == '') {
    return true;
  }

  return false;
}

/**
 * 숫자인지 검사
 * @param s
 * @returns {Boolean}
 */
function isNumber(s) {
  s += ''; // 문자열로 변환
  s = s.replace(/^\s*|\s*$/g, ''); // 좌우 공백 제거
  if (s == '' || isNaN(s)) return false;
  return true;
}

function check_phone_number(idx, send_tal_type) {
  if ($('.sms-member').length === 0) {
    return;
  }

  var regexMdn = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/;

  if (idx == 0) {
    $('.sms-member').each(function () {
      var index = $(this)
        .attr('id')
        .replace(/send\_member\_/g, '');
      var is_has_class = $(this).hasClass('wrong-phone-number');

      if (send_tal_type === 'mobile') {
        var mobile_obj = $("input[name='receiveMobile[" + index + "]']");

        $(mobile_obj).val(
          $(mobile_obj)
            .val()
            .replace(/[^0-9]/g, '')
            .replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/, '$1-$2-$3')
            .replace('--', '-'),
        );

        var phone_num = mobile_obj.val();
      } else if (send_tal_type === 'contect_phone') {
        var contect_phone_obj = $("input[name='receiveContectPhone[" + index + "]']");

        $(contect_phone_obj).val(
          $(contect_phone_obj)
            .val()
            .replace(/[^0-9]/g, '')
            .replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/, '$1-$2-$3')
            .replace('--', '-'),
        );

        var phone_num = contect_phone_obj.val();
      } else {
        var pic_phone_obj = $("input[name='receivePicPhone[" + index + "]']");

        $(pic_phone_obj).val(
          $(pic_phone_obj)
            .val()
            .replace(/[^0-9]/g, '')
            .replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/, '$1-$2-$3')
            .replace('--', '-'),
        );

        var phone_num = pic_phone_obj.val();
      }

      if (!regexMdn.test(phone_num) && is_has_class === false) {
        $(this).addClass('wrong-phone-number');
      }

      if (regexMdn.test(phone_num) && is_has_class === true) {
        $(this).removeClass('wrong-phone-number');
      }
    });
  } else {
    var obj = $('#send_member_' + idx);
    var is_has_class = $(obj).hasClass('wrong-phone-number');

    if (send_tal_type === 'mobile') {
      var mobile_obj = $("input[name='receiveMobile[" + idx + "]']");

      $(mobile_obj).val(
        $(mobile_obj)
          .val()
          .replace(/[^0-9]/g, '')
          .replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/, '$1-$2-$3')
          .replace('--', '-'),
      );

      var phone_num = mobile_obj.val();
    } else if (send_tal_type === 'contect_phone') {
      var contect_phone_obj = $("input[name='receiveContectPhone[" + idx + "]']");

      $(contect_phone_obj).val(
        $(contect_phone_obj)
          .val()
          .replace(/[^0-9]/g, '')
          .replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/, '$1-$2-$3')
          .replace('--', '-'),
      );

      var phone_num = contect_phone_obj.val();
    } else {
      var pic_phone_obj = $("input[name='receivePicPhone[" + idx + "]']");

      $(pic_phone_obj).val(
        $(pic_phone_obj)
          .val()
          .replace(/[^0-9]/g, '')
          .replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/, '$1-$2-$3')
          .replace('--', '-'),
      );

      var phone_num = pic_phone_obj.val();
    }

    if (!regexMdn.test(phone_num) && is_has_class === false) {
      $(obj).addClass('wrong-phone-number');
    }

    if (regexMdn.test(phone_num) && is_has_class === true) {
      $(obj).removeClass('wrong-phone-number');
    }
  }
}

function go_at() {
  location.href = '/Adm/Okems/Popup/at_send_pop' + location.search;
}

function go_old_sms() {
  location.href = '/Admin/Popup/send_message_popup' + location.search;
}

function target_toggle(target_id) {
  const target_display = $(`#${target_id}`).css('display');

  switch (target_display) {
    case 'none':
      $(`#${target_id}`).show();
      break;
    case 'block':
      $(`#${target_id}`).hide();
      break;
  }
}

/**
 * 첨부파일 업로드
 *
 * 파일의 저장된 seq 와 name 을 반환
 */
function upload_attach_file(this_obj) {
  let is_max = true;
  $("input[name='attached_file[]'][value='']").map((idx, row) => {
    is_max = false;
  });

  if (is_max) {
    alert('파일은 최대 3개까지만 첨부가 가능합니다.');
    return;
  }

  if (this_obj.files[0].size > 61440) {
    alert('파일은 100KB 이하로 첨부가 가능합니다.');
    return;
  }

  if (this_obj.files[0].type != 'image/jpeg') {
    alert('jpg 또는 jpeg 의 이미지 파일만 첨부가 가능합니다.');
    return;
  }

  // 업로드할 파일
  let attach_files = $('#attach_file_tmp')[0].files;

  if (attach_files.length == 0) {
    return;
  }

  let formData = new FormData();
  for (let i = 0; i < attach_files.length; i++) {
    formData.append(`files[${i}]`, attach_files[i]);
  }

  // 이미 업로드된 파일
  let attached_file = $("input[name='attached_file[]'");
  for (let i = 0; i < attached_file.length; i++) {
    let attached_file_val = $(attached_file[i]).val();
    if (attached_file_val != '' && attached_file_val != undefined) {
      formData.append('attached_file[]', attached_file_val);
    }
  }

  // Promise 를 사용한 AJAX 함수 순차 실행
  new Promise((resolve, reject) => {
    $.ajax({
      url: '/Adm/Okems/Popup/sms_send_pop/upload_attach_file',
      type: 'POST',
      async: true,
      data: formData,
      dataType: 'JSON',
      cache: false,
      contentType: false,
      processData: false,
      before: () => {
        // 전송버튼 비활성화
        $('.ck-btn').attr('disabled', true);
        $('.attach-file-upload-btn').attr('disabled', true);
      },
      xhr: () => {
        let xhr = $.ajaxSettings.xhr(); // XMLHttpRequest 재정의 가능
        xhr.upload.onprogress = function (e) {
          // progress 이벤트 리스너 추가
          let percent = (e.loaded * 100) / e.total;
          $('#progress_bar').val(percent);
        };
        return xhr;
      },
      success: (data) => {
        // console.log("파일 업로드 결과", data);
        if (data.msg != 'success') {
          alert(data.msg);
          return data.msg;
        }

        let file_no_arr = data.file.file_no.split(',');
        // console.log("파일 seq 배열",file_no_arr);
        $(file_no_arr).map((index, value) => {
          if ($("input[name='attached_file[]']").eq(0).val() == '') {
            $("input[name='attached_file[]']").eq(0).val(value);
          } else if ($("input[name='attached_file[]']").eq(1).val() == '') {
            $("input[name='attached_file[]']").eq(1).val(value);
          } else if ($("input[name='attached_file[]']").eq(2).val() == '') {
            $("input[name='attached_file[]']").eq(2).val(value);
          }
        });
        resolve(data);
      },
      error: (e) => {
        console.log('ajax error', e);
      },
      complete: () => {
        // 전송버튼 활성화
        $('.ck-btn').attr('disabled', false);
        $('.attach-file-upload-btn').attr('disabled', false);
        $('#progress_bar').val('0');
      },
    });
  }).then((data) => {
    // 파일 input 비우기
    $('#attach_file_tmp').val('');

    // 파일 미리보기 재배치
    set_attach_file_preview();
  });
}

// 파일 삭제
function remove_attach_file(idx) {
  $("input[name='attached_file[]']").eq(idx).val('');
  $('.img_preview').eq(idx).attr('src', '');
  $('.img_preview').eq(idx).attr('data-original-title', '');

  // 파일 미리보기 재배치
  set_attach_file_preview();
}

// 등록된 이미지 미리보기
function set_attach_file_preview(attach_file_no = null) {
  if (attach_file_no == null) {
    $("input[name='attached_file[]']").map((index, row) => {
      if (attach_file_no == '') {
        attach_file_no += row.value;
      } else {
        attach_file_no += ',' + row.value;
      }
    });
  }

  $.ajax({
    url: '/Adm/Okems/Popup/sms_send_pop/get_files_all',
    type: 'POST',
    async: false,
    data: { attach_file_no: attach_file_no },
    dataType: 'JSON',
    success: (data) => {
      // console.log("이미지 가져오기 결과", data);
      return data;
    },
    error: (e) => {
      console.log('ajax error', e);
    },
  }).then((data) => {
    // 첨부 파일 없음 처리
    if (data.length == 0) {
      // console.log("첨부 파일 없음");
      $('.attach-img-list-area.exist').css('display', 'none');
      $('.attach-img-list-area.empty').css('display', '');
      return;
    }

    // 첨부파일seq 비우기
    $("input[name='attached_file[]']").val('');

    // 첨부 파일 영역 display
    $('.attach-img-list-area.exist').css('display', '');
    $('.attach-img-list-area.empty').css('display', 'none');

    // 파일 이미지 경로 지정
    $('.img_preview').css('display', 'none');
    $('.img-item-del').css('display', 'none');
    data.map((row, idx) => {
      // console.log(row);
      // row.file_no; row.save_name; row.name; row.path, row.size;
      let kbyte_size = (row.size / 1024).toFixed(2);
      $('.img_preview').eq(idx).attr('src', `${row.path}/${row.save_name}`);
      $('.img_preview').eq(idx).attr('data-original-title', `${row.name} (${kbyte_size}KB)`);
      $('.img_preview').eq(idx).css('display', '');
      $('.img-item-del').eq(idx).css('display', '');
      $("input[name='attached_file[]']").eq(idx).val(row.file_no);
    });

    // 첨부 파일 카운트
    $('.attach-img-cnt').text(data.length + '/3');
  });
}
