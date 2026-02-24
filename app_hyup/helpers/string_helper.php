<?php

function timeAgo($datetime)
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return '방금전';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . '분전';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . '시간전';
    } elseif ($diff < 2592000) { // 30일
        return floor($diff / 86400) . '일전';
    } elseif ($diff < 31536000) { // 12달
        return floor($diff / 2592000) . '달전'; // 1달 = 30일
    } else {
        return floor($diff / 31536000) . '년전'; // 1년 = 365일
    }
}

function fileIcon($file_name)
{
    $ext = explode('.', $file_name);
    $ext = end($ext);

    // * ext가 이미지 확장자인지 체크
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) {
        $ext = 'image';
    }

    return "/assets/app_hyup/icons/" . $ext . ".png";
}

function maskString($str)
{
    $visible = mb_substr($str, 0, 5, 'UTF-8');
    return $visible . '****';
}

function 배송비측정기준($zipcode, $total_amount)
{
    $zipcode = (int)preg_replace('/\D/', '', $zipcode); // 숫자만 추출

    $fee = 3000;

    // 도서산간 지역별 우편번호 범위와 추가요금
    $islandAreas = [
        # 도서산간
        ['from' => 22386, 'to' => 22388, 'fee' => 9000],
        ['from' => 23004, 'to' => 23010, 'fee' => 9000],
        ['from' => 23100, 'to' => 23116, 'fee' => 9000],
        ['from' => 23124, 'to' => 23136, 'fee' => 9000],
        ['from' => 31708, 'to' => 31708, 'fee' => 9000],
        ['from' => 32133, 'to' => 32133, 'fee' => 9000],
        ['from' => 33411, 'to' => 33411, 'fee' => 9000],
        ['from' => 40200, 'to' => 40240, 'fee' => 9000],
        ['from' => 52570, 'to' => 52571, 'fee' => 9000],
        ['from' => 53031, 'to' => 53033, 'fee' => 9000],
        ['from' => 53089, 'to' => 53104, 'fee' => 9000],
        ['from' => 54000, 'to' => 54000, 'fee' => 9000],
        ['from' => 46768, 'to' => 46771, 'fee' => 9000],
        ['from' => 56347, 'to' => 56349, 'fee' => 9000],
        ['from' => 57068, 'to' => 57069, 'fee' => 9000],
        ['from' => 58760, 'to' => 58762, 'fee' => 9000],
        ['from' => 58800, 'to' => 58866, 'fee' => 9000],
        ['from' => 58953, 'to' => 58958, 'fee' => 9000],
        ['from' => 59102, 'to' => 59103, 'fee' => 9000],
        ['from' => 59106, 'to' => 59106, 'fee' => 9000],
        ['from' => 59127, 'to' => 59127, 'fee' => 9000],
        ['from' => 59129, 'to' => 59129, 'fee' => 9000],
        ['from' => 59137, 'to' => 59166, 'fee' => 9000],
        ['from' => 59421, 'to' => 59421, 'fee' => 9000],
        ['from' => 59531, 'to' => 59568, 'fee' => 9000],
        ['from' => 59650, 'to' => 59650, 'fee' => 9000],
        ['from' => 59766, 'to' => 59766, 'fee' => 9000],
        ['from' => 59781, 'to' => 59790, 'fee' => 9000],
        # 도서산간

        # 제주도
        ['from' => 63000, 'to' => 63644, 'fee' => 6000],
        ['from' => 63365, 'to' => 63365, 'fee' => 6000], // 우도
        ['from' => 63000, 'to' => 63001, 'fee' => 6000], // 추자도
        # 제주도
    ];

    foreach ($islandAreas as $area) {
        if ($zipcode >= $area['from'] && $zipcode <= $area['to']) {
            $fee = $area['fee'];
            break;
        }
    }

    if ($total_amount >= 30000) {
        $fee = $fee - 3000; // 3만원 이상은 3000원 할인
    }

    return $fee;
}

function encodeSHA256Base64($ediDate, $mid, $amt, $merchantKey)
{
    $data = $ediDate . $mid . $amt . $merchantKey;
    $hash = hash('sha256', $data, true);          // SHA-256 해시 (바이너리 출력)
    $base64 = base64_encode($hash);               // Base64 인코딩
    return $base64;
}

