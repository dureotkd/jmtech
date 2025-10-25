<?php
class alarmtalk
{
    /** 뿌리오 계정/연동키 */
    private $ACCOUNT  = 'mosihealth';                 // 뿌리오 계정
    private $ACCESS_KEY = '9ca1a1a270aa17ed637afc0a3ed8ac33d4b40b7919700f8c5673235f9660b11f';      // 연동 개발 인증키(비밀번호 아님)

    public function __construct()
    {
        $this->obj = &get_instance();
        $this->obj->load->library(['ajax']);
        $this->obj->load->model("/Page/service_model");

        // ✅ ppurio API 기준 base_uri 교체
        $this->obj->client = new \GuzzleHttp\Client();
    }

    /**
     * AccessToken 요청 (유효 24시간)
     */
    private function getToken(): string
    {
        $basicAuth = base64_encode($this->ACCOUNT . ':' . $this->ACCESS_KEY);
        $res = $this->obj->client->post('https://message.ppurio.com/v1/token', [
            'headers' => [
                'Authorization' => "Basic {$basicAuth}",
                'Content-Type'  => 'application/json; charset=utf-8',
            ],
        ]);
        $body = json_decode((string)$res->getBody(), true);

        if (empty($body['token'])) {
            throw new \RuntimeException('Failed to get access token');
        }
        return $body['token']; // Bearer 토큰
    }

    /**
     * 카카오 알림톡 발송 (ppurio 스펙)
     *
     * $payloads 예시:
     * [
     *   'phone'          => '01012341234',
     *   'name'           => 'Kildong Hong',
     *   'senderProfile'  => '발신프로필명',
     *   'templateCode'   => '템플릿코드',
     *     'changeWord'    => [
    '이름'   => '신성민',
    '상품명' => '제이엠테크 프로 멤버십 1개월',
    '주문번호' => 'ORDER-20250813-0001',
    '금액'   => number_format(10000) . '원', // "10,000원"
  ],
     *   'refKey'         => 'order_20250813_0001',// 중복방지키
     *   'duplicateFlag'  => 'N',                  // (옵션) Y/N
     *   // (옵션) 대체발송
     *   'resend' => [
     *       'messageType' => 'SMS',
     *       'from'        => '01011112222',
     *       'name'        => 'Kildong Hong',
     *       'subject'     => 'sms title',
     *       'content'     => 'sms content',
     *   ]
     * ]
     */
    public function send(array $payloads)
    {
        $phone         = $payloads['phone'] ?? null;
        $name          = $payloads['name']  ?? '';
        $type          = $payloads['type']  ?? '';

        /**
         * ppur_2025081316562215150903047 [배송시작]
         * ppur_2025081114295415150392024 [결제완료]
         * ppur_2025081316532126394450713 [회원가입]
         */
        $templateCode  = $payloads['templateCode']  ?? '템플릿코드';
        $changeWord    = $payloads['changeWord'] ?? [];
        $refKey        = $payloads['refKey'] ?? ('ref_' . date('Ymd_His'));
        $duplicateFlag = strtoupper($payloads['duplicateFlag'] ?? 'N');

        if (empty($phone)) {
            // 전화번호가 없으면 그냥 성공 처리(요청 안 함)
            return ['ok' => true, 'skipped' => true];
        }

        $token = $this->getToken();

        // ppurio 스펙에 맞는 payload 구성
        $target = [[
            // 'to'         => $phone,
            'to'         => $phone,
            'name'       => $name,
            'changeWord' => $changeWord,  // 템플릿 변수
        ]];


        $body = [
            'account'       => $this->ACCOUNT,
            'messageType'   => 'ALT',              // ✅ 알림톡
            'senderProfile' => '@제이엠테크',     // ✅ 발신프로필명
            'templateCode'  => $templateCode,      // ✅ 템플릿코드
            'duplicateFlag' => $duplicateFlag,     // Y:허용 / N:제거
            'targetCount'   => count($target),
            'targets'       => $target,
            'refKey'        => $refKey,            // 중복방지키
        ];

        // 대체발송 설정(옵션)
        if (!empty($payloads['resend'])) {
            $body['isResend'] = 'Y';
            $body['resend']   = $payloads['resend'];
        } else {
            $body['isResend'] = 'N';
        }

        try {

            $res = $this->obj->client->post('https://message.ppurio.com/v1/kakao', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'  => 'application/json; charset=utf-8',
                ],
                'body' => json_encode($body, JSON_UNESCAPED_UNICODE), // ✅ 수동 인코딩
            ]);

            $resBody = json_decode((string)$res->getBody(), true);

            $this->obj->service_model->insert_alarmtalk_log(DEBUG, [
                'phone' => $phone,
                'name'  => $name,
                'type'  => $type,
                'code'  => $resBody['code'] ?? '',
                'description' => $resBody['description'] ?? '',
                'refKey' => $resBody['refKey'] ?? '',
                'messageKey' => $resBody['messageKey'] ?? '',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            return ['ok' => true, 'response' => $resBody];
        } catch (\Throwable $e) {
            // 필요 시 재시도/로그 저장
            throw new \RuntimeException('ppurio send error: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * 예약발송 취소 (ppurio 스펙)
     * @param string $messageKey 예약 시 반환된 messageKey
     */
    public function cancel(string $messageKey)
    {
        $token = $this->getToken();

        $payload = [
            'account'    => $this->ACCOUNT,
            'messageKey' => $messageKey,
        ];

        try {
            $res = $this->obj->client->post('https://message.ppurio.com/v1/cancel/kakao', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type'  => 'application/json; charset=utf-8',
                ],
                'json' => $payload,
            ]);
            $resBody = json_decode((string)$res->getBody(), true);
            return ['ok' => true, 'response' => $resBody];
        } catch (\Throwable $e) {
            throw new \RuntimeException('ppurio cancel error: ' . $e->getMessage(), 0, $e);
        }
    }
}
