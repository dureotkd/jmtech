<div class="p-4 bg-white font-sans text-sm text-gray-800">

    <h1 class="!text-xl !border-b !font-sans !border-gray-300 !pb-3">
        견적서
    </h1>

    <!-- 필터 영역 -->
    <div class="flex items-center gap-2 mb-4 !text-sm !my-1">
        <!-- <button class="px-3 py-1.5 border border-gray-300 rounded hover:bg-gray-100">삭제</button>

        <div class="flex items-center gap-2">
            <input type="date" class="border rounded px-2 py-1" value="2025-09-25" />
            <span>~</span>
            <input type="date" class="border rounded px-2 py-1" value="2025-10-25" />
            <button class="border rounded px-2 py-1 bg-white hover:bg-gray-100">
                📅
            </button>
        </div>

        <select class="border rounded px-2 py-1">
            <option>전체 메모</option>
        </select>

        <select class="border rounded px-2 py-1">
            <option>거래일 ▼</option>
        </select> -->

        <div class="ml-auto flex w-full items-center gap-2 justify-between">
            <div class=""></div>
            <!-- <input
                type="text"
                placeholder="검색어를 입력하세요"
                class="border px-2 py-1 rounded w-64" />
            <button class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 rounded">상세</button> -->
            <button
                onclick="open_popup_default('/sales/estimate_register','견적서 등록',1000,820);"
                type="button"
                class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
                견적서 등록 +
            </button>
        </div>
    </div>

    <!-- 테이블 -->
    <table class="w-full border border-gray-300">
        <thead>
            <tr class="bg-[#788496] text-white">
                <th class=" w-10"><input type="checkbox" /></th>
                <th class="">발행일</th>
                <th class="">공급받는자상호</th>
                <th class="">공급가액</th>
                <th class="">세액</th>
                <th class="">합계금액</th>
                <th class=" w-16"></th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b hover:bg-gray-50">
                <td><input type="checkbox" /></td>
                <td class="">2025-10-24</td>
                <td class="">주식회사 지아이베컴</td>
                <td class="">31,834,400</td>
                <td class="">3,183,940</td>
                <td class="">35,023,340</td>
                <td class="cursor-pointer">
                    <img src="https://ai.serp.co.kr/img/serp/btn/btn_send.png" alt="">
                </td>
            </tr>
            <tr class="border-b hover:bg-gray-50">
                <td><input type="checkbox" /></td>
                <td class="">2025-10-01</td>
                <td class="">지아이베컴</td>
                <td class="">2,770,000</td>
                <td class="">277,000</td>
                <td class="">3,047,000</td>
                <td class="cursor-pointer">
                    <img src="https://ai.serp.co.kr/img/serp/btn/btn_send.png" alt="">
                </td>
            </tr>

            <tr class="border-b bg-[#e9f1fb] 래">
                <td></td>
                <td class=""></td>
                <td class="">총 2건</td>
                <td class="">공급가액 : 34,609,400</td>
                <td class="">세액 : 3,460,940</td>
                <td class="">합계금액 : 38,070,340</td>
                <td class="cursor-pointer">
                    <img src="https://ai.serp.co.kr/img/serp/btn/btn_send.png" alt="">
                </td>
            </tr>

        </tbody>
    </table>

</div>