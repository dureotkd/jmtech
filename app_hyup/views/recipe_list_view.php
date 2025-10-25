<div class="flex flex-col !gap-24 relative w-full bg-cover bg-center">
    <img
        class="lg:h-[calc(100vh-482px)] h-[170px] w-full object-cover filter brightness-75"
        src="/assets/app_hyup/images/banner/recipe.jpg"
        alt="" />

    <section class="!flex flex-col items-center w-full justify-center py-12 text-center">
        <h2 data-aos="fade-up"
            data-aos-anchor-placement="center-bottom"
            data-aos-duration="1200" class="lg:!text-2xl !text-xl color-sm font-bold playfair_display font-bold" style="letter-spacing: 0.04em;">Our Recipe</h2>
        <p data-aos="fade-up"
            data-aos-delay="300"
            data-aos-anchor-placement="center-bottom"
            data-aos-duration="1200" class="lg:!text-base !text-sm text-gray-900 !mt-3 playfair_display">우리의 레시피, 당신의 건강을 위한 약속</p>

        <div data-aos="fade"
            data-aos-offset="0"
            data-aos-delay="700"
            data-aos-duration="1200"
            data-aos-anchor-placement="center-bottom"
            data-aos-easing="ease-out-cubic" class="lg:!px-0 !px-4 !mt-6 grid grid-cols-2 md:grid-cols-3 lg:gap-6 gap-2 mx-auto">
            <!-- 상품 1 -->
            <?
            foreach ($recipes as $recipe) {
            ?>
                <div onclick="fadeOutButton('/recipe?id=<?= $recipe['id'] ?>')" class="">
                    <div class="realtive cursor-pointer relative group bg-white shadow-sm overflow-hidden">
                        <img
                            src="<?= $recipe['image_url'] ?>"
                            alt="<?= $recipe['title'] ?>"
                            class="lg:h-84 h-54 object-cover transition-transform duration-300 transform hover:scale-115" />
                    </div>

                    <div class="text-left !py-4 rounded">
                        <div class="!text-md text-center text-gray-800 !mb-1">
                            <?= $recipe['title'] ?>
                        </div>

                    </div>
                </div>
            <?
            }
            ?>

        </div>
    </section>
</div>