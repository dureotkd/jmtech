<?php

use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class order extends MY_Controller
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

    // * 발주서 페이지
    public function index()
    {
        $page = $this->input->get('page') ?? 1;
        $search_text = $this->input->get('search_text') ?? '';
        $start_date = $this->input->get('start_date') ?? '';
        $end_date = $this->input->get('end_date') ?? '';

        $where = [
            "type = 'SELL'",    // SELL:판매, BUY:구매
            "sub_type = 'G'",   // G:견적서, S:수주서
        ];

        if (!empty($search_text)) {

            $where[] = "(SELECT COUNT(*) FROM jmtech.business_partner bp WHERE bp.id = a.partner_id AND bp.company_name LIKE '%{$search_text}%') > 0";
        }

        if (!empty($start_date)) {

            $where[] = "a.estimate_date >= '{$start_date}'";
        }

        if (!empty($end_date)) {

            $where[] = "a.estimate_date <= '{$end_date}'";
        }


        $purchase_all = $this->service_model->get_purchase('all', $where);
        $title = '발주서';

        $view_data =  [
            'page'                 => $page,
            'search_text'          => $search_text,
            'start_date'           => $start_date,
            'end_date'             => $end_date,
            'title'                => $title,
            'layout_data'           => $this->layout_config('purchase', $title),
            'purchase_all'          => $purchase_all,
        ];

        $this->layout->view('/Purchase/order_view', $view_data);
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
