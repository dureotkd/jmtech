<?php

/**
 * TODO: 복사 기능
 * TODO: 발주서,수주서 확인
 * TODO: PDF 및 엑셀 확인
 * TODO: 세금계산서 발행 확인
 * TODO: supply_amount, tax_amount 확인
 */
class api extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "phpspreadsheet",
            "member",
            "/Service/user_service",
            "/Service/estimate_service",
            "/Service/purchase_service",
            "/Service/event_log_service",
        ]);

        $this->load->model('/Page/service_model');

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        $allowed_origins = [
            "http://localhost:5173",
            "http://127.0.0.1:5173",
            "http://jmtech.test",
            "https://jmtech.test",
            "https://www.jmtech.asia",
            "https://www.saju.asia",
            "https://saju.asia",
            "https://api.saju.asia",
        ];

        if ($origin && in_array($origin, $allowed_origins, true)) {
            header("Access-Control-Allow-Origin: $origin");
            header("Access-Control-Allow-Credentials: true"); // ✅ 쿠키 허용
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, PATCH");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            header("Access-Control-Max-Age: 86400");
        }
    }

    public function get_login_user()
    {
        $user = $this->member->get_login_member();

        echo json_encode($user);
    }

    # Handsontable Excel 템플릿 Load Init (견적서)
    public function load_excel_template()
    {
        $rows = (int)($this->input->get('rows') ?? 3);

        /**
         * * columns 컬럼 정보
         */
        // 내역서 초기 데이터 템플릿 (컬럼 순서대로)
        $rowTemplate = [
            '', // 도번
            '', // 재질
            // 재료비 섹션
            '', // 가로
            '', // 세로
            '', // 두께
            '', // 홀수
            '', // 탭
            '', // 절곡
            '', // 길이
            '', // 후
            '', // 수량
            '', // 비중 (수식으로 자동 설정됨)
            '', // 무게 (수식으로 자동 설정됨)
            '', // 단가 (수식으로 자동 설정됨)
            '', // 소계 (수식으로 자동 설정됨)
            // 가공비 섹션
            '', // 외곽 (수식으로 자동 설정됨)
            '', // 홀탭 (수식으로 자동 설정됨)
            '', // 밴딩 (수식으로 자동 설정됨)
            '', // 용접
            '', // 연마
            '', // 후처리 (수식으로 자동 설정됨)
            '', // 기타 (수식으로 자동 설정됨)
            '', // 소계 (수식으로 자동 설정됨)
            // 기타
            '', // 이익 (수식으로 자동 설정됨)
            '', // 수량 (수식으로 자동 설정됨)
            '', // 단가 (수식으로 자동 설정됨)
            '', // 금액 (수식으로 자동 설정됨)
            '', // 비고
        ];

        // 재료비 섹션 컬럼 인덱스
        $비중ColIndex = 11; // 비중 컬럼 인덱스 (M열)
        $무게ColIndex = 12; // 무게 컬럼 인덱스 (N열)
        $재료비단가ColIndex = 13; // 재료비 단가 컬럼 인덱스 (O열)
        $재료비소계ColIndex = 14; // 재료비 소계 컬럼 인덱스 (P열)

        // 가공비 섹션 컬럼 인덱스
        $외곽ColIndex = 15; // 외곽 컬럼 인덱스 (Q열)
        $홀탭ColIndex = 16; // 홀/탭 컬럼 인덱스 (R열)
        $밴딩ColIndex = 17; // 밴딩 컬럼 인덱스 (S열)
        $용접ColIndex = 18; // 용접 컬럼 인덱스 (T열)
        $연마ColIndex = 19; // 연마 컬럼 인덱스 (U열)
        $후처리ColIndex = 20; // 후처리 컬럼 인덱스 (V열)
        $기타ColIndex = 21; // 기타 컬럼 인덱스 (W열)
        $가공비소계ColIndex = 22; // 가공비 소계 컬럼 인덱스 (X열)

        // 기타 섹션 컬럼 인덱스
        $이익ColIndex = 23; // 이익 컬럼 인덱스 (Y열)
        $최종수량ColIndex = 24; // 최종 수량 컬럼 인덱스 (Z열)
        $최종단가ColIndex = 25; // 최종 단가 컬럼 인덱스 (AA열)
        $금액ColIndex = 26; // 금액 컬럼 인덱스 (AB열)

        // 초기 데이터 생성 ($rows행)
        $initialData = [];
        for ($i = 0; $i < $rows; $i++) {
            $row = $rowTemplate;
            // 각 행의 비중 컬럼에 수식 설정
            // Handsontable은 0부터 시작, Excel은 5행부터 시작 (헤더 2행 포함)
            $rowNum = $i + 1;

            // 재료비 섹션 수식 (0일 때 빈 문자열 반환)
            $row[$비중ColIndex] = "=IF(A{$rowNum}=\"\",\"\",IF(B{$rowNum}=\"SUS\",7.93,IF(B{$rowNum}=\"AL\",2.8,7.85)))";
            $row[$무게ColIndex] = "=IF(OR(A{$rowNum}=\"\",(C{$rowNum}*D{$rowNum}*E{$rowNum}*L{$rowNum})/1000000=0),\"\",(C{$rowNum}*D{$rowNum}*E{$rowNum}*L{$rowNum})/1000000)";
            $row[$재료비단가ColIndex] = "=IF(A{$rowNum}=\"\",\"\",IF(B{$rowNum}=\"SUS\",6500,IF(B{$rowNum}=\"AL\",7500,1600)))";
            $row[$재료비소계ColIndex] = "=IF(OR(A{$rowNum}=\"\",ROUND(M{$rowNum}*N{$rowNum},0)=0),\"\",ROUND(M{$rowNum}*N{$rowNum},0))";

            // 가공비 섹션 수식 (0일 때 빈 문자열 반환)
            $row[$외곽ColIndex] = "=IF(OR(A{$rowNum}=\"\",IF(E{$rowNum}>=3,(C{$rowNum}+D{$rowNum})*2*E{$rowNum},(C{$rowNum}+D{$rowNum})*5)=0),\"\",IF(E{$rowNum}>=3,(C{$rowNum}+D{$rowNum})*2*E{$rowNum},(C{$rowNum}+D{$rowNum})*5))";
            $row[$홀탭ColIndex] = "=IF(OR(A{$rowNum}=\"\",AND(F{$rowNum}=\"\",G{$rowNum}=\"\")),\"\",IF(IF(E{$rowNum}>=4,(F{$rowNum}+(G{$rowNum}*1.5))*300*1.5,(F{$rowNum}+(G{$rowNum}*1.5))*300)=0,\"\",IF(E{$rowNum}>=4,(F{$rowNum}+(G{$rowNum}*1.5))*300*1.5,(F{$rowNum}+(G{$rowNum}*1.5))*300)))";
            $row[$밴딩ColIndex] = "=IF(OR(H{$rowNum}=\"\",IF(E{$rowNum}>=4,H{$rowNum}*I{$rowNum}*3*1.5,H{$rowNum}*I{$rowNum}*3)=0),\"\",IF(E{$rowNum}>=4,H{$rowNum}*I{$rowNum}*3*1.5,H{$rowNum}*I{$rowNum}*3))";
            // 용접, 연마는 수동 입력
            $row[$후처리ColIndex] = "=IF(OR(J{$rowNum}=\"\",ROUND(IF(J{$rowNum}=\"E\",C{$rowNum}*D{$rowNum}*0.15,IF(J{$rowNum}=\"N\",C{$rowNum}*D{$rowNum}*0.12,IF(J{$rowNum}=\"A\",C{$rowNum}*D{$rowNum}*0.075,IF(J{$rowNum}=\"P\",C{$rowNum}*D{$rowNum}*0.025,C{$rowNum}*D{$rowNum}*0.04)))),0)=0),\"\",ROUND(IF(J{$rowNum}=\"E\",C{$rowNum}*D{$rowNum}*0.15,IF(J{$rowNum}=\"N\",C{$rowNum}*D{$rowNum}*0.12,IF(J{$rowNum}=\"A\",C{$rowNum}*D{$rowNum}*0.075,IF(J{$rowNum}=\"P\",C{$rowNum}*D{$rowNum}*0.025,C{$rowNum}*D{$rowNum}*0.04)))),0))";
            // 기타는 수동 입력
            $row[$가공비소계ColIndex] = "=IF(OR(A{$rowNum}=\"\",ROUND(SUM(P{$rowNum}:V{$rowNum}),0)=0),\"\",ROUND(SUM(P{$rowNum}:V{$rowNum}),0))";

            // 기타 섹션 수식 (0일 때 빈 문자열 반환)
            $row[$이익ColIndex] = "=IF(OR(A{$rowNum}=\"\",ROUND((W{$rowNum}+O{$rowNum})*0.15,0)=0),\"\",ROUND((W{$rowNum}+O{$rowNum})*0.15,0))";
            $row[$최종수량ColIndex] = "=IF(OR(K{$rowNum}=\"\",K{$rowNum}=0),\"\",K{$rowNum})";
            $row[$최종단가ColIndex] = "=IF(OR(A{$rowNum}=\"\",ROUNDUP(X{$rowNum}+W{$rowNum}+O{$rowNum},-2)=0),\"\",ROUNDUP(X{$rowNum}+W{$rowNum}+O{$rowNum},-2))";
            $row[$금액ColIndex] = "=IF(OR(A{$rowNum}=\"\",Z{$rowNum}*Y{$rowNum}=0),\"\",Z{$rowNum}*Y{$rowNum})";
            $initialData[] = $row;
        }

        array_push($initialData, [
            '', // 도번
            '', // 재질
            // 재료비 섹션
            '', // 가로
            '', // 세로
            '', // 두께
            '', // 홀수
            '', // 탭
            '', // 절곡
            '', // 길이
            '', // 후
            '', // 수량
            '', // 비중 (수식으로 자동 설정됨)
            '', // 무게 (수식으로 자동 설정됨)
            '', // 단가 (수식으로 자동 설정됨)
            '자재비', // 소계 (수식으로 자동 설정됨)
            // 가공비 섹션
            '절단비', // 외곽 (수식으로 자동 설정됨)
            '홀/탭', // 홀탭 (수식으로 자동 설정됨)
            '밴딩', // 밴딩 (수식으로 자동 설정됨)
            '용접', // 용접
            '연마', // 연마
            '후처리', // 후처리 (수식으로 자동 설정됨)
            '', // 기타 (수식으로 자동 설정됨)
            '', // 소계 (수식으로 자동 설정됨)
            // 기타
            '', // 이익 (수식으로 자동 설정됨)
            '', // 수량 (수식으로 자동 설정됨)
            '', // 단가 (수식으로 자동 설정됨)
            '합계금액', // 금액 (수식으로 자동 설정됨)
            '', // 비고
        ]);
        array_push($initialData, [
            '', // 도번
            '', // 재질
            // 재료비 섹션
            '', // 가로
            '', // 세로
            '', // 두께
            '', // 홀수
            '', // 탭
            '', // 절곡
            '', // 길이
            '', // 후
            '', // 수량
            '', // 비중 (수식으로 자동 설정됨)
            '', // 무게 (수식으로 자동 설정됨)
            '', // 단가 (수식으로 자동 설정됨)
            "=SUMPRODUCT(K1:K{$rows},O1:O{$rows})", // 소계 (수식으로 자동 설정됨)
            // 가공비 섹션
            "=SUMPRODUCT(K1:K{$rows},P1:P{$rows})", // 외곽 (수식으로 자동 설정됨)
            "=SUMPRODUCT(K1:K{$rows},Q1:Q{$rows})", // 홀탭 (수식으로 자동 설정됨)
            "=SUMPRODUCT(K1:K{$rows},R1:R{$rows})", // 밴딩 (수식으로 자동 설정됨)
            "=SUMPRODUCT(K1:K{$rows},S1:S{$rows})", // 용접 (수식으로 자동 설정됨)
            "=SUMPRODUCT(K1:K{$rows},T1:T{$rows})", // 연마 (수식으로 자동 설정됨)
            "=SUMPRODUCT(K1:K{$rows},U1:U{$rows})", // 후처리 (수식으로 자동 설정됨)
            '', // 기타 (수식으로 자동 설정됨)
            '', // 소계 (수식으로 자동 설정됨)
            // 기타
            '', // 이익 (수식으로 자동 설정됨)
            '', // 수량 (수식으로 자동 설정됨)
            '', // 단가 (수식으로 자동 설정됨)
            "=SUM(AA1:AA{$rows})", // 금액 (수식으로 자동 설정됨)
            '', // 비고
        ]);

        // 견적서 초기 데이터 템플릿
        $견적서RowTemplate = [
            '', // 도면번호/품명 (수식으로 자동 설정됨)
            '', // 소재 (수식으로 자동 설정됨)
            '', // 수량 (수식으로 자동 설정됨)
            '', // 단위 (수식으로 자동 설정됨)
            '', // 단가 (수식으로 자동 설정됨)
            '', // 금액 (수식으로 자동 설정됨)
            '', // 비고
        ];

        // 견적서 초기 데이터 생성 (3행)
        $견적서InitialData = [];
        for ($i = 0; $i < $rows; $i++) {
            $row = $견적서RowTemplate;
            // Handsontable은 0부터 시작, Excel은 5행부터 시작
            $rowNum = $i + 1;

            // 견적서 수식 설정 (내역서 참조)
            $row[0] = "=IF('내역서'!A{$rowNum}=\"\",\"\",'내역서'!A{$rowNum})"; // 도면번호/품명
            $row[1] = "=IF('내역서'!B{$rowNum}=\"\",\"\",'내역서'!B{$rowNum})"; // 소재
            $row[2] = "='내역서'!Y{$rowNum}"; // 수량
            $row[3] = "=IF(A{$rowNum}=\"\",\"\",\"EA\")"; // 단위
            $row[4] = "='내역서'!Z{$rowNum}"; // 단가
            $row[5] = "='내역서'!AA{$rowNum}"; // 금액
            // 비고는 빈 값

            $견적서InitialData[] = $row;
        }

        $sheets = [
            [
                'name' => '내역서',
                'data' => $initialData,
                'columns' => [
                    ['title' => '도번', 'className' => 'htCenter'],
                    ['title' => '재질', 'className' => 'htCenter'],
                    // 재료비 섹션
                    ['title' => '가로', 'className' => 'htRight'],
                    ['title' => '세로', 'className' => 'htRight'],
                    ['title' => '두께', 'className' => 'htRight'],
                    ['title' => '홀수', 'className' => 'htRight'],
                    ['title' => '탭', 'className' => 'htRight'],
                    ['title' => '절곡', 'className' => 'htRight'],
                    ['title' => '길이', 'className' => 'htRight'],
                    ['title' => '후', 'className' => 'htCenter'],
                    ['title' => '수량', 'className' => 'htRight'],
                    ['title' => '비중', 'className' => 'htRight'],
                    [
                        'title' => '무게',
                        'type' => 'numeric',
                        'className' => 'htRight',
                        'numericFormat' => [
                            'pattern' => '0.00', // 소수점 2자리
                        ],
                    ],
                    ['title' => '단가', 'className' => 'htRight'],
                    [
                        'title' => '소계',
                        'type' => 'numeric',
                        'className' => 'htRight',
                        'numericFormat' => [
                            'pattern' => '0,0', // 콤마 구분, 정수
                        ],
                    ],
                    // 가공비 섹션
                    [
                        'title' => '외곽',
                        'className' => 'htRight',
                        'type' => 'numeric',
                        'numericFormat' => [
                            'pattern' => '0,0', // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '홀/탭',
                        'className' => 'htRight',
                        'type' => 'numeric',
                        'numericFormat' => [
                            'pattern' => '0,0', // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '밴딩',
                        'className' => 'htRight',
                    ],
                    ['title' => '용접', 'className' => 'htRight'],
                    ['title' => '연마', 'className' => 'htRight'],
                    [
                        'title' => '후처리',
                        'className' => 'htRight',
                        'type' => 'numeric',
                        'numericFormat' => [
                            'pattern' => '0,0', // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    ['title' => '기타', 'className' => 'htRight'],
                    [
                        'title' => '소계',
                        'type' => 'numeric',
                        'className' => 'htRight',
                        'numericFormat' => [
                            'pattern' => '0,0', // 콤마 구분, 정수
                        ],
                    ],
                    // 기타
                    [
                        'title' => '이익',
                        'className' => 'htRight',
                        'type' => 'numeric',
                        'numericFormat' => [
                            'pattern' => '0,0', // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '수량',
                        'className' => 'htRight',
                        'type' => 'numeric',
                        'numericFormat' => [
                            'pattern' => '0,0', // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '단가',
                        'className' => 'htRight',
                        'type' => 'numeric',
                        'numericFormat' => [
                            'pattern' => '0,0', // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '금액',
                        'className' => 'htRight',
                        'type' => 'numeric',
                        'numericFormat' => [
                            'pattern' => '0,0', // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    ['title' => '비고', 'className' => 'htCenter'],
                ],
                'nestedHeaders' => [
                    // 첫 번째 행: 알파벳 (A~AB)
                    // 두 번째 행 (상위 헤더)
                    [
                        ['label' => '도번', 'colspan' => 1],
                        ['label' => '재질', 'colspan' => 1],
                        ['label' => '재료비', 'colspan' => 13], // 가로~소계 (13개)
                        ['label' => '가공비', 'colspan' => 8], // 외곽~소계 (8개)
                        ['label' => '이익', 'colspan' => 1],
                        ['label' => '수량', 'colspan' => 1],
                        ['label' => '단가', 'colspan' => 1],
                        ['label' => '금액', 'colspan' => 1],
                        ['label' => '비고', 'colspan' => 1],
                    ],
                    // 두 번째 행 (하위 헤더 - 실제 컬럼명)
                    // NO, 도번, 재질, 이익, 수량, 단가, 금액, 비고는 빈 공간으로
                    [
                        '', // 도번 (빈 공간)
                        '', // 재질 (빈 공간)
                        // 재료비 섹션 (13개)
                        '가로',
                        '세로',
                        '두께',
                        '홀수',
                        '탭',
                        '절곡',
                        '길이',
                        '후',
                        '수량',
                        '비중',
                        '무게',
                        '단가',
                        '소계',
                        // 가공비 섹션 (8개)
                        '외곽',
                        '홀/탭',
                        '밴딩',
                        '용접',
                        '연마',
                        '후처리',
                        '기타',
                        '소계',
                        '', // 이익 (빈 공간)
                        '', // 수량 (빈 공간)
                        '', // 단가 (빈 공간)
                        '', // 금액 (빈 공간)
                        '', // 비고 (빈 공간)
                    ],
                    [
                        'A',
                        'B',
                        'C',
                        'D',
                        'E',
                        'F',
                        'G',
                        'H',
                        'I',
                        'J',
                        'K',
                        'L',
                        'M',
                        'N',
                        'O',
                        'P',
                        'Q',
                        'R',
                        'S',
                        'T',
                        'U',
                        'V',
                        'W',
                        'X',
                        'Y',
                        'Z',
                        'AA',
                        'AB'
                    ],
                ],
                'colWidths' => [
                    80,  // 도번
                    80,  // 재질
                    // 재료비
                    60,  // 가로
                    60,  // 세로
                    60,  // 두께
                    50,  // 홀수
                    50,  // 탭
                    50,  // 절곡
                    60,  // 길이
                    50,  // 후
                    60,  // 수량
                    60,  // 비중
                    60,  // 무게
                    80,  // 단가
                    80,  // 소계
                    // 가공비
                    60,  // 외곽
                    60,  // 홀/탭
                    60,  // 밴딩
                    60,  // 용접
                    60,  // 연마
                    60,  // 후처리
                    60,  // 기타
                    80,  // 소계
                    // 기타
                    60,  // 이익
                    60,  // 수량
                    80,  // 단가
                    100, // 금액
                    100, // 비고
                ],
                'height' => $rows < 10 ? 'auto' : 500,
                'totalRowIndex' => count($initialData) - 1, // 마지막 행 인덱스 (배경색 적용, 고정 행)
            ],
            [
                'name' => '견적서',
                'data' => $견적서InitialData,
                'columns' => [
                    [
                        'title'     => '도면번호/품명',
                        'className' => 'htCenter',
                    ],
                    [
                        'title' => '소재',
                        'className' => 'htCenter',
                    ],
                    [
                        'title' => '수량',
                        'type' => 'numeric',
                        'className' => 'htRight',
                        'numericFormat' => [
                            'pattern' => '0,0',  // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '단위',
                        'className' => 'htCenter',
                    ],
                    [
                        'title' => '단가',
                        'type' => 'numeric',
                        'className' => 'ht-yellow-bg htRight',
                        'numericFormat' => [
                            'pattern' => '0,0',  // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '금액',
                        'type' => 'numeric',
                        'className' => 'ht-yellow-bg htRight',
                        'numericFormat' => [
                            'pattern' => '0,0',  // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '비고',
                        'className' => 'htCenter',
                    ]
                ],
                'colWidths' => [220, 60, 60, 100, 140, 140, 80],
                // 'height' => $rows < 10 ? 'auto' : 300,
                // 'height' => 100,
            ],
        ];

        echo json_encode($sheets);
        exit;
    }

    # Handsontable Excel 템플릿 Load Init (거래명세표)
    public function load_excel_template_v2()
    {
        $sub_type = $this->input->get('sub_type') ?? '';

        // $items = $this->service_model->get_item('all', [
        //     "is_active = 1"
        // ]);

        // $source = [];

        // if (!empty($items)) {
        //     foreach ($items as $item) {
        //         $source[] = [
        //             'key'   => $item['id'],
        //             'value' => "{$item['item_code']} // {$item['item_name']} // {$item['unit']}",
        //             'title' => $item['item_name'],
        //         ];
        //     }
        // }

        $sheets = [
            [
                'name' => $sub_type === 'MI' ? '매입 거래명세표' : '매출 거래명세표',
                'key' => $sub_type,
                'data' => [
                    [], // ^ 데이터
                    [],
                    [],
                    [],
                    [],
                    [],
                ],
                'columns' => [
                    [
                        'title' => '날짜',
                        'type' => 'date',            // ✅ 날짜 타입 지정
                        'dateFormat' => 'YYYY-MM-DD', // 표시 포맷
                        'correctFormat' => true,      // 자동으로 형식 맞춰줌
                        'allowInvalid' => false,      // 잘못된 형식 입력 시 거부
                        'defaultDate' => date('Y-m-d'), // 기본값 설정 (선택사항)
                        'datePickerConfig' => [
                            'firstDay' => 0,
                            'i18n' => [
                                'previousMonth' => '이전 달',
                                'nextMonth' => '다음 달',
                                'months' => ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
                                'weekdays' => ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
                                'weekdaysShort' => ['일', '월', '화', '수', '목', '금', '토']
                            ],
                        ]
                    ],
                    [
                        'title'     => '품목',
                        // 'type'      => 'dropdown',

                        // ^ 드롭다운 샘플 데이터
                        // 'source'    => $source,
                    ],
                    [
                        'title' => '규격',
                    ],
                    [
                        'title' => '수량',
                        'type' => 'numeric',
                        'numericFormat' => [
                            'pattern' => '0,0',  // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '단가',
                        'type' => 'numeric',
                        'className' => 'ht-yellow-bg',
                        'numericFormat' => [
                            'pattern' => '0,0',  // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '공급가액',
                        'type' => 'numeric',
                        'className' => 'ht-red-text',
                        'numericFormat' => [
                            'pattern' => '0,0',  // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '세액',
                        'type' => 'numeric',
                        'className' => 'ht-red-text',
                        'numericFormat' => [
                            'pattern' => '0,0',  // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '비고',
                    ]
                ],
                'colWidths' => [110, 250, 60, 60, 100, 120, 100, 80],
                'height' => 'auto',
            ],
        ];

        echo json_encode($sheets);
        exit;
    }

    # Handsontable Excel 템플릿 Load Init (발주서,수주서)
    public function load_excel_template_v3()
    {
        $sub_type = $this->input->get('sub_type') ?? '';

        switch ($sub_type) {
            case 'B':
                $sheet_name = '발주서';
                break;
            case 'S':
                $sheet_name = '수주서';
                break;
        }

        // $items = $this->service_model->get_item('all', [
        //     "is_active = 1"
        // ]);

        // $source = [];

        // if (!empty($items)) {
        //     foreach ($items as $item) {
        //         $source[] = [
        //             'key'   => $item['id'],
        //             'value' => "{$item['item_code']} // {$item['item_name']} // {$item['unit']}",
        //             'title' => $item['item_name'],
        //         ];
        //     }
        // }

        $sheets = [
            [
                'name' => $sheet_name,
                'data' => [
                    [], // ^ 데이터
                    [],
                    [],
                    [],
                    [],
                    [],
                ],
                'columns' => [
                    [
                        'title'     => '품목',
                        // 'type'      => 'dropdown',
                        // ^ 드롭다운 샘플 데이터
                        // 'source'    => $source,
                    ],
                    [
                        'title' => '규격',
                    ],
                    [
                        'title' => '수량',
                        'type' => 'numeric',
                        'numericFormat' => [
                            'pattern' => '0,0',  // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '단가',
                        'type' => 'numeric',
                        'className' => 'ht-yellow-bg',
                        'numericFormat' => [
                            'pattern' => '0,0',  // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '공급가액',
                        'type' => 'numeric',
                        'className' => 'ht-red-text',
                        'numericFormat' => [
                            'pattern' => '0,0',  // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '세액',
                        'type' => 'numeric',
                        'className' => 'ht-red-text',
                        'numericFormat' => [
                            'pattern' => '0,0',  // ✅ 콤마(천 단위 구분)
                        ],
                    ],
                    [
                        'title' => '비고',
                    ]
                ],
                'colWidths' => [360, 60, 60, 100, 120, 100, 80],
                'height' => 300,
            ],
        ];

        echo json_encode($sheets);
        exit;
    }

    # 저장된 Excel Template Load
    public function load_saved_excel_template()
    {

        $id = $this->input->get('id') ?? '';
        $sub_type = $this->input->get('sub_type') ?? '';

        $res_array = [
            'ok'    => true,
            'msg'   => '',
            'data'  => [],
        ];

        try {

            if (empty($id)) {
                throw new Exception('견적서 ID가 누락되었습니다.');
            }

            switch ($sub_type) {

                case 'MI':
                case 'MC':

                    $statement = $this->service_model->get_transcation_statement('row', [
                        "id = '{$id}'",
                        "sub_type = '{$sub_type}'"
                    ]);

                    if (empty($statement)) {
                        throw new Exception('존재하지 않는 명세서입니다.');
                    }

                    $sheets = !empty($statement['sheets']) ? json_decode($statement['sheets'], true) : [];

                    $files = $this->service_model->get_file('all', [
                        "ref_table = 'statement'",
                        "ref_id = '{$id}'"
                    ]);

                    $statement['sheets'] = $sheets;
                    $res_array['data'] = [
                        'statement' => $statement,
                        'files'    => $files,
                    ];

                    break;

                case 'G':

                    $estimate = $this->service_model->get_estimate('row', [
                        "id = '{$id}'"
                    ]);

                    if (empty($estimate)) {
                        throw new Exception('존재하지 않는 견적서입니다.');
                    }

                    $estimate_sheet = $this->service_model->get_estimate_sheet('row', [
                        "estimate_id = '{$id}'"
                    ]);

                    if (empty($estimate_sheet)) {
                        throw new Exception('존재하지 않는 견적서 시트입니다.');
                    }

                    $sheets = !empty($estimate['sheets']) ? json_decode($estimate['sheets'], true) : [];

                    /**
                     * 0번쨰 견적서
                     * 1번쨰 내역서
                     */
                    $original_sheets = !empty($estimate_sheet['sheets']) ? json_decode($estimate_sheet['sheets'], true) : [];

                    // * 내역서 시트 데이터 (ORIGINAL DATA)
                    $sheets[0]['data'] = $original_sheets[1];

                    // * 견적서 시트 데이터 (ORIGINAL DATA)
                    $sheets[1]['data'] = $original_sheets[0];

                    $files = $this->service_model->get_file('all', [
                        "ref_table = 'estimate'",
                        "ref_id = '{$id}'"
                    ]);

                    $estimate['sheets'] = $sheets;
                    $res_array['data'] = [
                        'estimate' => $estimate,
                        'files'    => $files,
                    ];

                    break;

                case 'B':
                case 'S':
                    $estimate = $this->service_model->get_estimate('row', [
                        "id = '{$id}'"
                    ]);

                    if (empty($estimate)) {
                        throw new Exception('존재하지 않는 견적서입니다.');
                    }

                    $sheets = !empty($estimate['sheets']) ? json_decode($estimate['sheets'], true) : [];

                    $files = $this->service_model->get_file('all', [
                        "ref_table = 'estimate'",
                        "ref_id = '{$id}'"
                    ]);

                    $estimate['sheets'] = $sheets;
                    $res_array['data'] = [
                        'estimate' => $estimate,
                        'files'    => $files,
                    ];

                    break;
            }
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    # 거래내역 불러오기 
    # 
    public function get_trade_history()
    {
        $company_name = $this->input->get('company_name') ?? '';

        /**
         * * 
         * * 견적서
         * * 수주서
         * * 매입명세서
         * * 매출명세서
         * * 발주서
         * * 세금계산서 전부 가져오기..
         */
        $res_array = [
            'ok'    => true,
            'msg'   => '',
            'data'  => [],
        ];

        $where = [];

        if (!empty($company_name)) {

            $partner = $this->service_model->get_business_partner('row', [
                "company_name = '{$company_name}'"
            ]);

            if (!empty($partner)) {

                $where[] = "partner_id = '{$partner['id']}'";
            }
        }

        $data = [];
        $type_vo = [
            'G' => '견적서',
            'S' => '수주서',
            'B' => '발주서',
            'MI' => '매입명세서',
            'MC' => '매출명세서',
            'TAX_INVOICE' => '세금계산서',
        ];

        // * 수주서,발주서
        $estimate_all = $this->service_model->get_estimate('all', array_merge($where, [
            "sub_type != 'G'"
        ]));

        // * 매입명세서,매출명세서
        $transcation_statement_all = $this->service_model->get_transcation_statement('all', array_merge($where, [
            1
        ]));

        // // * 세금계산서
        // $barobill_tax_invoice_all = $this->service_model->get_barobill_tax_invoice('all', array_merge($where, [
        //     1
        // ]));

        if (!empty($estimate_all)) {

            foreach ($estimate_all as $estimate) {

                $sub_type = $estimate['sub_type'];
                $estimate_date = $estimate['estimate_date'];
                $partner_name = $estimate['partner_name'];
                $sheets = !empty($estimate['sheets']) ? json_decode($estimate['sheets'], true) : [];

                $data[] = [
                    'id' => $estimate['id'],
                    '구분' => $type_vo[$sub_type],
                    '월일' => $estimate_date,
                    '거래처명' => $partner_name,
                    '공급가액' => $estimate['supply_amount'],
                    '세액' => $estimate['tax_amount'],
                    'sheets' => $sheets,
                ];
            }
        }

        if (!empty($transcation_statement_all)) {

            foreach ($transcation_statement_all as $row) {

                $sub_type = $row['sub_type'];
                $estimate_date = $row['estimate_date'];
                $partner_name = $row['partner_name'];
                $supply_amount = $row['supply_amount'];
                $tax_amount = $row['tax_amount'];
                $sheets = !empty($row['sheets']) ? json_decode($row['sheets'], true) : [];

                $data[] = [
                    'id' => $row['id'],
                    '구분' => $type_vo[$sub_type],
                    '월일' => $estimate_date,
                    '거래처명' => $partner_name,
                    '공급가액' => $supply_amount,
                    '세액' => $tax_amount,
                    'sheets' => $sheets,
                ];
            }
        }

        // * estimate_date 기준으로 정렬

        // PHP 내장 usort 사용해서 정렬 (월일 또는 estimate_date 기준)
        usort($data, function ($a, $b) {
            $dateA = $a['월일'] ?? $a['estimate_date'] ?? '';
            $dateB = $b['월일'] ?? $b['estimate_date'] ?? '';
            // 날짜가 동일하면 0, A가 작으면 -1, 크면 1

            // * DESC
            if ($dateA === $dateB) {
                return 0;
            }

            if ($dateA > $dateB) {
                return -1;
            }

            return 1;
        });

        /**
         * 하나의 포맷으로 변환 해야함..
         * * 월일 2025-01-01
         * * 품목 SUS304 2P 8T 4X8
         * * 규격
         * * 수량 1 / SH
         * * 단가 
         * * 공급가액
         * * 세액
         * * 비고
         */
        $res_array['data'] = $data;
        echo json_encode($res_array);
    }

    # 견적서 저장
    public function save_estimate()
    {
        $id = $this->input->post('id') ?? '';
        $tab = $this->input->post('tab') ?? ''; // * copay (복사)
        $type = $this->input->post('type') ?? ''; // * sell / buy (판매,구매)
        $sub_type = $this->input->post('sub_type') ?? ''; // * g / s (견적서,수주서)

        $partner_id = $this->input->post('partner_id') ?? '';
        $estimate_date = $this->input->post('estimate_date') ?? '';
        $phone_number = $this->input->post('phone_number') ?? '';
        $fax_number = $this->input->post('fax_number') ?? '';
        $title = $this->input->post('title') ?? '';
        $sheets = $this->input->post('sheets') ?? '';
        $amount = $this->input->post('amount') ?? 0;
        $supply_amount = $this->input->post('supply_amount') ?? 0;
        $tax_amount = $this->input->post('tax_amount') ?? 0;
        $vat_type = $this->input->post('vat_type') ?? '';
        $due_at = $this->input->post('due_at') ?? '';
        $location = $this->input->post('location') ?? '';
        $valid_at = $this->input->post('valid_at') ?? '';
        $payment_type = $this->input->post('payment_type') ?? '';
        $etc_memo = $this->input->post('etc_memo') ?? '';
        $file_ids = $this->input->post('file_ids') ?? '';
        $real_sheets = $this->input->post('real_sheets') ?? '';

        $amount = (int)preg_replace('/[^0-9]/u', '', $amount); // 숫자만 남김

        $res_array = [
            'ok'    => true,
            'msg'   => '견적서가 저장되었습니다.',
            'data'  => [],
            'redirect_url' => ''
        ];

        foreach ([1] as $proc) {

            try {

                if (!empty($id)) {

                    switch ($tab) {

                        case 'copy':

                            // * 복사 저장
                            $insert_estimate_id = $this->estimate_service->create([
                                'type'              => $type,
                                'sub_type'          => $sub_type,
                                'partner_id'        => $partner_id,
                                'estimate_date'     => $estimate_date,
                                'phone_number'      => $phone_number,
                                'fax_number'        => $fax_number,
                                'title'             => $title,
                                'location'          => $location,
                                'supply_amount'     => $supply_amount,
                                'tax_amount'        => $tax_amount,
                                'amount'            => $amount,
                                'vat_type'          => $vat_type,
                                'sheets'            => $sheets,
                                'due_at'            => $due_at,
                                'valid_at'          => $valid_at,
                                'payment_type'      => $payment_type,
                                'real_sheets'       => $real_sheets,
                                'etc_memo'          => $etc_memo,
                                'tab'               => 'copy',
                            ]);

                            if (empty($insert_estimate_id)) {
                                throw new Error('견적서 복사 저장에 실패했습니다.');
                            }

                            // * 파일 복사
                            $this->estimate_service->cloneFile($insert_estimate_id, $file_ids);

                            // * 추가 파일 업로드있을 경우 처리
                            if (!empty($_FILES)) {

                                $this->estimate_service->uploadFile($insert_estimate_id);
                            }

                            $res_array['msg'] = '견적서가 복사 저장되었습니다.';
                            $res_array['redirect_url'] = "/sales/estimate_detail?id={$insert_estimate_id}";

                            break;

                        default:

                            $update_result = $this->estimate_service->update([
                                'partner_id'        => $partner_id,
                                'estimate_date'     => $estimate_date,
                                'phone_number'      => $phone_number,
                                'fax_number'        => $fax_number,
                                'title'             => $title,
                                'location'          => $location,
                                'amount'            => $amount,
                                'supply_amount'     => $supply_amount,
                                'tax_amount'        => $tax_amount,
                                'vat_type'          => $vat_type,
                                'sheets'            => $sheets,
                                'due_at'            => $due_at,
                                'valid_at'          => $valid_at,
                                'payment_type'      => $payment_type,
                                'etc_memo'          => $etc_memo,
                                'real_sheets'       => $real_sheets,
                                'updated_at'        => date('Y-m-d H:i:s'),
                            ], $id);

                            if (empty($update_result)) {
                                throw new Error('견적서 수정에 실패했습니다.');
                            }

                            $this->estimate_service->deleteFile($id, $file_ids);

                            if (!empty($_FILES)) {

                                $this->estimate_service->uploadFile($id);
                            }

                            $res_array['msg'] = '견적서가 수정되었습니다.';
                            $res_array['redirect_url'] = "/sales/estimate_detail?id={$id}";

                            break;
                    }
                } else {

                    $insert_estimate_id = $this->estimate_service->create([
                        'type'              => $type,
                        'sub_type'          => $sub_type,
                        'partner_id'        => $partner_id,
                        'estimate_date'     => $estimate_date,
                        'phone_number'      => $phone_number,
                        'fax_number'        => $fax_number,
                        'title'             => $title,
                        'location'          => $location,
                        'amount'            => $amount,
                        'supply_amount'     => $supply_amount,
                        'tax_amount'        => $tax_amount,
                        'vat_type'          => $vat_type,
                        'sheets'            => $sheets,
                        'due_at'            => $due_at,
                        'valid_at'          => $valid_at,
                        'payment_type'      => $payment_type,
                        'etc_memo'          => $etc_memo,
                        'real_sheets'       => $real_sheets,
                        'tab'               => 'original',
                    ]);

                    if (empty($insert_estimate_id)) {
                        throw new Error('견적서 저장에 실패했습니다.');
                    }

                    if (!empty($_FILES)) {

                        $this->estimate_service->uploadFile($insert_estimate_id);
                    }

                    $res_array['redirect_url'] = "/sales/estimate_detail?id={$insert_estimate_id}";
                }
            } catch (Exception $e) {
                $res_array['ok'] = false;
                $res_array['msg'] = $e->getMessage();
                break;
            }
        }

        echo json_encode($res_array);
    }

    # 명세표 저장
    public function save_statement()
    {
        $id = $this->input->post('id') ?? '';

        $tab = $this->input->post('tab') ?? ''; // * copay (복사)
        $type = $this->input->post('type') ?? ''; // * sell / buy (판매,구매)
        $sub_type = $this->input->post('sub_type') ?? ''; // * g / s (견적서,수주서)

        $partner_id = $this->input->post('partner_id') ?? '';
        $estimate_date = $this->input->post('estimate_date') ?? '';
        $phone_number = $this->input->post('phone_number') ?? '';
        $fax_number = $this->input->post('fax_number') ?? '';
        $title = $this->input->post('title') ?? '';
        $sheets = $this->input->post('sheets') ?? '';
        $amount = $this->input->post('amount') ?? 0;
        $supply_amount = $this->input->post('supply_amount') ?? 0;
        $tax_amount = $this->input->post('tax_amount') ?? 0;
        $vat_type = $this->input->post('vat_type') ?? '';
        $due_at = $this->input->post('due_at') ?? '';
        $location = $this->input->post('location') ?? '';
        $valid_at = $this->input->post('valid_at') ?? '';
        $payment_type = $this->input->post('payment_type') ?? '';
        $etc_memo = $this->input->post('etc_memo') ?? '';
        $file_ids = $this->input->post('file_ids') ?? '';

        $amount = (int)preg_replace('/[^0-9]/u', '', $amount); // 숫자만 남김

        $res_array = [
            'ok'    => true,
            'msg'   => '견적서가 저장되었습니다.',
            'data'  => [],
            'redirect_url' => ''
        ];

        foreach ([1] as $proc) {

            try {

                if (!empty($id)) {

                    switch ($tab) {

                        case 'copy':

                            // * 복사 저장
                            $insert_statement_id = $this->purchase_service->create([
                                'type'              => $type,
                                'sub_type'          => $sub_type,
                                'partner_id'        => $partner_id,
                                'estimate_date'     => $estimate_date,
                                'phone_number'      => $phone_number,
                                'fax_number'        => $fax_number,
                                'title'             => $title,
                                'location'          => $location,
                                'supply_amount'     => $supply_amount,
                                'tax_amount'        => $tax_amount,
                                'amount'            => $amount,
                                'vat_type'          => $vat_type,
                                'sheets'            => $sheets,
                                'due_at'            => $due_at,
                                'valid_at'          => $valid_at,
                                'payment_type'      => $payment_type,
                                'etc_memo'          => $etc_memo,
                                'tab'               => 'copy',
                            ]);

                            if (empty($insert_statement_id)) {
                                throw new Error('명세표 복사 저장에 실패했습니다.');
                            }

                            // * 파일 복사
                            // $this->purchase_service->cloneFile($insert_statement_id, $file_ids);

                            // * 추가 파일 업로드있을 경우 처리
                            if (!empty($_FILES)) {

                                $this->purchase_service->uploadFile($insert_statement_id);
                            }

                            $res_array['msg'] = '명세표가 복사 저장되었습니다.';
                            $res_array['redirect_url'] = "/purchase/report/statement_detail?id={$insert_statement_id}";

                            break;

                        default:

                            $update_result = $this->purchase_service->update([
                                'partner_id'        => $partner_id,
                                'estimate_date'     => $estimate_date,
                                'phone_number'      => $phone_number,
                                'fax_number'        => $fax_number,
                                'title'             => $title,
                                'location'          => $location,
                                'amount'            => $amount,
                                'supply_amount'     => $supply_amount,
                                'tax_amount'        => $tax_amount,
                                'vat_type'          => $vat_type,
                                'sheets'            => $sheets,
                                'due_at'            => $due_at,
                                'valid_at'          => $valid_at,
                                'payment_type'      => $payment_type,
                                'etc_memo'          => $etc_memo,
                                'updated_at'        => date('Y-m-d H:i:s'),
                            ], $id);

                            if (empty($update_result)) {
                                throw new Error('명세표 수정에 실패했습니다.');
                            }

                            $this->purchase_service->deleteFile($id, $file_ids);

                            if (!empty($_FILES)) {

                                $this->purchase_service->uploadFile($id);
                            }

                            $res_array['msg'] = '명세표가 수정되었습니다.';
                            $res_array['redirect_url'] = "/purchase/report/statement_detail?id={$id}";

                            break;
                    }
                } else {

                    $insert_statment_id = $this->purchase_service->create([
                        'type'              => $type,
                        'sub_type'          => $sub_type,
                        'partner_id'        => $partner_id,
                        'estimate_date'     => $estimate_date,
                        'phone_number'      => $phone_number,
                        'fax_number'        => $fax_number,
                        'title'             => $title,
                        'location'          => $location,
                        'amount'            => $amount,
                        'supply_amount'     => $supply_amount,
                        'tax_amount'        => $tax_amount,
                        'vat_type'          => $vat_type,
                        'sheets'            => $sheets,
                        'due_at'            => $due_at,
                        'valid_at'          => $valid_at,
                        'payment_type'      => $payment_type,
                        'etc_memo'          => $etc_memo,
                        'tab'               => 'original',
                    ]);

                    if (empty($insert_statment_id)) {
                        throw new Error('명세표 저장에 실패했습니다.');
                    }

                    if (!empty($_FILES)) {

                        $this->purchase_service->uploadFile($insert_statment_id);
                    }

                    $res_array['redirect_url'] = "/purchase/report/statement_detail?id={$insert_statment_id}";
                }
            } catch (Exception $e) {
                $res_array['ok'] = false;
                $res_array['msg'] = $e->getMessage();
                break;
            }
        }

        echo json_encode($res_array);
    }

    # 거래처 목록 조회 (AJAX)
    public function get_partner_list()
    {

        $business_partners = $this->service_model->get_business_partner('all', [
            1
        ]);

        echo json_encode($business_partners);
        exit;
    }

    /**
     * 발주서 일괄등록 엑셀(bal_batch_excel 등) 파싱 → Handsontable 발주서 컬럼에 맞는 연관배열 목록
     * 열: A No., B 품번, C 모델, D·E 품명, F 수량(EA), G 단가, H 금액
     */
    public function 발주서일괄등록셋업($excel_file)
    {
        $res_array = [
            'ok'   => true,
            'msg'  => '',
            'data' => [],
        ];

        if (!$excel_file || ($excel_file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            $res_array['ok'] = false;
            $res_array['msg'] = '엑셀 파일 업로드 중 오류가 발생했습니다.';
            return $res_array;
        }

        try {
            $spreadsheet = $this->phpspreadsheet->loadExcelFile($excel_file['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $header_row_num = $this->_발주서일괄_헤더행찾기($rows);
            if ($header_row_num === null) {
                throw new Exception("엑셀 양식이 올바르지 않습니다. (품번·No. 헤더 행을 찾을 수 없습니다.)");
            }

            $max_row = (int)$sheet->getHighestRow();
            for ($row_num = $header_row_num + 1; $row_num <= $max_row; $row_num++) {
                $row = $rows[$row_num] ?? [];
                $flat = '';
                foreach (['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'] as $col) {
                    $flat .= trim((string)($row[$col] ?? ''));
                }
                if ($flat === '') {
                    continue;
                }

                if ($this->_발주서일괄_헤더행같음($row)) {
                    continue;
                }

                $part_no = trim((string)($row['B'] ?? ''));
                $model = trim((string)($row['C'] ?? ''));
                $name_d = trim((string)($row['D'] ?? ''));
                $name_e = trim((string)($row['E'] ?? ''));
                $name = $name_d !== '' ? $name_d : $name_e;
                if ($name_d !== '' && $name_e !== '' && $name_d !== $name_e) {
                    $name = $name_d . ' ' . $name_e;
                }

                $qty = $this->_발주서일괄_숫자셀($sheet, 'F', $row_num);
                $unit_price = $this->_발주서일괄_숫자셀($sheet, 'G', $row_num);
                $amount_cell = $this->_발주서일괄_숫자셀($sheet, 'H', $row_num);

                if ($part_no === '' && $name === '' && $qty == 0 && $unit_price == 0 && $amount_cell == 0) {
                    continue;
                }

                $품목 = $part_no;
                if ($name !== '') {
                    $품목 = $part_no !== '' ? ($part_no . ' ' . $name) : $name;
                }

                $공급가액 = $amount_cell > 0 ? (int)round($amount_cell) : (int)round($qty * $unit_price);
                $세액 = (int)round($공급가액 * 0.1);

                $res_array['data'][] = [
                    '품목'    => $품목,
                    '규격'    => $model,
                    '수량'    => $qty == (int)$qty ? (int)$qty : $qty,
                    '단가'    => (int)round($unit_price),
                    '공급가액' => $공급가액,
                    '세액'    => $세액,
                    '비고'    => '',
                ];
            }

            if (empty($res_array['data'])) {
                $res_array['ok'] = false;
                $res_array['msg'] = '불러올 품목 데이터가 없습니다.';
            }
        } catch (Throwable $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
            $res_array['data'] = [];
        }

        return $res_array;
    }

    private function _발주서일괄_헤더행찾기($rows)
    {
        foreach ($rows as $row_num => $row) {
            if (!is_array($row)) {
                continue;
            }
            $b = trim((string)($row['B'] ?? ''));
            if ($b === '') {
                continue;
            }
            if ((bool)preg_match('/품\s*번|품번/u', $b)) {
                return (int)$row_num;
            }
        }

        return null;
    }

    private function _발주서일괄_헤더행같음($row)
    {
        $b = trim((string)($row['B'] ?? ''));
        return (bool)preg_match('/품\s*번|품번/u', $b);
    }

    private function _발주서일괄_숫자셀($sheet, $col, $row_num)
    {
        try {
            $cell = $sheet->getCell($col . $row_num);
            $v = $cell->getCalculatedValue();
            if (is_numeric($v)) {
                return (float)$v;
            }
            $s = preg_replace('/[^0-9.\-]/', '', (string)$v);

            return $s === '' ? 0.0 : (float)$s;
        } catch (Throwable $e) {
            return 0.0;
        }
    }

    # 엑셀 불러오기 
    // TODO: Excel import 처리
    public function estimate_excel_load()
    {

        $excel_file = $_FILES['excel_file'] ?? null;
        $sheet_name = $this->input->post('sheet_name') ?? '';
        $sub_type = $this->input->post('sub_type') ?? '';

        $res_array = [
            'ok'    => true,
            'msg'   => '',
            'data'  => [],
        ];

        if (empty($excel_file)) {
            $res_array['ok'] = false;
            $res_array['msg'] = '엑셀 파일이 첨부되지 않았습니다.';
            echo json_encode($res_array);
            return;
        }

        if (empty($sheet_name)) {
            $res_array['ok'] = false;
            $res_array['msg'] = '시트를 선택해주세요.';
            echo json_encode($res_array);
            return;
        }

        if ($sub_type == 'B') {
            $res_array = $this->발주서일괄등록셋업($excel_file);
            echo json_encode($res_array);
            return;
        }

        if ($sheet_name != '내역서') {
            $res_array['ok'] = false;
            $res_array['msg'] = '현재 내역서만 엑셀 불러오기가 가능합니다.';
            echo json_encode($res_array);
            return;
        }

        if (!$excel_file || $excel_file['error'] !== UPLOAD_ERR_OK) {
            $res_array['ok'] = false;
            $res_array['msg'] = '엑셀 파일 업로드 중 오류가 발생했습니다.';
            echo json_encode($res_array);
            return;
        }

        // * Excel Upload 후 데이터 파싱
        try {
            // 엑셀 로드
            $spreadsheet = $this->phpspreadsheet->loadExcelFile($excel_file['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            $first_header = $rows[3] ?? [];
            $second_header = $rows[4] ?? [];

            if (empty($first_header) || empty($second_header)) {
                throw new Exception("엑셀 파일의 헤더가 올바르지 않습니다.\nERROR_CODE: 1001");
            }

            // 첫 번째 헤더 필수 컬럼 검증
            $first_required_columns = ['NO', '도번', '재질', '재료비', '가공비', '이익', '수량', '단가', '금액', '비고'];
            $first_header_values = array_map(function ($val) {
                return trim(str_replace(' ', '', $val ?? ''));
            }, array_values($first_header));

            foreach ($first_required_columns as $required_col) {
                $normalized_required = str_replace(' ', '', $required_col);
                $found = false;
                foreach ($first_header_values as $header_val) {
                    if ($header_val === $normalized_required) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    throw new Exception("엑셀 파일의 첫 번째 헤더에 필수 컬럼 '{$required_col}'이 없습니다.\nERROR_CODE: 1002");
                }
            }

            // 두 번째 헤더 필수 컬럼 검증
            $second_required_columns = ['가로', '세로', '두께', '홀수', '탭', '절곡', '길이', '후', '수량', '비중', '무게', '단가', '소계', '외곽', '홀/탭', '밴딩', '용접', '연마', '후처리', '기타'];
            $second_header_values = array_map(function ($val) {
                return trim($val ?? '');
            }, array_values($second_header));

            // 소계는 두 번 나타나므로 별도 검증
            $sogye_count = 0;
            foreach ($second_header_values as $header_val) {
                if ($header_val === '소계') {
                    $sogye_count++;
                }
            }
            if ($sogye_count < 2) {
                throw new Exception("엑셀 파일의 두 번째 헤더에 '소계' 컬럼이 2개 이상 필요합니다.\nERROR_CODE: 1003");
            }

            foreach ($second_required_columns as $required_col) {
                if ($required_col === '소계') continue; // 소계는 위에서 별도 검증
                $found = false;
                foreach ($second_header_values as $header_val) {
                    if ($header_val === $required_col) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    throw new Exception("엑셀 파일의 두 번째 헤더에 필수 컬럼 '{$required_col}'이 없습니다.\nERROR_CODE: 1004");
                }
            }

            /**
             * * 함수가 안걸린 애들만 프론트로 던져주면됌.
             * * 도번 B, 재질 C, 가로 D, 세로 E, 두께 F, 홀수 G, 탭 H, 절곡 I, 길이 J, 후 K
             * * 용접 T, 연마 U, 기타 W, 수량 Z, 비고 AC
             */

            // 헤더 행 제외하고 데이터 행만 처리 (5번째 행부터)
            // $rows의 5번째 키부터 시작되게 추출

            // 추출할 컬럼 정의 (컬럼명 => 컬럼 인덱스)
            $extract_columns = [
                '도번' => 'B',
                '재질' => 'C',
                '가로' => 'D',
                '세로' => 'E',
                '두께' => 'F',
                '홀수' => 'G',
                '탭' => 'H',
                '절곡' => 'I',
                '길이' => 'J',
                '후' => 'K',
                '수량1' => 'L',
                '용접' => 'T',
                '연마' => 'U',
                '기타' => 'W',
                '수량' => 'Z',
                '비고' => 'AC'
            ];

            foreach ($rows as $row_num => $row) {

                if ($row_num < 5) {
                    continue;
                }

                // NO 컬럼이 비어있으면 데이터 행이 아님
                if (empty(trim($row['A'] ?? ''))) {
                    continue;
                }

                $row_data = [];

                foreach ($extract_columns as $column_name => $column_letter) {
                    $cell_value = $row[$column_letter] ?? '';

                    // 셀의 원본 값을 확인하여 수식인지 체크
                    try {
                        $cell = $sheet->getCell($column_letter . $row_num);
                        $cell_value_raw = $cell->getValue();

                        // 수식이 아닌 경우만 값 추출 (수식은 '='로 시작)
                        if (!is_string($cell_value_raw) || substr($cell_value_raw, 0, 1) !== '=') {
                            $row_data[$column_name] = trim($cell_value);
                        } else {
                            // 수식인 경우 빈 값으로 처리
                            $row_data[$column_name] = '';
                        }
                    } catch (Exception $e) {
                        // 셀 읽기 실패 시 계산된 값 사용
                        $row_data[$column_name] = trim($cell_value);
                    }
                }

                // 모든 값이 비어있지 않은 경우만 추가
                if (!empty(array_filter($row_data))) {
                    $res_array['data'][] = $row_data;
                }
            }
        } catch (Throwable $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    # 견적서 품목양식 다운로드
    public function download_bulk_estimate_item_template()
    {
        $sub_type = $this->input->get('sub_type') ?? '';

        $this->load->helper('download');

        $file_vo_array = [
            'B' => 'bal_batch_excel.xlsx',
        ];

        $file_name = $file_vo_array[$sub_type] ?? 'estimate_batch_excel.xlsx';
        $file_path = FCPATH . "assets/app_hyup/excel/{$file_name}";

        if (!file_exists($file_path)) {
            echo "Not Found" . $file_path;
            return;
        }

        force_download($file_path, NULL);
    }

    # 명세서 조회 (AJAX)
    public function get_statement_detail()
    {

        $id = $this->input->get('id') ?? '';

        $res_array = [
            'ok'    => true,
            'msg'   => '',
            'data'  => [],
        ];

        try {

            if (empty($id)) {
                throw new Exception('명세서 ID가 누락되었습니다.');
            }

            if (strstr($id, ',')) {

                $ids_array = explode(',', $id);
                $statement_all = $this->service_model->get_transcation_statement('all', [
                    "id IN ('" . implode("','", $ids_array) . "')"
                ]);

                if (empty($statement_all)) {
                    throw new Exception('존재하지 않는 명세서입니다.');
                }

                foreach ($statement_all as &$statement) {

                    $sheets = !empty($statement['sheets']) ? json_decode($statement['sheets'], true) : [];
                    $filtered_sheets = [];

                    foreach ($sheets[0]['data'] as $row) {
                        if (!empty($row[0])) {
                            $filtered_sheets[] = $row;
                        }
                    }

                    $statement['sheets'] = $filtered_sheets;
                }

                $res_array['data'] = $statement_all;
            } else {

                $statement = $this->service_model->get_transcation_statement('row', [
                    "id = '{$id}'"
                ]);

                if (empty($statement)) {
                    throw new Exception('존재하지 않는 명세서입니다.');
                }

                $sheets = !empty($statement['sheets']) ? json_decode($statement['sheets'], true) : [];
                $filtered_sheets = [];

                foreach ($sheets[0]['data'] as $row) {
                    if (!empty($row[0])) {
                        $filtered_sheets[] = $row;
                    }
                }

                $statement['sheets'] = $filtered_sheets;
                $res_array['data'] = $statement;
            }
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    # 견적서 조회 (AJAX)
    public function get_estimate_detail()
    {
        $id = $this->input->get('id') ?? '';

        $res_array = [
            'ok'    => true,
            'msg'   => '',
            'data'  => [],
        ];

        try {

            if (empty($id)) {
                throw new Exception('견적서 ID가 누락되었습니다.');
            }

            if (strstr($id, ',')) {

                $ids_array = explode(',', $id);
                $estimate_all = $this->service_model->get_estimate('all', [
                    "id IN ('" . implode("','", $ids_array) . "')"
                ]);

                if (empty($estimate_all)) {
                    throw new Exception('존재하지 않는 견적서입니다.');
                }

                foreach ($estimate_all as &$estimate) {

                    $sheets = !empty($estimate['sheets']) ? json_decode($estimate['sheets'], true) : [];
                    $filtered_sheets = [];

                    foreach ($sheets[0]['data'] as $index => $row) {
                        if (!empty($row[0])) {
                            $filtered_sheets[] = array_merge([$index + 1], $row);
                        }
                    }

                    $estimate['sheets'] = $filtered_sheets;
                }

                $res_array['data'] = $estimate_all;
            } else {

                $estimate = $this->service_model->get_estimate('row', [
                    "id = '{$id}'"
                ]);

                if (empty($estimate)) {
                    throw new Exception('존재하지 않는 견적서입니다.');
                }

                $sheets = !empty($estimate['sheets']) ? json_decode($estimate['sheets'], true) : [];
                $filtered_sheets = [];

                foreach ($sheets[0]['data'] as $index => $row) {
                    if (!empty($row[0])) {

                        // * 순번넣어주기
                        $filtered_sheets[] = array_merge([$index + 1], $row);
                    }
                }

                $estimate['sheets'] = $filtered_sheets;
                $res_array['data'] = $estimate;
            }
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    # Log Event
    public function log_event()
    {
        $event_type = $this->input->post('event_type') ?? '';
        $event_id = $this->input->post('event_id') ?? '';
        $event_table = $this->input->post('event_table') ?? '';

        switch ($event_type) {
            case '인쇄':
                $this->event_log_service->인쇄($event_id, $event_table);
                break;

            case 'PDF출력':
                $this->event_log_service->PDF출력($event_id, $event_table);
                break;

            case '엑셀출력':
                $this->event_log_service->엑셀출력($event_id, $event_table);
                break;
        }

        echo json_encode([
            'ok'    => true,
            'msg'   => '1',
        ]);
    }
}
