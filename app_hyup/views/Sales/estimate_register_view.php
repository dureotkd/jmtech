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
        color: #666;
    }
</style>
<link rel="stylesheet" href="/assets/app_hyup/lib/pqgrid/pqgrid.css" />
<link rel="stylesheet" href="/assets/app_hyup/lib/pqgrid/pqgrid.min.css" />

<form id="form1" onsubmit="handle_form_submit(event);">
    <div class="w-full !px-2 !text-xs font-sans font-300">
        <input type="hidden" id="sheetData"
            value='<?= json_encode($sheets, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT) ?>'>

        <div class="w-full relative flex justify-center items-center mb-4">
            <img
                class="!mb-2 mx-auto"
                src="/assets/app_hyup/images/견적서.png" alt="견적서">

            <!-- <div class="absolute right-2 top-2 px-2 py-1 text-xs cursor-pointer hover:underline">
                거래내역 불러오기
            </div> -->
        </div>

        <div class="flex !border-x-2 !border-t-2 !border-black">
            <!-- 왼쪽: 견적 정보 -->
            <div class="relative flex-1 border-r !border-b border-black !p-3 !pr-14">
                <div class="!space-y-2">
                    <div class="flex items-center">
                        <label class="w-[75px]">거 래 처 명 :</label>
                        <div class="flex items-center">
                            <input type="text" id="searchBox" class="border w-[250px] h-[24px]" />
                            <input type="hidden" name="partner_id" />
                            <button class="bg-gray-200 border border-gray-400 h-[24px] px-2 text-xs" style="border-left: none !important;">🔍</button>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <label class="w-[75px]">견 적 일 자 :</label>
                        <input type="date" name="estimate_date" class="border flatpickr w-[180px] h-[24px] px-1 flatpicker" />
                    </div>

                    <div class="flex items-center">
                        <label class="w-[75px]">전 화 번 호 :</label>
                        <input type="text" name="phone_number" oninput="phoneNumberMask(this)"
                            class="border w-[100px] h-[24px] px-1" />
                        <span class="!ml-2 w-[75px]">팩 스 번 호 : </span>
                        <input type="text" name="fax_number" oninput="phoneNumberMask(this)" class="border w-[100px] h-[24px] !px-1" />
                    </div>

                    <div class="flex items-center">
                        <label class="w-[75px]">제 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목 :</label>
                        <input type="text" name="title" class="border flex-1 h-[24px] px-1" />
                    </div>

                </div>

                <p class="absolute bottom-[10px] font-semibold text-[13px]">
                    견적요청에 감사드리며 아래와 같이 견적합니다.
                </p>
            </div>

            <!-- 오른쪽: 공급자 정보 -->
            <div class="w-[580px] !border-l border-black">
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
            <input type="text" class="border w-[150px] h-[24px] !ml-1 px-1" value="₩0" readonly />
        </div>

        <div class="flex items-center justify-between !my-1 !py-1">

            <select name="" id="">
                <?
                $VAT_CONTROL = unserialize(VAT_CONTROL);

                foreach ($VAT_CONTROL as $key => $value) {
                ?>
                    <option value="<?= $key ?>"><?= $value ?></option>
                <?
                }
                ?>
            </select>

            <div class="flex items-center gap-1">
                <!-- Excel Button -->
                <button
                    onclick="my_modal_1.showModal()"
                    type="button"
                    class="flex items-center gap-1 border border-gray-300 rounded h-7 !px-1 bg-white hover:bg-gray-50 transition text-xs">
                    <img width="16" alt="Logo of Microsoft Excel since 2019" src="https://png.pngtree.com/element_our/sm/20180627/sm_5b33460f04516.jpg">
                    <span>일괄등록</span>
                </button>

                <!-- Plus Button -->
                <button
                    onclick="add_row()"
                    type="button"
                    class="flex items-center justify-center w-7 h-7 border border-gray-300 rounded bg-white hover:bg-gray-50 transition">
                    <span class="text-blue-600 !text-xl !font-bold !mb-1 leading-none">+</span>
                </button>

                <!-- Minus Button -->
                <button
                    onclick="remove_row()"
                    type="button"
                    class="flex items-center justify-center w-7 h-7 border border-gray-300 rounded bg-white hover:bg-gray-50 transition">
                    <span class="text-red-500 !text-xl !font-bold leading-none">−</span>
                </button>
            </div>
        </div>


    </div>

    <div class="!border-2 !border-black !mx-[9px]">
        <div class="sheet-tabs flex border-b border-gray-300 bg-gray-100">
            <?php foreach ($sheets as $sheet): ?>
                <button
                    type="button"
                    id="sheet_<?= $sheet['name'] ?>"
                    onclick="showSheet('<?= $sheet['name'] ?>')"
                    class="tab-btn px-4 py-2 text-sm font-medium border-r border-gray-300 
             bg-gray-100 hover:bg-gray-200 transition-colors
             focus:outline-none"
                    data-sheet="<?= $sheet['name'] ?>">
                    <?= $sheet['name'] ?>
                </button>
            <?php endforeach; ?>
        </div>

        <div id="sheetContainer" class="!w-full"></div>
        <table class="tg !border-t-2 !border-black">
            <thead>
                <tr>
                    <th class="tg-0pky !border-t !w-[100px] !text-center !text-black th-bg">납기일자</th>
                    <th class="tg-0pky !border-t w-[400px]">
                        <input type="date" name="due_at" class="text-black flatpickr border w-full h-[24px] px-1" value="" />
                    </th>
                    <th class="tg-0pky !border-t th-bg !w-[100px] !text-center">납품장소</th>
                    <th class="tg-0pky">
                        <input type="text" name="location" class="text-black border w-full h-[24px] px-1" value="" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="tg-0pky !border-1 text-center th-bg">유효일자</td>
                    <td class="tg-0pky !border-1 w-[400px]">
                        <input type="date" name="valid_at" class="text-black flatpickr border w-full h-[24px] px-1" value="" />
                    </td>
                    <td class="tg-0pky !border-1 th-bg !w-[100px] !text-center">결제조건</td>
                    <td class="tg-0pky !border-1">
                        <input type="text" name="payment_type" class="text-black border w-full h-[24px] px-1" value="" />
                    </td>
                </tr>
                <tr>
                    <td class="tg-0pky text-center th-bg ">비고</td>
                    <td class="tg-0pky" colspan="3">
                        <input type="text" name="etc_memo" class="text-black border w-full h-[24px] px-1" value="" />
                    </td>
                </tr>
            </tbody>
        </table>

    </div>

    <div class="w-full !px-2 !text-xs font-sans font-300">
        <div class="flex items-center gap-4">
            <button
                type="button"
                id="attachBtn"
                class="!my-2 flex items-center gap-1 border border-gray-300 rounded h-7 !text-xs !px-1 bg-white hover:bg-gray-50 transition text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-paperclip-icon lucide-paperclip">
                    <path d="m16 6-8.414 8.586a2 2 0 0 0 2.829 2.829l8.414-8.586a4 4 0 1 0-5.657-5.657l-8.379 8.551a6 6 0 1 0 8.485 8.485l8.379-8.551" />
                </svg>
                <span>첨부파일</span>
            </button>

            <!-- 파일 표시 영역 -->
            <div id="fileList" class="flex items-center flex-wrap gap-2 text-sm"></div>
        </div>

        <!-- 실제 파일 input (숨김) -->
        <input type="file" id="fileInput" class="hidden" multiple />

    </div>

    <div class="w-full !px-2 !text-[13px] flex justify-center items-center gap-1.5 font-sans font-300 !my-2">
        <!-- 저장 후 인쇄 -->
        <button
            type="button"
            class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
            저장 후 인쇄
        </button>

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
</form>


