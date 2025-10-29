function start_loading() {
  $("#loadingOverlay").addClass("!flex");
}

function stop_loading() {
  $("#loadingOverlay").removeClass("!flex");
}

function start_modal_loading() {
  $(".modal-box .modal-loading").removeClass("hidden");
}

function stop_modal_loading() {
  $(".modal-box .modal-loading").addClass("hidden");
}
