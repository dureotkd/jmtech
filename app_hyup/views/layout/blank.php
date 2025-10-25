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
    <meta name="description" content="">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:image" content="/assets/app_hyup/images/logo.jpg">
    <!-- <meta property="og:url" content="https://mosihealth.com"> -->

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
    <meta name="theme-color" content="#ffffff">

    <!-- <link rel="canonical" href="https://mosihealth.com/" /> -->

    <!-- tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

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

    <link rel="stylesheet" href="/assets/app_hyup/common/loading.css?v=<?= $datetime ?>" />

    <!-- Ladda CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda-themeless.min.css" />

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

</head>

<body>
    <div id="page-progress-bar"></div>

    <?
    if (!empty($pop_header)) {
    ?>
        <h1 class="!text-md bg-[#4b5563] !text-white !font-sans  !px-4 !py-2 !mb-4">
            <?= $pop_header ?>
        </h1>
    <?
    }
    ?>

    <!-- <div class="content !mx-auto max-w-[768px] h-full min-h-screen"> -->
    <?= $content ?>
    <!-- </div> -->

    <div class="overlay" id="loadingOverlay">
        <div class="overlay-text">
            <img
                class="w-16"
                src="/assets/app_hyup/images/loading.gif" alt="">
        </div>
    </div>

    <script src="/assets/app_hyup/common/customAlert.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/customAlert.js') ?>"></script>
    <script src="/assets/app_hyup/common/cookie.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/cookie.js') ?>"></script>
    <script src="/assets/app_hyup/common/mask.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/mask.js') ?>"></script>
    <script src="/assets/app_hyup/common/common.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/common.js') ?>"></script>
    <script src="/assets/app_hyup/common/pwa.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/pwa.js') ?>"></script>
    <script src="/assets/app_hyup/common/channel.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/channel.js') ?>"></script>
    <script src="/assets/app_hyup/common/customConfirm.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/customConfirm.js') ?>"></script>
    <script src="/assets/app_hyup/common/transition.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/transition.js') ?>"></script>
    <script src="/assets/app_hyup/common/toast.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/toast.js') ?>"></script>
    <script src="/assets/app_hyup/common/loading.js?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/common/loading.js') ?>"></script>

</body>

</html>