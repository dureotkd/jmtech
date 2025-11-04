<div class="p-4 bg-white font-sans text-sm text-gray-800">

    <h1 class="!text-xl !border-b !font-sans !border-gray-300 !pb-3">
        견적서
    </h1>

    <!-- 필터 영역 -->
    <div class="flex items-center gap-2 mb-4 !text-sm">

        <div class="ml-auto flex w-full items-center gap-2 justify-between">
            <button onclick="delete_estimate(event);" type="button" class="!my-2  flex items-center gap-1 border border-gray-300 rounded h-7 !px-3 bg-white hover:bg-gray-50 transition text-sm"><input multiple="" type="file" style="display: none;">
                삭제
            </button>
            <button
                onclick="open_popup_default('<?= REACT_PATH ?>','견적서 등록',1000,820);"
                type="button"
                class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
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
        <tbody>
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

                    <tr class="border-b hover:bg-gray-50" onclick="go_detail('<?= $estimate['id'] ?>')" data-estimate-id="<?= $estimate['id'] ?>">
                        <td><input type="checkbox" estimate-id="<?= $estimate['id'] ?>" onclick="event.stopPropagation();" /></td>
                        <td class="">
                            <?= date('Y-m-d', strtotime($estimate['created_at'])) ?>
                        </td>
                        <td class="">
                            <?= $estimate['partner_name'] ?>
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
                                if ($estimate['status'] === '수주전환' && !empty($estimate['su_estimate_id'])) {
                                ?>
                                    <button
                                        onclick="event.stopPropagation(); open_popup_default(`/sales/estimate_detail?id=<?= $estimate['su_estimate_id'] ?>`, '수주서 상세', 1000, 820);"
                                        type="button"
                                        class="sm-btn bg-primary !m-0 text-xs">
                                        수주서 보기
                                    </button>
                                <?
                                }
                                ?>
                            </div>

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
                    <td colspan="7" class="text-center py-4">등록된 견적서가 없습니다.</td>
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

</div>

<script>
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