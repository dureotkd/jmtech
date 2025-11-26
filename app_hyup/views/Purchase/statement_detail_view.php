<?
$datetime = date('YmdHis');
?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>

<style>
    input {
        padding-left: 6px !important;
    }

    table th {
        color: #fff;
        font-weight: 400;
        padding: 8px;
        text-align: left;
        font-size: 12px;
    }

    /* 헤더 행 */
    #example th {
        font-family: 'NanumGothicBold', sans-serif;
        background: #d9d9d9 !important;
        color: black;
    }

    /* 일반 셀 */
    #example .ht_master td {
        font-family: 'NanumGothicRegular', sans-serif;
        font-size: 13px;
        color: black;
    }

    table td {
        padding: 8px;
        font-size: 12px;
        color: #000;
        font-weight: 300;
        border-bottom: 1px solid black;
    }

    .ui-autocomplete {
        max-height: 300px;
        /* 보여줄 최대 높이 */
        overflow-y: auto;
        /* 세로 스크롤 활성화 */
        overflow-x: hidden;
        /* 가로 스크롤 숨김 */
        z-index: 9999 !important;
        /* 다른 요소보다 위에 표시 */
    }

    .tg-0pky {
        border-right: 1px solid black;
        border-left: 1px solid black;
    }

    .ui-menu {
        min-width: 450px !important;
    }

    /* dropdown 스타일 */
    .ui-autocomplete {
        /* max-height: 220px; */
        overflow-y: auto;
        border: 1px solid #ddd;
        background: #fff;
        font-size: 14px;
        border-radius: 4px;
        z-index: 9999;
    }

    .ui-menu-item-wrapper {
        padding: 6px 10px;
        background-color: #fff !important;
    }

    /* jQuery UI 기본 hover 효과 제거 */
    .ui-state-active,
    .ui-menu-item-wrapper:hover {
        background: none !important;
        border: none !important;
        margin: 0 !important;
        padding: 6px 10px !important;
        /* 원래 높이 유지 */
        font-weight: normal !important;
        color: inherit !important;
        background-color: #bdbdbd !important;
    }

    /* 🔧 Autocomplete hover시 padding 안변하게 고정 */
    .ui-menu-item-wrapper,
    .ui-menu-item-wrapper.ui-state-active {
        padding: 6px 10px !important;
        /* 고정 패딩 */
        margin: 0 !important;
        background: none !important;
        border: none !important;
        font-weight: normal !important;
        color: inherit !important;
        line-height: 1.4;
    }


    /* 항목 내부 커스텀 스타일 */
    .item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13px;
    }

    .item-name {
        font-weight: 500;
        width: 250px;
        color: #111;
    }

    .item-person {
        width: 50px;
        color: #555;
    }

    .item-account {
        width: 120px;
        font-family: monospace;
    }

    table.statement {
        border-collapse: collapse;
        text-align: center;
    }

    table.statement th,
    table.statement td {
        padding: 4px 6px;
    }

    table.statement thead th {
        font-weight: bold;
        text-align: center;
    }

    .tg {
        border-collapse: collapse;
        border-spacing: 0;
    }

    .tg td {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-size: 14px;
        overflow: hidden;
        padding: 4px 6px;
        word-break: normal;
        font-size: 12px;
        color: #000;
        font-weight: 300;
    }

    .tg th {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-size: 14px;
        font-weight: normal;
        overflow: hidden;
        padding: 4px 6px;
        word-break: normal;
    }

    .tg .tg-dvpl {
        border-color: inherit;
        text-align: right;
        vertical-align: top
    }

    .tg .tg-0lax {
        text-align: left;
        vertical-align: top
    }

    /* ------------- 기본 테이블 스타일 ------------- */

    /* ------------- 블루 테이블 스타일 ------------- */

    .blue-table th {
        border-right: 1px solid blue !important;
        border-bottom: 1px solid blue !important;
    }

    .blue-table td {
        border-bottom: 1px solid blue !important;
        border-right: 1px solid blue !important;
    }

    .blue-table .tg-0pky {
        color: blue !important;
    }

    .blue-text {
        color: blue !important;
    }

    .blue-table {
        border: 2px solid blue !important;
    }

    .blue-table th {
        border: 1px solid blue !important;
    }

    .blue-table td {
        border: 1px solid blue !important;
    }


    .blue-table .th-bg {
        background-color: #e5f0ff !important;
        color: #1E40AF !important;
    }


    .item-blue-table {
        border: 2px solid blue !important;
    }

    .item-blue-table thead th {
        border: 1px solid blue !important;
        background-color: #e5f0ff !important;
        color: #1E40AF !important;
    }

    .item-blue-table tbody td {
        border: 1px solid blue !important;
    }


    /* ------------- 블루 테이블 스타일 ------------- */

    /* ------------- 레드 테이블 스타일 ------------- */

    .item-red-table {
        border: 2px solid red !important;
    }

    .item-red-table thead th {
        border: 1px solid red !important;
        background-color: #ffe5e5 !important;
        color: #ff0000 !important;
    }

    .item-red-table tbody td {
        border: 1px solid red !important;
    }

    .red-table {
        border: 2px solid red !important;
    }

    .red-table th {
        border: 1px solid red !important;
    }

    .red-table td {
        border: 1px solid red !important;
    }

    .red-table .tg-0pky {
        color: red !important;
    }

    .red-table .th-bg {
        background-color: #ffe5e5 !important;
        color: red !important;
    }

    .red-text {
        color: red !important;
    }

    /* ------------- 레드 테이블 스타일 ------------- */
    /* .blue-table .tg-0pky {
        border-right: 1px solid red;
        border-left: 1px solid red;
    } */
