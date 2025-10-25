$(function () {
  // $('input[name="daterange"]').daterangepicker(
  //   {
  //     locale: {
  //       format: "YYYY-MM-DD",
  //       separator: " ~ ",
  //       applyLabel: "확인",
  //       cancelLabel: "취소",
  //       fromLabel: "From",
  //       toLabel: "To",
  //       customRangeLabel: "Custom",
  //       weekLabel: "W",
  //       daysOfWeek: ["일", "월", "화", "수", "목", "금", "토"],
  //       monthNames: [
  //         "1월",
  //         "2월",
  //         "3월",
  //         "4월",
  //         "5월",
  //         "6월",
  //         "7월",
  //         "8월",
  //         "9월",
  //         "10월",
  //         "11월",
  //         "12월",
  //       ],
  //     },
  //     minDate: moment().startOf("day"),
  //     startDate: $('input[name="startDate"]').val(),
  //     endDate: $('input[name="endDate"]').val(),
  //     drops: "auto",
  //   },
  //   function (start, end, label) {
  //     const start_date = start.format("YYYY-MM-DD");
  //     const end_date = end.format("YYYY-MM-DD");
  //     const nights = moment(end_date).diff(moment(start_date), "days");
  //     $(".nights-text").text(`총 ${nights}박`);
  //   }
  // );

  flatpickr("#daterange", {
    mode: "range",
    dateFormat: "Y-m-d",
    minDate: "today",
    defaultDate: [
      document.querySelector('input[name="startDate"]')?.value,
      document.querySelector('input[name="endDate"]')?.value,
    ],
    locale: {
      firstDayOfWeek: 0, // 일요일 시작
      weekdays: {
        shorthand: ["일", "월", "화", "수", "목", "금", "토"],
        longhand: [
          "일요일",
          "월요일",
          "화요일",
          "수요일",
          "목요일",
          "금요일",
          "토요일",
        ],
      },
      months: {
        shorthand: [
          "1월",
          "2월",
          "3월",
          "4월",
          "5월",
          "6월",
          "7월",
          "8월",
          "9월",
          "10월",
          "11월",
          "12월",
        ],
        longhand: [
          "1월",
          "2월",
          "3월",
          "4월",
          "5월",
          "6월",
          "7월",
          "8월",
          "9월",
          "10월",
          "11월",
          "12월",
        ],
      },
      rangeSeparator: " ~ ",
    },
    onChange: function (selectedDates) {
      if (selectedDates.length === 2) {
        const start = selectedDates[0];
        const end = selectedDates[1];

        const nights = Math.round((end - start) / (1000 * 60 * 60 * 24));
        document.querySelector(".nights-text").textContent = `총 ${nights}박`;
      }
    },
  });

  const start = moment($('input[name="startDate"]').val());
  const end = moment($('input[name="endDate"]').val());
  const start_date = start.format("YYYY-MM-DD");
  const end_date = end.format("YYYY-MM-DD");
  const nights = moment(end_date).diff(moment(start_date), "days");
  $(".nights-text").text(`총 ${nights}박`);
});

function down_bed_cnt(event) {
  const container = $(event.target).closest(
    ".flex.items-center.justify-between"
  );
  const countEl = container.find(".count");
  const hiddenInput = container.find("input[type=hidden]");

  let count = parseInt(countEl.text());
  if (count > 0) count--;

  countEl.text(count);
  hiddenInput.val(count);
}

function up_bed_cnt(event) {
  const container = $(event.target).closest(
    ".flex.items-center.justify-between"
  );
  const countEl = container.find(".count");
  const hiddenInput = container.find("input[type=hidden]");

  let count = parseInt(countEl.text());
  count++;

  countEl.text(count);
  hiddenInput.val(count);
}

function handle_search_people_form(event) {
  event.preventDefault(); // 기본 form 동작 막기

  const adults = $('input[name="adults"]').val().trim();
  const children = $('input[name="children"]').val().trim();
  const pets = $('input[name="pets"]').val().trim();

  let search_people_text = "";

  if (adults && adults !== "0") {
    search_people_text += `성인 ${adults}`;
  }
  if (children && children !== "0") {
    if (search_people_text) search_people_text += ", ";
    search_people_text += `아동 ${children}`;
  }
  if (pets && pets !== "0") {
    if (search_people_text) search_people_text += ", ";
    search_people_text += `애완동물 ${pets}`;
  }

  $('input[name="search_people"]').val(search_people_text);
  $("#people_text").text(search_people_text);

  close_bottom_sheet("main-sheet-form3");
}

function handle_search_date_form(event) {
  event.preventDefault(); // Prevent the default form submission

  const date_range = $('input[name="daterange"]').val().trim();
  const nights = $(".nights-text").text().trim();

  const start_date = date_range.split(" ~ ")[0];
  const end_date = date_range.split(" ~ ")[1];
  const diff = moment(end_date).diff(moment(start_date), "days");

  const formatted = moment(start_date).format("MM-DD");
  const formatted_end = moment(end_date).format("MM-DD");

  $("input[name='startDate']").val(start_date);
  $("input[name='endDate']").val(end_date);

  if (date_range) {
    $('input[name="search_date"]').val(date_range);
    $("#nights-text").text(` ${formatted} ~ ${formatted_end} · ${diff}박`);
  }

  close_bottom_sheet("main-sheet-form2");
}

function handle_search_local_form(event) {
  event.preventDefault(); // Prevent the default form submission

  const selected_local = $(".local-list > .active");

  const select_local_code = [];
  const select_local_text = [];

  if (!empty(selected_local)) {
    selected_local.each(function () {
      const code = $(this).children("input").val().trim();
      const text = $(this).children("p").text().trim();
      select_local_code.push(code);
      select_local_text.push(text);
    });

    $('input[name="search_local"]').val(select_local_code.join("/"));

    const finalText =
      select_local_text.length > 0
        ? select_local_text.join(" / ")
        : "국내 전체";

    $("#local_text").text(finalText);
  } else {
    // 선택된 지역이 하나도 없는 경우
    $('input[name="search_local"]').val("");
    $("#local_text").text("국내 전체");
  }

  close_bottom_sheet("main-sheet-form");
}

function search_local(event) {
  event.preventDefault(); // Prevent the default form submission

  const target = $(event.currentTarget);
  const local_name = target.text().trim();

  if (target.hasClass("active")) {
    target.children("svg").remove(); // Remove any existing SVG icons
  } else {
    target.append(
      `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5" /></svg>`
    );
  }

  target.toggleClass("active");
}

function search_estate(event) {
  event.preventDefault(); // Prevent the default form submission

  const target = $(event.target);
  const serial = target.serialize();

  window.location.href = serial ? `/rooms?${serial}` : `/rooms`;
}

function handle_search_estate_form(event) {
  $("#search_estate_form").submit();
}

function open_local_search_form() {
  open_bottom_sheet("main-sheet-form");
}

function open_date_search_form() {
  open_bottom_sheet("main-sheet-form2");
}

function open_people_search_form() {
  open_bottom_sheet("main-sheet-form3");
}
