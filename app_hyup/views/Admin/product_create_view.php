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

    <form method="post" enctype="multipart/form-data" class="!space-y-6">
        <input type="hidden" name="id" value="<?= $product_row['id'] ?>" />

        <!-- 상품 이미지 -->
        <div>
            <label class="label font-semibold">대표 이미지</label>
            <input type="file" name="main_image" class="file-input file-input-bordered w-full" />
        </div>

        <div>
            <label class="label font-semibold">상품 이미지</label>
            <input
                type="file"
                name="detail_image[]"
                class="file-input file-input-bordered w-full"
                multiple />
            <input
                type="file"
                name="detail_image[]"
                class="file-input file-input-bordered w-full !mt-2"
                multiple />
            <input
                type="file"
                name="detail_image[]"
                class="file-input file-input-bordered w-full !mt-2"
                multiple />
        </div>


        <!-- 상품명 -->
        <div>
            <label class="label font-semibold">상품명</label>
            <input type="text" value="<?= $product_row['name'] ?>" name="product_name" class="input input-bordered w-full" placeholder="예: 마이노멀 저당 아이스바" />
        </div>

        <!-- 매장 판매가 -->
        <div>
            <label class="label font-semibold">판매가</label>
            <input type="number" value="<?= $product_row['ori_price'] ?>" name="price" class="input input-bordered w-full" placeholder="예: 50000" />
        </div>


        <!-- 판매가 -->
        <div>
            <label class="label font-semibold">매장 판매가</label>
            <input type="number" value="<?= $product_row['only_admin_discount_price'] ?>" name="discount_price" class="input input-bordered w-full" placeholder="예: 50000" />
        </div>


        <!-- 상품 설명 -->
        <div>
            <label class="label font-semibold">상품 설명</label>
            <textarea name="description" class="textarea textarea-bordered w-full h-62" placeholder="상품의 특징 및 장점을 입력하세요."><?= $product_row['description'] ?></textarea>
        </div>

        <div class="mb-4">
            <label class="label font-semibold">상세정보</label>
            <div id="file-row" class="flex flex-wrap gap-2">
                <input type="file" name="detail_image2[]" class="file-input file-input-bordered" />
            </div>
            <button
                type="button"
                class="btn btn-outline btn-sm mt-2"
                onclick="addFileInput()">
                +
            </button>
        </div>

        <!-- 등록 버튼 -->
        <div class="text-center pt-4">
            <button type="submit" class="btn btn-primary px-8">상품 등록</button>
        </div>
    </form>
</div>

</div>


<script>
    function addFileInput() {
        const container = document.getElementById('file-row');
        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'detail_image2[]';
        input.className = 'file-input file-input-bordered';
        container.appendChild(input);
    }

    $('form').on('submit', function(e) {
        e.preventDefault(); // 기본 폼 제출 막기

        const form = $(this)[0];
        const formData = new FormData(form);

        $.ajax({
            url: '/admin/product/create_product', // 실제 처리할 PHP 경로
            type: 'POST',
            data: formData,
            contentType: false, // 중요!
            processData: false, // 중요!
            dataType: 'json',
            success: function(response) {

                alert(response.msg);

                if (response.ok) {
                    window.location.href = '/admin/product';
                }

            },
            error: function(xhr, status, error) {
                alert('에러 발생: ' + error);
            }
        });
    });

    function handle_update_modal(id) {
        // 여기서 id를 사용하여 상품 정보를 불러오고, 모달에 데이터를 채워넣는 로직을 구현해야 합니다.

        my_modal_1.show();
    }
</script>