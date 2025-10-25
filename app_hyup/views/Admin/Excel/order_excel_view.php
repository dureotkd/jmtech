<style>
    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
        /* ✅ 내용에 따라 너비 자동 조절 */
        text-align: center;
    }

    thead th,
    tbody td {
        padding: 4px;
        text-align: center;
        vertical-align: middle;
    }

    td {
        white-space: nowrap;
    }
</style>
<table border="1">
    <thead>
        <tr>
            <th>주문일</th>
            <th>결제수단</th>
            <th>상품명</th>
            <th>결제금액</th>
            <th>주문상태</th>
            <th>수령인 이름</th>
            <th>수령인 연락처</th>
            <th>주소</th>
            <th>택배사</th>
            <th>송장번호</th>
            <th>배송 메모</th>
            <th>관리자 메모</th>
        </tr>
    </thead>
    <tbody>
        <?
        $ORDER_STATUS = unserialize(ORDER_STATUS);
        ?>
        <?php foreach ($order_items as $order): ?>
            <tr>
                <td><?= $order['ordered_at'] ?></td>
                <td><?= $order['app_card_name'] ?: $order['payment_method'] ?></td>
                <td><?= $order['product_name'] ?> (<?= $order['quantity'] ?>개)</td>
                <td><?= number_format($order['total_amount']) ?>원</td>
                <td><?= $ORDER_STATUS[$order['order_status']] ?? $order['order_status'] ?></td>
                <td><?= $order['receiver_name'] ?></td>
                <td><?= $order['receiver_phone'] ?></td>
                <td><?= $order['address'] . ' ' . $order['address_detail'] . ' (' . $order['zipcode'] . ')' ?></td>
                <td><?= $order['delivery_company'] ?></td>
                <td><?= $order['tracking_number'] ?></td>
                <td><?= $order['order_memo'] ?></td>
                <td><?= $order['admin_memo'] ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>