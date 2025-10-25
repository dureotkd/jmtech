document.addEventListener("DOMContentLoaded", function () {
  const progressBar = document.getElementById("page-progress-bar");

  // 살짝 느리게 0 → 75%
  setTimeout(() => {
    progressBar.style.width = "75%";
  }, 200); // DOMContentLoaded 후 약간 딜레이

  // 모든 리소스가 로드된 후 천천히 100%
  window.addEventListener("load", function () {
    setTimeout(() => {
      progressBar.style.width = "100%";
    }, 300); // 약간 딜레이 후 100%로 천천히 이동

    // 100% 도달 후 천천히 사라지게
    setTimeout(() => {
      progressBar.style.opacity = "0"; // 부드럽게 사라지게
      setTimeout(() => {
        progressBar.style.display = "none";
      }, 500); // opacity 0 → 완전히 숨기기
    }, 1000); // 100% 도달 후 1초간 유지
  });
});

function open_bottom_sheet(id) {
  document.getElementById(id).openSheet();
}

function close_bottom_sheet(id) {
  document.getElementById(id).closeSheet();
}
