$("#all-agree").on("change", function () {
  const isChecked = $(this).is(":checked");
  $(".accent-black").prop("checked", isChecked);
});

function handle_aside() {
  const drawer = $("#my-drawer");
  drawer.click();
}

function handle_phone_format(e) {
  let value = e.target.value.replace(/[^0-9]/g, ""); // 숫자만 남기기

  if (value.length < 4) {
    e.target.value = value;
  } else if (value.length < 8) {
    e.target.value = value.replace(/(\d{3})(\d+)/, "$1-$2");
  } else {
    e.target.value = value
      .replace(/(\d{3})(\d{4})(\d{0,4})/, "$1-$2-$3")
      .replace(/-$/, "");
  }
}

function closeTopBanner() {
  const $banner = $("#top_banner");

  $banner.css("overflow", "hidden").animate(
    {
      height: 0,
      paddingTop: 0,
      paddingBottom: 0,
      marginTop: 0,
      marginBottom: 0,
      opacity: 0,
    },
    300,
    function () {
      setCookie("top_banner_closed", 1, 7); // 쿠키 설정 (7일 동안 유지)
      $(this).remove(); // 필요 없으면 생략 가능
    }
  );
}

function go_add_to_home() {
  Swal.fire({
    text: "홈 화면에 바로가기가 추가되었습니다.",
    icon: "success",
    confirmButtonText: "닫기",
  });
}

function update_user_modal() {
  my_modal_1.showModal();
}

function delete_user_modal() {
  my_modal_2.showModal();
}

function handle_image_upload(e) {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (event) {
      document.getElementById("profile_image").src = event.target.result;
    };
    reader.readAsDataURL(file);
  }
}

function handle_user_update_form(e) {
  e.preventDefault();
  const target = $(e.target);
  const serializedData = target.serializeArray();
  const formData = new FormData();
  // append the serialized data to the FormData object
  serializedData.forEach(function (item) {
    formData.append(item.name, item.value);
  });
  formData.append(
    "profile_image",
    document.getElementById("profileInput").files[0]
  );

  $.ajax({
    url: "/my/update_user",
    type: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json",
    success: function (response) {
      alert(response.msg);

      if (response.ok) {
        my_modal_1.close();
        fadeOutReload();
      }
    },
    error: function () {
      alert("서버 오류가 발생했습니다. 나중에 다시 시도해주세요.");
    },
  });
}

function closeModal(e) {
  const modal = document.getElementById("modal");

  if (e.target === modal) {
    modal.classList.add("hidden");
  }
}

function 스마트로결제금액수정(total_amount, tday) {
  // $("#Amt").val(total_amount); // 결제 금액 업데이트

  $.ajax({
    type: "POST",
    url: "/cart/getEncryptData",
    data: {
      tday: tday,
      total_amount: total_amount,
    },
    dataType: "text",
    success: function (text) {
      $("#Amt").val(total_amount); // 결제 금액 업데이트
      $("#EncryptData").val(text);
    },
  });
}

function 배송조회팝업(tk) {
  const url = `https://www.cjlogistics.com/ko/tool/parcel/tracking?gnbInvcNo=${tk}`;
  window.open(url, "cj_tracking", "width=1350,height=700,scrollbars=yes");
}

function 배송비측정(zipcode, totalAmount) {
  zipcode = parseInt(zipcode.toString().replace(/\D/g, ""), 10);

  let fee = 3000;

  const islandAreas = [
    { from: 22386, to: 22388, fee: 9000 },
    { from: 23004, to: 23010, fee: 9000 },
    { from: 23100, to: 23116, fee: 9000 },
    { from: 23124, to: 23136, fee: 9000 },
    { from: 31708, to: 31708, fee: 9000 },
    { from: 32133, to: 32133, fee: 9000 },
    { from: 33411, to: 33411, fee: 9000 },
    { from: 40200, to: 40240, fee: 9000 },
    { from: 52570, to: 52571, fee: 9000 },
    { from: 53031, to: 53033, fee: 9000 },
    { from: 53089, to: 53104, fee: 9000 },
    { from: 54000, to: 54000, fee: 9000 },
    { from: 46768, to: 46771, fee: 9000 },
    { from: 56347, to: 56349, fee: 9000 },
    { from: 57068, to: 57069, fee: 9000 },
    { from: 58760, to: 58762, fee: 9000 },
    { from: 58800, to: 58866, fee: 9000 },
    { from: 58953, to: 58958, fee: 9000 },
    { from: 59102, to: 59103, fee: 9000 },
    { from: 59106, to: 59106, fee: 9000 },
    { from: 59127, to: 59127, fee: 9000 },
    { from: 59129, to: 59129, fee: 9000 },
    { from: 59137, to: 59166, fee: 9000 },
    { from: 59421, to: 59421, fee: 9000 },
    { from: 59531, to: 59568, fee: 9000 },
    { from: 59650, to: 59650, fee: 9000 },
    { from: 59766, to: 59766, fee: 9000 },
    { from: 59781, to: 59790, fee: 9000 },
    { from: 63000, to: 63644, fee: 6000 }, // 제주도
    { from: 63365, to: 63365, fee: 6000 }, // 우도
    { from: 63000, to: 63001, fee: 6000 }, // 추자도
  ];

  for (const area of islandAreas) {
    if (zipcode >= area.from && zipcode <= area.to) {
      fee = area.fee;
      break;
    }
  }

  if (totalAmount >= 30000) {
    fee -= 3000; // 3만원 이상 주문 시 3,000원 할인
  }

  return fee;
}

