var lamda_btn;

function btn_start(ele) {
  ele.prop("disabled", true);
  ele.addClass("running");
  ele.append(`<div class="ld ld-ring ld-spin loader"></div>`);
}

function btn_end(ele) {
  ele.removeClass("running");
  ele.find(".loader").remove();
  ele.prop("disabled", false);
}
