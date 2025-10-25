$(document).ready(function () {
  $(".datepicker").datepicker({
    format: "yyyy-mm-dd", // format
    language: "kr", // 사용언어
    calendarWeeks: false, // 캘린더 왼쪽에 몇 주차인지 보여줌.
    todayHighlight: true, // 오늘 날짜에 하이라이트
    autoclose: true, // 날짜 선택시 자동으로 캘린더 닫힘.
    startView: 0, // 캘린더 처음에 보여줄 것..? 0: 일, 1: 월, 2: 년도
    todayBtn: "linked", // Today 버튼
  });

  search_type_event();
  search_grade_group_ok();

  set_search_array_name();
  set_join_purpose_search_array_name();
});

//클릭 이벤트
$(document).click(function (e) {
  // 회원상태분류 드랍다운 버튼
  if ($(".category-wrap").css("display") == "block") {
    if ($(e.target).parents(".customer-category-wrap").length < 1) {
      if ($(e.target).parents(".category-wrap").length < 1) {
        $(".category-wrap").hide();
        search_grade_group_ok();
      }
    }
  }
});

// 회원 상세 검색에 값이 있을 경우, 상세검색 열기
function detail_search_btn_view() {
  var view_yn = "N";

  $.each($("#toc-content").find(":input"), function (index, row) {
    if ($(row).val() != "") {
      view_yn = "Y";
    }
  });

  if (view_yn == "Y") {
    openCloseToc();
  }
}

// 회원상태분류 버튼
function search_grade_group_btn() {
  var btn_dropdown_box = $(".category-wrap");
  if (btn_dropdown_box.css("display") == "block") {
    btn_dropdown_box.hide();
  } else {
    btn_dropdown_box.show();
  }
}

// 상세검색
function openCloseToc() {
  if (document.getElementById("toc-content").style.display === "block") {
    document.getElementById("toc-content").style.display = "none";
    document.getElementById("toc-toggle").innerHTML =
      "<i class='fas fa-chevron-down'></i>";
  } else {
    document.getElementById("toc-content").style.display = "block";
    document.getElementById("toc-toggle").innerHTML =
      "<i class='fas fa-chevron-up'></i>";
  }
}

// 회원번호 제외 입력박스 - 검색 타입 회원번호 일 경우에만 보이기
function search_type_event() {
  var search_type = $("#search_type");
  const search_type_val = $(search_type).val();

  let is_in_ex_search = false;

  if (
    search_type_val == "user_no" ||
    search_type_val == "company_num" ||
    search_type_val == "mobile"
  ) {
    is_in_ex_search = true;
  }

  if (is_in_ex_search) {
    $(".in-input").prop("name", `search_${search_type_val}_group`);
    $(".except-input").prop("name", `search_${search_type_val}_except_group`);

    $(".textarea-label").show();
    $(".textarea").show();
    $(".textarea").attr("disabled", false);
    $(".self-search").attr("disabled", true);
    $(".self-search").hide();
  } else {
    $(".textarea-label").hide();
    $(".textarea").hide();
    $(".textarea").attr("disabled", true);
    $(".self-search").attr("disabled", false);
    $(".self-search").show();
  }
}

// 회원상태분류 그룹 드롭다운 영역
// O, X 버튼
function set_search_grade(value, this_obj) {
  classname = ".search_grade_" + value;

  if ($(this_obj).hasClass("on")) {
    $(classname).removeClass("on");
  } else {
    $(classname).removeClass("on");
    $(this_obj).addClass("on");
  }
}

