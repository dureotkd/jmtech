<div class="p-4 bg-white font-sans text-xs text-gray-800">

    <h1 class="!text-xl !border-b !font-sans !border-gray-300 !pb-3">
        거래처관리
    </h1>

    <!-- 필터 영역 -->
    <div class="flex items-center gap-2 mb-4 !text-xs">

        <div class="ml-auto flex w-full items-center gap-2 justify-between">
            <button onclick="delete_partner(event);" type="button" class="!my-2  flex items-center gap-1 border border-gray-300 rounded h-7 !px-3 bg-white hover:bg-gray-50 transition text-xs"><input multiple="" type="file" style="display: none;">
                삭제
            </button>
            <button
                onclick="open_popup_default('/setting/partner/create','거래처 등록',1000,720);"
                type="button"
                class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
                거래처 등록 +
            </button>
        </div>
    </div>

    <form id="searchFrm">
        <input type="hidden" name="page" id="page" value="<?= $page ?>">
        <!-- 테이블 -->
        <table class="w-full border border-gray-300">
            <thead>
                <tr class="bg-[#788496] text-white">
                    <th class=" w-10"><input type="checkbox" id="all_check" /></th>
                    <th class="">거래처명</th>
                    <th class="">등록번호</th>
                    <th class="">대표자명</th>
                    <th></th>
                    <th class="">이월미수금</th>
                    <th class="">이월미지급금</th>
                    <th class=""></th>
                    <th class=" w-32"></th>
                </tr>
            </thead>
            <tbody>
                <?
                $총건수 = 0;
                $이월미수총액 = 0;
                $이월미지급총액 = 0;
                if (!empty($partner_list)) :
                    foreach ($partner_list as $partner) :

                        $총건수 += 1;
                        $이월미수총액 += (int)$partner['이월미수금'];
                        $이월미지급총액 += (int)$partner['이월미지급금'];

                ?>

                        <tr class="border-b hover:bg-gray-50" onclick="go_detail('<?= $partner['id'] ?>')" data-partner-id="<?= $partner['id'] ?>">
                            <td><input type="checkbox" partner-id="<?= $partner['id'] ?>" onclick="event.stopPropagation();" /></td>
                            <td class="" data-label="거래처명">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building2-icon lucide-building-2">
                                        <path d="M10 12h4" />
                                        <path d="M10 8h4" />
                                        <path d="M14 21v-3a2 2 0 0 0-4 0v3" />
                                        <path d="M6 10H4a2 2 0 0 0-2 2v7a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-2" />
                                        <path d="M6 21V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16" />
                                    </svg>
                                    <?= $partner['company_name'] ?>
                                </div>
                            </td>
                            <td class="" data-label="등록번호"><?= $partner['company_num'] ?></td>
                            <td class="" data-label="대표자명"><?= $partner['ceo_name'] ?></td>
                            <td>
                                <div class="flex gap-2">
                                    <button
                                        type="button"
                                        class="px-3 py-1 text-red-600 border border-red-300 bg-red-50 rounded-full text-[12px] font-medium hover:bg-red-100 transition">
                                        매출
                                    </button>
                                    <button
                                        type="button"
                                        class="px-3 py-1 text-blue-600 border border-blue-300 bg-blue-50 rounded-full text-[12px] font-medium hover:bg-blue-100 transition">
                                        매입
                                    </button>
                                </div>

                            </td>
                            <td class="" data-label="이월미수금"><?= $partner['이월미수금'] > 0 ? number_format($partner['이월미수금']) : '' ?></td>
                            <td class="" data-label="이월미지급금"><?= $partner['이월미지급금'] > 0 ? number_format($partner['이월미지급금']) : '' ?></td>
                            <td class="" data-label="상태">
                            </td>
                            <td class="cursor-pointer" data-label="">
                            </td>
                        </tr>
                    <? endforeach;
                else : ?>
                    <tr>
                        <td colspan="9" class="text-center py-4">등록된 거래처가 없습니다.</td>
                    </tr>
                <? endif; ?>

                <?
                if (!empty($estimate_all)) {
                ?>
                    <tr class="border-b bg-[#e9f1fb]">
                        <td></td>
                        <td class=""></td>
                        <td class="!font-bold">총 <?= count($estimate_all) ?>건</td>
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

</div>

<script>
    function go_detail(estimate_id) {
        // open_popup_default(`/sales/estimate_detail?id=${estimate_id}`, '견적서 상세', 1000, 820);
    }

    function handle_select(event) {
        event.stopPropagation(); // 트리거링 방지
    }

    function delete_partner(e) {

        const checked_ids = [];
        $('tbody input[type="checkbox"]:checked').each(function() {
            const row = $(this).closest('tr');
            const partner_id = row.data('partner-id');
            checked_ids.push(partner_id);
        });

        if (checked_ids.length === 0) {
            alert('삭제할 거래처를 선택해주세요.');
            return;
        }

        if (!confirm('선택한 거래처를 삭제하시겠습니까?')) {
            return;
        }

        start_loading();

        $.ajax({
            type: "GET",
            url: "/setting/partner/delete_partner",
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