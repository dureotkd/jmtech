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

    public function upload_excel12()
    {
        $users = [
            [
                'user_id' => 'jeonym1096',
                'name'    => '전용문',
                'password' => 'tech123',
            ],
            [
                'user_id' => 'parkjy1096',
                'name'    => '정석문',
                'password' => 'tech123',
            ],
        ];

        try {
            foreach ($users as $user) {
                $this->service_model->insert_user(DEBUG, [
                    'user_id'   => $user['user_id'],
                    'name'      => $user['name'],
                    'password'  => password_hash($user['password'], PASSWORD_BCRYPT),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            echo '✅ ' . count($users) . '명 회원 등록 완료';
        } catch (Exception $e) {
            echo '❌ 오류: ' . $e->getMessage();
        }
    }


    // * https://jmtech.test/api/test/upload_excel1 (EXCEL 업로드 테스트)
    public function upload_excel1()
    {
        $users = [
            [
                'user_id' => 'jeonym1096',
                'name'    => '전용문',
                'password' => 'tech123',
            ],
            [
                'user_id' => 'parkjy1096',
                'name'    => '정석문',
                'password' => 'tech123',
            ],
        ];

        try {
            foreach ($users as $user) {
                $this->service_model->insert_user(DEBUG, [
                    'user_id'   => $user['user_id'],
                    'name'      => $user['name'],
                    'password'  => password_hash($user['password'], PASSWORD_BCRYPT),
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
            echo '✅ ' . count($users) . '명 회원 등록 완료';
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

    // * https://jmtech.test/test/test/moneypin_test

    /**
     * 
     * Array
(
    [0] => 3128137108
    [1] => 1538102131
    [2] => 2178603569
    [3] => 3128612344
    [4] => 4988702579
    [5] => 8878703249
    [6] => 3128144856
    [7] => 3248800807
    [8] => 3128165865
    [9] => 6060560311
)
     * @return never
     */
    public function moneypin_test()
    {

        $this->load->library('moneypin');

        $sales_monthly_summary = $this->service_model->get_sales_monthly_summary('all', [
            "sales > 0",
        ]);
        $company_nums = [];

        foreach ($sales_monthly_summary as $summary) {
            $company_num = $summary['company_num'];
            $company_num = str_replace('-', '', $company_num);

            if (empty($company_num)) continue;

            $company_nums[] = $company_num;
        }

        printr($company_nums);
        exit;

        $response = $this->moneypin->searchCompany($company_nums);

        foreach ($response as $res) {

            $info = $res['info'];

            $bizNo = $info['bizNo'];
            $bizName = $info['bizName'];
            $ceoName = $info['ceoName'];
            $zipcode = $info['zipCode'];
            $address = $info['address'];
            $bizType = $info['bizType'];
            $bizStatus = $info['bizStatus'];
            $taxTypeCode = $info['taxTypeCode'];
            $taxType = $info['taxType'];
            $openingDate = $info['openingDate'];
            $bizSectorName = $info['bizSectorName'];
            $bizCategoryName = $info['bizCategoryName'];
            $bizCategoryCode = $info['bizCategoryCode'];
            $phoneNumber = $info['phoneNumber'];
            $taxOfficeCode = $info['taxOfficeCode'];
            $taxOfficeName = $info['taxOfficeName'];
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
                'zipcode' => $zipcode,
                'tax_type_code' => $taxTypeCode,
                'opening_date' => $openingDate,
                'biz_type' => $bizType,
                'biz_sector_name' => $bizSectorName,
                'biz_category_name' => $bizCategoryName,
                'biz_category_code' => $bizCategoryCode,
                'phone_number' => $phoneNumber,
                'tax_office_code' => $taxOfficeCode,
                'tax_office_name' => $taxOfficeName,
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
