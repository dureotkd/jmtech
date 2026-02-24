<?php

class sales_document extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            "layout",
            "hometax",
            "/Service/user_service"
        ]);

        $this->load->model('/Page/service_model');
    }

    public function index() {}

    # 매출(거래명세표)
    public function report()
    {

        $view_data =  [
            'layout_data'           => $this->layout_config('report'),
        ];

        $this->layout->view('/Sales/report_view', $view_data);
    }

    # 홈택스 자료수집
    public function collect_hometax_sales_tax_invoice()
    {

        $res_array = [
            'ok'    => true,
            'msg'   => '홈택스 자료수집이 완료되었습니다.',
            'data'  => [],
        ];

        // 전날
        $start_date = date('Y-m-d', strtotime('-7 day'));
        $end_date = date('Y-m-d');

        try {

            $this->hometax->전체자료수집($start_date, $end_date);
        } catch (Exception $e) {
            $res_array['ok'] = false;
            $res_array['msg'] = $e->getMessage();
        }

        echo json_encode($res_array);
    }


    # 매출세금계산서(현영/기타)
    public function tax_bill()
    {
        $page = $this->input->get('page') ?? 1;
        $search_text = $this->input->get('search_text') ?? '';
        $start_date = $this->input->get('start_date') ?? '';
        $end_date = $this->input->get('end_date') ?? '';

        $where = [
            1
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

        $title = '매출세금계산서(현영/기타)';

        /**
         *     [0] => Array
        (
            [id] => 1
            [NTSSendKey] => 2025110441000026erp3i8sb
            [NTSSendDT] => 20251104000000
            [IssueDT] => 20251104123009
            [WriteDate] => 20251104
            [ModifyCode] => 1
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
            [AmountTotal] => -44500
            [TaxTotal] => 0
            [TotalAmount] => -44500
            [Cash] => 0
            [ChkBill] => 0
            [Note] => 0
            [Credit] => 0
            [Remark1] => 기업은행 489-023136-01-017당초 작성일자(2025-11-04)
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
        $barobill_tax_invoice = $this->service_model->get_barobill_tax_invoice('all', [
            "type = 'sales'"
        ]);

        $view_data =  [
            'title' => $title,
            'page' => $page,
            'search_text' => $search_text,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'barobill_tax_invoice' => $barobill_tax_invoice,
            'layout_data'           => $this->layout_config('tax_bill', $title),
        ];

        $this->layout->view('/Sales/tax_bill_view', $view_data);
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
}
