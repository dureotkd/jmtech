<?php

/**
 * ^ ------------- 바로빌 API -------------
 * & https://dev.barobill.co.kr/docs/references/%EB%B0%94%EB%A1%9C%EB%B9%8C-API-%EB%A0%88%ED%8D%BC%EB%9F%B0%EC%8A%A4-%EA%B0%9C%EC%9A%94
 * * 
 */
class Barobill
{

     public function __construct()
     {
          // // $BaroService_URL = 'https://testws.baroservice.com/TI.asmx?WSDL';    //테스트베드용
          // $BaroService_URL = 'https://ws.baroservice.com/TI.asmx?WSDL';          //실서비스용

          // $BaroService_TI = new SoapClient($BaroService_URL, array(
          //      'trace' => 'true',
          //      'encoding' => 'UTF-8' //소스를 ANSI로 사용할 경우 euc-kr로 수정
          // ));

          // $this->BaroService_TI = $BaroService_TI;

          // // $this->CERTKEY = '3C2743E7-C822-40C7-A55B-A13857B95678'; //연동인증키(테스트베드용)
          // $this->CERTKEY = '1F196223-E6F2-4F4E-B59F-E60D246A0F11'; //연동인증키(실서비스용)
          // $this->CorpNum = '3128630100'; //사업자번호
          // $this->UserID = 'dureotkd123'; //바로빌 회원아이디
     }