// 회원상태분류 그룹 버튼
function search_grade_group_ok() {
  var manage_joint_type_show = 0;
  var all_cnt = 0;

  var search_grade_group_text = ""; // 셀렉트박스에 보여줄 선택한 옵션

  var search_grade_group_value = ""; // input#search_grade_group 에 바꾸어줄 Value 값
  var search_grade_group_not_value = ""; // input#search_grade_group_not 에 바꾸어줄 Value 값

  var search_grade_group_array = $(".search_grade_group"); // search_grade_group 클래스를 가진 태그들
  var search_grade_group_not_array = $(".search_grade_group_not"); // search_grade_group 클래스를 가진 태그들

  // O 선택 옵션
  $.each(search_grade_group_array, function (index, search_grade_row) {
    var class_name = $(search_grade_row).attr("class"); // 클래스 이름 가져오기
    var option_text = $(search_grade_row).text().trim(); // 옵션 텍스트
    var option_id = $(search_grade_row).attr("id"); // 옵션 밸류

    if ($(search_grade_row).hasClass("on")) {
      search_grade_group_text += option_text + " , ";
      search_grade_group_value += option_id + "/";

      if (option_id == "s_manager") {
        manage_joint_type_show += 1;
      }

      all_cnt += 1;
    }
  });

  // X 선택 옵션
  $.each(search_grade_group_not_array, function (index, search_grade_row) {
    var class_name = $(search_grade_row).attr("class"); // 클래스 이름 가져오기
    var option_text = $(search_grade_row).text().trim(); // 옵션 텍스트
    var option_id = $(search_grade_row).attr("id"); // 옵션 밸류

    if ($(search_grade_row).hasClass("on")) {
      search_grade_group_text += option_text + " , ";
      search_grade_group_not_value += option_id + "/";

      all_cnt += 1;
    }
  });

  if (manage_joint_type_show > 0) {
    $(".manage_group").show();
    $("#manage_type").attr("disabled", false);
    $("#manage_joint_type").attr("disabled", false);
    $(".selectpicker").selectpicker("refresh");
  } else {
    $(".manage_group").hide();
    $("#manage_type").attr("disabled", true);
    $("#manage_joint_type").attr("disabled", true);
    $(".selectpicker").selectpicker("refresh");
  }

  if (search_grade_group_text != "") {
    search_grade_group_text = search_grade_group_text.substring(
      0,
      search_grade_group_text.length - 2
    );
    if (all_cnt <= 5) {
      $(".customer-category").html(
        search_grade_group_text + ' <i class="fas fa-caret-down"></i></button>'
      );
    } else {
      $(".customer-category").html(search_grade_group_text);
    }

    //		$(".customer-category").css("background","#17a2b8");
    //		$(".customer-category").css("color","white");
  } else {
    //		$(".customer-category").css("background","#f3f5fa");
    //		$(".customer-category").css("color","");
    $(".customer-category").html(
      '회원상태분류  <i class="fas fa-caret-down"></i></button>'
    );
  }

  $("#search_grade_group").val(search_grade_group_value);
  $("#search_grade_group_not").val(search_grade_group_not_value);

  $(".category-wrap").hide();
}

// 지역/종목설정 팝업
function open_setting_local_part_pop() {
  var url = "/Popup/setting_local_part_pop?";

  var local_array_param = "local_array=" + $("#local_array").val();
  var local_except_array_param =
    "local_except_array=" + $("#local_except_array").val();
  var part_array_param = "part_array=" + $("#part_array").val();
  var part_except_array_param =
    "part_except_array=" + $("#part_except_array").val();
  var part_and_or_param = "part_and_or=" + $("#part_and_or").val();
  var part_except_and_or_param =
    "part_except_and_or=" + $("#part_except_and_or").val();
  var detail_local_array_param =
    "detail_local_array=" + $("#detail_local_array").val();
  var detail_local_except_array_param =
    "detail_local_except_array=" + $("#detail_local_except_array").val();

  var param =
    local_array_param +
    "&" +
    local_except_array_param +
    "&" +
    part_array_param +
    "&" +
    part_except_array_param +
    "&" +
    part_and_or_param +
    "&" +
    part_except_and_or_param +
    "&" +
    detail_local_array_param +
    "&" +
    detail_local_except_array_param;

  w = window.open(url + param, "PopupWin", "width=830px,height=850px");

  w.onbeforeunload = function () {
    set_search_array_name();
  };
}

