// // 공통 인터셉터 설정
$.ajaxSetup({
  async: false,
  dataType: "json",
});

$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
  // 기존 success 저장
  var originalSuccess = options.success;
  options.success = function (data, status, xhr) {
    if (data.msg) {
      alert(data.msg);
    }

    // 원래 정의된 콜백 호출
    if (typeof originalSuccess === "function") {
      originalSuccess(data, status, xhr);
    }
  };
});
