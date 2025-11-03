<?php

/**
 * /api/auth/callback/naver
 */
class test extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library("layout");

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