<dialog id="my_modal_1" class="modal">
    <div class="modal-box !text-xs !w-[400px] relative">

        <div class="absolute inset-0 modal-loading hidden">
            <div class="flex items-center justify-center w-full h-full bg-white/70">
                <img class="w-16" src="/assets/app_hyup/images/loading.gif" alt="loading" />
            </div>
        </div>

        <form id="exce_form" onsubmit="handle_excel_form(event);" class="bg-white w-full border border-gray-300">
            <!-- 헤더 -->
            <div class="flex justify-between items-center !text-base !px-4 !py-2 bg-[#4b5563]">
                <h2 class="text-white font-semibold">엑셀 불러오기</h2>
                <button type="button" class="text-gray-200" onclick="close_modal_1();">
                    ✕
                </button>
            </div>

            <!-- 본문 -->
            <div class="!p-5 !space-y-4">
                <!-- 서식 다운로드 -->
                <div class="flex justify-end text-sm text-gray-700 items-center">
                    <a href="#" class="flex items-center text-blue-600 hover:underline">
                        견적서 품목양식
                        <svg
                            xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="ml-1 lucide lucide-download-icon lucide-download">
                            <path d="M12 15V3" />
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <path d="m7 10 5 5 5-5" />
                        </svg>
                    </a>
                </div>

                <!-- 파일선택 -->
                <div class="flex items-center">
                    <label class="block text-sm font-semibold w-[70px] mb-1">파일선택</label>
                    <div class="flex !w-[300px]">
                        <!-- 파일 이름 표시 input -->
                        <input
                            type="text"
                            id="fileNameInput"
                            placeholder="파일을 선택하세요"
                            class="flex-1 border border-gray-300 !px-2 !py-1.5"
                            readonly />

                        <!-- 실제 파일 input (숨김) -->
                        <input
                            id="excelFileInput"
                            type="file"
                            accept=".xls,.xlsx"
                            class="hidden"
                            onchange="change_excel_file(event)" />

                        <!-- 파일열기 버튼 -->
                        <button
                            type="button"
                            class="bg-gray-200 border border-l-0 border-gray-300 px-3 hover:bg-gray-300"
                            onclick="$('#excelFileInput').click()">
                            파일열기
                        </button>
                    </div>
                </div>

                <!-- 시트선택 -->
                <div class="flex items-center">
                    <label class="block text-sm font-semibold w-[70px] mb-1">시트선택</label>
                    <select
                        class="sheet_select w-[300px] border border-gray-300 !px-2 !py-1">
                        <?
                        foreach ($sheets as $sheet) {
                        ?>
                            <option value="<?= $sheet['name'] ?>">
                                <?= $sheet['name'] ?>
                            </option>
                        <?
                        }
                        ?>
                    </select>
                </div>
            </div>

            <!-- 하단 버튼 -->
            <div class="w-full !px-2 !text-[13px] flex justify-center items-center gap-1.5 font-sans font-300 !my-2">
                <!-- 저장 후 인쇄 -->
                <button
                    type="button"
                    onclick="$('#exce_form').submit();"
                    class="px-2 py-1 bg-[#4b8edc] text-white hover:bg-[#3d7ac0]">
                    불러오기
                </button>

                <!-- 취소 -->
                <button
                    type="button"
                    onclick="close_modal_1();"
                    class="px-2 py-1 bg-[#fff] text-gray-700 hover:bg-gray-100 border border-gray-300">
                    취소
                </button>
            </div>
        </form>
    </div>
