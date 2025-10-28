<!DOCTYPE html>
<html lang="ko">


<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <title>
        <?= $title ?>
    </title>

    <meta name="keywords" content="">
    <meta name="description" content="<?= $meta_description ?>">
    <meta name="naver-site-verification" content="a0f1a1dd0172994547138d3ee2da6321ee38d4d2" />

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $title ?>">
    <meta property="og:description" content="<?= $meta_description ?>">
    <meta property="og:image" content="/assets/app_hyup/images/logo.jpg">
    <meta property="og:url" content="https://mosihealth.com">

    <meta name="twitter:card" content="/assets/app_hyup/images/logo.jpg">
    <meta name="twitter:title" content="<?= $title ?>">
    <meta name="twitter:description" content="<?= $meta_description ?>">
    <meta name="twitter:image" content="https://assets/app_hyup/images/logo.jpg">

    <link rel="apple-touch-icon" sizes="57x57" href="/assets/app_hyup/images/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/assets/app_hyup/images/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/assets/app_hyup/images/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/app_hyup/images/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/assets/app_hyup/images/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/assets/app_hyup/images/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/assets/app_hyup/images/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/assets/app_hyup/images/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/app_hyup/images/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/assets/app_hyup/images/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/app_hyup/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/assets/app_hyup/images/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/app_hyup/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/assets/app_hyup/pwa/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/assets/app_hyup/images/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#2a4c8f">

    <!-- <link rel="canonical" href="https://mosihealth.com/" /> -->

    <!-- tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@400;700&display=swap"
        rel="stylesheet" />

    <!-- pretendard CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretendard@1.3.8/dist/web/variable/pretendardvariable-dynamic-subset.css" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <?
    $datetime = time();
    $current_path = $_SERVER['REQUEST_URI'];
    ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link
        rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/gh/loadingio/ldbutton@latest/dist/index.min.css" />
    <link rel="stylesheet" href="/assets/app_hyup/common/reset.css" />
    <link rel="stylesheet" href="/assets/app_hyup/common/base.css?v=<?= $datetime ?>" />

    <!-- Ladda CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda-themeless.min.css" />

    <!-- iOS용 (사파리 홈화면 추가 지원) -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="JM ERP">
    <link rel="apple-touch-icon" href="/assets/app_hyup/pwa/pwa-icon-192.png">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-migrate-3.5.2.js" integrity="sha256-ThFcNr/v1xKVt5cmolJIauUHvtXFOwwqiTP7IbgP8EU=" crossorigin="anonymous"></script>

    <!-- Spin.js (Ladda 내부 의존) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>

    <!-- Ladda JS -->
    <script src="https://lab.hakim.se/ladda/dist/ladda.min.js"></script>

    <!-- Toastify JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tippy.js/6.3.7/tippy-bundle.umd.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>

    <script src="/assets/app_hyup/common/ajaxsetup.js"></script>
    <script src="/assets/app_hyup/common/header.js"></script>

    <script src="/assets/app_hyup/common/lamba.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- ✅ CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

    <!-- ✅ JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ko.js"></script>

</head>

