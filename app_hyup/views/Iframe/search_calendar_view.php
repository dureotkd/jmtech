<dialog id="calendar_modal" class="modal">
    <div class="modal-box !text-xs relative max-w-3xl">

        <div class="absolute inset-0 modal-loading hidden">
            <div class="flex items-center justify-center w-full h-full bg-white/70">
                <img class="w-16" src="/assets/app_hyup/images/loading.gif" alt="loading" />
            </div>
        </div>

        <form id="calendar_form" class="bg-white w-full border border-gray-300">
            <!-- 헤더 -->
            <div class="flex justify-between items-center !text-base !px-4 !py-2 bg-[#4b5563]">
                <h2 class="text-white font-semibold">일자조회</h2>
                <button type="button" class="text-gray-200" onclick="close_calendar_modal();">
                    ✕
                </button>
            </div>

            <div class="border border-gray-300 !p-4 w-full !text-xs bg-white rounded text-sm">
                <div class="flex items-center gap-2 !mb-2">
                    <span>기준연도</span>
                    <select id="yearSelect" class="border border-gray-300 rounded px-1 py-0.5">
                        <option value="2025" selected>2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>
                </div>

                <?php
                // 1️⃣ 기본 기간
                $group1 = ['오늘', '전일', '주간', '전주', '당월', '전월', '올해'];

                // 2️⃣ 반기 / 분기 / 누적
                $group2 = ['상반기', '하반기', '1/4분기', '2/4분기', '3/4분기', '4/4분기', '오늘까지'];

                // 3️⃣ 월별
                $group3 = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];
                ?>

                <div class="!space-y-1 !mb-4">
                    <!-- 1️⃣ 기본 기간 -->
                    <div class="grid grid-cols-7 gap-1 mb-1">
                        <?php foreach ($group1 as $label): ?>
                            <button type="button" class="date-btn sm-btn justify-center !m-0"><?= $label ?></button>
                        <?php endforeach; ?>
                    </div>

                    <!-- 2️⃣ 반기 / 분기 / 누적 -->
                    <div class="grid grid-cols-7 gap-1 mb-1">
                        <?php foreach ($group2 as $label): ?>
                            <button type="button" class="date-btn sm-btn justify-center !m-0"><?= $label ?></button>
                        <?php endforeach; ?>
                    </div>

                    <!-- 3️⃣ 월별 -->
                    <div class="grid grid-cols-12 gap-1">
                        <?php foreach ($group3 as $label): ?>
                            <button type="button" class="date-btn sm-btn justify-center !m-0"><?= $label ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>


                <!-- 달력 2개 -->
                <div class="flex justify-between items-center mb-3">
                    <input type="hidden" id="temp_start_date" class="border border-gray-300 rounded px-2 py-1 w-[150px]" placeholder="시작일">
                    <input type="hidden" id="temp_end_date" class="border border-gray-300 rounded px-2 py-1 w-[150px]" placeholder="종료일">
                </div>

            </div>

            <!-- 하단 버튼 -->
            <div class="w-full !px-2 !text-[13px] flex justify-center items-center gap-1.5 font-sans font-300 !my-2">
                <!-- 저장 후 인쇄 -->
                <button
                    type="button"
                    onclick="handle_calendar_apply();"
                    class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
                    적용
                </button>

                <!-- 취소 -->
                <button
                    type="button"
                    onclick="close_calendar_modal();"
                    class="px-2 py-1 bg-[#fff] text-gray-700 hover:bg-gray-100 border border-gray-300">
                    취소
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
    const modal = document.getElementById('calendar_modal');

    modal.showModal();

    // 시작일 달력
    const startPicker = new Litepicker({
        element: document.getElementById('temp_start_date'),
        inlineMode: true, // ✅ 항상 표시
        singleMode: true,
        format: 'YYYY-MM-DD',
        lang: 'ko',
    });

    // 종료일 달력
    const endPicker = new Litepicker({
        element: document.getElementById('temp_end_date'),
        inlineMode: true, // ✅ 항상 표시
        singleMode: true,
        format: 'YYYY-MM-DD',
        lang: 'ko',
    });


    function open_calendar_modal(e) {

        e.stopPropagation();

        modal.showModal();
    }

    function close_calendar_modal() {
        modal.close();
    }

    function go_detail(estimate_id) {
        open_popup_default(`/sales/estimate_detail?id=${estimate_id}`, '상세', 1000, 820);
    }

    function handle_select(event) {
        event.stopPropagation(); // 트리거링 방지
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
</script>