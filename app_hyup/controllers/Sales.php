<?php

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class sales extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "phpspreadsheet",
            "/Service/user_service",
            "/Service/estimate_service",
            "file",
        ]);

        $this->load->model('/Page/service_model');
    }

    public function index() {}

    # 매출(거래명세표)
    public function report()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config('report', '매출(거래명세표)'),
        ];

        $this->layout->view('/Sales/report_view', $view_data);
    }

    # 견적서
    public function estimate()
    {

        /**
         *     [id] => 10
            [type] => 
            [no] => 20251102-24B91C
            [estimate_date] => 2025-11-01
            [phone_number] => 010-5653-9944
            [fax_number] => 042-111-1111
            [title] => 112
            [amount] => 13237543
            [amount_in_words] => 
            [status] => draft
            [memo] => 
            [created_at] => 2025-11-02 13:13:13
            [updated_at] => 2025-11-02 13:13:13
            [due_at] => 2025-11-01 00:00:00
            [location] => 11
            [valid_at] => 2025-11-29 00:00:00
            [payment_type] => 22
            [etc_memo] => 22
            [vat_type] => N
            [partner_name] => (유)에이지케이특수강
            [partner_id] => 2
         */
        $estimate_all = $this->service_model->get_estimate('all', [
            1
        ]);

        $view_data =  [
            'layout_data'           => $this->layout_config('estimate', '견적서'),
            'estimate_all'          => $estimate_all,
        ];

        $this->layout->view('/Sales/estimate_view', $view_data);
    }

    # 견적서 상세
    public function estimate_detail()
    {
        $id = $this->input->get('id') ?? '';

        if (empty($id)) {
            show_404();
            return;
        }

        $estimate = $this->service_model->get_estimate('row', [
            "id = {$id}"
        ]);

        if (empty($estimate)) {
            show_404();
            return;
        }

        $files = $this->service_model->get_file('all', [
            "ref_table = 'estimate'",
            "ref_id = {$id}"
        ]);

        $sheets_json = json_decode($estimate['sheets'], true);
        $sheets = $sheets_json[0]['data'] ?? [];

        $view_data =  [
            'id'            => $id,
            'estimate'      => $estimate,
            'sheets'        => $sheets,
            'files'         => $files,
            'layout_data'   => $this->layout_blank_config('estimate', '견적서'),
        ];

        $this->layout->view('/Sales/estimate_detail_view', $view_data);
    }

    # 수주서
    public function order()
    {
        /**
         * 📑 수주서 (Order Confirmation / Sales Order)

➡️ 구매자의 발주를 판매자가 ‘받았다’는 문서

목적: 견적을 승인받고, 실제 거래가 확정된 후 작성

작성 시점: 발주서(구매요청서)가 들어온 뒤

주요 내용:

견적 내용 + 발주번호 + 계약조건 확정사항

실제 납기, 공급일, 세금계산서 발행일 등

의미: “이 주문을 접수했습니다”라는 계약 확정 문서

📘 예시

거래처 A가 발주서를 보내면, JMTech이 “수주서”를 발행 → ERP에서는 이게 실제 매출 예약 데이터로 잡힘
         */

        $view_data =  [
            'faqs'          => '',
            'layout_data'   => $this->layout_config('order', '수주서'),
        ];

        $this->layout->view('/Sales/order_view', $view_data);
    }

    # 견적서 등록 (팝업)
    /**
    ['철판', 'SS400', 10, 15000, '=D1*E1', "='내역서'!D1", ''],
    ['볼트', 'M10', 20, 500, '=D2*E2', '=F2*0.1', ''],
    ['너트', 'M10', 20, 400, '=D3*E3', '=F3*0.1', ''],
    ['용접봉', '6013', 5, 10000, '=D4*E4', '=F4*0.1', ''],
    ['기타', '', 1, 20000, '=D5*E5', '=F5*0.1', ''],
    ['합계', '', 1, 20000, '=D5*E5', '=F5*0.1', ''],
     * @return void
     */
    public function estimate_register()
    {
        $sheets = [
            '견적서' => [
                'name' => '견적서',
                'data' => [
                    [], // ^ 데이터
                    [],
                    [],
                ],
                'columns' => [
                    [
                        'title'     => '품목',
                        'type'      => 'dropdown',
                        'source'    =>  [   // ^ 드롭다운 샘플 데이터
                            // ['key' => '1', 'value' => '00000000041 // 너트(스캔) // EA', 'title' => '너트(스캔)11'],
                            // ['key' => '2', 'value' => '00000000042 // 너트(스캔) // EA', 'title' => '품목'],
                            // ['key' => '3', 'value' => '00000000043 // 너트(스캔) // EA', 'title' => '품목'],
                            // ['key' => '3', 'value' => '00000000044 // 너트(스캔) // EA', 'title' => '품목'],
                            // ['key' => '3', 'value' => '00000000045 // 너트(스캔) // EA', 'title' => '품목'],
                            // ['key' => '3', 'value' => '00000000046 // 너트(스캔) // EA', 'title' => '품목'],
                            // ['key' => '3', 'value' => '000000000473 // 너트(스캔) // EA', 'title' => '품목'],
                        ]
                    ],
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
                'colWidth' => [278, 100, 80, 100, 120, 100, 150],
                'height' => 'auto',
            ],
            '내역서' => [
                'name' => '내역서',
                'data' => [
                    [],
                    [],
                    [],
                ],
                'columns' => [
                    [
                        'title'     => '품목',
                    ],
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
                'colWidth' => [278, 100, 80, 100, 120, 100, 150],
                'height' => 400,
            ],
        ];

        $view_data =  [
            'sheets'                => $sheets,
            'layout_data'           => $this->layout_blank_config('', '견적서 등록'),
        ];

        $this->layout->view('/Sales/estimate_register_view', $view_data);
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

    # 엑셀 다운로드 (견적서,수주서,발주서)
    public function download_estimate_excel()
    {
        $file_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/app_hyup/excel/base_estimate_excel.xlsx';

        if (!file_exists($file_path)) {
            show_404();
            return;
        }

        // 1️⃣ PhpSpreadsheet 로드
        $spreadsheet = IOFactory::load($file_path);
        $sheet = $spreadsheet->getSheet(0);

        // 2️⃣ 셀 값 입력
        $sheet->setCellValue('B5', '홍길동');
        $sheet->setCellValue('C6', '2025-10-30');
        $sheet->setCellValue('E10', '주식회사 지아이베콤');

        // 3️⃣ 한글 파일명 처리
        $filename = '견적서_' . date('Ymd_His') . '.xlsx';
        $encoded_filename = rawurlencode($filename);

        // 4️⃣ 출력 버퍼 비우기 (가장 중요)
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // 5️⃣ HTTP 헤더 설정
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename*=UTF-8''{$encoded_filename}");
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        header('Expires: 0');

        // 6️⃣ 브라우저로 바로 출력
        $writer = new Xlsx($spreadsheet);
        $writer->setPreCalculateFormulas(false); // 수식 미리계산 방지 (속도 + 안전)
        $writer->save('php://output');
        exit;
    }

    # PDF 다운로드 (견적서,수주서,발주서)
    # /sales/download_estimate_pdf
    public function download_estimate_pdf()
    {
        $type = $this->input->post('type') ?? '';

        // ✅ 한글 깨짐 방지 폰트 설정
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'unbatang',
        ]);

        // ✅ 샘플 데이터
        $items = [
            ['D110-60645A_R001', '', 24, 20000, 480000, 48000],
            ['D201-29479A_R001', '', 4, 40000, 160000, 16000],
            ['D201-29479A_R001', '', 6, 40000, 240000, 24000],
            ['SCP-000171-R04', '', 4, 45000, 180000, 18000],
            ['7375-005-100-203', '', 4, 10000, 40000, 4000],
            ['M27 TAP', '', 1, 70000, 70000, 7000],
            ['D110-60645A_R001', '', 12, 20000, 240000, 24000],
            ['D110-60644A_R001', '', 12, 20000, 240000, 24000],
            ['SUB EARTH BAR', '', 2, 140000, 280000, 28000],
            ['PDCPRW-PM103016A', '', 6, 15000, 90000, 9000],
            ['DEP-101998', '', 1, 50000, 50000, 5000],
            ['DEP-101974', '', 1, 150000, 150000, 15000],
            ['DEP-101275', '', 1, 40000, 40000, 4000],
            ['D104-26390A_R003', '', 2, 140000, 280000, 28000],
            ['D201-29479A_R001', '', 1, 40000, 40000, 4000],
            ['13706A-111299', '', 2, 7000, 14000, 1400],
            ['MK72-152-004-00', '', 32, 5500, 176000, 17600],
        ];

        $total = array_sum(array_column($items, 4));
        $tax = array_sum(array_column($items, 5));
        $totalWithTax = $total + $tax;

        // ✅ HTML 구성
        $estimate_pdf_view = $this->load->view('Pdf/estimate_pdf_view', [
            'items'         => $items,
            'total'         => $total,
            'tax'           => $tax,
            'totalWithTax'  => $totalWithTax,
        ], true);

        // ✅ PDF 출력
        $mpdf->SetHTMLHeader('
  <div style="width:100%; position:relative;">
    <img src="http://jmtech.net/theme/mv305/img/logo-color.png" style="width:150px; margin-top:27px;">
  </div>
');

        $mpdf->WriteHTML($estimate_pdf_view);
        $mpdf->Output('수주서.pdf', 'I'); // D: 다운로드, I: 브라우저보기
    }

    # 견적서 저장
    public function save_estimate()
    {

        $partner_id = $this->input->post('partner_id') ?? '';
        $estimate_date = $this->input->post('estimate_date') ?? '';
        $phone_number = $this->input->post('phone_number') ?? '';
        $fax_number = $this->input->post('fax_number') ?? '';
        $title = $this->input->post('title') ?? '';

        $due_at = $this->input->post('due_at') ?? '';
        $location = $this->input->post('location') ?? '';
        $valid_at = $this->input->post('valid_at') ?? '';
        $payment_type = $this->input->post('payment_type') ?? '';
        $etc_memo = $this->input->post('etc_memo') ?? '';

        $res_array = [
            'ok'    => true,
            'msg'   => '견적서가 저장되었습니다.',
            'data'  => [],
        ];

        foreach ([1] as $proc) {

            try {

                $insert_estimate_id = $this->estimate_service->create([
                    'partner_id'        => $partner_id,
                    'estimate_date'     => $estimate_date,
                    'phone_number'      => $phone_number,
                    'fax_number'        => $fax_number,
                    'title'             => $title,
                    'location'          => $location,
                    'due_at'            => $due_at,
                    'valid_at'          => $valid_at,
                    'payment_type'      => $payment_type,
                    'etc_memo'          => $etc_memo,
                ]);

                if (empty($insert_estimate_id)) {
                    throw new Exception('견적서 저장에 실패했습니다.');
                }

                if (!empty($_FILES)) {

                    $this->estimate_service->uploadFile($insert_estimate_id);
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

    # 파일 다운로드
    public function download_file()
    {
        $id = $this->input->get('id') ?? '';

        if (empty($id)) {
            show_404();
            return;
        }

        $file = $this->service_model->get_file('row', [
            "id = {$id}"
        ]);

        if (empty($file)) {
            show_404();
            return;
        }


        $this->file->download($file['file_path'], $file['file_name']);
    }

    private function layout_config($sub_menu_code = '', $title = '')
    {

        $this->layout->setLayout("layout/template");
        $this->layout->setTitle($title);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'sales',
            'sub_menu_code'    => $sub_menu_code,
        ];
    }

    private function layout_blank_config($sub_menu_code = '', $title = '')
    {

        $this->layout->setPopHeader($title);
        $this->layout->setLayout("layout/blank");
        $this->layout->setTitle($title);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'sales',
            'sub_menu_code'    => $sub_menu_code,
        ];
    }
}
