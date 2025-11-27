<style>
    .litepicker {
        font-size: 14px;
        /* 기본값은 12~13px 정도 */
    }
</style>

<div class="p-4 bg-white font-sans text-sm text-gray-800">
    <div class="flex items-center !border-b !font-sans !border-gray-300 !pb-3 justify-between">
        <h1 class="!text-xl">
            <?= $title ?>
        </h1>

        <div class="flex items-center gap-2 !text-xs">

            <form id="searchForm" action="/purchase/report" method="GET" class="flex items-center border border-gray-300 gap-2 rounded-sm overflow-hidden w-[330px] !text-xs">
                <input type="hidden" name="page" value="<?= $page ?>" />
                <input type="hidden" name="start_date" autocomplete="off" value="<?= $start_date ?>" />
                <input type="hidden" name="end_date" autocomplete="off" value="<?= $end_date ?>" />
                <!-- 검색 입력창 -->
                <input
                    type="text"
                    placeholder="검색어를 입력하세요"
                    name="search_text"
                    value="<?= htmlspecialchars($search_text) ?>"
                    class="flex-1 px-2 py-2 outline-none placeholder-gray-400" />
            </form>

            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button">
                    <button type="button" class="!px-2 py-1 !border-1 !border-gray-300 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu">
                            <path d="M4 5h16" />
                            <path d="M4 12h16" />
                            <path d="M4 19h16" />
                        </svg>
                    </button>

                </div>
                <ul
                    tabindex="-1"
                    class="!min-w-[210px] !border !border-gray-300 !bg-white !mt-2 items-center justify-center font-sans menu dropdown-content z-1 mt-4 w-52 shadow-sm">

                    <div class="w-full flex flex-col justify-start !text-xs">

                        <button onclick="open_popup_default('/setting/item/create','물품 등록',500,580);"
                            class="!text-left flex items-center gap-2 border-b-1 border-gray-300 !p-4 sm-hover" type="button">

                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download-icon lucide-download">
                                <path d="M12 15V3" />
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <path d="m7 10 5 5 5-5" />
                            </svg>

                            <span>
                                엑셀파일 다운로드
                            </span>
                        </button>

                        <button class="flex items-center gap-2 !text-left !p-4 sm-hover" onclick="show_excel_upload_modal();" type="button">

                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer-icon lucide-printer">
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" />
                                <rect x="6" y="14" width="12" height="8" rx="1" />
                            </svg>

                            <span>
                                <?= $title ?> 인쇄
                            </span>
                        </button>
                    </div>
                </ul>
            </div>
        </div>

    </div>

    <!-- 필터 영역 -->
    <div class="flex items-center gap-2 mb-4 !text-sm">

        <div class="ml-auto flex w-full items-center gap-2 justify-between">
            <div class="flex items-center gap-2">
                <button onclick="delete_transcation_statement(event);" type="button" class="!my-2  flex items-center gap-1 border border-gray-300 rounded h-7 !px-3 bg-white hover:bg-gray-50 transition text-sm"><input multiple="" type="file" style="display: none;">
                    삭제
                </button>

                <div class="flex items-center gap-1 !text-xs">

                    <input
                        type="text"
                        placeholder="시작날짜"
                        name="search_text"
                        id="start_date"
                        value="<?= htmlspecialchars($start_date) ?>"
                        class="w-[110px] flex-1 px-2 py-1 outline-none placeholder-gray-400" />
                    ~
                    <input
                        type="text"
                        placeholder="종료날짜"
                        name="search_text"
                        id="end_date"
                        value="<?= htmlspecialchars($end_date) ?>"
                        class="w-[110px] flex-1 px-2 py-1 outline-none placeholder-gray-400" />

                    <img onclick="open_calendar_modal(event);" class="cursor-pointer" src="/assets/app_hyup/images/calendar.png" alt="캘린더">

                    <button type="button" onclick="window.location.href = '/transcation_statement/report'" class="sm-btn">
                        초기화
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    onclick="collect_hometax(event);"
                    class="flex h-[28px] items-center gap-1 bg-[#2ea3eb] text-white px-4">
                    <img src="/assets/app_hyup/images/hometax.png" alt="hometax" class="w-12" />
                    홈택스 자료수집
                </button>
                <button
                    onclick="open_popup_default('<?= REACT_PATH ?>?sub_type=MI','<?= $title ?>',1000,820);"
                    type="button"
                    class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
                    <?= $title ?> 등록+
                </button>
            </div>
        </div>
    </div>

    <!-- 테이블 -->
    <table class="w-full border border-gray-300">
        <thead>
            <tr class="bg-[#788496] text-white">
                <th class="w-[100px]">매출일자</th>
                <th class="w-[200px]">공급받는자상호</th>
                <th class="!text-center">증빙</th>
                <th class="!text-right w-[200px]">매출금액</th>
                <th class="w-[150px]">용도</th>
                <th class="">내용</th>
                <th class="">비고</th>
            </tr>
        </thead>
        <tbody>
            <?
            if (!empty($barobill_tax_invoice)) :
                foreach ($barobill_tax_invoice as $tax_invoice) :

            ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td data-label="매출일자">
                            <?= date('Y-m-d', strtotime($tax_invoice['WriteDate'])) ?>
                        </td>
                        <td data-label="공급받는자상호">
                            <?= $tax_invoice['CorpName'] ?>
                        </td>
                        <td data-label="증빙" class="text-center">
                            <button
                                type="button"
                                class="px-3 py-[1px] !min-w-[50px] text-red-600 border border-red-300 bg-red-50 rounded-full !text-xs font-medium hover:bg-red-100 transition">
                                세계
                            </button>
                        </td>
                        <td class="text-right" data-label="매출금액">
                            <?= number_format($tax_invoice['TotalAmount']) ?>
                        </td>
                        <td data-label="용도" class="bg-[#fdedeb]">
                            매출
                        </td>
                        <td data-label="내용" class="bg-[#fdedeb]">
                            <?= $tax_invoice['ItemName'] ?>
                        </td>
                        <td data-label="비고" class="bg-[#fdedeb]">
                            <?= $tax_invoice['Remark1'] ?>
                        </td>
                    </tr>
                <? endforeach;
            else : ?>
                <tr>
                    <td colspan=" 9" class="text-center py-4">등록된 <?= $title ?>가 없습니다.</td>
                </tr>
            <? endif; ?>

        </tbody>
    </table>
