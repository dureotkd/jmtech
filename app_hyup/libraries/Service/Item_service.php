<?php
class Item_service
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

    public function createItem($payloads = [])
    {
        $item_name = $payloads['item_name'] ?? '';
        $alias = $payloads['alias'] ?? '';
        $unit = $payloads['unit'] ?? '';
        $purchase_price = $payloads['purchase_price'] ?? '';
        $sales_price = $payloads['sales_price'] ?? '';
        $memo = $payloads['memo'] ?? '';
        $is_active = $payloads['is_active'] ?? '';

        if (empty($item_name)) {
            throw new Exception("품목명이 누락되었습니다.");
        }

        $item_code = $this->makeItemCode();

        $res = $this->obj->service_model->insert_item(DEBUG, [
            'item_code'     => $item_code,
            'item_name'     => $item_name,
            'alias'          => $alias,
            'unit'           => $unit,
            'purchase_price' => $purchase_price,
            'sales_price'    => $sales_price,
            'memo'           => $memo,
            'is_active'      => $is_active,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        if (!$res) {
            throw new Exception("품목 등록에 실패했습니다.");
        }

        return $res;
    }

    public function convertExcelToItem($file_path)
    {

        // * Phpspreadsheet 라이브러리 로드
        $this->obj->load->library('phpspreadsheet');

        $spreadsheet = $this->obj->phpspreadsheet->loadExcelFile($file_path);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($sheetData as $key => $row) {

            if ($key == 1 && $row['A'] != '품목정보') {

                throw new Exception("엑셀 파일 형식이 올바르지 않습니다. (첫번째 행에 '품목정보' 필요합니다.)");
            }

            if ($key == 2) {

                if (
                    $row['A'] != '품목코드'
                    || $row['B'] != '품목명'
                    || $row['C'] != '단위'
                    || $row['D'] != '구매가'
                    || $row['E'] != '판매가'
                    || $row['F'] != '기타사항'
                ) {
                    throw new Exception("엑셀 파일 형식이 올바르지 않습니다. (두번째 행에 올바른 헤더 필요합니다.)");
                }
            }

            $품목코드 = $row['A']; // A열
            $품목명 = $row['B']; // B열
            $단위 = $row['C']; // C열
            $구매가 = $row['D']; // D열
            $판매가 = $row['E']; // E열
            $기타사항 = $row['F']; // F열

            if (empty($품목명)) {
                // 품목명이 비어있으면 건너뛰기
                continue;
            }

            if (empty($품목코드)) {

                continue;
            }

            $this->obj->service_model->insert_item(DEBUG, [
                'item_code'         => $품목코드,
                'item_name'         => $품목명,
                'unit'              => $단위,
                'purchase_price'    => str_replace(',', '', $구매가),
                'sales_price'       => str_replace(',', '', $판매가),
                'memo'              => $기타사항,
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s'),
            ]);
        }
    }

    public function deleteItem($id)
    {

        if (empty($id)) {
            throw new Exception("품목 ID가 누락되었습니다.");
        }

        // 품목 삭제 로직 구현 (예: DB에서 삭제)
        $res = $this->obj->service_model->delete_item(DEBUG, [
            sprintf("id IN ('%s')", join("', '", $id))
        ]);

        if (!$res) {
            throw new Exception("품목 삭제에 실패했습니다.");
        }

        return true;
    }

    public function updateItem($params = []) {}

    private function makeItemCode()
    {

        $last_item = $this->obj->service_model->get_item_custom('row', [
            "item_code != ''"
        ], " ORDER BY id DESC LIMIT 1");

        $last_item_code = $last_item['item_code'] ?? '0000000000';

        // * last_item_code : 0000000895면 => 0000000896
        $new_item_code = str_pad((int)$last_item_code + 1, 10, '0', STR_PAD_LEFT);

        return $new_item_code;
    }
}