     /**
      * * https://dev.barobill.co.kr/docs/references/세금계산서-API#RegistAndIssueTaxInvoice
      * * RegistAndIssueTaxInvoice
     Parameter
No	변수명	타입	길이	필수	설명
1	CERTKEY	string	50	O	연동인증키
2	CorpNum	string	10	O	공급자의 사업자번호. 하이픈(-)을 제외한 숫자만 입력
3	Invoice	TaxInvoice		O	세금계산서 내용
("일반세금계산서"와 "수정세금계산서"는 세금계산서의 내용에 따라 결정됩니다.)
4	SendSMS	bool		O	문자메세지 전송여부
(공급받는자 정보의 "휴대폰" 항목이 설정되지 않은 경우 전송되지 않습니다.)
5	ForceIssue	bool		O	가산세 발생이 예상되는 경우에도 발급할지 여부
(true 로 설정하더라도 국세청 전송설정 의 "지연발급" 설정이 차단인 경우 발급이 되지 않습니다.)
6	MailTitle	string	200	X	공급받는자에게 발송되는 이메일의 제목
(입력하지 않은 경우 바로빌에서 지정한 기본 이메일 제목으로 전송됩니다.)
Return
반환타입 : int
반환값

1 (성공)
음수 (실패.
      */
     public function 세금계산서발행()
     {

          $TaxInvoice = array(
               // 📦 세금계산서 기본정보
               'IssueDirection' => 1,   // 발급방향 (int) [필수] 1: 정발급, 2: 역발행
               'TaxInvoiceType' => 1,   // 세금계산서 형태 (int) [필수] 1: 세금계산서, 2: 계산서, 4: 위수탁세금계산서, 5: 위수탁계산서
               'ModifyCode' => '',      // 수정사유 코드 (string, 1자리) [선택] 1: 기재사항 착오/정정, 2: 공급가액 변동, 3: 환입, 4: 계약 해제, 5: 내국신용장 사후개설, 6: 이중발급
               'TaxType' => 1,          // 과세형태 (int) [필수] 1: 과세, 2: 영세, 3: 면세
               'PurposeType' => 2,      // 영수/청구 형태 (int) [필수] 1: 영수, 2: 청구
               'WriteDate' => '',       // 작성일자 (string, 8자리) [필수] YYYYMMDD 형식 (미입력 시 오늘 날짜)

               // 💰 금액 관련
               'AmountTotal' => '',     // 공급가액 (string, 18자리) [필수] 숫자만 입력 (-99999999999999999 ~ 999999999999999999)
               'TaxTotal' => '',        // 세액 (string, 18자리) [필수]
               'TotalAmount' => '',     // 합계금액 (string, 18자리) [필수]
               'Cash' => '',            // 현금 (string, 18자리) [선택]
               'ChkBill' => '',         // 수표 (string, 18자리) [선택]
               'Note' => '',            // 어음 (string, 18자리) [선택]
               'Credit' => '',          // 외상미수금 (string, 18자리) [선택]

               // 🗒 비고 및 추가정보
               'Remark1' => '',         // 비고1 (string, 150) [선택] 외국인 등록번호 또는 원본 승인번호 입력 가능
               'Remark2' => '',         // 비고2 (string, 150) [선택]
               'Remark3' => '',         // 비고3 (string, 150) [선택]
               'Kwon' => '',            // 권 (string, 4) [선택] 별지서식용, 국세청 전송 안됨
               'Ho' => '',              // 호 (string, 4) [선택] 별지서식용, 국세청 전송 안됨
               'SerialNum' => '',       // 일련번호 (string, 27) [선택] 별지서식용, 국세청 전송 안됨

               // 🏢 공급자 정보 (InvoicerParty)
               'InvoicerParty' => [
                    'MgtNum' => '',       // 문서관리번호 (string) [선택]
                    'CorpNum' => '',      // 사업자번호 (string, 10~13자리) [필수]
                    'TaxRegID' => '',     // 종사업장 식별번호 (string, 4) [선택]
                    'CorpName' => '',     // 회사명 (string, 200) [필수]
                    'CEOName' => '',      // 대표자명 (string, 100) [필수]
                    'Addr' => '',         // 주소 (string, 300) [선택]
                    'BizType' => '',      // 업태 (string, 100) [선택]
                    'BizClass' => '',     // 종목 (string, 100) [선택]
                    'ContactID' => '',    // 담당자 아이디 (string) [선택]
                    'ContactName' => '',  // 담당자명 (string) [선택]
                    'TEL' => '',          // 전화번호 (string) [선택]
                    'HP' => '',           // 휴대폰 (string) [선택]
                    'Email' => '',        // 이메일 (string, 100) [선택]
               ],

               // 🧾 공급받는자 정보 (InvoiceeParty)
               'InvoiceeParty' => [
                    'MgtNum' => '',       // 문서관리번호 (string) [선택]
                    'CorpNum' => '',      // 사업자번호 (string, 10~13자리) [필수]
                    'TaxRegID' => '',     // 종사업장 식별번호 (string, 4) [선택]
                    'CorpName' => '',     // 회사명 (string, 200) [필수]
                    'CEOName' => '',      // 대표자명 (string, 100) [필수]
                    'Addr' => '',         // 주소 (string, 300) [선택]
                    'BizType' => '',      // 업태 (string, 100) [선택]
                    'BizClass' => '',     // 종목 (string, 100) [선택]
                    'ContactID' => '',    // 담당자 아이디 (string) [선택]
                    'ContactName' => '',  // 담당자명 (string) [선택]
                    'TEL' => '',          // 전화번호 (string) [선택]
                    'HP' => '',           // 휴대폰 (string) [선택]
                    'Email' => '',        // 이메일 (string, 100) [선택]
               ],

               // 🤝 수탁자 정보 (BrokerParty)
               'BrokerParty' => [
                    'MgtNum' => '',       // 문서관리번호 (string) [선택]
                    'CorpNum' => '',      // 사업자번호 (string, 10~13자리) [필수 - 위수탁 시]
                    'TaxRegID' => '',     // 종사업장 식별번호 (string, 4) [선택]
                    'CorpName' => '',     // 회사명 (string, 200) [필수 - 위수탁 시]
                    'CEOName' => '',      // 대표자명 (string, 100) [필수 - 위수탁 시]
                    'Addr' => '',         // 주소 (string, 300) [선택]
                    'BizType' => '',      // 업태 (string, 100) [선택]
                    'BizClass' => '',     // 종목 (string, 100) [선택]
                    'ContactID' => '',    // 담당자 아이디 (string) [선택]
                    'ContactName' => '',  // 담당자명 (string) [선택]
                    'TEL' => '',          // 전화번호 (string) [선택]
                    'HP' => '',           // 휴대폰 (string) [선택]
                    'Email' => '',        // 이메일 (string, 100) [선택]
               ],

               // 📋 품목 상세 목록 (최대 99개)
               'TaxInvoiceTradeLineItems' => [
                    'TaxInvoiceTradeLineItem' => [
                         [
                              'PurchaseExpiry' => '',  // 거래일자 (string) [필수] YYYYMMDD
                              'Name' => '',            // 품목명 (string, 100) [필수]
                              'Information' => '',     // 규격 (string, 40) [선택]
                              'ChargeableUnit' => '',  // 단위 (string, 10) [선택]
                              'UnitPrice' => '',       // 단가 (string, 18) [선택]
                              'Amount' => '',          // 공급가액 (string, 18) [필수]
                              'Tax' => '',             // 세액 (string, 18) [필수]
                              'Description' => '',     // 비고 (string, 200) [선택]
                         ],
                         // ... 추가 품목 가능 (최대 99개)
                    ]
               ],

               // 🔗 기타 부가 정보
               'OriginalNTSSendKey' => '',  // 국세청 승인번호 (string, 24) [선택] 수정세금계산서일 경우 원본 승인번호
               'ChargeDirection' => '',     // 발급비용 과금대상 (int) [선택] 역발행 시 필수 (1: 공급자, 2: 공급받는자)
               'SMSSendYN' => 0,            // SMS 전송 여부 (int) [필수] 0: 미전송, 1: 전송
               'BusinessLicenseYN' => 0,    // 사업자등록증 첨부 여부 (int) [필수] 0: 미첨부, 1: 첨부
               'BankBookYN' => 0,           // 통장 사본 첨부 여부 (int) [필수] 0: 미첨부, 1: 첨부
               'Memo' => '',                // 메모 (string, 200) [선택]
               'EmailTitle' => '',          // 이메일 제목 (string, 200) [선택] 미입력 시 기본 제목 사용
          );

          $SendSMS = false;
          $ForceIssue = false;
          $MailTitle = '';

          $Result = $this->BaroService_TI->RegistAndIssueTaxInvoice([
               'CERTKEY'      => $this->CERTKEY,  //* 연동인증키
               'CorpNum'      => $TaxInvoice['InvoicerParty']['CorpNum'], //* 공급자의 사업자번호
               'Invoice'      => $TaxInvoice, //* 세금계산서 내용
               'SendSMS'      => $SendSMS, //* 문자메세지 전송여부
               'ForceIssue'   => $ForceIssue, //* 가산세 발생이 예상되는 경우에도 발급할지 여부
               'MailTitle'    => $MailTitle, //* 공급받는자에게 발송되는 이메일의 제목
          ])->RegistAndIssueTaxInvoiceResult;

          if ($Result < 0) { // 호출 실패
               echo $Result;
          } else { // 호출 성공
               print_r($Result);
          }
     }

