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
        ]);

        $this->load->model('/Page/service_model');

        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        $allowed_origins = [
            "http://localhost:5173",
            "http://127.0.0.1:5173",
            "http://jmtech.test",
            "https://jmtech.test",
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
                    'value' => "{$item['code']} // {$item['name']} // {$item['unit']}",
                    'title' => $item['name'],
                ];
            }
        }

        $sheets = [
            [
                'name' => '견적서',
                'data' => [
                    ["='내역서'!D2"], // ^ 데이터
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
                'colWidths' => [300, 100, 60, 100, 120, 100, 100],
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

        $res_array = [
            'ok'    => true,
            'msg'   => '',
            'data'  => [],
        ];

        try {

            if (empty($id)) {
                throw new Exception('견적서 ID가 누락되었습니다.');
            }

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

        $type = $this->input->post('type') ?? ''; // * sell / buy (판매,구매)
        $sub_type = $this->input->post('sub_type') ?? ''; // * g / s (견적서,수주서)

        $partner_id = $this->input->post('partner_id') ?? '';
        $estimate_date = $this->input->post('estimate_date') ?? '';
        $phone_number = $this->input->post('phone_number') ?? '';
        $fax_number = $this->input->post('fax_number') ?? '';
        $title = $this->input->post('title') ?? '';
        $sheets = $this->input->post('sheets') ?? '';
        $amount = $this->input->post('amount') ?? 0;
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
        ];

        foreach ([1] as $proc) {

            try {

                if (!empty($id)) {

                    $update_result = $this->estimate_service->update([
                        'partner_id'        => $partner_id,
                        'estimate_date'     => $estimate_date,
                        'phone_number'      => $phone_number,
                        'fax_number'        => $fax_number,
                        'title'             => $title,
                        'location'          => $location,
                        'amount'            => $amount,
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
                        'vat_type'          => $vat_type,
                        'sheets'            => $sheets,
                        'due_at'            => $due_at,
                        'valid_at'          => $valid_at,
                        'payment_type'      => $payment_type,
                        'etc_memo'          => $etc_memo,
                    ]);

                    if (empty($insert_estimate_id)) {
                        throw new Error('견적서 저장에 실패했습니다.');
                    }

                    if (!empty($_FILES)) {

                        $this->estimate_service->uploadFile($insert_estimate_id);
                    }
                }
            } catch (Exception $e) {
                $res_array['ok'] = false;
                $res_array['msg'] = $e->getMessage();
                break;
            }
        }

        printr($res_array);
        exit;

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
}
