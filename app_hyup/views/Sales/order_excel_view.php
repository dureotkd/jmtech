<table border="1">
    <thead>
        <tr>
            <th colspan="5" style="text-align: center;">수주서</th>
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
        if (!empty($estimate_all)) {
            foreach ($estimate_all as $estimate) {
        ?>
                <tr>
                    <td style="text-align: center;">
                        <?= date('Y-m-d', strtotime($estimate['created_at'])) ?>
                    </td>
                    <td style="text-align: center;">
                        <?= $estimate['partner_name'] ?>
                    </td>
                    <td class=""><?= number_format($estimate['supply_amount']) ?></td>
                    <td class=""><?= number_format($estimate['tax_amount']) ?></td>
                    <td class=""><?= number_format($estimate['amount']) ?></td>
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