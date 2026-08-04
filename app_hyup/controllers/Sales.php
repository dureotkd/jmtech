<?php

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
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
            "hometax"
        ]);

        $this->load->model('/Page/service_model');
    }

    public function index() {}

    # 매출(거래명세표)
    public function report()
    {
        $title = '매출(거래명세표)';

        $page = $this->input->get('page') ?? 1;
        $search_text = $this->input->get('search_text') ?? '';
        $start_date = $this->input->get('start_date') ?? '';
        $end_date = $this->input->get('end_date') ?? '';
        $excel_yn = $this->input->get('excel_yn') ?? 'N';

        $search_date2 = $this->input->get('search_date2') ?? '';

        $where = [
            "type = 'BUY'",    // SELL:판매, BUY:구매
            "sub_type = 'MC'",   // MI:매입,MC:매출
        ];

        if (!empty($search_text)) {
            $search_condition = $this->service_model->build_document_search_condition($search_text);
            if ($search_condition !== '') {
                $where[] = $search_condition;
            }
        }

        if (!empty($start_date)) {

            $where[] = "a.estimate_date >= '{$start_date}'";
        }

        if (!empty($end_date)) {

            $where[] = "a.estimate_date <= '{$end_date}'";
        }

        $statement_all = $this->service_model->get_transcation_statement('all', $where);

        /**
         * [1] => Array
        (
            [id] => 2
            [NTSSendKey] => 2025110441000026erp3i8gr
            [NTSSendDT] => 20251104000000
            [IssueDT] => 20251104113739
            [WriteDate] => 20251104
            [ModifyCode] => 0
            [TaxType] => 2
            [PurposeType] => 2
            [InvoicerCorpNum] => 3128630100
            [InvoicerTaxRegID] => 
            [InvoicerCorpName] => 제이엠테크
            [InvoicerCEOName] => 전용문
            [InvoicerAddr] => 충청남도 천안시 서북구 두정공단1길 149-2 (두정동, 미라클(주)) 제이엠테크
            [InvoicerBizType] => 제조업
            [InvoicerBizClass] => 산업기계 설계 및 개발
            [InvoicerContactName] => 
            [InvoicerEmail] => jmlaser@empas.com
            [InvoiceeCorpNum] => 8608800642
            [InvoiceeTaxRegID] => 
            [InvoiceeCorpName] => 주식회사 플렉시고
            [InvoiceeCEOName] => 이기용
            [InvoiceeAddr] => 천안시 동남구 목천읍 충절로 1065 3동
            [InvoiceeBizType] => 제조
            [InvoiceeBizClass] => 디스플레이, 반도체, 소프트웨어
            [InvoiceeContactName] => 
            [InvoiceeEmail] => flexigo@flexigo.co.kr
            [BrokerCorpNum] => 
            [BrokerTaxRegID] => 
            [BrokerCorpName] => 
            [BrokerCEOName] => 
            [BrokerAddr] => 
            [BrokerBizType] => 
            [BrokerBizClass] => 
            [BrokerContactName] => 
            [BrokerEmail] => 
            [AmountTotal] => 44500
            [TaxTotal] => 0
            [TotalAmount] => 44500
            [Cash] => 0
            [ChkBill] => 0
            [Note] => 0
            [Credit] => 0
            [Remark1] => 기업은행 489-023136-01-017
            [Remark2] => 
            [Remark3] => 
            [ItemName] => DUP-FE01
            [CorpNum] => 8608800642
            [TaxRegID] => 
            [CorpName] => 주식회사 플렉시고
            [CEOName] => 이기용
            [created_at] => 2025-11-21 14:05:21
            [updated_at] => 2025-11-21 14:05:21
            [type] => sales
        )
         */
        $barobill_tax_invoice_all = $this->service_model->get_barobill_tax_invoice('all', [
            "type = 'sales'",
        ]);

        $view_data =  [
            'title'         => $title,
            'page'          => $page,
            'search_text'   => $search_text,
            'start_date'    => $start_date,
            'end_date'      => $end_date,
            'excel_yn'      => $excel_yn,
            'statement_all'  => $statement_all,
            'barobill_tax_invoice_all' => $barobill_tax_invoice_all,
            'layout_data'   => $excel_yn == 'Y' ? $this->layout_none_config() : $this->layout_config('report', $title),
        ];

        if ($excel_yn == 'Y') {

            $filename = $title . '_' . date('Ymd_His') . '.xls';

            // * excel view
            header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
            header("Content-Disposition: attachment; filename=\"{$filename}\"");
            header("Content-Description: PHP Generated Data");
            header("Pragma: no-cache");
            header("Expires: 0");

            // 엑셀은 UTF-8 BOM 넣어주면 한글 깨짐 방지됨
            $this->layout->view('/Sales/report_excel_view', $view_data);
        } else {

            $this->layout->view('/Sales/report_view', $view_data);
        }
    }

    # 견적서
    public function estimate()
    {
        $page = $this->input->get('page') ?? 1;
        $search_text = $this->input->get('search_text') ?? '';
        $start_date = $this->input->get('start_date') ?? '';
        $end_date = $this->input->get('end_date') ?? '';
        $excel_yn = $this->input->get('excel_yn') ?? 'N';

        $where = [
            "type = 'SELL'",    // SELL:판매, BUY:구매
            "sub_type = 'G'",   // G:견적서, S:수주서
        ];

        if (!empty($search_text)) {
            $search_condition = $this->service_model->build_document_search_condition($search_text);
            if ($search_condition !== '') {
                $where[] = $search_condition;
            }
        }

        if (!empty($start_date)) {

            $where[] = "a.estimate_date >= '{$start_date}'";
        }

        if (!empty($end_date)) {

            $where[] = "a.estimate_date <= '{$end_date}'";
        }

        $estimate_all = $this->service_model->get_estimate('all', $where);

        $view_data =  [
            'page'          => $page,
            'search_text'   => $search_text,
            'start_date'    => $start_date,
            'end_date'      => $end_date,
            'excel_yn'      => $excel_yn,
            'layout_data'   => $excel_yn == 'Y' ? $this->layout_none_config() : $this->layout_config('estimate', '견적서'),
            'estimate_all'  => $estimate_all,
        ];

        if ($excel_yn == 'Y') {

            $filename = '견적서' . '_' . date('Ymd_His') . '.xls';

            // * excel view
            header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
            header("Content-Disposition: attachment; filename=\"{$filename}\"");
            header("Content-Description: PHP Generated Data");
            header("Pragma: no-cache");
            header("Expires: 0");

            // 엑셀은 UTF-8 BOM 넣어주면 한글 깨짐 방지됨
            $this->layout->view('/Sales/estimate_excel_view', $view_data);
        } else {

            $this->layout->view('/Sales/estimate_view', $view_data);
        }
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

        $title_vo = [
            'G' => '견적서',
            'S' => '수주서',
            'B' => '발주서',
        ];
        $title = $title_vo[$estimate['sub_type']] ?? '';

        $files = $this->service_model->get_file('all', [
            "ref_table = 'estimate'",
            "ref_id = {$id}"
        ]);

        $sheets_json = json_decode($estimate['sheets'], true);
        $sheets = $sheets_json[0]['data'] ?? [];

        $event_logs = $this->service_model->get_admin_event_log('all', [
            "target_table = 'estimate'",
            "target_id = {$id}"
        ]);

        /**
         *   [0] => D111
            [1] => AL
            [2] => 1
            [3] => EA
            [4] => 49800
            [5] => 49800
            [6] => 
         */
        $view_data =  [
            'id'            => $id,
            'estimate'      => $estimate,
            'sheets'        => $sheets,
            'files'         => $files,
            'title'         => $title,
            'event_logs'    => $event_logs,
            'title_vo'      => $title_vo,
            'layout_data'   => $this->layout_blank_config('estimate', $title),
        ];

        $this->layout->view('/Sales/estimate_detail_view', $view_data);
    }

    # 수주서
    public function order()
    {
        $page = $this->input->get('page') ?? 1;
        $search_text = $this->input->get('search_text') ?? '';
        $start_date = $this->input->get('start_date') ?? '';
        $end_date = $this->input->get('end_date') ?? '';
        $excel_yn = $this->input->get('excel_yn') ?? 'N';

        $where = [
            "type = 'SELL'",    // SELL:판매, BUY:구매
            "sub_type = 'S'",   // G:견적서, S:수주서
        ];

        if (!empty($search_text)) {
            $search_condition = $this->service_model->build_document_search_condition($search_text);
            if ($search_condition !== '') {
                $where[] = $search_condition;
            }
        }

        if (!empty($start_date)) {

            $where[] = "a.estimate_date >= '{$start_date}'";
        }

        if (!empty($end_date)) {

            $where[] = "a.estimate_date <= '{$end_date}'";
        }

        $estimate_all = $this->service_model->get_estimate('all', $where);

        $view_data =  [
            'page'          => $page,
            'search_text'   => $search_text,
            'start_date'    => $start_date,
            'end_date'      => $end_date,
            'excel_yn'      => $excel_yn,
            'estimate_all'  => $estimate_all,
            'layout_data'   => $excel_yn == 'Y' ? $this->layout_none_config() : $this->layout_config('order', '수주서'),
        ];

        if ($excel_yn == 'Y') {

            $filename = '수주서' . '_' . date('Ymd_His') . '.xls';

            // * excel view
            header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
            header("Content-Disposition: attachment; filename=\"{$filename}\"");
            header("Content-Description: PHP Generated Data");
            header("Pragma: no-cache");
            header("Expires: 0");

            // 엑셀은 UTF-8 BOM 넣어주면 한글 깨짐 방지됨
            $this->layout->view('/Sales/order_excel_view', $view_data);
        } else {

            $this->layout->view('/Sales/order_view', $view_data);
        }
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
    # sales/download_estimate_excel?id=56
    public function download_estimate_excel()
    {
        $id = $this->input->get('id') ?? '';

        if (empty($id)) {
            show_404();
            return;
        }

        $estimate_row = $this->service_model->get_estimate('row', [
            "id = {$id}"
        ]);

        if (empty($estimate_row)) {
            show_404();
            return;
        }

        $sub_type = $estimate_row['sub_type'] ?? ''; // G:견적서, S:수주서
        $SUB_TYPE = unserialize(SUB_TYPE);
        $title = $SUB_TYPE[$sub_type] ?? '';

        if (empty($title)) {
            show_404();
            return;
        }

        $file_path = $_SERVER['DOCUMENT_ROOT'] . "/assets/app_hyup/excel/{$sub_type}_estimate_excel.xlsx";

        if (!file_exists($file_path)) {
            show_404();
            return;
        }

        $sheets = json_decode($estimate_row['sheets'], true);
        $items = $sheets[0]['data'] ?? [];

        $spreadsheet = IOFactory::load($file_path);
        $sheet = $spreadsheet->getSheet(0);

        $count = count($items);
        $insertAt = 15; // 15행부터 삽입
        $lastAt = $insertAt + $count - 1;

        // * C+D  merge

        // ✅ 열 너비 설정
        $sheet->getColumnDimension('F')->setWidth(10); // 규격
        $sheet->getColumnDimension('G')->setWidth(10); // 수량
        $sheet->getColumnDimension('H')->setWidth(15); // 단가
        $sheet->getColumnDimension('J')->setWidth(15); // 공급가액
        $sheet->getColumnDimension('L')->setWidth(15); // 세액
        $sheet->getColumnDimension('P')->setAutoSize(true); // 비고

        // ✅ 기존 행 아래로 밀기
        $sheet->insertNewRowBefore($insertAt, $count);

        $합계금액_INDEX = 13;
        $합계_INDEX = $합계금액_INDEX + 2; // * 15부터 시작
        $VAT_TYPE = unserialize(VAT_TYPE);

        $number_to_korean = number_to_korean($estimate_row['amount'] ?? 0);
        $number_amount = number_format($estimate_row['amount'] ?? 0);
        $vat_type = $VAT_TYPE[$estimate_row['vat_type']] ?? '';
        $sheet->setCellValue("C{$합계금액_INDEX}", "합  계  금  액 : {$number_to_korean} ￦ {$number_amount}원 ({$vat_type})");
        // $sheet->setCellValue("", $number_to_korean);

        foreach ($items as $index => $item) {
            // ✅ 새로 밀린 만큼 offset
            $row_num = $insertAt + $index;
            $tmp_index = $index + 1;

            if ($sub_type === 'G') {

                $sheet->setCellValue("C{$row_num}", $tmp_index);     // 순번
                $sheet->mergeCells("C{$row_num}:D{$row_num}"); // C+D 병합

                $sheet->setCellValue("E{$row_num}", $item[0]); //  도면번호/품명

                $sheet->setCellValue("F{$row_num}", $item[1]); // 소재

                $sheet->setCellValue("G{$row_num}", !empty($item[2]) ? number_format($item[2]) : ''); // 수량

                $sheet->setCellValue("H{$row_num}", $item[3]); // 단위
                $sheet->mergeCells("H{$row_num}:I{$row_num}"); // H+I 병합

                $sheet->setCellValue("J{$row_num}", !empty($item[4]) ? number_format($item[4]) : ''); // 단가
                $sheet->mergeCells("J{$row_num}:K{$row_num}"); // J+K 병합

                $sheet->setCellValue("L{$row_num}", !empty($item[5]) ? number_format($item[5]) : ''); // 금액
                $sheet->mergeCells("L{$row_num}:O{$row_num}"); // L+M+N+O 병합

                $sheet->setCellValue("P{$row_num}", $item[6]); // 비고
                $sheet->mergeCells("P{$row_num}:U{$row_num}"); // P 병합

            } else {

                $sheet->setCellValue("C{$row_num}", $tmp_index); // 순번
                $sheet->mergeCells("C{$row_num}:D{$row_num}"); // C+D 병합

                $sheet->setCellValue("E{$row_num}", $item[0]); // 품목

                $sheet->setCellValue("F{$row_num}", $item[1]); // 규격

                $sheet->setCellValue("G{$row_num}", $item[2]); // 수량

                $sheet->setCellValue("H{$row_num}", !empty($item[3]) ? number_format($item[3]) : ''); // 단가
                $sheet->mergeCells("H{$row_num}:I{$row_num}"); // H+I 병합

                $sheet->setCellValue("J{$row_num}", !empty($item[4]) ? number_format($item[4]) : ''); // 공급가액
                $sheet->mergeCells("J{$row_num}:K{$row_num}"); // J+K 병합

                $sheet->setCellValue("L{$row_num}", !empty($item[5]) ? number_format($item[5]) : ''); // 세액
                $sheet->mergeCells("L{$row_num}:O{$row_num}"); // L+M+N+O 병합

                $sheet->setCellValue("P{$row_num}", $item[6]); // 비고
                $sheet->mergeCells("P{$row_num}:U{$row_num}"); // P 병합
            }


            $합계_INDEX++;
        }

        $sheet->getColumnDimension('C')->setAutoSize(true); // 순번
        $sheet->getColumnDimension('E')->setWidth(80); // 품목

        // 1️⃣ 셀 스타일 적용
        $sheet->setCellValue("J{$합계_INDEX}", number_format($estimate_row['supply_amount'] ?? 0));
        $sheet->setCellValue("L{$합계_INDEX}", number_format($estimate_row['tax_amount']));

        // * 단가 오른쪽 정렬
        $sheet->getStyle("J{$합계_INDEX}:L{$합계_INDEX}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // * 순번 가운데 정렬
        $sheet->getStyle("D{$insertAt}:D{$lastAt}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // * 품목 왼쪽 정렬
        $sheet->getStyle("E{$insertAt}:E{$lastAt}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // * 규격 가운데 정렬
        $sheet->getStyle("F{$insertAt}:F{$lastAt}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // * 수량 오른쪽 정렬
        $sheet->getStyle("G{$insertAt}:G{$lastAt}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // * 단가 오른쪽 정렬
        $sheet->getStyle("H{$insertAt}:I{$lastAt}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // * 공급가액 오른쪽 정렬
        $sheet->getStyle("J{$insertAt}:K{$lastAt}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // * 세액 오른쪽 정렬
        $sheet->getStyle("L{$insertAt}:O{$lastAt}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // * 비고 왼쪽 정렬
        $sheet->getStyle("P{$insertAt}:U{$lastAt}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // 2️⃣ 셀 값 입력
        $sheet->setCellValue('C5', "No. : " . $estimate_row['no']);
        $sheet->setCellValue('C6', $estimate_row['partner_name'] . ' 귀하');
        $sheet->setCellValue(
            'C9',
            ($estimate_row['sub_type'] === 'G' ? '견적일자 : ' : '수주일자 : ')
                . $estimate_row['estimate_date']
        );

        // * ---------------- 배경색 다시주기 ----------------

        $sheet->getStyle('G6')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('H6')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('H8')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('H9')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('H11')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('H12')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('L8')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('L11')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('L12')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('C14')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('E14')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)

            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('F14')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('G14')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('H14')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('I14')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('J14')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('K14')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('L14')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        $sheet->getStyle('P14')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');

        // * ---------------- 배경색 다시주기 ----------------


        // 3️⃣ 한글 파일명 처리
        $filename = $title . '_' . date('Ymd_His') . '.xlsx';
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
        $id = $this->input->get('id') ?? '';

        if (empty($id)) {
            throw new Error("견적서 아이디가 올바르지 않습니다.");
        }

        $estimate_row = $this->service_model->get_estimate('row', [
            "id = {$id}"
        ]);

        if (empty($estimate_row)) {
            show_404();
            return;
        }

        $sub_type = $estimate_row['sub_type'] ?? ''; // G:견적서, S:수주서
        $SUB_TYPE = unserialize(SUB_TYPE);
        $title = $SUB_TYPE[$sub_type] ?? '';

        $sheets = json_decode($estimate_row['sheets'], true);

        /**
         * Array
(
    [0] => Array
        (
            [0] => black matt,bk0005 - 에이치비외 (품목)
            [1] =>   (규격)
            [2] => 1 (수량)
            [3] => 600000 (단가)
            [4] => 600000 (공급가액)
            [5] => 60000 (세액)
            [6] =>  (비고)
        )
         */
        $items = $sheets[0]['data'] ?? [];
        // $items = [];

        // ✅ 한글 깨짐 방지 폰트 설정
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'unbatang',
        ]);



        $total = array_sum(array_column($items, 4));
        $tax = array_sum(array_column($items, 5));
        $totalWithTax = $total + $tax;

        // ✅ HTML 구성
        $estimate_pdf_view = $this->load->view('Pdf/estimate_pdf_view', [
            'items'         => $items,
            'total'         => $total,
            'tax'           => $tax,
            'estimate'      => $estimate_row,
            'title'         => $title,
            'totalWithTax'  => $totalWithTax,
        ], true);

        //         $mpdf->SetHTMLHeader('
        //   <div class="firstpage-header" style="width:100%; text-align:left;">
        //     <img src="http://jmtech.net/theme/mv305/img/logo-color.png"
        //          style="width:150px; margin-top:27px;">
        //   </div>
        // ');

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
        $supply_amount = $this->input->post('supply_amount') ?? '';
        $tax_amount = $this->input->post('tax_amount') ?? '';
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
                    'supply_amount'     => $supply_amount,
                    'tax_amount'        => $tax_amount,
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

    # 견적서 상태 변경
    public function change_status()
    {
        $id = $this->input->post('id') ?? '';
        $status = $this->input->post('status') ?? '';
        $title = $this->input->post('title') ?? '견적서';

        $res_array = [
            'ok'                => true,
            'msg'               => "{$title} 상태가 변경되었습니다.",
            'su_estimate_id'    => '',
        ];

        try {

            $res = $this->estimate_service->change_status($id, $status);

            $res_array['su_estimate_id'] = $res;
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    # 수주서 상태 변경
    public function change_status2()
    {
        $id = $this->input->post('id') ?? '';
        $status2 = $this->input->post('status2') ?? '';
        $title = $this->input->post('title') ?? '수주서';

        $res_array = [
            'ok'                => true,
            'msg'               => "{$title} 상태가 변경되었습니다.",
            'su_estimate_id'    => '',
        ];

        try {

            $res = $this->estimate_service->change_status2($id, $status2);

            $res_array['su_estimate_id'] = $res;
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }

    # 견적서 삭제
    public function delete_estimate()
    {
        $id = $this->input->get('id') ?? '';

        if (empty($id)) {
            show_404();
            return;
        }

        if (is_array($id)) {
            foreach ($id as $estimate_id) {
                $this->estimate_service->delete($estimate_id);
            }
        } else {
            $this->estimate_service->delete($id);
        }

        echo json_encode([
            'ok'    => true,
            'msg'   => '견적서가 삭제되었습니다',
        ]);
    }

    # 비밀번호 변경
    public function change_password()
    {
        $pw = $this->input->post('pw') ?? '';
        $pw_confirm = $this->input->post('pw_confirm') ?? '';

        $res_array = [
            'ok'    => true,
            'msg'   => '비밀번호가 변경되었습니다.',
            'data'  => [],
        ];

        try {

            $this->user_service->changePassword($pw, $pw_confirm);
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
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

    private function layout_none_config($sub_menu_code = '', $title = '')
    {

        $this->layout->setLayout("layout/none");
        $this->layout->setTitle($title);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'sales',
            'sub_menu_code'    => $sub_menu_code,
        ];
    }
}