/**
 *   'pending'   => '결제대기',
    'paid'      => '결제완료',
    'shipped'   => '배송중',
    'completed' => '거래완료',
    'canceled'  => '취소'
 *
 * @param [type] $status
 * @return void
 */
function status_badge($status)
{
    $badges = [
        'pending' => '<span class="inline-block w-fit rounded-full bg-[#d1e7dd] text-[#0f5132]  text-sm font-semibold !px-2 !py-1">결제대기</span>',
        'paid' => '<span class="inline-block w-fit rounded-full bg-[#e0fafa] text-[#0abab5] text-sm font-semibold !px-2 !py-1">결제완료</span>',
        'shipped' => '<span class="inline-block w-fit rounded-full bg-[#cff4fc] text-[#0c5460] text-sm font-semibold !px-2 !py-1">배송중</span>',
        'completed' => '<span class="inline-block w-fit rounded-full bg-[#c3e6cb] text-[#155724] text-sm font-semibold !px-2 !py-1">구매확정</span>',
        'canceled' => '<span class="inline-block w-fit rounded-full bg-[#f8d7da] text-[#721c24] text-sm font-semibold !px-2 !py-1">취소</span>',
    ];


    return $badges[$status] ?? '';
}

function number_to_korean($number)
{
    $number = intval($number); // 정수 변환

    if ($number === 0) return '영원정';

    $unit1 = ['', '만', '억', '조', '경'];
    $unit2 = ['', '십', '백', '천'];
    $numChar = ['', '일', '이', '삼', '사', '오', '육', '칠', '팔', '구'];

    $result = '';
    $pos = 0;

    while ($number > 0) {
        $part = $number % 10000;
        $number = floor($number / 10000);

        $subResult = '';
        $digits = str_pad($part, 4, '0', STR_PAD_LEFT);

        for ($i = 0; $i < 4; $i++) {
            $n = intval($digits[$i]);
            if ($n > 0) {
                $subResult .= $numChar[$n] . $unit2[3 - $i];
            }
        }

        if ($subResult !== '') {
            $result = $subResult . $unit1[$pos] . $result;
        }

        $pos++;
    }

    return $result . ' 원정';
}

function isValidPassword($password)
{
    // 최소 8자리, 영문자, 숫자, 특수문자 포함
    $lengthCheck = strlen($password) >= 8;
    $hasLetter = preg_match('/[a-zA-Z]/', $password);
    $hasNumber = preg_match('/[0-9]/', $password);
    $hasSpecial = preg_match('/[\W_]/', $password); // \W는 영숫자 외 문자, _ 포함

    return $lengthCheck && $hasLetter && $hasNumber && $hasSpecial;
}

function toPyeong($squareMeter, $precision = 2)
{
    $pyeong = $squareMeter / 3.305785;
    return round($pyeong, $precision);
}

function page_404()
{

    header("location: /error/404");
    exit;
}

function get_admin_date($datetime)
{
    if (!$datetime) return '';

    $parts = explode(' ', $datetime);

    if (count($parts) === 2) {
        return $parts[0] . '<br /><span class="text-xs text-gray-500">(' . $parts[1] . ')</span>';
    }

    return $datetime; // 혹시나 공백이 없으면 원본 그대로 반환
}


function 결제상태뱃지($결제상태)
{
    return [
        '결제대기' => '<span class="badge badge-warning absolute !text-[12px] top-1 left-1 !p-1 rounded">결제대기</span>',
        '결제완료' => '<span class="bdge badge-success absolute !text-[12px] topa-1 left-1 !p-1 rounded">결제완료</span>',
        '결제취소' => '<span class="badge badge-danger absolute !text-[12px] top-1 left-1 !p-1 rounded">결제취소</span>',
        '환불신청' => '<span class="badge badge-warning absolute !text-[12px] top-1 left-1 !p-1 rounded">환불신청</span>',
        '환불완료' => '<span class="badge badge-success absolute !text-[12px] top-1 left-1 !p-1 rounded">환불완료</span>',
    ][$결제상태] ?? '<span class="badge badge-info absolute !text-[12px] top-1 left-1 !p-1 rounded">문의중</span>';
}

/**
 * 주간 임대료를 받아 하루 임대료를 반환
 *
 * @param int|float $weekly_price 1주치 임대료 (ex: 200000)
 * @param int $days 요일 수 (기본값: 7일)
 * @param int $precision 소수점 자릿수 (기본값: 0 = 원 단위로)
 * @return float|string
 */
