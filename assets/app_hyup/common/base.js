function download_estimate_excel(e) {
  start_loading();

  // Simulate a download process
  $.ajax({
    type: "POST",
    url: "/sales/download_estimate_excel",
    data: {
      type: "suju",
    },
    dataType: "json",
    success: function (response) {},
  });
}

function download_estimate_pdf(e) {
  start_loading();

  // Simulate a download process
  $.ajax({
    type: "POST",
    url: "/sales/download_estimate_pdf",
    data: {
      type: "suju",
    },
    dataType: "json",
    success: function (response) {
      window.location.href = response.url;
    },
    complete: function () {
      stop_loading();
    },
  });
}

// all_check
$("#all_check").on("change", function () {
  var isChecked = $(this).is(":checked");
  $("input[type='checkbox']").prop("checked", isChecked);
});

function only_number_input(e) {
  e.value = e.value.replace(/[^0-9]/g, "");
}

function open_kakao_post_pop() {
  new daum.Postcode({
    oncomplete: function (res) {
      if (res.address) {
        $("input[name='address']").val(res.address);
      }

      if (res.zonecode) {
        $("input[name='zipcode']").val(res.zonecode);
      }
    },
  }).open();
}
