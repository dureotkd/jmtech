<div class="flex flex-col !gap-24 relative w-full bg-cover bg-center">
    <img class="lg:h-[calc(100vh-482px)] h-[170px] w-full object-cover object-left"
        src="/assets/app_hyup/images/banner/product.jpg" class="" />


    <section class="!flex flex-col items-center w-full justify-center py-12 text-center">
        <h2 data-aos="fade-up"
            data-aos-anchor-placement="center-bottom"
            data-aos-duration="1200" class="lg:!text-2xl !text-xl color-sm font-bold playfair_display font-bold" style="letter-spacing: 0.04em;">All Products</h2>
        <p data-aos="fade-up"
            data-aos-delay="300"
            data-aos-anchor-placement="center-bottom"
            data-aos-duration="1200" class="lg:!text-base !text-sm text-gray-900 !mt-3 playfair_display">제이엠테크의 가치가 담긴 제품을 만나보세요</p>

        <div data-aos="fade"
            data-aos-offset="0"
            data-aos-delay="700"
            data-aos-duration="1200"
            data-aos-anchor-placement="top-bottom"
            data-aos-easing="ease-out-cubic" class="lg:!px-0 lg:!w-5xl w-full !px-4 !mt-6 grid grid-cols-2 md:grid-cols-3 lg:gap-6 gap-2 mx-auto">

            <?
            if (!empty($products)) {
                foreach ($products as $product) {
            ?>
                    <div onclick="go_product_detail(event,'<?= $product['id'] ?>');" class="relative">
                        <div class="realtive cursor-pointer relative group bg-white rounded-sm overflow-hidden">
                            <img
                                src="<?= $product['image_url'] ?>"
                                alt="<?= $product['name'] ?>"
                                class="lg:h-84 h-54 object-cover transition-transform duration-300 transform hover:scale-115" />
                        </div>

                        <div class="text-left !mt-4 rounded">
                            <!-- 상품명 -->
                            <div class="text-sm text-gray-800 !mb-1">
                                <?= $product['name'] ?></div>
                        </div>

                        <!-- 가격 -->
                        <div class="flex items-center gap-2 mb-2">
                            <?
                            if (!empty($product['discount_price'])) {
                            ?>
                                <span class="text-lg font-bold text-gray-800">
                                    <?= number_format($product['discount_price']) ?>원
                                </span>
                                <span class="line-through !text-sm text-gray-400"><?= number_format($product['ori_price']) ?>원</span>
                            <?
                            } else {
                            ?>
                                <span class="text-lg font-bold text-gray-800"><?= number_format($product['price']) ?>원</span>
                            <?
                            }
                            ?>
                        </div>

                        <!-- 아이콘 버튼 그룹 -->
                        <div class="absolute top-2 right-2 flex flex-col gap-2">
                            <button onclick="go_product_pay(event,'<?= $product['id'] ?>');" type="button" class="bg-white w-8 h-8 rounded-lg shadow flex items-center justify-center">
                                <img src="https://akei.shop/web/upload/icon_202110142307174100.jpg" class="w-6 ec-admin-icon cart">
                            </button>
                        </div>
                    </div>
            <?
                }
            }
            ?>
        </div>
    </section>
</div>

<script>
    function go_product_pay(e, id) {
        // 이벤트 버블링 안되게
        e.stopPropagation();
        addCart(id, 1)
    }

    function go_product_detail(e, id) {

        // 이벤트 버블링 안되게
        e.stopPropagation();
        fadeOutButton(`/product?id=${id}`);
    }
</script>