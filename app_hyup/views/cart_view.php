<div class="min-h-screen w-full h-full">
    <div class="max-w-5xl flex flex-col !mx-auto !p-6 !space-y-6">
        <h1 class="!text-2xl font-semibold text-center !mb-6">장바구니</h1>

        <form id="order_frm" method="POST" class="lg:flex-row flex flex-col w-full gap-6">
            <input type="hidden" name="shipping_fee" value="<?= $배송비 ?>">
            <input type="hidden" name="user_id" value="<?= $login_user['id'] ?>">

            <div class="lg:w-1/2 w-full flex flex-col gap-6">
                <div class="bg-white w-full rounded !h-fit !p-6 !space-y-4">
                    <h2 class="!text-lg font-semibold">주문 상품 정보</h2>
                    <div class="flex flex-col gap-4">
                        <?
                        $cart_product_name =
                            count($product_all) > 1 ?
                            $product_all[0]['name'] . " 외 " . (count($product_all)) . "종" : $product_all[0]['name'];
                        $cart_product_quantity = 0;
                        $cart_product_price = 0;
                        $MOID = 'Moid_' . time() . rand(1000, 9999);
                        $expire_time = date('YmdHis', strtotime('+1 day'));

                        // 1일후
                        $tday = date('YmdHis', strtotime('+1 day'));

                        foreach ($product_all as $index => $product) {

                            $cart_product_quantity += $product['quantity'];
                            $cart_product_price += $product['price'] * $product['quantity'];
                        ?>
                            <div class="cart-item-box flex gap-4 !border-b !border-gray-200 !pb-4">
                                <img src="<?= $product['image_url'] ?>" alt="<?= $product['name'] ?>" class="w-28 h-[124px] object-cover border rounded">
                                <div class="flex-1 !space-y-1">
                                    <div class="flex justify-between items-center">
                                        <p class="font-semibold"><?= $product['name'] ?></p>
                                        <button onclick="remove_cart(event,<?= $product['id'] ?>);" type="button" class="btn btn-sm btn-circle btn-ghost">✕</button>
                                    </div>
                                    <?
                                    if (!empty($product['discount_price'])) {
                                    ?>
                                        <input type="hidden" name="product_id[]" value="<?= $product['id'] ?>">
                                        <input type="hidden" class="price" name="price[]" value="<?= $product['discount_price'] ?>">

                                        <span class="text-lg font-bold text-gray-800 !mr-2"><?= number_format($product['discount_price']) ?>원</span>
                                        <span class="line-through !text-sm text-gray-400"><?= number_format($product['ori_price']) ?>원</span>
                                    <?
                                    } else {
                                    ?>
                                        <input type="hidden" name="product_id[]" value="<?= $product['id'] ?>">
                                        <input type="hidden" class="price" name="price[]" value="<?= $product['price'] ?>">

                                        <span class="text-lg font-bold text-gray-800"><?= number_format($product['price']) ?>원</span>
                                    <?
                                    }
                                    ?>
                                    <div class="flex flex-col gap-2 items-start font-medium !mt-1">
                                        <span>수량</span>
                                        <div class="flex items-center border border-gray-300 rounded-sm overflow-hidden w-fit">
                                            <button type="button" onclick="handle_quan(event,'down');" class=" border-1 h-[30px] border-gray-300 px-3 py-1 text-gray-600 hover:bg-gray-100">-</button>
                                            <input
                                                type="text"
                                                min="1"
                                                name="quantity[]"
                                                value="<?= $product['quantity'] ?>"
                                                readonly
                                                class="w-12 h-[30px] text-center py-1 text-sm border-gray-300 outline-none" />
                                            <button type="button" onclick="handle_quan(event,'up');" class="border-1 border-gray-300 px-3 py-1 h-[30px] text-gray-600 hover:bg-gray-100">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?
                        }
                        ?>
                    </div>
                </div>

                <div class="bg-white w-full !p-6 !space-y-4">
                    <h2 class="!text-lg font-semibold mb-4">주문자 정보</h2>
                    <div class="flex flex-col gap-2">
                        <p>
                            <?= $login_user['name'] ?>
                        </p>
                        <p>
                            <?= $login_user['email'] ?>
                        </p>
                        <p>
                            <?= $login_user['phone'] ?>
                        </p>
                    </div>
                </div>

                <div class="bg-white w-full !p-6 !space-y-4">
                    <h2 class="!text-lg font-semibold mb-4">배송 정보</h2>
                    <div class="flex flex-col gap-7">
                        <div class="flex items-center gap-4">
                            <input type="text" name="receiver_name" value="<?= $login_user['name'] ?>" placeholder="수령인" class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                            <input onchange="handle_receiver_phone_change(event);" type="text" name="receiver_phone" value="<?= $login_user['phone'] ?>" placeholder="수령인 연락처" class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                        </div>

                        <div class="!space-y-1 text-sm text-gray-800">
                            <div class="flex items-center gap-2">
                                <input type="number" name="zipcode" readonly placeholder="우편번호" value="<?= $zipcode || '' ?>" class="w-fit max-w-[145px] border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                                <button onclick="open_kakao_post_pop(event);" type="button" class="px-4 py-1.5 border rounded-full text-sm text-[#0abab5] border-[#0abab5] hover:bg-[#0abab5]/10 transition">
                                    주소찾기
                                </button>
                            </div>
                            <input type="address" name="address" placeholder="주소" value="<?= $address ?>" readonly class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                            <input type="text" name="address_detail" placeholder="상세주소" value="<?= $address_detail ?>" class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                        </div>

                        <div>
                            <label for="deliveryMemo" class="text-sm font-medium text-gray-700 block">배송메모</label>
                            <select onchange="handle_memo(event);" name="memo" id="deliveryMemo" class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-gray-200 focus:outline-none appearance-none bg-white">
                                <option value="배송 전에 미리 연락 바랍니다.">배송 전에 미리 연락 바랍니다.</option>
                                <option value="부재시 경비실에 맡겨주세요.">부재시 경비실에 맡겨주세요.</option>
                                <option value="부재시 전화나 문자를 남겨주세요.">부재시 전화나 문자를 남겨주세요.</option>
                                <option value="직접입력">직접입력</option>
                            </select>
                            <input class="w-full mt-2 border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none hidden" type="text" id="deliveryMemoInput" placeholder="직접입력 시 여기에 작성해 주세요.">
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:w-1/2 w-full flex flex-col gap-6 sticky top-0">

                <div class="bg-white h-fit w-full !p-6 !space-y-4">
                    <h2 class="!text-lg font-semibold mb-4">추가 옵션</h2>
                    <div class="flex flex-col gap-2">
                        <!-- <select id="option_select"
                            onchange="handle_option(event);"
                            class="border-gray-300 rounded !p-3 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="0">선택 안 함</option>
                            <option value="1000" <?= $option1_fee == '1000' ? 'selected' : '' ?>>선물용 봉투 (+1,000원)</option>
                        </select> -->
                        <div class="flex !items-center justify-between">
                            <p class="!mt-1">
                                선물용 봉투 (+1,000원)
                            </p>
                            <div class="flex items-center border border-gray-300 rounded-sm overflow-hidden w-fit">
                                <button type="button" onclick="handle_quan1(event,'down');" class=" border-1 h-[30px] border-gray-300 px-3 py-1 text-gray-600 hover:bg-gray-100">-</button>
                                <input type="text" min="1" name="option1_quantity" id="option1_quantity" value="<?= $option1_quantity ?>" readonly="" class="w-12 h-[30px] text-center py-1 text-sm border-gray-300 outline-none">
                                <button type="button" onclick="handle_quan1(event,'up');" class="border-1 border-gray-300 px-3 py-1 h-[30px] text-gray-600 hover:bg-gray-100">+</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="bg-white h-fit w-full !p-6 !space-y-4">
                    <h2 class="!text-lg font-semibold mb-4">주문 요약</h2>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-500">상품가격</span>
                            <span>
                                <span id="product_amount"><?= number_format($cart_product_price) ?>원</span>
                            </span>
                        </div>

                        <div class="flex justify-between mb-1">
                            <span class="text-gray-500">배송비</span>
                            <span>
                                <span id="shipping_fee"><?= $배송비 > 0 ? number_format($배송비) . '원' : '무료' ?></span>
                            </span>
                        </div>


                        <div class="flex justify-between mb-1 <?= $option1_quantity > 0 ? '' : 'hidden' ?>" id="option1">
                            <span class="text-gray-500">선물용 봉투</span>
                            <span id="option1_fee"><?= number_format($option1_fee) ?>원</span>
                        </div>

                        <hr class="border-t border-gray-200 mb-3">

                        <div class="flex justify-between items-center font-bold text-base mb-2">
                            <span>총 주문금액</span>
                            <span>
                                <span id="total_amount"><?= number_format($cart_product_price + $배송비 + $option1_fee) ?>원</span>
                            </span>
                        </div>
                        <!-- <p class="!mt-2 !text-sm text-gray-400">
                            0
                            <span class="font-medium text-gray-500">포인트 적립예정</span>
                        </p> -->
                    </div>
                </div>

                <div class="bg-white h-fit w-full !p-6 !space-y-4">
                    <h2 class="!text-lg font-semibold mb-4">결제수단</h2>
                    <div class="flex flex-col gap-2">
                        <label class="flex items-center gap-2 radio-label">
                            <input onchange="handle_payment_method(event);" type="radio" name="payment_method" value="무통장입금" class="radio-input" />
                            <span class="ml-2">무통장입금</span>
                        </label>
                        <label class="flex items-center gap-2 radio-label">
                            <input onchange="handle_payment_method(event);" type="radio" name="payment_method" value="카드" checked class="radio-input" />
                            <span class="ml-2">신용카드 및 간편결제</span>
                        </label>
                    </div>
                </div>

                <!-- 동의 및 버튼 -->
                <div class="bg-white h-fit w-full !p-6 !space-y-4">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="payment_agree" class="agree accent-gray-800">
                        <span>구매조건 확인 및 결제진행에 동의</span>
                    </label>

                    <div class="flex gap-4">
                        <button id="pay_btn" onclick="goPay(event);" type="button" class="w-full btn-primary-sm !py-3 rounded hover:bg-gray-700 transition">결제하기</button>

                    </div>
                </div>

            </div>
        </form>

        <?
        $총결제금액 = (int)$cart_product_price + (int)$배송비 + (int)$option1_fee;
        $EncryptData = base64_encode(hash('sha256', $tday . MID . $총결제금액 . MKEY, true));
        ?>

        <form id="tranMgr" name="tranMgr" class="hidden" method="post">
            <input type="hidden" name="PayMethod" value="CARD">
            <input type="hidden" name="user_id" value="<?= $login_user['id'] ?>">
            <input type="hidden" name="GoodsCnt" value="<?= $cart_product_quantity ?>">
            <input type="hidden" name="GoodsName" value="<?= $cart_product_name ?>">
            <input type="hidden" name="ReturnUrl" class="input" value="/cart/pay">
            <input type="hidden" name="Amt" id="Amt" value="<?= $총결제금액 ?>">
            <input type="hidden" name="Moid" value="<?= $MOID ?>">
            <input type="hidden" name="Mid" value="<?= MID ?>">
            <input type="hidden" name="BuyerName" value="<?= $login_user['name'] ?>">
            <input type="hidden" name="BuyerTel" value="<?= $login_user['phone'] ?>">
            <input type="hidden" name="BuyerEmail" value="<?= $login_user['email'] ?>">
            <input type="hidden" name="EdiDate" value="<?= $tday ?>">
            <input type="hidden" name="EncryptData" id="EncryptData" value="<?= $EncryptData ?>">
            <input type="hidden" name="MallIp" value="<?= $_SERVER['REMOTE_ADDR'] ?>">

            <?
            if (IS_MOBILE) {
            ?>
                <input type="hidden" name="MallReserved" value="" />
            <?
            }
            ?>

        </form>

        <form id="approvalForm" name="approvalForm" method="post">
            <input type="hidden" id="Tid" name="Tid" />
            <input type="hidden" id="TrAuthKey" name="TrAuthKey" />

            <input type="hidden" name="PayMethod" value="CARD">
            <input type="hidden" name="user_id" value="<?= $login_user['id'] ?>">
            <input type="hidden" name="GoodsCnt" value="<?= $cart_product_quantity ?>">
            <input type="hidden" name="GoodsName" value="<?= $cart_product_name ?>">
            <input type="hidden" name="ReturnUrl" class="input" value="/cart/pay">

            <input type="hidden" name="Amt" id="Amt" value="<?= $총결제금액 ?>">
            <input type="hidden" name="Moid" value="<?= $MOID ?>">
            <input type="hidden" name="Mid" value="<?= MID ?>">
            <input type="hidden" name="BuyerName" value="<?= $login_user['name'] ?>">
            <input type="hidden" name="BuyerTel" value="<?= $login_user['phone'] ?>">
            <input type="hidden" name="BuyerEmail" value="<?= $login_user['email'] ?>">
            <input type="hidden" name="EdiDate" value="<?= $tday ?>">
            <input type="hidden" name="EncryptData" id="EncryptData" value="<?= $EncryptData ?>">
            <input type="hidden" name="MallIp" value="<?= $_SERVER['REMOTE_ADDR'] ?>">


            <?
            if (IS_MOBILE) {
            ?>
                <input type="hidden" name="MallReserved" value="" />
            <?
            }
            ?>

        </form>
    </div>
</div>

<script src="<?= SMARTRO_SCRIPT ?>/asset/js/SmartroPAY-1.0.min.js?version=<?= $tday ?>"></script>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    $("main").addClass("!bg-gray-100");

    var EncryptData = "<?= $EncryptData ?>";

    function handle_receiver_phone_change(e) {
        let input = e.target.value.replace(/[^0-9]/g, ''); // 숫자만 남김

        if (input.length <= 3) {
            e.target.value = input;
        } else if (input.length <= 7) {
            e.target.value = input.replace(/(\d{3})(\d{1,4})/, '$1-$2');
        } else {
            e.target.value = input.replace(/(\d{3})(\d{4})(\d{1,4})/, '$1-$2-$3');
        }

        const value = e.target.value;

        const buyerTel = $("input[name='BuyerTel']");

        if (buyerTel.val() == '') {
            $("input[name='BuyerTel']").val(value);
        }
    }

    function remove_cart(event, id) {
        const vsc = $("body").data("vsc");

        if (!vsc) {
            alert("로그인 후 이용해주세요");
            return;
        }

        const cart_key = `cart_${vsc}`;

        // 1. 기존 cart 쿠키 가져오기
        const existing = getCookie(cart_key);

        // 2. 기존 cart cookie JSON parse   
        let cart = existing ? JSON.parse(existing) : [];

        // 3. 해당 상품 제거
        cart = cart.filter(item => item.product_id != id);

        if (cart.length === 0) {
            // 장바구니가 비어있으면 쿠키 삭제
            deleteCookie(cart_key);
            location.reload();
            return;
        }

        const target = $(event.target);

        // 4. 가격 리셋
        const price = target.closest('.cart-item-box').find('.price');
        const qunainty = target.closest('.cart-item-box').find('input[name^="quantity"]');
        const amount = parseInt(price.val()) * parseInt(qunainty.val());

        const ori_amount = parseInt($("#product_amount").text().replace(/[^0-9]/g, ''));
        const update_amount = ori_amount - amount;

        $("#product_amount").text(`${update_amount.toLocaleString()}원`); // 총 금액 업데이트

        // 5. 배송비 업데이틒 필요
        const zonecode = $("input[name='zipcode']").val() || 0;
        const 배송비 = 배송비측정(zonecode, update_amount);
        const option1_fee = parseInt($("#option_select").val()) || 0;

        // 배송비 표시
        $("#shipping_fee").text(배송비 ? `${배송비.toLocaleString()}원` : "무료");
        $("input[name='shipping_fee']").val(배송비);

        // 총 결제 금액 업데이트
        const total_amount = update_amount + parseInt(배송비) + option1_fee;
        스마트로결제금액수정(total_amount, '<?= $tday ?>');
        $("#total_amount").text(`${total_amount.toLocaleString()}원`);

        // 5. 쿠키에 다시 저장
        target.closest('.cart-item-box').remove();
        $("#cartBadge").text(cart.length);
        setCookie(cart_key, JSON.stringify(cart));
    }

    function handle_quan(event, type) {
        const quan = $(event.target).siblings('input')
        let count = parseInt(quan.val());

        if (type == 'up') {

            count++;

        } else {

            if (count <= 1) {
                return; // 최소 수량이 1이므로 감소하지 않음
            }

            count--;

        }

        quan.val(count);

        const 옵션1비용 = parseInt($("#option1_quantity").val()) * 1000;
        const price_all = $(".price");
        let product_amount = 0;

        price_all.each(function(index) {
            const price = parseInt($(this).val());
            const quantity = $(this).closest('.cart-item-box').find('input[name^="quantity"]').val();

            product_amount += (price * quantity);
        });

        // 5. 배송비 업데이틒 필요
        const zonecode = $("input[name='zipcode']").val() || 0;
        const 배송비 = 배송비측정(zonecode, product_amount);

        const total_amount = product_amount + parseInt(배송비) + 옵션1비용;

        // 배송비 표시
        $("#shipping_fee").text(배송비 ? `${배송비.toLocaleString()}원` : "무료");
        $("input[name='shipping_fee']").val(배송비);
        $("#product_amount").text(`${product_amount.toLocaleString()}원`); // 총 금액 업데이트
        $("#total_amount").text(`${total_amount.toLocaleString()}원`); // 총 금액 업데이트
        스마트로결제금액수정(total_amount, '<?= $tday ?>');
    }


    function handle_quan1(event, type) {
        const quan = $(event.target).siblings('input')
        let count = parseInt(quan.val());

        if (type == 'up') {

            count++;

            $("#option1").css("display", "flex");

        } else {

            if (count == 0) {

                $("#option1").css("display", "none");

            } else {

                if (count == 1) {
                    $("#option1").css("display", "none");
                }

                count--;
            }

        }

        quan.val(count);
        const 옵션1비용 = 1000 * count;

        const price_all = $(".price");
        let amount = 0;
        price_all.each(function(index) {
            const price = parseInt($(this).val());
            const quantity = $(this).closest('.cart-item-box').find('input[name^="quantity"]').val();
            amount += (price * quantity);
        });

        // 5. 배송비 업데이틒 필요
        const zonecode = $("input[name='zipcode']").val() || 0;
        const 배송비 = 배송비측정(zonecode, amount + 옵션1비용);

        const total_amount = amount + parseInt(배송비) + 옵션1비용;

        // 배송비 표시
        $("#shipping_fee").text(배송비 ? `${배송비.toLocaleString()}원` : "무료");
        $("input[name='shipping_fee']").val(배송비);

        $("#total_amount").text(`${total_amount.toLocaleString()}원`); // 총 금액 업데이트
        $("#option1_fee").text(`${옵션1비용.toLocaleString()}원`);
        스마트로결제금액수정(total_amount, '<?= $tday ?>');
    }

    function handle_payment_method(e) {

        const target = $(e.target);
        const paymentMethod = target.val();

        // 결제수단에 따라 추가 로직 처리
        if (paymentMethod === '무통장입금') {

            $("#pay_btn").attr("onclick", "goPay2(event);");

        } else if (paymentMethod === '카드') {

            $("#pay_btn").attr("onclick", "goPay(event);");
        }
    }

    function basePayValid(e) {

        const target = $(e.target);
        target.prop('disabled', true);

        const order_frm = $("#order_frm")
        const approvalForm = $("#approvalForm");

        const agree = $(".agree");

        const zipcode = $("input[name='zipcode']").val();
        const address = $("input[name='address']").val();
        const address_detail = $("input[name='address_detail']").val();

        const receiver_name = $("input[name='receiver_name']").val();
        const receiver_phone = $("input[name='receiver_phone']").val();

        if (!receiver_name) {
            alert("수령인 이름을 입력해 주세요.");
            $("input[name='receiver_name']").focus();
            target.prop('disabled', false);
            return;
        }

        if (!receiver_phone) {
            alert("수령인 연락처를 입력해 주세요.");
            $("input[name='receiver_phone']").focus();
            target.prop('disabled', false);
            return;
        }

        if (!zipcode || !address) {
            alert("배송 정보를 입력해 주세요.");
            target.prop('disabled', false);
            return;
        }

        if (!address_detail) {
            alert("상세주소를 입력해 주세요.");
            $("input[name='address_detail']").focus();
            target.prop('disabled', false);
            return;
        }

        if (!agree.is(":checked")) {
            alert("구매조건 확인 및 결제진행에 동의해 주세요.");
            target.prop('disabled', false);
            return;
        }

        return true;
    }

    function handle_memo(e) {

        const value = $(e.target).val();

        if (value == '직접입력') {

            $(e.target).siblings('input').show();
        } else {
            $(e.target).siblings('input').hide();
        }

    }

    // ^ 결제 요청 함수 (카드결제)
    function goPay(e) {

        const target = $(e.target);

        if (!basePayValid(e)) {
            return;
        }

        const order_frm = $("#order_frm")
        const approvalForm = $("#approvalForm");

        approvalForm.find("input.dynamic").remove();
        order_frm.serializeArray().forEach(({
            name,
            value
        }) => {
            $("<input>")
                .attr({
                    type: "hidden",
                    name,
                    value
                })
                .addClass("dynamic") // 삭제 구분용
                .appendTo(approvalForm);
        });

        try {

            smartropay.init({
                mode: "<?= SMARTRO_MODE ?>",
                // actionUri: '/ssp/reqPay.do'
            });

            if (isMobile()) {

                const test = order_frm.serializeArray();
                const dataObj = {};

                test.forEach(({
                    name,
                    value
                }) => {
                    const cleanName = name.replace(/\[\]$/, ''); // [] 제거

                    if (dataObj.hasOwnProperty(cleanName)) {
                        if (Array.isArray(dataObj[cleanName])) {
                            dataObj[cleanName].push(value);
                        } else {
                            dataObj[cleanName] = [dataObj[cleanName], value];
                        }
                    } else {
                        dataObj[cleanName] = value;
                    }
                });


                const mobile_pay_key = encodeURIComponent(JSON.stringify(dataObj));

                $.ajax({
                    type: "POST",
                    url: "/cart/set_mobile_product",
                    data: {
                        mobile_pay_key: mobile_pay_key,
                        user_id: '<?= $login_user['id'] ?>'
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.ok) {
                            $("input[name='ReturnUrl']").val(location.origin + `/cart/pay`);
                            $("input[name='MallReserved']").val('<?= $login_user['id'] ?>')

                            // smartropay 결제 요청 함수, PC 연동시 아래와 같이 smartropay.payment 함수를 구현합니다.
                            smartropay.payment({
                                FormId: 'tranMgr',
                            });
                        } else {
                            alert(response.msg || "결제셋팅에 실패했습니다. 다시 시도해 주세요.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("오류 발생:", error);
                        alert("결제 요청 중 오류가 발생했습니다. 다시 시도해 주세요.");
                        return;
                    }
                });

            } else {

                // smartropay 결제 요청 함수, PC 연동시 아래와 같이 smartropay.payment 함수를 구현합니다.
                smartropay.payment({
                    FormId: 'tranMgr',
                    Callback: function(res) {
                        var approvalForm = document.approvalForm;
                        approvalForm.Tid.value = res.Tid;
                        approvalForm.TrAuthKey.value = res.TrAuthKey;
                        approvalForm.action = '/cart/pay';
                        approvalForm.submit();
                    }
                });
            }
        } catch (e) {
            target.prop('disabled', false);
            console.error("오류 발생:", e);
            alert("결제 요청 중 오류가 발생했습니다. 다시 시도해 주세요.");
            return;
        } finally {
            target.prop('disabled', false);
        }

    };

    function handle_option(e) {

        const 옵션비용 = parseInt($(e.target).val());

        if (옵션비용 > 0) {
            $("#option1").css("display", "flex");
        } else {
            $("#option1").css("display", "none");
        }

        const zonecode = $("input[name='zipcode']").val() || 0;
        const product_amount = parseInt($("#product_amount").text().replace(/[^0-9]/g, ''));
        const 배송비 = 배송비측정(zonecode, product_amount + 옵션비용);

        // 배송비 표시
        $("#shipping_fee").text(배송비 ? `${배송비.toLocaleString()}원` : "무료");
        $("input[name='shipping_fee']").val(배송비);
        $("input[name='option1_fee']").val(옵션비용);

        // 총 결제 금액 업데이트
        const total_amount = product_amount + parseInt(배송비) + 옵션비용;
        $("#total_amount").text(`${total_amount.toLocaleString()}원`);
        스마트로결제금액수정(total_amount, '<?= $tday ?>');

    }

    // ^ 결제 요청 함수 (무통장입금)
    function goPay2(e) {

        if (!basePayValid(e)) {
            return;
        }

        $("#order_frm").attr("action", "/cart/pay_bank");
        $("#order_frm").submit();
    }

    function handle_payment(e) {

        const target = $(e.target);

        // TODO: 결제 로직 구현

        fadeOutButton('/cart/payment');
    }

    function open_kakao_post_pop() {
        new daum.Postcode({
            oncomplete: function(res) {

                if (res.address) {

                    $("input[name='address']").val(res.address);
                }

                if (res.zonecode) {
                    const product_amount = parseInt($("#product_amount").text().replace(/[^0-9]/g, ''));
                    const 배송비 = 배송비측정(res.zonecode, product_amount);
                    const option1_quantity = parseInt($("#option1_quantity").val()) || 0;
                    const option1_fee = option1_quantity * 1000;

                    // 배송비 표시
                    $("#shipping_fee").text(배송비 ? `${배송비.toLocaleString()}원` : "무료");
                    $("input[name='shipping_fee']").val(배송비);

                    // 총 결제 금액 업데이트
                    const total_amount = product_amount + parseInt(배송비) + option1_fee;
                    $("#total_amount").text(`${total_amount.toLocaleString()}원`);
                    스마트로결제금액수정(total_amount, '<?= $tday ?>');

                    $("input[name='zipcode']").val(res.zonecode);
                }

                // // 우편번호
                // document.getElementById('postcode').value = data.zonecode;
                // // 도로명 주소
                // document.getElementById('roadAddress').value = data.roadAddress;
                // // 지번 주소
                // document.getElementById('jibunAddress').value = data.jibunAddress;
            }
        }).open();
    }
</script>