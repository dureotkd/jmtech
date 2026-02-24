<style>
    .litepicker {
        font-size: 14px;
        /* 기본값은 12~13px 정도 */
    }
</style>

<div class="p-4 bg-white font-sans text-sm text-gray-800">
    <div class="flex items-center !border-b !font-sans !border-gray-300 !pb-3 justify-between">
        <h1 class="!text-xl">
            견적서
        </h1>

        <div class="flex items-center gap-2 !text-xs">

            <form id="searchForm" action="/sales/estimate" method="GET" class="flex items-center border border-gray-300 gap-2 rounded-sm overflow-hidden w-[330px] !text-xs">
                <input type="hidden" name="excel_yn" value="<?= $excel_yn ?>" />
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

            <div class="relative">
                <div
                    class="custom-dropdown-wrapper"
                    data-dropdown-id="estimate-dropdown"
                    style="display: inline-block;">
                    <button
                        type="button"
                        class="!px-2 py-1 !border-1 !border-gray-300 hover:bg-gray-100"
                        onclick="toggleCustomDropdown(event, 'estimate-dropdown')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu">
                            <path d="M4 5h16" />
                            <path d="M4 12h16" />
                            <path d="M4 19h16" />
                        </svg>
                    </button>
                    <div
                        class="custom-dropdown-menu hidden absolute right-0 !min-w-[210px] !border !border-gray-300 !bg-white mt-2 flex flex-col justify-start font-sans z-10 w-52 shadow-sm !text-xs"
                        data-dropdown-menu="estimate-dropdown"
                        style="transition:opacity 0.15s;">
                        <button onclick="download_excel();"
                            class="!text-left flex items-center gap-2 border-b border-gray-300 !p-4 hover:bg-gray-50" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download-icon lucide-download">
                                <path d="M12 15V3" />
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <path d="m7 10 5 5 5-5" />
                            </svg>
                            <span>엑셀파일 다운로드</span>
                        </button>
                        <button class="flex items-center gap-2 !text-left !p-4 hover:bg-gray-50" onclick="show_prints();" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer-icon lucide-printer">
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" />
                                <rect x="6" y="14" width="12" height="8" rx="1" />
                            </svg>
                            <span>견적서 인쇄</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- 필터 영역 -->
    <div class="flex items-center gap-2 mb-4 !text-xs">

        <div class="ml-auto flex w-full items-center gap-2 justify-between">
            <div class="flex items-center gap-2">
                <button onclick="delete_estimate(event);" type="button" class="!my-2  flex items-center gap-1 border border-gray-300 rounded h-7 !px-3 bg-white hover:bg-gray-50 transition"><input multiple="" type="file" style="display: none;">
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

                    <button type="button" onclick="window.location.href = '/sales/estimate'" class="sm-btn">
                        초기화
                    </button>
                </div>
            </div>
            <button
                onclick="open_popup_default('<?= REACT_PATH ?>','견적서 등록',3000,1820);"
                type="button"
                class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0] rounded-sm">
                견적서 등록 +
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
        <tbody id="item-tbody">
            <?
            $총공급가액 = 0;
            $총세액 = 0;
            $총합계금액 = 0;
            if (!empty($estimate_all)) :
                foreach ($estimate_all as $estimate) :

                    $총공급가액 += $estimate['supply_amount'];
                    $총세액 += $estimate['tax_amount'];
                    $총합계금액 += $estimate['amount'];
            ?>

                    <tr class="border-b hover:bg-gray-50" data-estimate-id="<?= $estimate['id'] ?>">
                        <td><input type="checkbox" estimate-id="<?= $estimate['id'] ?>" onclick="event.stopPropagation();" /></td>
                        <td class="">
                            <?= date('Y-m-d', strtotime($estimate['created_at'])) ?>
                        </td>
                        <td class="">
                            <div class="flex items-center gap-2">

                                <span onclick="go_detail(<?= $estimate['id'] ?>);" class="underline cursor-pointer">
                                    <?= $estimate['partner_name'] ?>
                                </span>

                                <?
                                if (!empty($estimate['file_names'])) {

                                    $file_name_array = explode(',', $estimate['file_names']);
                                    $file_id_array = explode(',', $estimate['file_ids']);
                                ?>
                                    <div class="relative">
                                        <svg onclick="show_file_list(event);" class="lucide lucide-paperclip-icon lucide-paperclip cursor-pointer" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m16 6-8.414 8.586a2 2 0 0 0 2.829 2.829l8.414-8.586a4 4 0 1 0-5.657-5.657l-8.379 8.551a6 6 0 1 0 8.485 8.485l8.379-8.551" />
                                        </svg>

                                        <div class="bg-white file-list !border-1 !divide-y-1 !divide-gray-200 z-10 absolute left-0 top-0 hidden">
                                            <?
                                            foreach ($file_name_array as $index => $file_name) {
                                            ?>
                                                <div onclick="file_download(<?= $file_id_array[$index] ?>)" class="min-w-[200px] flex items-center gap-3 !p-2 hover:bg-gray-100 cursor-pointer">
                                                    <img src="<?= fileIcon($file_name) ?>" class="w-4 h-4" />
                                                    <span class="text-sm text-gray-800 truncate">
                                                        <?= $file_name ?>
                                                    </span>
                                                </div>
                                            <?
                                            }
                                            ?>

                                        </div>
                                    </div>
                                <?
                                }
                                ?>

                            </div>
                        </td>
                        <td class=""><?= number_format($estimate['supply_amount']) ?></td>
                        <td class=""><?= number_format($estimate['tax_amount']) ?></td>
                        <td class=""><?= number_format($estimate['amount']) ?></td>
                        <td>
                            <div class="flex items-center gap-2">
                                <select onclick="handle_select(event);" onchange="change_status(<?= $estimate['id'] ?>, event);" name="estimate_status" id="">
                                    <?
                                    $ESTIMATE_STATUS = unserialize(ESTIMATE_STATUS);
                                    foreach ($ESTIMATE_STATUS as $status_key => $status_val) {
                                    ?>
                                        <option <?= $status_key === $estimate['status'] ? 'selected' : '' ?> value="<?= $status_key ?>"><?= $status_val ?></option>
                                    <?
                                    }

                                    ?>
                                </select>

                                <?
                                if (!empty($estimate['su_estimate_id'])) {
                                ?>
                                    <button
                                        onclick="event.stopPropagation(); open_popup_default(`/sales/estimate_detail?id=<?= $estimate['su_estimate_id'] ?>`, '수주서 상세', 1000, 820);"
                                        type="button"
                                        class="sm-btn bg-primary !m-0 text-xs !min-w-[62px]">
                                        수주서
                                    </button>
                                <?
                                }
                                ?>
                            </div>

                        </td>
                        <td class="cursor-pointer">
                        </td>
                    </tr>
                <? endforeach;
            else : ?>
                <tr>
                    <td colspan="9" class="text-center py-4">등록된 데이터가 없습니다.</td>
                </tr>
            <? endif; ?>

            <?
            if (!empty($estimate_all)) {
            ?>
                <tr class="border-b bg-[#e9f1fb]">
                    <td></td>
                    <td class=""></td>
                    <td class="!font-bold">총 <?= count($estimate_all) ?>건</td>
                    <td class="!font-bold"><?= number_format($총공급가액) ?></td>
                    <td class="!font-bold"><?= number_format($총세액) ?></td>
                    <td class="!font-bold"><?= number_format($총합계금액) ?></td>
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
    // 배경 클릭 시 file-list 숨기기
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.file-list').length && !$(e.target).closest('.lucide-paperclip').length) {
            $(".file-list").addClass('hidden');
        }
    });

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

    const file_download = (id) => {
        window.location.href = `/sales/download_file?id=${id}`;
    }

    const show_file_list = (event) => {
        event.stopPropagation();

        const target = $(event.currentTarget);
        $('.file-list').addClass('hidden');
        target.next('.file-list').toggleClass('hidden');
    }

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

    function show_prints() {

        const checked_ids = [];
        $('#item-tbody input[type="checkbox"]:checked').each(function() {
            const row = $(this).closest('tr');
            const estimate_id = row.data('estimate-id');
            checked_ids.push(estimate_id);
        });

        if (checked_ids.length === 0) {
            alert('프린터 대상을 선택해주세요.');
            return;
        }

        open_popup_default('<?= REACT_PATH ?>?main_type=pdf&sub_type=G&id=' + checked_ids.join(','), '견적서 프린터', 1000, 820);
    }

    function download_excel() {
        start_loading();

        $("input[name='excel_yn']").val('Y')
        $("#searchForm").submit();
        $("input[name='excel_yn']").val('N')

        setTimeout(() => {
            stop_loading();
        }, 1000);
    }

    function close_calendar_modal() {
        const modal = document.getElementById('calendar_modal');
        modal.close();
    }

    function go_detail(estimate_id) {
        open_popup_default(`/sales/estimate_detail?id=${estimate_id}`, '견적서 상세', 1000, 820);
    }

    function handle_select(event) {
        event.stopPropagation(); // 트리거링 방지
    }

    function delete_estimate(e) {

        const checked_ids = [];
        $('tbody input[type="checkbox"]:checked').each(function() {
            const row = $(this).closest('tr');
            const estimate_id = row.data('estimate-id');
            checked_ids.push(estimate_id);
        });

        if (checked_ids.length === 0) {
            alert('삭제할 견적서를 선택해주세요.');
            return;
        }

        if (!confirm('선택한 견적서를 삭제하시겠습니까?')) {
            return;
        }

        console.log(checked_ids)

        start_loading();

        $.ajax({
            type: "GET",
            url: "/sales/delete_estimate",
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

    function change_status(estimate_id, e) {

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
                id: estimate_id,
                status: selected_status
            },
            dataType: "json",
            success: function(response) {

                alert(response.msg);

                if (response.ok && selected_status == '수주전환' && response.su_estimate_id) {

                    open_popup_default(`/sales/estimate_detail?id=${response.su_estimate_id}`, '수주서 상세', 1000, 820);
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