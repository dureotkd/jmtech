<?php

/**
 * @todo : 모든 컨트롤러는 이클래스를 상속 받아 사용합니다.
 */
class MY_Controller extends CI_Controller
{



    public $start_time_check = 0;

    # Parameter reference
    public $params = array();

    public $cookies = array();

    # 인증 처리 예외
    public $allow = array();

    # 인증 처리 확인용
    public $mustLogin = array();

    # 인증 처리 확인용
    //public $NC = array();

    public function __construct()
    {

        parent::__construct();
        $this->start_time_check = microtime();
        //echo $start;
        $str = explode(" ", $this->start_time_check);
        $this->start_time_check = $str[0] + $str[1];


        # Parameter
        $this->params = $this->getParams();

        $this->cookies = $this->getCookies();

        //자주사용되는 쿠키값 미리정의
        if (empty($this->cookies['session_igunsul'])) {
            $this->cookies['session_igunsul'] = '';
        }
        if (empty($this->cookies['session_igunsul_admin'])) {
            $this->cookies['session_igunsul_admin'] = '';
        }
        if (empty($this->cookies['memory_pw'])) {
            $this->cookies['memory_pw'] = '';
        }
        if (empty($this->cookies['session_igunsul_id_view2'])) {
            $this->cookies['session_igunsul_id_view2'] = '';
        }
        if (empty($this->cookies['session_choose_web'])) {
            $this->cookies['session_choose_web'] = '';
        }

        //$this->output->enable_profiler(true);
    }

    private function getParams()
    {

        $this->ajaxCharConvert();




        $aParams = array_merge($this->doGet(), $this->doPost());

        $this->sql_injection_filter($aParams);

        return $aParams;
    }





