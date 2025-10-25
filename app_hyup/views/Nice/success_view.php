<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>아이디 찾기</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 40px 20px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            text-align: center;
        }

        .success-icon {
            width: 60px;
            height: 60px;
            background: red;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .success-icon::after {
            content: '✔';
            color: white;
            font-size: 30px;
            font-weight: bold;
        }

        .description {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0 10px;
            display: flex;
            align-items: center;
        }

        .section-title::before {
            content: "●";
            color: orangered;
            font-size: 12px;
            margin-right: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
            font-size: 14px;
        }

        table th {
            background-color: #f7f7f7;
            font-weight: bold;
            width: 30%;
        }

        .button-group {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        button {
            padding: 10px 20px;
            border: 1px solid #ddd;
            cursor: pointer;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            min-width: 120px;
        }

        .btn-login {
            background-color: #444;
            color: white;
        }

        .btn-find-pw {
            background-color: white;
            color: #333;
        }

        /* 반응형 스타일 */
        @media (max-width: 480px) {
            .title {
                font-size: 20px;
            }

            .description {
                font-size: 16px;
            }

            table th,
            table td {
                padding: 8px;
                font-size: 12px;
            }

            button {
                font-size: 12px;
                padding: 8px 16px;
                min-width: 100px;
            }

            .container {
                padding: 20px 10px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="title">아이디 찾기</div>

        <div class="success-icon"></div>

        <div class="description">아이디 찾기 완료!</div>

        <div class="section-title">아이디 정보</div>

        <table>
            <tr>
                <th>아이디</th>
                <td><?= $data['id'] ?></td>
            </tr>
            <tr>
                <th>가입일</th>
                <td><?= date('Y/m/d', strtotime($data['reg_date'])) ?></td>
            </tr>
        </table>

        <div class="button-group">
            <button class="btn-login" type="button" onclick="go_login1()">로그인</button>
            <button class="btn-find-pw" type="button" onclick="go_login2()">비밀번호 찾기</button>
        </div>
    </div>
</body>

<script>
    function go_login1() {
        window.opener.location.href = '/page/login';
        window.close();
    }

    function go_login2() {
        window.opener.location.href = '/page/find_pw';
        window.close();
    }
</script>

</html>