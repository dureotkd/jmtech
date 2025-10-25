<section class="lg:!px-0 lg:!flex-row lg:gap-12 gap-4 flex-col !flex !px-6">
    <div class="lg:w-1/2">
        <h2 class="!text-lg font-bold !my-4">출금신청</h2>
        <form id="withdraw_form" class="flex flex-col gap-4 mx-auto">
            <!-- 은행명 (select) -->
            <div class="w-full flex gap-3 items-center">
                <div class="w-1/2">
                    <label for="bank" class="block text-sm font-medium text-gray-700">은행명</label>
                    <select id="bank" name="bank"
                        class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                        <option value="">
                            은행 선택
                        </option>
                        <?
                        $BANK_CODE = unserialize(BANK_CODE);
                        foreach ($BANK_CODE as $bank => $value) {
                        ?>
                            <option value="<?= $bank ?>">
                                <?= $value ?>
                            </option>
                        <?
                        }
                        ?>
                    </select>
                </div>

                <!-- 계좌번호 -->
                <div class="w-1/2">
                    <label for="account" class="block text-sm font-medium text-gray-700">계좌번호</label>
                    <input type="text" id="account_no" name="account_no" placeholder="계좌번호" class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                </div>
            </div>

            <div class="">
                <label for="account" class="block text-sm font-medium text-gray-700">출금금액</label>
                <input type="text" onkeyup="eventcomma(event);" name="amount" placeholder="금액" class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">

                <p class="!text-sm !mt-2 flex items-center gap-2">
                    <span class="text-sm text-green-800">현재 출금 가능금액: </span>
                    <span class="text-sm font-semibold">
                        <!-- 출금 가능 금액 계산 -->
                        <!-- 로그인 유저의 포인트에서 출금 요청 금액을 뺀 값 (디스플레이로 계싼 보여줌) -->
                        <?= number_format($login_user['point']) ?> - <?= number_format($login_user['withdraw_request_sum']) ?>(출금신청) = <?= number_format($login_user['point'] - $login_user['withdraw_request_sum']) ?>원
                    </span>
                </p>

                <p class="!text-sm !mt-2">
                    <span class="text-sm text-red-500">최소 출금금액: </span>
                    <span class="text-sm font-semibold">1,000원</span>
                </p>
            </div>

            <!-- 출금신청 버튼 -->
            <div class="pt-2">
                <button type="button" onclick="handle_withdraw(event);" class="ld-over-full w-full bg-sm text-white py-3 font-semibold">
                    출금신청
                </button>
            </div>
        </form>
    </div>
    <div class="lg:w-1/2">
        <h2 class="!text-lg font-bold !my-4">계좌관리</h2>
        <form class="flex flex-col gap-4 mx-auto">
            <!-- 은행명 (select) -->
            <?
            if (!empty($user_accounts)) {

                $BANK_CODE = unserialize(BANK_CODE);

                foreach ($user_accounts as $account) {

                    $bank_name = $BANK_CODE[$account['bank']] ?? '알수없음';


            ?>
                    <div class="relative text-left !border !border-gray-300 rounded-sm !p-4 text-center text-sm text-gray-800 max-w-md mx-auto">
                        <!-- 삭제 버튼 (오른쪽 상단) -->
                        <div class="!mb-2">
                            <button onclick="delete_account(<?= $account['id'] ?>);" type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 text-lg leading-none">
                                &times;
                            </button>

                            <span class="!text-sm text-blue-500 cursor-pointer hover:underline" onclick="asdasd('<?= $account['bank'] ?>','<?= $account['account_no'] ?>')">
                                복사하기
                            </span>
                        </div>

                        <!-- 상단 텍스트 -->
                        <p class=" font-semibold mb-1">
                            <?= $account['name'] ?>
                        </p>

                        <!-- 계좌 정보 -->
                        <p class="text-gray-800">
                            <?= $bank_name ?> /
                            <span class="font-semibold">
                                <?= $account['account_no'] ?>
                            </span>
                        </p>

                        <!-- 복사하기 버튼 -->
                    </div>
                <?
                }
            } else {
                ?>

            <?
            }
            ?>

            <!-- 출금신청 버튼 -->
            <div class="pt-2">
                <button type="button" onclick="reg_account();" class="btn btn-dash">+ 계좌 등록하기</button>
            </div>
        </form>
    </div>
