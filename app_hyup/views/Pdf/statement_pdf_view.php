<style>
    body {
        font-family: "NanumGothic";
    }

    .table {
        border-collapse: collapse;
        width: 100%;
    }

    table,
    th,
    td {
        font-size: 10pt;
    }


    .table th {
        font-weight: 300 !important;
    }

    .table th,
    .table td {
        /* border: 0.75px solid #000; */
        /* padding: 4px; */
        /* text-align: center; */
    }

    .header {
        text-align: center;
        font-size: 20pt;
        font-weight: 300;
        margin-bottom: 10px;
    }

    .section {
        border: 1px solid #000;
        margin-top: 5px;
    }

    .title {
        background: '#788496';
    }

    .no-border td {
        border: none;
    }

    .pdf_title {
        font-weight: 900;
        border-bottom: 1px solid black;
        display: inline-block;
        width: 95px;
        margin: 12px auto;
        letter-spacing: 5px;
        /* 🔹 글자 간격을 10px 만큼 띄움 */
    }
</style>
<style>
    .supplier-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10pt;
    }

    .supplier-table td {
        border: 1px solid #000;
        padding: 5px 6px;
        vertical-align: middle;
    }

    .supplier-table .title {
        text-align: center;
    }

    .supplier-table .section {
        background-color: #d9d9d9;
        text-align: center;
        line-height: 1.2;
        width: 7%;
    }

    .supplier-table .value {
        text-align: left;
        padding-left: 6px;
    }

    .supplier-table .value.center {
        text-align: center;
    }
</style>

<table style="width:100%; margin-bottom:4px; border-collapse:collapse;">
    <tr>
        <td style="width:150px; vertical-align:middle;">
            <img src="https://www.jmtech.asia/assets/app_hyup/images/logo.png" alt="로고"
                style="width:120px;">
        </td>
        <td style="text-align:left; vertical-align:right; padding-left:95px;">
            <img class="!mb-2 mx-auto" src="https://www.jmtech.asia/assets/app_hyup/images/<?= $statement['sub_type'] == 'MI' ? '매입 거래명세표' : '매출 거래명세표' ?>.png" alt="견적서">
        </td>
    </tr>
</table>


<!-- 전체 상단 영역 -->
<table style="width:100%; border:2px solid #d00; border-collapse:collapse; font-size:10pt;">
    <tr>
        <!-- 🔵 왼쪽 영역 -->
        <td style="width:50%; padding:6px; border-right:2px solid #d00; vertical-align:top;">

            <table style="width:100%; border-collapse:collapse; line-height:1.5;">
                <tr>
                    <td style="color:#d00; width:70px;">일&nbsp;&nbsp;&nbsp;자 :</td>
                    <td>2025-11-13</td>

                    <td style="color:#d00; width:80px;">등록번호 :</td>
                    <td>124-86-31308</td>
                </tr>

                <tr>
                    <td style="color:#d00;">거래처 :</td>
                    <td>(주)신우에이엔티</td>

                    <td style="color:#d00;">성&nbsp;&nbsp;&nbsp;명 :</td>
                    <td>전용문 (인)</td>
                </tr>

                <tr>
                    <td style="color:#d00;">주&nbsp;&nbsp;&nbsp;소 :</td>
                    <td>경기도 안성시 대덕면 능말길 100 (무능리)</td>

                    <td style="color:#d00;">업&nbsp;&nbsp;&nbsp;종 :</td>
                    <td>산업기계 설계 및 개발</td>
                </tr>

                <tr>
                    <td style="color:#d00;">전화번호 :</td>
                    <td>031-678-6400</td>

                    <td style="color:#d00;">팩스번호 :</td>
                    <td>031-678-6498</td>
                </tr>
            </table>

            <!-- 🔵 합계금액 박스 -->
            <table style="width:100%; border:2px solid #d00; border-collapse:collapse; margin-top:6px;">
                <tr>
                    <td style="
                        width:130px;
                        font-weight:bold;
                        font-size:12pt;
                        color:#d00;
                        border-right:2px solid #d00;
                        padding:4px;
                    ">
                        합계금액 :
                    </td>

                    <td style="
                        text-align:right;
                        font-weight:bold;
                        font-size:18pt;
                        padding:4px 6px;
                    ">
                        913,000
                    </td>
                </tr>
            </table>
        </td>

        <!-- 🔵 오른쪽 공급자 영역 -->
        <td style="width:50%; padding:6px; vertical-align:top;">

            <table style="width:100%; border:2px solid #d00; border-collapse:collapse;">
                <tr>
                    <td rowspan="4" style="
                        width:40px;
                        text-align:center;
                        font-weight:bold;
                        color:#d00;
                        border-right:2px solid #d00;
                    ">공<br>급<br>자</td>

                    <td style="width:80px; color:#d00;">등록번호</td>
                    <td colspan="3">312-86-30100</td>
                </tr>

                <tr>
                    <td style="color:#d00;">상&nbsp;&nbsp;&nbsp;호</td>
                    <td colspan="3">제이엠테크</td>
                </tr>

                <tr>
                    <td style="color:#d00;">주&nbsp;&nbsp;&nbsp;소</td>
                    <td colspan="3">
                        충청남도 천안시 서북구 두정공단1길 149-2<br>
                        (두정동, 미래플㈜) 제이엠테크
                    </td>
                </tr>

                <tr>
                    <td style="color:#d00;">업&nbsp;&nbsp;&nbsp;태</td>
                    <td>제조업</td>

                    <td style="color:#d00;">종목</td>
                    <td>산업기계 설계 및 개발</td>
                </tr>

                <tr>
                    <td style="color:#d00;">전화번호</td>
                    <td>041-583-1096</td>

                    <td style="color:#d00;">팩스번호</td>
                    <td>041-583-1097</td>
                </tr>
            </table>

        </td>
    </tr>
