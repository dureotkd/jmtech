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
        $type = $payloads['type'] ?? ''; // * sell / buy (판매,구매)
        $sub_type = $payloads['sub_type'] ?? ''; // * g / s (견적서,수주서)

        $partner_id = $payloads['partner_id'];
        $estimate_date = $payloads['estimate_date'];
        $fax_number = $payloads['fax_number'];
        $phone_number = $payloads['phone_number'];
        $title = $payloads['title'];
        $due_at = $payloads['due_at'];
        $sheets = $payloads['sheets'];
        $vat_type = $payloads['vat_type'];
        $amount = $payloads['amount'];
        $location = $payloads['location'];
        $valid_at = $payloads['valid_at'];
        $payment_type = $payloads['payment_type'];
        $etc_memo = $payloads['etc_memo'];

        $no = $this->makeUniqueNo();

        if (empty($type)) {
            throw new Exception("견적서 유형이 올바르지 않습니다.");
        }
        if (empty($sub_type)) {
            throw new Exception("견적서 하위 유형이 올바르지 않습니다.");
        }
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
            'type'          => $type,
            'sub_type'      => $sub_type,
            'no'            => $no,
            'partner_id'    => $partner_id,
            'estimate_date' => $estimate_date,
            'fax_number'    => $fax_number,
            'title'         => $title,
            'location'      => $location,
            'sheets'        => $sheets,
            'vat_type'      => $vat_type,
            'amount'        => $amount,
            'phone_number'  => $phone_number,
            'due_at'        => $due_at,
            'valid_at'      => $valid_at,
            'payment_type'  => $payment_type,
            'etc_memo'      => $etc_memo,
        ]);

        return $res;
    }


    public function update($update_data, $id)
    {

        $res = $this->obj->service_model->update_estimate(DEBUG, $update_data, [
            "id = '{$id}'"
        ]);

        return $res;
    }

    public function delete($id)
    {

        $estimate_row = $this->obj->service_model->get_estimate('row', [
            "id = {$id}"
        ]);

        if (empty($estimate_row)) {
            show_404();
            return;
        }

        $estimate_no = $estimate_row['no'] ?? '';

        $res = $this->obj->service_model->delete_estimate(DEBUG, [
            "id = '{$id}'"
        ]);

        // * 관련된 수주서도 함께 삭제
        $this->obj->service_model->delete_estimate(DEBUG, [
            "sub_type = 'S'",   // 수주서
            "no = '{$estimate_no}'" // 견적서 번호 동일
        ]);

        return $res;
    }

    public function change_status($id, $status)
    {
        $res = null;

        if (empty($status)) {
            throw new Exception("변경할 상태값이 올바르지 않습니다.");
        }

        if (empty($id)) {
            throw new Exception("견적서 아이디가 올바르지 않습니다.");
        }

        if ($status === '수주전환') {

            // * 수주전환 로직 구현 필요
            $estimate_row = $this->obj->service_model->get_estimate('row', [
                "id = '{$id}'"
            ]);

            if (empty($estimate_row)) {
                throw new Exception("해당 견적서를 찾을 수 없습니다.");
            }

            $su_estimate_row = [
                'type'              => $estimate_row['type'],
                'no'                => $estimate_row['no'],
                'estimate_date'     => date('Y-m-d'),
                'phone_number'      => $estimate_row['phone_number'],
                'fax_number'        => $estimate_row['fax_number'],
                'partner_id'        => $estimate_row['partner_id'],
                'title'             => $estimate_row['title'],
                'amount'            => $estimate_row['amount'],
                'memo'              => $estimate_row['memo'],
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
                'due_at'            => $estimate_row['due_at'],
                'valid_at'          => $estimate_row['valid_at'],
                'payment_type'      => $estimate_row['payment_type'],
                'etc_memo'          => $estimate_row['etc_memo'],
                'vat_type'          => $estimate_row['vat_type'],
                'sheets'            => $estimate_row['sheets'],
                'sub_type'          => 'S', // 수주서로 생성
                'status'            => '수주전환',
            ];

            $res = $this->obj->service_model->insert_estimate(DEBUG, $su_estimate_row);

            if (empty($res)) {
                throw new Exception("수주전환 중 오류가 발생했습니다.");
            }
        }

        $this->obj->service_model->update_estimate(DEBUG, [
            'status'        => $status,
            'updated_at'    => date('Y-m-d H:i:s')
        ], [
            "id = '{$id}'"
        ]);

        return $res;
    }

    public function uploadFile($estimate_id)
    {

        try {

            // 견적서 저장 로직 구현 (예: DB에 저장)
            $uploadDir = '/assets/app_hyup/uploads/estimate/';
            $file_upload_res = $this->obj->file->upload_multiple('files', $uploadDir);

            $ref_table = 'estimate';

            if (!empty($file_upload_res)) {
                foreach ($file_upload_res as $file_res) {

                    if ($file_res['status'] === 'success') {

                        $this->obj->service_model->insert_file(DEBUG, [
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

            throw new Exception("파일 업로드 중 오류가 발생했습니다: " . $e->getMessage());
        }
    }

    public function deleteFile($estimate_id, $file_ids)
    {
        if (!empty($file_ids)) {

            $this->obj->service_model->delete_file(DEBUG, [
                "ref_table = 'estimate'",
                "ref_id = {$estimate_id}",
                "id NOT IN ({$file_ids})"
            ]);
        } else {
            $this->obj->service_model->delete_file(DEBUG, [
                "ref_table = 'estimate'",
                "ref_id = {$estimate_id}"
            ]);
        }
    }

    private function makeUniqueNo()
    {

        $datePart = date('Ymd');
        $randomPart = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

        return $datePart . '-' . $randomPart;
    }
}
