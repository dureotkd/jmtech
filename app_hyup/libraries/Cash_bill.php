<?
require_once __DIR__ . '/Popbill/PopbillCashbill.php';

/**
 * 현금영수증 발행
 */
class cash_bill
{
    public function __construct()
    {

        $this->obj = &get_instance();
        $this->obj->load->model("service_model");
    }

    private function 랜덤문자열($length = 10)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function 발행($id_no)
    {

        $팝빌회원사업자번호 = "8071601721";
        $팝빌회원아이디 = "dbalslzk";
        $발행자상호 = "겜마톡(머쉬빌리지)";
        $발행자대표 = "유지훈";
        $발행자주소 = "전라북도 전주시 덕진구 가재미로 83(인후동1가)";
        $발행자전화번호 = "01048133956";
        $발행담당자이메일 = "dbalslzk12@naver.com";

        /**
         * Array
(
    [id_no] => 264
    [order_no] => 33
    [midx] => 73250
    [userid] => dudrhks0319
    [cash_type] => 소득공제용
    [name] => 1
    [number] => 2
    [amount] => 60000
    [vat_amount] => 600
    [approval_number] => 
    [status] => 0
    [regdate] => 2025-01-11 21:32:53
)
         */
        $tb_cash_receipt_row = $this->obj->service_model->get_tb_cash_receipt('row', [
            "id_no = '{$id_no}'"
        ]);

        if (empty($tb_cash_receipt_row)) {

            throw new Error('empty tb_cash_receipt_row');
        }

        $cash_type      = $tb_cash_receipt_row['cash_type'];
        $order_no       = $tb_cash_receipt_row['order_no'];
        $userid         = $tb_cash_receipt_row['userid'];
        $name           = $tb_cash_receipt_row['name'];
        $amount         = $tb_cash_receipt_row['amount'];
        $vat_amount     = $tb_cash_receipt_row['vat_amount'];
        $phone          = $tb_cash_receipt_row['phone'];

        // 공급가액 계산
        $공급가액 = (int)$amount - (int)$vat_amount;

        // 팝빌 회원 사업자번호, '-' 제외 10자리
        $testCorpNum = $팝빌회원사업자번호;
        // 팝빌회원 아이디
        $testUserID = $팝빌회원아이디;

        // 문서번호, 사업자별로 중복없이 1~24자리 영문, 숫자, '-', '_' 조합으로 구성
        $mgtKey = date("ymd") . "_" . $this->랜덤문자열();

        // 메모
        $memo = '현금영수증 즉시 발행';

        // 발행안내메일 제목
        // 미기재시 기본양식으로 전송
        $emailSubject = '';

        // 현금영수증 객체 생성
        $Cashbill = new Cashbill();

        // [필수] 현금영수증 문서번호,랜덤문자열
        $Cashbill->mgtKey = $mgtKey;

        // [취소 현금영수증 발행시 필수] 당초 승인 현금영수증 국세청승인번호
        // 국세청승인번호는 GetInfo API의 ConfirmNum 항목으로 확인할 수 있습니다.
        $Cashbill->orgConfirmNum = '';

        // [취소 현금영수증 발행시 필수] 당초 승인 현금영수증 거래일자
        // 현금영수증 거래일자는 GetInfo API의 TradeDate 항목으로 확인할 수 있습니다.
        $Cashbill->orgTradeDate = '';

        // [필수] 문서형태, (승인거래, 취소거래) 중 기재
        $Cashbill->tradeType = '승인거래';

        // [필수] 거래구분, (소득공제용, 지출증빙용) 중 기재
        // $Cashbill->tradeUsage = $뱅크정보['cash_type'];
        $Cashbill->tradeUsage = 1;

        // [필수] 거래유형, (일반, 도서공연, 대중교통) 중 기재
        $Cashbill->tradeOpt = '일반';

        // [필수] 과세형태, (과세, 비과세) 중 기재
        $Cashbill->taxationType = '과세';

        // [필수] 거래금액, ','콤마 불가 숫자만 가능
        // $Cashbill->totalAmount = $뱅크정보['vat_amount'];
        $Cashbill->totalAmount = $amount;

        // [필수] 공급가액, ','콤마 불가 숫자만 가능
        $Cashbill->supplyCost = $공급가액;

        // [필수] 부가세, ','콤마 불가 숫자만 가능
        $Cashbill->tax = $vat_amount;

        // [필수] 봉사료, ','콤마 불가 숫자만 가능
        $Cashbill->serviceFee = '0';

        // [필수] 가맹점 사업자번호
        $Cashbill->franchiseCorpNum = $testCorpNum;

        // 가맹점 종사업장 식별번호
        $Cashbill->franchiseTaxRegID = '';

        // 가맹점 상호
        $Cashbill->franchiseCorpName = $발행자상호;

        // 가맹점 대표자 성명
        $Cashbill->franchiseCEOName = $발행자대표;

        // 가맹점 주소
        $Cashbill->franchiseAddr = $발행자주소;

        // 가맹점 전화번호
        $Cashbill->franchiseTEL = $발행자전화번호;

        // [필수] 식별번호, 거래구분에 따라 작성
        // 소득공제용 - 주민등록/휴대폰/카드번호 기재가능
        // 지출증빙용 - 사업자번호/주민등록/휴대폰/카드번호 기재가능
        $Cashbill->identityNum = str_replace('-', '', $phone);

        // 구매자명
        $Cashbill->customerName = '신성민';

        // 주문상품명
        $Cashbill->itemName = $발행자상호 . '_수수료_' . '10000';

        // 주문주문번호
        $Cashbill->orderNumber = date("Ymdhi") . "-" . 123123;

        // 구매자 이메일
        // 팝빌 개발환경에서 테스트하는 경우에도 안내 메일이 전송되므로,
        // 실제 거래처의 메일주소가 기재되지 않도록 주의
        $Cashbill->email = $발행담당자이메일;

        // 구매자 휴대폰
        $Cashbill->hp = str_replace('-', '', $phone);

        // 발행시 알림문자 전송여부
        $Cashbill->smssendYN = false;

        // 링크아이디
        $LinkID = 'DUDRHKS0319';

        // 비밀키
        $SecretKey = 'lHCim6QWMiwnoCNZqLY736RWtbO+yffso1uLs2UV1nI=';

        // 통신방식 기본은 CURL , curl 사용에 문제가 있을경우 STREAM 사용가능.
        // STREAM 사용시에는 php.ini의 allow_url_fopen = on 으로 설정해야함.
        define('LINKHUB_COMM_MODE', 'CURL');

        $CashbillService = new CashbillService($LinkID, $SecretKey);

        // 연동환경 설정값, 개발용(true), 상업용(false)
        $CashbillService->IsTest(false);

        // 인증토큰에 대한 IP제한기능 사용여부, 권장(true)
        $CashbillService->IPRestrictOnOff(true);

        // 팝빌 API 서비스 고정 IP 사용여부, 기본값(false)
        $CashbillService->UseStaticIP(false);

        // 로컬시스템 시간 사용 여부 true(기본값) - 사용, false(미사용)
        $CashbillService->UseLocalTimeYN(true);

        try {
            $result = $CashbillService->RegistIssue($testCorpNum, $Cashbill, $memo, $testUserID, $emailSubject);
            $code = $result->code;
            $message = $result->message;

            if ($message == '발행 완료') {

                $approval_number = $result->confirmNum;

                $this->service_model->update_tb_bank([
                    "approval_number"   => $approval_number,
                ], [
                    "id_no = '{$id_no}'"
                ]);
            }

            // if ($message != "발행 완료") {
            //     die($message);
            // } else {
            //     //echo var_dump($result);
            //     echo $code;
            //     //tax_status = 1, 현금영수증
            //     $sql = "update ez_cash_receipt set approval_number = '{$result->confirmNum}', status = 1 where id_no = {$idx} ";
            //     $dbo->query($sql);
            // }
        } catch (PopbillException $pe) {
            $code = $pe->getCode();
            $message = $pe->getMessage();

            printr($pe);

            throw new Error($message);
        }
    }
}
