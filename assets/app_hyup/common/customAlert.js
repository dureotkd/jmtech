function showAlert({
  title = "알림",
  text = "",
  html = "",
  icon = "",
  confirmText = "확인",
  cancelText = "닫기",
  showCancel = false,
  onConfirm = null,
  onCancel = null,
} = {}) {
  Swal.fire({
    title,
    text,
    html,
    icon,
    confirmButtonText: confirmText,
    cancelButtonText: cancelText,
    showCancelButton: showCancel,
    reverseButtons: true, // 버튼 순서: [확인, 닫기]
    allowOutsideClick: true,
    allowEscapeKey: true,
    backdrop: "rgba(0,0,0,0.4)",
    showClass: { popup: "" },
    hideClass: { popup: "" },
    customClass: {
      popup: "erp-swal",
      confirmButton: "erp-swal-confirm",
      cancelButton: "erp-swal-cancel",
      title: "erp-swal-title",
      htmlContainer: "erp-swal-text",
    },
  }).then((result) => {
    if (result.isConfirmed && typeof onConfirm === "function") onConfirm();
    if (result.isDismissed && typeof onCancel === "function") onCancel();
  });
}
