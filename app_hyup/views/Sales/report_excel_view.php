<table border="1">
    <thead>
        <tr>
            <th colspan="5" style="text-align: center;">매출(거래명세표)</th>
        </tr>
        <tr>
            <th>발행일</th>
            <th>공급받는자상호</th>
            <th>공급가액</th>
            <th>세액</th>
            <th>합계금액</th>
        </tr>
    </thead>
    <tbody>
        <?
        if (!empty($statement_all)) {
            foreach ($statement_all as $statement) {
        ?>
                <tr>
                    <td style="text-align: center;">
                        <?= date('Y-m-d', strtotime($statement['created_at'])) ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $statement['partner_name'] ?>
                    </td>
                    <td class=""><?= number_format($statement['supply_amount']) ?></td>
                    <td class=""><?= number_format($statement['tax_amount']) ?></td>
                    <td class=""><?= number_format($statement['amount']) ?></td>
                </tr>
            <?
            }
        } else {

            ?>
            <tr>
                <td colspan="5" style="text-align: center;">조회된 데이터가 없습니다.</td>
            </tr>
        <?
        }
        ?>
    </tbody>
</table>