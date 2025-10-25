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
    <h2 class="!text-lg font-bold mb-6">레시피관리</h2>

    <div class="overflow-x-auto !mt-8">

        <div class="w-full flex justify-between gap-2 !mb-2">
            <div class="">
                <button type="button" onclick="window.location.href = '/admin/recipe/create'" class="btn btn-soft">레시피등록</button>
            </div>
        </div>
        <table class="table w-full border border-gray-200">
            <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                <tr>
                    <th class="w-[100px]">메인 이미지</th>
                    <th class="w-[50px]">난이도</th>
                    <th class="w-[100px]">조리시간</th>
                    <th class="w-[200px]">레시피명</th>
                    <th>설명</th>
                    <th class="w-[200px]">등록일</th>
                    <th class="w-[150px]">처리명령</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">

                <?
                if (!empty($recipe_all)) {

                    foreach ($recipe_all as $recipe) {

                ?>
                        <tr class="border-b">
                            <td>
                                <img src="<?= $recipe['image_url'] ?>" alt="레시피 이미지" class="w-16 h-16 object-cover rounded">
                            </td>
                            <td>
                                <div class="rating">
                                    <?
                                    for ($i = 1; $i <= 5; $i++) {
                                    ?>
                                        <input type="radio" name="level_<?= $recipe['id'] ?>" <?= $recipe['level'] == $i ? 'checked' : '' ?> value=" <?= $i ?>" name="level" class="mask mask-star-2 bg-orange-400" aria-label="<?= $i ?> star" />
                                    <?
                                    }
                                    ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-soft !bg-gray-100 !text-gray-700 !border-gray-300 !px-3 !py-1">
                                    <?= $recipe['cooking_time'] ?? 0 ?>분
                                </span>
                            </td>
                            <td>
                                <a href="/admin/recipe?id=<?= $recipe['id'] ?>" class="!text-blue-600 hover:underline">
                                    <?= $recipe['title'] ?>
                                </a>
                            </td>
                            <td>
                                <?= nl2br(mb_substr($recipe['content'], 0, 30)) ?>
                            </td>

                            <td>
                                <?= explode(' ', $recipe['created_at'])[0] ?>
                                <br />
                                <span class="text-xs text-gray-500">(<?= explode(' ', $recipe['created_at'])[1] ?>)</span>
                            </td>

                            <td>
                                <div class="flex gap-2">
                                    <button type="button" onclick="window.location.href = '/admin/recipe/create?id=<?= $recipe['id'] ?>';" class="btn !px-3 !py-2">
                                        수정
                                    </button>
                                    <button type="button" onclick="delete_recipe(<?= $recipe['id'] ?>);" class="btn !px-3 !py-2">
                                        삭제
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?
                    }
                    ?>
                <?
                }
                ?>

            </tbody>
        </table>
    </div>

</div>

<dialog id="my_modal_1" class="modal">
    <div class="modal-box !px-6 !py-4">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <div class="flex flex-col items-center justify-center">
            <h3 class="!text-lg !font-bold text-center">레시피 수정</h3>
            <div class="!my-4 relative w-18 h-18">
                <!-- 아바타 이미지 -->
                <img
                    id="profile_image"
                    src="/assets/app_hyup/images/default_profile.png"
                    alt="프로필 이미지"
                    class="w-18 h-18 rounded-full object-cover border border-gray-200" />

                <!-- 카메라 아이콘 버튼 -->
                <button
                    class="absolute bottom-0 right-0 w-7 h-7 bg-white border border-gray-300 rounded-full flex items-center justify-center text-gray-600 hover:bg-gray-100"
                    onclick="document.getElementById('profileInput').click()"
                    title="사진 변경">
                    <input type="file" id="profileInput" name="profile_image" accept="image/*" class="hidden" onchange="handle_image_upload(event);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-700 lucide lucide-camera-icon lucide-camera">
                        <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                        <circle cx="12" cy="13" r="3" />
                    </svg>
                </button>
            </div>
        </div>

        <form onsubmit="handle_join_form(event);" id="join-form" class="!mt-2 border-t border-black text-sm !space-y-6">

            <div class="">
                <input class="w-full !border !border-gray-200  px-4 py-3 text-sm !placeholder-gray-400"
                    placeholder="아이디"
                    type="text"
                    value=""


                    name="user_id">
                <input
                    type="password"
                    name="password"
                    placeholder="비밀번호를 변경하는 경우 입력해주세요"
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
                    <label class="text-sm text-left font-semibold text-gray-800 !mb-2 block">이름</label>
                    <input
                        type="text"
                        name="name"
                        placeholder="이름"
                        class="w-full border border-gray-200 rounded px-4 py-3 text-sm placeholder-gray-400"
                        value="" />
                </div>

                <div class="w-full">
                    <label class="text-sm text-left font-semibold text-gray-800 !mb-2 block">연락처</label>
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
                    <label class="text-sm text-left font-semibold text-gray-800 !mb-2 block">이메일</label>
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

            <!-- 버튼 -->
            <div class="flex justify-between mt-8 gap-4">
                <button type="button" onclick="my_modal_1.close();" class="w-1/2 border border-gray-300 py-3 text-sm hover:bg-gray-50">닫기</button>
                <button type="submit" class="w-1/2 btn-primary-sm bg-black text-white py-3 text-sm font-semibold hover:bg-gray-900">수정</button>
            </div>
        </form>
    </div>
</dialog>


<script>
    function handle_update_modal(id) {
        // 여기서 id를 사용하여 레시피 정보를 불러오고, 모달에 데이터를 채워넣는 로직을 구현해야 합니다.

        my_modal_1.show();
    }

    function delete_recipe(id) {

        if (!confirm("정말로 이 레시피를 삭제하시겠습니까?")) {
            return;
        }

        $.ajax({
            type: "POST",
            url: "/admin/recipe/delete_recipe",
            data: {
                id: id
            },
            dataType: "json",
            success: function(response) {
                if (response.ok) {
                    alert(response.msg);
                    window.location.reload();
                } else {
                    alert(response.msg);
                }
            }
        });
    }
</script>