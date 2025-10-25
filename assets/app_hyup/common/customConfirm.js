function customConfirm(msg = "", confirmText = "확인", cancelText = "취소") {
  return Swal.fire({
    html: msg,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: confirmText,
    cancelButtonText: cancelText,
    customClass: {
      popup: "my-popup",
      title: "my-title",
      confirmButton: "my-confirm-button",
      cancelButton: "my-cancel-button",
      content: "my-content",
    },
    buttonsStyling: false,
  });
}
