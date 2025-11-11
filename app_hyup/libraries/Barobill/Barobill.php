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
          // $BaroService_URL = 'https://testws.baroservice.com/TI.asmx?WSDL';    //테스트베드용
          $BaroService_URL = 'https://ws.baroservice.com/TI.asmx?WSDL';          //실서비스용

          $BaroService_TI = new SoapClient($BaroService_URL, array(
               'trace' => 'true',
               'encoding' => 'UTF-8' //소스를 ANSI로 사용할 경우 euc-kr로 수정
          ));

          $this->BaroService_TI = $BaroService_TI;

          // $this->CERTKEY = '3C2743E7-C822-40C7-A55B-A13857B95678'; //연동인증키(테스트베드용)
          $this->CERTKEY = '1F196223-E6F2-4F4E-B59F-E60D246A0F11'; //연동인증키(실서비스용)
          $this->CorpNum = '3128630100'; //사업자번호
          $this->UserID = 'dureotkd123'; //바로빌 회원아이디
     }

     /**
      * * RegistAndIssueTaxInvoice
      * 
      * 1	CERTKEY	string	50	O	연동인증키
2	CorpNum	string	10	O	공급자의 사업자번호. 하이픈(-)을 제외한 숫자만 입력
3	Invoice	TaxInvoice		O	세금계산서 내용
("일반세금계산서"와 "수정세금계산서"는 세금계산서의 내용에 따라 결정됩니다.)
4	SendSMS	bool		O	문자메세지 전송여부
(공급받는자 정보의 "휴대폰" 항목이 설정되지 않은 경우 전송되지 않습니다.)
5	ForceIssue	bool		O	가산세 발생이 예상되는 경우에도 발급할지 여부
(true 로 설정하더라도 국세청 전송설정 의 "지연발급" 설정이 차단인 경우 발급이 되지 않습니다.)
6	MailTitle	string	200	X	공급받는자에게 발송되는 이메일의 제목
(입력하지 않은 경우 바로빌에서 지정한 기본 이메일 제목으로 전송됩니다.)
      */
     public function 세금계산서발행() {}

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
     public function 매출세금계산서조회()
     {

          // code...
          // ...
          // ...
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
      */
     public function 매입세금계산서기간조회($StartDate, $EndDate) // YYYYMMDD
     {
          echo $StartDate;
          exit;

          $TaxType = 1;
          $DateType = 1;
          $CountPerPage = 10;
          $CurrentPage = 1;

          $params = [
               'CERTKEY' => $this->CERTKEY,
               'CorpNum' => $this->CorpNum,
               'UserID' => $this->UserID,
               'TaxType' => $TaxType,
               'DateType' => $DateType,
               'StartDate' => $StartDate,
               'EndDate' => $EndDate,
               'CountPerPage' => $CountPerPage,
               'CurrentPage' => $CurrentPage,
          ];

          $Result = $this->BaroService_TI->GetPeriodTaxInvoicePurchaseList($params)->GetPeriodTaxInvoicePurchaseListResult;

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

               printr($SimpleTaxInvoices);
               exit;


               foreach ($SimpleTaxInvoices as $SimpleTaxInvoice) {
                    // 필드정보는 레퍼런스를 참고해주세요.
                    echo '<pre>';
                    print_r($SimpleTaxInvoice);
                    echo '</pre>';
               }
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
          $CountPerPage = 10;
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
