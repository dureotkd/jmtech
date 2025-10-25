<?php
class innopay
{

    public $INFINI_MID = "bugamema1m";
    public $INFINI_KEY = "cL8xJ2Hc57YtF5cVgZxKXC9k0AJx9VvU8LfoFoJF4HHgQCGFczoFap4/O4Cb098Bl/wnQBPJrXnuJ1M87C4s8w==";

    public function __construct()
    {

        $this->obj = &get_instance();
        $this->obj->load->library("ajax");
    }


    # 예치금 잔액 조회 요청
    public function check_money()
    {

        $url        = "https://acct.innopay.co.kr/AcctOutRemainSearch.acct";

        $result     = $this->obj->ajax->request($url, [
            'mid'       => $this->INFINI_MID,
            'merkey'    => $this->INFINI_KEY,
            'depAcntNo' => '66400000397982'  //예치금계좌번호
        ]);

        if (!empty($result['resultCode']) && $result['resultCode'] == '0000') {

            return $result['remainAmt'];
        }
    }

    # 계좌실명 조회
    public function check_real_account($account_bank, $account_no, $account_owner, $birth)
    {

        $url        = "https://acct.innopay.co.kr/AcctNmReq.acct";

        $moid       = 'dureotkd' . time();          // 가맹점거래번호


        $result     = $this->obj->ajax->request($url, [
            'mid'       => $this->INFINI_MID,
            'merkey'    => $this->INFINI_KEY,
            'moid'      => $moid,
            'bankCode'  => $account_bank,
            'acntNo'    => $account_no,
            'idNo'      => $birth,
            'acntNm'    => $account_owner,
        ]);

        $res_array = [
            'ok'    => false,
            'msg'   => '',
        ];

        if (!empty($result['resultCode']) && $result['resultCode'] == '0000') {

            $res_array['ok'] = true;
            $res_array['msg'] = $result['resultMsg'];
        } else {

            $res_array['ok'] = false;
            $res_array['msg'] = $result['resultMsg'];
        }

        return $res_array;
    }

    /**
     * 계좌 이체 송금
     * 사용하려면 이노페이에서 IP등록 필수
     *
     * @return void
     */
    public function send_money($params = [])
    {
        $account_no = $params['account_no'];
        $bank_code = $params['bank_code'];
        $account_owner = $params['account_owner'];
        $amount = $params['amount'];

        $url = "https://acct.innopay.co.kr/AcctOutTransReq.acct";

        $moid           = 'dureotkd' . time();          // 가맹점거래번호
        $bankCode       = $bank_code;                   //은행코드
        $acntNo         = $account_no;                  // 입금계좌번호
        $acntNm         = $account_owner;               //입금계좌 예금주
        $amt            = $amount;                     //입금액
        $depAcntNo      = "66400000397982";             //예치금계좌번호
        $depAcntNm      = "김민호";                     //예금주

        $result = [];
        // $result     = $this->obj->ajax->request($url,  [
        //     'mid'           => $this->INFINI_MID,
        //     'merkey'        => $this->INFINI_KEY,
        //     'moid'          => $moid,
        //     'bankCode'      => $bankCode,
        //     'acntNo'        => $acntNo,
        //     'acntNm'        => $acntNm,
        //     'amt'           => $amt,
        //     'depAcntNo'     => $depAcntNo,
        //     'depAcntNm'     => $depAcntNm
        // ]);

        /**
         * Array
(
    [mid] => bugamema1m
    [tid] => 
    [moid] => dureotkd1740398212
    [reqDt] => 
    [bankCode] => 011
    [acntNo] => 3560991665723
    [acntNm] => 신성민
    [amt] => 10000
    [depAcntNo] => 66400000397982
    [depAcntNm] => 김민호
    [resultCode] => 1015
    [resultMsg] => IP등록요망(관리자에게 문의하세요)
    [transDt] => 
)
         */
        return $result;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function make_virtual_account($amount = 0)
    {
        $now_date = date('Y-m-d');
        $tomorrow_date = date('Y-m-d', strtotime('+1 day', strtotime($now_date)));
        $tomorrow_date = str_replace('-', '', $tomorrow_date);

        $url = "https://api.innopay.co.kr/api/vbankApi";  // 실제 API 주소로 변경
        $data = [
            // "mid"              => "pggamett1m",
            // "licenseKey"       => "cL8xJ2Hc57YtF5cVgZxKXC9k0AJx9VvU8LfoFoJF4HHgQCGFczoFap4/O4Cb098Bl/wnQBPJrXnuJ1M87C4s8w==",
            "mid"              => "testpay01m",
            "licenseKey"       => "Ma29gyAFhvv/+e4/AHpV6pISQIvSKziLIbrNoXPbRS5nfTx2DOs8OJve+NzwyoaQ8p9Uy1AN4S1I0Um5v7oNUg==",
            "moid"             => "test1234567890",
            "amt"              => $amount,
            "vbankBankCode"    => "003",
            "vbankNum"         => "08205103497444",
            "vbankExpDate"     => $tomorrow_date,
            "vbankAccountName" => "홍길동",
            "countryCode"      => "KR",
            "socNo"            => "801212",
            "addr"             => "서울시 금천구 가산디지털2로 53",
            "accountTel"       => "01012342552"
        ];

        /**
         *    [resultCode] => 4100
    [resultMsg] => 가상계좌 발급 성공
         */
        $result     = $this->obj->ajax->request($url, $data);

        return $result;
    }
}
