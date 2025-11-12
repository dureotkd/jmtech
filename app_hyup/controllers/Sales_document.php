<?php

class sales_document extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");
        $this->load->library("/Service/user_service");

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

        /**
         *             [id] => 209
            [NTSSendKey] => 202510311025103123392057
            [NTSSendDT] => 20251031000000
            [IssueDT] => 20251031163406
            [WriteDate] => 20251031
            [ModifyCode] => 0
            [TaxType] => 1
            [PurposeType] => 2
            [InvoicerCorpNum] => 6315700716
            [InvoicerTaxRegID] => 
            [InvoicerCorpName] => 테크로지스
            [InvoicerCEOName] => 이경열
            [InvoicerAddr] => 충청북도 청주시 서원구 모충로26번길 60, 상가동 103호(개신동, 두산한솔2차아파트)
            [InvoicerBizType] => 운수업
            [InvoicerBizClass] => 화물운송주선
            [InvoicerContactName] => 
            [InvoicerEmail] => lky5091@naver.com
            [InvoiceeCorpNum] => 3128630100
            [InvoiceeTaxRegID] => 
            [InvoiceeCorpName] => 제이엠테크주식회사
            [InvoiceeCEOName] => 전용문
            [InvoiceeAddr] => 충청남도 천안시 서북구 두정공단1길 149-2(두정동)
            [InvoiceeBizType] => 제조업
            [InvoiceeBizClass] => 산업기계,기타부수제작
            [InvoiceeContactName] => 
            [InvoiceeEmail] => jmlaser@empas.com
            [BrokerCorpNum] => 
            [BrokerTaxRegID] => 
            [BrokerCorpName] => 
            [BrokerCEOName] => 
            [BrokerAddr] => 
            [BrokerBizType] => 
            [BrokerBizClass] => 
            [BrokerContactName] => 
            [BrokerEmail] => 
            [AmountTotal] => 440000
            [TaxTotal] => 44000
            [TotalAmount] => 484000
            [Cash] => 0
            [ChkBill] => 0
            [Note] => 0
            [Credit] => 0
            [Remark1] => 하나은행 413-910019-14508 이경열(테크로지스
            [Remark2] => 
            [Remark3] => 
            [ItemName] => 운반비
            [CorpNum] => 6315700716
            [TaxRegID] => 
            [CorpName] => 테크로지스
            [CEOName] => 이경열
            [created_at] => 2025-11-12 12:11:04
            [updated_at] => 2025-11-12 12:11:04
         */
        $title = '매출세금계산서(현영/기타)';
        $barobill_tax_invoice = $this->service_model->get_barobill_tax_invoice('all', [1]);

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
