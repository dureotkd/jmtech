 <?
    $ORDER_STATUS = unserialize(ORDER_STATUS);

    ?>

 <section class="lg:!px-0 !px-6">

     <div class="w-full flex gap-2 !items-center z-10">
         <button type="button" onclick="page_back();">
             <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left-icon lucide-arrow-left">
                 <path d="m12 19-7-7 7-7" />
                 <path d="M19 12H5" />
             </svg>
         </button>
         <h2 class="!text-lg font-bold !my-4">
             주문 상세조회
         </h2>
     </div>

     <div class="flex flex-col">
         <!-- 상품 카드 -->
         <div class="border border-gray-200 rounded-md p-4 flex gap-4 bg-white">

             <!-- 상품 정보 -->
             <div class="w-full border border-gray-200 rounded-md p-4 flex gap-4 bg-white">
                 <!-- 이미지 -->
                 <img class="h-42 w-42 object-cover rounded-md" src="<?= $order_item['image_url'] ?>" alt="<?= $order_item['name'] ?>" />

                 <!-- 상품 정보 -->
                 <div class="relative flex flex-1 flex-col justify-between">
                     <div class="!space-y-0.5">

                         <div class="flex flex-col text-gray-500 text-sm gap-1">

                             <?= status_badge($order_item['status']) ?>

                             <span class="ml-2 color-sm">주문번호 : <?= $order_item['number'] ?></span>
                         </div>

                         <div class="font-semibold text-gray-800">
                             <?= $order_item['name'] ?>
                         </div>
                         <div class="font-medium text-gray-800">
                             <?= $order_item['quantity'] ?>개
                             <?= number_format($order_item['total_amount']) ?>원 </div>
                     </div>

                     <div class="lg:!top-0 lg:!right-0 !absolute bottom-0 flex gap-2 items-start text-sm">
                         <button onclick="re_order(event,'<?= $order_item['product_id'] ?>','<?= $order_item['quantity'] ?>','<?= $order_item['order_detail_id'] ?>');" class="bg-sm text-white border !text-sm border-gray-300 px-4 py-1 rounded-full hover:bg-gray-100 transition">
                             재구매
                         </button>
                         <!-- 상태 및 버튼 -->
                         <?
                            if ($order_item['status'] == 'pending' || $order_item['status'] == 'paid') {
                            ?>
                             <button onclick="cancel_order(event,'<?= $order_item['order_item_id'] ?>');" class="border border-gray-300 px-4 py-1 rounded-full hover:bg-gray-100 transition">취소</button>
                         <?
                            }
                            ?>
                     </div>
                 </div>
             </div>

         </div>


         <!-- 
         <div class="!py-4 flex w-full gap-2">
             <?
                if ($order_item['status'] == 'pending' || $order_item['status'] == 'paid') {
                ?>
                 <div class="flex-1/2">
                     <button onclick="fadeOutButton('/product/order?product_id=<?= $order_item['id'] ?>&quantity=<?= $order_item['quantity'] ?>')" class="w-full bg-sm text-white border !text-base border-gray-300 px-4 py-1 rounded-full hover:bg-gray-100 transition">
                         재구매
                     </button>
                 </div>
             <?
                }
                ?>

             <div class="flex-1/2">
                 <button onclick="cancel_order(event);" class="w-full border !text-base border-gray-300 px-4 py-1 rounded-full hover:bg-gray-100 transition">취소</button>
             </div>
         </div> -->

         <div class="rounded-lg !divide-y">

             <div class="!py-4 border-b !text-sm !border-gray-200">
                 <p class="mt-2 text-gray-600 leading-relaxed">
                     <?= $order_item['address'] ?><br />
                     <?= $order_item['address_detail'] ?> (<?= $order_item['zipcode'] ?>)<br />
                     <?= $order_item['receiver_name'] ?> <?= $order_item['receiver_phone'] ?>
                     <?= $order_item['memo'] ?>
                 </p>
             </div>

             <div class="!py-4 flex justify-between border-b !border-gray-200">
                 <span class="text-gray-700 font-medium">배송 방법</span>
                 <span class="text-gray-600">택배</span>
             </div>
             <div class="!py-4 flex justify-between border-b !border-gray-200">
                 <span class="text-gray-700 font-medium">주문번호</span>
                 <span class="text-gray-600">
                     <?= $order_item['number'] ?>
                 </span>
             </div>
             <div class="!py-4 flex justify-between border-b !border-gray-200">
                 <span class="text-gray-700 font-medium">결제정보</span>
                 <span class="text-gray-600">
                     <?= $order_item['payment_method'] ?>
                 </span>
             </div>

             <div class="!py-4 flex justify-between border-b !border-gray-200">
                 <span class="text-gray-700 font-medium">주문자명</span>
                 <span class="text-lg font-bold text-gray-900"><?= $order_item['buyer_name'] ?></span>
             </div>

             <?
                if ($order_item['payment_method'] == '무통장입금' && $order_item['status'] == 'pending') {
                ?>
                 <div class="!py-4 flex justify-between border-b !border-gray-200">
                     <span class="text-gray-700 font-medium">계좌번호</span>
                     <span class="text-lg font-bold text-gray-900"><?= $site_meta_row['account'] ?> (주)제이엠테크</span>
                 </div>
             <?
                }
                ?>

             <?
                if ($order_item['status'] == 'paid') {
                ?>
                 <div class="!py-4 flex justify-between border-b !border-gray-200">
                     <span class="text-gray-700 font-medium">
                         결제일
                     </span>
                     <span class="text-lg font-bold text-gray-900">
                         <?= date('Y-m-d H:i', strtotime($order_item['paid_at'])) ?>
                     </span>
                 </div>
             <?
                }
                ?>

             <div class="!py-4 flex justify-between border-b !border-gray-200">
                 <span class="text-gray-700 font-medium">상품금액</span>
                 <span class="text-gray-600">
                     <?= number_format($order_item['total_amount']) ?> 원
                 </span>
             </div>

             <div class="!py-4 flex justify-between border-b !border-gray-200">
                 <span class="text-gray-700 font-medium">배송비</span>
                 <span class="text-gray-600">
                     <?
                        if ($order_item['shipping_fee'] > 0) {
                        ?>
                         <?= number_format($order_item['shipping_fee']) ?> 원
                     <?
                        } else {
                        ?>
                         무료
                     <?
                        }
                        ?>
                 </span>
             </div>

             <?
                if (!empty($order_item['option1_fee'])) {
                ?>
                 <div class="!py-4 flex justify-between border-b !border-gray-200">
                     <span class="text-gray-700 font-medium">선물용 봉투</span>
                     <span class="text-gray-600">
                         <?= number_format($order_item['option1_fee']) ?> 원
                     </span>
                 </div>
             <?
                }
                ?>

             <div class="!py-4 flex justify-between">
                 <span class="text-gray-700 font-medium">결제금액</span>
                 <span class="text-lg font-bold text-gray-900">
                     <?= number_format($order_item['total_amount'] + $order_item['shipping_fee'] + $order_item['option1_fee']) ?> 원
                 </span>
             </div>
         </div>
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