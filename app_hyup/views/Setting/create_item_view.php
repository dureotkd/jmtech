<style>
    input {
        padding-left: 6px !important;
    }
</style>


<div class="mx-auto !px-4 !py-2 bg-white border border-gray-200 text-sm !space-y-4 !text-[13px]">

    <div class="flex flex-col gap-y-4">
        <!-- 구분 -->
        <div class="flex items-center mb-4">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg style="color:red;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                구분
            </label>

            <div class="flex items-center gap-4">
                <label class="mr-4 flex items-center"><input type="radio" name="type" class="mr-1">사업자</label>
                <label class="flex items-center"><input type="radio" name="type" class="mr-1">개인</label>
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
            <input type="text" name="company_name" class="border flex-1 min-w-[200px] max-w-[200px] h-[24px] px-1" />
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
            <input type="text" name="company_num" placeholder="사업자번호 입력 (숫자 10자리)" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
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
            <input type="text" name="ceo_name" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
        </div>

        <!-- 회사전화번호 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                회사전화번호
            </label>
            <input type="text" name="ceo_name" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
        </div>

        <!-- 팩스번호 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                팩스번호
            </label>
            <input type="text" name="ceo_name" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
        </div>

        <!-- 주소 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                주소
            </label>
            <input type="text" name="ceo_name" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
        </div>

        <!-- 업태/종목-->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                업태/종목
            </label>
            <input type="text" name="ceo_name" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
        </div>

        <!-- 적요-->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                적요
            </label>
            <input type="text" name="ceo_name" class="border flex-1  min-w-[200px] max-w-[200px] h-[24px] px-1" />
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
                    <label class="mr-4 flex items-center"><input type="checkbox" name="partner_group[]" value="<?= $key ?>" class="mr-1"><?= $value ?></label>
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
            <input type="file" name="document_attachment" class="border flex-1  min-w-[200px] max-w-[200px] h-[27.5px] px-1" />
            <span class="text-gray-500 text-xs">(파일 형식: PDF, 최대 5MB)</span>
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
                        <th class="border px-2 py-1 w-16">관리</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-2 py-1"><input type="text" class="!py-0.5 w-full"></td>
                        <td class="border px-2 py-1"><input type="text" class="!py-0.5 w-full"></td>
                        <td class="border px-2 py-1"><input type="email" class="!py-0.5 w-full"></td>
                        <td class="border px-2 py-1"><input type="text" class="!py-0.5 w-full"></td>
                        <td class="border px-2 py-1 text-center"><input type="checkbox" class="rounded"></td>
                        <td class="border px-2 py-1 text-center">
                            <div class="flex justify-center items-center">
                                <button class="!border !px-2 !py-1 text-red-500 hover:text-red-700 mr-1">-</button>
                                <button class="!border !px-2 !py-1 text-blue-500 hover:text-blue-700">+</button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


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
            class="px-2 py-1 bg-[#fff] text-gray-700 hover:bg-gray-100 border border-gray-300">
            취소
        </button>
    </div>
</div>