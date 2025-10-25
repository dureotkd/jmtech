<div class="min-h-screen lg:!bg-gray-100 flex flex-col !mx-auto !space-y-6 w-full h-full !pb-18">
    <div class="w-full lg:!my-12 lg:max-w-3xl flex flex-col !bg-[#fff] !mx-auto !p-6 !space-y-6">
        <h1 class="!text-2xl font-semibold text-center !mb-6">결제완료</h1>

        <p class="text-center mb-6 text-gray-500">주문배송은 마이페이지에서 조회 가능합니다</p>

        <div class=" mb-3 rounded-lg !divide-y">
            <div class="!p-4 border-b !border-gray-200">
                <p class="font-medium text-gray-700">배송 정보</p>
                <p class="mt-2 text-gray-600 leading-relaxed">
                    <?= $address ?><br />
                    <?= $address_detail ?><br />
                    (<?= $zipcode ?>)
                </p>
            </div>
            <div class="!p-4 flex justify-between border-b !border-gray-200">
                <span class="text-gray-700 font-medium">배송 방법</span>
                <span class="text-gray-600">택배</span>
            </div>
            <div class="!p-4 flex justify-between border-b !border-gray-200">
                <span class="text-gray-700 font-medium">주문번호</span>
                <span class="text-gray-600">
                    <?= $order_item['number'] ?>
                </span>
            </div>
            <div class="!p-4 flex justify-between border-b !border-gray-200">
                <span class="text-gray-700 font-medium">결제정보</span>
                <span class="text-gray-600">
                    <?= unserialize(PAY_METHOD_CODE)[$smartro_payment_log['PayMethod']] ?? '' ?>
                </span>
            </div>
            <div class="!p-4 flex justify-between">
                <span class="text-gray-700 font-medium">결제금액</span>
                <span class="text-lg font-bold text-gray-900">
                    <?= number_format($order_item['total_amount'] + $order_item['shipping_fee'] + $order_item['option1_fee']) ?> 원
                </span>
            </div>
        </div>

        <div class="lg:!mt-2 flex justify-between">
            <button onclick="fadeOutButton('/')" class="flex-1 !mr-2 !py-2 rounded bg-gray-200 text-gray-700">홈으로</button>
            <button onclick="fadeOutButton('/my')" class="flex-1 !ml-2 !py-2 rounded btn-primary-sm text-white">마이페이지</button>
        </div>
    </div>
</div>

<!-- <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script> -->
<script>
    <? if (!$already_paid) {
    ?>
        // confetti({
        //     particleCount: 150,
        //     spread: 70,
        //     origin: {
        //         y: 0.6
        //     }
        // });
    <?
    } ?>
</script>