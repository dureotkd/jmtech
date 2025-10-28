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