<body>
    <div id="page-progress-bar"></div>

    <!-- <div class="loader"></div> -->
    <div class="relative w-full min-h-screen flex">


        <nav class="w-55 bg-[#2c2a34] min-h-screen min-w-[220px] text-white font-sans !text-[13px]">
            <!-- 로고 -->
            <div class="flex flex-col items-center py-4 border-b border-[#3f3f3f]">
                <img src="/assets/app_hyup/images/favicon/android-icon-192x192.png" alt="jmtech" class="w-24" />
                <div class="w-full font-semibold !pl-[16px] !py-1.5 text-black text-lg bg-[#edf3ff]">
                    제이엠테크
                </div>
            </div>

            <!-- 메인 메뉴 -->
            <nav class="flex flex-col mt-2 !py-4">
                <div class="accordion" style="min-height: 100vh; height:100%;"
                    position: sticky;
                    top: 0px;
                    height: 100vh;">
                    <?

                    foreach ($menus as $code1 => $items1) {

                        if ($code1 != $top_menu_code) {
                            continue;
                        }

                        $active_main = $top_menu_code == $code1 ? 'active_main' : '';
                        $active_sub_menu = $top_menu_code == $code1 ? 'show' : 'hide';

                        $active_sub_menu2 = !empty($items1['sub'][$sub_menu_code]) ? 'active_sub' : '';

                    ?>
                        <div class="mb-[12px] !space-y-4 !pb-[14px] p-0 border-bttom-menu">

                            <?
                            if (!empty($items1['sub'])) {
                            ?>

                                <div class="mt-[10px] !space-y-6 sub-menu-main-div2 <?= $active_sub_menu ?>">
                                    <?
                                    foreach ($items1['sub'] as $code2 => $items2) {

                                        $중제목 = !empty($items2['sub']) ? 'mid_class' : '';

                                        $target = !empty($items2['target']) ? $items2['target'] : '_self';

                                        $active_sub_menu2 = $top_menu_code == $code1 && $sub_menu_code == $code2 ? 'active-sub-menu' : '';

                                    ?>
                                        <div class="mt-[10px] sub-menu-div1 <?= $중제목 ?>">

                                            <p class="!pl-[16px] !text-[14px] font-semibold flex items-center !my-2 <?= $active_sub_menu2 ?>">
                                                <span class="ml-[4px]">
                                                    <?= $items2['name'] ?>
                                                </span>
                                            </p>

                                            <?
                                            if ($중제목) {
                                            ?>

                                                <div class="sub-menu-main-div3">
                                                    <?
                                                    foreach ($items2['sub'] as $code3 => $items3) {

                                                        $active_sub_menu3 = $top_menu_code == $code1 && $sub_menu_code == $code3 ? 'active-sub-menu' : '';
                                                    ?>
                                                        <a href="<?= $items3['path'] ?>"
                                                            class="
                                                            !pl-[24px] !py-[8px] 
                                                            <?= $active_sub_menu3 ? '!bg-white !text-black' : 'hover:!bg-gray-800 transition transition-colors duration-100' ?> 
                                                            text-[13px] sub-menu-div1 flex items-center">
                                                            <img class="w-[5px] h-[8px] !mr-2 !mt-1" src="https://gamemarket.kr/comm/bkoff/images/common/ic_arrow.gif" alt="화살표">
                                                            <span>
                                                                <?= $items3['name'] ?>
                                                            </span>
                                                        </a>
                                                    <?
                                                    }
                                                    ?>
                                                </div>
                                            <?
                                            }
                                            ?>
                                        </div>
                                    <?
                                    }
                                    ?>
                                </div>
                            <?
                            }
                            ?>


                        </div>
                    <?
                    }
                    ?>
                </div>
            </nav>

        </nav>

        <div class="flex flex-col flex-1 min-w-[1120px]">

            <header class="!flex items-center justify-between w-full bg-[#558ad9] text-white font-bold h-[62px]">

                <div class="!flex !h-full">
                    <!-- 판매 -->
                    <div onclick="window.location.href = '/'" class="cursor-pointer flex items-center gap-2 !px-4 py-2 menu-active">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                        </svg>
                        <span>판매</span>
                    </div>

                    <!-- 구매 -->
                    <div onclick="window.location.href = '/purchase'" class="cursor-pointer flex items-center gap-2 !px-4 py-2 hover:bg-[#3d7ac0] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart-icon lucide-shopping-cart">
                            <circle cx="8" cy="21" r="1" />
                            <circle cx="19" cy="21" r="1" />
                            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                        </svg>
                        <span>구매</span>
                    </div>

                    <div class="w-px bg-white/30 my-2"></div>

                    <div onclick="window.location.href = '/setting'" class="cursor-pointer flex items-center gap-2 !px-4 py-2 hover:bg-[#3d7ac0] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings-icon lucide-settings">
                            <path d="M9.671 4.136a2.34 2.34 0 0 1 4.659 0 2.34 2.34 0 0 0 3.319 1.915 2.34 2.34 0 0 1 2.33 4.033 2.34 2.34 0 0 0 0 3.831 2.34 2.34 0 0 1-2.33 4.033 2.34 2.34 0 0 0-3.319 1.915 2.34 2.34 0 0 1-4.659 0 2.34 2.34 0 0 0-3.32-1.915 2.34 2.34 0 0 1-2.33-4.033 2.34 2.34 0 0 0 0-3.831A2.34 2.34 0 0 1 6.35 6.051a2.34 2.34 0 0 0 3.319-1.915" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>

                        <span>설정</span>
                    </div>

                    <div class="w-px bg-white/30 my-2"></div>

                    <!-- 전자결제 -->
                    <div onclick="window.location.href = '/contract'" class="cursor-pointer flex items-center gap-2 !px-4 py-2 hover:bg-[#3d7ac0] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                        </svg>
                        <span>전자결제</span>
                    </div>
                </div>

                <div class="!mr-4">
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button">
                            <img class="w-[32px] rounded-sm cursor-pointer" src="/assets/app_hyup/images/default_profile.png" alt="기본이미지">
                        </div>
                        <ul
                            tabindex="-1"
                            class="!p-4 !min-w-[280px] !bg-white !mt-2 items-center justify-center font-sans menu dropdown-content rounded-box z-1 mt-4 w-52 p-2 shadow-sm ">
                            <!-- 프로필 이미지 -->
                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center !mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.67 0 8 1.34 8 4v4H4v-4c0-2.66 5.33-4 8-4z" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>

                            <!-- 이름 -->
                            <p class="text-gray-800 text-base font-medium">
                                <?= $login_user['name'] ?? '' ?>
                            </p>

                            <!-- 이메일 -->
                            <p class="text-gray-500 text-sm !mb-4">
                                <?= $login_user['user_id'] ?? '' ?>
                            </p>

                            <!-- 내정보수정 -->
                            <button class="flex items-center gap-1 text-gray-700 hover:text-blue-600 text-sm !mb-2">
                                내정보수정
                            </button>

                            <!-- 환경설정 -->
                            <button class="flex items-center gap-1 text-gray-700 hover:text-blue-600 text-sm !mb-5">
                                환경설정
                            </button>

                            <!-- 로그아웃 버튼 -->
                            <button onclick="window.location.href = '/login/logout'" class="w-full btn-sm text-white py-2 rounded-md transition">
                                로그아웃
                            </button>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="flex-1 !p-7">
                <?= $content ?>
            </main>
        </div>

    </div>

    <script>
        AOS.init({
            once: true, // ✅ 한 번만 애니메이션 실행
            duration: 1000,
            easing: 'ease-out-cubic',
        });

        var swiper = new Swiper(".mySwiper", {
            direction: "vertical",
            loop: true,
            speed: 800,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
                // 좀 느리게
            },
            pagination: {
                clickable: false,
            },
        });
    </script>
    <!-- 호출 -->
    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>
    <script>
        MicroModal.init();
    </script>
    <script src="/assets/app_hyup/common/cookie.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/cookie.js') ?>"></script>
    <script src="/assets/app_hyup/common/mask.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/mask.js') ?>"></script>
    <script src="/assets/app_hyup/common/common.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/common.js') ?>"></script>
    <script src="/assets/app_hyup/common/pwa.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/pwa.js') ?>"></script>
    <script src="/assets/app_hyup/common/alert.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/alert.js') ?>"></script>
    <script src="/assets/app_hyup/common/customConfirm.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/customConfirm.js') ?>"></script>
    <script src="/assets/app_hyup/common/transition.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/transition.js') ?>"></script>
    <script src="/assets/app_hyup/common/toast.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/toast.js') ?>"></script>

</body>

</html>