<style>
    table th,
    table td {
        border: 1px solid #d1d5db;
        /* Tailwind의 gray-300 */
        padding: 0.75rem;
        /* Tailwind의 p-3 정도 */
        text-align: left;
        vertical-align: middle;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table thead {
        background-color: #f9fafb;
        /* gray-50 */
    }
</style>


<div class="!space-y-2">
    <h2 class="text-2xl font-bold mb-6">포인트관리</h2>

    <div class="">
        <form class="w-full flex items-center !mb-2 gap-2 !justify-between">
            <div class="flex w-full gap-2 items-center justify-between">

                <div class="">
                    <select name="search_point_status" onchange="$('form').submit();" class="select !w-fit">
                        <?
                        foreach ($search_point_status as $key => $value) {
                        ?>
                            <option value="<?= $key ?>" <?= $value['select'] ?>>
                                <?= $value['name'] ?>
                            </option>
                        <?
                        }
                        ?>
                    </select>

                    <select name="search_point_request_type" onchange="$('form').submit();" class="select !w-fit">
                        <?
                        foreach ($search_point_request_type_item as $key => $value) {
                        ?>
                            <option value="<?= $key ?>" <?= $value['select'] ?>>
                                <?= $value['name'] ?>
                            </option>
                        <?
                        }
                        ?>
                    </select>
                </div>

                <div class="flex gap-2 items-center">
                    <select name="search_type" class="select !w-fit">
                        <?
                        foreach ($search_type_item as $key => $value) {
                        ?>
                            <option value="<?= $key ?>" <?= $value['select'] ?>>
                                <?= $value['name'] ?>
                            </option>
                        <?
                        }
                        ?>
                    </select>

                    <input type="text" name="search_value" placeholder="Type here" class="input" value="<?= $search_value ?>" />

                    <button class="btn btn btn-soft" type="submit">
                        검색
                    </button>
                </div>
            </div>
        </form>
        <table class="table w-full !text-sm border border-gray-200">
            <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                <tr>
                    <th>회원정보</th>
                    <th>유형</th>
                    <th>은행</th>
                    <th>계좌</th>
                    <th>금액</th>
                    <th>상태</th>
                    <th>요청일</th>
                    <th>처리일</th>
                    <th>
                        처리명령
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?
                if (!empty($point_requests)) {

                    $BANK_CODE = unserialize(BANK_CODE);
                    $POINT_REQUEST_STATUS = unserialize(POINT_REQUEST_STATUS);
                    $POINT_REQUEST_TYPE = unserialize(POINT_REQUEST_TYPE);

                    foreach ($point_requests as $point_request) {
                ?>
                        <tr class="border-b">
                            <td>
                                <a class="!text-blue-600 hover:underline" href="/admin/agent?id=<?= $point_request['id'] ?>">
                                    <?= $point_request['user_id'] ?>
                                </a>
                            </td>
                            <td>
                                <?= $POINT_REQUEST_TYPE[$point_request['type']] ?? '' ?>
                            </td>
                            <td>
                                <?= $BANK_CODE[$point_request['bank']] ?? '' ?>
                            </td>
                            <td>
                                <?= $point_request['account_no'] ?>
                            </td>
                            <td>
                                <?= number_format($point_request['amount']) ?> 원
                            </td>
                            <td>
                                <?
                                $point_class = 'text-gray-500';
                                if ($point_request['status'] == 'pending') {
                                    $point_class = 'text-yellow-500';
                                } elseif ($point_request['status'] == 'approved') {
                                    $point_class = 'text-green-500';
                                } elseif ($point_request['status'] == 'rejected') {
                                    $point_class = 'text-red-500';
                                }
                                ?>
                                <span class="<?= $point_class ?>">
                                    <?= $POINT_REQUEST_STATUS[$point_request['status']] ?>
                                </span>
                            </td>
                            <td>
                                <?= explode(' ', $point_request['requested_at'])[0] ?>
                                <br />
                                <span class="text-xs text-gray-500">(<?= explode(' ', $point_request['requested_at'])[1] ?>)</span>
                            </td>
                            <td>
                                <? if (!empty($point_request['processed_at'])) { ?>
                                    <?= explode(' ', $point_request['processed_at'])[0] ?>
                                    <br />
                                    <span class="text-xs text-gray-500">(<?= explode(' ', $point_request['processed_at'])[1] ?>)</span>
                                <? } else { ?>
                                    <span class="text-gray-500">처리되지 않음</span>
                                <? } ?>
                            </td>
                            <td>
                                <?
                                if ($point_request['status'] == 'pending') {
                                ?>
                                    <button type="button" onclick="handle_request_approve(event,'<?= $point_request['request_id'] ?>');" class=" btn btn-sm btn-soft">승인</button>
                                    <button type="button" onclick="handle_request_reject(event,'<?= $point_request['request_id'] ?>');" class="ld-over-full btn btn-sm text-white  btn-error">거절</button>
                                <?
                                }
                                ?>
                            </td>
                        </tr>
                    <?
                    }
                    ?>
                <?
                } else {
                ?>
                    <tr class="border-b">
                        <td colspan="9" class="!text-center">
                            <span class="text-gray-500">등록된 요청이 없습니다.</span>
                        </td>
                    </tr>
                <?
                }
                ?>
            </tbody>
        </table>
    </div>


</div>

<script>
    function handle_request_reject(e, id) {
        const target = $(e.target);

        btn_start(target);

        $.ajax({
            type: "POST",
            url: "/admin/point/reject_request",
            dataType: "json",
            data: {
                id: id,
            },
            success: function(response) {
                alert(response.message);
                if (response.ok) {
                    location.reload();
                } else {}

                btn_end(target);
            },
            error: function(xhr, status, error) {
                alert(error);
                btn_end(target);
            }
        });
    }

    function handle_request_approve(e, id) {

        const target = $(e.target);

        btn_start(target);

        $.ajax({
            type: "POST",
            url: "/admin/point/approve_request",
            dataType: "json",
            data: {
                id: id,
            },
            success: function(response) {
                alert(response.message);
                if (response.ok) {
                    location.reload();
                } else {}

                btn_end(target);
            },
            error: function(xhr, status, error) {
                alert(error);
                btn_end(target);
            }
        });
    }

    function handle_search_submit(e) {

        const target = $(e.target);
        const form1 = $("form")
        form1.submit()
    }

    $('#all_check').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.item_check').prop('checked', isChecked);
    });

    $('.item_check').on('change', function() {
        const total = $('.item_check').length;
        const checked = $('.item_check:checked').length;
        $('#all_check').prop('checked', total === checked);
    });

    function handle_agent(e) {

        const selectedItems = $('.item_check:checked');
        if (selectedItems.length === 0) {
            alert('처리할 회원을 선택해주세요.');
            return;
        }

        const userIds = selectedItems.map(function() {
            return $(this).val();
        }).get();


        $.ajax({
            type: "POST",
            url: "/admin/agent/add_agent",
            data: {
                user_ids: userIds,
            },
            dataType: "json",
            success: function(response) {

                if (response.ok) {
                    alert('처리가 완료되었습니다.');
                    location.reload();
                } else {
                    alert('처리 중 오류가 발생했습니다: ' + response.message);
                }
            }
        });
    }

    function handle_delete_agent(e) {

        const selectedItems = $('.item_check:checked');
        if (selectedItems.length === 0) {
            alert('처리할 회원을 선택해주세요.');
            return;
        }

        const userIds = selectedItems.map(function() {
            return $(this).val();
        }).get();

        if (!confirm('선택한 회원의 부본사를 해제하시겠습니까?')) {
            return;
        }

        $.ajax({
            type: "POST",
            url: "/admin/agent/delete_agent",
            data: {
                user_ids: userIds,
            },
            dataType: "json",
            success: function(response) {

                if (response.ok) {
                    alert('부본사 해제가 완료되었습니다.');
                    location.reload();
                } else {
                    alert('처리 중 오류가 발생했습니다: ' + response.message);
                }
            }
        });
    }
</script>