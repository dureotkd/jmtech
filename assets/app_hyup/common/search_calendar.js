(function (global) {
  "use strict";

  function initializeSearchCalendar(options) {
    const settings = options || {};
    const modal = document.getElementById("calendar_modal");
    const startInput = document.getElementById("temp_start_date");
    const endInput = document.getElementById("temp_end_date");
    const yearSelect = document.getElementById("yearSelect");

    if (!modal || !startInput || !endInput || !yearSelect) {
      return null;
    }

    if (modal.dataset.calendarInitialized === "true") {
      if (settings.showOnInit) {
        modal.showModal();
      }
      return null;
    }

    modal.dataset.calendarInitialized = "true";

    const startPicker =
      settings.startPicker ||
      new Litepicker({
        element: startInput,
        inlineMode: true,
        singleMode: true,
        format: "YYYY-MM-DD",
        lang: "ko",
      });
    const endPicker =
      settings.endPicker ||
      new Litepicker({
        element: endInput,
        inlineMode: true,
        singleMode: true,
        format: "YYYY-MM-DD",
        lang: "ko",
      });

    const dateButtons = modal.querySelectorAll(".date-btn");
    let lastSelectedLabel = "";

    function setDateRangeByLabel(label, year) {
      const today = dayjs();
      let start;
      let end;

      switch (label) {
        case "오늘":
          start = end = today;
          break;
        case "전일":
          start = end = today.subtract(1, "day");
          break;
        case "주간":
          start = today.startOf("week");
          end = today.endOf("week");
          break;
        case "전주":
          start = today.subtract(1, "week").startOf("week");
          end = today.subtract(1, "week").endOf("week");
          break;
        case "당월":
          start = dayjs(`${year}-${today.month() + 1}-01`).startOf("month");
          end = start.endOf("month");
          break;
        case "전월":
          start = dayjs(`${year}-${today.month() + 1}-01`)
            .subtract(1, "month")
            .startOf("month");
          end = start.endOf("month");
          break;
        case "올해":
          start = dayjs(`${year}-01-01`);
          end = dayjs(`${year}-12-31`);
          break;
        case "상반기":
          start = dayjs(`${year}-01-01`);
          end = dayjs(`${year}-06-30`);
          break;
        case "하반기":
          start = dayjs(`${year}-07-01`);
          end = dayjs(`${year}-12-31`);
          break;
        case "1/4분기":
          start = dayjs(`${year}-01-01`);
          end = dayjs(`${year}-03-31`);
          break;
        case "2/4분기":
          start = dayjs(`${year}-04-01`);
          end = dayjs(`${year}-06-30`);
          break;
        case "3/4분기":
          start = dayjs(`${year}-07-01`);
          end = dayjs(`${year}-09-30`);
          break;
        case "4/4분기":
          start = dayjs(`${year}-10-01`);
          end = dayjs(`${year}-12-31`);
          break;
        case "오늘까지":
          start = dayjs(`${year}-01-01`);
          end = year === today.year() ? today : dayjs(`${year}-12-31`);
          break;
        default: {
          if (!label.endsWith("월")) {
            return;
          }
          const month = parseInt(label, 10);
          start = dayjs(`${year}-${month}-01`).startOf("month");
          end = start.endOf("month");
        }
      }

      startPicker.setDate(start.toDate());
      startPicker.gotoDate(start.toDate());
      endPicker.setDate(end.toDate());
      endPicker.gotoDate(end.toDate());
    }

    dateButtons.forEach((button) => {
      button.addEventListener("click", () => {
        dateButtons.forEach((item) => item.classList.remove("active"));
        button.classList.add("active");
        lastSelectedLabel = button.textContent.trim();
        setDateRangeByLabel(lastSelectedLabel, parseInt(yearSelect.value, 10));
      });
    });

    yearSelect.addEventListener("change", () => {
      if (lastSelectedLabel) {
        setDateRangeByLabel(lastSelectedLabel, parseInt(yearSelect.value, 10));
      }
    });

    global.open_calendar_modal = function (event) {
      event?.stopPropagation();
      modal.showModal();
    };

    global.close_calendar_modal = function () {
      modal.close();
    };

    global.handle_calendar_apply = function () {
      const startDate = startInput.value;
      const endDate = endInput.value;
      const searchForm = document.getElementById("searchForm");

      if (!startDate || !endDate || !searchForm) {
        alert("조회할 시작일과 종료일을 선택해주세요.");
        return;
      }

      const visibleStart = document.getElementById("start_date");
      const visibleEnd = document.getElementById("end_date");
      const formStart = searchForm.querySelector('input[name="start_date"]');
      const formEnd = searchForm.querySelector('input[name="end_date"]');

      if (visibleStart) visibleStart.value = startDate;
      if (visibleEnd) visibleEnd.value = endDate;
      if (formStart) formStart.value = startDate;
      if (formEnd) formEnd.value = endDate;

      searchForm.submit();
    };

    if (settings.showOnInit) {
      modal.showModal();
    }

    return { startPicker, endPicker };
  }

  global.initializeSearchCalendar = initializeSearchCalendar;
})(window);
