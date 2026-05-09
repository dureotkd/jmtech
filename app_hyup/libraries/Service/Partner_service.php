<?php
class Partner_service
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

    public function create($payloads = [])
    {

        $type = $payloads['type'] ?? '';
        $company_name = $payloads['company_name'] ?? '';
        $company_num = $payloads['company_num'] ?? '';
        $ceo_name = $payloads['ceo_name'] ?? '';
        $phone_number = $payloads['phone_number'] ?? '';
        $fax_number = $payloads['fax_number'] ?? '';
        $address = $payloads['address'] ?? '';
        $business_type = $payloads['business_type'] ?? '';
        $memo = $payloads['memo'] ?? '';
        $bank_code = $payloads['bank_code'] ?? '';
        $zipcode = $payloads['zipcode'] ?? '';

        // 배열 값 (manager 관련)
        $manager_name = $payloads['manager_name'] ?? [];
        $manager_phone = $payloads['manager_phone'] ?? [];
        $manager_email = $payloads['manager_email'] ?? [];
        $manager_note = $payloads['manager_note'] ?? [];

        if (empty($type)) {
            throw new Exception("구분을 선택해주세요.");
        }

        if (empty($company_name)) {
            throw new Exception("거래처명을 입력해주세요.");
        }

        if (empty($company_num)) {
            throw new Exception("사업자등록번호를 입력해주세요.");
        }

        $res = $this->obj->service_model->insert_business_partner(DEBUG, [
            'type'          => $type,
            'company_name'  => $company_name,
            'company_num'   => $company_num,
            'ceo_name'      => $ceo_name,
            'phone_number'  => $phone_number,
            'fax_number'    => $fax_number,
            'address'       => $address,
            'zipcode'       => $zipcode,
            'business_type' => $business_type,
            'memo'          => $memo,
            'bank_code'     => $bank_code,

            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),

            'manager_user_json' => json_encode([
                'manager_name'  => $manager_name,
                'manager_phone' => $manager_phone,
                'manager_email' => $manager_email,
                'manager_note'  => $manager_note,
            ]),
        ]);

        if (!$res) {
            throw new Exception("거래처 등록에 실패했습니다.");
        }

        return $res;
    }

    public function deletePartner($id)
    {

        if (empty($id)) {
            throw new Exception("거래처 ID가 누락되었습니다.");
        }

        // 거래처 삭제 로직 구현 (예: DB에서 삭제)
        $res = $this->obj->service_model->delete_business_partner(DEBUG, [
            sprintf("id IN ('%s')", join("', '", $id))
        ]);

        if (!$res) {
            throw new Exception("거래처 삭제에 실패했습니다.");
        }

        return true;
    }

    public function uploadFile($partner_id)
    {

        try {

            // 거래처 저장 로직 구현 (예: DB에 저장)
            $uploadDir = '/assets/app_hyup/uploads/partner/';
            $file_res = $this->obj->file->upload('file1', $uploadDir);

            $ref_table = 'partner';

            if ($file_res['status'] !== 'success') {
                throw new Exception($file_res['message'] ?? '파일 업로드에 실패했습니다.');
            }

            $this->obj->service_model->insert_file(DEBUG, [
                'ref_table'     => $ref_table,
                'ref_id'        => $partner_id,
                'file_name'     => $file_res['originalFileName'],
                'file_path'     => $file_res['filePath'],
                'file_size'     => $file_res['fileSize'],
                'file_url'      => $file_res['fileSrc'],
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
        } catch (Exception $e) {

            throw new Exception("파일 업로드 중 오류가 발생했습니다: " . $e->getMessage());
        }
    }

    public function toggleBookmark($id, $bookmark_yn)
    {

        if (empty($id)) {
            throw new Exception("거래처 ID가 누락되었습니다.");
        }

        if (!in_array($bookmark_yn, ['Y', 'N'])) {
            throw new Exception("잘못된 즐겨찾기 값입니다.");
        }

        $res = $this->obj->service_model->update_business_partner(DEBUG, [
            'bookmark_yn' => $bookmark_yn,
            'updated_at' => date('Y-m-d H:i:s'),
        ], [
            sprintf("id = '%s'", $id)
        ]);

        if (!$res) {
            throw new Exception("즐겨찾기 업데이트에 실패했습니다.");
        }

        return true;
    }

    public function update($params = []) {}

    public function delete($params = []) {}
}