</dialog>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>

<script>
    // ✅ PHP에서 넘어온 JSON 읽기
    const sheets = Object.values(JSON.parse(document.getElementById('sheetData').value));
    const containers = {};

    document.addEventListener('DOMContentLoaded', async () => {

        start_loading();
        // await wait(700);

        // * Handsontable 초기화 (Excel)
        initializeHandsontable();

        // * 거래처 목록 가져오기
        fetchPartners();

        stop_loading();
    });

    function fetchPartners() {
        let availableTags = []

        $.ajax({
            type: "GET",
            url: "/sales/get_partner_list",
            dataType: "json",
            success: function(response) {
                availableTags = response;
            }
        });

        $("#searchBox").autocomplete({
                minLength: 1,
                delay: 100,
                source: availableTags,
                // ✅ hover 시 input 값 바꾸지 않음
                focus: function() {
                    return false; // 🔥 여기서 UI만 유지하고 값은 변경 안 함
                },
                select: function(event, ui) {
                    console.log("선택:", ui.item);
                    $("#searchBox").val(ui.item.company_name);
                    return false;
                },
                source: function(request, response) {
                    const term = $.trim(request.term).toLowerCase();

                    const results = availableTags.filter(item => {
                        // label, person, account 전부 검색 조건 포함
                        return (
                            item.company_name.toLowerCase().includes(term) ||
                            item.company_num.toLowerCase().includes(term) ||
                            item.ceo_name.toLowerCase().includes(term)
                        );
                    });

                    // ✅ 최대 30개까지만 보여줌
                    const limitedResults = results.slice(0, 30);

                    response(limitedResults);
                },
            })
            // ✅ 항목 렌더링 커스텀 + 하이라이트
            .data("ui-autocomplete")._renderItem = function(ul, item) {
                const term = this.term.toLowerCase(); // 사용자가 입력한 검색어
                const highlight = (text) => {
                    if (!term) return text;
                    const regex = new RegExp("(" + term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ")", "gi");
                    return text.replace(regex, '<span class="highlight">$1</span>');
                };

                return $("<li>")
                    .append(`
      <div class="item-row">
        <div class="item-name">${highlight(item.company_name)}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
        <div class="item-person">${highlight(item.ceo_name)}</div>
        <div class="item-account">${highlight(item.company_num)}</div>
      </div>
    `)
                    .appendTo(ul);
            };
    }

    function initializeHandsontable() {
        const hfInstance = HyperFormula.buildEmpty({});
        const sheetContainer = document.getElementById('sheetContainer');
        const hotInstances = {};

        const reverseSheets = [...sheets].reverse();
        for (const reverseSheet of reverseSheets) {
            hfInstance.addSheet(reverseSheet.name);
        }

        for (const sheet of sheets) {
            const div = document.createElement('div');
            div.style.display = 'none';
            sheetContainer.appendChild(div);
            containers[sheet.name] = div;

            // 🔧 시트별 초기 데이터 세팅 (원하면 서버에서 주입 가능)
            const initData = sheet.data && sheet.data.length ? sheet.data : [
                [null, null]
            ];

            hotInstances[sheet.name] = new Handsontable(div, {
                data: initData,
                formulas: {
                    engine: hfInstance,
                    sheetName: sheet.name,
                },
                colHeaders: function(col) {
                    const title = sheet.title;
                    return `${title[col]} (${getColumnLetter(col)})`;
                },
                columns: sheet.columns,
                rowHeaders: true,
                width: '100%',
                height: sheet.height || 'auto',
                fixedRowsTop: 0,
                colWidths: !empty(sheet.colWidth) ? sheet.colWidth : [100, 100],
                autoWrapRow: true,
                autoWrapCol: true,

                renderAllRows: false, // ✅ 가상 스크롤만 렌더
                rowHeights: 23,
                stretchH: 'all',
                viewportRowRenderingOffset: 20, // 화면 근처 20행만 유지

                afterChange: sheet.name === '견적서' ? function(changes, source) {
                    // * 0번쨰 품목 수정시
                    if (source === 'edit' && changes[0][3]?.key) {
                        const hot = hotInstances[sheet.name]; // ✅ 현재 시트의 Handsontable 인스턴스 가져오기

                        changes.forEach(([row, prop, oldValue, newValue]) => {
                            if (prop === 0 && oldValue !== newValue.title) {
                                console.log(newValue)
                                hot.setDataAtRowProp(row, 0, newValue.title); // * ㅋ명
                            }

                            // 품목이 변경되면 관련 셀 자동 입력
                            // const info = itemInfo[newValue];
                            // const hot = hotInstances[sheet.name];
                            // hot.setDataAtRowProp(row, 1, info['규격']); // 규격
                            // hot.setDataAtRowProp(row, 3, info['단가']); // 단가
                            // hot.setDataAtRowProp(row, 5, info['세액']); // 세액
                        });
                    }

                } : null,
                licenseKey: 'non-commercial-and-evaluation',
            });
        }

        // 전역 참조
        window._handsontable = {
            hfInstance,
            containers,
            hotInstances,
            sheets
        };

        showSheet(sheets[0].name);

    }

    // ✅ 시트 전환
    function showSheet(name) {
        const {
            containers
        } = window._handsontable;

        Object.values(containers).forEach((el) => {

            el.style.display = 'none';
        });
        containers[name].style.display = 'block';

        $(`.tab-btn`).removeClass('active');
        $(`#sheet_${name}`).addClass('active');
    }

    // ✅ 행 추가/삭제 (현재 표시 중인 시트 기준)
    function add_row() {
        const {
            containers,
            hotInstances
        } = window._handsontable;
        const activeName = Object.keys(containers).find(
            (key) => containers[key].style.display === 'block'
        );
        const hot = hotInstances[activeName];
        hot.alter('insert_row_above', hot.countRows());
    }

    function remove_row() {
        const {
            containers,
            hotInstances
        } = window._handsontable;
        const activeName = Object.keys(containers).find(
            (key) => containers[key].style.display === 'block'
        );
        const hot = hotInstances[activeName];
        if (hot.countRows() > 1) hot.alter('remove_row', hot.countRows() - 1);
    }

    const $fileInput = $('#fileInput');
    const $fileList = $('#fileList');

    // 버튼 클릭 → 파일 선택창 열기
    $('#attachBtn').on('click', function() {
        $fileInput.click();
    });

    // 파일 선택 시 이벤트
    $fileInput.on('change', function(e) {
        const newFiles = Array.from(e.target.files);

        // 새로운 파일을 기존 배열에 병합 (중복 방지)
        newFiles.forEach(f => {
            if (!filesArray.find(existing => existing.name === f.name && existing.size === f.size)) {
                filesArray.push(f);
            }
        });

        renderFileList();
        $fileInput.val(''); // input 초기화 (같은 파일 다시 선택 가능)
    });

    // 파일 리스트 렌더링
    function renderFileList() {
        $fileList.empty();

        filesArray.forEach((file, idx) => {
            const $item = $(`
        <div class="flex items-center gap-1 border border-gray-200 rounded !px-2 !py-1 bg-gray-50">
          <span class="text-gray-700 truncate max-w-[300px]">${file.name}</span>
          <button type="button" class="text-gray-400 hover:text-red-500 transition text-xs" data-idx="${idx}">✕</button>
        </div>
      `);
            $fileList.append($item);
        });
    }

    // 삭제 버튼 클릭 시
    $fileList.on('click', 'button', function() {
        const idx = $(this).data('idx');
        filesArray.splice(idx, 1); // 배열에서 제거
        renderFileList(); // 다시 렌더링
    });