</div>

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
    const picker = new Litepicker({
        element: document.getElementById('start_date'),
        elementEnd: document.getElementById('end_date'),
        singleMode: false,
        format: 'YYYY-MM-DD',
        lang: 'ko',
        numberOfMonths: 2,
        numberOfColumns: 2,
        onSelect: (start, end) => {
            console.log('✅ 날짜 선택 완료!');
            console.log('시작일:', start.format('YYYY-MM-DD'));
            console.log('종료일:', end.format('YYYY-MM-DD'));
        },
    });

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

        const modal = document.getElementById('calendar_modal');
        modal.showModal();
    }

    function close_calendar_modal() {
        const modal = document.getElementById('calendar_modal');
        modal.close();
    }

    function go_detail(transcation_statement_id) {
        open_popup_default(`/purchase/report/statement_detail?id=${transcation_statement_id}`, '<?= $title ?> 상세', 1000, 820);
    }

    function handle_select(event) {
        event.stopPropagation(); // 트리거링 방지
    }

    function collect_hometax() {

        start_loading();

        $.ajax({
            type: "GET",
            url: "/sales/collect_hometax_sales_tax_invoice",
            dataType: "json",
            success: function(response) {

                alert(response.msg);

                if (response.ok) {
                    window.location.reload();
                }

            },
            error: function(xhr, status, error) {
                alert("에러가 발생했습니다: " + error);
            },
            complete: function() {
                stop_loading();
            }
        });
    }

    function delete_transcation_statement(e) {

        const checked_ids = [];
        $('tbody input[type="checkbox"]:checked').each(function() {
            const row = $(this).closest('tr');
            const transcation_statement_id = row.data('transcation_statement-id');
            checked_ids.push(transcation_statement_id);
        });

        if (checked_ids.length === 0) {
            alert('삭제할 <?= $title ?>를 선택해주세요.');
            return;
        }

        if (!confirm('선택한 <?= $title ?>를 삭제하시겠습니까?')) {
            return;
        }

        console.log(checked_ids)

        start_loading();

        $.ajax({
            type: "GET",
            url: "/purchase/report/delete_transcation_statement",
            data: {
                id: checked_ids
            },
            dataType: "json",
            success: function(response) {

                alert(response.msg);

                if (response.ok) {
                    window.location.reload();
                }

            },
            error: function(xhr, status, error) {
                alert("에러가 발생했습니다: " + error);
            },
            complete: function() {
                stop_loading();
            }
        });
    }

    function change_status(transcation_statement_id, e) {

        start_loading();

        const selected_status = e.target.value;

        if (selected_status === '수주전환') {
            if (!confirm('수주전환 하시겠습니까?')) {
                return;
            }
        }

        $.ajax({
            type: "POST",
            url: "/sales/change_status",
            data: {
                id: transcation_statement_id,
                status: selected_status
            },
            dataType: "json",
            success: function(response) {

                alert(response.msg);

                if (response.ok && selected_status == '수주전환' && response.su_transcation_statement_id) {

                    open_popup_default(`/purchase/report/statement_detail?id=${response.su_transcation_statement_id}`, '수주서 상세', 1000, 820);
                }

                if (response.ok) {
                    window.location.reload();
                }

            },
            error: function(xhr, status, error) {
                alert("에러가 발생했습니다: " + error);
            },
            complete: function() {
                stop_loading();
            }
        });
    }
</script>