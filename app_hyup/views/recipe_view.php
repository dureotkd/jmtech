<div class="lg:gap-24 lg:!mt-10 gap-12 flex flex-col max-w-screen-md !mx-auto justify-center p-10 font-sans text-sm text-gray-800">
    <div class="lg:flex-row lg:gap-12 gap-4 flex-col flex w-full h-full">
        <!-- 상품 이미지 -->
        <div class="lg:w-1/2 w-full max-w-[612px] z-0 !space-y-4 relative">
            <div class="owl-carousel owl-theme main-slider owl-theme">
                <div class="w-full ">
                    <img class="w-full" src="<?= $recipe['image_url'] ?>" alt="<?= $recipe['title'] ?>" class="rounded-md w-full object-cover" />
                </div>
            </div>
        </div>

        <div class="lg:w-1/2 lg:!px-0 !px-4 w-full flex flex-col justify-between">
            <div class="!space-y-4">

                <!-- 상단 제목 -->
                <h2 class="lg:!border-t-2 lg:!pt-4 lg:!mb-9 !border-t-0 !border-gray-100 !text-2xl !text-gray-800">
                    <?= $recipe['title'] ?>
                </h2>

                <!-- 가격 및 배송 정보 -->
                <div class="!space-y-6 text-sm">

                    <!-- 판매가 -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        <div class="font-bold w-32 shrink-0">난이도</div>
                        <div>
                            <span class="text-lg color-sm font-bold text-gray-800 !mr-1">
                                <?
                                $filled_star = '★';
                                $empty_star = '☆';
                                $max_stars = 5;

                                for ($i = 1; $i <= $max_stars; $i++) {
                                    echo $i <= $recipe['level'] ? $filled_star : $empty_star;
                                }
                                ?>
                            </span>
                        </div>
                    </div>

                    <!-- 배송방법 -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        <div class="font-bold w-32 shrink-0">소요시간</div>
                        <div>
                            <?= $recipe['cooking_time'] ?>분
                        </div>
                    </div>


                    <!-- 사용제품 -->
                    <!-- <div class="flex flex-col md:flex-row gap-2">
                        <div class="font-bold w-32 shrink-0">사용제품</div>
                        <div class="grid grid-cols-2 !gap-2">

                            <div class="!space-y-1 cursor-pointer group bg-white rounded-md overflow-hidden">
                                <img src="https://ecudemo276582.cafe24.com/web/product/medium/202304/429f508a8cca930c14c9fd94c819fc53.png" alt="Eggs" class="w-full object-cover">
                                <div class="w-full py-2 !text-sm truncate text-center text-gray-800 font-semibold">
                                    마이노멀 저당 아이스
                                </div>
                            </div>

                        </div>
                    </div> -->

                </div>
            </div>

        </div>
    </div>

    <div class="lg:!px-0 !px-4">
        <?= nl2br($recipe['content']) ?>
    </div>

</div>