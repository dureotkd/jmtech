<?php

	if (! defined ( 'BASEPATH' ))
		exit ( 'No direct script access allowed' );
	
	if ( ! function_exists('getDateTimeStr')){
		function getDateTimeStr(){
			return date("Y-m-d H:i:s");
		}
	}
	if ( ! function_exists('getByteNum')){
		 /**
	     * 문자열 바이트수 체크
	     *
	     * @param unknown_type $str
	     * @return number 
	     */
	    function getByteNum($str){
	    	$buf_k = 0;
	    	$buf_e = 0;
	    	for($i=0,$maxi=strlen($str); $i<$maxi; $i++) {
	    		if (ord($str[$i])< 128) {
	    			$buf_e++;
	    		}else{
	    			$buf_k++;
	    		}
	    	}
	    	$total_nick = $buf_k + $buf_e;
	    
	    	return $total_nick;
	    }
	}
	
	if ( ! function_exists('getMillitime')){
		/**
		 * 밀리세컨드 얻기
		 * @return string
		 */
		function getMillitime() {
			$microtime = microtime();
			$comps = explode(' ', $microtime);
		
			// Note: Using a string here to prevent loss of precision
			// in case of "overflow" (PHP converts it to a double)
			return sprintf('%d%03d', $comps[1], $comps[0] * 1000000);
		}
	}
	
	if (!function_exists('get_str_to_time_diff')) {
		//단순 지정 날짜 +- Y m d H i s
		function get_str_to_time_diff($date='1999-01-01 00:00:00', $str_term='+1 days', $return_dateFormat='Y-m-d H:i:s'){
			 
			$result_date	= date($return_dateFormat, strtotime($str_term, strtotime($date)));
			
			return $result_date;
		}
	}
?>