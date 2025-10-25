<!--
  This example requires updating your template:

  ```
  <html class="h-full bg-white">
  <body class="h-full">
  ```
-->
<div class="flex w-full min-h-full flex-col justify-center items-center px-6 py-12 lg:px-8">
    <div class="flex flex-col items-center justify-center sm:mx-auto sm:w-full sm:max-w-sm">
        <img class="mx-auto h-24 w-auto" src="/assets/app_hyup/images/logo.png" alt="Your Company" />
        <h2 class="!mt-5 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Sign in to your account (ADMIN PAGE)</h2>
    </div>

    <div class="!mt-10 w-full sm:max-w-sm sm:w-full !px-12">
        <form onsubmit="handle_login(event);" class="!space-y-6 w-full" action="#" method="POST">
            <div>
                <label for="user_id" class="block text-sm/6 !font-semibold !text-gray-900">아이디</label>
                <div class="mt-2">
                    <input type="text" name="user_id" id="user_id" placeholder="아이디를 입력해주세요" autocomplete="user_id" required class="block w-full bg-white px-3 py-2 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                </div>
            </div>

            <div>
                <label for="password" class="block text-sm/6 !font-semibold !text-gray-900">비밀번호</label>
                <div class="mt-2">
                    <input type="password" name="password" id="password" placeholder="아이디를 입력해주세요" autocomplete="current-password" required class="block w-full bg-white px-3 py-2 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
                </div>
            </div>

            <div>
                <button type="submit" class="flex w-full justify-center bg-indigo-600 px-3 py-2 text-sm/6 font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
            </div>
        </form>
    </div>
</div>

<script>
    function handle_login(e) {
        e.preventDefault(); // 폼 제출 방지

        const user_id = $('#user_id').val();
        const user_pw = $('#password').val();

        const data = {
            user_id: user_id,
            user_pw: user_pw
        };
        $.ajax({
            url: '/admin/login/login_proc',
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(response) {
                if (response.ok) {
                    window.location.href = '/admin/setting';
                } else {
                    alert(response.msg);
                }
            },
            error: function() {
                alert('로그인 처리 중 오류가 발생했습니다. 다시 시도해주세요.');
            }
        });
    }
</script>