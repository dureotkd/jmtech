<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <title>비밀번호 찾기</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
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

        .section-title {
            font-weight: bold;
            font-size: 18px;
            margin: 20px 0 10px;
        }

        .highlight {
            color: red;
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
            font-size: 14px;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin: 10px 0;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .form-group label {
            width: 100px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            min-width: 0;
        }

        .form-group span {
            font-size: 12px;
            color: #666;
            margin-left: 10px;
            flex-basis: 100%;
        }

        .button-group {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        button {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-confirm {
            background-color: #333;
            color: white;
        }

        .btn-id {
            background-color: #f1f1f1;
        }

        /* 반응형 스타일 */
        @media (max-width: 480px) {
            .title {
                font-size: 20px;
            }

            .section-title {
                font-size: 16px;
            }

            .form-group label {
                width: 100%;
            }

            .form-group input {
                width: 100%;
            }

            button {
                width: 100%;
                padding: 12px;
                font-size: 14px;
            }

            .button-group {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="title">비밀번호 찾기</div>

        <div class="success-icon"></div>

        <div class="description">
            소중한 정보 보호를 위해 <span class="highlight">비밀번호를 변경</span>해주세요.
        </div>

        <form method="post" id="passfrm">
            <div class="form-group">
                <label for="password">비밀번호</label>
                <input type="text" name="password" id="password" placeholder="비밀번호 입력">
                <span>* 8~16자 영문/숫자/특수문자만 가능합니다.</span>
            </div>
            <div class="form-group">
                <label for="password-confirm">비밀번호 확인</label>
                <input type="text" name="repassword" id="password-confirm" placeholder="비밀번호 확인">
            </div>

            <div class="button-group">
                <button type="button" class="btn-confirm" onclick="go_login1()">확인</button>
                <button type="button" class="btn-id" onclick="go_login2()">아이디 찾기</button>
            </div>
        </form>
    </div>
</body>
<script>
    function go_login1() {
        var params = jQuery("#passfrm").serialize();
        $.ajax({
            type: "POST",
            url: "/Page/Member_modify/update_pw",
            async: false,
            data: params,
            dataType: 'json',
            success: function(result) {

                if (result.ok) {

                    Swal.fire({
                        text: "비밀번호가 변경되었습니다",
                        icon: "success",
                        confirmButtonText: "닫기",
                    });

                    setTimeout(() => {
                        window.close();
                    }, 2500)

                }
            },
            error: function(e) {
                alert(e.responseText);
            }
        });
    }

    function go_login2() {
        window.opener.location.href = '/page/find_id';
        window.close();
    }
</script>

</html>