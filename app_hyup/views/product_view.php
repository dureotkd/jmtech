<style>
    th {
        font-weight: normal;
        width: 106px;
        padding: 7px 5px 6px 0;
        vertical-align: top;
        text-align: left;
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

<!-- Owl Carousel 스타일 -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/assets/owl.carousel.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/assets/owl.theme.default.min.css" />


<div class="lg:gap-24 lg:!mt-10 gap-12 flex flex-col max-w-screen-xl !mx-auto justify-center p-10 font-sans text-sm text-gray-800">
    <div class="lg:flex-row lg:gap-12 gap-4 flex-col flex w-full h-full">
        <!-- 상품 이미지 -->
        <div class="lg:w-1/2 w-full max-w-[612px] z-0 !space-y-4 relative">

            <div class="owl-carousel owl-theme main-slider owl-theme max-h-[580px]">
                <?
                foreach ($product['image_array'] as $image_url) {
                ?>
                    <div class="w-full">
                        <img class="w-full object-cover max-h-[580px]" src="<?= $image_url ?>" alt="유기농 아보카도" />
                    </div>
                <?
                }
                ?>
            </div>

            <!-- Thumbnail Slider -->
            <div class="lg:!px-0 !px-4 owl-carousel owl-theme thumb-slider">
                <?
                foreach ($product['image_array'] as $image_url) {
                ?>
                    <div>
                        <img src="<?= $image_url ?>" class="h-20 object-cover cursor-pointer" />
                    </div>
                <?
                }
                ?>
            </div>

        </div>

        <!-- 상품 상세 정보 -->
        <form id="form1" onsubmit="handle_buy_product(event);" class="lg:w-1/2 lg:!px-0 !px-4 w-full flex flex-col justify-between">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>" />
            <input type="hidden" name="price" value="<?= $product['price'] ?>" />
            <input type="hidden" name="amount" value="0" />


            <div class="!space-y-4">

                <!-- 상단 제목 -->
                <h2 class="lg:!border-t-2 lg:!pt-4 lg:!mb-9 !border-t-0 !border-gray-100 !text-2xl !text-gray-800">
                    <?= $product['name'] ?>
                </h2>

                <!-- 가격 및 배송 정보 -->
                <div class="!space-y-6 text-sm">

                    <!-- 판매가 -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        <div class="font-bold w-32 shrink-0">판매가</div>
                        <div>
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
                        </div>
                    </div>

                    <!-- 배송방법 -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        <div class="font-bold w-32 shrink-0">배송방법</div>
                        <div>택배</div>
                    </div>

                    <!-- 배송비 -->
                    <div class="flex flex-col md:flex-row md:items-center gap-2">
                        <div class="font-bold w-32 shrink-0">배송비</div>
                        <div>
                            3,000원
                            <div class="flex items-center gap-1">
                                <span class="!text-sm text-gray-500">(30,000원 이상 구매 시 무료)</span>
                                <svg id="ship_tooltip" class="!text-sm text-gray-500" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info-icon lucide-info">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 16v-4" />
                                    <path d="M12 8h.01" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- 제품설명 -->
                    <div class="flex flex-col md:flex-row gap-2">
                        <div class="font-bold w-32 shrink-0">제품설명</div>
                        <div>
                            <p>
                                <?= nl2br($product['description']) ?>
                            </p>
                        </div>
                    </div>

                    <!-- 레시피 -->
                    <!-- <div class="flex flex-col md:flex-row gap-2">
                        <div class="font-bold w-32 shrink-0">레시피</div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 !gap-2">

                            <div class="!space-y-1 cursor-pointer group bg-white rounded-md overflow-hidden">
                                <img src="https://cdn.imweb.me/upload/S20200629b683866f4b1fb/857b1804a0cfb.jpg" alt="Eggs" class="w-full object-cover">
                                <div class="w-full py-2 !text-sm truncate text-center text-gray-800 font-semibold">
                                    마이노멀 저당 아이스
                                </div>
                            </div>

                            <div class="!space-y-1 cursor-pointer group bg-white rounded-md overflow-hidden">
                                <img src="https://cdn.imweb.me/upload/S20200629b683866f4b1fb/c25fac2c281c5.jpg" alt="Eggs" class="w-full object-cover">
                                <div class="py-2 !text-sm truncate text-center text-gray-800 font-semibold">
                                    마이노멀 저당 아이스
                                </div>
                            </div>

                        </div>
                    </div> -->

                    <div class="!space-y-4 !py-4 !border-t !border-gray-400">

                        <!-- 상품 선택 -->
                        <div class="flex justify-between items-center">
                            <p class="text-gray-800 font-medium">
                                <?= $product['name'] ?>
                            </p>
                            <div class="flex items-center border border-gray-300 rounded-sm overflow-hidden">
                                <div class="flex items-center border border-gray-300 rounded-sm overflow-hidden w-fit">
                                    <button onclick="down_quan(event);" class="border-1 h-[30px] border-gray-300 px-3 py-1 text-gray-600 hover:bg-gray-100">-</button>
                                    <input
                                        type="text"
                                        min="1"
                                        value="1"
                                        id="quantity"
                                        readonly
                                        class="w-12 h-[30px] text-center py-1 text-sm border-gray-300 outline-none" />
                                    <button onclick="up_quan(event);" class="border-1 border-gray-300 px-3 py-1 h-[30px] text-gray-600 hover:bg-gray-100">+</button>
                                </div>

                            </div>
                            <?
                            if (!empty($product['discount'])) {
                            ?>
                                <span class="unit-price text-gray-900 font-semibold text-sm"><?= number_format($product['discount']) ?>원</span>
                            <?
                            } else {
                            ?>
                                <span class="unit-price text-gray-900 font-semibold text-sm"><?= number_format($product['price']) ?>원</span>
                            <?
                            }
                            ?>
                        </div>

                        <hr class="border-gray-200">

                        <!-- 총합 -->
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-900 uppercase">Total (Quantity)</p>
                            <div class="text-right">
                                <p id="amount" class="text-lg font-bold text-gray-900"><?= number_format($product['price']) ?>원 </p>
                                <span id="quan" class="text-sm text-gray-500 align-middle">(1개)</span>
                            </div>
                        </div>

                        <!-- 버튼 영역 -->
                        <div class="grid grid-cols-2 gap-2 mt-4">
                            <button type="button" onclick="go_payment_view();" class="bg-sm text-white py-3 font-semibold">구매하기</button>
                            <button type="button" onclick="handle_cart();" class="border border-gray-300 text-gray-700 py-3 font-semibold hover:bg-gray-100">장바구니</button>
                        </div>

                    </div>


                </div>
            </div>

        </form>
    </div>

    <div class="w-full sticky bg-[#fff] top-0 z-1  border-b">
        <div class="menus mx-auto flex justify-center text-sm text-gray-600">

            <!-- 상세정보 (활성 탭) -->
            <button onclick="scrollToSection(event,'detail')" data-target="detail" type="button" class="flex-1 text-center py-4 active-link hover:text-[#0abab5] font-medium">
                상세정보
            </button>

            <!-- 리뷰 -->
            <button onclick="scrollToSection(event,'review')" data-target="review" type="button" class="flex-1 text-center py-4 border-b border-gray-200 hover:text-[#0abab5]">
                리뷰 <span class="text-[13px]">(<?= count($reviews) ?>)</span>
            </button>

            <!-- Q&A -->
            <button onclick="scrollToSection(event,'qna')" data-target="qna" type="button" class="flex-1 text-center py-4 border-b border-gray-200 hover:text-[#0abab5]">
                Q&amp;A <span class="text-[13px]">(<?= count($qnas) ?>)</span>
            </button>

        </div>
    </div>

    <div class="lg:!space-y-32 !space-y-12">
        <?
        $detail_images = [
            "00.jpg",
            "01-1.jpg",
            "02-1.jpg",
            "02-2.jpg",
            "03-1.jpg",
            "04-1.jpg",
            "05-1.jpg",
            "06-1.jpg",
            "07-1.jpg",
            "09-1.jpg",
            "10-1.jpg",
            "12-1.jpg"
        ];
        ?>

        <section id="detail" class="!flex">
            <div class="grid grid-cols-1 !mx-auto">
                <?
                if (!empty($product['detail_image_urls2'])) {
                    foreach ($product['detail_image_urls2'] as $image_src) {
                ?>
                        <img src="<?= $image_src ?>" alt="상품 상세 이미지" class="h-auto" />
                <?
                    }
                }
                ?>
            </div>
        </section>

        <section class="lg:px-0 !px-4" id="review">
            <div class="lg:!space-y-14 !space-y-8">
                <h2 class="!text-lg font-bold playfair_display !border-b !border-gray-200 !pb-2">REVIEW <span class="font-normal">(183)</span></h2>

                <div class="flex flex-col md:flex-row !gap-6">
                    <!-- 별점 -->
                    <div class="flex-1 flex flex-col items-center justify-center">
                        <div class="color-sm !text-5xl mb-2">★</div>
                        <div class="!text-4xl font-semibold">
                            <?= $avg_review['rating'] ?? 0 ?>
                        </div>
                        <?
                        if (!empty($login_user)) {
                        ?>
                            <button onclick="open_review_modal();" type="button" class="mt-4 btn-primary-sm text-white px-4 py-2 rounded font-semibold hover:opacity-90">
                                상품 리뷰 작성하기
                            </button>
                        <?
                        } else {
                        ?>
                            <button onclick="alert('로그인 후 이용해주세요')" type="button" class="mt-4 btn-primary-sm text-white px-4 py-2 rounded font-semibold hover:opacity-90">
                                상품 리뷰 작성하기
                            </button>
                        <?
                        }
                        ?>
                    </div>

                    <!-- 막대 그래프 -->
                    <?php
                    $labels = [
                        5 => '정말 만족해요',
                        4 => '만족해요',
                        3 => '괜찮아요',
                        2 => '아쉬워요',
                        1 => '별로예요'
                    ];

                    $total = array_sum($avg_review); // 전체 리뷰 수
                    ?>
                    <div class="flex-1 !space-y-3 text-sm">
                        <?php foreach ($labels as $score => $label):
                            $count = $avg_review[$score] ?? 0;
                            $percent = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                        ?>
                            <div class="flex items-center justify-between">
                                <span class="w-[100px]"><?= $label ?></span>
                                <div class="flex-1 mx-2 bg-gray-200 h-2 rounded overflow-hidden">
                                    <div
                                        class="bg-sm h-full"
                                        style="width: <?= $percent ?>%"></div>
                                </div>
                                <span class="!ml-3 text-right text-sm text-gray-500">
                                    <?= $count ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>

                <!-- 포토/영상 -->
                <div>
                    <div class="flex justify-between items-center !mb-2">
                        <h3 class="!font-semibold playfair_display">PHOTO <span class="font-normal">(<?= count($photo_reviews) ?>)</span></h3>
                    </div>

                    <div id="photoGallery" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 !mb-12 gap-2">
                        <?
                        if (!empty($photo_reviews)) {
                            foreach ($photo_reviews as $idx => $photo_review) {

                                if ($idx >= 6) {
                                    break;
                                }

                        ?>
                                <img onclick="handle_preview_images('<?= $idx ?>');" src="<?= $photo_review ?>" class="w-full h-46 object-cover rounded" />
                        <?

                            }
                        }
                        ?>
                        <div class="hidden">
                            <?
                            if (!empty($photo_reviews)) {
                                foreach ($photo_reviews as $idx => $photo_review) {
                            ?>
                                    <a href="<?= $photo_review ?>" data-src="<?= $photo_review ?>" data-fancybox="gallery">
                                        <img src="<?= $photo_review ?>">
                                    </a>
                            <?

                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?
                    if (!empty($reviews)) {

                        $review_rating = unserialize(REVIEW_RATING);

                        foreach ($reviews as $review) {
                    ?>
                            <!-- 리뷰 카드 -->
                            <div class="flex gap-6 !mt-4 !border-b !border-gray-200 !pb-6">

                                <!-- 좌측: 리뷰 내용 -->
                                <div class="flex-1 !space-y-3">

                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center color-sm">
                                            <?
                                            for ($i = 0; $i < 5; $i++) {
                                                if ($i < $review['rating']) {
                                                    echo '★';
                                                } else {
                                                    echo '☆';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <span class="font-semibold  text-gray-800">
                                            <?= $review_rating[$review['rating']] ?>
                                        </span>
                                    </div>

                                    <div class="text-gray-700 leading-6 text-sm">
                                        <?= $review['content'] ?>
                                    </div>

                                    <div class="flex flex-wrap gap-2 !mt-2">
                                        <?
                                        if (!empty($review['image_urls'])) {

                                            $review_image_urls = explode(',', $review['image_urls']);

                                            foreach ($review_image_urls as $image_url) {
                                        ?>
                                                <img src="<?= $image_url ?> " alt="리뷰 이미지" class="!w-42 h-fit rounded border border-sm" />
                                        <?
                                            }
                                        }
                                        ?>
                                    </div>

                                </div>

                                <!-- 우측: 작성자 정보 -->
                                <div class="lg:flex hidden flex-col items-end gap-1 w-64 text-sm text-gray-600 flex-shrink-0">
                                    <div class="mb-2"><?= $review['user_login_id'] ?>님의 리뷰입니다.</div>
                                    <div class="text-right !text-sm text-gray-400 mt-2">
                                        <?= timeAgo($review['created_at']) ?>
                                    </div>
                                </div>

                            </div>
                        <?
                        }
                    } else {
                        ?>
                        <div class="flex items-center justify-center w-full !my-6">
                            아직 작성된 리뷰가 없습니다.
                        </div>
                    <?
                    }
                    ?>
                </div>


            </div>

        </section>

        <section class="lg:px-0 !px-4" id="qna">
            <div class="text-sm text-gray-800 !space-y-4">

                <h2 class="!text-lg font-bold playfair_display !border-b !border-gray-200 !pb-2">Q&amp;A <span class="font-normal">(<?= count($qnas) ?>)</span></h2>

                <!-- 문의 버튼 -->

                <div class="flex flex-col">

                    <p class="text-gray-500">구매하시려는 상품에 대해 궁금한 점이 있으면 문의주세요.</p>
                </div>

                <div class="">
                    <!-- 테이블 헤더 -->
                    <div class="grid grid-cols-4 md:grid-cols-5 !py-2 !px-4 !border-t !border-gray-600 font-semibold bg-white text-center">
                        <div class="col-span-1">상태</div>
                        <div class="col-span-2 md:col-span-2">제목</div>
                        <div class="hidden md:block">작성자</div>
                        <div class="text-right">등록일</div>
                    </div>

                    <!-- Q&A 항목 -->
                    <div class="divide-y !py-2 !px-4 !border-y !border-gray-200 divide-gray-200">
                        <?
                        if (!empty($qnas)) {
                            foreach ($qnas as $qna) {
                        ?>
                                <!-- 반복 항목 -->
                                <div onclick="toggle_answer(<?= $qna['id'] ?>);" class="grid grid-cols-4 md:grid-cols-5 py-3 px-4 text-center bg-white cursor-pointer !py-2 items-center">
                                    <div class="text-xs color-sm font-semibold">
                                        <?= $qna['status'] ?>
                                    </div>
                                    <div class="col-span-2 md:col-span-2 flex items-center gap-1 justify-center text-gray-500 text-sm">
                                        <?
                                        if (!empty($qna['is_secret']) && $qna['is_secret'] == 1) {
                                        ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-lock-keyhole-icon lucide-lock-keyhole">
                                                <circle cx="12" cy="16" r="1" />
                                                <rect x="3" y="10" width="18" height="12" rx="2" />
                                                <path d="M7 10V7a5 5 0 0 1 10 0v3" />
                                            </svg>
                                        <?
                                        }
                                        ?>
                                        <p class="line-clamp-1"><?= $qna['title'] ?></p>
                                    </div>
                                    <div class="hidden md:block text-gray-500 text-sm">
                                        <?= maskString($qna['writer_user_id']) ?>
                                    </div>
                                    <div class="text-right text-gray-500 text-xs">
                                        <?= timeAgo($qna['created_at']) ?>
                                    </div>
                                </div>
                                <?
                                if ($qna['is_secret']) {
                                ?>
                                    <?
                                    if (!empty($login_user) && $login_user['user_id'] == $qna['writer_user_id']) {
                                    ?>
                                        <div class="hidden answer-box-<?= $qna['id'] ?> w-full !text-sm text-center !space-y-3 bg-white !py-2 items-center">
                                            <!-- 질문자 말풍선 -->
                                            <div class="flex items-center !space-x-2">
                                                <!-- 프로필 아이콘 -->
                                                <div class="w-8 h-8 rounded-full bg-blue-200 text-blue-800 flex items-center justify-center font-bold">
                                                    Q
                                                </div>
                                                <!-- 말풍선 -->
                                                <div class="bg-white border border-gray-200 rounded-xl !p-3 shadow-sm">
                                                    <p class="!text-sm text-gray-800"><?= $qna['title'] ?></p>
                                                    <p class="!text-sm text-gray-800"><?= $qna['content'] ?></p>
                                                </div>
                                            </div>

                                            <!-- 답변자 말풍선 -->
                                            <div class="flex items-center justify-end !space-x-2">
                                                <!-- 말풍선 -->
                                                <div class="bg-green-100 border border-green-200 rounded-xl !p-3 shadow-sm">
                                                    <p class="!text-sm text-gray-800">
                                                        <?= $qna['answer'] ?? '아직 답변이 등록되지 않았습니다.' ?>
                                                    </p>
                                                </div>
                                                <!-- 프로필 아이콘 -->
                                                <div class="w-8 h-8 rounded-full bg-green-300 text-green-900 flex items-center justify-center font-bold">
                                                    A
                                                </div>
                                            </div>

                                        </div>
                                    <?
                                    }
                                    ?>
                                <?
                                } else {
                                ?>
                                    <div class="hidden answer-box-<?= $qna['id'] ?> w-full !text-sm text-center !space-y-3 bg-white !py-2 items-center">
                                        <!-- 질문자 말풍선 -->
                                        <div class="flex items-center !space-x-2">
                                            <!-- 프로필 아이콘 -->
                                            <div class="w-8 h-8 rounded-full bg-blue-200 text-blue-800 flex items-center justify-center font-bold">
                                                Q
                                            </div>
                                            <!-- 말풍선 -->
                                            <div class="bg-white border border-gray-200 rounded-xl !p-3 shadow-sm">
                                                <p class="!text-sm text-gray-800"><?= $qna['title'] ?></p>
                                                <p class="!text-sm text-gray-800"><?= $qna['content'] ?></p>
                                            </div>
                                        </div>

                                        <!-- 답변자 말풍선 -->
                                        <div class="flex items-center justify-end !space-x-2">
                                            <!-- 말풍선 -->
                                            <div class="bg-green-100 border border-green-200 rounded-xl !p-3 shadow-sm">
                                                <p class="!text-sm text-gray-800">
                                                    <?= $qna['answer'] ?? '아직 답변이 등록되지 않았습니다.' ?>
                                                </p>
                                            </div>
                                            <!-- 프로필 아이콘 -->
                                            <div class="w-8 h-8 rounded-full bg-green-300 text-green-900 flex items-center justify-center font-bold">
                                                A
                                            </div>
                                        </div>

                                    </div>
                                <?
                                }
                                ?>
                            <?
                            }
                        } else {
                            ?>
                            <!-- 반복 항목 -->
                            <div class="">
                                <p class="text-center !text-sm text-gray-500">아직 작성된 상품 문의가 없습니다.</p>
                            </div>
                        <?
                        }
                        ?>
                    </div>

                    <?
                    if (!empty($login_user)) {
                    ?>
                        <div class="!mt-4 flex justify-end">
                            <button onclick="handle_qna_modal(event);" type="button" class="bg-sm text-white px-6 py-2 rounded-md text-sm font-semibold">상품문의</button>
                        </div>
                    <?
                    } else {
                    ?>
                        <div class="!mt-4 flex justify-end">
                            <button onclick="alert('로그인 후 이용해주세요')" type="button" class="bg-sm text-white px-6 py-2 rounded-md text-sm font-semibold">상품문의</button>
                        </div>
                    <?
                    }
                    ?>

                </div>

                <!-- 페이지네이션 -->
                <!-- <div class="flex justify-center items-center mt-6 space-x-2 text-gray-500 text-sm">
                        <button class="px-2">‹</button>
                        <button class="font-bold text-black">1</button>
                        <button>2</button>
                        <button>3</button>
                        <button>4</button>
                        <button class="px-2">›</button>
                    </div> -->

                <!-- 하단 버튼 -->
            </div>

        </section>

    </div>
</div>

<dialog id="my_modal_2" class="modal">
    <form onsubmit="handle_review_form(event);" class="modal-box !px-6 !py-4">
        <button type="button" onclick="hide_close_modal();" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        <div class="flex flex-col items-center justify-center">
            <h3 class="!text-lg !font-bold text-center">리뷰 작성</h3>
        </div>

        <input type="hidden" name="product_id" value="<?= $product['id'] ?>" />

        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <div class="flex items-center !space-x-3 !my-3">
                <img src="<?= $product['image_url'] ?>" alt="product" class="max-h-[140px] object-contain" />
                <h2 class="text-sm font-semibold">
                    <?= $product['name'] ?> 상품 리뷰
                </h2>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 !space-y-4 text-sm">
            <div class="">
                <p><strong><?= $login_user['name'] ?> 고객님</strong>, 구매 상품은 어떠셨나요?</p>
                <p>소중한 리뷰를 기다리고 있어요.</p>
            </div>

            <!-- Dropdown -->
            <div class="!space-y-2">
                <select name="rating" class="w-full border border-gray-400 rounded px-3 py-2 text-sm">
                    <option>리뷰를 작성해주세요.</option>
                    <?
                    $review_rating = unserialize(REVIEW_RATING);
                    foreach ($review_rating as $key => $value) {
                        echo "<option value='$key'>$value</option>";
                    }
                    ?>
                </select>
                <textarea rows="4" name="content" class="w-full border border-gray-400 rounded px-3 py-2 text-sm resize-none" placeholder=""></textarea>
            </div>

            <!-- Upload -->
            <div>
                <label class="block mb-1 font-medium">포토/동영상 첨부</label>
                <p class="!text-sm text-gray-500 mb-2">
                    상품과 상관없는 사진 및 동영상은 첨부된 리뷰는 통보없이 삭제될 수 있습니다.
                </p>

                <div class="gap-2 flex flex-wrap flex-row max-h-[125px] overflow-y-auto">
                    <?
                    for ($i = 1; $i <= 10; $i++) {
                    ?>
                        <div id="previewContainer_<?= $i ?>" class="preview-container">

                            <?
                            if (!empty($estate_row['sub_images'][$i - 1])) {

                                $sub_image = $estate_row['sub_images'][$i - 1];
                            ?>
                                <div class="image-preview">
                                    <img src="<?= $sub_image['file_path'] ?>" />
                                    <button type="button" onclick="remeov_image(event);" class="remove-btn absolute top-1 right-1 bg-opacity-80 rounded-full bg-[#4b4f53] w-[30px] h-[30px] p-2 flex items-center justify-center text-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x !text-[#fff] font-semibold">
                                            <path d="M18 6 6 18"></path>
                                            <path d="m6 6 12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?
                            }
                            ?>
                        </div>
                    <?
                    }
                    ?>
                </div>

                <!-- 파일 업로드 영역 -->
                <div class="relative !my-2">
                    <!-- 숨겨진 파일 입력 -->
                    <input type="file" class="file_uploads hidden" id="file_1" accept="image/*" multiple>
                    <!-- 클릭 가능한 UI -->
                    <div
                        class="border border-dashed rounded flex items-center justify-center w-full h-24 cursor-pointer my-2 bg-gray-100"
                        onclick="document.getElementById('file_1').click()">
                        <span class="!text-2xl text-gray-400">+</span>
                    </div>
                </div>

            </div>
        </div>


        <!-- 버튼 -->
        <button type="submit" class="w-full btn-primary-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900">
            리뷰 작성하기
        </button>
    </form>
</dialog>

<dialog id="my_modal_3" class="modal">
    <form onsubmit="handle_qna_form(event);" class="modal-box !px-6 !py-4 !spacee-y-4">
        <button type="button" onclick="hide_close_modal2();" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        <div class="flex flex-row items-center justify-between">
            <h3 class="!text-lg !font-bold text-center">Q&A 작성</h3>
        </div>

        <input type="hidden" name="product_id" value="<?= $product['id'] ?>" />
        <input type="hidden" name="content" value="" />

        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <div class="flex items-center !space-x-3 !my-3">
                <h2 class="text-sm font-semibold">
                    <?= $product['name'] ?>
                </h2>
            </div>

            <label>
                비밀글
                <input type="checkbox" name="is_secret" value="1" class="checkbox checkbox-sm mr-2" />
            </label>
        </div>

        <!-- Content -->
        <div class="p-4 !space-y-4 text-sm">
            <!-- Dropdown -->
            <input type="text" name="title" placeholder="제목" class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400">

            <div id="editor" class="min-h-[200px] max-h-[500px] border border-gray-200 rounded p-3 overflow-y-auto">
            </div>
        </div>

        <!-- 버튼 -->
        <button type="submit" class="!mt-4 w-full btn-primary-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900">
            Q&A 작성하기
        </button>
    </form>
</dialog>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet" />
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.css" />
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.0/dist/fancybox/fancybox.umd.js"></script>

<script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>

<script>
    tippy('#ship_tooltip', {
        placement: 'top',
        arrow: false,
        allowHTML: true,
        content: "도서산간 지역 9,000원 추가<br />제주도 6,000원 추가",
    });

    let imageFiles = []; // 실제 전송할 이미지 저장소

    const quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'], // 굵게, 기울임 등
                [{
                    color: []
                }, {
                    background: []
                }], // 글자색, 배경색
                [{
                    header: [1, 2, 3, false]
                }], // 제목 크기
                [{
                    list: 'ordered'
                }, {
                    list: 'bullet'
                }], // 리스트
                ['link', 'image'], // 링크, 이미지
                ['clean'] // 초기화
            ]
        },
        placeholder: '내용을 입력하세요...'
    });


    // ✅ 1. 내용 변경 시 hidden input에 HTML 저장
    quill.on('text-change', function() {
        document.querySelector("input[name='content']").value = quill.root.innerHTML;
    });


    function handle_qna_modal(e) {

        e.preventDefault();
        const modal = document.getElementById("my_modal_3");
        if (modal) {
            modal.showModal();
        } else {
            console.error("Modal element not found");
        }
    }


    $("#file_1").on("change", function() {
        const files = Array.from(this.files);
        const maxCount = 10;

        if (imageFiles.length + files.length > maxCount) {
            alert("최대 10장까지 업로드 가능합니다.");
            return;
        }

        files.forEach((file) => {
            imageFiles.push(file); // ✅ 여기 저장
            const reader = new FileReader();

            reader.onload = function(e) {
                const preview = $(`
        <div class="image-preview2 relative inline-block mr-2 mb-2 w-[100px] h-[100px] overflow-hidden rounded-md border">
              <img src="${e.target.result}" class="w-full h-full object-cover" />
              <button type="button" class="remove-btn absolute top-1 right-1 bg-opacity-80 rounded-full bg-[#4b4f53] w-[24px] h-[24px] p-1 flex items-center justify-center text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 6 6 18"></path>
                  <path d="m6 6 12 12"></path>
                </svg>
              </button>
            </div>
      `);

                preview.find(".remove-btn").on("click", function() {
                    const index = preview.index(); // 위치로 판단하거나, 다른 방식으로 식별
                    imageFiles.splice(index, 1); // ✅ 배열에서도 제거
                    preview.remove();
                });

                for (let i = 1; i <= maxCount; i++) {
                    const container = $(`#previewContainer_${i}`);
                    if (container.children().length === 0) {
                        container.append(preview);
                        break;
                    }
                }
            };

            reader.readAsDataURL(file);
        });

        this.value = ""; // 초기화 OK — imageFiles에 따로 저장했으므로
    });

    Fancybox.bind("[data-fancybox]", {
        // Your custom options
    });


    function handle_preview_images(startIndex = 0) {
        Fancybox.show([
            <?php foreach ($photo_reviews as $photo_review): ?> {
                    src: "<?= $photo_review ?>",
                    type: "image"
                },
            <?php endforeach; ?>
        ], {
            startIndex: startIndex,
            Toolbar: true, // 상단 툴바 (닫기/공유 등)
            animated: true,
            infinite: true, // 루프
            dragToClose: false
        });
    }

    function open_review_modal() {
        const modal = document.getElementById("my_modal_2");
        if (modal) {
            modal.showModal();
        } else {
            console.error("Modal element not found");
        }
    }

    function toggle_answer(id) {
        const answer_box = $(".answer-box-" + id);
        if (answer_box) {
            answer_box.toggleClass("hidden");
        }
    }

    function handle_qna_form(e) {
        e.preventDefault(); // 폼 제출 방지

        const form = $(e.target);
        const serial = form.serialize();

        $.ajax({
            url: '/product/create_qna', // 실제 처리할 PHP 경로
            type: 'POST',
            data: serial,
            dataType: 'json',
            success: function(response) {
                alert(response.msg);

                if (response.ok) {
                    hide_close_modal2(); // 모달 닫기
                    fadeOutReload();
                }
            },
            error: function(xhr, status, error) {
                alert('에러 발생: ' + error);
            }
        });
    }

    function hide_close_modal() {
        const modal = document.getElementById("my_modal_2");
        if (modal) {
            modal.close();
        } else {
            console.error("Modal element not found");
        }
    }

    function hide_close_modal2() {
        const modal = document.getElementById("my_modal_3");
        if (modal) {
            modal.close();
        } else {
            console.error("Modal element not found");
        }
    }

    function remeov_image(e) {
        const target = $(e.target).closest('.image-preview');
        target.remove(); // 미리보기 이미지 제거
    }

    $("#file_1").on("change", function() {
        const files = Array.from(this.files);
        const maxCount = 10;

        if (imageFiles.length + files.length > maxCount) {
            alert("최대 10장까지 업로드 가능합니다.");
            return;
        }

        files.forEach((file) => {
            imageFiles.push(file); // ✅ 여기 저장
            const reader = new FileReader();

            reader.onload = function(e) {
                const preview = $(`
        <div class="image-preview2 relative inline-block mr-2 mb-2 w-[100px] h-[100px] overflow-hidden rounded-md border">
              <img src="${e.target.result}" class="w-full h-full object-cover" />
              <button type="button" class="remove-btn absolute top-1 right-1 bg-opacity-80 rounded-full bg-[#4b4f53] w-[24px] h-[24px] p-1 flex items-center justify-center text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M18 6 6 18"></path>
                  <path d="m6 6 12 12"></path>
                </svg>
              </button>
            </div>
      `);

                preview.find(".remove-btn").on("click", function() {
                    const index = preview.index(); // 위치로 판단하거나, 다른 방식으로 식별
                    imageFiles.splice(index, 1); // ✅ 배열에서도 제거
                    preview.remove();
                });

                for (let i = 1; i <= maxCount; i++) {
                    const container = $(`#previewContainer_${i}`);
                    if (container.children().length === 0) {
                        container.append(preview);
                        break;
                    }
                }
            };

            reader.readAsDataURL(file);
        });

        this.value = ""; // 초기화 OK — imageFiles에 따로 저장했으므로
    });

    function remeov_image(e) {
        const target = $(e.target).closest('.image-preview');
        target.remove(); // 미리보기 이미지 제거
    }


    $(document).ready(function() {
        var main = $(".main-slider");
        var thumbs = $(".thumb-slider");

        // 메인 슬라이더
        main.owlCarousel({
            items: 1,
            nav: false,
            dots: false,
            loop: true,
            smartSpeed: 500,
            autoplay: false,
            responsiveRefreshRate: 200
        });

        // 썸네일 슬라이더
        thumbs.owlCarousel({
            items: 5,
            margin: 8,
            nav: false,
            dots: false,
            center: false,
            responsiveRefreshRate: 100
        });

        // 썸네일 클릭 → 메인 이동
        thumbs.on("click", ".owl-item", function(e) {
            const index = $(this).index();
            main.trigger("to.owl.carousel", [index, 300, true]);
        });

        // 메인 이동 시 썸네일도 이동
        main.on("changed.owl.carousel", function(event) {
            thumbs.trigger("to.owl.carousel", [event.item.index, 100, true]);
        });
    });

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

    function handle_buy_product(e) {

        e.preventDefault(); // 폼 제출 방지
        const target = $(e.target);
        const serial = target.serialize();

    }

    function handle_review_form(e) {

        const target = $(e.target);
        e.preventDefault(); // 폼 제출 방지
        const formData = new FormData(e.target);

        if (imageFiles.length > 0) {
            for (let i = 0; i < imageFiles.length; i++) {
                formData.append('review_images[]', imageFiles[i]); // 파일 배열로 추가
            }
        }

        // ajax 요청
        $.ajax({
            type: "POST",
            url: "/product/create_review",
            data: formData,
            processData: false, // FormData 객체를 사용하므로 false로 설정
            contentType: false, // FormData 객체를 사용하므로 false로 설정
            dataType: "json", // 응답 데이터 타입 설정
            success: function(response) {
                alert(response.msg);

                if (response.ok) {
                    hide_close_modal(); // 모달 닫기
                    fadeOutReload();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error submitting review:", error);
                alert("리뷰 제출 중 오류가 발생했습니다. 다시 시도해주세요.");
            }
        });

    }

    function down_quan(event) {
        const quan = $(event.target).siblings('input')
        let count = parseInt(quan.val());
        if (count <= 1) {
            return; // 최소 수량이 1이므로 감소하지 않음
        }
        count--;
        quan.val(count);

        const price = parseInt($("input[name=price]").val());
        const amount = count * price;
        $("input[name=amount]").val(amount);
        $("#amount").text(`${amount.toLocaleString()}원`); // 총 금액 업데이트
        $("#quan").text(`(${count}개)`); // 총 수량 업데이트
    }

    function handle_cart(e) {

        const productId = $("input[name=product_id]").val();
        const quantity = parseInt($("#quantity").val());

        addCart(productId, quantity);

    }

    function up_quan(event) {
        const quan = $(event.target).siblings('input')
        let count = parseInt(quan.val());
        count++;
        quan.val(count);
        console.log(count)

        const price = parseInt($("input[name=price]").val());
        const amount = count * price;
        $("input[name=amount]").val(amount);
        $("#amount").text(`${amount.toLocaleString()}원`); // 총 금액 업데이트
        $("#quan").text(`(${count}개)`); // 총 수량 업데이트
    }

    function go_payment_view() {

        const product_id = $("input[name=product_id]").val();
        const quantity = parseInt($("#quantity").val());

        fadeOutButton(`/product/order?product_id=${product_id}&quantity=${quantity}`);
    }
</script>