function handle_go_detail(room_id) {
  window.location.href = "/room_detail?room_id=" + room_id;
}

function isMobile() {
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
    navigator.userAgent
  );
}

function diffDay(start, end) {
  // endStr은 FullCalendar에서 **선택 종료일의 다음날**로 들어오는 경우가 많음.
  // 그래서 실제 선택한 종료일을 포함하려면 하루를 빼야 함
  const diffTime = end.getTime() - start.getTime();
  const diffDays = diffTime / (1000 * 60 * 60 * 24); // 밀리초 → 일수

  return diffDays;
}

function addCart(productId, quantity) {
  const vsc = $("body").data("vsc");

  if (!vsc) {
    alert("로그인 후 이용해주세요");
    return;
  }

  const cart_key = `cart_${vsc}`;

  // 1. 기존 cart 쿠키 가져오기
  const existing = getCookie(cart_key);
  console.log("🚀 Debug: ~ addCart ~ existing:", existing);

  let cart = [];

  if (existing) {
    try {
      cart = JSON.parse(existing);
    } catch (e) {
      cart = [];
    }

    // 2. 기존에 동일 product_id가 있는지 확인
    const index = cart?.findIndex((item) => item.product_id === productId);

    if (index !== -1) {
      // 기존 제품 있으면 덮어쓰기
      cart[index].quantity = quantity;
    } else {
      // 없으면 추가
      cart.push({
        product_id: productId,
        quantity,
      });
    }
  } else {
    // 2. 기존에 동일 product_id가 있는지 확인
    cart.push({
      product_id: productId,
      quantity,
    });
  }

  setCookie(cart_key, JSON.stringify(cart));
  $("#cartBadge").text(cart.length);
  showToast(
    `장바구니에 추가되었습니다  <button onclick="location.href='/cart'" class="underline ml-2">이동</button>`
  );
}

function openModal() {
  const modal = document.getElementById("modal");
  const modalContent = modal.querySelector("div");

  modal.classList.remove("hidden");
  modalContent.classList.remove("scale-95", "opacity-0");
  modalContent.classList.add("scale-100", "opacity-100");
}

function copyToClipboard(className) {
  const element = document.querySelector(className);
  if (element) {
    const text = (element.textContent || element.value || "").trim(); // 공백 제거
    navigator.clipboard
      .writeText(text)
      .then(() => {})
      .catch((err) => {
        console.error("복사 실패:", err);
      });
  }
}

function empty(value) {
  if (
    value == "" ||
    value == 0 ||
    value == null ||
    value == undefined ||
    (value != null && typeof value == "object" && !Object.keys(value).length)
  ) {
    return true;
  } else {
    return false;
  }
}

