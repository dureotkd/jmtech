<style>
    body {
        font-family: "NanumGothic";
        font-size: 9pt;
    }

    .table {
        border-collapse: collapse;
        width: 100%;
    }

    .table th {
        font-weight: 300 !important;
    }

    .table th,
    .table td {
        border: 0.75px solid #000;
        padding: 4px;
        text-align: center;
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
        <td style="text-align:left; vertical-align:right; padding-left:155px;">
            <h1 class="header pdf_title">
                <?= $title ?>
            </h1>
        </td>
    </tr>
</table>


<table class="section table" style="width:100%; border:1px solid #000; border-collapse:collapse;">
    <tr>
        <!-- 왼쪽 영역 -->
        <td style="width:50%; font-size:9pt; text-align:center; vertical-align:top; padding:16px; border-right:none !important;">
            <h2 style="margin:0;"><?= $estimate['partner_name'] ?> 귀하</h2><br />
            <p style="margin:4px 0;">전화 : <?= $estimate['phone_number'] ?>&nbsp;&nbsp;&nbsp;팩스 : <?= $estimate['fax_number'] ?></p><br />
            <p style="margin:4px 0;">아래와 같이 수주합니다.</p>
        </td>

        <!-- 오른쪽 영역 -->
        <td style="width:50%; font-size:9pt; text-align:center; padding:0px !important; border:none !important;">
            <!-- <table class="table">
                <tr>
                    <td class="no-border" rowspan="3" style="width:9%; background:#D9D9D9; ;">공<br />급<br />자</td>
                    <td style="width:10%;">등록번호</td>
                    <td style="width:20%;">312-86-30100</td>
                    <td style="width:15%;">상호</td>
                    <td style="width:15%;">제이엠테크</td>
                    <td style="width:15%;">성명</td>
                    <td>전용훈</td>
                </tr>
                <tr>
                    <td class="title">주소</td>
                    <td colspan="5" style="text-align:left;">충청남도 천안시 서북구 두정공단1길 149-2 (두정동, 마리플(주)) 제이엠테크</td>
                </tr>
                <tr>
                    <td class="title">업태</td>
                    <td>제조업</td>
                    <td class="title">종목</td>
                    <td colspan="3">산업기계 설계 및 개발</td>
                </tr>
            </table> -->
            <table class="supplier-table">
                <tr>
                    <td class="section" rowspan="3">
                        공<br>급<br>자
                    </td>
                    <td class="title" style="width: 100px;">등록번호</td>
                    <td class="value center" colspan="6">312-86-30100</td>
                </tr>

                <tr>
                    <td class="title">주소</td>
                    <td colspan="5" class="value">
                        충청남도 천안시 서북구 두정공단1길 149-2 (두정동, 마리플㈜) 제이엠테크
                    </td>
                </tr>

                <tr>
                    <td class="title">업태</td>
                    <td class="value center">제조업</td>
                    <td class="title">종목</td>
                    <td colspan="3" class="value center">산업기계 설계 및 개발</td>
                </tr>
            </table>
        </td>
    </tr>
</table>


<?
$VAT_TYPE = unserialize(VAT_TYPE);
?>
<p style="margin:8px 0; font-size:12pt; font-weight:700;">합계금액 : <?= number_to_korean($estimate['amount']) ?> (₩ <?= number_format($estimate['amount']) ?>) (<?= $VAT_TYPE[$estimate['vat_type']] ?>)</p>

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

                $총공급가액 += $item[4];
                $총세액 += $item[5];
        ?>
                <tr>
                    <td>
                        <?= $count - $index ?>
                    </td>
                    <td style="width:300px; text-align:left;"><?= $item[0] ?></td>
                    <td data-label="규격"><?= $item[1] ?></td>
                    <td data-label="수량"><?= $item[2] ?></td>
                    <td data-label="단가" style="text-align: right;"><?= number_format($item[3]) ?></td>
                    <td data-label="공급가액" style="text-align: right;"><?= number_format($item[4]) ?></td>
                    <td data-label="세액" style="text-align: right;"><?= number_format($item[5]) ?></td>
                    <td><?= $item[6] ?></td>
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
            <?= $estimate['due_at'] ?>
        </td>
        <td class="title" style="width:25%; background:#D9D9D9; border-bottom:1px solid black; border-right:1px solid black; border-left:1px solid black;">납품장소</td>
        <td style="width:25%; border-bottom:1px solid black;">
            <?= $estimate['location'] ?>
        </td>
    </tr>
    <tr>
        <td class="title" style="background:#D9D9D9; border-right:1px solid black;">유효일자</td>
        <td><?= $estimate['valid_at'] ?></td>
        <td class="title" style="background:#D9D9D9; border-right:1px solid black; border-left:1px solid black;">결제조건</td>
        <td><?= $estimate['payment_type'] ?></td>
    </tr>
</table>