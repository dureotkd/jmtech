<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class php_email
{
  public function __construct()
  {
    $this->obj = &get_instance();
    $this->obj->load->library("teamroom");
  }

  public function send($to_email, $subject, $body, $is_layout = true)
  {
    $mail = new PHPMailer(true);

    try {
      // SMTP 설정
      $mail->isSMTP();
      $mail->Host = 'smtp.naver.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'mosihealth@naver.com'; // 반드시 @naver.com까지 포함
      $mail->Password = 'mosi1004^^';
      $mail->SMTPSecure = 'ssl'; // SSL 설정
      $mail->Port = 465;

      // 메일 정보
      $mail->CharSet = 'UTF-8';
      $mail->setFrom('mosihealth@naver.com', '제이엠테크, 나를 위한 건강의 시작');
      $mail->addAddress($to_email); // 수신자
      $mail->Subject = $subject;
      $mail->Body    = $is_layout ? $this->layout($subject, $body) : $body;
      $mail->isHTML(true); // HTML 메일일 경우

      $mail->send();

      return true;
    } catch (Exception $e) {

      $this->obj->teamroom->send('개발자', $mail->ErrorInfo);

      error_log('메일 전송 실패: ' . $mail->ErrorInfo);
      return false;
    }
  }

  public function layout($subject, $body)
  {

    // 레이아웃을 적용한 HTML 템플릿을 반환
    return '<html>
<body style="margin:0; padding:0; background-color:#f2f2f2; font-family:\'Apple SD Gothic Neo\', sans-serif;">
  <div style="width:100%; height:100%; display: flex; justify-content: center; align-items: center; padding: 40px 0; background-color: #f2f2f2;">
    <div style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 4px; overflow: hidden; padding: 40px 30px; box-sizing: border-box;">

      <div style="display: flex; flex-direction: column; align-items: center; text-align: center;">
        <img src="/assets/app_hyup/images/logo.png" alt="로고" style="width: 100px; margin-bottom: 25px;">
        <h1 style="margin: 0; font-size: 24px; color: #444;">' . $subject . '</h1>
      </div>
      ' . $body . '
      <div style="margin-top: 30px; text-align: center;">
        <a href=" ' . 도메인 . ' " 
           style="display: inline-block; background-color: #0abab5; color: #fff; padding: 12px 24px; text-decoration: none; font-weight: bold; border-radius: 4px;">
          사이트 바로가기
        </a>
      </div>
    </div>
  </div>
</body>
</html>
                ';
  }
}