//쿠폰발행 팝업
function issue_coupon_pop() {
  var check_user_no_all = "";

  var url = "/Popup/coupon_send_pop?";

  var check_box_all = $(".check_receive_user_no");

  check_box_all.each(function (index, row) {
    if ($(row).prop("checked") == true) {
      check_user_no = $(row).val();
      check_user_no_all += $(row).val() + ",";
    }
  });

  if (check_user_no_all == "") {
    alert("회원을 선택해주세요.");
  } else {
    var param = "user_no=" + check_user_no_all;

    w = window.open(url + param, "PopupWin", "width=1190px,height=630px");
  }
}

// 지역/종목 설정한 값 코드를 이름으로 변환
function search_array_name(target_array_name, type, target_class) {
  var target_array_txt = $("input[name='" + target_array_name + "']").val();
  var ajax_url = "/Member_manage/member_list/search_name_array_ajax";
  var ajax_param =
    "target_array_name=" +
    target_array_name +
    "&target_array_txt=" +
    target_array_txt +
    "&type=" +
    type;
  var part_and_or = $("#part_and_or").val();
  var part_except_and_or = $("#part_except_and_or").val();

  var part_and_or = $("#part_and_or").val();
  var part_except_and_or = $("#part_except_and_or").val();

  var join_purpose_and_or = $("#join_purpose_and_or").val();
  var join_purpose_except_and_or = $("#join_purpose_except_and_or").val();

  var type_txt = "";
  var and_or = "";

  if (target_class.indexOf("except") !== -1) {
    if (type == "join_purpose") {
      and_or = join_purpose_except_and_or.toUpperCase();
    } else {
      and_or = part_except_and_or.toUpperCase();
    }
  } else {
    if (type == "join_purpose") {
      and_or = join_purpose_and_or.toUpperCase();
    } else {
      and_or = part_and_or.toUpperCase();
    }
  }

  if (type == "local") {
    type_txt = '<span class="label label-default">지역 : </span>';
  } else if (type == "detail_local") {
    type_txt = '<span class="label label-default">관내 : </span>';
  } else if (type == "part") {
    type_txt =
      '<span class="label label-default">종목 : (' + and_or + ") </span>";
  } else if (type == "join_purpose") {
    type_txt = '<span class="label label-default">(' + and_or + ") </span>";
  }

  $.ajax({
    url: ajax_url,
    type: "POST",
    data: ajax_param,
    dataType: "json",
    success: function (res) {
      if (res.state == "success") {
        $("." + target_class).html(type_txt + res.data);
      }
    },
    error: function (xhr, status, error) {
      console.log("zz");
      // alert('status : ' + xhr.status + ' error : ' + error);
    },
  });
}

//지역/종목 설정한 값 코드를 이름으로 변환 프로세스
function set_search_array_name() {
  let setting_button_active = "false";

  search_array_name("part_array", "part", "part_array_txt");
  search_array_name("part_except_array", "part", "part_except_array_txt");
  search_array_name("local_array", "local", "local_array_txt");
  search_array_name("local_except_array", "local", "local_except_array_txt");
  search_array_name(
    "detail_local_array",
    "detail_local",
    "detail_local_array_txt"
  );
  search_array_name(
    "detail_local_except_array",
    "detail_local",
    "detail_local_except_array_txt"
  );

  if (
    $("#part_array").val() != "" ||
    $("#part_except_array").val() != "" ||
    $("#local_array").val() != "" ||
    $("#local_except_array").val() != "" ||
    $("#detail_local_except_array").val() != "" ||
    $("#detail_local_except_array").val() != ""
  ) {
    setting_button_active = "true";
  }

  if (setting_button_active == "true") {
    //		$(".setting-btn").css("background", "#17a2b8");
    $(".contain_tr").css("display", "table-row");
    $(".except_tr").css("display", "table-row");
  } else {
    //		$(".setting-btn").css("background", "#8d8d8d");
    $(".contain_tr").css("display", "none");
    $(".except_tr").css("display", "none");
  }
}

