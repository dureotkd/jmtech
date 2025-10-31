<style>
    ::-ms-reveal,
    ::-ms-clear {
        display: none !important;
    }
</style>

<div class="max-w-md !mx-auto !px-4">

    <input type="hidden" name="sec" value="<?= $sec ?>">

    <!-- 로그인 영역 -->
    <div class="!pt-14 w-full h-full !space-y-6 text-center">

        <!-- 중앙 로고 -->
        <div class="!my-12 flex justify-center items-center">
            <img class="object-cover h-14" src="/assets/app_hyup/images/logo.png" alt="로고">
        </div>

        <div class="!w-full flex radio-inputs">
            <label class="radio">
                <input onchange="handle_login_tab(event);" id="gi" type="radio" name="radio" value="내부 IP" checked>
                <span class="name">내부 IP</span>
            </label>
            <label class="radio">
                <input onchange="handle_login_tab(event);" id="bi" type="radio" value="인증번호" name="radio">
                <span class="name">인증번호</span>
            </label>
        </div>

        <!-- 입력 필드 -->
        <form onsubmit="handle_login_form(event);" class="user-box space-y-4">
            <input
                type="text"
                name="user_id"
                placeholder="아이디"
                class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none" />

            <div class="relative !mb-4">
                <input
                    type="password"
                    name="password"
                    placeholder="비밀번호"
                    class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none" />
                <svg
                    onclick="toggle_view_pw(event);"
                    id="togglePassword"
                    class="absolute cursor-pointer right-4 top-1/2 transform -translate-y-1/2 lucide lucide-eye-icon lucide-eye"
                    xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
            </div>

            <!-- 로그인 버튼 -->
            <button type="submit" class="w-full btn-sm bg-black text-white py-3 font-semibold hover:bg-gray-900 transition">
                로그인
            </button>

        </form>

        <form onsubmit="handle_non_member_form(event);" class="non-user-box">
            <div class="non-user-box hidden space-y-4">
                <input
                    type="number"
                    placeholder="주문번호"
                    class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none" />
                <input
                    type="text"
                    placeholder="연락처"
                    oninput="phoneNumberMask(this)"
                    class="w-full phone border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none" />

                <!--  버튼 -->
                <button class="w-full btn-sm bg-black text-white py-3 font-semibold hover:bg-gray-900 transition">
                    비회원 조회
                </button>
            </div>
        </form>


        <div class="!text-sm text-gray-600 space-x-2">
            <span class="lg:hidden inline-block cursor-pointer" onclick="fadeOutButton('/signup');">회원가입</span>
            <span class="lg:hidden inline-block">|</span>
            <span class="cursor-pointer" data-micromodal-trigger="modal-1" onclick="show_findid_modal();">아이디 찾기</span>
            <span>|</span>
            <span class="cursor-pointer" onclick="show_findpw_modal();">비밀번호 찾기</span>
        </div>

        <div id="naver_id_login" class="hidden">
        </div>

        <div class="install-block lg:block hidden !border !border-gray-200 !p-6 text-center flex flex-col !items-center !justify-center !space-y-2">
            <p class="text-base font-semibold text-gray-900">더 빠른 접근을 원하시나요?</p>
            <p class="!text-sm text-gray-500">바로가기를 추가하면 ERP를 손쉽게 열 수 있습니다.</p>
            <div class="flex flex-col items-center">
                <button id="install_pwa" type="button" class="w-fit mt-3 px-6 py-2 !text-sm border border-black text-black hover:bg-gray-100 transition">
                    바로가기 만들기
                </button>
            </div>
        </div>

    </div>
</div>


<dialog id="my_modal_1" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button onclick="close_modal_1();" class="text-black !text-lg focus:outline-none font-bold absolute right-6 top-2">✕</button>
        </form>

        <form onsubmit="handle_findid_form(event);" class="find-id-step-1 !mt-12 w-full rounded-lg !p-6 relative">

            <div class="!space-y-3 !mb-4">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="find-method" checked />
                    <span class="text-sm">전화번호 찾기</span>
                </label>

                <input
                    type="text"
                    name="phone"
                    placeholder="전화번호"
                    oninput="handle_phone_format(event);"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none" />

                <p id="find_id_error" class="w-full border !text-sm text-red-500">
                </p>
            </div>

            <button
                type="submit"
                class="w-full btn-sm font-medium py-2 rounded">
                아이디 찾기
            </button>
        </form>

        <div class="find-id-step-2 hidden bg-white w-full !mt-12 !space-y-3 !mb-4 rounded-lg !p-6 relative">
            <div class="text-center mb-4">
                <p class="text-lg font-semibold text-gray-800">입력하신 정보와 일치하는 계정을 발견했습니다.</p>
                <p class="find-id text-sm text-gray-600 mt-2"></p>
            </div>

            <div class="flex gap-2 justify-center items-center mb-4">
                <button
                    onclick="my_modal_1.close(); my_modal_2.showModal();"
                    class="w-1/2 btn-sm font-medium py-2 rounded">
                    비밀번호 재설정
                </button>

                <button
                    class="w-1/2 btn-primary-sm font-medium py-2 rounded">
                    로그인
                </button>
            </div>
        </div>
    </div>
</dialog>