function weekly_to_daily_rent($weekly_price, $days = 7)
{
    if ($days <= 0) {
        return '요일 수는 1 이상이어야 합니다.';
    }

    $daily = $weekly_price / $days;

    // 1000원 단위 이하 올림 * floor (내림됌)
    return ceil($daily / 1000) * 1000;
}

function get_guest_txt($guest)
{

    foreach ($guest as $label => $count) {
        if ((int)$count > 0) {
            $result[] = "{$label} {$count}명";
        }
    }

    return implode(', ', $result);
}

function get_방상태_badge($status)
{
    $badges = [
        '등록중' => '<span class="absolute top-0 right-0 !px-2 !py-1 inline-block bg-[#eb7c9c] w-[120px] text-white text-center text-xs font-medium px-2 py-1 mb-2">등록중</span>',
        '승인대기' => '<span class="absolute top-0 right-0 !px-2 !py-1 inline-block bg-gray-600 w-[120px] text-white text-center text-xs font-medium px-2 py-1 mb-2">승인대기</span>',
        '거절' => '<span class="absolute top-0 right-0 !px-2 !py-1 inline-block bg-red-700 w-[120px] text-white text-center text-xs font-medium px-2 py-1 mb-2">거절</span>',
    ];

    return $badges[$status];
}

function get_reservation_status_badge($status)
{
    $badges = [
        'waiting_approval' => '<span class="bg-indigo-400 text-white text-xs px-2 py-1 rounded font-medium  absolute !text-[12px] top-1 left-1 !p-1 rounded font-medium">승인대기</span>',
        'waiting_payment' => '<span class="bg-blue-500 text-white text-xs px-2 py-1 rounded font-medium  absolute !text-[12px] top-1 left-1 !p-1 rounded font-medium">결제대기</span>',
        'confirmed' => '<span class="bg-green-500 text-white text-xs px-2 py-1 rounded font-medium  absolute !text-[12px] top-1 left-1 !p-1 rounded font-medium">예약확정</span>',
        'completed' => '<span class="bg-gray-600 text-white text-xs px-2 py-1 rounded font-medium  absolute !text-[12px] top-1 left-1 !p-1 rounded font-medium">이용완료</span>',
        'canceld' => '<span class="bg-red-500 text-white text-xs px-2 py-1 rounded font-medium  absolute !text-[12px] top-1 left-1 !p-1 rounded font-medium">예약취소</span>',
    ];

    return $badges[$status] ?? '<span class="bg-gray-500 text-white text-xs px-2 py-1 rounded font-medium  absolute !text-[12px] top-1 left-1 !p-1 rounded font-medium">문의</span>';
}

function get_day_text($date)
{
    if (empty($date) || $date == '0000-00-00') {
        return '';
    }

    $weekdays = ['일', '월', '화', '수', '목', '금', '토'];

    $timestamp = strtotime($date); // 문자열을 타임스탬프로 변환
    $ymd = date('y.m.d', $timestamp);
    $w = date('w', $timestamp); // 정수로 안전하게 요일 추출
    $kor_w = $weekdays[$w];
    return $ymd . " (" . $kor_w . ")";
}

function get_stay_days($check_in_date, $check_out_date)
{
    $in = new DateTime($check_in_date);
    $out = new DateTime($check_out_date);
    $diff = $in->diff($out);
    return (int)$diff->days; // 일 수 반환 (int로 캐스팅)
}

function rent_array($rent_day)
{

    return [
        '일주일' => (int)$rent_day * 7,
        '보름' => (int)$rent_day * 15,
        '한달' => (int)$rent_day * 30,
    ];
}

function get_search_date_text($dates)
{

    if (empty($dates) || !is_array($dates) || count($dates) < 2) {
        $today = date('Y-m-d');
        $oneWeekLater = date('Y-m-d', strtotime('+6 days')); // 7일 일정 (6박)
        $dates = [$today, $oneWeekLater];
    }

    $start = date('m-d', strtotime($dates[0]));
    $end = date('m-d', strtotime($dates[1]));
    $nights = (strtotime($dates[1]) - strtotime($dates[0])) / (60 * 60 * 24);

    // 🟩 단위 계산
    if ($nights >= 30 && $nights % 30 === 0) {
        $stayLabel = ($nights / 30) . '개월';
    } elseif ($nights >= 7 && $nights % 7 === 0) {
        $stayLabel = ($nights / 7) . '주일';
    } else {
        $stayLabel = $nights . '박';
    }

    $label = "{$start} ~ {$end} · {$stayLabel}";
    return $label;
}