</table>



<?
$VAT_TYPE = unserialize(VAT_TYPE);
?>
<p style="margin:8px 0; font-size:12pt; font-weight:700;">합계금액 : <?= number_to_korean($statement['amount']) ?> (₩ <?= number_format($statement['amount']) ?>) (<?= $VAT_TYPE[$statement['vat_type']] ?>)</p>

<table class="table">
    <thead>
        <tr style="background:#D9D9D9;">
            <th>순번</th>
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
        if (!empty($items)) {

            $count = count($items);
            $총공급가액 = 0;
            $총세액 = 0;

            foreach ($items as $index => $item) {

                if (empty($item[1])) {
                    continue;
                }

                $총공급가액 += $item[4];
                $총세액 += $item[5];
        ?>
                <tr>
                    <td style="font-size:14px !important; width: 50px;">
                        <?= $index + 1 ?>
                    </td>
                    <td style="font-size:12pt !important; width:500px; text-align:left;"><?= $item[0] ?></td>
                    <td data-label="규격" style="font-size:12pt !important; width: 100px;"><?= $item[1] ?></td>
                    <td data-label="수량" style="font-size:12pt !important; width: 50px;"><?= $item[2] ?></td>
                    <td data-label="단가" style="font-size:12pt !important; width:150px; text-align: right;"><?= !empty($item[3]) ? number_format($item[3]) : '' ?></td>
                    <td data-label="공급가액" style="font-size:12pt !important; width:150px; text-align: right;"><?= !empty($item[4]) ? number_format($item[4]) : '' ?></td>
                    <td data-label="세액" style="font-size:12pt !important; width:150px; text-align: right;"><?= !empty($item[5]) ? number_format($item[5]) : '' ?></td>
                    <td style="font-size:12pt !important; width:200px;"><?= $item[6] ?></td>
                </tr>

            <?
            }
        } else {
            ?>
            <tr>
                <td colspan="8">등록된 항목이 없습니다.</td>
            </tr>
        <?
        }
        ?>

        <tr>
            <td colspan="3" class="title" style="background:#D9D9D9;">합계</td>
            <td></td>
            <td></td>
            <td><?= number_format($총공급가액) ?></td>
            <td><?= number_format($총세액) ?></td>
            <td></td>
        </tr>
    </tbody>
</table>

<table class="section no-border table" style="margin-top:10px;">
    <tr>
        <td class="title" style="width:25%; background:#D9D9D9; border-bottom:1px solid black; border-right:1px solid black;">납기일자</td>
        <td style="width:25%; border-bottom:1px solid black; ">
            <?= $statement['due_at'] ?>
        </td>
        <td class="title" style="width:25%; background:#D9D9D9; border-bottom:1px solid black; border-right:1px solid black; border-left:1px solid black;">납품장소</td>
        <td style="width:25%; border-bottom:1px solid black;">
            <?= $statement['location'] ?>
        </td>
    </tr>
    <tr>
        <td class="title" style="background:#D9D9D9; border-right:1px solid black;">유효일자</td>
        <td><?= $statement['valid_at'] ?></td>
        <td class="title" style="background:#D9D9D9; border-right:1px solid black; border-left:1px solid black;">결제조건</td>
        <td><?= $statement['payment_type'] ?></td>
    </tr>
</table>