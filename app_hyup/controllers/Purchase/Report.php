<?php

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class report extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "phpspreadsheet",
            "/Service/user_service",
            "/Service/estimate_service",
            "/Service/purchase_service",
            "file",
        ]);

        $this->load->model('/Page/service_model');
    }

    // * 매입(거래명세표) 페이지
    public function index()
    {
        $page = $this->input->get('page') ?? 1;
        $search_text = $this->input->get('search_text') ?? '';
        $start_date = $this->input->get('start_date') ?? '';
        $end_date = $this->input->get('end_date') ?? '';

        $where = [
            "type = 'BUY'",    // SELL:판매, BUY:구매
            "sub_type = 'MI'",   // MI:매입,MC:매출
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

        $transcation_statement_all = $this->service_model->get_transcation_statement('all', $where);
        $title = '매입(거래명세표)';

        $barobill_tax_invoice_all = $this->service_model->get_barobill_tax_invoice('all', [
            "type = 'purchase'",
        ]);

        $view_data =  [
            'transcation_statement_all' => $transcation_statement_all,
            'page'                  => $page,
            'search_text'           => $search_text,
            'start_date'            => $start_date,
            'end_date'              => $end_date,
            'title'                 => $title,
            'layout_data'           => $this->layout_config('report', $title),
        ];

        $this->layout->view('/Purchase/report_view', $view_data);
    }

    # 명세표 상세
    public function statement_detail()
    {
        $id = $this->input->get('id') ?? '';

        if (empty($id)) {
            show_404();
            return;
        }

        $statement = $this->service_model->get_transcation_statement('row', [
            "id = {$id}"
        ]);

        if (empty($statement)) {
            show_404();
            return;
        }

        $table_theme = '';
        $text_theme = '';

        $reverse_table_theme = '';
        $reverse_text_theme = '';


        if ($statement['sub_type'] == 'MI') {
            $table_theme = 'blue-table';
            $text_theme = 'blue-text';

            $reverse_table_theme = 'red-table';
            $reverse_text_theme = 'red-text';
        } elseif ($statement['sub_type'] == 'MC') {
            $table_theme = 'red-table';
            $text_theme = 'red-text';

            $reverse_table_theme = 'blue-table';
            $reverse_text_theme = 'blue-text';
        }

        $files = $this->service_model->get_file('all', [
            "ref_table = 'statement'",
            "ref_id = {$id}"
        ]);

        $sheets_json = json_decode($statement['sheets'], true);
        $sheets = $sheets_json[0]['data'] ?? [];

        $event_logs = $this->service_model->get_admin_event_log('all', [
            "target_table = 'statement'",
            "target_id = {$id}"
        ]);

        $view_data =  [
            'id'            => $id,
            'statement'     => $statement,
            'sheets'        => $sheets,
            'files'         => $files,

            'table_theme'   => $table_theme,
            'text_theme'    => $text_theme,
            'reverse_table_theme' => $reverse_table_theme,
            'reverse_text_theme'  => $reverse_text_theme,
            'event_logs'    => $event_logs,
            'layout_data'   => $this->layout_blank_config('statement', '거래명세서'),
        ];

        $this->layout->view('/Purchase/statement_detail_view', $view_data);
    }

    # 명세표 삭제
    public function delete_statement()
    {
        $id = $this->input->get('id') ?? '';

        if (empty($id)) {
            show_404();
            return;
        }

        if (is_array($id)) {
            foreach ($id as $statement_id) {
                $this->purchase_service->delete($statement_id);
            }
        } else {
            $this->purchase_service->delete($id);

            alert_close('명세표가 삭제되었습니다');
        }

        echo json_encode([
            'ok'    => true,
            'msg'   => '명세표가 삭제되었습니다',
        ]);
    }

    # 명세표 PDF 다운로드
    public function download_statement_pdf()
    {

        $id = $this->input->get('id') ?? ''; // * statement id
        $type = $this->input->get('type') ?? ''; // * reverse type ()

        if (empty($id)) {
            throw new Error("명세서 아이디가 올바르지 않습니다.");
        }

        $statement = $this->service_model->get_transcation_statement('row', [
            "id = {$id}"
        ]);

        if (empty($statement)) {
            show_404();
            return;
        }

        $sub_type   = $statement['sub_type'] ?? ''; // MI, MC
        $SUB_TYPE   = unserialize(SUB_TYPE);
        $title      = $SUB_TYPE[$sub_type] ?? '';

        $sheets     = json_decode($statement['sheets'], true);
        $items      = $sheets[0]['data'] ?? [];

        // ✅ 한글 깨짐 방지 폰트 설정
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'unbatang',
        ]);

        $table_theme = '';
        $text_theme = '';

        $reverse_table_theme = '';
        $reverse_text_theme = '';


        if ($statement['sub_type'] == 'MI') {
            $table_theme = 'blue-table';
            $text_theme = 'blue-text';

            $reverse_table_theme = 'red-table';
            $reverse_text_theme = 'red-text';
        } elseif ($statement['sub_type'] == 'MC') {
            $table_theme = 'red-table';
            $text_theme = 'red-text';

            $reverse_table_theme = 'blue-table';
            $reverse_text_theme = 'blue-text';
        }

        if ($type == 'reverse') {
            // * 공급자
            $table_theme = $reverse_table_theme;
            $text_theme = $reverse_text_theme;
        }

        $total = array_sum(array_column($items, 4));
        $tax = array_sum(array_column($items, 5));
        $totalWithTax = $total + $tax;

        // ✅ HTML 구성
        $statement_pdf_view = $this->load->view('Pdf/statement_pdf_view', [
            'items'         => $items,
            'total'         => $total,
            'tax'           => $tax,
            'statement'     => $statement,
            'title'         => $title,
            'totalWithTax'  => $totalWithTax,
            'title'        => $title,
            'table_theme'   => $table_theme,
            'text_theme'    => $text_theme,
        ], true);

        $mpdf->WriteHTML($statement_pdf_view);
        $mpdf->Output('명세서.pdf', 'I'); // D: 다운로드, I: 브라우저보기
    }

    private function layout_blank_config($sub_menu_code = '', $title = '')
    {

        $this->layout->setPopHeader($title);
        $this->layout->setLayout("layout/blank");
        $this->layout->setTitle($title);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'purchase',
            'sub_menu_code'    => $sub_menu_code,
        ];
    }

    private function layout_config($sub_menu_code = '', $title = '')
    {

        $this->layout->setLayout("layout/template");
        $this->layout->setTitle($title);
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'purchase',
            'sub_menu_code'    => $sub_menu_code,
        ];
    }
}