     /**
      * * RegistAndIssueTaxInvoiceBulk
      * 1	CERTKEY	string	50	O	연동인증키
2	Invoices	TaxInvoiceEx[]		O	세금계산서 등록 정보 배열
3	ForceIssue	bool		O	가산세 발생이 예상되는 경우에도 발급할지 여부
(true 로 설정하더라도 국세청 전송설정 의 "지연발급" 설정이 차단인 경우 발급이 되지 않습니다.)
      */
     public function 세금계산서대량발행() {}

     /**
      * * RegistTaxInvoiceScrapEx
      * Parameter
No	변수명	타입	길이	필수	설명
1	CERTKEY	string	50	O	연동인증키
2	CorpNum	string	10	O	바로빌 회원사 사업자번호. 하이픈(-)을 제외한 숫자만 입력
3	HometaxLoginMethod	string	4	O	홈택스 로그인 방법
ID : 홈택스 아이디
CERT : 바로빌에 등록된 공동인증서
4	HometaxID	string	50	△	홈택스 아이디
(홈택스 로그인 방법이 ID 로 입력된 경우에만 필수 입력)
5	HometaxPWD	string	50	△	홈택스 비밀번호
(홈택스 로그인 방법이 ID 로 입력된 경우에만 필수 입력)
6	ShortJuminNum	string	7	△	홈택스 사용자(대표자) 주민등록번호 앞 7자리
(홈택스 로그인 방법이 ID 로 입력된 경우에만 필수 입력)
      */
     public function 홈택스연동신청() {}

