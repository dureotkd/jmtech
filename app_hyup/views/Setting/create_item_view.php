<style>
    input {
        padding-left: 6px !important;
    }
</style>


<div class="mx-auto !px-4 !py-2 bg-white border border-gray-200 text-sm !space-y-4 !text-[13px]">

    <form id="submitForm" class="flex flex-col gap-y-4" onsubmit="submitForm(event);">
        <!-- 품목명 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg style="color:red;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                품목명
            </label>
            <input type="text" name="item_name" class="border flex-1 min-w-[200px] max-w-[200px] h-[35px] px-1" />
        </div>

        <!-- 별칭 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                별칭
            </label>
            <input type="text" name="alias" placeholder="사업자번호 입력 (숫자 10자리)" class="border flex-1  min-w-[200px] max-w-[200px] h-[35px] px-1" />
        </div>

        <!-- 단위 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                단위
            </label>
            <input type="text" name="unit" class="border flex-1  min-w-[200px] max-w-[200px] h-[35px] px-1" />
        </div>

        <!-- 구매가 -->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                구매가
            </label>
            <input type="text" name="purchase_price" class="border flex-1  min-w-[200px] max-w-[200px] h-[35px] px-1" />
        </div>

        <!-- 판매가 -->
        <div class="flex items-center !pb-2">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                판매가
            </label>
            <input type="text" name="sales_price" class="border flex-1  min-w-[200px] max-w-[200px] h-[35px] px-1" />
        </div>

        <!-- 기타사항 -->
        <div class="flex items-center !border-t-1 !border-gray-300 !pt-6">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                기타사항
            </label>
            <input type="text" name="memo" class="border flex-1  min-w-[200px] max-w-[200px] h-[35px] px-1" />
        </div>

        <!-- 활동여부-->
        <div class="flex items-center">
            <label class="flex items-center font-semibold text-gray-700 w-[150px]">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-dot-icon lucide-dot">
                    <circle cx="12.1" cy="12.1" r="1" />
                </svg>
                활동여부
            </label>
            <input type="text" name="is_active" class="border flex-1  min-w-[200px] max-w-[200px] h-[35px] px-1" />
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
        onclick="window.close();"
        class="px-2 py-1 bg-[#fff] text-gray-700 hover:bg-gray-100 border border-gray-300">
        취소
    </button>
</div>

<script>
    function submitForm(e) {
        e.preventDefault(); // 폼의 기본 제출 동작 방지

        const serial = $('#submitForm').serialize();
        const formData = new FormData($('#submitForm')[0]);

        $.ajax({
            url: '/setting/item/create_item',
            type: 'POST',
            data: serial,
            success: function(response) {
                alert('품목이 성공적으로 생성되었습니다.');
                window.opener.location.reload();
                window.close();
                // 추가적인 성공 처리 로직 작성
            },
            error: function(xhr, status, error) {
                alert('품목 생성 중 오류가 발생했습니다.');
                // 추가적인 오류 처리 로직 작성
            }
        });
    }
</script>