<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <title>Hi Home | 단기임대·한달살기 숙소 예약</title>

    <meta name="keywords" content="ANANANA">
    <meta name="description" content="ANANANA">
    <meta property="og:title" content="ANANANA">
    <meta property="og:description" content="ANANANA">
    <meta property="og:image" content="">
    <meta property="og:url" content="">

    <link data-n-head="1" rel="icon" type="image/x-icon" href="/assets/app_hyup/images/favicon.ico">

    <!-- tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- pretendard CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pretendard@1.3.8/dist/web/variable/pretendardvariable-dynamic-subset.css" />

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@solb/bottom-sheet/style/style.css" />

    <?
    $datetime = time();
    ?>

    <!-- Summernote CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/assets/app_hyup/common/reset.css" />
    <link rel="stylesheet" href="/assets/app_hyup/common/base.css?v=<?= $datetime ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-migrate-3.5.2.js" integrity="sha256-ThFcNr/v1xKVt5cmolJIauUHvtXFOwwqiTP7IbgP8EU=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tippy.js/6.3.7/tippy-bundle.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>

    <script src="/assets/app_hyup/common/ajaxsetup.js"></script>
    <script src="/assets/app_hyup/common/header.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@solb/bottom-sheet/dist/index.min.js" defer></script>

</head>

<body>
    <div id="page-progress-bar"></div>

    <div class="content !mx-auto max-w-[768px] relative h-full min-h-screen text-center flex flex-col relative pb-[60px]">

        <?
        if (!$onlyConent) {
        ?>
            <div class="!p-4 flex justify-center items-center">
                <img onclick="window.location.href = '/'" class="w-[100px] cursor-pointer" src="/assets/app_hyup/images/hihome-logo.jpg" alt="">
            </div>
        <?
        }
        ?>

        <div>
            <?= $content ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.21/lodash.min.js"></script>
    <script src="/assets/app_hyup/common/common.js"></script>

    <!-- <script>
        function go_channer_talk() {
            ChannelIO('showMessenger');
        }

        (function() {
            var w = window;
            if (w.ChannelIO) {
                return w.console.error("ChannelIO script included twice.");
            }
            var ch = function() {
                ch.c(arguments);
            };
            ch.q = [];
            ch.c = function(args) {
                ch.q.push(args);
            };
            w.ChannelIO = ch;

            function l() {
                if (w.ChannelIOInitialized) {
                    return;
                }
                w.ChannelIOInitialized = true;
                var s = document.createElement("script");
                s.type = "text/javascript";
                s.async = true;
                s.src = "https://cdn.channel.io/plugin/ch-plugin-web.js";
                var x = document.getElementsByTagName("script")[0];
                if (x.parentNode) {
                    x.parentNode.insertBefore(s, x);
                }
            }
            if (document.readyState === "complete") {
                l();
            } else {
                w.addEventListener("DOMContentLoaded", l);
                w.addEventListener("load", l);
            }
        })();

        ChannelIO('boot', {
            "pluginKey": "4482735a-a4c8-4be2-a5e2-7543cad25582"
        });
    </script> -->

</body>

</html>