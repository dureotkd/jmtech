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
    <h2 class="!text-lg font-bold mb-6">상품관리</h2>

    <div class="overflow-x-auto !mt-8">

        <div class="w-full flex justify-between gap-2 !mb-2">
            <div class="">
                <button type="button" onclick="window.location.href = '/admin/product/create'" class="btn btn-soft">상품등록</button>
            </div>
        </div>
        <table class="table w-full border border-gray-200">
            <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                <tr>
                    <th class="w-[100px]">메인 이미지</th>
                    <th>상품명</th>
                    <th>판매가</th>
                    <th>매장 판매가</th>
                    <th>등록일</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">

                <?php
                if (!empty($product_list)) {
                    foreach ($product_list as $product) {
                ?>
                        <tr class="border-b">
                            <td>
                                <img src="<?= $product['image_url'] ?>" alt="상품 이미지" class="w-16 h-16 object-cover rounded">
                            </td>
                            <td>
                                <a href="/admin/product?id=<?= $product['id'] ?>" class="!text-blue-600 hover:underline">
                                    <?= $product['name'] ?>
                                </a>
                            </td>
                            <td>
                                <?= number_format($product['price']) ?>원
                            </td>
                            <td>
                                <?= number_format($product['only_admin_discount_price']) ?>원
                            </td>
                            <td>
                                2025-05-16<br />
                                <span class="text-xs text-gray-500">(13:43:24)</span>
                            </td>
                        </tr>

                        <!-- 리뷰 보기/숨기기 버튼 -->
                        <tr class="border-b">
                            <td colspan="5" class="pl-16">
                                <button type="button" class="text-blue-500 hover:underline toggle-qna" data-product-id="<?= $product['id'] ?>">QNA 보기</button>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td colspan="5" class="pl-16">
                                <button type="button" class="text-blue-500 hover:underline toggle-reviews" data-product-id="<?= $product['id'] ?>">리뷰 보기</button>
                            </td>
                        </tr>

                        <!-- 상품 리뷰 표시 (처음에는 숨김) -->
                        <?php if (!empty($product['qnas'])) { ?>
                            <tr class="qnas-<?= $product['id'] ?> hidden">
                                <td colspan="5" class="pl-16">
                                    <div class="!space-y-4">
                                        <?php foreach ($product['qnas'] as $qna) {

                                        ?>
                                            <div class="!p-4 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                                                <div class="flex items-center !space-x-3">
                                                    <span>
                                                        <?= $qna['status'] ?> / <?= $qna['is_secret'] ? '비밀글' : '공개' ?> / <?= $qna['writer_name'] ?> / <?= $qna['created_at'] ?>
                                                    </span>
                                                </div>
                                                <h2 class="!text-lg !font-bold">
                                                    <?= $qna['title'] ?>
                                                </h2>
                                                <p class="text-gray-700"><?= $qna['content'] ?></p>
                                                <textarea placeholder="답변을 남겨주세요" class="w-full !p-4 !border !border-gray-500" rows="5" name="answer" id=""><?= $qna['answer'] ?></textarea>
                                                <div class="">
                                                    <button type="button" onclick="update_answer(event,<?= $qna['id'] ?>);" class="btn-sm btn btn-success text-white">답변</button>
                                                    <button type="button" onclick="delete_qna(<?= $qna['id'] ?>);" class="btn-sm btn btn-error text-white">삭제</button>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>

                        <!-- 상품 리뷰 표시 (처음에는 숨김) -->
                        <?php if (!empty($product['reviews'])) { ?>
                            <tr class="reviews-<?= $product['id'] ?> hidden">
                                <td colspan="5" class="pl-16">
                                    <div class="!space-y-4">
                                        <?php foreach ($product['reviews'] as $review) {

                                            $image_urls = explode(',', $review['image_urls']);
                                        ?>
                                            <div class="!p-4 bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                                                <div class="flex items-center !space-x-4 gap-2">
                                                    <?
                                                    if (!empty($image_urls)) {
                                                        foreach ($image_urls as $image_url) {
                                                    ?>
                                                            <img class="w-32 h-32 !my-2" src="<?= $image_url ?>" alt="<?= $review['id'] ?>">
                                                    <?
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <div class="flex items-center !space-x-3">
                                                    <p class="font-semibold text-lg"><?= $review['user_login_id'] ?></p>
                                                    <p class="text-yellow-500 font-semibold"><?= str_repeat('★', $review['rating']) ?><?= str_repeat('☆', 5 - $review['rating']) ?></p>
                                                </div>
                                                <p class="!mt-2 text-gray-700"><?= nl2br(htmlspecialchars($review['content'])) ?></p>
                                                <p class="!mt-2 text-sm text-gray-500"><?= $review['created_at'] ?></p>

                                                <button type="button" onclick="delete_review(<?= $review['id'] ?>);" class="btn-sm btn btn-error text-white">삭제</button>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>


                <?php
                    }
                }
                ?>

            </tbody>
        </table>
    </div>

</div>


<script>
    // 리뷰 토글 기능 (jQuery 사용)
    $(document).ready(function() {
        $('.toggle-reviews').click(function() {
            var productId = $(this).data('product-id');
            var reviewRow = $('.reviews-' + productId);
            var buttonText = reviewRow.is(':visible') ? '리뷰 보기' : '리뷰 숨기기';

            // 리뷰 보이기/숨기기
            reviewRow.toggle(); // `toggle()` 메서드를 사용하여 보이기/숨기기
            $(this).text(buttonText); // 버튼 텍스트 변경
        });

        $('.toggle-qna').click(function() {
            var productId = $(this).data('product-id');
            var qnaRow = $('.qnas-' + productId);
            var buttonText = qnaRow.is(':visible') ? 'QNA 보기' : 'QNA 숨기기';

            // 리뷰 보이기/숨기기
            qnaRow.toggle(); // `toggle()` 메서드를 사용하여 보이기/숨기기
            $(this).text(buttonText); // 버튼 텍스트 변경
        });
    });

    function delete_review(id) {
        if (confirm('정말로 이 리뷰를 삭제하시겠습니까?')) {

            $.ajax({
                type: "POST",
                url: "/admin/review/delete_review",
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

    function delete_qna(id) {
        if (confirm('정말로 이 QNA를 삭제하시겠습니까?')) {

            $.ajax({
                type: "POST",
                url: "/admin/review/delete_qna",
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

    function update_answer(event, id) {
        const target = $(event.target);
        const answer = target.parent().siblings('textarea[name="answer"]').val();

        $.ajax({
            type: "POST",
            url: "/admin/review/update_answer",
            data: {
                id: id,
                answer: answer
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