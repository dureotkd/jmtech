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
        require_once '../Popbill/biz_common.php';
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
    public function 매입세금계산서조회()
    {
        // code...
        // ...
        // ...
    }
}
