// ✅ 입장 애니메이션
// window.addEventListener("load", () => {
//   requestAnimationFrame(() => {
//     document.body.classList.add("fade-in");
//   });
// });

// window.addEventListener("pageshow", (event) => {
//   document.body.classList.add("fade-in");
// });

// function applyFadeIn() {
//   document.body.classList.remove("fade-out", "fade-in");
//   requestAnimationFrame(() => {
//     document.body.classList.add("fade-in");
//   });
// }

// // window.addEventListener("load", applyFadeIn);
// window.addEventListener("pageshow", applyFadeIn);

// // a태그로 퇴장 애니메이션 적용
// document.querySelectorAll("a").forEach((link) => {
//   if (
//     !link.href.includes("#") &&
//     !link.href.includes("mailto:") &&
//     !link.href.includes("tel:") &&
//     !link.href.includes("javascript:void(0)") &&
//     !link.href.includes(".png") &&
//     !link.hasAttribute("data-no-fade-out")
//   ) {
//     link.addEventListener("click", fadeOut);
//   }
// });

// // ✅ 퇴장 애니메이션
// function fadeOut(event) {
//   event.preventDefault();
//   const link = event.currentTarget.href;
//   document.body.classList.remove("fade-in");
//   document.body.classList.add("fade-out");

//   setTimeout(() => {
//     window.location.href = link;
//   }, 425); // transition 시간과 맞춤
// }

// function fadeOutButton(link) {
//   event.preventDefault();
//   document.body.classList.remove("fade-in");
//   document.body.classList.add("fade-out");

//   setTimeout(() => {
//     window.location.href = link;
//   }, 425); // transition 시간과 맞춤
// }

// function fadeOutReload() {
//   event.preventDefault();
//   document.body.classList.remove("fade-in");
//   document.body.classList.add("fade-out");

//   setTimeout(() => {
//     window.location.reload();
//   }, 425); // transition 시간과 맞춤
// }

// function page_back() {
//   event.preventDefault();
//   document.body.classList.remove("fade-in");
//   document.body.classList.add("fade-out");

//   setTimeout(() => {
//     window.history.back();
//   }, 425); // transition 시간과 맞춤
// }
