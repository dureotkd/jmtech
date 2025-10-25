<style>
    table th,
    table td {
        border: 1px solid #d1d5db;
        /* Tailwind의 gray-300 */
        padding: 0.75rem;
        /* Tailwind의 p-3 정도 */
        text-align: left;
        vertical-align: middle;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    table thead {
        background-color: #f9fafb;
        /* gray-50 */
    }
</style>

<?
$agentvo = unserialize(AGENT);
$USER_STATUS = unserialize(USER_STATUS);
?>

<div class="bg-white">
    <div class="!border-b !pb-4 !mb-4 !space-y-4">
        <span>
            <!-- zzz > zzz > zzz -->
        </span>
        <div class="!text-xl font-semibold flex items-center gap-1">
            <span>
                (<?= $USER_STATUS[$user_row['status']] ?? $user_row['status'] ?>)
            </span>

            <?

            $agent_name = $agentvo[$user_row['agent']] ?? '일반회원';
            $classname_array = [
                'BRANCH' => 'text-orange-600',
                'STORE' => 'text-blue-600',
                'USER' => 'text-gray-600',
                'ADMIN' => 'text-purple-600',
            ];
            $classname = $classname_array[$user_row['agent']] ?? 'text-gray-600';
            ?>

            <?
            if ($user_row['agent'] == 'BRANCH') {
            ?>
                <span class="badge badge-outline text-orange-600 border-orange-400">
                    <?= $agent_name ?>
                    - <?= $user_row['id'] ?>
                </span>
            <?
            } else {
            ?>
                <span class="<?= $classname ?>"><?= $agent_name ?></span>
            <?
            }
            ?>

            <?
            if ($user_row['store_code']) {
            ?>
                <br />
                <span class="badge badge-outline text-blue-600 border-blue-400">
                    (<?= $user_row['store_code'] ?>)
                </span>
            <?
            }
            ?>
        </div>
        <input type="hidden" id="id" value="<?= $user_row['id'] ?? '' ?>">

        <div class="flex items-center justify-between gap-2">
            <button onclick="history.back();" class="btn btn-soft">뒤로가기</button>

            <div class="">
                <button type="button" onclick="update_password_modal();" class="btn btn-soft">
                    비밀번호 변경
                </button>
                <button type="button" onclick="handle_user_status(event, <?= $user_row['id']  ?>,'Y');" class="btn btn-soft">계정 활성화</button>
                <button type="button" onclick="handle_user_status(event, <?= $user_row['id']  ?>,'S');" class="btn btn-soft">계정 정지</button>
                <button type="button" onclick="handle_user_status(event, <?= $user_row['id']  ?>,'D');" class="btn btn-soft">계정 삭제</button>
            </div>
        </div>

        <form id="user_form" class="grid grid-cols-2 gap-4 mt-2">
            <input type="hidden" name="id" value="<?= $user_row['id'] ?>">
            <div>
                <label class="block text-sm font-medium text-gray-700">이름</label>
                <input type="text" name="name" value="<?= $user_row['name'] ?>" class="input input-bordered w-full mt-1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">아이디</label>
                <input type="text" readonly value="<?= $user_row['user_id'] ?>" class="bg-gray-100 cursor-not-allowed  input input-bordered w-full mt-1">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">연락처</label>
                <input type="text" name="phone" value="<?= $user_row['phone'] ?>" class="input input-bordered w-full mt-1">
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <label class="block text-sm font-medium text-gray-700">주소</label>
                    <button onclick="open_kakao_post_pop(event);" type="button" class="btn btn-sm btn-soft">
                        주소찾기
                    </button>
                </div>
                <div class="flex gap-2">
                    <input type="text" name="zipcode" value="<?= $user_row['zip_code'] ?>" placeholder="우편번호" class="!w-[120px] input input-bordered w-full mt-1">
                    <input type="text" name="address" value="<?= $user_row['address'] ?>" placeholder="주소" class="input input-bordered w-full mt-1">
                </div>
                <input type="text" name="address_detail" value="<?= $user_row['address_detail'] ?>" placeholder="주소상세" class="input input-bordered w-full mt-1">
            </div>
            <div>
                <label class="flex justify-between text-sm font-medium text-gray-700">
                    <span>
                        포인트
                    </span>
                    <a href="#" class="!text-blue-600 hover:underline" onclick="my_modal_1.showModal(); return false;">
                        (포인트 부여/차감)
                    </a>
                </label>
                <input type="text" value="<?= number_format($user_row['point']) . '원' ?>" readonly class="bg-gray-100 cursor-not-allowed input input-bordered w-full mt-1">
                <div class="">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">메모</label>
                <div class="">
                    <textarea name="memo" class="textarea h-42 w-full" placeholder="관리자 메모"><?= $user_row['memo'] ?></textarea>
                    <button type="button" onclick="update_user();" class="btn btn-soft mt-2">
                        회원 정보 저장
                    </button>
                </div>
            </div>
        </form>
    </div>

    <?
    if (!empty($store_all)) {
    ?>
        <div class="!space-y-2">
            <h2 class="!text-lg font-bold">연결된 매장 (<?= count($store_all) ?>)</h2>

            <form action="" class="!mt-2 flex items-center gap-2 bg-white justify-between">
                <div class="flex gap-2">
                    총 적립금 <span class="font-semibold"><?= number_format($total_point) ?>원</span>
                </div>

                <div class="flex gap-2">
                    <select class="select !w-fit">
                        <option disabled selected>전체</option>
                        <option>Crimson</option>
                        <option>Amber</option>
                        <option>Velvet</option>
                    </select>

                    <input type="text" placeholder="Type here" class="input" />
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="table w-full border border-gray-200">
                    <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                        <tr>
                            <th>회원번호</th>
                            <th>아이디</th>
                            <th>이름</th>
                            <th>연락처</th>
                            <th>주소</th>
                            <th>포인트</th>
                            <th>총판코드</th>
                            <th>등록일시</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700">
                        <?
                        foreach ($store_all as $store) {
                        ?>
                            <tr class="border-b">
                                <th>
                                    <?= $store['id'] ?>
                                </th>
                                <td>
                                    <a href="/admin/agent?id=<?= $store['id'] ?>" class="!text-blue-600 hover:underline">
                                        <?= $store['user_id'] ?>
                                    </a>
                                </td>
                                <td>
                                    <?= $store['name'] ?>
                                </td>
                                <td>
                                    <?= $store['phone'] ?>
                                </td>
                                <td>
                                    <div class="space-y-1">
                                        <div>주소: <span class="badge badge-outline"><?= $store['address'] ?> / <?= $store['address_detail'] ?></span></div>
                                        <div>우편번호: <?= $store['zip_code'] ?> </div>
                                    </div>
                                </td>
                                <td>
                                    <?= number_format($store['point']) ?>원
                                </td>
                                <td>
                                    <?= $store['store_code'] ?>
                                </td>
                                <td>
                                    <?= explode(" ", $store['created_at'])[0] ?>
                                    <br />
                                    <span class="text-xs text-gray-500">(<?= explode(" ", $store['created_at'])[1] ?>)</span>
                                </td>
                            </tr>
                        <?
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?
    }
    ?>

    <div class="!space-y-2 !mt-8">
        <h2 class="!text-lg font-bold">포인트 내역</h2>

        <div class="overflow-x-auto">
            <table class="table w-full border border-gray-200">
                <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                    <tr>
                        <th class="w-[50px]">로그번호</th>
                        <th class="w-[120px]">유형</th>
                        <th>금액</th>
                        <th>잔액</th>
                        <th>내용</th>
                        <th class="min-w-[200px]">시스템 메모</th>
                        <th class="min-w-[200px]">메모</th>
                        <th>IP</th>
                        <th>등록일시</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    <?
                    /**
                     * Array
(
    [id] => 6
    [user_id] => 14
    [point_type] => SAVE
    [amount] => 40000
    [description] => 관리자 포인트 적립
    [related_table] => admin
    [related_id] => 0
    [created_at] => 2025-06-30 20:51:20
    [updated_at] => 2025-06-30 20:51:20
    [balance] => 100000
    [ip] => 127.0.0.1
)
                     */

                    $point_type_vo = unserialize(POINT_TYPE);
                    foreach ($point_logs as $point_log) {

                    ?>
                        <tr class="border-b">
                            <th>
                                <?= $point_log['id'] ?>
                            </th>
                            <td>
                                <?= $point_type_vo[$point_log['point_type']] ?? '' ?>
                            <td>
                                <?
                                if (in_array($point_log['point_type'], ['SAVE', 'ADMIN', 'USE'])) {

                                    if (strstr($point_log['amount'], '-')) {
                                ?>
                                        <span class="text-red-600">
                                            <?= number_format($point_log['amount']) ?>원
                                        </span>
                                    <?
                                    } else {
                                    ?>
                                        <span class="text-blue-600">
                                            <?= number_format($point_log['amount']) ?>원
                                        </span>
                                    <?
                                    }
                                    ?>
                                <?
                                } else {
                                ?>
                                    <?= number_format($point_log['amount']) ?>원
                                <?
                                }
                                ?>
                            </td>
                            <td>
                                <?= number_format($point_log['balance']) ?>원
                            </td>
                            <td>
                                <?= $point_log['description'] ?>
                            </td>
                            <td>
                                <?= $point_log['system_memo'] ?? '-' ?>
                            </td>
                            <td>
                                <div class="flex flex-col gap-2">
                                    <textarea class="textarea h-26 w-full" id=""><?= $point_log['memo'] ?? '-' ?></textarea>
                                    <button type="button" onclick="handle_update_memo('<?= $point_log['id'] ?>',event);" class="btn btn-sm">저장</button>
                                </div>
                            </td>
                            <td>
                                <?= $point_log['ip'] ?>
                            </td>
                            <td>
                                <?= get_admin_date($point_log['created_at']) ?>
                            </td>
                        </tr>
                    <?
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>


<dialog id="my_modal_1" class="modal">
    <div class="modal-box !px-6 !py-4">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <div class="flex flex-col items-center justify-center">
            <h3 class="!text-lg !font-bold text-center">포인트 부여/차감</h3>
        </div>

        <form onsubmit="handle_point_admin(event);" id="join-form" class="!mt-4 border-t border-black text-sm !space-y-6">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
            <div class="flex flex-col gap-2">
                <div class="flex -gap-2 items-center !mb-2">
                    <div class="flex flex-col gap-2">
                        <label for="plus_point_type">
                            <input type="radio" id="plus_point_type" name="point_type" checked value="plus" class="radio radio-primary">
                            +부여
                        </label>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="minus_point_type">
                            <input type="radio" id="minus_point_type" name="point_type" value="minus" class="radio !text-red-900 radio-primary ml-4">
                            -차감
                        </label>
                    </div>
                </div>

                <input type="number" id="point_value" name="point_value" class="input input-bordered w-full" placeholder="포인트를 입력하세요." required>
                <textarea id="point_memo" class="textarea w-full !mt-2" placeholder="메모"></textarea>
            </div>
            <!-- 버튼 -->
            <button type="submit" class="w-full btn-primary-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900">
                저장
            </button>
        </form>
    </div>
</dialog>

<dialog id="my_modal_2" class="modal">
    <div class="modal-box !px-6 !py-4">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <div class="flex flex-col items-center justify-center">
            <h3 class="!text-lg !font-bold text-center">비밀번호 변경</h3>
        </div>

        <form onsubmit="update_password(event);" id="join-form" class="!mt-4 border-t border-black text-sm !space-y-6">
            <div class="flex flex-col gap-2">
                <input type="hidden" name="id" value="<?= $user_row['id'] ?>">
                <input type="text" name="password" class="input input-bordered w-full" placeholder="비밀번호" required>
                <input type="text" name="password_confirm" class="input input-bordered w-full" placeholder="비밀번호 확인" required>
            </div>
            <!-- 버튼 -->
            <button type="submit" class="w-full btn-primary-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900">
                변경
            </button>
        </form>
    </div>
</dialog>

<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>
    function handle_update_memo(log_id, e) {
        e.preventDefault();
        const $textarea = $(e.target).siblings('textarea');
        const memo = $textarea.val();

        $.ajax({
            type: "POST",
            url: "/admin/agent/update_point_log_memo",
            data: {
                log_id: log_id,
                memo: memo
            },
            dataType: "json",
            success: function(response) {
                alert(response.msg);
                if (response.ok) {
                    window.location.reload();
                }
            }
        });
    }

    function update_password_modal() {

        my_modal_2.showModal();

    }

    function open_kakao_post_pop() {
        new daum.Postcode({
            oncomplete: function(res) {

                if (res.address) {

                    $("input[name='address']").val(res.address);
                }

                if (res.zonecode) {
                    $("input[name='zipcode']").val(res.zonecode);
                }

            }
        }).open();
    }

    function update_password(e) {

        e.preventDefault();
        const target = $(e.target);
        const serial = target.serialize();


        $.ajax({
            type: "POST",
            url: "/admin/agent/update_password",
            data: serial,
            dataType: "json",
            success: function(response) {
                alert(response.msg);

                if (response.ok) {
                    my_modal_2.close();
                    window.location.reload();
                }
            }
        });
    }

    function handle_point_admin(e) {
        e.preventDefault();
        const target = $(e.target);
        const point_type = target.find('input[name="point_type"]:checked').val();
        const point_value = target.find('#point_value').val();
        const user_id = target.find('input[name="user_id"]').val();
        const point_memo = target.find('#point_memo').val();

        // 중복 방지: 버튼 비활성화
        const $submitBtn = target.find('button[type="submit"]');
        $submitBtn.prop('disabled', true);


        $.ajax({
            type: "POST",
            url: "/admin/agent/point_admin",
            data: {
                point_type: point_type,
                point_value: point_value,
                user_id: user_id,
                memo: point_memo
            },
            dataType: "json",
            success: function(response) {
                alert(response.msg);
                if (response.ok) {
                    window.location.reload();
                }
                $submitBtn.prop('disabled', false);
            },
            error: function(xhr, status, error) {
                $submitBtn.prop('disabled', false);
            },
        });
    }

    function handle_user_status(e, user_id, status) {

        if (status === 'D' && !confirm('정말로 이 계정을 삭제하시겠습니까?')) {
            return;
        }

        if (status === 'S' && !confirm('정말로 이 계정을 정지하시겠습니까?')) {
            return;
        }

        if (status === 'Y' && !confirm('정말로 이 계정을 활성화하시겠습니까?')) {
            return;
        }

        const target = $(e.target);

        $.ajax({
            type: "POST",
            url: "/admin/agent/update_user_status",
            data: {
                user_id: user_id,
                status: status
            },
            dataType: "json",
            success: function(response) {
                alert(response.msg);
                window.location.reload();
            }
        });
    }

    function update_user() {
        const serial = $('#user_form').serialize();

        $.ajax({
            type: "POST",
            url: "/admin/agent/update_user",
            data: serial,
            dataType: "json",
            success: function(response) {
                alert(response.msg);
                if (response.ok) {
                    location.reload();
                }
            },
            error: function(xhr, status, error) {}
        });
    }
</script>