<dialog id="my_modal_2" class="modal">
    <div class="modal-box">
        <form method="dialog">
            <button onclick="close_modal_2();" class="text-black !text-lg focus:outline-none font-bold absolute right-6 top-2">✕</button>
        </form>

        <form onsubmit="handle_findpw_form(event);" class="find-pw-step-1 bg-white w-full !mt-12 rounded-lg !p-6 relative">

            <div class="!space-y-3 !mb-4">
                <label class="flex items-center space-x-2">
                    <input type="radio" name="find-method" checked />
                    <span class="text-sm">가입한 아이디로 찾기</span>
                </label>

                <input
                    type="text"
                    name="user_id"
                    placeholder="아이디"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-[#a25e2c]" />

                <p id="find_pw_error" class="w-full border !text-sm text-red-500">
                </p>

            </div>

            <button
                type="submit"
                class="w-full btn-sm font-medium py-2 rounded">
                비밀번호 초기화
            </button>
        </form>

        <div class="find-pw-step-2 hidden bg-white w-full !mt-12 !space-y-3 !mb-4 rounded-lg !p-6 relative">
            <p class="text-lg text-center font-semibold text-gray-800">
                비밀번호가 초기화 되었습니다.
            </p>
        </div>

    </div>
</dialog>
<!-- 
<div class="modal micromodal-slide" id="modal-2" aria-hidden="true">
    <div class="modal__overlay" tabindex="-1">
        <div class="modal__container">
            <header class="modal__header">
                <h2 class="text-lg font-semibold text-center mb-4">비밀번호 찾기</h2>
                <button data-micromodal-close aria-label="Close"></button>
            </header>
            <main class="modal__content">

                <form onsubmit="handle_findpw_form(event);" class="bg-white w-full max-w-sm rounded-lg p-6 relative">

                    <div class="space-y-3 mb-4">
                        <input
                            type="text"
                            name="user_id"
                            placeholder="아이디"
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-[#a25e2c]" />
                    </div>

                    <button
                        type="submit"
                        class="w-full border border-[#a25e2c] text-[#a25e2c] font-medium py-2 rounded hover:bg-[#a25e2c] hover:text-white transition">
                        비밀번호 재설정
                    </button>
                </form>

            </main>
        </div>
    </div>
</div> -->

<script type="text/javascript" src="https://static.nid.naver.com/js/naverLogin_implicit-1.0.3.js" charset="utf-8"></script>
<script src="https://developers.kakao.com/sdk/js/kakao.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sec = $("[name=sec]").val();
        $(`#${sec}`).trigger("click");
    });

    function toggle_view_pw(e) {
        const svg = $(e.currentTarget);
        const input = svg.siblings('input');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            svg.removeClass('lucide-eye').addClass('lucide-eye-off');
        } else {
            input.attr('type', 'password');
            svg.removeClass('lucide-eye-off').addClass('lucide-eye');
        }
    }

    const modal1 = document.getElementById('my_modal_1');
    const modal2 = document.getElementById('my_modal_2');

    // 닫힐 때 실행할 이벤트
    modal1.addEventListener('close', () => {
        console.log('모달이 닫혔습니다!');
        // 여기서 원하는 동작 수행
    });

    modal2.addEventListener('close', () => {
        console.log('모달이 닫혔습니다!');
        // 여기서 원하는 동작 수행
    });

    function close_modal_1() {
        setTimeout(() => {
            $(".find-id-step-2").hide();
            $(".find-id-step-1").show();
            $(".find-id").text('');
            $("#find_id_error").text('');
        }, 500);
    }

    function close_modal_2() {
        setTimeout(() => {
            $(".find-pw-step-2").hide();
            $(".find-pw-step-1").show();
            $(".find-pw").text('');
            $("#find_pw_error").text('');
        }, 500);
    }

    function show_findid_modal() {

        my_modal_1.showModal()
    }

    function show_findpw_modal() {

        my_modal_2.showModal()
    }

    function naver_login() {

        $("#naver_id_login").trigger("click");
    }

    function go_signup_page() {

        fadeOutButton('/signup')
    }

    function handle_login_tab(event) {

        const target = $(event.target);
        const value = target.val();

        if (value === '기존 회원') {

            $(".user-box").show();
            $(".non-user-box").hide();
        } else if (value === '비회원 조회') {
            $(".user-box").hide();
            $(".non-user-box").show();
        }

    }

    function handle_findpw_form(e) {

        e.preventDefault(); // 기본 폼 제출 방지
        const target = $(e.target);
        const serial = target.serialize();

        $.ajax({
            type: "POST",
            url: "/login/find_password",
            data: serial,
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    my_modal_2.close()
                    alert(response.msg);
                } else {
                    $("#find_pw_error").text(response.data);
                }
            },
            error: function(xhr, status, error) {
                alert(JSON.parse(xhr.responseText).msg);
            }
        });
    }

    function handle_findid_form(e) {

        e.preventDefault(); // 기본 폼 제출 방지
        const target = $(e.target);
        const serial = target.serialize();

        $.ajax({
            type: "POST",
            url: "/login/find_id",
            data: serial,
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    $(".find-id").text(response.data);
                    $(".find-id-step-1").hide();
                    $(".find-id-step-2").show();
                } else {
                    $("#find_id_error").text(response.data);
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    text: JSON.parse(xhr.responseText).msg,
                    icon: "error",
                    confirmButtonText: "닫기",
                });
            }
        });

    }

    function handle_login_form(event) {
        event.preventDefault(); // 기본 폼 제출 방지q

        start_loading();

        const target = $(event.target);
        const serial = target.serialize();

        $.ajax({
            type: "POST",
            url: "/login/login_proc",
            data: serial,
            dataType: "json",
            success: function(response) {
                if (response.ok) {

                    window.location.href = '/sales/estimate';

                } else {
                    alert(response.msg);
                }
            },
            error: function(xhr, status, error) {
                alert(JSON.parse(xhr.responseText).msg);
            },
            complete: function() {
                stop_loading();
            }
        });
    }

    function open_date_search_form() {

        open_bottom_sheet('main-sheet-form');

    }

    function open_people_search_form() {

        open_bottom_sheet('main-sheet-form');

    }
</script>