function eventcomma(event) {
  // 숫자만 남기기
  let numbersOnly = event.target.value.replace(/[^0-9]/g, "");

  // 천 단위 콤마 넣기
  event.target.value = numbersOnly.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function addcomma(value) {
  // 숫자만 남기기
  let numbersOnly = String(value).replace(/[^0-9]/g, "");

  // 천 단위 콤마 넣기
  return numbersOnly.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function removecomma(val) {
  if (!val) {
    return 0;
  }
  // 숫자만 남기기
  let numbersOnly = val.replace(/[^0-9]/g, "");
  return parseFloat(numbersOnly);
}

// 쿠키 정보
function getCookieCus(Name) {
  var search = Name + "=";

  if (document.cookie.length > 0) {
    // if there are any cookies

    offset = document.cookie.indexOf(search);

    if (offset != -1) {
      offset += search.length;
      end = document.cookie.length;

      if (end == -1) {
        end = document.cookie.length;
      }

      var get_search = unescape(document.cookie.substring(offset, end));
      var get_search_arr = get_search.split(";");

      return decodeURIComponent(get_search_arr[0]);
    }
  }
}

// 콤마 붙이기
function number_format(val) {
  var val = String(val);

  return val.replace(/(\d)(?=(?:\d{3})+(?!\d))/g, "$1,");
}

function set_comma(frm) {
  var frm_cursor_position = frm.selectionStart; // 커서의 현재 위치
  var is_add_comma = false; // 콤마가 있냐 없냐 true : 있음

  var g_set_comma_comma_cnt = (frm.value.match(/,/g) || []).length;

  /*
   * event.keyCode
   * 16 : shift
   * 35 : END
   * 36 : home
   * 37 : 왼쪽방향키
   * 39 : 오른쪽방향키
   */
  if (event != null) {
    if (
      event.keyCode == 16 ||
      event.keyCode == 35 ||
      event.keyCode == 36 ||
      event.keyCode == 37 ||
      event.keyCode == 39
    ) {
      return;
    }
  }

  n = frm.value.replace(/ /g, "");
  n = n.replace(/,/g, "");
  n = n.replace(/^0/g, "");
  n = n.replace(/[^0-9.]/g, "");

  var reg = /(^[+-]?\d+)(\d{3})/;

  n += "";

  while (reg.test(n)) {
    n = n.replace(reg, "$1" + "," + "$2");
    is_add_comma = true;
  }

  frm.value = n;

  if (event.type == "keyup") {
    // keyup 이벤트일때만 커서이동 실행

    var comma_cnt = (frm.value.match(/,/g) || []).length; // 콤마갯수
    if (is_add_comma == true) {
      // 콤마가 있을 시

      if (g_set_comma_comma_cnt != comma_cnt) {
        // 콤마 갯수의 증감이 있으면 커서위치를 +-1

        var frm_position = 0;

        if (event.keyCode == 8) {
          // backspace 를 눌렀을 시 커서를 앞으로 한 칸 이동.
          frm_position = frm_cursor_position - 1;
        } else {
          // 커서를 뒤로 한 칸 이동
          frm_position = frm_cursor_position + 1;
        }

        frm.setSelectionRange(frm_position, frm_position);
      } else {
        var frm_position = 0;
        if (event.keyCode == 8) {
          // backspace 를 눌렀을 시 커서를 앞으로 한 칸 이동.
          frm_position = frm_cursor_position;
        } else {
          frm_position = frm_cursor_position;
        }
        frm.setSelectionRange(frm_position, frm_position);
      }
    } else {
      // 콤마가 없을 시

      var frm_position = 0;
      if (event.keyCode == 8) {
        // backspace 를 눌렀을 시 그대로
        frm_position = frm_cursor_position;
        if (g_set_comma_comma_cnt != comma_cnt) {
          // 콤마 갯수의 증감이 있으면 커서위치를 +-1
          frm_position = frm_position - 1;
        }
        frm.setSelectionRange(frm_position, frm_position);
      } else {
        frm.setSelectionRange(frm_cursor_position, frm_cursor_position);
      }
    }

    g_set_comma_comma_cnt = comma_cnt;
  }
}

function openPopup(url, w, h) {
  const dualScreenLeft =
    window.screenLeft !== undefined ? window.screenLeft : window.screenX;
  const dualScreenTop =
    window.screenTop !== undefined ? window.screenTop : window.screenY;

  const width =
    window.innerWidth || document.documentElement.clientWidth || screen.width;
  const height =
    window.innerHeight ||
    document.documentElement.clientHeight ||
    screen.height;

  const left = (width - w) / 2 + dualScreenLeft;
  const top = (height - h) / 2 + dualScreenTop;

  const newWindow = window.open(
    url,
    "_blank",
    `scrollbars=yes, width=${w}, height=${h}, top=${top}, left=${left}`
  );

  if (window.focus) newWindow.focus();
}

function set_comma_zero(frm) {
  var frm_cursor_position = frm.selectionStart; // 커서의 현재 위치
  var is_add_comma = false; // 콤마가 있냐 없냐 true : 있음

  var g_set_comma_zero_comma_cnt = (frm.value.match(/,/g) || []).length;

  /*
   * event.keyCode
   * 16 : shift
   * 35 : END
   * 36 : home
   * 37 : 왼쪽방향키
   * 39 : 오른쪽방향키
   */
  if (event != null) {
    if (
      event.keyCode == 16 ||
      event.keyCode == 35 ||
      event.keyCode == 36 ||
      event.keyCode == 37 ||
      event.keyCode == 39
    ) {
      return;
    }
  }

  n = frm.value.replace(/ /g, "");
  n = n.replace(/,/g, "");
  n = n.replace(/[^0-9.]/g, "");
  if (/^(0)\d+/g.test(n)) {
    n = n.replace(/^0*/g, "0");
  }

  var reg = /(^[+-]?\d+)(\d{3})/;

  n += "";

  while (reg.test(n)) {
    n = n.replace(reg, "$1" + "," + "$2");
    is_add_comma = true;
  }

  frm.value = n;

  if (event.type == "keyup") {
    // keyup 이벤트일때만 커서이동 실행

    var comma_cnt = (frm.value.match(/,/g) || []).length; // 콤마갯수
    if (is_add_comma == true) {
      // 콤마가 있을 시

      if (g_set_comma_zero_comma_cnt != comma_cnt) {
        // 콤마 갯수의 증감이 있으면 커서위치를 +-1

        var frm_position = 0;

        if (event.keyCode == 8) {
          // backspace 를 눌렀을 시 커서를 앞으로 한 칸 이동.
          frm_position = frm_cursor_position - 1;
        } else {
          // 커서를 뒤로 한 칸 이동
          frm_position = frm_cursor_position + 1;
        }

        frm.setSelectionRange(frm_position, frm_position);
      } else {
        var frm_position = 0;
        if (event.keyCode == 8) {
          // backspace 를 눌렀을 시 커서를 앞으로 한 칸 이동.
          frm_position = frm_cursor_position;
        } else {
          frm_position = frm_cursor_position;
        }
        frm.setSelectionRange(frm_position, frm_position);
      }
    } else {
      // 콤마가 없을 시

      var frm_position = 0;
      if (event.keyCode == 8) {
        // backspace 를 눌렀을 시 그대로
        frm_position = frm_cursor_position;
        if (g_set_comma_zero_comma_cnt != comma_cnt) {
          // 콤마 갯수의 증감이 있으면 커서위치를 +-1
          frm_position = frm_position - 1;
        }
        frm.setSelectionRange(frm_position, frm_position);
      } else {
        frm.setSelectionRange(frm_cursor_position, frm_cursor_position);
      }
    }

    g_set_comma_zero_comma_cnt = comma_cnt;
  }
}

function comma(event) {
  // 숫자만 남기기
  let numbersOnly = event.target.value.replace(/[^0-9]/g, "");

  // 천 단위 콤마 넣기
  event.target.value = numbersOnly.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function pyeongToSquareMeter(pyeong) {
  const squareMeter = pyeong * 3.3058;
  return Math.round(squareMeter * 100) / 100; // 소수점 둘째 자리까지 반올림
}

function set_comma_zero_minus(frm) {
  var frm_cursor_position = frm.selectionStart; // 커서의 현재 위치
  var is_add_comma = false; // 콤마가 있냐 없냐 true : 있음

  var g_set_comma_zero_comma_cnt = (frm.value.match(/,/g) || []).length;

  /*
   * event.keyCode
   * 16 : shift
   * 35 : END
   * 36 : home
   * 37 : 왼쪽방향키
   * 39 : 오른쪽방향키
   */
  if (event != null) {
    if (
      event.keyCode == 16 ||
      event.keyCode == 35 ||
      event.keyCode == 36 ||
      event.keyCode == 37 ||
      event.keyCode == 39
    ) {
      return;
    }
  }

  console.log(frm?.value);

  n = frm.value.replace(/ /g, "");
  n = n.replace(/,/g, "");
  n = n.replace(/[^0-9.]/g, "");
  if (/^(0)\d+/g.test(n)) {
    n = n.replace(/^0*/g, "");
  }

  var reg = /(^[+-]?\d+)(\d{3})/;

  n += "";

  while (reg.test(n)) {
    n = n.replace(reg, "$1" + "," + "$2");
    is_add_comma = true;
  }

  if (frm.value.substring(0, 1) == "-") {
    n = "-" + n;
  }

  frm.value = n;

  if (event.type == "keyup") {
    // keyup 이벤트일때만 커서이동 실행

    var comma_cnt = (frm.value.match(/,/g) || []).length; // 콤마갯수
    if (is_add_comma == true) {
      // 콤마가 있을 시

      if (g_set_comma_zero_comma_cnt != comma_cnt) {
        // 콤마 갯수의 증감이 있으면 커서위치를 +-1

        var frm_position = 0;

        if (event.keyCode == 8) {
          // backspace 를 눌렀을 시 커서를 앞으로 한 칸 이동.
          frm_position = frm_cursor_position - 1;
        } else {
          // 커서를 뒤로 한 칸 이동
          frm_position = frm_cursor_position + 1;
        }

        frm.setSelectionRange(frm_position, frm_position);
      } else {
        var frm_position = 0;
        if (event.keyCode == 8) {
          // backspace 를 눌렀을 시 커서를 앞으로 한 칸 이동.
          frm_position = frm_cursor_position;
        } else {
          frm_position = frm_cursor_position;
        }
        frm.setSelectionRange(frm_position, frm_position);
      }
    } else {
      // 콤마가 없을 시

      var frm_position = 0;
      if (event.keyCode == 8) {
        // backspace 를 눌렀을 시 그대로
        frm_position = frm_cursor_position;
        if (g_set_comma_zero_comma_cnt != comma_cnt) {
          // 콤마 갯수의 증감이 있으면 커서위치를 +-1
          frm_position = frm_position - 1;
        }
        frm.setSelectionRange(frm_position, frm_position);
      } else {
        frm.setSelectionRange(frm_cursor_position, frm_cursor_position);
      }
    }

    g_set_comma_zero_comma_cnt = comma_cnt;
  }
}

// 날짜 일수 차이 계산
function dateDiff(start, end) {
  var start_date = new Date(start);
  var end_date = new Date(end);

  diff_start_date = new Date(
    start_date.getFullYear(),
    start_date.getMonth() + 1,
    start_date.getDate()
  );
  diff_end_date = new Date(
    end_date.getFullYear(),
    end_date.getMonth() + 1,
    end_date.getDate()
  );

  var diff = Math.abs(diff_end_date.getTime() - diff_start_date.getTime());
  diff = Math.ceil(diff / (1000 * 3600 * 24));

  return diff;
}

//만광 참여마감시간 반복 설정
function readableTimeBackLoop(viewId, strdateTime, LoopTime) {
  var str_basic_time = strdateTime;

  $("#" + viewId).text(readableTimeBack(strdateTime));

  setTimeout(function () {
    readableTimeBackLoop(viewId, str_basic_time, LoopTime);
  }, LoopTime);
}

//몇시간 몇분 남음  , 몇일 몇시간 남음 등...
//$datetime의 값이 무조건 현재 시간보다 커야한다. 클때의 표현이다 ㅡㅡㅋ
//만광 참여마감시간
function readableTimeBack(strdateTime) {
  var check_sec = time() - strtotime(strdateTime);

  if (check_sec > 0) {
    return "-";
  }

  var sec = abs(time() - strtotime(strdateTime));

  if (sec < 60) return sec + "초";

  var min = sec / 60;
  if (min < 60) return intval(min) + "분";

  var hour = min / 60;
  var hour_min = (hour - intval(hour)) * 60;
  var hour_sec = (hour_min - intval(hour_min)) * 60;
  //	if(hour < 24) return intval(hour) + '시간 ' + intval(hour_min) + '분' + intval(hour_sec) + '초';
  if (hour < 24) return intval(hour) + "시간 " + intval(hour_min) + "분";

  var day = hour / 24;
  var day_hour = (day - intval(day)) * 24;
  if (day < 7) return intval(day) + "일 " + intval(day_hour) + "시간";

  var week = day / 7;
  var week_day = (week - intval(week)) * 7;
  if (week < 5) return intval(week) + "주";

  var month = day / 30;
  if (month < 24) return intval(month) + "개월";

  var year = day / 365;

  return intval(year) + "년";
}

function time() {
  //  discuss at: http://phpjs.org/functions/time/
  // original by: GeekFG (http://geekfg.blogspot.com)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: metjay
  // improved by: HKM
  //   example 1: timeStamp = time();
  //   example 1: timeStamp > 1000000000 && timeStamp < 2000000000
  //   returns 1: true

  return Math.floor(new Date().getTime() / 1000);
}

function intval(mixed_var, base) {
  //  discuss at: http://phpjs.org/functions/intval/
  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: stensi
  // bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Rafał Kukawski (http://kukawski.pl)
  //    input by: Matteo
  //   example 1: intval('Kevin van Zonneveld');
  //   returns 1: 0
  //   example 2: intval(4.2);
  //   returns 2: 4
  //   example 3: intval(42, 8);
  //   returns 3: 42
  //   example 4: intval('09');
  //   returns 4: 9
  //   example 5: intval('1e', 16);
  //   returns 5: 30

  var tmp;

  var type = typeof mixed_var;

  if (type === "boolean") {
    return +mixed_var;
  } else if (type === "string") {
    tmp = parseInt(mixed_var, base || 10);

    return isNaN(tmp) || !isFinite(tmp) ? 0 : tmp;
  } else if (type === "number" && isFinite(mixed_var)) {
    return mixed_var | 0;
  } else {
    return 0;
  }
}

function abs(mixed_number) {
  //  discuss at: http://phpjs.org/functions/abs/
  // original by: Waldo Malqui Silva
  // improved by: Karol Kowalski
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  //   example 1: abs(4.2);
  //   returns 1: 4.2
  //   example 2: abs(-4.2);
  //   returns 2: 4.2
  //   example 3: abs(-5);
  //   returns 3: 5
  //   example 4: abs('_argos');
  //   returns 4: 0

  return Math.abs(mixed_number) || 0;
}

function strtotime(text, now) {
  //  discuss at: http://phpjs.org/functions/strtotime/
  //     version: 1109.2016
  // original by: Caio Ariede (http://caioariede.com)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Caio Ariede (http://caioariede.com)
  // improved by: A. Mat?as Quezada (http://amatiasq.com)
  // improved by: preuter
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Mirko Faber
  //    input by: David
  // bugfixed by: Wagner B. Soares
  // bugfixed by: Artur Tchernychev
  //        note: Examples all have a fixed timestamp to prevent tests to fail because of variable time(zones)
  //   example 1: strtotime('+1 day', 1129633200);
  //   returns 1: 1129719600
  //   example 2: strtotime('+1 week 2 days 4 hours 2 seconds', 1129633200);
  //   returns 2: 1130425202
  //   example 3: strtotime('last month', 1129633200);
  //   returns 3: 1127041200
  //   example 4: strtotime('2009-05-04 08:30:00 GMT');
  //   returns 4: 1241425800

  var parsed,
    match,
    today,
    year,
    date,
    days,
    ranges,
    len,
    times,
    regex,
    i,
    fail = false;

  if (!text) {
    return fail;
  }

  // Unecessary spaces
  text = text
    .replace(/^\s+|\s+$/g, "")
    .replace(/\s{2,}/g, " ")
    .replace(/[\t\r\n]/g, "")
    .toLowerCase();

  // in contrast to php, js Date.parse function interprets:
  // dates given as yyyy-mm-dd as in timezone: UTC,
  // dates with "." or "-" as MDY instead of DMY
  // dates with two-digit years differently
  // etc...etc...
  // ...therefore we manually parse lots of common date formats
  match = text.match(
    /^(\d{1,4})([\-\.\/\:])(\d{1,2})([\-\.\/\:])(\d{1,4})(?:\s(\d{1,2}):(\d{2})?:?(\d{2})?)?(?:\s([A-Z]+)?)?$/
  );

  if (match && match[2] === match[4]) {
    if (match[1] > 1901) {
      switch (match[2]) {
        case "-": {
          // YYYY-M-D
          if (match[3] > 12 || match[5] > 31) {
            return fail;
          }

          return (
            new Date(
              match[1],
              parseInt(match[3], 10) - 1,
              match[5],
              match[6] || 0,
              match[7] || 0,
              match[8] || 0,
              match[9] || 0
            ) / 1000
          );
        }
        case ".": {
          // YYYY.M.D is not parsed by strtotime()

          return fail;
        }
        case "/": {
          // YYYY/M/D

          if (match[3] > 12 || match[5] > 31) {
            return fail;
          }

          return (
            new Date(
              match[1],
              parseInt(match[3], 10) - 1,
              match[5],
              match[6] || 0,
              match[7] || 0,
              match[8] || 0,
              match[9] || 0
            ) / 1000
          );
        }
      }
    } else if (match[5] > 1901) {
      switch (match[2]) {
        case "-": {
          // D-M-YYYY

          if (match[3] > 12 || match[1] > 31) {
            return fail;
          }

          return (
            new Date(
              match[5],
              parseInt(match[3], 10) - 1,
              match[1],
              match[6] || 0,
              match[7] || 0,
              match[8] || 0,
              match[9] || 0
            ) / 1000
          );
        }
        case ".": {
          // D.M.YYYY

          if (match[3] > 12 || match[1] > 31) {
            return fail;
          }

          return (
            new Date(
              match[5],
              parseInt(match[3], 10) - 1,
              match[1],
              match[6] || 0,
              match[7] || 0,
              match[8] || 0,
              match[9] || 0
            ) / 1000
          );
        }
        case "/": {
          // M/D/YYYY

          if (match[1] > 12 || match[3] > 31) {
            return fail;
          }

          return (
            new Date(
              match[5],
              parseInt(match[1], 10) - 1,
              match[3],
              match[6] || 0,
              match[7] || 0,
              match[8] || 0,
              match[9] || 0
            ) / 1000
          );
        }
      }
    } else {
      switch (match[2]) {
        case "-": {
          // YY-M-D

          if (
            match[3] > 12 ||
            match[5] > 31 ||
            (match[1] < 70 && match[1] > 38)
          ) {
            return fail;
          }

          year = match[1] >= 0 && match[1] <= 38 ? +match[1] + 2000 : match[1];

          return (
            new Date(
              year,
              parseInt(match[3], 10) - 1,
              match[5],
              match[6] || 0,
              match[7] || 0,
              match[8] || 0,
              match[9] || 0
            ) / 1000
          );
        }
        case ".": {
          // D.M.YY or H.MM.SS

          if (match[5] >= 70) {
            // D.M.YY

            if (match[3] > 12 || match[1] > 31) {
              return fail;
            }

            return (
              new Date(
                match[5],
                parseInt(match[3], 10) - 1,
                match[1],
                match[6] || 0,
                match[7] || 0,
                match[8] || 0,
                match[9] || 0
              ) / 1000
            );
          }

          if (match[5] < 60 && !match[6]) {
            // H.MM.SS

            if (match[1] > 23 || match[3] > 59) {
              return fail;
            }

            today = new Date();

            return (
              new Date(
                today.getFullYear(),
                today.getMonth(),
                today.getDate(),
                match[1] || 0,
                match[3] || 0,
                match[5] || 0,
                match[9] || 0
              ) / 1000
            );
          }

          return fail; // invalid format, cannot be parsed
        }
        case "/": {
          // M/D/YY

          if (
            match[1] > 12 ||
            match[3] > 31 ||
            (match[5] < 70 && match[5] > 38)
          ) {
            return fail;
          }

          year = match[5] >= 0 && match[5] <= 38 ? +match[5] + 2000 : match[5];

          return (
            new Date(
              year,
              parseInt(match[1], 10) - 1,
              match[3],
              match[6] || 0,
              match[7] || 0,
              match[8] || 0,
              match[9] || 0
            ) / 1000
          );
        }
        case ":": {
          // HH:MM:SS

          if (match[1] > 23 || match[3] > 59 || match[5] > 59) {
            return fail;
          }

          today = new Date();

          return (
            new Date(
              today.getFullYear(),
              today.getMonth(),
              today.getDate(),
              match[1] || 0,
              match[3] || 0,
              match[5] || 0
            ) / 1000
          );
        }
      }
    }
  }

  // other formats and "now" should be parsed by Date.parse()
  if (text === "now") {
    return now === null || isNaN(now)
      ? (new Date().getTime() / 1000) | 0
      : now | 0;
  }

  if (!isNaN((parsed = Date.parse(text)))) {
    return (parsed / 1000) | 0;
  }

  date = now ? new Date(now * 1000) : new Date();
  days = {
    sun: 0,
    mon: 1,
    tue: 2,
    wed: 3,
    thu: 4,
    fri: 5,
    sat: 6,
  };

  ranges = {
    yea: "FullYear",
    mon: "Month",
    day: "Date",
    hou: "Hours",
    min: "Minutes",
    sec: "Seconds",
  };

  times =
    "(years?|months?|weeks?|days?|hours?|minutes?|min|seconds?|sec" +
    "|sunday|sun\\.?|monday|mon\\.?|tuesday|tue\\.?|wednesday|wed\\.?" +
    "|thursday|thu\\.?|friday|fri\\.?|saturday|sat\\.?)";
  regex =
    "([+-]?\\d+\\s" + times + "|" + "(last|next)\\s" + times + ")(\\sago)?";

  match = text.match(new RegExp(regex, "gi"));

  if (!match) {
    return fail;
  }

  for (i = 0, len = match.length; i < len; i++) {
    if (!process(match[i])) {
      return fail;
    }
  }

  // ECMAScript 5 only
  // if (!match.every(process))
  //    return false;

  return date.getTime() / 1000;
}

const getColumnLetter = (n) => {
  return n >= 0
    ? getColumnLetter(((n / 26) | 0) - 1) + String.fromCharCode(65 + (n % 26))
    : "";
};

function lastNext(type, range, modifier) {
  var diff,
    day = days[range];

  if (typeof day !== "undefined") {
    diff = day - date.getDay();

    if (diff === 0) {
      diff = 7 * modifier;
    } else if (diff > 0 && type === "last") {
      diff -= 7;
    } else if (diff < 0 && type === "next") {
      diff += 7;
    }

    date.setDate(date.getDate() + diff);
  }
}

function go_page(pg) {
  $("#page").val(pg);
  $("#searchFrm").submit();
}

function process(val) {
  var splt = val.split(" "), // Todo: Reconcile this with regex using \s, taking into account browser issues with split and regexes
    type = splt[0],
    range = splt[1].substring(0, 3),
    typeIsNumber = /\d+/.test(type),
    ago = splt[2] === "ago",
    num = (type === "last" ? -1 : 1) * (ago ? -1 : 1);

  if (typeIsNumber) {
    num *= parseInt(type, 10);
  }

  if (ranges.hasOwnProperty(range) && !splt[1].match(/^mon(day|\.)?$/i)) {
    return date["set" + ranges[range]](date["get" + ranges[range]]() + num);
  }

  if (range === "wee") {
    return date.setDate(date.getDate() + num * 7);
  }

  if (type === "next" || type === "last") {
    lastNext(type, range, num);
  } else if (!typeIsNumber) {
    return false;
  }

  return true;
}

const 기다려 = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
const wait = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

/* [팝업] 기본 팝업 열기 (항상 중앙 정렬) */
function open_popup_default(
  url,
  title = "",
  width = 1200,
  height = 800,
  opt = ""
) {
  // 현재 화면 크기 가져오기
  const screenLeft = window.screenLeft ?? window.screenX;
  const screenTop = window.screenTop ?? window.screenY;
  const screenWidth =
    window.innerWidth || document.documentElement.clientWidth || screen.width;
  const screenHeight =
    window.innerHeight ||
    document.documentElement.clientHeight ||
    screen.height;

  // 중앙 위치 계산
  const left = screenLeft + (screenWidth - width) / 2;
  const top = screenTop + (screenHeight - height) / 2;

  // 옵션 문자열 조합
  let option = `width=${width},height=${height},left=${left},top=${top},${opt}`;

  console.log(option);

  window.open(url, title, option);
}

// [팝업] 스크롤 있는 화면 중앙 팝업 - 팝업차단시 return
function popup_open_scroll(url, title, width, height) {
  if (title == "") title = "Popup_Open";
  if (width == "") width = 900;
  if (height == "") height = 520;
  var left = "";
  var top = "";

  //화면 가운데로 배치
  var dim = new Array(2);

  dim = CenterWindow(height, width);
  top = dim[0];
  left = dim[1];

  var toolbar = "no";
  var menubar = "no";
  var status = "no";
  var scrollbars = "yes";
  var resizable = "no";
  var location = "no";
  var diretory = "no";
  var code_search = window.open(
    url,
    title,
    "left=" +
      left +
      ", top=" +
      top +
      ",width=" +
      width +
      ",height=" +
      height +
      ", toolbar=" +
      toolbar +
      ", menubar=" +
      menubar +
      ", status=" +
      status +
      ", scrollbars=" +
      scrollbars +
      ", resizable=" +
      resizable +
      ", location=" +
      location
  );

  if (code_search == null) {
    return;
  } else {
    code_search.focus();
    return code_search;
  }
}

//일반팝업을 중앙에 위치도록 할때
function CenterWindow(height, width) {
  var outx = screen.height;
  var outy = screen.width;
  var x = (outx - height) / 2;
  var y = (outy - width) / 2;
  dim = new Array(2);
  dim[0] = x;
  dim[1] = y;

  return dim;
}

//빈값 체크 이벤트 (null : true, not null : false)
function checkNull(id) {
  if ($(id).val().replace(/\s|　/gi, "") == "") {
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
  s += ""; // 문자열로 변환
  s = s.replace(/^\s*|\s*$/g, ""); // 좌우 공백 제거
  if (s == "" || isNaN(s)) return false;
  return true;
}

/*
 * 숫자 제외 문자제거
 */
function remove_char(val) {
  return val.replace(/[^0-9]/g, "");
}

function change_page(this_obj, page) {
  var page = remove_char(page);

  this_obj.value = page;

  if (event.keyCode == 13) {
    var page_type = $("#page_type").val();

    if (page_type == "condition") {
      condition_paging(page);
    } else {
      paging(page);
    }
  }
}

// 클릭 로그 엑셀 다운로드
function download_log_excel() {
  $("#log_excel_yn").val("Y");
  set_admin_log_view();
  $("#log_excel_yn").val("N");
}

/**
 * @param fixed_obj 고정시킬 fixed 되어있는 객체
 * @param t_b top, bottom px값
 */
function fixed_obj_control(fixed_obj, t_b, ex_height) {
  var top_head_height = Number($(".top-hd").height());
  var content_height = Number($(".cont-wrap").height());

  var content_padding_top = Number(
    $(".cont-wrap").css("padding-top").replace("px", "")
  );
  var content_padding_bottom = Number(
    $(".cont-wrap").css("padding-bottom").replace("px", "")
  );
  var content_margin_top = Number(
    $(".cont-wrap").css("margin-top").replace("px", "")
  );
  var content_margin_bottom = Number(
    $(".cont-wrap").css("margin-top").replace("px", "")
  );

  content_height =
    content_height +
    content_padding_top +
    content_padding_bottom +
    content_margin_top +
    content_margin_bottom -
    ex_height;

  var window_inner_height = Number(window.innerHeight);
  var window_outer_height = Number(window.outerHeight);

  var scroll_top = Number(document.documentElement.scrollTop);
  var scroll_height = Number(document.documentElement.scrollHeight);
  var scroll_width = Number(document.documentElement.scrollWidth);

  /**
   * 브라우저 창높이 - (헤더 높이 + 내용영역 높이) + 스크롤 위치 값
   */
  var standard_height =
    window_inner_height - (top_head_height + content_height) + scroll_top;

  if (standard_height > 0) {
    var auto_save_fixed_bottom = standard_height;
    $(fixed_obj).css(t_b, auto_save_fixed_bottom);
  } else {
    $(fixed_obj).css(t_b, 0);
  }
}

function formatPhoneNumber(el) {
  let number = el.value.replace(/[^0-9]/g, ""); // 숫자만 추출

  if (number.length < 4) {
    el.value = number;
  } else if (number.length < 7) {
    el.value = number.slice(0, 3) + "-" + number.slice(3);
  } else if (number.length < 11) {
    el.value =
      number.slice(0, 3) + "-" + number.slice(3, 6) + "-" + number.slice(6);
  } else {
    el.value =
      number.slice(0, 3) + "-" + number.slice(3, 7) + "-" + number.slice(7, 11);
  }
}

function all_check(e) {
  const isChecked = $(e.target).is(":checked");
  $(".chkbox").prop("checked", isChecked);
}

function clipboard_copy_text(target_id) {
  const content = document.getElementById(target_id).value;

  navigator.clipboard
    .writeText(content)
    .then(() => {
      console.log(`${content}`);
    })
    .catch((err) => {
      console.error(err);
    });
}

// 체크박스 전체 선택 or 해제
function all_chk(this_obj, class_name) {
  const is_chk = this_obj.checked;

  const list_chk_element = document.querySelectorAll(`.${class_name}`);

  list_chk_element.forEach((element) => {
    element.checked = is_chk;
  });
}

// 연락처 하이픈 처리
function autoHypenPhone(str) {
  str = str.replace(/[^0-9]/g, "");
  var tmp = "";
  if (str.length < 4) {
    return str;
  } else if (str.length < 7) {
    tmp += str.substr(0, 3);
    tmp += "-";
    tmp += str.substr(3);
    return tmp;
  } else if (str.length < 11) {
    tmp += str.substr(0, 3);
    tmp += "-";
    tmp += str.substr(3, 3);
    tmp += "-";
    tmp += str.substr(6);
    return tmp;
  } else {
    tmp += str.substr(0, 3);
    tmp += "-";
    tmp += str.substr(3, 4);
    tmp += "-";
    tmp += str.substr(7);
    return tmp;
  }
  return str;
}

function copyToClipboard(text) {
  // 클립보드에 텍스트 복사
  navigator.clipboard
    .writeText(text)
    .then(() => {})
    .catch((err) => {
      console.error("클립보드 복사 실패:", err);
    });
}

function scrollToSection(event, id) {
  const target = $(event.currentTarget);

  // menus active 클래스 제거
  $(".menus .active-link").removeClass("active-link");
  target.addClass("active-link");

  const el = document.getElementById(id);
  if (el) {
    const offsetTop = el.getBoundingClientRect().top + window.scrollY;
    const offset = 54; // 원하는 상단 여백

    window.scrollTo({
      top: offsetTop - offset,
      behavior: "smooth",
    });
  }
}

//숫자와 하이픈 표시
function isNumberOrHyphen(obj) {
  var exp = /[^0-9-]/g;
  if (exp.test(obj.value)) {
    Swal.fire({
      text: "숫자와 '-'만 입력가능합니다.",
      icon: "error",
      confirmButtonText: "닫기",
    });
    obj.value = "";
    obj.focus();
  }
}

// 전화번호에 하이픈 찍어주기
function cvtPhoneNumber(obj) {
  var exp = /-/g;
  var number = obj.value.replace(exp, "");
  var revNumber = reverse(number);
  if (obj.value.length > 2) {
    if (number.substring(0, 2) == "02") {
      obj.value =
        number.substring(0, 2) + "-" + insertHyphen(number.substring(2));
    } else if (
      obj.value.length > 3 &&
      number.substring(0, 2) != "02" &&
      number.substring(0, 1) == "0"
    ) {
      obj.value =
        number.substring(0, 3) + "-" + insertHyphen(number.substring(3));
    } else if (obj.value.length > 4 && number.substring(0, 1) != "0") {
      obj.value =
        number.substring(0, 4) + "-" + insertHyphen(number.substring(4));
    }
  }
}

function reverse(s) {
  var rev = "";

  for (var i = s.length - 1; i >= 0; i--) {
    rev += s.charAt(i);
  }
  return rev;
}

function insertHyphen(target) {
  var rev = reverse(target);
  var cnt = 0;
  if (target.length % 4 != 0) {
    cnt = Math.floor(target.length / 4);
  } else {
    cnt = Math.floor(target.length / 4) - 1;
  }
  var result = "";
  if (cnt > 0) {
    var token = new Array();
    for (var i = 0; i <= cnt; i++) {
      token[i] = reverse(rev.substring(0, 4));
      rev = rev.substring(4);
    }
    for (var i = cnt; i > 0; i--) {
      result = result + token[i] + "-";
    }
    result += token[0];
    return result;
  } else {
    return target;
  }
}

function containsSpecialChar(str) {
  const specialCharPattern = /[!@#$%^&*(),.?":{}|<>]/;
  return specialCharPattern.test(str);
}

function preview_file(event) {
  const previewContainer = document.getElementById("previewContainer");
  const previewImage = document.getElementById("previewImage");

  const file = event.target.files[0];

  if (file && file.type.startsWith("image/")) {
    const reader = new FileReader();
    reader.onload = () => {
      previewImage.src = reader.result;
      previewContainer.classList.remove("hidden");
    };
    reader.readAsDataURL(file);
  }
}

function go_back() {
  $("#backFrm").submit();
}
function detail_open() {
  $(".detail_row").toggleClass("on");
}

function IS_MOBILE() {
  return /Mobi|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
    navigator.userAgent
  );
}
