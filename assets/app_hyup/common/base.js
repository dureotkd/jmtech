function download_estimate_excel(e) {
  start_loading();

  // Simulate a download process
  $.ajax({
    type: "POST",
    url: "/sales/download_estimate_excel",
    data: {
      type: "suju",
    },
    dataType: "json",
    success: function (response) {},
  });
}

function download_estimate_pdf(e) {
  start_loading();

  // Simulate a download process
  $.ajax({
    type: "POST",
    url: "/sales/download_estimate_pdf",
    data: {
      type: "suju",
    },
    dataType: "json",
    success: function (response) {
      window.location.href = response.url;
    },
    complete: function () {
      stop_loading();
    },
  });
}

// all_check
$("#all_check").on("change", function () {
  var isChecked = $(this).is(":checked");
  $("input[type='checkbox']").prop("checked", isChecked);
});

function only_number_input(e) {
  e.value = e.value.replace(/[^0-9]/g, "");
}

function open_kakao_post_pop() {
  new daum.Postcode({
    oncomplete: function (res) {
      if (res.address) {
        $("input[name='address']").val(res.address);
      }

      if (res.zonecode) {
        $("input[name='zipcode']").val(res.zonecode);
      }
    },
  }).open();
}

const dateBtns = document.querySelectorAll(".date-btn");
const yearSelect = document.getElementById("yearSelect");

// ✅ 버튼 클릭 이벤트
if (dateBtns.length > 0) {
  dateBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      dateBtns.forEach((b) => b.classList.remove("active"));

      btn.classList.add("active");

      const label = btn.textContent.trim();
      const selectedYear = parseInt(yearSelect.value);
      lastSelectedLabel = label; // 마지막 선택 버튼 기억
      setDateRangeByLabel(label, selectedYear);
    });
  });
}

// ✅ 기준연도 변경 시 자동 반영
if (yearSelect) {
  yearSelect.addEventListener("change", () => {
    const selectedYear = parseInt(yearSelect.value);
    if (lastSelectedLabel) {
      setDateRangeByLabel(lastSelectedLabel, selectedYear);
    }
  });
}

function handle_calendar_apply() {
  const start_date = $("#temp_start_date").val();
  const end_date = $("#temp_end_date").val();

  $("#start_date").val(start_date);
  $("#end_date").val(end_date);

  $("input[name='start_date']").val(start_date);
  $("input[name='end_date']").val(end_date);

  $("#searchForm").submit();
}

function setDateRangeByLabel(label, year) {
  const today = dayjs();
  let start, end;

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
      end = dayjs(`${year}-${today.month() + 1}-01`).endOf("month");
      break;
    case "전월":
      start = dayjs(`${year}-${today.month()}-01`)
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
      end = today;
      break;
    default:
      if (label.endsWith("월")) {
        const month = parseInt(label);
        start = dayjs(`${year}-${month}-01`).startOf("month");
        end = dayjs(`${year}-${month}-01`).endOf("month");
      } else {
        console.warn(`정의되지 않은 버튼: ${label}`);
        return;
      }
  }

  startPicker.setDateRange(start.toDate());
  startPicker.gotoDate(start.toDate());
  startPicker.show();

  endPicker.setDateRange(end.toDate());
  endPicker.gotoDate(end.toDate());
  endPicker.show();

  console.log(
    `📅 ${label} (${year}년): ${start.format("YYYY-MM-DD")} ~ ${end.format(
      "YYYY-MM-DD"
    )}`
  );
}
