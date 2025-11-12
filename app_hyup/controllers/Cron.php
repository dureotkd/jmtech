<?php

class cron extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            'barobill'
        ]);

        $this->load->model('/Page/service_model');
    }

    /**
     * & 
     * ^ ----------- 홈택스 API (새벽 1시) -----------
     * * 매입/매출 내역 크롤링
     * * barobill_tax_invoice 테이블에 저장
     */
    public function collection_tax_invoice()
    {
        $start_date = '20250501';
        $end_date = date('Ymd');

        $res = $this->barobill->매입세금계산서기간조회($start_date, $end_date);

        if (!empty($res)) {

            foreach ($res as $row) {

                // 📦 국세청 관련
                $NTSSendKey = $row->NTSSendKey;
                $NTSSendDT = $row->NTSSendDT;
                $IssueDT = $row->IssueDT;
                $WriteDate = $row->WriteDate;
                $ModifyCode = $row->ModifyCode;
                $TaxType = $row->TaxType;
                $PurposeType = $row->PurposeType;

                // 🏢 공급자 (발행자)
                $InvoicerCorpNum = $row->InvoicerCorpNum;
                $InvoicerTaxRegID = $row->InvoicerTaxRegID;
                $InvoicerCorpName = $row->InvoicerCorpName;
                $InvoicerCEOName = $row->InvoicerCEOName;
                $InvoicerAddr = $row->InvoicerAddr;
                $InvoicerBizType = $row->InvoicerBizType;
                $InvoicerBizClass = $row->InvoicerBizClass;
                $InvoicerContactName = $row->InvoicerContactName;
                $InvoicerEmail = $row->InvoicerEmail;

                // 🧾 공급받는자 (매입자)
                $InvoiceeCorpNum = $row->InvoiceeCorpNum;
                $InvoiceeTaxRegID = $row->InvoiceeTaxRegID;
                $InvoiceeCorpName = $row->InvoiceeCorpName;
                $InvoiceeCEOName = $row->InvoiceeCEOName;
                $InvoiceeAddr = $row->InvoiceeAddr;
                $InvoiceeBizType = $row->InvoiceeBizType;
                $InvoiceeBizClass = $row->InvoiceeBizClass;
                $InvoiceeContactName = $row->InvoiceeContactName;
                $InvoiceeEmail = $row->InvoiceeEmail;

                // 🤝 수탁자
                $BrokerCorpNum = $row->BrokerCorpNum;
                $BrokerTaxRegID = $row->BrokerTaxRegID;
                $BrokerCorpName = $row->BrokerCorpName;
                $BrokerCEOName = $row->BrokerCEOName;
                $BrokerAddr = $row->BrokerAddr;
                $BrokerBizType = $row->BrokerBizType;
                $BrokerBizClass = $row->BrokerBizClass;
                $BrokerContactName = $row->BrokerContactName;
                $BrokerEmail = $row->BrokerEmail;

                // 💰 금액 관련
                $AmountTotal = $row->AmountTotal;
                $TaxTotal = $row->TaxTotal;
                $TotalAmount = $row->TotalAmount;
                $Cash = $row->Cash;
                $ChkBill = $row->ChkBill;
                $Note = $row->Note;
                $Credit = $row->Credit;

                // 🗒 비고 및 품목
                $Remark1 = $row->Remark1;
                $Remark2 = $row->Remark2;
                $Remark3 = $row->Remark3;
                $ItemName = $row->ItemName;

                // 🧩 거래처 정보
                $CorpNum = $row->CorpNum;
                $TaxRegID = $row->TaxRegID;
                $CorpName = $row->CorpName;
                $CEOName = $row->CEOName;

                $this->service_model->insert_barobill_tax_invoice(DEBUG, [
                    'NTSSendKey' => $NTSSendKey,
                    'NTSSendDT' => $NTSSendDT,
                    'IssueDT' => $IssueDT,
                    'WriteDate' => $WriteDate,
                    'ModifyCode' => $ModifyCode,
                    'TaxType' => $TaxType,
                    'PurposeType' => $PurposeType,
                    'InvoicerCorpNum' => $InvoicerCorpNum,
                    'InvoicerTaxRegID' => $InvoicerTaxRegID,
                    'InvoicerCorpName' => $InvoicerCorpName,
                    'InvoicerCEOName' => $InvoicerCEOName,
                    'InvoicerAddr' => $InvoicerAddr,
                    'InvoicerBizType' => $InvoicerBizType,
                    'InvoicerBizClass' => $InvoicerBizClass,
                    'InvoicerContactName' => $InvoicerContactName,
                    'InvoicerEmail' => $InvoicerEmail,
                    'InvoiceeCorpNum' => $InvoiceeCorpNum,
                    'InvoiceeTaxRegID' => $InvoiceeTaxRegID,
                    'InvoiceeCorpName' => $InvoiceeCorpName,
                    'InvoiceeCEOName' => $InvoiceeCEOName,
                    'InvoiceeAddr' => $InvoiceeAddr,
                    'InvoiceeBizType' => $InvoiceeBizType,
                    'InvoiceeBizClass' => $InvoiceeBizClass,
                    'InvoiceeContactName' => $InvoiceeContactName,
                    'InvoiceeEmail' => $InvoiceeEmail,
                    'BrokerCorpNum' => $BrokerCorpNum,
                    'BrokerTaxRegID' => $BrokerTaxRegID,
                    'BrokerCorpName' => $BrokerCorpName,
                    'BrokerCEOName' => $BrokerCEOName,
                    'BrokerAddr' => $BrokerAddr,
                    'BrokerBizType' => $BrokerBizType,
                    'BrokerBizClass' => $BrokerBizClass,
                    'BrokerContactName' => $BrokerContactName,
                    'BrokerEmail' => $BrokerEmail,
                    'AmountTotal' => $AmountTotal,
                    'TaxTotal' => $TaxTotal,
                    'TotalAmount' => $TotalAmount,
                    'Cash' => $Cash,
                    'ChkBill' => $ChkBill,
                    'Note' => $Note,
                    'Credit' => $Credit,
                    'Remark1' => $Remark1,
                    'Remark2' => $Remark2,
                    'Remark3' => $Remark3,
                    'ItemName' => $ItemName,
                    'CorpNum' => $CorpNum,
                    'TaxRegID' => $TaxRegID,
                    'CorpName' => $CorpName,
                    'CEOName' => $CEOName,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }
}
