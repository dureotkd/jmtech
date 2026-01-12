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
            "file",
            "php_ajax",
            "/Service/event_log_service",
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

        $partner_id = $payloads['partner_id'] ?? '';
        $estimate_date = $payloads['estimate_date'] ?? '';
        $fax_number = $payloads['fax_number'] ?? '';
        $phone_number = $payloads['phone_number'] ?? '';
        $title = $payloads['title'] ?? '';
        $due_at = $payloads['due_at'] ?? '';
        $sheets = $payloads['sheets'] ?? [];
        $vat_type = $payloads['vat_type'] ?? '';
        $amount = $payloads['amount'] ?? 0;
        $supply_amount = $payloads['supply_amount'] ?? 0;
        $tax_amount = $payloads['tax_amount'] ?? 0;
        $tab = $payloads['tab'] ?? '';
        $location = $payloads['location'] ?? '';
        $valid_at = $payloads['valid_at'] ?? '';
        $payment_type = $payloads['payment_type'] ?? '';
        $etc_memo = $payloads['etc_memo'] ?? '';
        $real_sheets = $payloads['real_sheets'] ?? [];

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
        // if (empty($phone_number)) {
        //     throw new Exception("전화번호를 입력해주세요.");
        // }
        // if (empty($title)) {
        //     throw new Exception("제목을 입력해주세요.");
        // }

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
            'supply_amount' => $supply_amount,
            'tax_amount'    => $tax_amount,
            'tab'           => $tab,
            'phone_number'  => $phone_number,
            'due_at'        => $due_at,
            'valid_at'      => $valid_at,
            'payment_type'  => $payment_type,
            'etc_memo'      => $etc_memo,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        if ($sub_type === 'G') {

            /**
             * * 견적서 시트 저장
             */
            if (!empty($real_sheets)) {
                $this->obj->service_model->insert_estimate_sheet(DEBUG, [
                    'estimate_id' => $res,
                    'sheets'      => $real_sheets,
                ]);
            }

            $this->obj->event_log_service->견적서등록($res);
        } else if ($sub_type === 'S') {
            $this->obj->event_log_service->수주서등록($res);
        }

        return $res;
    }


    public function update($update_data, $id)
    {
        $estimate_row = $this->obj->service_model->get_estimate('row', [
            "id = {$id}"
        ]);

        if (empty($estimate_row)) {
            show_404();
            return;
        }

        $res = $this->obj->service_model->update_estimate(DEBUG, [
            // 기존의 업데이트 데이터에서 real_sheets만 새 값으로 바꿔서 전체 배열로 전달
            'partner_id'     => $update_data['partner_id'] ?? null,
            'estimate_date'  => $update_data['estimate_date'] ?? null,
            'phone_number'   => $update_data['phone_number'] ?? null,
            'fax_number'     => $update_data['fax_number'] ?? null,
            'title'          => $update_data['title'] ?? null,
            'location'       => $update_data['location'] ?? null,
            'amount'         => $update_data['amount'] ?? null,
            'supply_amount'  => $update_data['supply_amount'] ?? null,
            'tax_amount'     => $update_data['tax_amount'] ?? null,
            'vat_type'       => $update_data['vat_type'] ?? null,
            'sheets'         => $update_data['sheets'] ?? null,
            'due_at'         => $update_data['due_at'] ?? null,
            'valid_at'       => $update_data['valid_at'] ?? null,
            'payment_type'   => $update_data['payment_type'] ?? null,
            'etc_memo'       => $update_data['etc_memo'] ?? null,
            'updated_at'     => date('Y-m-d H:i:s'),
        ], [
            "id = '{$id}'"
        ]);

        if ($estimate_row['sub_type'] === 'G') {

            if (!empty($update_data['real_sheets'])) {

                // * 견적서 시트 수정
                $this->obj->service_model->update_estimate_sheet(DEBUG, [
                    'sheets'      => $update_data['real_sheets'],
                ], [
                    "estimate_id = '{$id}'"
                ]);
            }


            $this->obj->event_log_service->견적서수정($id);
        } else if ($estimate_row['sub_type'] === 'S') {
            $this->obj->event_log_service->수주서수정($id);
        }

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

        if ($estimate_row['sub_type'] === 'G') {

            // * 견적서 시트 삭제
            $this->obj->service_model->delete_estimate_sheet(DEBUG, [
                "estimate_id = '{$id}'"
            ]);

            // * 관련된 수주서도 함께 삭제
            $this->obj->service_model->delete_estimate(DEBUG, [
                "sub_type = 'S'",   // 수주서
                "no = '{$estimate_no}'" // 견적서 번호 동일
            ]);
        }

        if ($estimate_row['sub_type'] === 'G') {
            $this->obj->event_log_service->견적서삭제($id);
        } else if ($estimate_row['sub_type'] === 'S') {
            $this->obj->event_log_service->수주서삭제($id);
        }

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

            // * 견적서의 경우에만 수주전환 가능
            if ($estimate_row['sub_type'] === 'G') {

                $su_estimate_row = $this->obj->service_model->get_estimate('row', [
                    "sub_type = 'S'",   // 수주서
                    "no = '{$estimate_row['no']}'" // 견적서 번호 동일
                ]);

                if (empty($su_estimate_row)) {

                    $estimate_sheets = json_decode($estimate_row['sheets'], true);

                    /**
                     * * --------------- 수주전환할 경우 견적서 시트 데이터 복사 ----------------
                     * * 허나 견적서랑 수주서랑 양식이 완전 다름..
                     * * 따라서.. 견적서에서 받은 값을 수주서의 맞춰야함...
                     */

                    try {

                        $new_sheets = $this->obj->php_ajax->get(도메인 . '/api/load_excel_template_v3', [
                            'sub_type' => 'S'
                        ]);

                        printr($new_sheets);
                        exit;


                        /**
                         * 순번	도면번호/품명	소재	수량	단위	단가	금액
                         *               [0] => D111
                  [0] => Array
        (
            [0] => s 도면번호
            [1] => al 소재
            [2] => 2 수량
            [3] => EA 단위
            [4] => 378700 단가
            [5] => 757400 금액
            [6] => 비고
        )
                         */
                        $estimate_sheets_data = $estimate_sheets[0]['data'];
                        $new_sheet_data = [];

                        foreach ($estimate_sheets_data as $row) {

                            $공급가액 = $row[5];

                            if (empty($공급가액)) {
                                continue;
                            }

                            $세액 = $공급가액 * 0.1;

                            $new_sheet_data[] = [
                                $row[0] . ' & ' . $row[1],    // 품목
                                $row[3],    // 규격
                                $row[2],    // 수량
                                $row[4],    // 단가
                                $공급가액,    // 공급가액
                                $세액,    // 세액
                                $row[6],    // 비고
                            ];
                        }

                        /**
                         * Array
(
    [0] => Array
        (
            [0] => s & al
            [1] => EA
            [2] => 11
            [3] => 240600
            [4] => 2646600
            [5] => 264660
            [6] => 
        )

)
                         */
                        printr($new_sheet_data);
                        exit;

                        @$new_sheets[0]['data'] = $new_sheet_data;
                    } catch (Exception $e) {

                        throw new Exception("수주전환 중 오류가 발생했습니다. : " . $e->getMessage());
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
                        'supply_amount'     => $estimate_row['supply_amount'],
                        'tax_amount'        => $estimate_row['tax_amount'],
                        'etc_memo'          => $estimate_row['etc_memo'],
                        'vat_type'          => $estimate_row['vat_type'],
                        'sheets'            => json_encode($new_sheets),
                        'sub_type'          => 'S', // 수주서로 생성
                        'status2'           => '도면확인',
                    ];

                    $res = $this->obj->service_model->insert_estimate(DEBUG, $su_estimate_row);

                    if (empty($res)) {
                        throw new Exception("수주전환 중 오류가 발생했습니다.");
                    }

                    $this->obj->event_log_service->수주서등록($res);
                }
            }
        }

        $this->obj->service_model->update_estimate(DEBUG, [
            'status'        => $status,
            'updated_at'    => date('Y-m-d H:i:s')
        ], [
            "id = '{$id}'"
        ]);

        $this->obj->event_log_service->견적서상태변경($id, $status);

        return $res;
    }

    public function change_status2($id, $status)
    {
        $res = null;

        if (empty($status)) {
            throw new Exception("변경할 상태값이 올바르지 않습니다.");
        }

        if (empty($id)) {
            throw new Exception("수주서 아이디가 올바르지 않습니다.");
        }

        $this->obj->service_model->update_estimate(DEBUG, [
            'status2'       => $status,
            'updated_at'    => date('Y-m-d H:i:s')
        ], [
            "id = '{$id}'"
        ]);

        $this->obj->event_log_service->수주서상태변경($id, $status);

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

    public function cloneFile($new_estimate_id, $file_ids)
    {

        if (!empty($file_ids)) {

            $file_list = $this->obj->service_model->get_file('all', [
                "ref_table = 'estimate'",
                "id IN ({$file_ids})"
            ]);

            if (!empty($file_list)) {
                foreach ($file_list as $file_row) {

                    $this->obj->service_model->insert_file(DEBUG, [
                        'ref_table'     => 'estimate',
                        'ref_id'        => $new_estimate_id,
                        'file_name'     => $file_row['file_name'],
                        'file_path'     => $file_row['file_path'],
                        'file_size'     => $file_row['file_size'],
                        'file_url'      => $file_row['file_url'],
                        'created_at'    => date('Y-m-d H:i:s'),
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]);
                }
            }
        }
    }

    private function makeUniqueNo()
    {

        $datePart = date('Ymd');
        $randomPart = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

        return $datePart . '-' . $randomPart;
    }
}