</style>

<!-- 공급받는자 보관용 -->
<div id="printArea1" style="display: none;">
    <div class="w-full !px-2 !text-xs font-sans font-300">

        <div class="flex items-center justify-between w-full print-hide">
            <!-- 왼쪽 버튼 그룹 -->
            <div class="flex space-x-2">
                <button onclick="window.location.href = '<?= REACT_PATH ?>?id=<?= $statement['id'] ?>&sub_type=<?= $statement['sub_type'] ?>'" class="flex items-center border border-gray-400 px-3 py-1 gap-1  text-xs hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-pen-line-icon lucide-file-pen-line">
                        <path d="m18.226 5.226-2.52-2.52A2.4 2.4 0 0 0 14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.351" />
                        <path d="M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                        <path d="M8 18h1" />
                    </svg>
                    <span>
                        수정
                    </span>
                </button>
                <button onclick="handle_delete(event);" class="flex items-center border border-gray-400 px-3 py-1 gap-1  text-xs hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2">
                        <path d="M10 11v6" />
                        <path d="M14 11v6" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                        <path d="M3 6h18" />
                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                    </svg>
                    <span>
                        삭제
                    </span>
                </button>
                <button onclick="handle_copy(event);" class="flex items-center border border-gray-400 px-3 py-1 gap-1  text-xs hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layers2-icon lucide-layers-2">
                        <path d="M13 13.74a2 2 0 0 1-2 0L2.5 8.87a1 1 0 0 1 0-1.74L11 2.26a2 2 0 0 1 2 0l8.5 4.87a1 1 0 0 1 0 1.74z" />
                        <path d="m20 14.285 1.5.845a1 1 0 0 1 0 1.74L13 21.74a2 2 0 0 1-2 0l-8.5-4.87a1 1 0 0 1 0-1.74l1.5-.845" />
                    </svg>
                    <span>
                        복사
                    </span>
                </button>
            </div>

            <!-- 오른쪽 버튼 그룹 -->
            <div class="flex space-x-2">

                <div class="dropdown dropdown-end !mr-2">
                    <div tabindex="0" role="button">

                        <button type="button" class="flex items-center border border-gray-400 px-3 py-1 gap-1 text-xs hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer-icon lucide-printer">
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" />
                                <rect x="6" y="14" width="12" height="8" rx="1" />
                            </svg>
                            <span>
                                인쇄
                            </span>
                        </button>
                    </div>
                    <ul
                        tabindex="-1"
                        class="!min-w-[210px] !border !border-gray-300 !bg-white !mt-2 items-center justify-center font-sans menu dropdown-content z-1 mt-4 w-52 shadow-sm">

                        <div class="w-full flex flex-col justify-start !text-xs">

                            <button onclick="handle_print1(event);"
                                class="!text-left flex items-center gap-2 border-b-1 border-gray-300 !p-4 sm-hover" type="button">

                                <span>
                                    공급받는자 인쇄
                                </span>
                            </button>

                            <button class="flex items-center gap-2 !text-left !p-4 sm-hover" onclick="handle_print2(event);" type="button">

                                <span>
                                    공급자 인쇄
                                </span>
                            </button>

                        </div>
                    </ul>
                </div>

                <div class="dropdown dropdown-end !mr-2">
                    <div tabindex="0" role="button">
                        <button onclick="handle_pdf(event);" class="flex items-center border border-gray-400 px-3 py-1 gap-1 text-xs hover:bg-gray-100">
                            <img width="14" alt="Logo of Microsoft Excel since 2019" src="https://media.istockphoto.com/id/1298834280/ko/%EB%B2%A1%ED%84%B0/pdf-%EC%95%84%EC%9D%B4%EC%BD%98-%EC%A3%BC%EC%9A%94-%ED%8C%8C%EC%9D%BC-%ED%98%95%EC%8B%9D-%EB%B2%A1%ED%84%B0-%EC%95%84%EC%9D%B4%EC%BD%98-%EA%B7%B8%EB%A6%BC.jpg?s=612x612&amp;w=0&amp;k=20&amp;c=p1hZH6NRAUA1tToGtDQ5weAxeJhVjtdlkhCD7Tsra0g=">
                            PDF
                        </button>
                    </div>
                    <ul
                        tabindex="-1"
                        class="!min-w-[210px] !border !border-gray-300 !bg-white !mt-2 items-center justify-center font-sans menu dropdown-content z-1 mt-4 w-52 shadow-sm">

                        <div class="w-full flex flex-col justify-start !text-xs">

                            <button onclick="handle_print1(event);"
                                class="!text-left flex items-center gap-2 border-b-1 border-gray-300 !p-4 sm-hover" type="button">

                                <span>
                                    공급받는자 PDF로 저장
                                </span>
                            </button>

                            <button class="flex items-center gap-2 !text-left !p-4 sm-hover" onclick="handle_print2(event);" type="button">

                                <span>
                                    공급자 PDF로 저장
                                </span>
                            </button>

                        </div>
                    </ul>
                </div>

                <button onclick="handle_excel(event);" class="flex items-center border border-gray-400 px-3 py-1 gap-1 text-xs hover:bg-gray-100">
                    <img width="16" alt="Logo of Microsoft Excel since 2019" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e3/Microsoft_Office_Excel_%282019%E2%80%932025%29.svg/32px-Microsoft_Office_Excel_%282019%E2%80%932025%29.svg.png?20190925171014">
                    <span>
                        엑셀
                    </span>
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between w-full !my-2 print-show">
            <img class="object-cover h-8" src="/assets/app_hyup/images/logo.png" alt="로고">

            <div>
                <table class="tg">
                    <thead>
                        <tr>
                            <td class="tg-dvpl text-center bg-[#d9d9d9] !font-semibold !align-middle" rowspan="2">
                                결<br /><br />제
                            </td>
                            <td class="tg-0lax !font-semibold !text-center !align-middle">기안</td>
                            <td class="tg-0lax !font-semibold !text-center">검토</td>
                            <td class="tg-0lax !font-semibold !text-center">승인</td>
                        </tr>
                        <tr>
                            <td class="tg-0lax w-[60px] h-[60px]"></td>
                            <td class="tg-0lax w-[60px] h-[60px]"></td>
                            <td class="tg-0lax w-[60px] h-[60px]"></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="w-full relative flex justify-center items-center !mt-4 relative">
            <img
                class="!mb-2 mx-auto"
                src="/assets/app_hyup/images/매입 거래명세표.png" alt="견적서">

            <!-- <div class="absolute right-2 top-2 px-2 py-1 text-xs cursor-pointer hover:underline">
                거래내역 불러오기
            </div> -->
        </div>

        <div class="flex">
            <!-- 왼쪽: 견적 정보 -->
            <div class="w-[42%] relative flex-1 !pr-3">
                <div class="!space-y-2 relative">
                </div>

                <div class="!gap-4">
                    <div class="flex flex-col gap-3 !mt-2">
                        <div class="flex">
                            <div class="flex">
                                <label class="<?= $reverse_text_theme ?>">
                                    일&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;자 :
                                </label>
                                <p class="!ml-6">
                                    <?= $statement['estimate_date'] ?>
                                </p>
                            </div>
                            <div class="flex !ml-12">
                                <label class="<?= $reverse_text_theme ?>">
                                    등&nbsp;록&nbsp;번&nbsp;호 :
                                </label>
                                <p class="!ml-6">
                                    <?= $statement['estimate_date'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="flex">
                            <label class="<?= $reverse_text_theme ?>">
                                거&nbsp;&nbsp;&nbsp;래&nbsp;&nbsp;&nbsp;처 :
                            </label>
                            <p class="!ml-6">
                                <?= $statement['partner_name'] ?>
                            </p>
                        </div>
                        <div class="flex">
                            <label class="<?= $reverse_text_theme ?>">
                                주&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;소 :
                            </label>
                            <p class="!ml-6">
                                <?= $statement['partner_name'] ?>
                            </p>
                        </div>
                        <div class="flex">
                            <div class="flex">
                                <label class="<?= $reverse_text_theme ?>">
                                    전&nbsp;화&nbsp;번&nbsp;호 :
                                </label>
                                <p class="!ml-6">
                                    <?= $statement['phone_number'] ?>
                                </p>
                            </div>
                            <div class="flex !ml-7.5">
                                <label class="<?= $reverse_text_theme ?>">
                                    팩&nbsp;스&nbsp;번&nbsp;호 :
                                </label>
                                <p class="!ml-6">
                                    <?= $statement['fax_number'] ?>
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center !mt-[17px] <?= $reverse_table_theme ?> !px-2 !py-1.5 !text-[15px] font-semibold">
                            <h2 class=" <?= $reverse_text_theme ?>">합계금액 : </h2>
                            <h2>
                                <?= number_format($statement['amount']) ?>
                            </h2>
                        </div>
                    </div>

                    <div class="!space-x-1 !mt-2">
                        <?
                        if (!empty($phone_number)) {
                        ?>
                            <span>
                                전&nbsp;&nbsp;화&nbsp;&nbsp;번&nbsp;&nbsp;호 : <?= $statement['phone_number'] ?>
                            </span>
                        <?
                        }
                        ?>

                        <?
                        if (!empty($phone_number)) {
                        ?>
                            <span>
                                팩&nbsp;&nbsp;스&nbsp;&nbsp;번&nbsp;&nbsp;호 : <?= $statement['fax_number'] ?>
                            </span>
                        <?
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- 오른쪽: 공급자 정보 -->
            <div class="w-[58%]">
                <table class="w-full border-collapse text-sm <?= $reverse_table_theme ?>">
                    <col style="width: 35px">
                    <col style="width: 82px">
                    <col style="width: 25px">
                    <col style="width: 25px">
                    <col style="width: 53px">
                    <col style="width: 86px">
                    </colgroup>
                    <thead>
                        <tr>
                            <td class="tg-0pky !text-center">등록번호</td>
                            <td class="tg-jgcz" colspan="6"><span style="color:#000">312-86-30100</span></td>
                        </tr>
                        <tr>
                            <td class="tg-0pky !text-center">상&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;호</td>
                            <td class="tg-wjrz" colspan="3">제이엠테크</td>
                            <td class="tg-0pky !text-center">성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명</td>
                            <td class="tg-0lax">
                                <div class="!relative flex items-center">
                                    <span>전용준</span>
                                    <img
                                        class="w-14 h-14 absolute left-6 -top-4"
                                        src="/assets/app_hyup/images/stamp.png"
                                        alt="stamp" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="tg-0pky !text-center">주&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;소</td>
                            <td class="" colspan="5">충청남도 천안시 서북구 두정공단1길 149-2 (두정동, 미라클(주)) 제이엠테크</td>
                        </tr>
                        <tr>
                            <td class="tg-0pky !text-center">업&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;태</td>
                            <td class="" colspan="3">제조업</td>
                            <td class="tg-0pky !text-center">종&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목</td>
                            <td class="tg-0lax">산업기계 설계 및 개발</td>
                        </tr>
                        <tr>
                            <td class="tg-0pky !text-center">전화번호</td>
                            <td class="" colspan="3">041-483-1111</td>
                            <td class="tg-0pky !text-center">팩스번호</td>
                            <td class="tg-0lax">041-1111-1111</td>
                        </tr>
                    </thead>
                </table>
            </div>


        </div>

    </div>

    <div class="!mx-[9px] !my-1">
        <table class="statement item-<?= $reverse_table_theme ?> w-full">
            <colgroup>
                <col style="width:100px"> <!-- 순번 -->
                <col style="width:300px"> <!-- 품목 -->
                <col style="width:120px"> <!-- 규격 -->
                <col style="width:80px"> <!-- 수량 -->
                <col style="width:100px"> <!-- 단가 -->
                <col style="width:120px"> <!-- 공급가액 -->
                <col style="width:120px"> <!-- 세액 -->
                <col style="width:120px"> <!-- 비고 -->
            </colgroup>

            <thead>
                <tr>
                    <th>월일</th>
                    <th>품목</th>
                    <th>규격</th>
                    <th>수량</th>
                    <th>단가</th>
                    <th>공급가액</th>
                    <th>세액</th>
                    <th>비고</th>
                </tr>
            </thead>
            <tbody>
                <?
                if (!empty($sheets)) {

                    $no = count($sheets);

                    foreach ($sheets as $index => $item) {

                        if (empty($item[1])) {
                            continue;
                        }
                ?>
                        <tr>
                            <td><?= $item[0] ?></td>
                            <td class="text-left">
                                <?= $item[1] ?>
                            </td>
                            <td>
                                <?= $item[2] ?>
                            </td>
                            <td class="!text-right">
                                <?= $item[3] ?>
                            </td>
                            <td class="!text-right">
                                <?= !empty($item[4]) ? number_format($item[4]) : '' ?>
                            </td>
                            <td class="!text-right">
                                <?= !empty($item[5]) ? number_format($item[5]) : '' ?>
                            </td>
                            <td class="!text-right">
                                <?= !empty($item[6]) ? number_format($item[6]) : '' ?>
                            </td>
                            <td></td>
                        </tr>
                    <?
                    }
                } else {
                    ?>

                <?
                }
                ?>
            </tbody>
        </table>


        <table class="w-full  border  tg !mt-2 w-full <?= $reverse_table_theme ?>">

            <colgroup>
                <col style="width:100px"> <!-- 순번 -->
                <col style="width:305px"> <!-- 품목 -->
                <col style="width:120px"> <!-- 규격 -->
                <col style="width:80px"> <!-- 수량 -->
                <col style="width:105px"> <!-- 단가 -->
                <col style="width:120px"> <!-- 공급가액 -->
                <col style="width:120px"> <!-- 세액 -->
                <col style="width:120px"> <!-- 비고 -->
            </colgroup>

            <thead>
                <tr>
                    <th class="!border-t !text-xs !text-center th-bg">전미수잔액</th>
                    <th class="!border-t !text-xs" colspan="2">
                    </th>
                    <th class="!border-t !text-xs th-bg !text-center" colspan="2">합계</th>
                    <th class="!border-t !text-xs !text-black !text-right">
                        <?= number_format($statement['supply_amount']) ?>
                    </th>
                    <th class="!border-t !text-xs !text-black !text-right">
                        <?= number_format($statement['tax_amount']) ?>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="!text-xs !border-1 text-center th-bg">총합계</td>
                    <td class="!text-xs !border-1 !text-right">
                        <?= number_format($statement['amount']) ?>
                    </td>

                    <td class="!text-xs !border-1 th-bg !w-[80px] text-center">입금액</td>
                    <td class="!text-xs !border-1">
                    </td>

                    <td class="!text-xs !border-1 th-bg !w-[80px] text-center">총미수잔액</td>
                    <td class="!text-xs !border-1">
                    </td>

                    <td class="!text-xs !border-1 th-bg !w-[80px] text-center">인수자</td>
                    <td class="!text-xs !border-1">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="w-full !px-2 !text-xs font-sans font-300 print-hide !mb-4">

        <div class="flex items-center gap-2 flex-wrap !my-3">
            <?
            if (!empty($files)) {
                foreach ($files as $file) {
            ?>
                    <div
                        class="flex items-center border border-gray-300 rounded !px-4 !py-2 bg-gray-100">
                        <a
                            href="/sales/download_file?id=<?= $file['id'] ?>"
                            class="text-blue-600 hover:underline">
                            <?= $file['file_name'] ?>
                        </a>
                    </div>
                <?
                }
            } else {
                ?>
                <span class="text-gray-500">첨부된 파일이 없습니다.</span>
            <?
            }
            ?>
        </div>
    </div>

</div>

<!-- 공급자 보관용 -->
<div id="printArea2">
    <div class="w-full !px-2 !text-xs font-sans font-300">

        <div class="flex items-center justify-between w-full print-hide">
            <!-- 왼쪽 버튼 그룹 -->
            <div class="flex space-x-2">
                <button onclick="window.location.href = '<?= REACT_PATH ?>?id=<?= $statement['id'] ?>&sub_type=<?= $statement['sub_type'] ?>'" class="flex items-center border border-gray-400 px-3 py-1 gap-1  text-xs hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-pen-line-icon lucide-file-pen-line">
                        <path d="m18.226 5.226-2.52-2.52A2.4 2.4 0 0 0 14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-.351" />
                        <path d="M21.378 12.626a1 1 0 0 0-3.004-3.004l-4.01 4.012a2 2 0 0 0-.506.854l-.837 2.87a.5.5 0 0 0 .62.62l2.87-.837a2 2 0 0 0 .854-.506z" />
                        <path d="M8 18h1" />
                    </svg>
                    <span>
                        수정
                    </span>
                </button>
                <button onclick="handle_delete(event);" class="flex items-center border border-gray-400 px-3 py-1 gap-1  text-xs hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2">
                        <path d="M10 11v6" />
                        <path d="M14 11v6" />
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                        <path d="M3 6h18" />
                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                    </svg>
                    <span>
                        삭제
                    </span>
                </button>
                <button onclick="handle_copy(event);" class="flex items-center border border-gray-400 px-3 py-1 gap-1  text-xs hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layers2-icon lucide-layers-2">
                        <path d="M13 13.74a2 2 0 0 1-2 0L2.5 8.87a1 1 0 0 1 0-1.74L11 2.26a2 2 0 0 1 2 0l8.5 4.87a1 1 0 0 1 0 1.74z" />
                        <path d="m20 14.285 1.5.845a1 1 0 0 1 0 1.74L13 21.74a2 2 0 0 1-2 0l-8.5-4.87a1 1 0 0 1 0-1.74l1.5-.845" />
                    </svg>
                    <span>
                        복사
                    </span>
                </button>
            </div>

            <!-- 오른쪽 버튼 그룹 -->
            <div class="flex space-x-2">

                <div class="dropdown dropdown-end !mr-2">
                    <div tabindex="0" role="button">

                        <button type="button" class="flex items-center border border-gray-400 px-3 py-1 gap-1 text-xs hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer-icon lucide-printer">
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" />
                                <rect x="6" y="14" width="12" height="8" rx="1" />
                            </svg>
                            <span>
                                인쇄
                            </span>
                        </button>
                    </div>
                    <ul
                        tabindex="-1"
                        class="!min-w-[210px] !border !border-gray-300 !bg-white !mt-2 items-center justify-center font-sans menu dropdown-content z-1 mt-4 w-52 shadow-sm">

                        <div class="w-full flex flex-col justify-start !text-xs">

                            <button onclick="handle_print1(event);"
                                class="!text-left flex items-center gap-2 border-b-1 border-gray-300 !p-4 sm-hover" type="button">

                                <span>
                                    공급받는자 인쇄
                                </span>
                            </button>

                            <button class="flex items-center gap-2 !text-left !p-4 sm-hover" onclick="handle_print2(event);" type="button">

                                <span>
                                    공급자 인쇄
                                </span>
                            </button>

                        </div>
                    </ul>
                </div>

                <div class="dropdown dropdown-end !mr-2">
                    <div tabindex="0" role="button">
                        <button class="flex items-center border border-gray-400 px-3 py-1 gap-1 text-xs hover:bg-gray-100">
                            <img width="14" alt="Logo of Microsoft Excel since 2019" src="https://media.istockphoto.com/id/1298834280/ko/%EB%B2%A1%ED%84%B0/pdf-%EC%95%84%EC%9D%B4%EC%BD%98-%EC%A3%BC%EC%9A%94-%ED%8C%8C%EC%9D%BC-%ED%98%95%EC%8B%9D-%EB%B2%A1%ED%84%B0-%EC%95%84%EC%9D%B4%EC%BD%98-%EA%B7%B8%EB%A6%BC.jpg?s=612x612&amp;w=0&amp;k=20&amp;c=p1hZH6NRAUA1tToGtDQ5weAxeJhVjtdlkhCD7Tsra0g=">
                            PDF
                        </button>
                    </div>
                    <ul
                        tabindex="-1"
                        class="!min-w-[210px] !border !border-gray-300 !bg-white !mt-2 items-center justify-center font-sans menu dropdown-content z-1 mt-4 w-52 shadow-sm">

                        <div class="w-full flex flex-col justify-start !text-xs">

                            <button onclick="handle_pdf1(event);"
                                class="!text-left flex items-center gap-2 border-b-1 border-gray-300 !p-4 sm-hover" type="button">

                                <span>
                                    공급받는자 PDF로 저장
                                </span>
                            </button>

                            <button onclick="handle_pdf2(event);" class="flex items-center gap-2 !text-left !p-4 sm-hover" type="button">

                                <span>
                                    공급자 PDF로 저장
                                </span>
                            </button>

                        </div>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between w-full !my-2 print-show">
            <img class="object-cover h-8" src="/assets/app_hyup/images/logo.png" alt="로고">

            <div>
                <table class="tg">
                    <thead>
                        <tr>
                            <td class="tg-dvpl text-center bg-[#d9d9d9] !font-semibold !align-middle" rowspan="2">
                                결<br /><br />제
                            </td>
                            <td class="tg-0lax !font-semibold !text-center !align-middle">기안</td>
                            <td class="tg-0lax !font-semibold !text-center">검토</td>
                            <td class="tg-0lax !font-semibold !text-center">승인</td>
                        </tr>
                        <tr>
                            <td class="tg-0lax w-[60px] h-[60px]"></td>
                            <td class="tg-0lax w-[60px] h-[60px]"></td>
                            <td class="tg-0lax w-[60px] h-[60px]"></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div id="printAreaContent2">

            <div class="w-full relative flex justify-center items-center !mt-4 relative">
                <img
                    class="!mb-2 mx-auto"
                    src="/assets/app_hyup/images/<?= $statement['sub_type'] == 'MI' ? '매입 거래명세표' : '매출 거래명세표' ?>.png" alt="견적서">
            </div>

            <div class="flex">
                <!-- 왼쪽: 견적 정보 -->
                <div class="w-[42%] relative flex-1 !pr-3">
                    <div class="!space-y-2 relative">
                    </div>

                    <div class="!gap-4">
                        <div class="flex flex-col gap-3 !mt-2">
                            <div class="flex">
                                <div class="flex">
                                    <label class="<?= $text_theme ?>">
                                        일&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;자 :
                                    </label>
                                    <p class="!ml-6">
                                        <?= $statement['estimate_date'] ?>
                                    </p>
                                </div>
                                <div class="flex !ml-12">
                                    <label class="<?= $text_theme ?>">
                                        등&nbsp;록&nbsp;번&nbsp;호 :
                                    </label>
                                    <p class="!ml-6">
                                        <?= $statement['estimate_date'] ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex">
                                <label class="<?= $text_theme ?>">
                                    거&nbsp;&nbsp;&nbsp;래&nbsp;&nbsp;&nbsp;처 :
                                </label>
                                <p class="!ml-6">
                                    <?= $statement['partner_name'] ?>
                                </p>
                            </div>
                            <div class="flex">
                                <label class="<?= $text_theme ?>">
                                    주&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;소 :
                                </label>
                                <p class="!ml-6">
                                    <?= $statement['partner_name'] ?>
                                </p>
                            </div>
                            <div class="flex">
                                <div class="flex">
                                    <label class="<?= $text_theme ?>">
                                        전&nbsp;화&nbsp;번&nbsp;호 :
                                    </label>
                                    <p class="!ml-6">
                                        <?= $statement['phone_number'] ?>
                                    </p>
                                </div>
                                <div class="flex !ml-7.5">
                                    <label class="<?= $text_theme ?>">
                                        팩&nbsp;스&nbsp;번&nbsp;호 :
                                    </label>
                                    <p class="!ml-6">
                                        <?= $statement['fax_number'] ?>
                                    </p>
                                </div>
                            </div>

                            <div class="flex justify-between items-center !mt-[17px] <?= $table_theme ?> !px-2 !py-1.5 !text-[15px] font-semibold">
                                <h2 class=" <?= $text_theme ?>">합계금액 : </h2>
                                <h2>
                                    <?= number_format($statement['amount']) ?>
                                </h2>
                            </div>
                        </div>

                        <div class="!space-x-1 !mt-2">
                            <?
                            if (!empty($phone_number)) {
                            ?>
                                <span>
                                    전&nbsp;&nbsp;화&nbsp;&nbsp;번&nbsp;&nbsp;호 : <?= $statement['phone_number'] ?>
                                </span>
                            <?
                            }
                            ?>

                            <?
                            if (!empty($phone_number)) {
                            ?>
                                <span>
                                    팩&nbsp;&nbsp;스&nbsp;&nbsp;번&nbsp;&nbsp;호 : <?= $statement['fax_number'] ?>
                                </span>
                            <?
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- 오른쪽: 공급자 정보 -->
                <div class="w-[58%]">
                    <table class="w-full border-collapse text-sm <?= $table_theme ?>">
                        <col style="width: 35px">
                        <col style="width: 82px">
                        <col style="width: 25px">
                        <col style="width: 25px">
                        <col style="width: 53px">
                        <col style="width: 86px">
                        </colgroup>
                        <thead>
                            <tr>
                                <td class="tg-0pky !text-center">등록번호</td>
                                <td class="tg-jgcz" colspan="6"><span style="color:#000">312-86-30100</span></td>
                            </tr>
                            <tr>
                                <td class="tg-0pky !text-center">상&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;호</td>
                                <td class="tg-wjrz" colspan="3">제이엠테크</td>
                                <td class="tg-0pky !text-center">성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명</td>
                                <td class="tg-0lax">
                                    <div class="!relative flex items-center">
                                        <span>전용준</span>
                                        <img
                                            class="w-14 h-14 absolute left-6 -top-4"
                                            src="/assets/app_hyup/images/stamp.png"
                                            alt="stamp" />
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="tg-0pky !text-center">주&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;소</td>
                                <td class="" colspan="5">충청남도 천안시 서북구 두정공단1길 149-2 (두정동, 미라클(주)) 제이엠테크</td>
                            </tr>
                            <tr>
                                <td class="tg-0pky !text-center">업&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;태</td>
                                <td class="" colspan="3">제조업</td>
                                <td class="tg-0pky !text-center">종&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목</td>
                                <td class="tg-0lax">산업기계 설계 및 개발</td>
                            </tr>
                            <tr>
                                <td class="tg-0pky !text-center">전화번호</td>
                                <td class="" colspan="3">041-483-1111</td>
                                <td class="tg-0pky !text-center">팩스번호</td>
                                <td class="tg-0lax">041-1111-1111</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

        </div>

    </div>

    <div class="!mx-[9px] !my-1">
        <table class="statement item-<?= $table_theme ?> w-full">
            <colgroup>
                <col style="width:100px"> <!-- 순번 -->
                <col style="width:300px"> <!-- 품목 -->
                <col style="width:120px"> <!-- 규격 -->
                <col style="width:80px"> <!-- 수량 -->
                <col style="width:100px"> <!-- 단가 -->
                <col style="width:120px"> <!-- 공급가액 -->
                <col style="width:120px"> <!-- 세액 -->
                <col style="width:120px"> <!-- 비고 -->
            </colgroup>

            <thead>
                <tr>
                    <th>월일</th>
                    <th>품목</th>
                    <th>규격</th>
                    <th>수량</th>
                    <th>단가</th>
                    <th>공급가액</th>
                    <th>세액</th>
                    <th>비고</th>
                </tr>
            </thead>
            <tbody>
                <?
                if (!empty($sheets)) {

                    $no = count($sheets);

                    foreach ($sheets as $index => $item) {

                        if (empty($item[1])) {
                            continue;
                        }
                ?>
                        <tr>
                            <td><?= $item[0] ?></td>
                            <td class="text-left">
                                <?= $item[1] ?>
                            </td>
                            <td>
                                <?= $item[2] ?>
                            </td>
                            <td class="!text-right">
                                <?= $item[3] ?>
                            </td>
                            <td class="!text-right">
                                <?= !empty($item[4]) ? number_format($item[4]) : '' ?>
                            </td>
                            <td class="!text-right">
                                <?= !empty($item[5]) ? number_format($item[5]) : '' ?>
                            </td>
                            <td class="!text-right">
                                <?= !empty($item[6]) ? number_format($item[6]) : '' ?>
                            </td>
                            <td></td>
                        </tr>
                    <?
                    }
                } else {
                    ?>

                <?
                }
                ?>
            </tbody>
        </table>


        <table class="w-full  border  tg !mt-2 w-full <?= $table_theme ?>">

            <colgroup>
                <col style="width:100px"> <!-- 순번 -->
                <col style="width:305px"> <!-- 품목 -->
                <col style="width:120px"> <!-- 규격 -->
                <col style="width:80px"> <!-- 수량 -->
                <col style="width:105px"> <!-- 단가 -->
                <col style="width:120px"> <!-- 공급가액 -->
                <col style="width:120px"> <!-- 세액 -->
                <col style="width:120px"> <!-- 비고 -->
            </colgroup>

            <thead>
                <tr>
                    <th class="!border-t !text-xs !text-center th-bg">전미수잔액</th>
                    <th class="!border-t !text-xs" colspan="2">
                    </th>
                    <th class="!border-t !text-xs th-bg !text-center" colspan="2">합계</th>
                    <th class="!border-t !text-xs !text-black !text-right">
                        <?= number_format($statement['supply_amount']) ?>
                    </th>
                    <th class="!border-t !text-xs !text-black !text-right">
                        <?= number_format($statement['tax_amount']) ?>
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="!text-xs !border-1 text-center th-bg">총합계</td>
                    <td class="!text-xs !border-1 !text-right">
                        <?= number_format($statement['amount']) ?>
                    </td>

                    <td class="!text-xs !border-1 th-bg !w-[80px] text-center">입금액</td>
                    <td class="!text-xs !border-1">
                    </td>

                    <td class="!text-xs !border-1 th-bg !w-[80px] text-center">총미수잔액</td>
                    <td class="!text-xs !border-1">
                    </td>

                    <td class="!text-xs !border-1 th-bg !w-[80px] text-center">인수자</td>
                    <td class="!text-xs !border-1">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="w-full !px-2 !text-xs font-sans font-300 print-hide !mb-4">

        <div class="flex items-center gap-2 flex-wrap !my-3">
            <?
            if (!empty($files)) {
                foreach ($files as $file) {
            ?>
                    <div
                        class="flex items-center border border-gray-300 rounded !px-4 !py-2 bg-gray-100">
                        <a
                            href="/sales/download_file?id=<?= $file['id'] ?>"
                            class="text-blue-600 hover:underline">
                            <?= $file['file_name'] ?>
                        </a>
                    </div>
                <?
                }
            } else {
                ?>
                <span class="text-gray-500">첨부된 파일이 없습니다.</span>
            <?
            }
            ?>
        </div>
    </div>


</div>

<div class="!space-y-1 !px-2 !text-xs print-hide">

    <?
    if (!empty($event_logs)) {
        foreach ($event_logs as $log) {
    ?>
            <div class="flex items-center justify-between !border !border-gray-300 !bg-gray-50 !px-3 !py-2 text-gray-700">
                <div class="gap-6 flex items-center">
                    <span class="text-gray-500">
                        <?= $log['created_at'] ?>
                    </span>
                    <span class="font-medium">
                        <?= $log['event_action'] ?>
                    </span>
                </div>
                <span class="text-gray-600">
                    <?= $log['admin_name'] ?>
                </span>
            </div>
    <?
        }
    }
    ?>
</div>




<script>
    const handle_delete = (e) => {
        if (confirm('정말로 삭제하시겠습니까? \n삭제된 명세표는 복구할 수 없습니다.')) {
            window.location.href = '/purchase/report/delete_statement?id=<?= $statement['id'] ?>';
        }
    }

    const handle_copy = (e) => {
        window.location.href = `<?= REACT_PATH ?>?tab=copy&id=<?= $statement['id'] ?>&sub_type=<?= $statement['sub_type'] ?>`;
    }

    const eventId = '<?= $statement['id'] ?>';
    const eventTable = 'statement';

    // * 공급자 인쇄 */
    const handle_print2 = (e) => {

        $.ajax({
            type: "POST",
            url: "/api/log_event",
            async: true,
            data: {
                event_type: '인쇄',
                event_id: eventId,
                event_table: eventTable
            },
            dataType: "json",
        });

        $("#printArea1").hide();
        $("#printArea2").show();
        window.print()
    }

    // * 공급받는자 인쇄 */
    const handle_print1 = (e) => {

        $.ajax({
            type: "POST",
            url: "/api/log_event",
            async: true,
            data: {
                event_type: '인쇄',
                event_id: eventId,
                event_table: eventTable
            },
            dataType: "json",
        });

        $("#printArea2").hide();
        $("#printArea1").show();
        window.print()
        $("#printArea1").hide();
        $("#printArea2").show();
    }

    // * 공급받는자 PDF */
    const handle_pdf1 = (e) => {

        $.ajax({
            type: "POST",
            url: "/api/log_event",
            async: true,
            data: {
                event_type: 'PDF출력',
                event_id: eventId,
                event_table: eventTable
            },
            dataType: "json",
        });

        start_loading();

        window.location.href = '<?= REACT_PATH ?>?id=<?= $statement['id'] ?>&main_type=pdf&sub_type=MI'

        setTimeout(() => {
            stop_loading();
        }, 1000);
    }

    // * 공급자 PDF */
    const handle_pdf2 = (e) => {

        $.ajax({
            type: "POST",
            url: "/api/log_event",
            async: true,
            data: {
                event_type: 'PDF출력',
                event_id: eventId,
                event_table: eventTable
            },
            dataType: "json",
        });

        start_loading();

        window.location.href = '<?= REACT_PATH ?>?id=<?= $statement['id'] ?>&main_type=pdf&sub_type=MC'

        setTimeout(() => {
            stop_loading();
        }, 1000);
    }

    const handle_excel = (e) => {

        $.ajax({
            type: "POST",
            url: "/api/log_event",
            async: true,
            data: {
                event_type: '엑셀출력',
                event_id: eventId,
                event_table: eventTable
            },
            dataType: "json",
        });

        start_loading();

        window.location.href = '/sales/download_statement_excel?id=<?= $statement['id'] ?>';

        setTimeout(() => {
            stop_loading();
        }, 1000);
    }
</script>