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


<div class="!space-y-2">
    <h2 class="text-2xl font-bold mb-6">회원관리</h2>

    <form id="search_form" action="/admin/agent" class="!mt-8 flex items-center gap-2 bg-white justify-between">
        <input type="hidden" name="excel_yn" value="<?= $excel_yn ?>" id="excel_yn" />
        <input type="hidden" name="page" value="<?= $page ?>" />

        <div class="flex flex-col gap-4 w-full">
            <div class="w-full flex items-center gap-2 !justify-between">
                <div class="flex gap-2">
                    <select name="search_user" onchange="handle_search_submit(event);" class="select !w-fit min-w-[120px]">
                        <?
                        foreach ($search_user_item as $key => $value) {
                        ?>
                            <option value="<?= $key ?>" <?= $value['select'] ?>>
                                <?= $value['name'] ?>
                            </option>
                        <?
                        }
                        ?>
                    </select>
                    <select name="search_type" class="select !w-fit min-w-[120px]">

                        <?
                        foreach ($search_type_item as $key => $value) {
                        ?>
                            <option value="<?= $key ?>" <?= $value['select'] ?>>
                                <?= $value['name'] ?>
                            </option>
                        <?
                        }
                        ?>
                    </select>

                    <input type="text" name="search_value" placeholder="Type here" class="input" value="<?= $search_value ?>" />
                    <button type="submit" class="btn btn-soft">검색</button>
                </div>

            </div>
            <div class="w-full flex items-end justify-between gap-2 !mb-4">

                <div className="flex w-full flex-col lg:flex-row">
                    <div class="flex w-full">
                        <div class="card rounded-box grid h-20 grow place-items-center gap-2">
                            <button type="button" onclick="handle_agent();" class="btn btn-soft">부본사 지정</button>
                            <button type="button" onclick="handle_delete_agent();" class="btn btn-soft btn-error">부본사 해제</button>
                        </div>
                        <div class="divider divider-horizontal !mx-4"></div>
                        <!-- <div class="card rounded-box grid h-20 grow place-items-center gap-2">
                            <button type="button" onclick="handle_agent();" class="btn btn-soft">매장 지정</button>
                            <button type="button" onclick="handle_delete_agent();" class="btn btn-soft btn-error">매장 해제</button>
                        </div> -->
                    </div>
                </div>

                <div class="">
                    <button type="button" onclick="my_modal_4.showModal();"
                        class="inline-flex items-center gap-2 bg-yellow-100 text-yellow-800 border border-yellow-300 px-4 py-2 text-sm rounded hover:bg-yellow-200 transition">
                        회원 생성
                    </button>
                    <button type="button" onclick="download_excel();"
                        class="inline-flex items-center gap-2 bg-green-100 text-green-800 border border-green-300 px-4 py-2 text-sm rounded hover:bg-green-200 transition">
                        엑셀 다운로드
                    </button>
                </div>
            </div>
        </div>

    </form>

    <div class="overflow-x-auto">
        <table class="table w-full !text-sm border border-gray-200">
            <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                <tr>
                    <th>
                        No.
                    </th>
                    <th>
                        <input type="checkbox" class="checkbox checkbox-sm" name="all_check" id="all_check" />
                    </th>
                    <th>유형</th>
                    <th>포인트</th>
                    <th>아이디</th>
                    <th>이름</th>
                    <th>연락처</th>
                    <th>이메일</th>
                    <th>주소</th>
                    <th>로그인 연동</th>
                    <th>처리명령</th>
                    <th>등록일시</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                <?
                if (!empty($user_all)) {

                    $agentvo = unserialize(AGENT);
                    $cnt = count($user_all);

                    foreach ($user_all as $user) {

                        $agent_name = $agentvo[$user['agent']] ?? '일반회원';

                        $classname_array = [
                            'BRANCH' => 'text-orange-600',
                            'STORE' => 'text-blue-600',
                            'USER' => 'text-gray-600',
                            'CUSTOMER' => 'text-lime-600',
                            'HEAD' => 'text-purple-600',
                        ];
                        $classname = $classname_array[$user['agent']] ?? 'text-gray-600';
                ?>
                        <tr class="border-b">
                            <td>
                                <?= $cnt-- ?>
                            </td>
                            <td>
                                <?
                                if ($user['agent'] != 'HEAD') {
                                ?>
                                    <input type="checkbox" class="checkbox checkbox-sm item_check" name="user_id[]" value="<?= $user['id'] ?>" />
                                <?
                                }
                                ?>
                            </td>
                            <td>
                                <?
                                if ($user['agent'] == 'BRANCH') {
                                ?>
                                    <span class="badge badge-outline text-orange-600 border-orange-400">
                                        <?= $agent_name ?>
                                        - <?= $user['id'] ?>
                                    </span>
                                <?
                                } else {
                                ?>
                                    <span class="<?= $classname ?>"><?= $agent_name ?></span>
                                <?
                                }
                                ?>

                                <?
                                if ($user['store_code']) {
                                ?>
                                    <br />
                                    <span class="badge badge-outline text-blue-600 border-blue-400">
                                        (<?= $user['store_code'] ?>)
                                    </span>
                                <?
                                }
                                ?>
                            </td>
                            <td>
                                <?= number_format($user['point']) ?>원
                            </td>
                            <td>
                                <a href="/admin/agent?id=<?= $user['id'] ?>" class="!text-blue-600 hover:underline">
                                    <?= $user['user_id'] ?>
                                </a>
                            </td>
                            <td>
                                <?= $user['name'] ?>
                            </td>
                            <td>
                                <?= $user['phone'] ?>
                            </td>
                            <td>
                                <?= $user['email'] ?>
                            </td>
                            <td>
                                <div class="space-y-1">
                                    <div>주소: <span class="badge badge-outline text-blue-600 border-blue-400"><?= $user['address'] ?> <?= $user['address_detail'] ?></span></div>
                                    <div>우편번호: <?= $user['zip_code'] ?></div>
                                </div>
                            </td>
                            <td>
                                <?= $user['social_type'] ?>
                            </td>
                            <td>
                                <div class="flex flex-col gap-1">
                                    <?
                                    if ($user['agent'] == 'USER' || $user['agent'] == 'STORE' || $user['agent'] == 'CUSTOMER') {
                                    ?>
                                        <button type="button" onclick="handle_store(<?= $user['id'] ?>);" class="btn btn-sm btn-soft">매장 지정</button>
                                    <?
                                    }
                                    ?>

                                    <?
                                    if ($user['agent'] == 'USER' || $user['agent'] == 'CUSTOMER') {
                                    ?>
                                        <button type="button" onclick="handle_customer(<?= $user['id'] ?>);" class="btn btn-sm btn-soft">고객 지정</button>
                                    <?
                                    }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <?= explode(' ', $user['created_at'])[0] ?>
                                <br />
                                <span class="text-xs text-gray-500">(<?= explode(' ', $user['created_at'])[1] ?>)</span>
                            </td>
                        </tr>
                    <?
                    }
                    ?>
                <?
                } else {
                ?>
                    <tr class="border-b">
                        <td colspan="9" class="!text-center">
                            <span class="text-gray-500">등록된 회원이 없습니다.</span>
                        </td>
                    </tr>
                <?
                }
                ?>
            </tbody>
        </table>
    </div>

