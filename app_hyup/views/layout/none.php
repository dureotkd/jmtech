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

    <meta name="keywords" content="제이엠테크, 이눌린, 스위틀린, 무설탕 감미료, 혈당관리, 장 건강, 프리바이오틱스, 식이섬유, 건강 습관, 다이어트 설탕 대체품">
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

    <link data-n-head="1" rel="icon" type="image/x-icon" href="/assets/app_hyup/images/favicon.ico">
    <link rel="canonical" href="https://mosihealth.com/" />

    <!-- tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- pretendard CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretendard@1.3.8/dist/web/variable/pretendardvariable-dynamic-subset.css" />

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Swiper CSS -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

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


    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            background: #000;
        }

        .swiper {
            height: 100vh;
        }

        .swiper-slide {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2rem;
            position: relative;
        }

        /* 페이징 점 스타일 */
        .swiper-pagination-bullet {
            background: #fff !important;
            opacity: 0.5;
        }

        .swiper-pagination-bullet-active {
            opacity: 1;
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-migrate-3.5.2.js" integrity="sha256-ThFcNr/v1xKVt5cmolJIauUHvtXFOwwqiTP7IbgP8EU=" crossorigin="anonymous"></script>

    <!-- AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <!-- Swiper -->
    <?= $content ?>
    <!-- Swiper JS -->

    <script src="/assets/app_hyup/common/cookie.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/cookie.js') ?>"></script>
    <script src="/assets/app_hyup/common/mask.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/mask.js') ?>"></script>
    <script src="/assets/app_hyup/common/common.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/common.js') ?>"></script>
    <script src="/assets/app_hyup/common/channel.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/channel.js') ?>"></script>
    <script src="/assets/app_hyup/common/alert.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/alert.js') ?>"></script>
    <script src="/assets/app_hyup/common/customConfirm.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/customConfirm.js') ?>"></script>
    <script src="/assets/app_hyup/common/transition.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/transition.js') ?>"></script>
    <script src="/assets/app_hyup/common/toast.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/toast.js') ?>"></script>

    <script>
        AOS.init({
            once: true, // ✅ 한 번만 애니메이션 실행
            duration: 1000,
            easing: 'ease-out-cubic',
        });
    </script>

</body>

</html>