// 가입목적/세부목적 팝업
function open_setting_join_purpose_pop() {
  let url = "/Popup/setting_join_purpose_pop?";

  let join_purpose_array_param =
    "join_purpose_array=" + $("#join_purpose_array").val();
  let join_purpose_except_array_param =
    "join_purpose_except_array=" + $("#join_purpose_except_array").val();
  let join_purpose_detail_array_param =
    "join_purpose_detail_array=" + $("#join_purpose_detail_array").val();
  let join_purpose_detail_except_array_param =
    "join_purpose_detail_except_array=" +
    $("#join_purpose_detail_except_array").val();
  let join_purpose_and_or_param =
    "join_purpose_and_or=" + $("#join_purpose_and_or").val();
  let join_purpose_except_and_or_param =
    "join_purpose_except_and_or=" + $("#join_purpose_except_and_or").val();

  let param =
    join_purpose_array_param +
    "&" +
    join_purpose_except_array_param +
    "&" +
    join_purpose_detail_array_param +
    "&" +
    join_purpose_detail_except_array_param +
    "&" +
    join_purpose_and_or_param +
    "&" +
    join_purpose_except_and_or_param;

  w = window.open(url + param, "PopupWin", "width=780px,height=860px");

  w.onbeforeunload = function () {
    set_join_purpose_search_array_name();
  };
}

//가입목적/세부목적 이름으로 변환 프로세스
function set_join_purpose_search_array_name() {
  let setting_button_active = "false";

  search_array_name(
    "join_purpose_array",
    "join_purpose",
    "join_purpose_array_txt"
  );
  search_array_name(
    "join_purpose_except_array",
    "join_purpose",
    "join_purpose_except_array_txt"
  );

  if (
    $("#join_purpose_array").val() != "" ||
    $("#join_purpose_except_array").val() != ""
  ) {
    setting_button_active = "true";
  }

  if (setting_button_active == "true") {
    $(".join_purpose_contain_tr").css("display", "table-row");
    $(".join_purpose_except_tr").css("display", "table-row");
  } else {
    $(".join_purpose_contain_tr").css("display", "none");
    $(".join_purpose_except_tr").css("display", "none");
  }
}

// 지역/종목 삭제 ( target_name에서 part_code 삭제 )
function remove_local_part(target_name, part_code, this_obj) {
  // 종목,지역은 제외항목과 포함항목에 동시에 들어갈 수 없음으로 중복은 고려 안해도 됨.
  let origin_val = $("input[name='" + target_name + "']").val();
  let result_val = origin_val.replace(part_code + "/", "");
  $("input[name='" + target_name + "']").val(result_val);

  // 지역을 제거하면 해당 지역의 관내도 제거
  if (target_name == "local_array") {
    $(`[data-local='${part_code}']`).map((index, item) => {
      var node_val = $(item).data("detail");
      var parent_val = $("input[name='detail_local_array']").val();
      parent_val = parent_val.replace(node_val + "/", "");
      $("input[name='detail_local_array']").val(parent_val);
      $(item).parent().remove();
    });
  } else if (target_name == "local_except_array") {
    $(`[data-local='${part_code}']`).map((index, item) => {
      var node_val = $(item).data("detail");
      var parent_val = $("input[name='detail_local_except_array']").val();
      parent_val = parent_val.replace(node_val + "/", "");
      $("input[name='detail_local_except_array']").val(parent_val);
      $(item).parent().remove();
    });
  }

  let parent = $(this_obj).parent();
  $(parent).remove();
  $("input[name='capacity_cost_min[" + part_code + "]']").remove();
  $("input[name='capacity_cost_max[" + part_code + "]']").remove();
  $("input[name='year3_cost_min[" + part_code + "]']").remove();
  $("input[name='year3_cost_max[" + part_code + "]']").remove();
  $("input[name='year5_cost_min[" + part_code + "]']").remove();
  $("input[name='year5_cost_max[" + part_code + "]']").remove();
  $("input[name='year_cost_min[" + part_code + "]']").remove();
  $("input[name='year_cost_max[" + part_code + "]']").remove();
  $("input[name='cost_year[" + part_code + "]']").remove();
  set_search_array_name();
  $(".tooltip").css("display", "none");
}

