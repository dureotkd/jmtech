<style>
    body {
        font-family: "NanumGothic";
        font-size: 9pt;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th {
        font-weight: 300 !important;
    }

    th,
    td {
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
        letter-spacing: 10px;
        /* 🔹 글자 간격을 10px 만큼 띄움 */
    }
</style>

<!-- #f2f2f2 (예비)-->
<h1 class="header pdf_title ">
    <?= $title ?>
</h1>

<table class="section" style="width:100%; border:1px solid #000; border-collapse:collapse;">
    <tr>
        <!-- 왼쪽 영역 -->
        <td style="width:70%; font-size:9pt; text-align:center; vertical-align:top; padding:8px;">
            <h2 style="margin:0;"><?= $estimate['partner_name'] ?> 귀하</h2><br />
            <p style="margin:4px 0;">전화 : <?= $estimate['phone_number'] ?>&nbsp;&nbsp;&nbsp;팩스 : <?= $estimate['fax_number'] ?></p><br />
            <p style="margin:4px 0;">아래와 같이 수주합니다.</p>
        </td>

        <!-- 오른쪽 영역 -->
        <td style="width:30%; font-size:9pt; text-align:center; padding:8px;">
            수주일자 : <?= $estimate['created_at'] ?><br /><br />
            NO : <?= $estimate['no'] ?>
        </td>
    </tr>
</table>


<table class="section">
    <tr>
        <td rowspan="3" style="width:9%; background:#D9D9D9; ;">공급자</td>
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
</table>
<?
$VAT_TYPE = unserialize(VAT_TYPE);
?>
<p style="margin:8px 0; font-size:12pt; font-weight:700;">합계금액 : <?= number_to_korean($estimate['amount']) ?> (₩ <?= number_format($estimate['amount']) ?>) (<?= $VAT_TYPE[$estimate['vat_type']] ?>)</p>

<table>
    <thead>
        <tr style="background:#D9D9D9;">
            <th>순번</th>
            <th>품목명</th>
            <th>규격</th>
            <th>수량</th>
            <th>단가</th>
            <th>공급가액</th>
            <th>세액</th>
            <th>비고</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>111</td>
            <td>22</td>
            <td>33</td>
            <td>44</td>
            <td>2</td>
            <td>3</td>
            <td>11</td>
            <td>11</td>
        </tr>

        <tr>
            <td colspan="5" class="title">합계</td>
            <td>1</td>
            <td>2</td>
            <td>33</td>
        </tr>
    </tbody>
</table>

<table class="section no-border" style="margin-top:10px;">
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