     /**
      * * GetPeriodTaxInvoiceSalesList
      * Parameter
No	변수명	타입	길이	필수	설명
1	CERTKEY	string	50	O	연동인증키
2	CorpNum	string	10	O	공급자의 사업자번호. 하이픈(-)을 제외한 숫자만 입력
3	UserID	string	20	O	공급자의 바로빌 회원 아이디
4	TaxType	int		O	1 : 과세 + 영세
3 : 면세
5	DateType	int		O	1 : 작성일자
2 : 발급일자
3 : 전송일자
6	StartDate	string	8	O	조회시작일자. YYYYMMDD 형식
7	EndDate	string	8	O	조회종료일자. YYYYMMDD 형식
8	CountPerPage	int		O	페이지 당 조회 건수 (최대 100건)
9	CurrentPage	int		O	조회할 페이지 번호
      */
     public function 매출세금계산서조회($StartDate, $EndDate) // YYYYMMDD
     {

          // code...
          // ...
          // ...
          $TaxType = 1;
          $DateType = 1;
          $CountPerPage = 100;
          $CurrentPage = 1; // 1~3페이지 까지있음

          $params = [
               'CERTKEY' => $this->CERTKEY,
               'CorpNum' => $this->CorpNum,
               'UserID' => $this->UserID,
               'TaxType' => $TaxType,
               'DateType' => $DateType,
               'StartDate' => str_replace('-', '', $StartDate),
               'EndDate' => str_replace('-', '', $EndDate),
               'CountPerPage' => $CountPerPage,
               'CurrentPage' => $CurrentPage,
          ];

          $Result = $this->BaroService_TI->GetPeriodTaxInvoiceSalesList($params)->GetPeriodTaxInvoiceSalesListResult;

          if ($Result->CurrentPage < 0) { // 호출 실패

               return [];
          } else { // 호출 성공

               if (!array_key_exists('SimpleTaxInvoiceEx', $Result->SimpleTaxInvoiceExList)) {
                    $SimpleTaxInvoices = [];
               } else if (!is_array($Result->SimpleTaxInvoiceExList->SimpleTaxInvoiceEx)) {
                    $SimpleTaxInvoices = [$Result->SimpleTaxInvoiceExList->SimpleTaxInvoiceEx];
               } else {
                    $SimpleTaxInvoices = $Result->SimpleTaxInvoiceExList->SimpleTaxInvoiceEx;
               }
          }

          return $SimpleTaxInvoices;
     }

