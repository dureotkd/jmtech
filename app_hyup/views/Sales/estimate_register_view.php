<?
$datetime = date('YmdHis');
?>

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

    .tg-0pky {
        border-right: 1px solid black;
        border-left: 1px solid black;
    }
</style>
<link rel="stylesheet" href="/assets/app_hyup/lib/pqgrid/pqgrid.css" />
<link rel="stylesheet" href="/assets/app_hyup/lib/pqgrid/pqgrid.min.css" />

<div class="w-full !px-2 !text-xs font-sans font-300">

    <div class="w-full relative flex justify-center items-center mb-4">
        <img
            class="!mb-2 mx-auto"
            src="/assets/app_hyup/images/견적서.png" alt="견적서">

        <div class="absolute right-2 top-2 px-2 py-1 text-xs cursor-pointer hover:underline">
            거래내역 불러오기
        </div>
    </div>

    <div class="flex !border-x-2 !border-t-2 !border-black">
        <!-- 왼쪽: 견적 정보 -->
        <div class="relative flex-1 border-r !border-b border-black !p-3 !pr-14">
            <div class="!space-y-2">
                <div class="flex items-center">
                    <label class="w-[75px]">거 래 처 명 :</label>
                    <div class="flex items-center">
                        <input type="text" class="border w-[250px] h-[24px]" />
                        <button class="bg-gray-200 border border-gray-400 h-[24px] px-2 text-xs" style="border-left: none !important;">🔍</button>
                    </div>
                </div>

                <div class="flex items-center">
                    <label class="w-[75px]">견 적 일 자 :</label>
                    <input type="date" class="border w-[180px] h-[24px] px-1" value="2025-10-25" />
                </div>

                <div class="flex items-center">
                    <label class="w-[75px]">전 화 번 호 :</label>
                    <input type="text" class="border w-[100px] h-[24px] px-1" />
                    <span class="!ml-2 w-[75px]">팩 스 번 호 : </span>
                    <input type="text" class="border w-[100px] h-[24px] !px-1" />
                </div>

                <div class="flex items-center">
                    <label class="w-[75px]">제 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;목 :</label>
                    <input type="text" class="border flex-1 h-[24px] px-1" />
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
                class="flex items-center gap-1 border border-gray-300 rounded h-7 !px-1 bg-white hover:bg-gray-50 transition text-sm">
                <img width="16" alt="Logo of Microsoft Excel since 2019" src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e3/Microsoft_Office_Excel_%282019%E2%80%932025%29.svg/32px-Microsoft_Office_Excel_%282019%E2%80%932025%29.svg.png?20190925171014">
                <span>일괄등록</span>
            </button>

            <!-- Plus Button -->
            <button
                class="flex items-center justify-center w-7 h-7 border border-gray-300 rounded bg-white hover:bg-gray-50 transition">
                <span class="text-blue-600 !text-xl !font-bold !mb-1 leading-none">+</span>
            </button>

            <!-- Minus Button -->
            <button
                class="flex items-center justify-center w-7 h-7 border border-gray-300 rounded bg-white hover:bg-gray-50 transition">
                <span class="text-red-500 !text-xl !font-bold leading-none">−</span>
            </button>
        </div>
    </div>


</div>

<div class="!border-2 !border-black !mx-[9px]">
    <div id="example" class="!max-w-full"></div>
    <div class="">
        <table class="w-full border-collapse border border-black text-center">
            <tbody>
                <tr>
                    <!-- "합계" 글씨칸 (2칸 병합) -->
                    <td colspan="6" class="bg-[#d9d9d9] w-[50px] font-semibold text-sm text-black font-serif p-2">
                        합계
                    </td>

                    <!-- 합계 금액 3칸 -->
                    <td class="p-2">₩0</td>
                    <td class="p-2">₩0</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js?v=<?= $datetime ?>"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const hfInstance = HyperFormula.buildEmpty({
            licenseKey: 'internal-use-in-handsontable',
        });

        const container = document.getElementById('example');

        const hot = new Handsontable(container, {
            data: [
                ['철판', 'SS400', 10, 15000, '=D1*E1', '=F1*0.1', ''],
                ['볼트', 'M10', 20, 500, '=D2*E2', '=F2*0.1', ''],
                ['너트', 'M10', 20, 400, '=D3*E3', '=F3*0.1', ''],
                ['용접봉', '6013', 5, 10000, '=D4*E4', '=F4*0.1', ''],
                ['기타', '', 1, 20000, '=D5*E5', '=F5*0.1', ''],
                ['합계', '', 1, 20000, '=D5*E5', '=F5*0.1', ''],
            ],

            // ✅ 여기서 헤더 지정
            colHeaders: function(col) {
                const letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
                const titles = ['품목', '규격', '수량', '단가', '공급가액', '세액', '비고'];
                return `${titles[col]} ${letters[col]}`;
            },
            colWidths: [344, 120, 80, 100, 120, 100, 150],

            rowHeaders: true,
            height: 'auto',
            width: '100%',
            autoWrapRow: true,
            autoWrapCol: true,

            formulas: {
                engine: hfInstance,
                sheetName: 'Sheet1',
            },

            mergeCells: [
                // {row, col, rowspan, colspan}
                {
                    row: 5,
                    col: 0,
                    rowspan: 1,
                    colspan: 3
                }, // “합계”를 왼쪽 3칸 병합
            ],

            columns: [{
                    data: 0
                }, // 품목
                {
                    data: 1
                }, // 규격
                {
                    data: 2,
                    type: 'numeric',
                    numericFormat: {
                        pattern: '0,0'
                    },
                    allowInvalid: false
                },
                {
                    data: 3,
                    type: 'numeric',
                    numericFormat: {
                        pattern: '0,0'
                    },
                    allowInvalid: false
                },
                {
                    data: 4,
                    type: 'numeric',
                    numericFormat: {
                        pattern: '0,0'
                    },
                },
                {
                    data: 5,
                    type: 'numeric',
                    numericFormat: {
                        pattern: '0,0'
                    },
                },
                {
                    data: 6
                }, // 비고
            ],

            // ✅ 특정 셀 스타일 지정
            cells(row, col) {
                const cellProperties = {};

                // 오른쪽 정렬 열들 → 규격(1), 수량(2), 단가(3), 공급가액(4), 세액(5)
                const rightAlignedCols = [1, 2, 3, 4, 5];
                if (rightAlignedCols.includes(col)) {
                    cellProperties.className = 'htRight'; // Handsontable 기본 오른쪽 정렬 클래스
                }

                // “합계” 행 스타일
                if (row === 5) {
                    cellProperties.className = '!bg-[#d9d9d9] !font-bold text-black htRight font-serif';
                }

                return cellProperties;
            },

            licenseKey: 'non-commercial-and-evaluation',
        });
    });
</script>