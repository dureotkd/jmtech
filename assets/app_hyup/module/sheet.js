/**
 * Minified by jsDelivr using Terser v5.19.2.
 * Original file: /npm/@solb/bottom-sheet@1.0.0/dist/index.js
 *
 * Do NOT use SRI with dynamically generated files! More information: https://www.jsdelivr.com/using-sri-with-dynamic-files
 */
"use strict";
class BottomSheet extends HTMLElement {
  constructor() {
    super(),
      (this.defaultVh = 0),
      (this.beforeVh = 0),
      (this.sheetHeight = 0),
      (this.mobileVh = 0.01 * window.innerHeight);
  }
  connectedCallback() {
    this.setAttribute("aria-hidden", "true"), this.renderBottomSheet();
  }
  renderBottomSheet() {
    const e = this.getAttribute("id");
    (this.className = "customBottomsheet"),
      isMobile || this.classList.add("_modal");
    const t = document.createElement("div");
    t.className = "overlay";
    const s = document.createElement("div");
    s.className = "sheet__wrapper";
    const i = document.createElement("header");
    (i.className = "controls"),
      (i.innerHTML = `\n      <div class="draggable-area">\n        <div class="draggable-thumb"></div>\n      </div>\n      ${
        this.getAttribute("title")
          ? `<div class="title__wrapper">\n            <span class="title">${this.getAttribute(
              "title"
            )}</span>\n          </div>`
          : ""
      }\n    `);
    const h = this.querySelector(`#${e} > main`);
    if (
      ((h.className = `${h.className} content`),
      s.appendChild(i),
      s.appendChild(h),
      this.appendChild(t),
      this.appendChild(s),
      this.querySelector(".overlay").addEventListener("click", () => {
        this.closeSheet();
      }),
      isMobile)
    ) {
      const e = this.querySelector(".draggable-area");
      let t = 0;
      const i = (e) => {
          (t = e.touches[0].clientY), s.classList.add("not-selectable");
        },
        h = (e) => {
          if (0 === t) return;
          const s = e.touches[0].clientY,
            i = ((t - s) / window.innerHeight) * 100;
          this.setSheetHeight(this.sheetHeight + i), (t = s);
        },
        o = () => {
          (t = 0),
            s.classList.remove("not-selectable"),
            this.sheetHeight < this.beforeVh - 5
              ? this.closeSheet()
              : this.sheetHeight > this.defaultVh + 10
              ? this.setSheetHeight(100)
              : this.setSheetHeight(this.defaultVh),
            (this.beforeVh = this.sheetHeight);
        };
      e.addEventListener("touchstart", i, { passive: !0 }),
        this.addEventListener("touchmove", h, { passive: !0 }),
        this.addEventListener("touchend", o, { passive: !0 });
    }
  }
  setSheetHeight(e) {
    const t = this.querySelector(".sheet__wrapper");
    isMobile &&
      ((this.sheetHeight = Math.max(0, Math.min(100, e))),
      (t.style.height = this.sheetHeight * this.mobileVh + "px"),
      100 === this.sheetHeight
        ? t.classList.add("fullscreen")
        : t.classList.remove("fullscreen"));
  }
  setIsSheetShown(e) {
    if ((this.setAttribute("aria-hidden", String(!e)), e))
      document.body.classList.add("no-scroll");
    else {
      Array.from(document.querySelectorAll("bottom-sheet")).find(
        (e) => "false" === e.ariaHidden
      ) || document.body.classList.remove("no-scroll");
    }
  }
  openSheet() {
    if (0 === this.defaultVh) {
      const e = this.querySelector(".sheet__wrapper");
      this.getAttribute("vh")
        ? (this.defaultVh = Number(this.getAttribute("vh")))
        : (this.defaultVh = Number(
            (e.offsetHeight / window.innerHeight) * 100
          ));
    }
    (this.beforeVh = this.defaultVh),
      this.setSheetHeight(this.defaultVh),
      this.setIsSheetShown(!0);
  }
  openFullSheet() {
    (this.beforeVh = 100), this.setSheetHeight(100), this.setIsSheetShown(!0);
  }
  closeSheet() {
    this.setSheetHeight(0), this.setIsSheetShown(!1);
  }
  fullSheet() {
    (this.beforeVh = 100), this.setSheetHeight(100);
  }
}
// const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
const isMobile = false;
customElements.define("bottom-sheet", BottomSheet);
