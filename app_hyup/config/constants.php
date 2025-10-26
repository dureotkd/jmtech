<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Seoul');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('DB_ERR_MSG', "DB Error");

define('AGENT', serialize([
    "HEAD" => "본사",
    "BRANCH" => "부본사",
    "STORE" => "매장",
    "CUSTOMER" => "고객",
    "USER" => "일반회원",
]));

define('BUY_REWARD_MILEAGE', serialize([
    'HEAD' => 700,
    'BRANCH' => 500,
    'STORE' => 5000,
    'USER' => 0,
]));

define('USER_STATUS', serialize([
    'Y' => '활성화',
    'S' => '정지',
    'D' => '삭제',
]));


define('ORDER_STATUS', serialize([
    'pending'   => '결제대기',
    'paid'      => '결제완료',
    'shipped'   => '배송중',
    'completed' => '구매확정',
    'canceled'  => '취소'
]));

define('POINT_TYPE', serialize([
    'SAVE' => '적립',
    'USE' => '차감',
    'REFUND' => '회수',
    'REQUEST' => '요청',
    'REJECT' => '거절',
    'ADMIN' => '관리자',
]));

define('POINT_TYPE_CLASS', serialize([
    'SAVE' => 'text-green-500',
    'USE' => 'text-red-500',
    'REQUEST' => 'text-blue-500',
    'REJECT' => 'text-red-500',
    'ADMIN' => 'text-yellow-500',
]));


define('FAQ_CATEGORY', serialize([
    'order' => '주문/결제',
    'return' => '교환/반품',
    'delivery' => '배송',
    'user' => '회원정보',
]));

define('REVIEW_RATING', serialize([
    '5' => '정말 만족해요',
    '4' => '만족해요',
    '3' => '괜찮아요',
    '2' => '아쉬워요',
    '1' => '별로예요',
]));

define('POINT_REQUEST_STATUS', serialize([
    'pending' => '대기중',
    'approved' => '승인됨',
    'rejected' => '거절됨'
]));

define('POINT_REQUEST_TYPE', serialize([
    'withdraw' => '출금요청',
    'deposit' => '입금요청',
    'refund' => '환불요청',
]));

define('MENU', serialize([
    [
        'title' => '회사소개',
        'url' => '/brand',
        'items' => [
            ['label' => '브랜드 소개', 'url' => '/brand'],
            ['label' => '전문성', 'url' => '/brand/expertise'],
            ['label' => '공간', 'url' => '/brand/space'],
            ['label' => '아카이브', 'url' => '/brand/archive'],
        ],
    ],
    [
        'title' => '쇼핑하기',
        'url' => '/product',
        'items' => [
            ['label' => '모든 상품', 'url' => '/product'],
            ['label' => '커피', 'url' => '/product?category=coffee'],
            ['label' => '굿즈', 'url' => '/product?category=goods'],
            ['label' => '선물세트', 'url' => '/product?category=gift'],
        ],
    ],
    [
        'title' => '비즈니스',
        'url' => '/business',
        'items' => [
            ['label' => '카페 사업자 안내', 'url' => '/business/guide'],
            ['label' => '쇼핑하기', 'url' => '/business/shop'],
            ['label' => '대량 주문 문의', 'url' => '/business/bulk-order'],
            ['label' => '협업문의', 'url' => '/business/partnership'],
        ],
    ],
    [
        'title' => '커뮤니티',
        'url' => '/community/notice',
        'items' => [
            ['label' => '공지사항', 'url' => '/community/notice'],
            ['label' => '추출 가이드', 'url' => '/community/guide'],
        ],
    ],
    [
        'title' => '고객센터',
        'url' => '/support/faq',
        'items' => [
            ['label' => 'FAQ', 'url' => '/support/faq'],
        ],
    ],
]));


define('PAY_METHOD_CODE', serialize([
    "CARD"       => "신용카드",
    "BANK"       => "계좌이체",
    "VBANK"      => "가상계좌",
    "CELLPHONE"  => "휴대폰결제",
    "NAVER"      => "네이버페이",
    "KAKAO"      => "카카오페이",
    "PAYCO"      => "페이코",
    "LPAY"       => "엘페이",
    "PINPAY"     => "핀페이",
    "SAMSUNGPAY" => "삼성페이",
    "TOSS"       => "토스",
    "LINEPAY"    => "라인페이",
    "TMONEYPAY"  => "티머니"
]));

define('BANK_CODE', serialize([
    "090" => "카카오뱅크",
    "011" => "농협은행",
    "012" => "지역농축협",
    "004" => "국민은행",
    "020" => "우리은행",
    "088" => "신한은행",
    "081" => "하나은행",
    "002" => "산업은행",
    "003" => "기업은행",
    "023" => "제일은행",
    "027" => "씨티은행",
    "031" => "대구은행",
    "032" => "부산은행",
    "034" => "광주은행",
    "035" => "제주은행",
    "037" => "전북은행",
    "039" => "경남은행",
    "089" => "케이뱅크",
    "092" => "토스뱅크",
    "045" => "새마을금고",
    "071" => "우체국",
    "007" => "수협",
    "048" => "신협",
    "054" => "HSBC은행",
    "050" => "상호저축은행"
]));

define('DEBUG', false);

define('PAYUP_DEBUG', true);
define('TOSS_DEBUG', true);

define('SMARTRO_DEBUG', false);
// define('MID', 'mosio0001m');
// define('MKEY', '0/4GFsSd7ERVRGX9WHOzJ96GyeMTwvIaKSWUCKmN3fDklNRGw3CualCFoMPZaS99YiFGOuwtzTkrLo4bR4V+Ow==');
define('MID', 'mosi00001m');
define('MKEY', '+m4H1h8V5VCvM3K5uoHKTY8M4Hbg34pIdbQrlsnyR6MOyeET50GS0Rfi0IfuACjLz4hXXSN1eT4xCyusBAuy9A==');

define('ROOT_PATH', ($_SERVER['REMOTE_ADDR'] === '127.0.0.1') ? 'C:/workSpace/hihome/assets/app_hyup' : '/var/www/html/hihome/assets/app_hyup');
define('도메인', ($_SERVER['REMOTE_ADDR'] === '127.0.0.1') ? 'https://mosihealth.test' : 'https://mosihealth.com');

define('DEFAULT_PROFILE_IMAGE', 'https://mosihealth.com/assets/app_hyup/images/default_profile.png');

define('NICE_PROGRAM_PATH', "/var/www/html/gamemarket-service-new1/assets/app_hyup/lib/CPClient_linux_x64");
define('NAVER_CALLBACK_URL', ($_SERVER['REMOTE_ADDR'] === '127.0.0.1') ? 'http://mosihealth.test/api/auth/callback/naver' : 'https://www.mosihealth.com/api/auth/callback/naver');


define('VAT_CONTROL', serialize([
    'N' => '부가세 별도',
    'Y' => '부가세 포함',
    'X' => '부가세 없음',
]));
