<div class="p-4 bg-white font-sans text-xs text-gray-800">

    <h1 class="!text-xl !border-b !font-sans !border-gray-300 !pb-3">
        품목관리
    </h1>

    <!-- 필터 영역 -->
    <div class="flex items-center gap-2 mb-4 !text-xs">

        <div class="ml-auto flex w-full items-center gap-2 justify-between">
            <button onclick="delete_item(event);" type="button" class="!my-2  flex items-center gap-1 border border-gray-300 rounded h-7 !px-3 bg-white hover:bg-gray-50 transition text-xs"><input multiple="" type="file" style="display: none;">
                삭제
            </button>
            <div class="relative">
                <div
                    class="custom-dropdown-wrapper"
                    data-dropdown-id="item-dropdown"
                    style="display: inline-block;">
                    <button
                        type="button"
                        class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]"
                        onclick="toggleCustomDropdown(event, 'item-dropdown')">
                        품목 등록 +
                    </button>
                </div>
                <div
                    data-dropdown-menu="item-dropdown"
                    class="custom-dropdown-menu hidden absolute right-0 mt-2 !min-w-[210px] !bg-white rounded-box shadow-sm z-10 py-4 px-4 font-sans"
                    style="min-width:210px;">
                    <div class="w-full flex flex-col justify-start !text-xs">
                        <button
                            onclick="open_popup_default('/setting/item/create','품목 등록',1000,820);"
                            class="!text-left flex items-center gap-2 border-b-1 border-gray-300 !p-4 sm-hover w-full"
                            type="button">
                            단일등록
                        </button>
                        <button
                            onclick="open_popup_default('/setting/item/bulk_create','일괄 등록',1000,820);"
                            class="!text-left flex items-center gap-2 border-b-1 border-gray-300 !p-4 sm-hover w-full"
                            type="button">
                            일괄등록
                        </button>
                        <button
                            onclick="show_excel_upload_modal();"
                            class="!text-left flex items-center gap-2 !p-4 sm-hover w-full"
                            type="button">
                            엑셀등록
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="searchFrm">
        <input type="hidden" name="page" id="page" value="<?= $page ?>">
        <!-- 테이블 -->
        <table class="w-full border border-gray-300">
            <thead>
                <tr class="bg-[#788496] text-white">
                    <th class="w-10"><input type="checkbox" id="all_check" /></th>
                    <th class="">품목코드</th>
                    <th class="w-[300px]">품목명</th>
                    <th class="">단위</th>
                    <th>구매가</th>
                    <th class="">판매가</th>
                    <th class="">기타사항</th>
                    <th class=""></th>
                </tr>
            </thead>
            <tbody>
                <?
                $총건수 = 0;
                if (!empty($item_list)) :
                    foreach ($item_list as $item) :

                        $총건수 += 1;

                ?>

                        <tr class="border-b hover:bg-gray-50" onclick="go_detail('<?= $item['id'] ?>')" data-item-id="<?= $item['id'] ?>">
                            <td><input type="checkbox" item-id="<?= $item['id'] ?>" onclick="event.stopPropagation();" /></td>
                            <td>
                                <?= $item['item_code'] ?>
                            </td>
                            <td>
                                <?= $item['item_name'] ?>
                            </td>
                            <td>
                                <?= $item['unit'] ?>
                            </td>
                            <td>
                                <?= number_format($item['purchase_price']) ?>
                            </td>
                            <td>
                                <?= number_format($item['sales_price']) ?>
                            </td>
                            <td>
                                <?= $item['memo'] ?>
                            </td>
                            <td>

                            </td>
                        </tr>
                    <? endforeach;
                else : ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">등록된 품목이 없습니다.</td>
                    </tr>
                <? endif; ?>

                <?
                if (!empty($item_list)) {
                ?>
                    <tr class="border-b bg-[#e9f1fb]">
                        <td></td>
                        <td class=""></td>
                        <td class="!font-bold">총 <?= $item_count ?>건</td>
                        <td class="!font-bold"></td>
                        <td class="!font-bold"></td>
                        <td class="!font-bold"></td>
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

        <div class="page_wrap">

            <?php if (!empty($page_data['is_prev'])): ?>
                <!-- 이전 버튼 -->
                <a href="javascript:go_page('<?php echo $page_data['start_page'] - 1; ?>')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M41.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.3 256 246.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                    </svg>
                </a>
            <?php endif; ?>

            <?php foreach ($page_data['page_num'] as $page => $active): ?>
                <a href="javascript:go_page('<?php echo $page; ?>')" class="<?php echo $active; ?>"><?php echo $page; ?></a>
            <?php endforeach; ?>

            <?php if (!empty($page_data['is_next'])): ?>
                <!-- 다음 버튼 -->
                <a href="javascript:go_page('<?php echo $page_data['end_page'] + 1; ?>')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z" />
                    </svg>
                </a>
            <?php endif; ?>

        </div>

    </form>

    <dialog id="excel_upload_modal" class="modal">
        <form id="exce_form" onsubmit="handle_excel_form(event);" class="modal-box !text-xs !w-[400px] relative">

            <div class="absolute inset-0 modal-loading hidden">
                <div class="flex items-center justify-center w-full h-full bg-white/70">
                    <img class="w-16" src="/assets/app_hyup/images/loading.gif" alt="loading" />
                </div>
            </div>

            <div class="bg-white w-full border border-gray-300">
                <!-- 헤더 -->
                <div class="flex justify-between items-center !text-base !px-4 !py-2 bg-[#4b5563]">
                    <h2 class="text-white font-semibold">엑셀 업로드</h2>
                    <button type="button" class="text-gray-200" onclick="close_my_info_modal();">
                        ✕
                    </button>
                </div>

                <!-- 본문 -->
                <div class="w-full !px-2 !text-xs font-sans font-300 !py-6">
                    <form id="excelForm" class="flex items-center justify-center gap-4">
                        <div class="p-5 space-y-4">
                            <div class="flex justify-end text-xs text-gray-700 items-center !mb-4"><a href="#" class="flex items-center text-xs hover:underline">품목 양식<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-1">
                                        <path d="M12 15V3"></path>
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <path d="m7 10 5 5 5-5"></path>
                                    </svg></a></div>
                            <div class="flex">
                                <input type="text" placeholder="엑셀 파일을 선택하세요" readonly class="flex-1 border border-gray-300 px-2 py-1.5" id="fileNameInput">
                                <input id="excelFileInput" name="file1" type="file" accept=".xls,.xlsx" class="hidden">
                                <button type="button" id="openFileBtn" class="bg-gray-200 border border-l-0 border-gray-300 px-3 hover:bg-gray-300">파일열기</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="w-full !px-2 !text-[13px] flex justify-center items-center gap-1.5 font-sans font-300 !my-2">
                    <!-- 저장 -->
                    <button
                        class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
                        업로드
                    </button>

                    <!-- 취소 -->
                    <button
                        type="button"
                        onclick="close_excel_upload_modal();"
                        class="px-2 py-1 bg-[#fff] text-gray-700 hover:bg-gray-100 border border-gray-300">
                        취소
                    </button>
                </div>
            </div>

        </form>
    </dialog>

</div>

<script>
    const openBtn = document.getElementById("openFileBtn");
    const fileInput = document.getElementById("excelFileInput");
    const fileNameInput = document.getElementById("fileNameInput");

    // 버튼 클릭 시 실제 파일 input 클릭
    openBtn.addEventListener("click", () => {
        fileInput.click();
    });

    // 파일 선택 시 이름 표시
    fileInput.addEventListener("change", (e) => {
        const file = e.target.files[0];
        fileNameInput.value = file ? file.name : "";
    });

    async function handle_excel_form(e) {
        e.preventDefault();

        start_modal_loading();
        await wait(500);

        const formData = new FormData(e.target);

        // 엑셀 파일 업로드 처리
        $.ajax({
            type: "POST",
            url: "/setting/item/create_excel_item",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                stop_modal_loading();

                alert(response.msg);

                if (response.ok) {
                    window.location.reload();
                }
            },
            error: function(xhr, status, error) {
                stop_modal_loading();
                alert("엑셀 양식이 올바르지 않습니다: " + error);
            },
        });
    }

    function go_detail(item_id) {
        // open_popup_default(`/sales/item_detail?id=${item_id}`, '견적서 상세', 1000, 820);
    }

    function handle_select(event) {
        event.stopPropagation(); // 트리거링 방지
    }

    function show_excel_upload_modal(e) {
        const excel_upload_modal = document.getElementById('excel_upload_modal');
        excel_upload_modal.showModal();
    }

    function close_excel_upload_modal() {
        const excel_upload_modal = document.getElementById('excel_upload_modal');
        excel_upload_modal.close();
    }

    function delete_item(e) {

        const checked_ids = [];
        $('tbody input[type="checkbox"]:checked').each(function() {
            const row = $(this).closest('tr');
            const item_id = row.data('item-id');
            checked_ids.push(item_id);
        });

        if (checked_ids.length === 0) {
            alert('삭제할 품목을 선택해주세요.');
            return;
        }

        if (!confirm('선택한 품목을 삭제하시겠습니까?')) {
            return;
        }

        console.log(checked_ids)

        start_loading();

        $.ajax({
            type: "GET",
            url: "/setting/item/delete_item",
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

    function change_status(item_id, e) {

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
                id: item_id,
                status: selected_status
            },
            dataType: "json",
            success: function(response) {

                alert(response.msg);

                if (response.ok && selected_status == '수주전환' && response.su_item_id) {

                    open_popup_default(`/sales/item_detail?id=${response.su_item_id}`, '수주서 상세', 1000, 820);
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