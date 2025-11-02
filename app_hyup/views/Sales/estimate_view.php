<div class="p-4 bg-white font-sans text-sm text-gray-800">

    <h1 class="!text-xl !border-b !font-sans !border-gray-300 !pb-3">
        견적서
    </h1>

    <!-- 필터 영역 -->
    <div class="flex items-center gap-2 mb-4 !text-sm">

        <div class="ml-auto flex w-full items-center gap-2 justify-between">
            <button type="button" class="!my-2  flex items-center gap-1 border border-gray-300 rounded h-7 !px-3 bg-white hover:bg-gray-50 transition text-sm"><input multiple="" type="file" style="display: none;">
                삭제
            </button>
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
                <th class=" w-10"><input type="checkbox" id="all_check" /></th>
                <th class="">발행일</th>
                <th class="">공급받는자상호</th>
                <th class="">공급가액</th>
                <th class="">세액</th>
                <th class="">합계금액</th>
                <th class=" w-32"></th>
            </tr>
        </thead>
        <tbody>
            <?
            if (!empty($estimate_all)) :
                foreach ($estimate_all as $estimate) :
            ?>

                    <tr class="border-b hover:bg-gray-50" onclick="go_detail('<?= $estimate['id'] ?>')">
                        <td><input type="checkbox" /></td>
                        <td class="">2025-10-24</td>
                        <td class="">주식회사 지아이베컴</td>
                        <td class="">31,834,400</td>
                        <td class="">3,183,940</td>
                        <td class="">35,023,340</td>
                        <td class="cursor-pointer">
                            <div class="flex items-center gap-1">
                                <img src="https://ai.serp.co.kr/img/serp/btn/btn_send.png" alt="">
                                <span class="font-semibold">상세보기</span>
                            </div>
                        </td>
                    </tr>
                <? endforeach;
            else : ?>
                <tr>
                    <td colspan="7" class="text-center py-4">등록된 견적서가 없습니다.</td>
                </tr>
            <? endif; ?>

            <tr class="border-b bg-[#e9f1fb]">
                <td></td>
                <td class=""></td>
                <td class="!font-bold">총 2건</td>
                <td class="!font-bold">공급가액 : 34,609,400</td>
                <td class="!font-bold">세액 : 3,460,940</td>
                <td class="!font-bold">합계금액 : 38,070,340</td>
                <td class="cursor-pointer">
                </td>
            </tr>

        </tbody>
    </table>

</div>

<script>
    function go_detail(estimate_id) {
        open_popup_default(`/sales/estimate_detail?id=${estimate_id}`, '견적서 상세', 1000, 820);
    }
</script>