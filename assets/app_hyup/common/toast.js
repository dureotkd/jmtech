function showToast(node) {
  const toastContent = document.createElement("div");
  //   toastContent.innerHTML = `장바구니에 추가되었습니다. <button onclick="alert('취소됨')" class="underline ml-2">취소</button>`;
  toastContent.innerHTML = node;

  Toastify({
    node: toastContent,
    duration: 3000,
    gravity: "bottom",
    position: "center",
    stopOnFocus: true,
    close: false,
    style: {
      background: "#0abab5",
      color: "white",
      borderRadius: "9999px",
      padding: "10px 16px",
      marginBottom: "24px",
    },
  }).showToast();
}
