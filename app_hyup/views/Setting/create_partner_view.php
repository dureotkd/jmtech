<style>
    input {
        padding-left: 6px !important;
    }
</style>

<script src="https://t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

<div class="mx-auto !px-4 !py-2 bg-white border border-gray-200 text-sm !space-y-4 !text-[13px]">

    <form id="submitForm" class="flex flex-col gap-y-4" onsubmit="submitForm(event);">
        <!-- 구분 -->
        <div class="flex items-center mb-4">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg style="color:red;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                구분
            </label>

            <div class="flex items-center gap-4">
                <label class="mr-4 flex items-center"><input type="radio" name="type" value="business" checked class="mr-1">사업자</label>
                <label class="flex items-center"><input type="radio" name="type" value="personal" class="mr-1">개인</label>
            </div>
        </div>

        <!-- 상호(법인명) -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg style="color:red;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                상호(법인명)
            </label>
            <input type="text" name="company_name" maxlength="45" class="border flex-1 min-w-[200px] max-w-[200px] h-[24px] px-1" />
            <span class="!ml-2 !text-gray-400">
                ※ 사업자등록증에 기재된 상호 또는 법인명을 입력합니다. (세금계산서 및 증빙/영수증에 사용함)
            </span>
        </div>

        <!-- 등록번호 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                등록번호
            </label>
            <input type="text" name="company_num" oninput="only_number_input(this);" placeholder="사업자번호 입력 (숫자 10자리)" maxlength="10" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
            <span class="!ml-2 !text-gray-400">
                ※ ERP에서 거래처 관리를 쉽게 하기 위해 통상적으로 사용하는 호칭을 입력 합니다.
            </span>
        </div>

        <!-- 대표자 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                대표자
            </label>
            <input type="text" name="ceo_name" maxlength="10" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
        </div>

        <!-- 회사전화번호 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                회사전화번호
            </label>
            <input type="text" name="phone_number" maxlength="15" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
        </div>

        <!-- 팩스번호 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                팩스번호
            </label>
            <input type="text" name="fax_number" maxlength="15" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
        </div>

        <!-- 주소 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                주소
            </label>
            <input type="text" name="address" class="border flex-1  min-w-[200px] max-w-[300px] h-[24px] px-1" />
            <input type="text" name="zipcode" readonly class="border flex-1  min-w-[200px] max-w-[100px] h-[24px] !ml-2 px-1" />
            <button type="button" class="sm-btn !h-[24px] ml-2" onclick="open_kakao_post_pop()">주소 검색</button>
        </div>

        <!-- 업태/종목-->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                업태/종목
            </label>
            <input type="text" name="business_type" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
        </div>

        <!-- 적요-->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                적요
            </label>
            <input type="text" name="memo" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
        </div>

        <!-- 그룹-->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                그룹
            </label>

            <div class="flex items-center gap-4">
                <?
                $PARTNER_GROUP = unserialize(PARTNER_GROUP);
                foreach ($PARTNER_GROUP as $key => $value) {
                ?>
                    <label class="mr-4 flex items-center"><input type="checkbox" name="<?= $key ?>" value="<?= $key ?>" class="mr-1"><?= $value ?></label>
                <?
                }
                ?>
            </div>
        </div>

        <!-- 계좌 정보-->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                계좌 정보
            </label>

            <div class="flex items-center gap-2">
                <select name="bank_code" id="">
                    <?
                    $BANK_CODE = unserialize(BANK_CODE);

                    foreach ($BANK_CODE as $key => $value) {
                    ?>
                        <option value="<?= $key ?>"><?= $value ?></option>
                    <?
                    }
                    ?>
                </select>
                <input type="text" name="ceo_name" class="border flex-1  min-w-[200px] max-w-[200px] h-[27.5px] px-1" />
            </div>
        </div>

        <!-- 문서 첨부-->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                문서 첨부
            </label>
            <input type="file" name="file1" class="border flex-1  min-w-[200px] max-w-[200px] h-[27.5px] px-1" />
        </div>

        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[176px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                담당자 정보
            </label>
            <table class="w-full text-sm border border-gray-200">
                <thead class="bg-[#788496] text-white">
                    <tr>
                        <th class="border px-2 py-1">이름</th>
                        <th class="border px-2 py-1">연락처</th>
                        <th class="border px-2 py-1">메일</th>
                        <th class="border px-2 py-1">비고</th>
                        <th class="border px-2 py-1">계산서 담당자</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-2 py-1"><input type="text" name="manager_name[]" class="!py-0.5 w-full"></td>
                        <td class="border px-2 py-1"><input type="text" name="manager_phone[]" class="!py-0.5 w-full"></td>
                        <td class="border px-2 py-1"><input type="email" name="manager_email[]" class="!py-0.5 w-full"></td>
                        <td class="border px-2 py-1"><input type="text" name="manager_note[]" class="!py-0.5 w-full"></td>
                        <td class="border px-2 py-1 text-center"><input type="checkbox" name="manager_invoice[]" class="rounded"></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="w-full !px-2 !text-[13px] flex justify-center items-center gap-1.5 font-sans font-300 !my-2">
            <!-- 저장 -->
            <button
                class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
                저장
            </button>

            <!-- 취소 -->
            <button
                type="button"
                onclick="window.close();"
                class="px-2 py-1 bg-[#fff] text-gray-700 hover:bg-gray-100 border border-gray-300">
                취소
            </button>
        </div>
    </form>

</div>

<script>
    function submitForm(e) {
        e.preventDefault(); // 폼의 기본 제출 동작 방지

        const serial = $('#submitForm').serialize();
        const formData = new FormData($('#submitForm')[0]);

        $.ajax({
            url: '/setting/partner/create_partner',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('거래처가 성공적으로 생성되었습니다.');
                window.opener.location.reload();
                window.close();
                // 추가적인 성공 처리 로직 작성
            },
            error: function(xhr, status, error) {
                alert('거래처 생성 중 오류가 발생했습니다. 다시 시도해주세요.');
                // 추가적인 오류 처리 로직 작성
            }
        });
    }
</script>