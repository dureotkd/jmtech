<div class="max-w-md !mx-auto !px-4">
    <input type="hidden" name="step" value="1" />

    <div class="!pt-4 w-full h-full !space-y-6 text-center">

        <div class="w-full flex justify-between items-center">
            <button type="button" onclick="fadeOutButton('/');">
                <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7"></path>
                    <path d="M19 12H5"></path>
                </svg>
            </button>

            <a href="/">
                <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
                    <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                </svg>
            </a>
        </div>

        <!-- 타이틀 -->
        <h1 class="!text-2xl font-bold text-center !mb-8">회원 가입</h1>

        <!-- 입력 폼 -->
        <?
        if (empty($agree)) {
        ?>
            <!-- 단계 표시 -->
            <div class="flex justify-center text-sm text-gray-500 !mb-8 !space-x-2">
                <span class="font-bold text-black">1. 약관동의</span>
                <span>&gt;</span>
                <span>2. 정보입력</span>
            </div>

            <!-- 약관 동의 -->
            <form class="step-1 !space-y-4 border-t border-black pt-6">
                <!-- 전체 동의 -->
                <div class="flex items-start gap-3">
                    <input type="checkbox" id="all-agree" class="accent-black mt-1" />
                    <div>
                        <label for="all-agree" class="font-semibold text-black">모든 약관을 확인하고 전체 동의합니다.</label>
                    </div>
                </div>

                <hr />

                <!-- 개별 약관 -->
                <div class="!space-y-4 text-sm">
                    <!-- 이용약관 -->
                    <div class="flex justify-between items-start">
                        <label class="flex items-start gap-2">
                            <input type="checkbox" class="accent-black mt-1" />
                            <span>이용약관 동의 <span class="text-gray-500">(필수)</span></span>
                        </label>
                        <svg onclick="openPopup('/agreement?sec=terms',600,600)" class="!text-sm !text-gray-600" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </div>

                    <!-- 개인정보 수집 -->
                    <div class="flex justify-between items-start">
                        <label class="flex items-start gap-2">
                            <input type="checkbox" class="accent-black mt-1" />
                            <span>개인정보 수집 및 이용 동의 <span class="text-gray-500">(필수)</span></span>
                        </label>
                        <svg onclick="openPopup('/agreement?sec=service',600,600)" class="!text-sm !text-gray-600" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down-icon lucide-chevron-down">
                            <path d="m6 9 6 6 6-6" />
                        </svg>
                    </div>

                    <!-- SMS/이메일 수신 -->
                    <div class="flex flex-wrap items-center gap-4 pl-6 text-gray-700">
                        <label class="flex items-center gap-2">
                            <input name="market_agree" type="checkbox" class="accent-black" />
                            <span>SMS 수신 동의 <span class="text-gray-500">(선택)</span></span>
                        </label>
                    </div>
                </div>

                <!-- 버튼 -->
                <div class="flex justify-between !mt-10 !gap-4">
                    <button onclick="fadeOutButton('/');" type="button" class="w-1/2 border border-gray-300 py-3 text-sm hover:bg-gray-50">취소</button>
                    <button type="button" onclick="handle_next_page(event);" class="w-1/2 btn-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900">다음</button>
                </div>
            </form>

        <?
        } else {
        ?>
            <!-- 단계 표시 -->
            <div class="flex justify-center text-sm text-gray-500 !mb-8 !space-x-2">
                <span>1. 약관동의</span>
                <span>&gt;</span>
                <span class="font-bold text-black">2. 정보입력</span>
            </div>

            <form onsubmit="handle_join_form(event);" id="join-form" class="step-2 border-t border-black text-sm !space-y-6">

                <div class="">
                    <input class="w-full !border !border-gray-200  px-4 py-3 text-sm !placeholder-gray-400"
                        placeholder="아이디"
                        type="text"
                        value=""
                        name="user_id">
                    <input
                        type="password"
                        name="password"
                        placeholder="비밀번호"
                        style="border-top: none;"
                        class="w-full border border-gray-200  px-4 py-3 text-sm placeholder-gray-400"
                        value="" />
                    <input
                        type="password"
                        name="repassword"
                        placeholder="비밀번호 확인"
                        style="border-top: none;"
                        class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400"
                        value="" />
                </div>


                <div class="flex gap-4">
                    <div class="w-full">
                        <label class="text-sm text-left font-semibold text-gray-800 !mb-2 block">이름 <span class="text-red-600">*</span></label>
                        <input
                            type="text"
                            name="name"
                            placeholder="이름"
                            class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400"
                            value="" />
                    </div>

                    <div class="w-full">
                        <label class="text-sm text-left font-semibold text-gray-800 !mb-2 block">연락처 <span class="text-red-600">*</span></label>
                        <input
                            type="text"
                            name="phone"
                            placeholder="연락처"
                            oninput="phoneNumberMask(this)"
                            class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400"
                            value="" />
                    </div>
                </div>

                <div class="flex items-end gap-4">

                    <div class="w-full">
                        <label class="text-sm text-left font-semibold text-gray-800 !mb-2 block">이메일 <span class="text-red-600">*</span></label>
                        <input
                            type="text"
                            name="email"
                            placeholder="이메일"
                            class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400"
                            value="" />
                    </div>

                    <div class="w-full">
                        <select name="email_domain"
                            class="w-full border border-gray-200 rounded !px-4 !py-3 text-gray-700">
                            <option value="naver.com">@naver.com</option>
                            <option value="daum.net">@daum.net</option>
                            <option value="gmail.com">@gmail.com</option>
                        </select>
                    </div>
                </div>

                <!-- 버튼 -->
                <div class="flex justify-between mt-8 gap-4">
                    <button type="button" onclick="fadeOutButton('/signup')" class="w-1/2 border border-gray-300 py-3 text-sm hover:bg-gray-50">이전</button>
                    <button type="submit" data-style="slide-up" id="submit_btn" class="w-1/2 ld-over-full btn-primary-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900 flex justify-center items-center">
                        가입하기
                    </button>
                </div>
            </form>

        <?
        }
        ?>

    </div>


</div>

<!-- Spin.js (Ladda 내부 의존) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>

<!-- Ladda JS -->
<script src="https://lab.hakim.se/ladda/dist/ladda.min.js"></script>

<script>
    $("#all-agree").on("change", function() {
        const isChecked = $(this).is(":checked");
        $("input[type='checkbox']").prop("checked", isChecked);
    });

    function handle_join_form(e) {
        e.preventDefault(); // 기본 폼 제출 동작 방지

        const submit_btn = $("#submit_btn");
        submit_btn.attr("disabled", true);

        let serial = $("#join-form").serialize();
        const market_agree = $("input[name='market_agree']").is(":checked");
        serial += `&market_agree=${market_agree}`;

        $.ajax({
            type: "POST",
            url: "/signup/signup_proc",
            data: serial,
            dataType: "json",
            success: function({
                ok,
                msg
            }) {
                submit_btn.attr("disabled", false);

                if (ok) {
                    alert(msg).then(() => {
                        fadeOutButton('/login');
                    });
                    return;
                } else {
                    alert(msg);
                    return;
                }

            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("회원 가입 중 오류가 발생했습니다. 다시 시도해주세요.");

                submit_btn.attr("disabled", false);
            }
        });
    }


    function handle_next_page(e) {
        fadeOutButton('/signup?agree=1');
    }
</script>