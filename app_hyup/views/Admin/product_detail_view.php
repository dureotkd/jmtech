<style>
    th {
        font-weight: normal;
        width: 106px;
        padding: 7px 5px 6px 0;
        text-align: left;
        vertical-align: top;
    }

    td {
        padding: 7px 6px 8px 0;
        vertical-align: middle;
    }

    .cc input {
        width: 45px;
        height: 30px;
        line-height: 28px;
        margin-left: -1px;
        padding: 0;
        border: 1px solid #e5e5e5;
        text-align: center;
    }

    .cc button {
        width: 30px;
        height: 30px;
        border: 1px solid #e5e5e5;
        box-sizing: border-box;
        overflow: hidden;
        white-space: nowrap;
        text-indent: 150%;
        color: transparent;
        font-size: 1px;
        line-height: 1px;
    }

    .cc button:before {
        content: '';
        position: absolute;
        left: 10px;
        top: 50%;
        width: 9px;
        height: 1px;
        background: #000;
    }


    .cc button:after {
        content: '';
        position: absolute;
        left: 50%;
        top: 50%;
        width: 1px;
        height: 9px;
        margin: -4px 0 0 0;
        background: #000;
    }
</style>

<div class="font-sans text-sm text-gray-800">
    <div class="!mb-4 flex gap-2">
        <button class="btn" onclick="history.back();">뒤로가기</button>
        <button class="btn" onclick="window.location.href = '/admin/product/create?id=<?= $product_row['id'] ?>'">상품수정</button>
    </div>

    <div class="lg:flex-row lg:gap-12 gap-4 flex-col flex w-full h-full">

        <!-- 상품 이미지 -->
        <div class="lg:w-1/2 w-full max-w-[418px] z-0 !space-y-4 relative">
            <div class="w-full ">
                <img class="w-full" src="<?= $product_row['image_url'] ?>" alt="메인이미지" class="rounded-md w-full object-cover" />
            </div>

            <!-- Thumbnail Slider -->
            <div class="flex gap-2">
                <?
                $detail_image_urls = explode(',', $product_row['detail_image_urls']);
                foreach ($detail_image_urls as $image_url) :
                ?>
                    <div class="w-1/3">
                        <img src="<?= $image_url ?>" alt="상품 상세 이미지" class="w-full h-auto object-cover rounded-md" />
                    </div>
                <?
                endforeach;
                ?>
            </div>

        </div>

        <!-- 상품 상세 정보 -->
        <div class="lg:w-1/2 lg:!px-0 !px-4 w-full flex flex-col justify-between">

            <div class="!space-y-4">

                <!-- 상단 제목 -->
                <h2 class="lg:!border-t-2 lg:!pt-4 lg:!mb-9 !border-t-0 !border-gray-100 !text-2xl !text-gray-800">
                    <?= $product_row['name'] ?>
                </h2>

                <!-- 가격 및 배송 정보 -->
                <div class="!space-y-6 text-sm">


                    <!-- 판매가 -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        <div class="font-bold w-32 shrink-0">판매가</div>
                        <div>
                            <span class="text-lg font-bold text-gray-800 !mr-1">
                                <?= number_format($product_row['price']) ?>원
                            </span>
                        </div>
                    </div>

                    <!-- 제품설명 -->
                    <div class="flex flex-col md:flex-row gap-2">
                        <div class="font-bold w-32 shrink-0">제품설명</div>
                        <div>
                            <p>
                                <?= nl2br($product_row['description']) ?>
                            </p>
                        </div>
                    </div>

                    <!-- 레시피 -->
                    <div class="flex flex-col md:flex-row gap-2">
                        <div class="font-bold w-32 shrink-0">레시피</div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 !gap-2">
                            -
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="w-full flex flex-col items-center justify-center sticky bg-[#fff] top-0 z-1  border-b">
        <div class="menus mx-auto flex text-sm text-gray-600">

            <!-- 상세정보 (활성 탭) -->
            <button data-target="detail" type="button" class="flex-1 text-center py-4 active-link hover:text-[#0abab5] font-medium">
                상세정보
            </button>
        </div>

        <div class="grid grid-cols-1 !mx-auto">
            <?
            if (!empty($product_row['detail_image_urls2'])) {
                foreach ($product_row['detail_image_urls2'] as $image_src) {
            ?>
                    <img src="<?= $image_src ?>" alt="상품 상세 이미지" class="h-auto" />
            <?
                }
            }
            ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>


<script>
    $(window).on("scroll", function() {
        const sections = $("section[id]"); // id가 있는 section을 모두 가져옴
        const scrollPosition = $(window).scrollTop() + 60; // 여유 여백 조정

        let currentId = "";

        sections.each(function() {
            const sectionTop = $(this).offset().top;
            if (scrollPosition >= sectionTop) {
                currentId = $(this).attr("id");
            }
        });

        console.log(currentId)

        // 메뉴 active 클래스 처리
        if (currentId) {
            $(".menus .active-link").removeClass("active-link");
            $(`[data-target="${currentId}"]`).addClass("active-link");
        }
    });


    function down_quan(event) {
        const quan = $(event.target).siblings('input')
        let count = parseInt(quan.val());

        if (count <= 1) {
            return; // 최소 수량이 1이므로 감소하지 않음
        }

        count--;
        quan.val(count);
    }

    function up_quan(event) {
        const quan = $(event.target).siblings('input')
        let count = parseInt(quan.val());
        count++;
        quan.val(count);
    }

    function go_payment_view() {

        fadeOutButton('/product/order?product_id=1&quantity=1&option_id=2');
    }
</script>