</script>

<script>
    let filesArray = []; // 첨부된 파일 목록 저장용

    function handle_form_submit(e) {
        e.preventDefault(); // 기본 폼 제출 방지

        start_loading();

        const formData = new FormData(e.target);
        const serial = $(e.target).serializeArray();

        serial.forEach(item => {
            formData.append(item.name, item.value);
        });

        if (!empty(filesArray)) {

            filesArray.forEach((file, index) => {
                formData.append('files[]', file);
            });
        }

        /**
         * * handsontable 입력된 데이터 전부 가져오기.
         */
        const sheet_data = get_sheet_data('견적서');
        formData.append('sheet_data', JSON.stringify(sheet_data));

        $.ajax({
            type: "POST",
            url: "/sales/save_estimate",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                alert(response.msg);

                if (response.ok) {
                    // 저장 후 처리 (예: 페이지 리로드 또는 다른 작업)
                }
            },
            error: function(xhr, status, error) {
                alert(`견적서 저장 중 오류가 발생했습니다. 관리자에게 문의하세요.`);
            },
            complete: function() {
                stop_loading();
            }
        });
    }

    function get_sheet_data(sheetName) {
        const {
            hotInstances
        } = window._handsontable;
        const hot = hotInstances[sheetName];
        return hot.getSourceData();
    }
