<div class="p-4 bg-white font-sans text-sm text-gray-800">

    <h1 class="!text-xl !border-b !font-sans !border-gray-300 !pb-3">
        견적서
    </h1>

    <!-- 필터 영역 -->
    <div class="flex items-center gap-2 mb-4 !text-sm !my-1">

        <div class="ml-auto flex w-full items-center gap-2 justify-between">
            <div class=""></div>
            <button
                onclick="open_popup_default('http://localhost:5173/','견적서 등록',1000,820);"
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
                    <div class="w-full flex items-center justify-center gap-2">
                        <!-- Excel Button -->
                        <button
                            onclick="download_estimate_excel(event);"
                            class="flex !w-fit gap-2  min-w-[60px] items-center gap-1 border border-gray-300 rounded h-7 !px-1 bg-white hover:bg-gray-50 transition text-xs">
                            <img width="16" alt="Logo of Microsoft Excel since 2019" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e3/Microsoft_Office_Excel_%282019%E2%80%932025%29.svg/32px-Microsoft_Office_Excel_%282019%E2%80%932025%29.svg.png?20190925171014">
                            <span class="font-bold">엑셀</span>
                        </button>
                        <a
                            href="/sales/download_estimate_pdf"
                            class="flex !w-fit gap-2 min-w-[60px] items-center gap-1 !border !border-gray-300 rounded h-7 !px-1 bg-white hover:bg-gray-50 transition text-xs">
                            <img width="14" alt="Logo of Microsoft Excel since 2019" src="https://media.istockphoto.com/id/1298834280/ko/%EB%B2%A1%ED%84%B0/pdf-%EC%95%84%EC%9D%B4%EC%BD%98-%EC%A3%BC%EC%9A%94-%ED%8C%8C%EC%9D%BC-%ED%98%95%EC%8B%9D-%EB%B2%A1%ED%84%B0-%EC%95%84%EC%9D%B4%EC%BD%98-%EA%B7%B8%EB%A6%BC.jpg?s=612x612&w=0&k=20&c=p1hZH6NRAUA1tToGtDQ5weAxeJhVjtdlkhCD7Tsra0g=">
                            <span class="font-bold">PDF</span>
                        </a>
                    </div>
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