</div>

<dialog id="my_modal_2" class="modal">
    <div class="modal-box !px-6 !py-4">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <div class="flex flex-col items-center justify-center">
            <h3 class="!text-lg !font-bold text-center">매장(총판코드) 지정 </h3>
        </div>

        <form onsubmit="handle_store_reg(event);" class="!mt-4 border-t border-black text-sm !space-y-6">
            <input type="hidden" name="user_id" value="">

            <div class="flex flex-wrap gap-4">
                <?
                if (!empty($store_code_all)) {
                    foreach ($store_code_all as $row) {
                ?>
                        <label>
                            <input type="radio" name="store_code" value="<?= $row['code'] ?>" />
                            <?= $row['code'] ?> (부본사 - <?= $row['agent_number'] ?>)
                        </label>
                <?
                    }
                }
                ?>
            </div>
            <!-- 버튼 -->
            <button type="submit" class="w-full btn-primary-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900">
                지정하기
            </button>
        </form>
    </div>
</dialog>

<dialog id="my_modal_3" class="modal">
    <div class="modal-box !px-6 !py-4">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <div class="flex flex-col items-center justify-center">
            <h3 class="!text-lg !font-bold text-center">고객 지정 </h3>
        </div>

        <form onsubmit="handle_store_customer(event);" class="!mt-4 border-t border-black text-sm !space-y-6">
            <input type="hidden" name="user_id" value="">

            <div class="flex flex-wrap gap-4">
                <?
                if (!empty($store_user_all)) {
                    foreach ($store_user_all as $row) {
                ?>
                        <label>
                            <input type="radio" name="store_code" value="<?= $row['store_code'] ?>" />
                            매장 - <?= $row['id'] ?> (<?= $row['name'] ?>)
                        </label>
                <?
                    }
                }
                ?>
            </div>
            <!-- 버튼 -->
            <button type="submit" class="w-full btn-primary-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900">
                지정하기
            </button>
        </form>
    </div>
