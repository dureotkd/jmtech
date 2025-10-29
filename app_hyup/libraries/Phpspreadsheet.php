<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Phpspreadsheet
{
    public function loadExcelFile($file_path)
    {
        $spreadsheet = IOFactory::load($file_path);
        return $spreadsheet;
    }

    public function createSpreadsheet()
    {
        return new Spreadsheet();
    }
}