</script>

<script>
    flatpickr(".flatpickr", {
        dateFormat: "Y-m-d", // 날짜 형식: 2025-10-28
        locale: "ko", // ✅ 한글 로케일 지정
        // defaultDate: new Date(), // 기본값: 오늘 날짜
        disableMobile: true, // 모바일에서도 같은 UI 유지 (선택)
    });

    // * 엑셀 일괄등록 모달
    const excel_upload_modal = document.getElementById('my_modal_1');

    function close_modal_1() {
        excel_upload_modal.close();
    }

    function change_excel_file(event) {
        const file = event.target.files[0];
        if (file) {
            // 파일명 표시
            $('#fileNameInput').val(file.name);
            console.log("선택된 파일:", file.name);
        } else {
            $('#fileNameInput').val('');
        }
    }

    async function handle_excel_form(event) {
        event.preventDefault(); // 폼 기본 전송 막기

        const fileInput = $('#excelFileInput')[0];
        const file = fileInput.files?.[0];

        // if (!file) {
        //     alert('엑셀 파일을 선택해주세요.');
        //     return;
        // }

        start_modal_loading();

        await wait(500);

        const formData = new FormData();

        formData.append('excel_file', file);
        formData.append('sheet_name', $('select.sheet_select').val());

        $.ajax({
            type: "POST",
            url: "/sales/estimate_excel_load",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: async function(res) {

                if (res.ok) {

                    const data = res.data; // PHP에서 보낸 엑셀 파싱 결과 배열

                    const {
                        hotInstances
                    } = window._handsontable;
                    const activeName = $('select.sheet_select').val();
                    const hot = hotInstances[activeName];

                    // 기존 데이터 가져오기
                    const currentData = hot.getSourceData();

                    // 기존 + 새 데이터 병합
                    const mergedData = [...data];

                    // * 페이징 처리
                    // const itemsPerPage = 100; // 페이지당 항목 수
                    // const totalPages = Math.ceil(mergedData.length / itemsPerPage);
                    // let currentPage = 1;
                    // const pageList = mergedData.slice(0, itemsPerPage);

                    // 한번에 반영 (초고속)
                    hot.suspendRender();
                    await hot.loadData(mergedData);
                    hot.resumeRender();
                    hot.render();

                    close_modal_1();

                } else {
                    alert(res.msg);
                }

                // close_modal_1();
            },
            error: function(xhr, status, error) {
                alert('엑셀 파일의 양식이 올바르지 않습니다. 다시 확인해주세요.');
            },
            complete: async function() {
                await wait(1500);

                stop_modal_loading();
            }
        });
    }
</script>