</dialog>


<dialog id="my_modal_4" class="modal">
    <div class="modal-box !px-6 !py-4">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <div class="flex flex-col items-center justify-center ">
            <h3 class="!text-lg !font-bold text-center">회원 생성</h3>
        </div>

        <form onsubmit="handle_join_form(event);" id="join-form" class="!mt-4 step-2 border-t border-black text-sm !space-y-6">

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

            <div class="w-full">
                <label class="text-sm text-left font-semibold text-gray-800 !mb-2 block">총판코드</label>
                <input
                    type="text"
                    name="store_code"
                    placeholder="총판코드"
                    class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400"
                    value="" />
            </div>

            <div class="g-recaptcha" data-sitekey="6Lf_6YkrAAAAAGHM8uF8IcpndALRutrRtMson49V"></div>

            <!-- 버튼 -->
            <div class="flex justify-between mt-8 gap-4">

                <button type="button" onclick="my_modal_4.close();" class="w-1/2 border border-gray-300 py-3 text-sm hover:bg-gray-50">닫기</button>
                <button type="submit" data-style="slide-up" id="submit_btn" class="w-1/2 ld-over-full btn-primary-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900 flex justify-center items-center">
                    회원 생성
                </button>
            </div>
        </form>

    </div>
</dialog>


<script>
    function handle_join_form(e) {
        e.preventDefault(); // 기본 폼 제출 동작 방지

        const submit_btn = $("#submit_btn");
        submit_btn.attr("disabled", true);

        let serial = $("#join-form").serialize();

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

                if (msg) alert(msg);
                if (ok) location.reload();
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("회원 가입 중 오류가 발생했습니다. 다시 시도해주세요.");

                submit_btn.attr("disabled", false);
            }
        });
    }

    function phoneNumberMask(el) {
        let number = el.value.replace(/[^0-9]/g, ""); // 숫자만 추출

        if (number.length < 4) {
            el.value = number;
        } else if (number.length < 7) {
            el.value = number.slice(0, 3) + "-" + number.slice(3);
        } else if (number.length < 11) {
            el.value =
                number.slice(0, 3) + "-" + number.slice(3, 6) + "-" + number.slice(6);
        } else {
            el.value =
                number.slice(0, 3) + "-" + number.slice(3, 7) + "-" + number.slice(7, 11);
        }
    }


    function download_excel(e) {
        const excel_yn = $("input[name='excel_yn']").val();
        if (excel_yn === 'Y') {
            alert('이미 엑셀 다운로드가 진행 중입니다.');
            return;
        }

        $("input[name='excel_yn']").val('Y');
        $("#search_form").submit();
        $("input[name='excel_yn']").val('N');
    }

    function handle_store(id) {
        $("input[name='user_id']").val(id);
        const my_modal_2 = document.getElementById('my_modal_2');
        my_modal_2.showModal();
    }

    function handle_customer(id) {
        $("input[name='user_id']").val(id);
        const my_modal_3 = document.getElementById('my_modal_3');
        my_modal_3.showModal();
    }

    function handle_store_customer(e) {
        e.preventDefault();

        const target = $(e.target);
        const user_id = target.find("input[name='user_id']").val();
        const store_code = target.find("input[name='store_code']:checked").val();

        if (!store_code) {
            alert('매장을 선택해주세요.');
            return;
        }

        $.ajax({
            type: "POST",
            url: "/admin/agent/add_store_customer",
            data: {
                user_id: user_id,
                store_code: store_code,
            },
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    alert('고객 지정이 완료되었습니다.');
                    location.reload();
                } else {
                    alert('처리 중 오류가 발생했습니다: ' + response.message);
                }
            }
        });

    }

    function handle_store_reg(e) {

        e.preventDefault();
        const target = $(e.target);

        const user_id = target.find("input[name='user_id']").val();
        const store_code = target.find("input[name='store_code']:checked").val();
        if (!store_code) {
            alert('총판코드를 선택해주세요.');
            return;
        }
        $.ajax({
            type: "POST",
            url: "/admin/agent/add_store",
            data: {
                user_id: user_id,
                store_code: store_code,
            },
            dataType: "json",
            success: function(response) {

                if (response.ok) {
                    alert('총판코드 지정이 완료되었습니다.');
                    location.reload();
                } else {
                    alert('처리 중 오류가 발생했습니다: ' + response.message);
                }
            }
        });
    }


    function handle_search_submit(e) {

        const target = $(e.target);
        const form1 = $("#search_form");
        form1.submit();
    }

    $('#all_check').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.item_check').prop('checked', isChecked);
    });

    $('.item_check').on('change', function() {
        const total = $('.item_check').length;
        const checked = $('.item_check:checked').length;
        $('#all_check').prop('checked', total === checked);
    });

    function handle_agent(e) {

        const selectedItems = $('.item_check:checked');
        if (selectedItems.length === 0) {
            alert('처리할 회원을 선택해주세요.');
            return;
        }

        const userIds = selectedItems.map(function() {
            return $(this).val();
        }).get();


        $.ajax({
            type: "POST",
            url: "/admin/agent/add_agent",
            data: {
                user_ids: userIds,
            },
            dataType: "json",
            success: function(response) {

                if (response.ok) {
                    alert('처리가 완료되었습니다.');
                    location.reload();
                } else {
                    alert('처리 중 오류가 발생했습니다: ' + response.message);
                }
            }
        });
    }

    function handle_delete_agent(e) {

        const selectedItems = $('.item_check:checked');
        if (selectedItems.length === 0) {
            alert('처리할 회원을 선택해주세요.');
            return;
        }

        const userIds = selectedItems.map(function() {
            return $(this).val();
        }).get();

        if (!confirm('선택한 회원의 부본사를 해제하시겠습니까?')) {
            return;
        }

        $.ajax({
            type: "POST",
            url: "/admin/agent/delete_agent",
            data: {
                user_ids: userIds,
            },
            dataType: "json",
            success: function(response) {

                if (response.ok) {
                    alert('부본사 해제가 완료되었습니다.');
                    location.reload();
                } else {
                    alert('처리 중 오류가 발생했습니다: ' + response.message);
                }
            }
        });
    }
</script>