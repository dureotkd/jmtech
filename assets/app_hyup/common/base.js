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
