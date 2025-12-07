<?
$datetime = date('YmdHis');
?>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
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

    table.estimate {
        border-collapse: collapse;
        text-align: center;
    }

    table.estimate th,
    table.estimate td {
        border: 1px solid #555;
        padding: 4px 6px;
    }

    table.estimate thead th {
        background-color: #d9d9d9;
        color: #000;
        font-weight: bold;
        text-align: center;
    }

    table.estimate tbody tr:nth-child(even) {
        background-color: #fafafa;
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
        padding: 10px 5px;
        word-break: normal;
    }

    .tg th {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-size: 14px;
        font-weight: normal;
        overflow: hidden;
        padding: 10px 5px;
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
</style>
<link rel="stylesheet" href="/assets/app_hyup/lib/pqgrid/pqgrid.css" />
<link rel="stylesheet" href="/assets/app_hyup/lib/pqgrid/pqgrid.min.css" />

<div>
    <div class="w-full !px-2 !text-xs font-sans font-300">

        <div class="flex items-center justify-between w-full print-hide">
            <!-- 왼쪽 버튼 그룹 -->
            <div class="flex space-x-2">
                <button type="button" class="flex items-center border border-gray-400 px-3 py-1 gap-1 text-xs">

                    <?
                    if (!empty($estimate['su_estimate_id']) && $estimate['sub_type'] == 'G') {
                    ?>
                        <a href="/sales/estimate_detail?id=<?= $estimate['id'] ?>" class="!text-blue-600 hover:underline">
                            <?= $estimate['gu_status'] ?>
                        </a>
                        <span class="!mx-0.5 !text-blue-600">
                            >
                        </span>
                        <a href="/sales/estimate_detail?id=<?= $estimate['su_estimate_id'] ?>" class="!text-blue-600 hover:underline">
                            <?= $estimate['su_status'] ?>
                        </a>
                    <?
                    } else if (!empty($estimate['g_estimate_id']) && $estimate['sub_type'] == 'S') {
                    ?>
                        <a href="/sales/estimate_detail?id=<?= $estimate['g_estimate_id'] ?>" class="!text-blue-600 hover:underline">
                            <?= $estimate['gu_status'] ?>
                        </a>
                        <span class="!mx-0.5 !text-blue-600">
                            >
                        </span>
                        <a href="/sales/estimate_detail?id=<?= $estimate['id'] ?>" class="!text-blue-600 hover:underline">
                            <?= $estimate['su_status'] ?>
                        </a>
                    <?
                    } else {
                    ?>
                        <span>
                            <?= $estimate['gu_status'] ?? $estimate['su_status'] ?>
                        </span>
                    <?
                    }
                    ?>

                </button>

                <button onclick="window.location.href = '<?= REACT_PATH ?>?id=<?= $estimate['id'] ?>&sub_type=<?= $estimate['sub_type'] ?>'" class="flex items-center border border-gray-400 px-3 py-1 gap-1  text-xs hover:bg-gray-100">
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
                <button onclick="handle_print(event);" class="flex items-center border border-gray-400 px-3 py-1 gap-1 text-xs hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-printer-icon lucide-printer">
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                        <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6" />
                        <rect x="6" y="14" width="12" height="8" rx="1" />
                    </svg>
                    <span>
                        인쇄
                    </span>
                </button>
                <button onclick="handle_pdf(event);" class="flex items-center border border-gray-400 px-3 py-1 gap-1 text-xs hover:bg-gray-100">
                    <img width="14" alt="Logo of Microsoft Excel since 2019" src="https://media.istockphoto.com/id/1298834280/ko/%EB%B2%A1%ED%84%B0/pdf-%EC%95%84%EC%9D%B4%EC%BD%98-%EC%A3%BC%EC%9A%94-%ED%8C%8C%EC%9D%BC-%ED%98%95%EC%8B%9D-%EB%B2%A1%ED%84%B0-%EC%95%84%EC%9D%B4%EC%BD%98-%EA%B7%B8%EB%A6%BC.jpg?s=612x612&amp;w=0&amp;k=20&amp;c=p1hZH6NRAUA1tToGtDQ5weAxeJhVjtdlkhCD7Tsra0g=">
                    PDF
                </button>
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
                src="/assets/app_hyup/images/<?= $estimate['sub_type'] == 'G' ? '견적서' : '수주서' ?>.png" alt="견적서">

            <!-- <div class="absolute right-2 top-2 px-2 py-1 text-xs cursor-pointer hover:underline">
                거래내역 불러오기
            </div> -->
        </div>

        <div class="flex !border-x-2 !border-t-2 !border-black">
            <!-- 왼쪽: 견적 정보 -->
            <div class="w-[42%] relative flex-1 border-r !border-b border-black !p-3">
                <div class="!space-y-2 relative">
                    <h2 class="!text-lg font-semibold font-mono !border-b border-black pb-1 mb-1 text-center">
                        <?= $estimate['partner_name'] ?>

                    </h2>
                    <span class="absolute right-0 top-[7px]">
                        귀하
                    </span>
                </div>

                <div class="!gap-4 !mt-3">
                    <span>
                        견&nbsp;&nbsp;적&nbsp;&nbsp;일&nbsp;&nbsp;자 : <?= $estimate['estimate_date'] ?>
                    </span>
                    <br>

                    <div class="!space-x-1 !mt-2">
                        <?
                        if (!empty($phone_number)) {
                        ?>
                            <span>
                                전&nbsp;&nbsp;화&nbsp;&nbsp;번&nbsp;&nbsp;호 : <?= $estimate['phone_number'] ?>
                            </span>
                        <?
                        }
                        ?>

                        <?
                        if (!empty($phone_number)) {
                        ?>
                            <span>
                                팩&nbsp;&nbsp;스&nbsp;&nbsp;번&nbsp;&nbsp;호 : <?= $estimate['fax_number'] ?>
                            </span>
                        <?
                        }
                        ?>
                    </div>
                </div>

                <p class="absolute bottom-[10px] font-semibold text-[13px]">
                    견적요청에 감사드리며 아래와 같이 견적합니다.
                </p>
            </div>

            <!-- 오른쪽: 공급자 정보 -->
            <div class="w-[58%] !border-l border-black">
                <table class="w-full border-collapse text-sm border-l border-black">
                    <col style="width: 35px">
                    <col style="width: 82px">
                    <col style="width: 25px">
                    <col style="width: 25px">
                    <col style="width: 53px">
                    <col style="width: 86px">
                    </colgroup>
                    <thead>
                        <tr>
                            <td
                                class="tg-c3ow  bg-[#d9d9d9] !text-lg !font-semibold font-serif"
                                rowspan="6">공<br>급<br>자</td>
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
                            <td class="tg-0pky" colspan="3">제조업</td>
                            <td class="tg-0pky !text-center">종&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목</td>
                            <td class="tg-0lax">산업기계 설계 및 개발</td>
                        </tr>
                        <tr>
                            <td class="tg-0pky !text-center">전화번호</td>
                            <td class="tg-0pky" colspan="3">041-483-1111</td>
                            <td class="tg-0pky !text-center">팩스번호</td>
                            <td class="tg-0lax">041-1111-1111</td>
                        </tr>
                    </thead>
                </table>
            </div>


        </div>

        <div class="flex items-center mt-2 !px-4 !py-1 !border-x-2 !border-b-2 !border-black justify-start">
            <span class="font-semibold mr-2">합&nbsp;&nbsp;계&nbsp;&nbsp;금&nbsp;&nbsp;액 : 일금 </span>
            <h2 class="!text-sm font-bold !ml-4">
                <?= number_to_korean($estimate['supply_amount']) ?>
                ￦<?= number_format($estimate['supply_amount']) ?>원
                <?
                $VAT_TYPE = unserialize(VAT_TYPE);
                echo '(' . $VAT_TYPE[$estimate['vat_type']] . ')';
                ?>
            </h2>
        </div>

    </div>

    <div class="!border-2 !border-black !mx-[9px] !my-3">
        <table class="estimate">
            <thead>
                <tr>
                    <?
                    if ($estimate['sub_type'] == 'G') {
                    ?>
                        <th>순번</th>
                        <th>도면번호/품명</th>
                        <th>소재</th>
                        <th>수량</th>
                        <th>단위</th>
                        <th>단가</th>
                        <th>금액</th>
                        <th>비고</th>
                    <?
                    } else {
                    ?>
                        <th>순번</th>
                        <th>품목</th>
                        <th>규격</th>
                        <th>수량</th>
                        <th>단가</th>
                        <th>공급가액</th>
                        <th>세액</th>
                        <th>비고</th>
                    <?
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?
                if (!empty($sheets)) {

                    $no = count($sheets);

                    foreach ($sheets as $index => $item) {
                ?>
                        <tr>
                            <td><?= $no - $index ?></td>
                            <td class="text-left">
                                <?= $item[0] ?>
                            </td>
                            <td>
                                <?= $item[1] ?>
                            </td>
                            <td class="!text-right">
                                <?= $item[2] ?>
                            </td>
                            <td class="!text-right">
                                <?
                                if ($estimate['sub_type'] == 'G') {
                                ?>
                                    <?= $item[3] ?>
                                <?
                                } else {
                                ?>
                                    <?= !empty($item[4]) ? number_format($item[4]) : '' ?>
                                <?
                                }
                                ?>
                            </td>
                            <td class="!text-right">
                                <?= !empty($item[4]) ? number_format($item[4]) : '' ?>
                            </td>
                            <td class="!text-right">
                                <?= !empty($item[5]) ? number_format($item[5]) : '' ?>
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
        <table class="tg !border-t-2 !border-black">
            <thead>
                <tr>
                    <th class="tg-0pky !border-t !text-xs !w-[100px] !text-center !text-black th-bg">납기일자</th>
                    <th class="tg-0pky !border-t w-[400px]">
                        <?= $estimate['due_at'] ?>
                    </th>
                    <th class="tg-0pky !text-xs !border-t th-bg !w-[100px] !text-center">납품장소</th>
                    <th class="tg-0pky !text-xs">
                        <?= $estimate['location'] ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tg-0pky !text-xs !border-1 text-center th-bg">유효일자</td>
                    <td class="tg-0pky !text-xs !border-1 w-[400px]">
                        <?= $estimate['valid_at'] === '0000-00-00' ? '' : $estimate['valid_at'] ?>
                    </td>
                    <td class="tg-0pky !text-xs !border-1 th-bg !w-[100px] !text-center">결제조건</td>
                    <td class="tg-0pky !text-xs !border-1">
                        <?= $estimate['payment_type'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="tg-0pky !text-xs text-center th-bg ">비고</td>
                    <td class="tg-0pky !text-xs" colspan="3">
                        <?= htmlspecialchars($estimate['etc_memo']) ?>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <div class="w-full !px-2 !text-xs font-sans font-300 print-hide !mb-4">

        <div class="flex items-center gap-2 flex-wrap">
            <?
            if (!empty($files)) {
                foreach ($files as $file) {
            ?>
                    <a
                        href="/sales/download_file?id=<?= $file['id'] ?>"
                        class="flex items-center border border-gray-300 rounded !px-4 !py-2 gap-2 bg-gray-100">
                        <img src="<?= fileIcon($file['file_name']) ?>" class="w-4 h-4" />
                        <span
                            class="text-blue-600 hover:underline">
                            <?= $file['file_name'] ?>
                        </span>
                    </a>
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

<div class="!space-y-1 !px-2 !py-6 !text-xs print-hide">

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
    /**
     * * 접근시 팝업 사이즈를 1000, 820로 조정
     */
    $(document).ready(function() {
        window.resizeTo(1000, 820);
    });

    const handle_delete = (e) => {
        if (confirm('정말로 삭제하시겠습니까? \n삭제된 견적서는 복구할 수 없습니다.\n(관련된 수주서도 함께 삭제됩니다.)')) {
            window.location.href = '/sales/delete_estimate?id=<?= $estimate['id'] ?>';
        }
    }

    const handle_copy = (e) => {

        window.location.href = `<?= REACT_PATH ?>?tab=copy&id=<?= $estimate['id'] ?>&sub_type=<?= $estimate['sub_type'] ?>`;
    }

    const eventId = '<?= $estimate['id'] ?>';
    const eventTable = 'estimate';

    const handle_print = (e) => {

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

        window.print()

    }

    const handle_pdf = (e) => {

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

        window.location.href = '<?= REACT_PATH ?>?id=<?= $estimate['id'] ?>&main_type=pdf&sub_type=<?= $estimate['sub_type'] ?>'

        setTimeout(() => {
            stop_loading();
        }, 1000);
    }


    const handle_excel = async () => {
        start_loading();

        try {
            const res = await fetch(`/sales/download_estimate_excel?id=<?= $estimate['id'] ?>`);

            const blob = await res.blob();
            const url = window.URL.createObjectURL(blob);

            const a = document.createElement("a");
            a.href = url;
            a.download = "<?= $estimate['partner_name'] ?>_<?= $estimate['sub_type'] == 'G' ? '견적서' : '수주서' ?>.xlsx"; // 원하는 파일명
            a.click();

            window.URL.revokeObjectURL(url);
        } catch (err) {
            console.error(err);
        } finally {
            stop_loading(); // 다운로드 완료 후 실행됨

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
        }
    };
</script>