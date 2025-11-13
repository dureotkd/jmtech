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

            <form id="searchForm" action="/sales/report" method="GET" class="flex items-center border border-gray-300 gap-2 rounded-sm overflow-hidden w-[330px] !text-xs">
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
                <button onclick="delete_statement(event);" type="button" class="!my-2  flex items-center gap-1 border border-gray-300 rounded h-7 !px-3 bg-white hover:bg-gray-50 transition text-sm"><input multiple="" type="file" style="display: none;">
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

                    <button type="button" onclick="window.location.href = '/sales/report'" class="sm-btn">
                        초기화
                    </button>
                </div>
            </div>
            <button
                onclick="open_popup_default('<?= REACT_PATH ?>?sub_type=MC','<?= $title ?>',1000,820);"
                type="button"
                class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
                <?= $title ?> 등록+
            </button>
        </div>
    </div>

    <!-- 테이블 -->
    <table class="w-full border border-gray-300">
        <thead>
            <tr class="bg-[#788496] text-white">
                <th class=" w-10"><input type="checkbox" id="all_check" /></th>
                <th class="">발행일</th>
                <th class="">공급받는자상호</th>
                <th class="">공급가액</th>
                <th class="">세액</th>
                <th class="">합계금액</th>
                <th class="">상태</th>
                <th class=" w-32"></th>
            </tr>
        </thead>
        <tbody>
            <?
            $총공급가액 = 0;
            $총세액 = 0;
            $총합계금액 = 0;
            if (!empty($statement_all)) :
                foreach ($statement_all as $statement) :

                    $총공급가액 += $statement['supply_amount'];
                    $총세액 += $statement['tax_amount'];
                    $총합계금액 += $statement['amount'];
            ?>

                    <tr class="border-b hover:bg-gray-50" onclick="go_detail('<?= $statement['id'] ?>')" data-statement-id="<?= $statement['id'] ?>">
                        <td><input type="checkbox" statement-id="<?= $statement['id'] ?>" onclick="event.stopPropagation();" /></td>
                        <td class="">
                            <?= date('Y-m-d', strtotime($statement['created_at'])) ?>
                        </td>
                        <td class="">
                            <?= $statement['partner_name'] ?>
                        </td>
                        <td class=""><?= number_format($statement['supply_amount']) ?></td>
                        <td class=""><?= number_format($statement['tax_amount']) ?></td>
                        <td class=""><?= number_format($statement['amount']) ?></td>
                        <td>

                        </td>
                        <td class="cursor-pointer">
                            <div class="flex items-center gap-1">
                                <img src="https://ai.serp.co.kr/img/serp/btn/btn_send.png" alt="">
                                <span class="font-semibold">상세보기</span>
                            </div>
                        </td>
                    </tr>
                <? endforeach;
            else : ?>
                <tr>
                    <td colspan="9" class="text-center py-4">등록된 <?= $title ?> 데이터가 없습니다.</td>
                </tr>
            <? endif; ?>

            <?
            if (!empty($statement_all)) {
            ?>
                <tr class="border-b bg-[#e9f1fb]">
                    <td></td>
                    <td class=""></td>
                    <td class="!font-bold">총 <?= count($statement_all) ?>건</td>
                    <td class="!font-bold">공급가액 : <?= number_format($총공급가액) ?></td>
                    <td class="!font-bold">세액 : <?= number_format($총세액) ?></td>
                    <td class="!font-bold">합계금액 : <?= number_format($총합계금액) ?></td>
                    <td class="!font-bold">
                    </td>
                    <td class="cursor-pointer">
                    </td>
                </tr>

            <?
            }
            ?>

        </tbody>
    </table>
</div>

<div id="calendar"></div>

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

    function open_calendar_modal(e) {
        e.stopPropagation();

        const target = $(e.currentTarget);
        /**
         * iframe
         */
        $.ajax({
            type: "GET",
            url: "/iframe/search_calendar",
            dataType: "html",
            success: function(response) {
                $('#calendar').html(response);
            }
        });
    }

    function go_detail(statement_id) {
        open_popup_default(`/purchase/report/statement_detail?id=${statement_id}`, '<?= $title ?> 상세', 1000, 820);
    }
</script>