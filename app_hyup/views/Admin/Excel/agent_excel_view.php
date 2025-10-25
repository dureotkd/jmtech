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
    <thead style="background-color: #f3f4f6; font-weight: bold;">
        <tr>
            <th>No.</th>
            <th>유형</th>
            <th>포인트</th>
            <th>아이디</th>
            <th>이름</th>
            <th>연락처</th>
            <th>이메일</th>
            <th>주소</th>
            <th>우편번호</th>
            <th>로그인 연동</th>
            <th>등록일</th>
            <th>등록시각</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (!empty($user_all)) {
            $agentvo = unserialize(AGENT);
            $cnt = count($user_all);

            foreach ($user_all as $user):
                $agent_name = $agentvo[$user['agent']] ?? '일반회원';
        ?>
                <tr>
                    <td><?= $cnt-- ?></td>
                    <td><?= $agent_name . ($user['store_code'] ? " ({$user['store_code']})" : '') ?></td>
                    <td><?= number_format($user['point']) ?>원</td>
                    <td><?= $user['user_id'] ?></td>
                    <td><?= $user['name'] ?></td>
                    <td><?= $user['phone'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['address'] . ' ' . $user['address_detail'] ?></td>
                    <td><?= $user['zip_code'] ?></td>
                    <td><?= $user['social_type'] ?></td>
                    <td><?= explode(' ', $user['created_at'])[0] ?></td>
                    <td><?= explode(' ', $user['created_at'])[1] ?></td>
                </tr>
            <?php endforeach;
        } else { ?>
            <tr>
                <td colspan="12">등록된 회원이 없습니다.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>