//페이지 넘기기
function page_move(pageNum) {
  $("input[name='page']").val(pageNum);

  if ($("#chk_debug").is(":checked")) {
    $("input[name='debug']").val("true");
  }

  $("#searchForm").submit();
}

//보여줄 로우 개수 변경
function view_row_cnt_change() {
  var view_row_cnt = $("#s_view_row_cnt").val();
  $("#view_row_cnt").val(view_row_cnt);
}

//일자 변경
function date_change(this_obj) {
  var this_val = $(this_obj).val();

  console.log(this_obj);

  $(this_obj).datepicker("setDate", $(this_obj).val());
  $(this_obj).val($(this_obj).val());
}

// 모든 체크박스 체크
function check_all(this_obj) {
  var is_checked_all_btn = $(this_obj).prop("checked");

  if (is_checked_all_btn) {
    $(".check_receive_user_no").prop("checked", true);
  } else {
    $(".check_receive_user_no").prop("checked", false);
  }
}

//엑셀 다운
function excel_down() {
  if (
    confirm(
      "최대 5,000개 까지 다운로드 됩니다. \n그 이상 데이터가 필요할 시, 개발자에게 문의 부탁드립니다."
    )
  ) {
    $("#is_excel").val("Y");

    if ($("#chk_debug").is(":checked")) {
      $("input[name='debug']").val("true");
    }

    $("#searchForm").submit();

    $("input[name='debug']").val("false");

    $("#is_excel").val("N");
  }
}

// 검색조건 초기화
function reset_all() {
  location.href = "/Member_manage/member_list";
}

var sms_max_cnt = 100;
var sms_cnt = 0;
//phone_type - mobile : 핸드폰, contect_phone : 협정가능 연락처, input : 텍스트박스 값
//area - 동작위치
//areaCode - 구분값
//receiveUserNo - 발신 회원 정보 (구분 : _)
//receiveComNo - 발신 업체 정보
//meetOpenSeq - 만남의 광장 일련번호
//input_id - phone_type이 input일 경우 텍스트박스 ID
function okems_sms_call(phone_type, area, areaCode) {
  var receiveUserNo = "";

  $(".check_receive_user_no").each(function (index, row) {
    if ($(row).prop("checked") == true) {
      receiveUserNo += $(row).val() + ",";
    }
  });

  if (sms_cnt > sms_max_cnt) {
    alert("문자전송은 " + sms_max_cnt + "건 이하로 제한합니다.");
    return;
  }

  if (receiveUserNo == "") {
    alert("선택된 회원이 없습니다.");
    return;
  }

  receiveUserNo = receiveUserNo.substring(0, receiveUserNo.length - 1);

  var param = "?site_type=okems";

  param +=
    "&phone_type=" + phone_type + "&area=" + area + "&areaCode=" + areaCode;

  param += (param == "" ? "?" : "&") + "receiveUserNo=" + receiveUserNo;

  popUpOpen("/Popup/sms_send_pop" + param, "sms_call_12313123", 1032, 640);
}

function popUpOpen(url, title, width, height) {
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
  var scrollbars = "no";
  var resizable = "no";
  var location = "no";
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
    //alert("차단된 팝업창을 허용해 주십시오.");
    return;
  } else {
    code_search.focus();
    return code_search;
  }
}
