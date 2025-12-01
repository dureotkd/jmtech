<?php

/**
 * /api/auth/callback/naver
 */
class test extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library([
            'layout',
            'barobill',
            'moneypin'
        ]);

        $this->load->model('/Page/service_model');
    }

    public function index()
    {
        $view_data =  [

            'layout_data'           => $this->layout_config(),

        ];

        $this->layout->view('/Api/Auth/Callback/naver_view', $view_data);
    }

    // * https://jmtech.test/api/test/upload_excel1 (EXCEL 업로드 테스트)
    public function upload_excel1()
    {

        // * Phpspreadsheet 라이브러리 로드
        $this->load->library('phpspreadsheet');

        echo 'zz';
        exit;


        // * C드라이브 파일 경로
        $filePath = 'C:/tttt.xlsx';  // 또는 Windows 서버라면 '\\' 대신 '/' 사용

        try {
            $spreadsheet = $this->phpspreadsheet->loadExcelFile($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            foreach ($sheetData as $key => $row) {
                if ($key === 1) {
                    // 헤더 행 건너뛰기
                    continue;
                }

                $name = $row['A']; // A열
                $user_id = $row['B']; // B열
                $phone = $row['D']; // D열
                $team = $row['E']; // E열
                $employment_type = $row['F']; // F열
                $auth_type = $row['H']; // H열

                $this->service_model->insert_user(DEBUG, [
                    'user_id' => $user_id,
                    'name' => $name,
                    'password' => password_hash('123', PASSWORD_BCRYPT),
                    'phone' => $phone,
                    'team' => $team,
                    'employment_type' => $employment_type,
                    'auth_type' => $auth_type,
                ]);
            }
        } catch (Exception $e) {
            echo '❌ 오류: ' . $e->getMessage();
        }
    }

    // * https://jmtech.test/api/test/upload_excel2 (EXCEL 업로드 테스트)
    public function upload_excel2()
    {

        // * Phpspreadsheet 라이브러리 로드
        $this->load->library('phpspreadsheet');

        // * C드라이브 파일 경로
        $filePath = 'C:/item.xls';  // 또는 Windows 서버라면 '\\' 대신 '/' 사용

        try {
            $spreadsheet = $this->phpspreadsheet->loadExcelFile($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            /**
             * 
    [26] => Array
        (
            [A] => 0000000023
            [B] => sts 304 양P/S(레) 판 1.5t*1219*2438
            [C] => 
            [D] => 156,150
            [E] => 0
            [F] => 
        )

             */
            foreach ($sheetData as $key => $row) {
                if ($key < 3) {
                    // 헤더 행 건너뛰기
                    continue;
                }

                $품목코드 = $row['A']; // A열
                $품목명 = $row['B']; // B열
                $단위 = $row['C']; // C열
                $구매가 = $row['D']; // D열
                $판매가 = $row['E']; // E열
                $기타사항 = $row['F']; // F열

                $this->service_model->insert_item(DEBUG, [
                    'item_code' => $품목코드,
                    'item_name' => $품목명,
                    'unit' => $단위,
                    'purchase_price' => str_replace(',', '', $구매가),
                    'sales_price' => str_replace(',', '', $판매가),
                    'memo' => $기타사항,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        } catch (Exception $e) {
            echo '❌ 오류: ' . $e->getMessage();
        }
    }

    // * https://jmtech.test/test/test/upload_excel3 (EXCEL 업로드 테스트)
    public function upload_excel3()
    {

        // * Phpspreadsheet 라이브러리 로드
        $this->load->library('phpspreadsheet');

        // * C드라이브 파일 경로
        $filePath = 'C:/ttttttttt.xls';  // 또는 Windows 서버라면 '\\' 대신 '/' 사용

        try {
            $spreadsheet = $this->phpspreadsheet->loadExcelFile($filePath);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $company_nums = [];
            /**
             * 
    [26] => Array
        (
            [A] => 0000000023
            [B] => sts 304 양P/S(레) 판 1.5t*1219*2438
            [C] => 
            [D] => 156,150
            [E] => 0
            [F] => 
        )

             */
            foreach ($sheetData as $key => $row) {
                if ($key < 3) {
                    // 헤더 행 건너뛰기
                    continue;
                }

                $사업자번호 = $row['B'];

                if (empty($사업자번호)) {
                    continue;
                }

                $사업자번호 = str_replace('-', '', $사업자번호);

                if (strlen($사업자번호) != 10) {
                    continue;
                }

                $company_nums[] = $사업자번호;
            }

            $response = $this->moneypin->searchCompany(['3128624947']);

            printr($response);
            exit;


            // * company_nums 10개씩 배열로 나누기

            /**
             *             [0] => 3128209915
            [1] => 3128624947
            [2] => 1278651448
            [3] => 1238161895
            [4] => 3128137073
            [5] => 3128158684
            [6] => 1198108663
            [7] => 5048124750
            [8] => 3128139751
            [9] => 1238625837
             */
            $company_nums = array_chunk($company_nums, 10);

            foreach ($company_nums as $index => $company_num_chunk) {

                echo '==== ' . ($index) . '번째 호출 ==== <br>';

                $response = $this->moneypin->searchCompany($company_num_chunk);

                $test_tmp = 0;

                foreach ($response as $res) {

                    $test_tmp++;

                    echo '----' . $test_tmp . '----<br>';

                    $info = $res['info'];

                    $bizNo = $info['bizNo'];
                    $bizName = $info['bizName'];
                    $ceoName = $info['ceoName'];
                    $address = $info['address'];
                    $bizStatus = $info['bizStatus'];
                    $taxType = $info['taxType'];
                    $simplifiedTaxTypeDate = $info['simplifiedTaxTypeDate'];
                    $closingDate = $info['closingDate'];

                    $this->service_model->insert_moneypin_biz_info(DEBUG, [
                        'biz_no' => $bizNo,
                        'biz_name' => $bizName,
                        'ceo_name' => $ceoName,
                        'address' => $address,
                        'biz_status' => $bizStatus,
                        'tax_type' => $taxType,
                        'simplified_tax_type_date' => $simplifiedTaxTypeDate,
                        'closing_date' => $closingDate,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }

                sleep(1); // * 1초 대기

            }
        } catch (Exception $e) {
            echo '❌ 오류: ' . $e->getMessage();
        }
    }

    public function mig()
    {

        $moneypin_biz_info_all = $this->service_model->get_moneypin_biz_info('all', [
            1
        ]);

        foreach ($moneypin_biz_info_all as $biz_info) {

            $biz_no = $biz_info['biz_no'];

            $this->service_model->update_business_partner(DEBUG, [
                'address' => $biz_info['address'],
                'biz_status' => $biz_info['biz_status'],
                'closing_date' => $biz_info['closing_date'],
            ], [
                "REPLACE(company_num, '-', '') = '{$biz_no}'"
            ]);
        }
    }

    // * https://jmtech.test/test/test/barobill_test
    public function barobill_test()
    {

        // $this->barobill->매출세금계산서조회();
        $this->barobill->매입세금계산서일별조회();
    }

    // * 매출처현황 업로드 https://jmtech.test/test/test/sales_excel_upload
    public function sales_excel_upload()
    {

        $file_path = 'C:\1.xls';

        try {
            // 엑셀 파일 읽기
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();

            echo "총 {$highestRow}개 행 발견<br>";

            // 2행부터 시작 (1행은 헤더)
            for ($row = 2; $row <= $highestRow; $row++) {

                if ($row === 2) {
                    continue;
                }

                $partner_name = $sheet->getCell("A{$row}")->getValue();
                $registration_number = $sheet->getCell("B{$row}")->getValue();
                $unit = $sheet->getCell("C{$row}")->getValue();

                // 빈 행 스킵
                if (empty($partner_name)) {
                    continue;
                }

                // 월별 매출 데이터 (D~O 컬럼 = 1~12월)
                $sales_data = [];
                $columns = ['D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'];

                for ($i = 0; $i < 12; $i++) {
                    $month = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                    $value = $sheet->getCell("{$columns[$i]}{$row}")->getValue();
                    $sales_data["sales_{$month}"] = !empty($value) ? floatval(str_replace(',', '', $value)) : 0;
                }

                // 최근거래일자 (P 컬럼)
                $last_transaction_date = $sheet->getCell("P{$row}")->getValue();

                // DB에 저장
                $insert_data = array_merge([
                    'partner_name' => $partner_name,
                    'company_num' => $registration_number,
                    'sales' => $unit,
                ], $sales_data, [
                    'desc' => $last_transaction_date,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);

                $this->service_model->insert_sales_monthly_summary(DEBUG, $insert_data);
            }

            echo "<br>✅ 업로드 완료!";
        } catch (Exception $e) {
            echo "❌ 오류: " . $e->getMessage();
        }
    }

    // * https://jmtech.test/test/test/moneypin_test
    public function moneypin_test()
    {

        $this->load->library('moneypin');

        $company_all = $this->service_model->get_business_partner('all', [
            "company_num IN ('312-09-86753','312-86-00310')"
        ]);

        $company_nums = [];

        foreach ($company_all as $company) {
            $company_nums[] = str_replace('-', '', $company['company_num']);
        }

        $response = $this->moneypin->searchCompany($company_nums);

        foreach ($response as $res) {

            $info = $res['info'];

            $bizNo = $info['bizNo'];
            $bizName = $info['bizName'];
            $ceoName = $info['ceoName'];
            $address = $info['address'];
            $bizStatus = $info['bizStatus'];
            $taxType = $info['taxType'];
            $simplifiedTaxTypeDate = $info['simplifiedTaxTypeDate'];
            $closingDate = $info['closingDate'];

            $this->service_model->insert_moneypin_biz_info(DEBUG, [
                'biz_no' => $bizNo,
                'biz_name' => $bizName,
                'ceo_name' => $ceoName,
                'address' => $address,
                'biz_status' => $bizStatus,
                'tax_type' => $taxType,
                'simplified_tax_type_date' => $simplifiedTaxTypeDate,
                'closing_date' => $closingDate,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    private function layout_config()
    {

        $this->layout->setLayout("layout/blank");
        $this->layout->setCss([]);
        $this->layout->setScript([]);

        return [
            'top_menu_code'    => 'base',
            'sub_menu_code'    => 'banner',
        ];
    }
}