    private function ajaxCharConvert()
    {
        /*
    	if ( !empty($_REQUEST['useAjaxKr']) && $_REQUEST['useAjaxKr'] == "true") {
    		if(count($_POST) > 0){
	    		foreach($_POST as $name => $value) {
	    			if (!is_array($_POST[$name])) {
	    				$value_char = mb_detect_encoding($value, array('UTF-8',  'shift_jis', 'CN-GB', 'EUC-KR'));
	    				$_POST[$name] = iconv($value_char, "CP949", $value);
	    				$_REQUEST[$name] = iconv($value_char, "CP949", $value);

	    				//예외조건
	    				if (!$_POST[$name] || !ereg_replace("\r", "", $_POST[$name])) {
	    					$_POST[$name] = $value;
	    					$_REQUEST[$name] = $value;
	    				}
	    			} else {
	    				foreach ($_POST[$name] as $name_a => $value_a) {
	    					if (!is_array($_POST[$name][$name_a])) {
	    						if(!empty($value_a)){
	    							$value_a_char = mb_detect_encoding($value_a, array( 'UTF-8', 'shift_jis', 'CN-GB', 'EUC-KR'));
	    							$_POST[$name][$name_a] = iconv($value_a_char, "CP949", $value_a);
	    							$_REQUEST[$name][$name_a] = iconv($value_a_char, "CP949", $value_a);

	    							//예외조건
	    							if (!$_POST[$name][$name_a] || !ereg_replace("\r", "", $_POST[$name][$name_a])) {
	    								$_POST[$name][$name_a] = $value_a;
	    								$_REQUEST[$name][$name_a] = $value_a;
	    							}
	    						}
	    					} else {
	    						foreach ($_POST[$name][$name_a] as $name_b => $value_b) {
	    							if (!is_array($_POST[$name][$name_a][$name_b])) {
		    							$value_b_char = mb_detect_encoding($value_b, array('UTF-8',  'shift_jis', 'CN-GB', 'EUC-KR'));
		    							$_POST[$name][$name_a][$name_b] = iconv($value_b_char, "CP949", $value_b);
		    							$_REQUEST[$name][$name_a][$name_b] = iconv($value_b_char, "CP949", $value_b);

		    							//예외조건
		    							if (!$_POST[$name][$name_a][$name_b] || !ereg_replace("\r", "", $_POST[$name][$name_a][$name_b])) {
		    								$_POST[$name][$name_a][$name_b] = $value_b;
		    								$_REQUEST[$name][$name_a][$name_b] = $value_b;
		    							}
	    							} else {	// 2차배열로 인한 추가 2017.07.18 -방준보-
	    								foreach ($_POST[$name][$name_a][$name_b] as $name_c => $value_c) {
	    									$value_c_char	= "";
	    									if (!empty($value_c)) {
	    										$value_c_char								= mb_detect_encoding($value_c, array('UTF-8', 'shift_jis', 'CN-GB', 'EUC-KR'));
	    										$_POST[$name][$name_a][$name_b][$name_c]	= iconv($value_c_char, "CP949", $value_c);
	    										$_REQUEST[$name][$name_a][$name_b][$name_c]	= iconv($value_c_char, "CP949", $value_c);
	    									}
	    								}
	    							}
	    						}
	    					}
	    				}
	    			}
	    		}
    		}


    		if (count($_GET) > 0) {
	    		foreach($_GET as $name => $value) {
	    			if (!is_array($_GET[$name])) {
	    				$value_char			= mb_detect_encoding($value, array('UTF-8', 'shift_jis', 'CN-GB', 'EUC-KR'));
	    				$_GET[$name] 		= iconv($value_char, "CP949", $value);
	    				$_REQUEST[$name] 	= iconv($value_char, "CP949", $value);

	    				//예외조건
	    				if (empty($_GET[$name]) || empty(ereg_replace("\r", "", $_GET[$name]))) {
	    					$_GET[$name] 		= $value;
	    					$_REQUEST[$name] 	= $value;
	    				}
	    			} else {
	    				foreach ($_GET[$name] as $name_a => $value_a) {
	    					if (!is_array($_GET[$name][$name_a])) {
	    						if (!empty($value_a)) {
	    							$value_a_char 				= mb_detect_encoding($value_a, array('UTF-8', 'shift_jis', 'CN-GB', 'EUC-KR'));
	    							$_GET[$name][$name_a] 		= iconv($value_a_char, "CP949", $value_a);
	    							$_REQUEST[$name][$name_a]	= iconv($value_a_char, "CP949", $value_a);

	    							//예외조건
	    							if (!$_GET[$name][$name_a] || !ereg_replace("\r", "", $_GET[$name][$name_a])) {
	    								$_GET[$name][$name_a] 		= $value_a;
	    								$_REQUEST[$name][$name_a] 	= $value_a;
	    							}
	    						}
	    					} else {
	    						foreach ($_GET[$name][$name_a] as $name_b => $value_b) {
	    							if (!is_array($_GET[$name][$name_a][$name_b])) {
		    							$value_b_char 						= mb_detect_encoding($value_b, array('UTF-8', 'shift_jis', 'CN-GB', 'EUC-KR'));
		    							$_GET[$name][$name_a][$name_b] 		= iconv($value_b_char, "CP949", $value_b);
		    							$_REQUEST[$name][$name_a][$name_b] 	= iconv($value_b_char, "CP949", $value_b);

		    							//예외조건
		    							if (!$_GET[$name][$name_a][$name_b] || !ereg_replace("\r", "", $_GET[$name][$name_a][$name_b])) {
		    								$_GET[$name][$name_a][$name_b] 		= $value_b;
		    								$_REQUEST[$name][$name_a][$name_b] 	= $value_b;
		    							}
	    							} else {	// 2차배열로 인한 추가 2017.07.18 -방준보-
	    								foreach ($_GET[$name][$name_a][$name_b] as $name_c => $value_c) {
	    									$value_c_char	= "";
	    									if (!empty($value_c)) {
		    									$value_c_char								= mb_detect_encoding($value_c, array('UTF-8', 'shift_jis', 'CN-GB', 'EUC-KR'));
		    									$_GET[$name][$name_a][$name_b][$name_c]		= iconv($value_c_char, "CP949", $value_c);
		    									$_REQUEST[$name][$name_a][$name_b][$name_c]	= iconv($value_c_char, "CP949", $value_c);
	    									}
	    								}
	    							}
	    						}
	    					}
	    				}
	    			}
	    		}
    		}
    	}
    	*/
    }