function generateRandomNickname()
{
    $adjectives = ['귀여운', '작은', '웃는', '똑똑한', '멋진', '따뜻한', '용감한'];
    $animals = ['사자', '토끼', '고양이', '강아지', '곰', '다람쥐', '호랑이'];

    $adj = $adjectives[array_rand($adjectives)];
    $animal = $animals[array_rand($animals)];
    $date = date('Ymd'); // 오늘 날짜
    $randNum = rand(100, 999); // 세 자리 숫자

    return $adj . $animal . $date . $randNum;
}

function 회원가입_아이디길이체크($username)
{
    $length = strlen($username);
    return !($length >= 5 && $length <= 20);
}

function 친절한regdate($datetime)
{
    $now = new DateTime();
    $target = new DateTime($datetime);
    $diff = $now->getTimestamp() - $target->getTimestamp();

    if ($diff < 60) {
        return "방금 전";
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return "약 {$minutes}분 전";
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "약 {$hours}시간 전";
    } elseif ($diff < 2592000) {
        $days = floor($diff / 86400);
        return "약 {$days}일 전";
    } else {
        return $target->format("Y-m-d");
    }
}

function 프리미엄기간($price)
{
    $prday = array(
        "3000" => "1 days",
        "16500" => "7 days",
        "55000" => "30 days",
        "200" => "1 hours",
        "400" => "2 hours",

        "600" => "3 hours",
        "800" => "4 hours",
        "1000" => "5 hours",
        "1200" => "6 hours",
        "1400" => "7 hours",
        "1600" => "8 hours",
        "1800" => "9 hours",
        "2000" => "10 hours",
        "2200" => "11 hours",
        "2400" => "12 hours",
    );
    return isset($prday[$price]) ? $prday[$price] : null;
}

function 등록된주문시간($dateTime)
{
    // 현재 시간 가져오기
    $currentTime = new DateTime();
    // 입력된 날짜 시간 객체로 변환
    $inputTime = new DateTime($dateTime);

    // 시간 차이를 계산
    $interval = $currentTime->diff($inputTime);
    $days = (int)$interval->format('%a'); // 일 단위 차이
    $hours = (int)$interval->format('%h'); // 시간 단위 차이
    $minutes = (int)$interval->format('%i'); // 분 단위 차이
    $seconds = (int)$interval->format('%s'); // 초 단위 차이

    // 미래인지 과거인지 확인
    $isFuture = $inputTime > $currentTime;

    if ($days >= 3) {
        // 3일 이상 차이나면 날짜만 반환
        return $inputTime->format('Y-m-d');
    }

    // 3일 미만인 경우 상대적인 시간 반환
    if ($days > 0) {
        return $days . ($isFuture ? '일 후' : '일 전');
    }
    if ($hours > 0) {
        return $hours . ($isFuture ? '시간 후' : '시간 전');
    }
    if ($minutes > 0) {
        return $minutes . ($isFuture ? '분 후' : '분 전');
    }
    return $seconds . ($isFuture ? '초 후' : '초 전');
}

function containsSpecialChar($str)
{
    return preg_match('/[!@#$%^&*(),.?":{}|<>]/', $str);
}

function isDatePassed($targetDate)
{
    $currentTime = time();  // 현재 시간의 타임스탬프
    $targetTime = strtotime($targetDate);  // 주어진 날짜를 타임스탬프 형태로 변환

    if ($currentTime > $targetTime) {
        return true;  // 현재 시간이 주어진 날짜를 넘었으면 true
    } else {
        return false;  // 그렇지 않으면 false
    }
}

