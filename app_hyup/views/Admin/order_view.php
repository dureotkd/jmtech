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
    <h2 class="!text-lg font-bold mb-6">주문관리</h2>

    <form id="search_form" action="/admin/order" class="!mt-4 flex items-center gap-2 bg-white justify-between">
        <input type="hidden" name="excel_yn" value="<?= $excel_yn ?>" id="excel_yn" />
        <input type="hidden" name="page" value="<?= $page ?>" />

        <div class="flex flex-col w-full">
            <div class="w-full flex justify-between">
                <div class="flex gap-2">

                    <select name="search_order_status" onchange="submit_search_form();" class="select !w-fit min-w-[180px]">

                        <?
                        foreach ($search_order_status_item as $key => $value) {
                        ?>
                            <option value="<?= $key ?>" <?= $value['select'] ?>>
                                <?= $value['name'] ?>
                            </option>
                        <?
                        }
                        ?>
                    </select>

                    <select name="search_type" class="select !w-fit min-w-[120px]">

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

                    <!-- 검색 -->
                    <button type="submit" class="btn btn btn-soft !px-4 !py-2">
                        검색
                    </button>
                </div>
                <div class="">
                    <button type="button" onclick="download_excel();"
                        class="inline-flex items-center gap-2 bg-green-100 text-green-800 border border-green-300 px-4 py-2 text-sm rounded hover:bg-green-200 transition">
                        엑셀 다운로드
                    </button>
                </div>
            </div>
        </div>

    </form>

    <div class="!text-sm overflow-x-auto !mt-4">
        <table class="table w-full border border-gray-200">
            <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                <tr>
                    <th class="w-[110px]">
                        주문일/결제일
                    </th>
                    <th class="w-[90px]">
                        결제유형
                    </th>
                    <th class="w-[220px]">
                        주문번호/상품명/개수
                    </th>
                    <th class="w-[120px]">
                        결제금액
                    </th>
                    <th>
                        주문상태
                    </th>
                    <th class="w-[200px]">
                        주문자 정보
                    </th>
                    <th class="w-[200px]">
                        수령인 정보
                    </th>
                    <th>
                        배송 정보
                    </th>
                    <th class="w-[150px]">
                        택배사/송장번호
                    </th>
                    <th class="w-[230px]">
                        관리자 메모
                    </th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?
                if (!empty($order_items)) {

                    $ORDER_STATUS = unserialize(ORDER_STATUS);
                    $AGENT = unserialize(AGENT);
                    $PAY_METHOD_CODE = unserialize(PAY_METHOD_CODE);

                    foreach ($order_items as $order) {
                ?>
                        <tr class="border-b">
                            <td data-label="주문일">
                                <?= explode(' ', $order['ordered_at'])[0] ?><br />
                                <?= explode(' ', $order['ordered_at'])[1] ?>
                            </td>
                            </td>
                            <td data-label="결제유형">
                                <?
                                if (!empty($order['app_card_name'])) {
                                ?>
                                    <?= $order['app_card_name'] ?>
                                <?php
                                } else {
                                ?>
                                    <?= $order['payment_method'] ?>
                                <?
                                }
                                ?>
                            </td>
                            <td data-label="상품명">
                                <span class=""><?= $order['number'] ?></span><br />
                                <?= $order['product_name'] ?><br />
                                <?= $order['quantity'] ?>개<br />

                                <?
                                if ($order['is_multy'] && $order['bundle_items_cnt'] > 1) {
                                ?>
                                    <button onclick="handle_bundle_item('<?= $order['order_item_id'] ?>');" type="button" class="!mt-2 btn btn-sm btn-soft">
                                        제품 더보기 <span class="accordion-icon">▾</span>
                                    </button>
                                <?
                                }
                                ?>
                            </td>
                            <td data-label="결제금액">
                                <?= number_format($order['total_amount'] + $order['shipping_fee']) ?> 원
                            </td>
                            <td data-label="주문상태">
                                <?
                                if ($order['order_status'] == 'canceled' || $order['order_status'] == 'completed') {
                                ?>
                                    <?= status_badge($order['order_status']) ?>
                                <?
                                } else {
                                ?>
                                    <select data-original-value=<?= $order['order_status'] ?> onchange="update_order_status(event,'<?= $order['order_item_id'] ?>')" class="border border-gray-300 px-2 py-1" name="order_status[<?= $order['order_item_id'] ?>]">
                                        <?
                                        foreach ($ORDER_STATUS as $key => $status) {
                                            $selected = ($order['order_status'] == $key) ? 'selected' : '';
                                        ?>
                                            <option value="<?= $key ?>" <?= $selected ?>><?= $status ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                <?
                                }
                                ?>
                            </td>
                            <td data-label="주문자 정보">
                                <a class="!text-blue-600 hover:underline" href="/admin/agent?id=<?= $order['id'] ?>">
                                    <?= $order['customer_name'] ?> <br />
                                    <?= $order['customer_phone'] ?> <br />
                                </a>
                            </td>
                            <td data-label="수령인 정보">
                                <?= $order['receiver_name'] ?> <br />
                                <?= $order['receiver_phone'] ?> <br />
                            </td>
                            <td data-label="배송 정보">
                                <?= $order['address'] . ' ' . $order['address_detail'] . ' <br/>(' . $order['zipcode'] . ')' ?>
                                <br />
                                <br />
                                <?= $order['order_memo'] ?>
                            </td>
                            <td data-label="택배사/송장번호">
                                <div class="flex  flex-col gap-2">
                                    <?= $order['delivery_company'] ?><br />
                                    <input type="text" name="tracking_number[<?= $order['order_item_id'] ?>]" value="<?= $order['tracking_number'] ?>" class="w-full border border-gray-300 rounded px-2 py-1 text-sm placeholder-gray-400" />
                                    <button type="button" onclick="update_tracking_number(event,<?= $order['order_detail_id'] ?>)" class="btn btn-soft !text-sm !px-3 !py-1.5 !rounded-md !bg-gray-100 hover:!bg-gray-200">
                                        저장
                                    </button>
                                </div>
                            </td>
                            <td data-label="관리자 메모">
                                <div class="w-full flex flex-col gap-2">
                                    <textarea
                                        oninput="handle_textarea_height(this);"
                                        onfocus="resize_textarea_once(this);"
                                        class="resize-y w-full border border-gray-300 rounded px-2 py-1 text-sm placeholder-gray-400"
                                        name="admin_memo[<?= $order['order_item_id'] ?>]"><?= $order['admin_memo'] ?></textarea>
                                    <button type="button" onclick="updateAdminMemo(event,<?= $order['order_detail_id'] ?>)" class="btn btn-soft !text-sm !px-3 !py-1.5 !rounded-md !bg-gray-100 hover:!bg-gray-200">
                                        저장
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <?
                        if ($order['is_multy'] && $order['bundle_items_cnt'] > 1) {
                        ?>
                            <tr class="bundle-item-<?= $order['order_item_id'] ?> hidden">
                                <td colspan="9" class="!p-0">
                                    <div class="!p-4 !bg-gray-50 !border-t !border-gray-200">
                                        <div class="!text-sm !text-gray-600 !space-y-2">
                                            <?
                                            foreach ($order['bundle_items'] as $bundle_item) {
                                            ?>
                                                <div class="!mb-4 flex items-center gap-2">
                                                    <img src="<?= $bundle_item['image_url'] ?>" alt="<?= $bundle_item['product_name'] ?>" class="h-16 w-16 object-cover rounded-md inline-block mr-2" />

                                                    <div class="flex flex-col">
                                                        <span class="!text-gray-800 !font-semibold">
                                                            <?= $bundle_item['product_name'] ?>
                                                        </span>

                                                        <div class="">
                                                            <span class="!text-gray-800">
                                                                <?= $bundle_item['bundle_item_quantity'] ?>개 X <?= number_format($bundle_item['bundle_item_price']) ?>원
                                                                = <?= number_format($bundle_item['bundle_item_amount']) ?>원
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                        <?
                        }
                        ?>
                    <?
                    }
                    ?>
                <?
                }
                ?>

            </tbody>
        </table>
    </div>

</div>

<dialog id="my_modal_1" class="modal">
    <div class="modal-box !px-6 !py-4">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <div class="flex flex-col items-center justify-center">
            <h3 class="!text-lg !font-bold text-center">상품 수정</h3>
            <div class="!my-4 relative w-18 h-18">
                <!-- 아바타 이미지 -->
                <img
                    id="profile_image"
                    src="/assets/app_hyup/images/default_profile.png"
                    alt="프로필 이미지"
                    class="w-18 h-18 rounded-full object-cover border border-gray-200" />

                <!-- 카메라 아이콘 버튼 -->
                <button
                    class="absolute bottom-0 right-0 w-7 h-7 bg-white border border-gray-300 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-100"
                    onclick="document.getElementById('profileInput').click()"
                    title="사진 변경">
                    <input type="file" id="profileInput" name="profile_image" accept="image/*" class="hidden" onchange="handle_image_upload(event);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 lucide lucide-camera-icon lucide-camera">
                        <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                        <circle cx="12" cy="13" r="3" />
                    </svg>
                </button>
            </div>
        </div>

        <form onsubmit="handle_join_form(event);" id="join-form" class="!mt-2 border-t border-black text-sm !space-y-6">

            <div class="">
                <input class="w-full !border !border-gray-200  px-4 py-3 text-sm !placeholder-gray-400"
                    placeholder="아이디"
                    type="text"
                    value=""


                    name="user_id">
                <input
                    type="password"
                    name="password"
                    placeholder="비밀번호를 변경하는 경우 입력해주세요"
                    style="border-top: none;"
                    class="w-full border border-gray-200  px-4 py-3 text-sm placeholder-gray-400"
                    value="" />
                <input
                    type="password"
                    name="repassword"
                    placeholder="비밀번호 확인"
                    style="border-top: none;"
                    class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400"
                    value="" />
            </div>


            <div class="flex gap-4">
                <div class="w-full">
                    <label class="text-sm text-left font-semibold text-gray-800 !mb-2 block">이름</label>
                    <input
                        type="text"
                        name="name"
                        placeholder="이름"
                        class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400"
                        value="" />
                </div>

                <div class="w-full">
                    <label class="text-sm text-left font-semibold text-gray-800 !mb-2 block">연락처</label>
                    <input
                        type="text"
                        name="phone"
                        placeholder="연락처"
                        oninput="phoneNumberMask(this)"
                        class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400"
                        value="" />
                </div>
            </div>

            <div class="flex items-end gap-4">

                <div class="w-full">
                    <label class="text-sm text-left font-semibold text-gray-800 !mb-2 block">이메일</label>
                    <input
                        type="text"
                        name="email"
                        placeholder="이메일"
                        class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400"
                        value="" />
                </div>

                <div class="w-full">
                    <select name="email_domain"
                        class="w-full border border-gray-200 rounded !px-4 !py-3 text-gray-700">
                        <option value="naver.com">@naver.com</option>
                        <option value="daum.net">@daum.net</option>
                        <option value="gmail.com">@gmail.com</option>
                    </select>
                </div>
            </div>

            <div class="w-full">
                <label class="text-sm text-left font-semibold text-gray-800 !mb-2 block">총판코드</label>
                <input
                    type="text"
                    name="store_code"
                    placeholder="총판코드"
                    class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400"
                    value="" />
            </div>

            <!-- 버튼 -->
            <div class="flex justify-between mt-8 gap-4">
                <button type="button" onclick="my_modal_1.close();" class="w-1/2 border border-gray-300 py-3 text-sm hover:bg-gray-50">닫기</button>
                <button type="submit" class="w-1/2 btn-primary-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900">수정</button>
            </div>
        </form>
    </div>
</dialog>

<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
<script>
    const handle_textarea_height = _.debounce((el) => {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    }, 400); // 100ms 지연

    function handle_bundle_item(item_id) {

        $(`.bundle-item-${item_id}`).toggleClass('hidden');
    }

    function resize_textarea_once(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
    }

    function download_excel(e) {
        const excel_yn = $("input[name='excel_yn']").val();
        if (excel_yn === 'Y') {
            alert('이미 엑셀 다운로드가 진행 중입니다.');
            return;
        }

        $("input[name='excel_yn']").val('Y');
        $("#search_form").submit();
        $("input[name='excel_yn']").val('N');
    }

    function submit_search_form() {
        $("name[page]").val(1);
        $("#search_form").submit();
    }

    function update_order_status(e, id) {

        const target = $(e.target);
        const value = target.val();

        if (value == 'canceled') {
            if (!confirm('주문을 취소하면 환불 처리가 진행됩니다. 정말로 취소하시겠습니까?')) {
                target.val(target.data('original-value')); // 원래 값으로 되돌리기
                return;
            }
        }

        if (value == 'completed') {
            if (!confirm('구매확정 하면 포인트 지급으로 인해 되돌릴 수 없습니다. 정말로 완료하시겠습니까?')) {
                target.val(target.data('original-value')); // 원래 값으로 되돌리기
                return;
            }
        }

        $.ajax({
            type: "POST",
            url: "/admin/order/update_order_status",
            data: {
                id: id,
                order_status: value,
            },
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    alert('주문 정보가 업데이트되었습니다.');
                } else {
                    alert(response.msg);
                }
            }
        });
    }

    function updateAdminMemo(e, id) {
        const memo = $(e.target).siblings('textarea').val();
        $.ajax({
            type: "POST",
            url: "/admin/order/update_admin_memo",
            data: {
                id: id,
                memo: memo
            },
            dataType: "json",
            success: function(response) {

            }
        });
    }

    function update_tracking_number(e, id) {
        const tracking_number = $(e.target).siblings('input').val();
        $.ajax({
            type: "POST",
            url: "/admin/order/update_tracking_number",
            data: {
                id: id,
                tracking_number: tracking_number
            },
            dataType: "json",
            success: function(response) {}
        });
    }

    function update_product(id) {

        const order_status = $(`select[name="order_status[${id}]"]`).val();
        const tracking_number = $(`input[name="tracking_number[${id}]"]`).val();
        const admin_memo = $(`textarea[name="admin_memo[${id}]"]`).val();

        if (order_status == 'canceled') {
            if (!confirm('주문을 취소하면 환불 처리가 진행됩니다. 정말로 취소하시겠습니까?')) {
                return;
            }
        }

        if (order_status == 'completed') {
            if (!confirm('구매확정 하면 포인트 지급으로 인해 되돌릴 수 없습니다. 정말로 완료하시겠습니까?')) {
                return;
            }
        }

        $.ajax({
            type: "POST",
            url: "/admin/order/update_order",
            data: {
                id: id,
                order_status: order_status,
                tracking_number: tracking_number,
                admin_memo: admin_memo
            },
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    alert('주문 정보가 업데이트되었습니다.');
                } else {
                    alert(response.msg);
                }
            }
        });
    }

    function delete_product(id) {

        if (confirm('정말로 이 상품을 삭제하시겠습니까?')) {

            $.ajax({
                type: "POST",
                url: "/admin/product/delete_product",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.ok) {
                        alert(response.msg);
                        window.location.reload();
                    } else {
                        alert(response.msg);
                    }
                }
            });
        }
    }
</script>