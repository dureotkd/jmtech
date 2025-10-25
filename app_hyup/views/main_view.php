<?php
$current_path = $_SERVER['REQUEST_URI'];

$menuItems = unserialize(MENU);

$PATH_INFO = $_SERVER['PATH_INFO'] ?? '';
$DEFAULT_PATH = $_SERVER['PATH_INFO'] ?? '/brand';
$currentMenuIndex = null;
$currentMenu = null;

foreach ($menuItems as $index => $menuItem) {

    $urls = explode('/', $menuItem['url']);
    $front_url = $urls[count($urls) - 1];

    if (strstr($DEFAULT_PATH, $front_url)) {
        $currentMenuIndex = $index;
        break;
    }
}

if ($currentMenuIndex !== null) { // ✅ 수정 포인트
    $currentMenu = $menuItems[$currentMenuIndex]['items'];
}
?>


<script>
    var swiper2 = new Swiper(".mySwiper2", {
        loop: true,
        speed: 800,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        effect: "fade", // ✅ 페이드 효과
        fadeEffect: {
            crossFade: true, // 두 슬라이드가 겹치면서 자연스럽게 전환
        },
    });

    new Swiper(".main-swiper", {
        direction: "vertical", // 세로 스크롤
        slidesPerView: 1, // 한 번에 한 페이지
        mousewheel: true, // 마우스 휠로 이동
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        speed: 800, // 전환 속도 (ms)
    });


    const my_modal_2 = document.getElementById('my_modal_2');
    const my_modal_3 = document.getElementById('my_modal_3');

    $(document).ready(function() {

        setTimeout(() => {

            if (my_modal_2) {
                my_modal_2.showModal();
            }
            if (my_modal_3) {
                my_modal_3.showModal();
            }

        }, 400);


    });

    $(".group").hover(
        function() {
            $(this).find(".info-box").css("transform", "translateY(0%)");
        },
        function() {
            $(this).find(".info-box").css("transform", "translateY(100%)");
        }
    );

    function go_product_pay(e, id) {
        // 이벤트 버블링 안되게
        e.stopPropagation();
        //   fadeOutButton(`/product/order?product_id=${id}&quantity=1`);

        addCart(id, 1)

    }

    function handle_store_code() {
        const store_code = $('input[name="store_code"]').val().trim();

        $.ajax({
            type: "POST",
            url: "/main/init_store_code",
            data: {
                store_code: store_code
            },
            dataType: "json",
            success: function(response) {
                alert(response.msg);
                if (response.ok) {
                    const my_modal_3 = document.getElementById('my_modal_3');
                    my_modal_3.close();
                    fadeOutReload();
                }
            }
        });
    }

    function 총판코드다신안보기() {

        setCookie('store_code_view', 'N', 30);
        const my_modal_3 = document.getElementById('my_modal_3');
        my_modal_3.close();
    }

    function go_product_detail(e, id) {

        // 이벤트 버블링 안되게
        e.stopPropagation();
        fadeOutButton(`/product?id=${id}`);
    }

    function handle_reset_pw_form(e) {
        e.preventDefault();

        const target = $(e.target);
        const serial = target.serialize();

        $.ajax({
            type: "POST",
            url: "/login/reset_password",
            data: serial,
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    my_modal_1.close()
                    setTimeout(() => {
                        alert('비밀번호가 변경되었습니다.')
                    }, 400);

                } else {
                    $("#pw_reset_valid").text(response.data)
                }

            }
        });
    }

    function closeTopBanner() {
        const $banner = $("#top_banner");

        $banner
            .css("overflow", "hidden")
            .animate({
                    height: 0,
                    paddingTop: 0,
                    paddingBottom: 0,
                    marginTop: 0,
                    marginBottom: 0,
                    opacity: 0,
                },
                300,
                function() {
                    setCookie("top_banner_closed", 1, 7); // 쿠키 설정 (7일 동안 유지)
                    $(this).remove(); // 필요 없으면 생략 가능
                }
            );
    }
</script>