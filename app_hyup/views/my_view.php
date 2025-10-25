    <!-- 주문 내역 영역 -->
    <section class="lg:!px-0 !px-6">
        <h2 class="!text-lg font-bold !my-4">주문 조회</h2>
        <div class="!space-y-8 text-gray-400">
            <?
            if (!empty($order_items)) {

                $ORDER_STATUS = unserialize(ORDER_STATUS);


                foreach ($order_items as $order) {

            ?>
                    <div onclick="fadeOutButton('/my/order_detail?num=<?= $order['number'] ?>')" class="cursor-pointer flex flex-col gap-2">
                        <!-- 상품 카드 -->
                        <div class="border border-gray-200 rounded-md p-4 flex gap-4 bg-white">
                            <!-- 이미지 -->
                            <img class="h-[182px] w-42 object-cover rounded-md" src="<?= $order['image_url'] ?>" alt="<?= $order['name'] ?>" />

                            <!-- 상품 정보 -->
                            <div class="relative flex flex-1 flex-col justify-between">
                                <div class="!space-y-0.5">

                                    <div class="flex flex-col text-gray-500 text-sm gap-1">

                                        <?= status_badge($order['order_status']) ?>

                                        <span class="ml-2 color-sm">주문번호 : <?= $order['number'] ?></span>
                                    </div>

                                    <div class="font-semibold text-gray-800">
                                        <?= $order['name'] ?>
                                    </div>
                                    <div class="font-medium text-gray-800 !text-sm !mb-2">
                                        <?= $order['quantity'] ?>개
                                        <?= number_format($order['total_amount']) ?>원 <br />
                                        <?
                                        if ($order['shipping_fee'] > 0) {
                                        ?>
                                            배송비 <?= number_format($order['shipping_fee']) ?>원 <br />
                                        <?
                                        }
                                        ?>
                                        <?
                                        if ($order['option1_fee'] > 0) {
                                        ?>
                                            선물용 봉투 <?= number_format($order['option1_fee']) ?>원
                                        <?
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="lg:block hidden !text-sm !text-gray-800">
                                    <?= $order['address'] ?> <?= $order['address_detail'] ?> (<?= $order['zipcode'] ?>)
                                    <?
                                    if ($order['tracking_number']) {
                                    ?>
                                        <br />
                                        <span onclick="handle_view_tracking_number(event,'<?= str_replace('-', '', $order['tracking_number']) ?>');" class="!text-sm underline text-blue-500">CJ대한통운 <?= $order['tracking_number'] ?></span>
                                    <?
                                    }
                                    ?>
                                </div>

                                <div class="lg:!top-0 lg:!right-0 lg:!absolute lg:mt-0 !mt-1 bottom-0 flex gap-2 items-start !text-sm">
                                    <button onclick="re_order(event,'<?= $order['product_id'] ?>','<?= $order['quantity'] ?>','<?= $order['order_detail_id'] ?>');" class="bg-sm text-white border !text-sm border-gray-300 px-4 py-1 rounded-full hover:bg-gray-100 transition">
                                        재구매
                                    </button>
                                    <!-- 상태 및 버튼 -->
                                    <?
                                    if ($order['status'] == 'pending' || $order['status'] == 'paid') {
                                    ?>
                                        <button onclick="cancel_order(event,'<?= $order['order_item_id'] ?>');" class="border border-gray-300 px-4 py-1 !text-sm rounded-full hover:bg-gray-100 transition">취소</button>
                                    <?
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="lg:hidden block !text-sm !text-gray-600 leading-relaxed">
                            <p class="!text-gray-600 leading-relaxed">
                                <?= $order['receiver_name'] ?> / <?= $order['receiver_phone'] ?>
                            </p>
                            <p class="!text-gray-600 leading-relaxed">
                                <?= $order['address'] ?> <?= $order['address_detail'] ?> (<?= $order['zipcode'] ?>)
                                <?
                                if ($order['tracking_number']) {
                                ?>
                                    <br />
                                    <span onclick="handle_view_tracking_number(event,'<?= str_replace('-', '', $order['tracking_number']) ?>');" class="!text-sm underline text-blue-500">CJ대한통운 <?= $order['tracking_number'] ?></span>
                                <?
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                <?
                }
                ?>

            <?
            } else {
            ?>
                <p>
                    [EMPTY]
                    주문 내역이 없습니다.
                </p>
            <?
            }
            ?>

        </div>
    </section>

    <script>
        function re_order(e, product_id, quantity, order_detail_id) {
            e.stopPropagation();
            fadeOutButton(`/cart?order_detail_id=${order_detail_id}`);
        }

        function handle_view_tracking_number(e, tk) {
            e.stopPropagation();
            배송조회팝업(tk);
        }

        function cancel_order(e, id) {

            e.stopPropagation();
            const target = $(e.target);

            target.text('취소중').prop('disabled', true);

            $.ajax({
                type: "POST",
                url: "/my/cancel_order",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {

                    target.prop('disabled', false).text('취소');

                    alert(response.msg);

                    if (response.ok) {
                        fadeOutReload();
                        return;
                    }

                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    alert('주문 취소에 실패했습니다. 잠시 후 다시 시도해주세요.');
                    target.prop('disabled', false).text('취소');
                },
            });
        }
    </script>