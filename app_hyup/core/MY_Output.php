<?php
/**
 * @package : 
 * @version : 1.0
 * 
 * @todo : 캐시
 */
class MY_Output extends CI_Output {
  
    function nocache() {
        $this->set_header("HTTP/1.0 200 OK");
        $this->set_header("HTTP/1.1 200 OK");
        $this->set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT');
        $this->set_header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
        $this->set_header('Cache-Control: post-check=0, pre-check=0', FALSE);
        $this->set_header("Pragma: no-cache");
    }
}