     /**
      * * https://dev.barobill.co.kr/docs/references/%EC%84%B8%EA%B8%88%EA%B3%84%EC%82%B0%EC%84%9C-API#GetPeriodTaxInvoicePurchaseList
      * * GetPeriodTaxInvoicePurchaseList
      * 1	CERTKEY	string	50	O	연동인증키
2	CorpNum	string	10	O	공급받는자의 사업자번호. 하이픈(-)을 제외한 숫자만 입력
3	UserID	string	20	O	공급받는자의 바로빌 회원 아이디
4	TaxType	int		O	1 : 과세 + 영세
3 : 면세
5	DateType	int		O	1 : 작성일자
2 : 발급일자
3 : 전송일자
6	StartDate	string	8	O	조회시작일자. YYYYMMDD 형식
7	EndDate	string	8	O	조회종료일자. YYYYMMDD 형식
8	CountPerPage	int		O	페이지 당 조회 건수 (최대 100건)
9	CurrentPage	int		O	조회할 페이지 번호
Return

10000
알 수 없는 오류 발생.
API 호출 중 서버오류가 발생한 경우입니다. 바로빌로 문의바랍니다.
-10003
연동서비스가 점검 중입니다.
-10004
해당 기능은 더 이상 사용되지 않습니다.
-10007
해당 기능을 사용할 수 없습니다.
-10005
최대 100건까지만 사용하실 수 있습니다.
-10006
최대 1000건까지만 사용하실 수 있습니다.
-10008
날짜형식이 잘못되었습니다.
-10148
조회 기간이 잘못되었습니다.
-40001
파일을 찾을 수 없습니다.
-40002
빈 파일입니다(0byte
      */
     public function 매입세금계산서기간조회($StartDate, $EndDate) // YYYYMMDD
     {

          $TaxType = 1;
          $DateType = 1;
          $CountPerPage = 100;
          $CurrentPage = 1; // 1~3페이지 까지있음

          $params = [
               'CERTKEY' => $this->CERTKEY,
               'CorpNum' => $this->CorpNum,
               'UserID' => $this->UserID,
               'TaxType' => $TaxType,
               'DateType' => $DateType,
               'StartDate' => str_replace('-', '', $StartDate),
               'EndDate' => str_replace('-', '', $EndDate),
               'CountPerPage' => $CountPerPage,
               'CurrentPage' => $CurrentPage,
          ];

          $Result = $this->BaroService_TI->GetPeriodTaxInvoicePurchaseList($params)->GetPeriodTaxInvoicePurchaseListResult;

          if ($Result->CurrentPage < 0) { // 호출 실패

               return [];
          } else { // 호출 성공

               if (!array_key_exists('SimpleTaxInvoiceEx', $Result->SimpleTaxInvoiceExList)) {
                    $SimpleTaxInvoices = [];
               } else if (!is_array($Result->SimpleTaxInvoiceExList->SimpleTaxInvoiceEx)) {
                    $SimpleTaxInvoices = [$Result->SimpleTaxInvoiceExList->SimpleTaxInvoiceEx];
               } else {
                    $SimpleTaxInvoices = $Result->SimpleTaxInvoiceExList->SimpleTaxInvoiceEx;
               }

               return $SimpleTaxInvoices;
          }
     }

     public function 매입세금계산서일별조회()
     {
          // ---------------------------------------------------------------------------------------------------
          // API 레퍼런스 : https://dev.barobill.co.kr/docs/references/세금계산서-API#GetDailyTaxInvoiceSalesList
          // ---------------------------------------------------------------------------------------------------
          $TaxType = 1;
          $DateType = 1;
          $BaseDate = '';
          $CountPerPage = 100;
          $CurrentPage = 1;

          $Result = $this->BaroService_TI->GetDailyTaxInvoiceSalesList([
               'CERTKEY' => $this->CERTKEY,
               'CorpNum' => $this->CorpNum,
               'UserID' => $this->UserID,
               'TaxType' => $TaxType,
               'DateType' => $DateType,
               'BaseDate' => $BaseDate,
               'CountPerPage' => $CountPerPage,
               'CurrentPage' => $CurrentPage,
          ])->GetDailyTaxInvoiceSalesListResult;

          if ($Result->CurrentPage < 0) { // 호출 실패
               echo $Result->CurrentPage;
          } else { // 호출 성공
               echo $Result->CurrentPage;
               echo '<br/>';
               echo $Result->CountPerPage;
               echo '<br/>';
               echo $Result->MaxPageNum;
               echo '<br/>';
               echo $Result->MaxIndex;
               echo '<br/>';

               if (!array_key_exists('SimpleTaxInvoiceEx', $Result->SimpleTaxInvoiceExList)) {
                    $SimpleTaxInvoices = [];
               } else if (!is_array($Result->SimpleTaxInvoiceExList->SimpleTaxInvoiceEx)) {
                    $SimpleTaxInvoices = [$Result->SimpleTaxInvoiceExList->SimpleTaxInvoiceEx];
               } else {
                    $SimpleTaxInvoices = $Result->SimpleTaxInvoiceExList->SimpleTaxInvoiceEx;
               }

               foreach ($SimpleTaxInvoices as $SimpleTaxInvoice) {
                    // 필드정보는 레퍼런스를 참고해주세요.
                    echo '<pre>';
                    print_r($SimpleTaxInvoice);
                    echo '</pre>';
               }
          }
     }
}