function 거래등급($totalScore, $recentScore)
{
    // 각 등급에 해당하는 이미지 파일 경로
    $gradeImages = [
        '브론즈' => '<img src="/assets/app_hyup/images/bronze.png" alt="브론즈">',
        '실버' => '<img src="/assets/app_hyup/images/silver.png" alt="실버">',
        '골드' => '<img src="/assets/app_hyup/images/gold.png" alt="골드">',
        '플래티넘' => '<img src="/assets/app_hyup/images/platinum.png" alt="플래티넘">',
        'VIP' => '<img src="/assets/app_hyup/images/vip.png" alt="VIP">',
    ];

    // 등급 판별
    if ($totalScore < 5) {
        return ['grade' => '브론즈', 'image' => $gradeImages['브론즈']];
    } elseif ($totalScore >= 5 && $totalScore < 51) {
        return ['grade' => '실버', 'image' => $gradeImages['실버']];
    } elseif ($totalScore >= 51 && $totalScore < 101) {
        return ['grade' => '골드', 'image' => $gradeImages['골드']];
    } elseif ($totalScore >= 101 && $recentScore >= 5) {
        return ['grade' => '플래티넘', 'image' => $gradeImages['플래티넘']];
    } elseif ($totalScore >= 301) {
        return ['grade' => 'VIP', 'image' => $gradeImages['VIP']];
    } else {
        return ['grade' => '조건에 맞는 등급이 없습니다.', 'image' => ''];
    }
}

function formatPhoneNumber($phoneNumber)
{
    // 숫자만 남기기
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    // 전화번호 길이가 맞는지 확인
    if (strlen($phoneNumber) == 11 && substr($phoneNumber, 0, 3) == '010') {
        // 형식에 맞게 변환
        return substr($phoneNumber, 0, 3) . '-' . substr($phoneNumber, 3, 4) . '-' . substr($phoneNumber, 7);
    }

    return "Invalid phone number"; // 유효하지 않은 번호일 경우
}

function GetValue($str, $name)
{
    $pos1 = 0;  //length의 시작 위치
    $pos2 = 0;  //:의 위치

    while ($pos1 <= strlen($str)) {
        $pos2 = strpos($str, ":", $pos1);
        $len = substr($str, $pos1, $pos2 - $pos1);
        $key = substr($str, $pos2 + 1, $len);
        $pos1 = $pos2 + $len + 1;
        if ($key == $name) {
            $pos2 = strpos($str, ":", $pos1);
            $len = substr($str, $pos1, $pos2 - $pos1);
            $value = substr($str, $pos2 + 1, $len);
            return $value;
        } else {
            // 다르면 스킵한다.
            $pos2 = strpos($str, ":", $pos1);
            $len = substr($str, $pos1, $pos2 - $pos1);
            $pos1 = $pos2 + $len + 1;
        }
    }
}

function validatePassword($password)
{
    // 정규식 설명
    // ^                : 문자열 시작
    // [a-zA-Z0-9!@#$%^&*()-_+=<>?,./] : 영문 대소문자, 숫자, 허용 특수문자만 가능
    // {8,16}           : 8자 이상 16자 이하
    // $                : 문자열 끝
    $pattern = '/^[a-zA-Z0-9!@#$%^&*()\-_+=<>?,.\/]{8,16}$/';

    if (preg_match($pattern, $password)) {
        return true; // 유효한 비밀번호
    } else {
        return false; // 유효하지 않은 비밀번호
    }
}

function 남은시간상세($targetTime)
{
    // 타임존 설정 (서버 시간 보장)
    date_default_timezone_set('Asia/Seoul');

    // 현재 시간과 목표 시간 설정
    $currentTime = new DateTime();
    $deadlineTime = new DateTime($targetTime);

    if ($currentTime > $deadlineTime) {
        return "이미 마감된 시간입니다.";
    }

    // 시간 차이 구하기
    $interval = $currentTime->diff($deadlineTime);

    // 남은 시간 정보를 배열로 반환
    return [
        '일' => $interval->days,
        '시간' => $interval->h,
        '분' => $interval->i,
        '초' => $interval->s
    ];
}

function calculateAdditionTimes($start, $end)
{
    if ($start <= 0 || $end <= 0) {
        return "입력 값은 0보다 커야 합니다.";
    }

    // 몇 번 더해야 원하는 값에 도달하는지 계산
    $times = $end / $start;

    return $times;
}

