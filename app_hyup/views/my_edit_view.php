<form id="form1" onsubmit="handle_modify_form(event)" class=" text-center flex flex-col relative pb-[60px] min-h-screen !px-4 !py-3">
    <div class="">

        <!-- 아이디 입력 -->
        <div class="mb-6">
            <label class="block !text-sm text-left font-semibold text-gray-800 !mb-2">이메일</label>
            <input
                type="email"
                name="email"
                placeholder="이메일 주소 입력"
                class="w-full border rounded px-4 py-3 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>

        <!-- 비밀번호 -->
        <div class="!mt-4">
            <label class="block !text-sm font-semibold text-gray-800 text-left !mb-2">비밀번호</label>
            <div class="relative">
                <input
                    type="password"
                    name="password"
                    placeholder="8자리 이상 영문,숫자,특수문자 포함"
                    class="w-full border rounded px-4 py-3 pr-10 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
        </div>

        <!-- 비밀번호 확인 -->
        <div class="mb-8 !mt-2">
            <div class="relative">
                <input
                    type="password"
                    name="repassword"
                    placeholder="비밀번호 확인"
                    class="w-full border rounded px-4 py-3 pr-10 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
        </div>

    </div>

    <div class="w-full !mt-4">

        <!-- 다음 버튼 -->
        <button class="w-full mt-4 cursor-pointer bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 rounded-md font-semibold shadow transition-all duration-300">
            회원정보 수정
        </button>

    </div>

</form>

<script>
    function handle_modify_form() {

        event.preventDefault(); // 기본 폼 제출 동작 방지

        const serial = $("#form1").serialize();

        $.ajax({
            type: "POST",
            url: "/my/update_user",
            data: serial,
            dataType: "json",
            success: function({
                ok,
                msg
            }) {

                // if (ok) {
                //     window.location.href = "/";
                // }

            }
        });
    }

    function all_check(event) {
        const target = $(event.target);

        if (target.is(':checked')) {
            // 전체 동의 체크박스가 체크된 경우
            $('#cbx-1, #cbx-2, #cbx-3').prop('checked', true);
        } else {
            // 전체 동의 체크박스가 체크 해제된 경우
            $('#cbx-1, #cbx-2, #cbx-3').prop('checked', false);
        }
    }
</script>