</section>

<dialog id="my_modal_3" class="modal">
    <div class="modal-box !px-6 !py-4">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <div class="flex flex-col items-center justify-center">
            <h3 class="!text-lg !font-bold text-center">계좌등록</h3>
        </div>

        <form onsubmit="handle_account_form(event);" id="join-form" class="!mt-2 border-t border-black text-sm !space-y-6">
            <div class="">
                <label for="name" class="block text-sm font-medium text-gray-700">계좌명</label>
                <input
                    type="text"
                    name="name"
                    placeholder="계좌명"
                    class="w-full border border-gray-200  px-4 py-3 text-sm placeholder-gray-400"
                    value="" />
            </div>

            <div class="flex gap-4 items-center">
                <div class="w-1/2">

                    <label for="bank" class="block text-sm font-medium text-gray-700">은행</label>
                    <select name="bank"
                        class="w-full border border-gray-300 px-4 py-3 text-sm placeholder-gray-400 focus:outline-none">
                        <option value="">
                            은행 선택
                        </option>
                        <?
                        $BANK_CODE = unserialize(BANK_CODE);
                        foreach ($BANK_CODE as $bank => $value) {
                        ?>
                            <option value="<?= $bank ?>">
                                <?= $value ?>
                            </option>
                        <?
                        }
                        ?>
                    </select>
                </div>

                <div class="w-1/2">
                    <label for="account_no" class="block text-sm font-medium text-gray-700">계좌번호 (-제외)</label>
                    <input
                        type="number"
                        name="account_no"
                        placeholder="계좌번호"
                        class="w-full border border-gray-200  px-4 py-3 text-sm placeholder-gray-400"
                        value="" />
                </div>

            </div>

            <!-- 버튼 -->
            <div class="flex justify-between mt-8 gap-4">
                <button type="button" onclick="my_modal_3.close();" class="w-1/2 border border-gray-300 py-3 text-sm hover:bg-gray-50">닫기</button>
                <button type="submit" class="w-1/2 btn-primary-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900">
                    등록하기
                </button>
            </div>
        </form>
    </div>
</dialog>


<script>
    function asdasd(bank, account_no) {

        $("#bank").val(bank);
        $("#account_no").val(account_no);
        $("input[name='amount']").focus();
    }

    function reg_account() {

        my_modal_3.showModal();
    }

    function handle_account_form(e) {

        e.preventDefault();

        const form = $(e.target);
        const serial = form.serialize();


        $.ajax({
            type: "POST",
            url: "/my/reg_account",
            data: serial,
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    alert('계좌 등록이 완료되었습니다.');
                    window.location.reload();
                } else {
                    alert('계좌 등록에 실패했습니다: ' + response.message);
                }
            },
            finally: function() {},
        });
    }

    function delete_account(id) {

        $.ajax({
            type: "POST",
            url: "/my/delete_account",
            data: {
                id: id
            },
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    alert('계좌가 삭제되었습니다.');
                    window.location.reload();
                } else {
                    alert('계좌 삭제에 실패했습니다: ' + response.message);
                }
            },
            finally: function() {
                // 추가적인 작업이 필요하다면 여기에 작성
            }
        });
    }

    function handle_withdraw(e) {

        const $btn = $(e.target);
        const withdraw_form = $('#withdraw_form');
        const serial = withdraw_form.serialize();

        btn_start($btn)


        $.ajax({
            type: "POST",
            url: "/my/go_withdraw",
            data: serial,
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    alert('출금신청이 완료되었습니다.');
                    fadeOutReload();
                } else {
                    alert('출금신청에 실패했습니다: ' + response.message);
                }

                btn_end($btn);

            },
            error: function(xhr, status, error) {
                alert('오류가 발생했습니다: ' + xhr.responseText);
                btn_end($btn);
            },
        });


    }
</script>