    private function getCookies()
    {

        $aParams = $this->doCookie();

        return $aParams;
    }


    private function doGet()
    {
        $aGetData = $this->input->get(NULL, TRUE);
        return (empty($aGetData)) ? array() : $aGetData;
    }

    private function doPost()
    {
        $aPostData = $this->input->post(NULL, TRUE);
        return (empty($aPostData)) ? array() : $aPostData;
    }

    private function doCookie()
    {
        $aCookieData = $this->input->cookie(NULL, TRUE);

        return (empty($aCookieData)) ? array() : $aCookieData;
    }

    public function js($file, $v = '')
    {
        if (is_array($file)) {
            foreach ($file as $iKey => $sValue) {
                $this->optimizer->setJs($sValue, $v);
            }
        } else {
            $this->optimizer->setJs($file, $v);
        }
    }

    public function externaljs($file)
    {
        if (is_array($file)) {
            foreach ($file as $iKey => $sValue) {
                $this->optimizer->setExternalJs($sValue);
            }
        } else {
            $this->optimizer->setExternalJs($file);
        }
    }

    public function css($file, $v = '')
    {
        if (is_array($file)) {
            foreach ($file as $iKey => $sValue) {
                $this->optimizer->setCss($sValue, $v);
            }
        } else {
            $this->optimizer->setCss($file, $v);
        }
    }

    // sql 인젝션 필터링
    private function sql_injection_filter($str)
    {
        /*
         * Is the string an array?
        *
        */
        if (is_array($str)) {
            foreach ($str as $key => $value) {
                $str[$key] = $this->sql_injection_filter($str[$key]);
            }

            return $str;
        }


        $aPatterns = array(
            "delete[[:space:]]+from",
            "drop[[:space:]]+database",
            "drop[[:space:]]+table",
            "drop[[:space:]]+column",
            "drop[[:space:]]+procedure",
            "create[[:space:]]+table",
            "update[[:space:]]+.*[[:space:]]+set",
            "insert[[:space:]]+into.*[[:space:]]+values",
            "select[[:space:]]+.*[[:space:]]+from",
            "bulk[[:space:]]+insert",
            "union[[:space:]]+select",
            "union/\*\*/[[:space:]]+select/\*\*/",
            "/\*[[:space:]]+\*/",
            "/\*\*/",
            "or+[[:space:]]+[a-zA-Z]+[[:space:]]*['\"]?[[:space:]]*=[[:space:]]*\(?['\"]?[[:space:]]*[a-zA-Z]+",
            "or+[[:space:]]+[0-9]+[[:space:]]*['\"]?[[:space:]]*=[[:space:]]*\(?['\"]?[[:space:]]*[0-9]+",
            "alter[[:space:]]+table",
            "into[[:space:]]+outfile",
            "load[[:space:]]+data",
            "declare.+varchar.+set",
            "sleep\([0-9]{1,}\)",
        );

        foreach ($aPatterns as $regexp) {
            $esc_regexp = str_replace("/", "\/", $regexp);

            if (preg_match("/$esc_regexp/i", $str)) {
                exit('error : sql injection attack');
            }
        }
    }
    /**
     *  변수 셋팅
     */
    public function setVars($arr = array())
    {
        $aVars = array();
        foreach ($arr as $val) {
            $aVars;
        }

        $this->load->vars($aVars);
    }

    /**
     *  공통 전역 변수 셋팅
     */
    public function setCommonVars()
    {
        $aVars = array();

        $aVars['test'] = array("test1" => "test1");

        $this->load->vars($aVars);
    }
}
