<div class="max-w-5xl flex flex-col !mx-auto !p-6 !space-y-6">
    <h1 class="!text-2xl font-semibold text-center !mb-6">결제하기</h1>

    <form id="order_frm" method="POST" class="lg:flex-row flex flex-col w-full gap-6">
        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
        <input type="hidden" name="quantity" value="<?= $quantity ?>">
        <input type="hidden" name="price" value="<?= $product['price'] ?>">
        <input type="hidden" name="amount" value="<?= $product['price'] * $quantity ?>">
        <input type="hidden" name="shipping_fee" value="<?= $배송비 ?>">
        <input type="hidden" name="user_id" value="<?= $login_user['id'] ?>">
        <input type="hidden" name="option1_fee" value="0">

        <div class="lg:w-1/2 w-full flex flex-col gap-6">
            <div class="bg-white w-full rounded !h-fit !p-6 !space-y-4">
                <h2 class="!text-lg font-semibold">주문 상품 정보</h2>
                <div class="flex gap-4">
                    <img src="<?= $product['image_url'] ?>" alt="상품 이미지" class="w-24 h-24 object-cover border rounded" />
                    <div class="flex-1">
                        <p class="font-semibold">
                            <?= $product['name'] ?>
                        </p>
                        <?
                        if (!empty($product['discount_price'])) {
                        ?>
                            <span class="text-lg font-bold text-gray-800 !mr-1"><?= number_format($product['discount_price']) ?>원</span>
                            <span class="line-through text-sm text-gray-400"><?= number_format($product['ori_price']) ?>원</span>
                        <?
                        } else {
                        ?>
                            <span class="text-lg font-bold text-gray-800"><?= number_format($product['price']) ?>원</span>
                        <?
                        }
                        ?>
                        <div class="flex gap-2 font-medium !mt-1">
                            수량: <?= $quantity ?>개
                        </div>
                        <p class="shipping_fee !text-sm">배송비 <?= $배송비 ? number_format($배송비) . '원' : '무료' ?></p>
                    </div>
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
                        <input type="text" name="receiver_name" value="<?= $receiver_name ?>" placeholder="수령인" class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                        <input onchange="handle_receiver_phone_change(event);" type="text" name="receiver_phone" value="<?= $receiver_phone ?>" placeholder="수령인 연락처" class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                    </div>

                    <div class="!space-y-1 text-sm text-gray-800">
                        <div class="flex items-center gap-2">
                            <input type="number" name="zipcode" value="<?= $zipcode ? $zipcode : '' ?>" readonly placeholder="우편번호" class="w-fit max-w-[145px] border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                            <button onclick="open_kakao_post_pop(event);" type="button" class="px-4 py-1.5 border rounded-full text-sm text-[#0abab5] border-[#0abab5] hover:bg-[#0abab5]/10 transition">
                                주소찾기
                            </button>
                        </div>
                        <input type="address" name="address" value="<?= $address ?>" readonly placeholder="주소" class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                        <input type="text" name="address_detail" value="<?= $address_detail ?>" placeholder="상세주소" class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                    </div>

                    <div>
                        <label for="deliveryMemo" class="text-sm font-medium text-gray-700 block">배송메모</label>
                        <select onchange="handle_memo(event);" name="memo" id="deliveryMemo" class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-gray-200 focus:outline-none appearance-none bg-white">
                            <option value="배송 전에 미리 연락 바랍니다.">배송 전에 미리 연락 바랍니다.</option>
                            <option value="부재시 경비실에 맡겨주세요.">부재시 경비실에 맡겨주세요.</option>
                            <option value="부재시 전화나 문자를 남겨주세요.">부재시 전화나 문자를 남겨주세요.</option>
                            <option value="직접입력">직접입력</option>
                        </select>
                        <input class="w-full mt-2 border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none hidden" type="text" id="deliveryMemoInput" name="memo" placeholder="직접입력 시 여기에 작성해 주세요.">
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:w-1/2 w-full flex flex-col gap-6 sticky top-0">

            <!-- <div class="bg-white w-full h-fit !p-6 !space-y-4">
                <h2 class="!text-lg font-semibold mb-4">포인트 사용</h2>
                <div class="flex flex-col gap-2">
                    <div class="flex items-center !gap-2">
                        <input
                            type="text"
                            value="0"
                            class="flex-1 px-3 py-2 border border-gray-200 rounded-l bg-gray-50 text-right text-sm text-gray-800 focus:outline-none" />
                        <button type="button" class="px-4 py-2 btn-sm text-sm text-gray-700 rounded-r">
                            전액사용
                        </button>
                    </div>

                    <p class="!text-sm text-gray-500 mt-1">
                        사용 가능 포인트 <span class="font-semibold text-gray-800">0</span> / 보유 포인트 <span class="font-semibold text-gray-800">0</span>
                    </p>
                </div>
            </div> -->

            <div class="bg-white h-fit w-full !p-6 !space-y-4">
                <h2 class="!text-lg font-semibold mb-4">추가 옵션</h2>
                <div class="flex flex-col gap-2">
                    <div class="flex !items-center justify-between">
                        <p class="!mt-1">
                            선물용 봉투 (+1,000원)
                        </p>
                        <div class="flex items-center border border-gray-300 rounded-sm overflow-hidden w-fit">
                            <button type="button" onclick="handle_quan1(event,'down');" class=" border-1 h-[30px] border-gray-300 px-3 py-1 text-gray-600 hover:bg-gray-100">-</button>
                            <input type="text" min="1" name="option1_quantity" id="option1_quantity" value="0" readonly="" class="w-12 h-[30px] text-center py-1 text-sm border-gray-300 outline-none">
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
                            <?= number_format($product['ori_price'] * $quantity) ?>원
                        </span>
                    </div>

                    <div class="flex justify-between mb-1">
                        <span class="text-gray-500">배송비</span>
                        <span id="shipping_fee" class="shipping_fee">
                            <?= $배송비 ? number_format($배송비) . '원' : '무료' ?>
                        </span>
                    </div>

                    <div class="flex justify-between mb-1 hidden" id="option1">
                        <span class="text-gray-500">선물용 봉투</span>
                        <span id="option1_fee"><?= 1000 > 0 ? number_format(1000) . '원' : '무료' ?></span>
                    </div>


                    <?
                    if (!empty($login_user['agent'])) {
                        // 매장 할인 적용
                        if (!empty($product['discount_price'])) {

                            $ori_price = $product['ori_price'] * $quantity;
                            $discount_price = $product['discount_price'] * $quantity;
                            $minus_price = $ori_price - $discount_price;
                    ?>
                            <div class="flex justify-between mb-3">
                                <span class="text-gray-500">매장 할인</span>
                                <span class="text-red-500">-<?= number_format($minus_price) ?>원</span>
                            </div>
                    <?
                        }
                    }
                    ?>

                    <hr class="border-t border-gray-200 mb-3">

                    <div class="flex justify-between items-center font-bold text-base mb-2">
                        <span>총 주문금액</span>
                        <span id="total_amount">
                            <?= number_format(($product['price'] * $quantity) + $배송비) ?>원
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
                    <input type="checkbox" class="agree accent-gray-800" />
                    <span>구매조건 확인 및 결제진행에 동의</span>
                </label>

                <div class="flex gap-4">
                    <button id="pay_btn" onclick="goPay(event);" type="button" class="w-full btn-primary-sm !py-3 rounded hover:bg-gray-700 transition">결제하기</button>
                    <!-- <button onclick="goPay2(event);" type="button" class="w-full btn-primary-sm !py-3 rounded hover:bg-gray-700 transition">결제하기</button> -->
                </div>
            </div>

        </div>
    </form>


    <form id="tranMgr" name="tranMgr" class="hidden" method="post">

        <?
        $total_amount = ($product['price'] * $quantity) + $배송비;
        $EncryptData = base64_encode(hash('sha256', $tday . MID . $total_amount . MKEY, true));
        $expire_time = date('YmdHis', strtotime('+1 day'));

        ?>

        <!-- <input type="text" name="PayMethod" value="CARD" />

        <input type="text" name="GoodsCnt" value="<?= $quantity ?>" />
        <input type="text" name="GoodsName" value="<?= $product['name'] ?>" />
        <input type="text" name="Amt" value="<?= $product['price'] * $quantity ?>" />
        <input type="text" name="Moid" value="Moid_<?= $product['id'] ?>_<?= $tday ?>" />
        <input type="text" name="Mid" value="<?= MID ?>" />
        <input type="text" name="BuyerName" value="<?= $login_user['name'] ?>" />
        <input type="text" name="BuyerTel" value="<?= $login_user['phone'] ?>" />
        <input type="text" name="BuyerEmail" value="<?= $login_user['email'] ?>" />
        <input type="text" name="VbankExpDate" value="<?= $expire_time ?>" />
        <input type="text" name="EncryptData" value="<?= $EncryptData ?>" />
        <input type="text" name="GoodsCl" value="0" />
        <input type="text" name="EdiDate" value="<?= $tday ?>" />
        <input type="text" name="EncodingType" maxlength="14" valueㄴ="" placeholder="utf-8 or euc-kr" />

        <input type="text" name="MallUserId" maxlength="20" value="<?= $login_user['id'] ?>" placeholder="" />
        <input type="text" name="SspMallId" maxlength="20" value="SMTSSPAY0p" placeholder="테스트용 SspMallId" />

        <input type="text" name="TaxAmt" value="" />
        <input type="text" name="TaxFreeAmt" value="" />
        <input type="text" name="VatAmt" value=""> -->

        <input type="hidden" name="PayMethod" value="CARD">
        <input type="hidden" name="GoodsCnt" value="<?= $quantity ?>">
        <input type="hidden" name="GoodsName" value="<?= $product['name'] ?>">
        <input type="hidden" name="ReturnUrl" class="input" value="/product/pay">
        <input type="hidden" name="Amt" id="Amt" value="<?= $total_amount ?>">
        <input type="hidden" name="Moid" value="Moid_<?= $product['id'] ?>">
        <input type="hidden" name="Mid" value="<?= MID ?>">
        <input type="hidden" name="BuyerName" value="<?= $login_user['name'] ?>">
        <input type="hidden" name="BuyerTel" value="<?= $login_user['phone'] ?>">
        <input type="hidden" name="BuyerEmail" value="<?= $login_user['email'] ?>">
        <input type="hidden" name="EdiDate" value="<?= $tday ?>">
        <input type="hidden" name="EncryptData" id="EncryptData" value="<?= $EncryptData ?>">
        <input type="hidden" name="MallIp" value="<?= $_SERVER['REMOTE_ADDR'] ?>">
        <input type="hidden" name="user_id" value="<?= $login_user['id'] ?>">
        <input type="hidden" name="GoodsCl" value="1">

        <?
        if (IS_MOBILE) {
        ?>
            <input type="hidden" id="StopUrl" name="StopUrl" value="<?= $_SERVER['REQUEST_URI'] ?>">
            <input type="hidden" name="MallReserved" value="" />
        <?
        }
        ?>
        <!-- <input type="hidden" name="NonUi" value="">
            <input type="hidden" name="PayType" value="">
            <input type="hidden" name="FnCd" value="">
            <input type="hidden" name="BankCode" value="">
            <input type="hidden" name="CardQuota" value=""> -->


        <td colspan="4" class="text-center">
            <button type="button" class="btn btn-primary" onclick="goPay(event);">결제하기</button>
            <!-- <button type="button" class="btn btn-primary" onclick="goPay2();">결제하기</button> -->
        </td>
    </form>

    <!-- PC 연동의 경우에만 아래 승인폼이 필요합니다. (Mobile은 제외) -->
    <form id="approvalForm" name="approvalForm" method="post">
        <input type="hidden" name="MallReserved" value="" />
        <?
        if (!IS_MOBILE) {
        ?>
            <input type="hidden" id="Tid" name="Tid" />
            <input type="hidden" id="TrAuthKey" name="TrAuthKey" />
        <?
        }
        ?>
    </form>
</div>


<script src="<?= SMARTRO_SCRIPT ?>/asset/js/SmartroPAY-1.0.min.js?version=<?= $tday ?>"></script>
<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    $("main").addClass("!bg-gray-100");

    const stopUrlInput = document.getElementById('StopUrl');
    if (stopUrlInput) {
        stopUrlInput.value = window.location.href;
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


    function handle_option(e) {

        const 옵션비용 = parseInt($(e.target).val());

        if (옵션비용 > 0) {
            $("#option1").css("display", "flex");
        } else {
            $("#option1").css("display", "none");
        }

        const zonecode = $("input[name='zipcode']").val() || 0;
        const product_amount = parseInt($("input[name=amount]").val().replace(/[^0-9]/g, ''));
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

    function goPay2(e) {

        if (!basePayValid(e)) {
            return;
        }

        $("#order_frm").attr("action", "/product/pay_bank");
        $("#order_frm").submit();
    }

    function basePayValid(e) {

        const target = $(e.target);
        target.prop('disabled', true);


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

    function goPay(e) {

        if (!basePayValid(e)) {
            return;
        }

        const order_frm = $("#order_frm")
        const approvalForm = $("#approvalForm");

        // order_frm의 input들을 approvalForm에 hidden input으로 추가
        order_frm.serializeArray().forEach(({
            name,
            value
        }) => {
            // 같은 name의 hidden input이 이미 존재할 경우 중복 방지
            if (approvalForm.find(`[name="${name}"]`).length == 0) {
                $("<input>")
                    .attr({
                        type: "hidden",
                        name: name,
                        value: value
                    })
                    .appendTo(approvalForm);
            }
        });

        try {

            smartropay.init({
                mode: "<?= SMARTRO_MODE ?>",
                // actionUri: '/ssp/reqPay.do'
            });

            if (isMobile()) {

                const data = $("#approvalForm").serializeArray();
                const dataObj = {};

                data.forEach(({
                    name,
                    value
                }) => {
                    dataObj[name] = value;
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
                            $("input[name='ReturnUrl']").val(location.origin + `/product/pay`);
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
                        approvalForm.action = '/product/pay';
                        approvalForm.submit();
                    }
                });
            }
        } catch (e) {
            console.error("오류 발생:", e);
            alert("결제 요청 중 오류가 발생했습니다. 다시 시도해 주세요.");
            return;
        } finally {

            const target = $(e.target);
            target.prop('disabled', false);
        }


    };

    function handle_memo(e) {

        const value = $(e.target).val();

        if (value == '직접입력') {

            $(e.target).siblings('input').show();
        } else {
            $(e.target).siblings('input').hide();
        }

    }

    function handle_payment(e) {

        const target = $(e.target);

        // TODO: 결제 로직 구현

        fadeOutButton('/product/payment');
    }

    function open_kakao_post_pop() {
        new daum.Postcode({
            oncomplete: function(res) {

                if (res.address) {

                    $("input[name='address']").val(res.address);
                }

                if (res.zonecode) {
                    const 배송비 = 배송비측정(res.zonecode, $("input[name='amount']").val());
                    const option1_quantity = parseInt($("#option1_quantity").val()) || 0;
                    const option1_fee = option1_quantity * 1000;

                    // 배송비 표시
                    $(".shipping_fee").text(배송비 ? `${배송비.toLocaleString()}원` : "무료");
                    $("input[name='shipping_fee']").val(배송비);

                    // 총 결제 금액 업데이트
                    const total_amount = parseInt($("input[name=amount]").val()) + parseInt(배송비) + parseInt(option1_fee);
                    console.log("🚀 Debug: ~ handle_option ~ total_amount:", total_amount)

                    스마트로결제금액수정(total_amount, '<?= $tday ?>');
                    $("#total_amount").text(`${total_amount.toLocaleString()}원`);

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
        const product_amount = parseInt($("input[name=amount]").val().replace(/[^0-9]/g, ''));

        // 5. 배송비 업데이틒 필요
        const zonecode = $("input[name='zipcode']").val() || 0;
        const 배송비 = 배송비측정(zonecode, product_amount + 옵션1비용);
        console.log("🚀 Debug: ~ handle_quan1 ~ 배송비:", 배송비)
        const total_amount = product_amount + parseInt(배송비) + 옵션1비용;

        // 배송비 표시
        $("#shipping_fee").text(배송비 ? `${배송비.toLocaleString()}원` : "무료");
        $("input[name='shipping_fee']").val(배송비);

        $("#total_amount").text(`${total_amount.toLocaleString()}원`); // 총 금액 업데이트
        $("#option1_fee").text(`${옵션1비용.toLocaleString()}원`);
        스마트로결제금액수정(total_amount, '<?= $tday ?>');
    }
</script>