function isTwoHoursPassed($targetTime)
{
    // 현재 시간
    $currentTime = new DateTime();

    // 비교할 타겟 시간
    $targetTime = new DateTime($targetTime);

    // 시간 차이 계산
    $interval = $currentTime->diff($targetTime);

    // 시간 차이가 2시간 이상인지 확인
    if ($interval->h >= 2 || ($interval->d > 0)) {
        return true; // 2시간 이상 지났으면 true 반환
    } else {
        return false; // 2시간 미만이면 false 반환
    }
}

function get_os()
{
    $mobile_agent = "/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/";

    if (preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {
        return "Mobile";
    } else {
        return "PC";
    }
}

function 거래상태($obj)
{
    if ($obj == 1) {
        return '<div class="box green">입금대기</div>';
    } else if ($obj == 2) {
        return '<div class="box green">거래중</div>';
    } else if ($obj == 3) {
        return '<div class="box orange">완료예정</div>';
    } else if ($obj == 4) {
        return '<div class="box red">거래취소</div>';
    } else if ($obj == 5) {
        return '<div class="box red">취소요청</div>';
    } else if ($obj == 9) {
        return '<div class="box blue">거래완료</div>';
    } else {
        return '<div class="box blue">거래완료</div>';
    }
}

function 거래상태안내($status_txt, $tb_item_order = null)
{
    $status = $tb_item_order['status'];

    if ($status == 1) {
        return "물품을 넘기면 {$status_txt}확인을 눌러주세요.";
    } else if ($status == 2) {
        return "물품을 넘기면 {$status_txt}확인을 눌러주세요.";
    } else if ($status == 3) {

        if (!empty($tb_item_order)) {

            $insu = $tb_item_order['insu'];
            $inge = $tb_item_order['inge'];

            if ($insu == 1 && $inge == 0) {

                return '판매자가 인계확인을 누르면 거래가 완료됩니다.';
            }

            if ($insu == 0 && $inge == 1) {

                return '구매자가 인수확인을 누르면 거래가 완료됩니다.';
            }
        }
    } else if ($status == 5) {
        return '구매자가 취소신청을 하였습니다<br/>5분뒤 자동 취소됩니다';
    } else if ($status == 9) {
        return '거래가 완료 되었습니다.';
    } else {
        return '';
    }
}

function 남은시간($targetTime)
{
    // 현재 시간
    $currentTime = new DateTime();
    // 마감 시간
    $deadlineTime = new DateTime($targetTime);

    if ($currentTime > $deadlineTime) {
        return "over";
    }

    // 시간 차이 계산 (분 단위로 변환)
    $interval = $currentTime->diff($deadlineTime);
    $remainingMinutes = ($interval->days * 24 * 60) +   // 남은 일 -> 분
        ($interval->h * 60) +            // 남은 시간 -> 분
        $interval->i;                    // 남은 분

    return $remainingMinutes;
}

function 거래상태안내_구매자($obj)
{
    if ($obj == 1) {
        return "물품을 넘기면 인계확인을 눌러주세요.";
    } else if ($obj == 2) {
        return '물품을 받으면 인수확인을 눌러주세요.';
    } else if ($obj == 3) {
        return '판매자가 인계확인을 누르면 거래가 완료됩니다.';
    } else if ($obj == 5) {
        return '구매자가 취소신청을 하였습니다<br/>5분뒤 자동 취소됩니다';
    } else if ($obj == 9) {
        return '거래가 완료 되었습니다.';
    } else {
        return '';
    }
}


function 판매거래내역_거래상태($obj)
{
    if ($obj == 1) {
        return '<span class="green">입금대기</span>';
    } else if ($obj == 2) {
        return '<span class="green">거래중</span>';
    } else if ($obj == 3) {
        return '<span class="orange">완료예정</span>';
    } else if ($obj == 4) {
        return '<span class="red">거래취소</span>';
    } else if ($obj == 5) {
        return '<span class="red">취소요청</span>';
    } else if ($obj == 9) {
        return '<span class="blue">거래완료</span>';
    } else {
        return '<span class="blue">거래완료</span>';
    }
}

function isSixDigitNumber($birth)
{
    // $birth가 숫자인지 확인하고, 길이가 6자리인지 체크
    return is_numeric($birth) && strlen($birth) === 6;
}

function 채팅시간($givenDate)
{
    // 현재 날짜 및 시간 가져오기
    $now = new DateTime();
    $givenDate = new DateTime($givenDate);

    // 날짜 차이 계산
    $interval = $now->diff($givenDate);

    // 오늘 날짜와 비교
    if ($interval->days == 0) {
        // 오늘이라면 시간:분 형식으로 출력
        return $givenDate->format('H:i');
    } elseif ($interval->days == 1) {
        // 어제라면 "1일 전" 출력
        return "1일 전";
    } else {
        // 그 외의 경우는 "n일 전" 형식으로 출력
        return $interval->days . "일 전";
    }
}

function 랜덤문자열($length = 10)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[mt_rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function 이십분경과체크($givenDate)
{
    // 현재 시간
    $currentDate = new DateTime();
    // 주어진 날짜 시간 객체 생성
    $givenDateTime = new DateTime($givenDate);
    // 현재 시간과 주어진 시간의 차이 계산
    $interval = $currentDate->diff($givenDateTime);
    // 차이를 분 단위로 확인
    $minutesDifference = ($interval->h * 60) + $interval->i;
    // 20분 이상 경과했는지 여부 반환
    return $minutesDifference >= 20;
}


function 삼분분경과체크($givenDate)
{

    // 현재 시간
    $currentDate = new DateTime();
    // 주어진 날짜 시간 객체 생성
    $givenDateTime = new DateTime($givenDate);
    // 현재 시간과 주어진 시간의 차이 계산
    $interval = $currentDate->diff($givenDateTime);
    // 차이를 분 단위로 확인
    $minutesDifference = ($interval->h * 60) + $interval->i;
    // 3분 이상 경과했는지 여부 반환
    return $minutesDifference >= 1;
}

function 십분경과체크($givenDate)
{
    // 현재 시간
    $currentDate = new DateTime();
    // 주어진 날짜 시간 객체 생성
    $givenDateTime = new DateTime($givenDate);
    // 현재 시간과 주어진 시간의 차이 계산
    $interval = $currentDate->diff($givenDateTime);

    // 시간 차이를 분 단위로 계산
    $minutesDifference = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

    // $givenDate가 과거일 경우만 체크 (즉, 10분 이상 경과한 경우)
    if ($givenDateTime > $currentDate && $minutesDifference >= 10) {
        return true;
    }
    return false;
}

function 은행($account_bank)
{
    $banks = [
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
    ];
    return $banks[$account_bank];
}


function 마일리지상태($obj)
{
    if ($obj == 1) {
        return '<div class="sangtae sangtae_red">마일리지 차감</div>';
    } else {
        return '<div class="sangtae sangtae_blue">마일리지 적립</div>';
    }
}

function 평균별점($avg, $class = '')
{
    $avg = floatval($avg);
    $full = floor($avg);                     // 꽉 찬 별
    $half = ($avg - $full) >= 0.5 ? 1 : 0;   // 반 별 (반올림)
    $empty = 5 - $full - $half;              // 빈 별

    $html = $class ? "<div class='{$class}'>" : "<div class='review_total_star'>";

    // 꽉 찬 별
    for ($i = 0; $i < $full; $i++) {
        $html .= '<img src="/assets/app_hyup/images/star_on.png" alt="on">';
    }

    // 반 별 (반 별 이미지 없으면 그냥 on.png로 표시하거나 생략 가능)
    if ($half) {
        $html .= '<img src="/assets/app_hyup/images/star_on.png" alt="on">'; // 또는 star_half.png
    }

    // 빈 별
    for ($i = 0; $i < $empty; $i++) {
        $html .= '<img src="/assets/app_hyup/images/star_off.png" alt="off">';
    }

    $html .= '</div>';

    return $html;
}

function 실제충전금액($amount, $feePercentage)
{
    // 수수료를 계산
    $fee = $amount * ($feePercentage / 100);
    // 수수료를 차감한 금액을 계산
    $amountAfterFee = $amount - $fee;
    return $amountAfterFee;
}

function 마일리지_충전상태($status)
{
    // 상태별 배열 정의
    $충전상태 = array(
        "0" => ['sangtae_green', '충전대기중'],
        "1" => ['sangtae_blue', '충전완료'],
        "2" => ['sangtae_orange', '충전취소'],
        "3" => ['sangtae_silver', '출금신청'],
        "4" => ['sangtae_red', '출금완료']
    );

    // 상태가 존재하는지 확인하고 반환, 없으면 null 반환
    return isset($충전상태[$status]) ? $충전상태[$status] : null;
}
