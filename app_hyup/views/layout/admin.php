<!DOCTYPE html>
<html lang="ko">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta http-equiv="Content-Script-Type" content="text/javascript" />
    <meta http-equiv="Content-Style-Type" content="text/css" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />


    <!-- tailwind CSS -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

    <? if (count($css_files) > 0) : foreach ($css_files as $sc) : ?>
            <link rel="stylesheet" rel="stylesheet" href="<?= $sc ?>" />
    <? endforeach;
    endif; ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard/dist/web/variable/pretendardvariable.css" />
    <link rel="stylesheet" href="/assets/app_hyup/common/common.css" />
    <link
        rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/gh/loadingio/ldbutton@latest/dist/index.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' integrity='sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==' crossorigin='anonymous'></script>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="/assets/app_hyup/common/reset.css" />

    <!-- Spin.js (Ladda 내부 의존) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>

    <!-- Ladda JS -->
    <script src="https://lab.hakim.se/ladda/dist/ladda.min.js"></script>

    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' integrity='sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==' crossorigin='anonymous'></script>


    <title><?= $title ?></title>

    <style>
        @font-face {
            font-family: 'Pretendard Variable';
            font-weight: 45 920;
            font-style: normal;
            font-display: swap;
            src: local('Pretendard Variable'), url('./images/PretendardVariable.woff2') format('woff2-variations');
        }

        .active-sub-menu {
            color: #4040bb !important;
            text-decoration: underline !important;
            text-underline-offset: 7px !important;
        }

        label {
            color: #3b3b3b !important;
        }
    </style>

</head>


<body style="font-family: 'Pretendard Variable', sans-serif;">
    <?
    if (empty($blank)) {
    ?>
        <header class="xl:flex hidden !mx-auto !flex  !px-4 !py-4 items-center justify-between m-auto bg-[#fff]" style="border-bottom: 2px solid #f8f8f8;">
            <div class="flex flex-row gap-2 items-center">
                <span style="color:#669999;font-size:9pt;font-family:돋움;letter-spacing:-1px"><b><?= $manager['name'] ?? '' ?></b>님 방문을 환영합니다.</span>
                <a class="!text-sm" href="/login/logout" style="color:#336699;">로그아웃</a>
            </div>
        </header>
    <?
    }
    ?>


    <?
    if (!empty($blank)) {
    ?>
        <div class="h-screen flex justify-center items-center">
            <?= $content ?>
        </div>
    <?
    } else {
    ?>
        <main class="xl:flex-row flex flex-col-reverse w-full">
            <aside class="w-[220px] min-w-[220px] !space-y-8" style="background-color: #f8f8f8;">
                <div class="flex items-center justify-center !mt-8">
                    <img class="w-28 text-center" src="/assets/app_hyup/images/logo-Photoroom.png" alt="">
                </div>
                <div class="col p-0 container-left"><!-- left menu -->
                    <div class="accordion sticky top-0 h-screen mt-3">
                        <?
                        foreach ($menus as $code1 => $items1) {

                            $active_main = $top_menu_code == $code1 ? 'active_main' : '';
                            $active_sub_menu = $top_menu_code == $code1 ? 'show' : 'hide';

                            $active_sub_menu2 = !empty($items1['sub'][$sub_menu_code]) ? 'active_sub' : '';

                        ?>
                            <div class="mb-[12px] pl-[14px] pr-[14px] pb-[14px] p-0 border-bttom-menu">
                                <!-- <div class="flex justify-between main-menu-div1  <?= $active_main ?>" onclick="handle_main_menu(event)">
                                    <span class="!my-2 !mx-4">
                                        <div class="badge badge-primary !px-4 !py-2"> <?= $items1['name'] ?>
                                        </div>

                                    </span>
                                </div> -->

                                <?
                                if (!empty($items1['sub'])) {
                                ?>

                                    <div class="!mt-2 sub-menu-main-div2 <?= $active_sub_menu ?>" style="margin-left:6px;">
                                        <?
                                        foreach ($items1['sub'] as $code2 => $items2) {

                                            $중제목 = !empty($items2['sub']) ? 'mid_class' : '';

                                            $target = !empty($items2['target']) ? $items2['target'] : '_self';

                                            $active_sub_menu2 = $top_menu_code == $code1 && $sub_menu_code == $code2 ? 'active-sub-menu' : '';

                                        ?>
                                            <div class="mt-[10px] sub-menu-div1 <?= $중제목 ?>">

                                                <?
                                                if ($중제목) {
                                                ?>
                                                    <a href="<?= $items2['path'] ?>" rel="중제목 링크" class="!ml-6 !mb-4 flex items-center <?= $active_sub_menu2 ?>">
                                                        <img class="w-[5px] h-[8px]" src="https://gamemarket.kr/comm/bkoff/images/common/ic_arrow.gif" alt="화살표">
                                                        <span class="!ml-2">
                                                            <?= $items2['name'] ?>
                                                        </span>
                                                    </a>
                                                <?
                                                } else {
                                                ?>
                                                    <div class="mt-[14px] text-[13px] !ml-6 !mb-4 flex items-center">
                                                        <img class="w-[5px] h-[8px]" src="https://gamemarket.kr/comm/bkoff/images/common/ic_arrow.gif" alt="화살표">
                                                        <a class="!ml-2 <?= $active_sub_menu2 ?>" href="<?= $items2['path'] ?>" target="<?= $target ?>">
                                                            <?= $items2['name'] ?>
                                                        </a>
                                                    </div>
                                                <?
                                                }
                                                ?>

                                                <?
                                                if ($중제목) {
                                                ?>

                                                    <div class="ml-[12px] sub-menu-main-div3" style="margin-left:12px;">
                                                        <?
                                                        foreach ($items2['sub'] as $code3 => $items3) {

                                                            $active_sub_menu3 = $top_menu_code == $code1 && $sub_menu_code == $code3 ? 'active-sub-menu' : '';

                                                        ?>
                                                            <div class="!ml-7 !mt-4 mt-[14px] text-[13px] sub-menu-div1">

                                                                <a class="<?= $active_sub_menu3 ?>" href="<?= $items3['path'] ?>">
                                                                    - <?= $items3['name'] ?>
                                                                </a>
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
                        <?
                        }
                        ?>
                    </div>

            </aside>

            <div class="!p-8 w-full">
                <?= $content ?>
            </div>
        </main>
    <?
    }
    ?>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js' integrity='sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==' crossorigin='anonymous'></script>
    <script src="/assets/app_hyup/common/common.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

        })

        function handle_menu(e, type) {


            const target = $(e.target);

            if (!empty(target.children('.sub-menu-wrap').length)) {


                switch (type) {

                    case 'show':

                        target.children('.sub-menu-wrap').show();
                        break;

                    case 'hide':

                        target.children('.sub-menu-wrap').hide();
                        break;


                }
            }

        }
    </script>

    <? if (count($js_files) > 0) : foreach ($js_files as $sc) : ?>
            <script type="text/javascript" src="<?= $sc ?>"></script>
    <? endforeach;
    endif; ?>

    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="/assets/app_hyup/common/lamba.js"></script>
    <!-- from cdn -->
    <script src="https://unpkg.com/@material-tailwind/html@latest/scripts/dialog.js"></script>

</body>



</html>