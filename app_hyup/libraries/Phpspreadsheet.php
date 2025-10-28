<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Phpspreadsheet
{
    public function loadExcelFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new Exception("엑셀 파일을 찾을 수 없습니다: {$filePath}");
        }

        return IOFactory::load($filePath);
    }

    public function createSpreadsheet()
    {
        return new Spreadsheet();
    }
}
