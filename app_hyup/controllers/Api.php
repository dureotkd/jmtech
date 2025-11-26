<?php

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

    # Excel 템플릿 Load Init
    public function load_excel_template()
    {

        $items = $this->service_model->get_item('all', [
            "is_active = 1"
        ]);

        $source = [];

        if (!empty($items)) {
            foreach ($items as $item) {
                $source[] = [
                    'key'   => $item['id'],
                    'value' => "{$item['item_code']} // {$item['item_name']} // {$item['unit']}",
                    'title' => $item['item_name'],
                ];
            }
        }

        $sheets = [
            [
                'name' => '견적서',
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
                        'type'      => 'dropdown',

                        // ^ 드롭다운 샘플 데이터
                        'source'    => $source,
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
            [
                'name' => '내역서',
                'data' => [
                    ["='견적서'!D2"],  // ✅ 교차시트 수식
                    [],
                    [],
                ],
                'columns' => [
                    ['title' => '품목'],
                    [
                        'title' => '규격',
                    ],
                    [
                        'title' => '수량',
                    ],
                    [
                        'title' => '단가',
                    ],
                    [
                        'title' => '공급가액',
                    ],
                    [
                        'title' => '세액',
                    ],
                    [
                        'title' => '비고',
                    ]
                ],
                'colWidths' => [300, 100, 60, 100, 120, 100, 100],
                'height' => 'auto',
            ],
        ];

        echo json_encode($sheets);
        exit;
    }

    # Excel 템플릿 Load Init (거래명세표)
    public function load_excel_template_v2()
    {

        $items = $this->service_model->get_item('all', [
            "is_active = 1"
        ]);

        $source = [];

        if (!empty($items)) {
            foreach ($items as $item) {
                $source[] = [
                    'key'   => $item['id'],
                    'value' => "{$item['item_code']} // {$item['item_name']} // {$item['unit']}",
                    'title' => $item['item_name'],
                ];
            }
        }

        $sheets = [
            [
                'name' => '견적서',
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
                        'defaultDate' => '2025-01-01', // 기본값 설정 (선택사항)
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
                        'type'      => 'dropdown',

                        // ^ 드롭다운 샘플 데이터
                        'source'    => $source,
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
            [
                'name' => '내역서',
                'data' => [
                    ["='견적서'!D2"],  // ✅ 교차시트 수식
                    [],
                    [],
                ],
                'columns' => [
                    ['title' => '품목'],
                    [
                        'title' => '규격',
                    ],
                    [
                        'title' => '수량',
                    ],
                    [
                        'title' => '단가',
                    ],
                    [
                        'title' => '공급가액',
                    ],
                    [
                        'title' => '세액',
                    ],
                    [
                        'title' => '비고',
                    ]
                ],
                'colWidths' => [300, 100, 60, 100, 120, 100, 100],
                'height' => 'auto',
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

                case 'B':
                case 'S':
                case 'G':

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

    # 엑셀 불러오기 
    public function estimate_excel_load()
    {

        $excel_file = $_FILES['excel_file'] ?? null;

        $sheet_name = $this->input->post('sheet_name') ?? '';

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

        if (!$excel_file || $excel_file['error'] !== UPLOAD_ERR_OK) {
            $res_array['ok'] = false;
            $res_array['msg'] = '엑셀 파일 업로드 중 오류가 발생했습니다.';
            echo json_encode($res_array);
            return;
        }

        $excel_base_thead = [
            '견적서'    => [
                '품목코드',
                '품목명',
                '규격',
                '창고',
                '수량',
                '단위',
                '단가',
                '공급가',
                '부가세',
                '비고'
            ],
            '내역서'    => [
                '품목명',
                '규격',
                '수량',
                '단가',
                '공급가',
                '부가세',
                '비고'
            ],
        ];

        // * Excel Upload 후 데이터 파싱
        try {
            // 엑셀 로드
            $spreadsheet = $this->phpspreadsheet->loadExcelFile($excel_file['tmp_name']);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            // 첫 번째 행(헤더) 기준으로 파싱
            $header = array_shift($rows);

            foreach ($excel_base_thead[$sheet_name] as $index => $expected_header) {
                $column_letter = chr(65 + $index); // A, B, C, ...
                if (!isset($header[$column_letter]) || trim($header[$column_letter]) !== $expected_header) {
                    throw new Exception("엑셀 파일의 헤더가 올바르지 않습니다. 예상 헤더: '{$expected_header}'");
                }
            }

            if (empty($rows)) {
                throw new Exception('엑셀 파일에 데이터가 없습니다.');
            }

            /**
             *                     ['철판', 'SS400', 10, 15000, '=D1*E1', "='내역서'!D1", ''],
                    ['볼트', 'M10', 20, 500, '=D2*E2', '=F2*0.1', ''],
                    ['너트', 'M10', 20, 400, '=D3*E3', '=F3*0.1', ''],
                    ['용접봉', '6013', 5, 10000, '=D4*E4', '=F4*0.1', ''],
                    ['기타', '', 1, 20000, '=D5*E5', '=F5*0.1', ''],
                    ['합계', '', 1, 20000, '=D5*E5', '=F5*0.1', ''],
             */
            foreach ($rows as $row) {

                $품목코드 = trim($row['A']); // 품목코드
                $품목명   = trim($row['B']); // 품목명
                $규격     = trim($row['C']); // 규격
                $창고     = trim($row['D']); // 창고
                $수량     = (int)trim($row['E']); // 수량
                $단위     = trim($row['F']); // 단위
                $단가     = trim($row['G']); // 단가
                $부가세   = trim($row['I']); // 부가세
                $비고     = trim($row['J']); // 비고

                $단가 = (int)preg_replace('/[^0-9]/u', '', $단가); // 숫자만 남김
                $공급가  = !empty($단가) ? (int)$단가 * $수량 : 0; // 공급가 계산
                $부가세 = !empty($공급가) ? (int)($공급가 * 0.1) : 0; // 부가세 계산

                $res_array['data'][] = [$품목명, $규격, $수량, $단가, $공급가, $부가세, $비고];
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
        $this->load->helper('download');

        $file_path = FCPATH . "assets/app_hyup/excel/estimate_batch_excel.xls";

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

    // * 
    public function test()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.moneypin.biz/bizno/v1/biz/info/base',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "bizNoList": [
                "0000000000",
                "6428700732"
            ]
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer <TOKEN>'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        if ($error) {
            echo "cURL Error: " . $error;
            return;
        }

        // JSON 파싱
        $result = json_decode($response, true);

        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
}
