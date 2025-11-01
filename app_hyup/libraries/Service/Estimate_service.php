<?php
class Estimate_service
{
    protected $obj;
    protected $loginUser = false;

    public function __construct()
    {
        $this->obj = &get_instance();

        $this->obj->load->library([
            "ajax",
            "file"
        ]);

        $this->obj->load->model("/Page/service_model");
    }

    /**
     * ^ ---------------- 견적서 저장 프로세스 ----------------
     * * 1. 견적서 기본 정보 저장
     */
    public function create($payloads)
    {

        $partner_id = $payloads['partner_id'];
        $estimate_date = $payloads['estimate_date'];
        $fax_number = $payloads['fax_number'];
        $phone_number = $payloads['phone_number'];
        $title = $payloads['title'];
        $due_at = $payloads['due_at'];
        $location = $payloads['location'];
        $valid_at = $payloads['valid_at'];
        $payment_type = $payloads['payment_type'];
        $etc_memo = $payloads['etc_memo'];
        $no = $this->makeUniqueNo();

        if (empty($partner_id)) {
            throw new Exception("거래처명을 선택해주세요.");
        }
        if (empty($estimate_date)) {
            throw new Exception("견적일자를 입력해주세요.");
        }
        if (empty($phone_number)) {
            throw new Exception("전화번호를 입력해주세요.");
        }
        if (empty($title)) {
            throw new Exception("제목을 입력해주세요.");
        }

        $res = $this->obj->service_model->insert_estimate(DEBUG, [
            'type'          => '견적서',
            'no'            => $no,
            'partner_id'    => $partner_id,
            'estimate_date' => $estimate_date,
            'fax_number'    => $fax_number,
            'title'         => $title,
            'location'      => $location,
            'due_at'        => $due_at,
            'valid_at'      => $valid_at,
            'payment_type'  => $payment_type,
            'etc_memo'      => $etc_memo,
        ]);

        return $res;
    }

    public function uploadFile($estimate_id)
    {

        try {

            // 견적서 저장 로직 구현 (예: DB에 저장)
            $uploadDir = '/assets/app_hyup/uploads/estimate/';
            $file_upload_res = $this->file->upload_multiple('files', $uploadDir);

            $ref_table = 'estimate';

            if (!empty($file_upload_res)) {
                foreach ($file_upload_res as $file_res) {

                    if ($file_res['status'] === 'success') {

                        $this->service_model->insert_file(DEBUG, [
                            'ref_table'     => $ref_table,
                            'ref_id'        => $estimate_id,
                            'file_name'     => $file_res['originalFileName'],
                            'file_path'     => $file_res['filePath'],
                            'file_size'     => $file_res['fileSize'],
                            'file_url'      => $file_res['fileSrc'],
                            'created_at'    => date('Y-m-d H:i:s'),
                            'updated_at'    => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
            }
        } catch (Exception $e) {

            throw new Error("파일 업로드 중 오류가 발생했습니다: " . $e->getMessage());
        }
    }

    private function makeUniqueNo()
    {

        $datePart = date('Ymd');
        $randomPart = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

        return $datePart . '-' . $randomPart;
    }
}
