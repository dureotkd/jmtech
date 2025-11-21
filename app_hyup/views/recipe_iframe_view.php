<!-- Modal Background -->
<!-- Modal Box -->
<div class="bg-white rounded-lg shadow-xl w-[900px] max-h-[90vh] flex flex-col">

    <!-- Header -->
    <div class="flex items-center justify-between px-5 py-3 border-b">
        <h2 class="text-lg font-semibold">매입증빙 수취확인</h2>
        <button class="text-gray-400 hover:text-gray-600 text-xl">×</button>
    </div>

    <!-- 탭 메뉴 -->
    <div class="border-b px-5 flex gap-6 text-sm font-medium">
        <button class="py-3 text-blue-600 border-b-2 border-blue-600">세금계산서</button>
        <button class="py-3 text-gray-500 hover:text-black">카드/제로페이</button>
        <button class="py-3 text-gray-500 hover:text-black">현금영수증</button>
    </div>

    <!-- 상단 버튼 + 기간 선택 -->
    <div class="px-5 py-3 flex justify-between items-center">
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                수취확인
            </button>
            <button class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300 text-sm">
                취소분 정리
            </button>
        </div>

        <select class="border rounded-md px-3 py-2 text-sm">
            <option>최근 3개월</option>
            <option>최근 6개월</option>
            <option>최근 1년</option>
        </select>
    </div>

    <!-- 테이블 -->
    <div class="overflow-auto px-5 pb-5">
        <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-gray-100 border-y">
                <tr>
                    <th class="py-2 w-12"><input type="checkbox"></th>
                    <th class="py-2 w-24">작성일</th>
                    <th class="py-2 w-52">공급받는자상호</th>
                    <th class="py-2">내용</th>
                    <th class="py-2 w-28 text-right">금액</th>
                </tr>
            </thead>
            <tbody>
                <!-- ROW 1 -->
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-2"><input type="checkbox"></td>
                    <td>2025-11-19</td>
                    <td>그 커피집</td>
                    <td>커피외</td>
                    <td class="text-right">489,500</td>
                </tr>

                <tr class="border-b hover:bg-gray-50">
                    <td><input type="checkbox"></td>
                    <td>2025-11-12</td>
                    <td>(주)예스원 천안</td>
                    <td>서비스료(202501~202512)</td>
                    <td class="text-right">143,000</td>
                </tr>

                <tr class="border-b hover:bg-gray-50">
                    <td><input type="checkbox"></td>
                    <td>2025-11-12</td>
                    <td>(주) 나프</td>
                    <td>Nozzle 1.7(선정·정품)</td>
                    <td class="text-right">343,750</td>
                </tr>

                <tr class="border-b hover:bg-gray-50">
                    <td><input type="checkbox"></td>
                    <td>2025-11-11</td>
                    <td>(주) 나프</td>
                    <td>ZnSe-Lens 250 D40 외4</td>
                    <td class="text-right">1,780,900</td>
                </tr>

                <tr class="border-b hover:bg-gray-50">
                    <td><input type="checkbox"></td>
                    <td>2025-11-10</td>
                    <td>(주)대담전기인더스트리</td>
                    <td>전기안전관리대행수수료(11월분)</td>
                    <td class="text-right">165,000</td>
                </tr>

                <tr class="border-b hover:bg-gray-50">
                    <td><input type="checkbox"></td>
                    <td>2025-11-09</td>
                    <td>신한카드(주)</td>
                    <td>2025년11월 렌트 1769호083</td>
                    <td class="text-right">827,000</td>
                </tr>

                <tr class="border-b hover:bg-gray-50">
                    <td><input type="checkbox"></td>
                    <td>2025-11-08</td>
                    <td>하나캐피탈(주)</td>
                    <td>렌터카 대여료(21.225호01)</td>
                    <td class="text-right">2,242,790</td>
                </tr>

            </tbody>
        </table>
    </div>
</div>