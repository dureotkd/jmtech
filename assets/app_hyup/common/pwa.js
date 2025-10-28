let deferredPrompt;
let beforeInstallTriggered = false; // ⚡️ 플래그 추가

const installBtn = document.getElementById("install_pwa");

window.addEventListener("beforeinstallprompt", (e) => {
  beforeInstallTriggered = true; // ✅ 이벤트 발생 체크
  e.preventDefault();
  deferredPrompt = e;

  installBtn?.addEventListener("click", async () => {
    deferredPrompt.prompt();
    const choiceResult = await deferredPrompt.userChoice;

    console.log(
      choiceResult.outcome === "accepted" ? "✅ 설치 완료" : "❌ 취소됨"
    );

    if (choiceResult.outcome === "accepted") {
      sessionStorage.setItem("pwaInstalled", "true");
    }

    deferredPrompt = null;
  });
});

window.addEventListener("load", () => {
  setTimeout(() => {
    installBtn?.addEventListener("click", () => {
      if (!beforeInstallTriggered) {
        Swal.fire({
          html: `이미 홈 화면에 추가되어 있습니다<br>바탕화면에서 ERP 아이콘을 눌러 실행하세요.`,
          confirmButtonText: "닫기",
        });
      }
    });
  }, 1500); // 1.5초 기다렸다가 실행 (원하는 시간으로 조정 가능)
});
