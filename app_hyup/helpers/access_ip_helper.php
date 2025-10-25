<?php

if (! function_exists('bidcoaching_ip')) {
	function bidcoaching_ip()
	{
		$res_bidcoaching_ip = null;

		$res_bidcoaching_ip = array(
			// 				"106.253.233.132", 	// 비드코칭 내부망
			// 				"106.253.233.135",	// 비드코칭 사설
			// 				"106.253.233.139",	// 비드코칭 사설 (2020-01-03 VPN 장비 교체)
			// 				"118.34.10.152", 	// 비드코칭 내부망
			// 				"118.34.10.179", 	// 비드코칭 내부망
			// 				"118.34.10.87", 	// 비드코칭 내부망
			// 				"118.34.10.181",	// 비드코칭 내부망 (KT)
			"1.223.69.253",		// 비드코칭 신사옥 (LG)
			"211.229.165.147",	//비 드코칭 신사옥 (KT)
			// 				"118.34.10.6",
			"127.0.0.1",
			"127.0.0.2",

			"115.91.77.134",	// 유동 IP 
		);

		for ($i = 130; $i <= 190; $i++) {
			// if($i == "132"){
			// continue;
			// }
			$res_bidcoaching_ip[] 	= "211.238.132." . $i;

			//$res_bidcoaching_ip[]	= "1.223.69." . $i;	// 비드코칭 사설
		}

		return $res_bidcoaching_ip;
	}
}

if (! function_exists('get_developer_ip')) {

	function get_developer_ip()
	{

		$res_bidcoaching_ip = array(
			"127.0.0.1",
			"211.238.132.131",
			"180.68.206.165",		// BID-W1
			"1.234.41.82",			// pann-w1
		);

		for ($i = 180; $i <= 190; $i++) {
			$res_bidcoaching_ip[] = "211.238.132." . $i;
		}
		return $res_bidcoaching_ip;
	}
}

if (! function_exists('get_pann_test_ip')) {
	/** 
	 * 판 테스트를 위한 아이피
	 */
	function get_pann_test_ip()
	{
		$ip = bidcoaching_ip();
		$ip[] = "211.210.32.70";		// 중화기술단
		$ip[] = "125.180.72.246";		// 중화기술단
		$ip[] = "220.126.15.99";		// 중화기술단
		$ip[] = "223.38.45.15";			// 중화기술단

		return $ip;
	}
}

if (! function_exists('etc_ip')) {
	function etc_ip()
	{
		$res_etc_ip = null;
		//비드테크
		$res_etc_ip = array(
			// 비드테크=========================
			"1.215.233.74",			// 비드테크 사무실 전체 아이피
			"1.215.233.75",			// 비드테크 사무실 전체 아이피
			"121.188.162.228",		// 재택근무자 홍성국
			"118.44.251.57",		// 재택근무자 피은자
			// 				"221.138.41.73",		// 재택근무자 박현숙
			"175.119.24.166",		// 재택근무자 박현숙 2020.04.23
			"122.43.177.144",		// 재택근무자 조윤주
			"61.79.171.91",			// 류흥열(자택)
			"58.227.109.106",		// 최남순(자택)
			"61.79.137.159",		// 김하윤(자택)
			"203.128.221.58",		// 조은경(자택)
			"203.128.219.72",		// 조은경(자택)
			"1.237.9.95",			// 김예본 (변경 전 : 121.164.159.134)
			"119.65.194.100",		// 테스트용 사무실 컴터
			//비드테크=========================

			// 비드테크 재택근무=========================
			"122.34.63.187",		// 채수진
			"221.141.202.41",		// 김수권
			"218.155.132.248",		// 최수진
			// 				"116.33.193.84",		// 김성곤
			"112.170.25.187",		// 김성곤 2020-08-10
			"112.170.25.90",		// 김성곤 2020-09-01
			"112.170.25.142",		// 김성곤   2020-10-14
			"14.6.176.47",			// 박현숙 2020-09-03
			// 비드테크 재택근무=========================

			//이을재==============================
			"114.204.93.46",
			"116.125.117.72",
			//이을재==============================

			// 정상희==============================
			// 				"211.107.223.48",
			// 				"203.232.116.249",
			// 				"125.181.38.239",
			// 				"125.181.38.244",	// 2018-11-14 추가
			"49.168.173.121",	// 2019-06-19 추가
			"211.107.223.48",	// 2019-06-19 추가
			"221.158.30.176",	// 2019-11-11 추가 장규남
			"49.168.173.83",	// 2020-02-14 추가
			"49.168.173.161",	// 2020-03-26 추가
			"59.26.57.47",		// 2020-08-24 추가
			"220.123.154.25",	// 2020-09-14 추가
			// 정상희==============================

			// 김영택==============================
			"121.182.46.56",
			// 김영택==============================
		);

		//기타분석가
		return $res_etc_ip;
	}
}

if (!function_exists('getRealIpAddr')) {
	/**
	 *  IP얻기
	 * @return string
	 */
	function getRealIpAddr()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip	= $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip	= $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip	= $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}
}

if (!function_exists('get_is_developer')) {
	/**
	 * 개발자 여부
	 *
	 * 1. 현재 접속 아이피가 개발자 아이피일 경우 해당
	 *
	 * @return boolean
	 */
	function get_is_developer()
	{

		$is_developer	= false;

		$developer_ip	= get_developer_ip();

		if (in_array($_SERVER['REMOTE_ADDR'], $developer_ip)) {


			$is_developer	= true;
		}

		return $is_developer;
	}
}

if (!function_exists('get_is_bidcoaching')) {
	/**
	 * 비드코칭 여부
	 *
	 * 1. 현재 접속 아이피가 비드코칭 아이피일 경우 해당
	 *
	 * @return boolean
	 */
	function get_is_bidcoaching()
	{

		$is_bidcoaching	= false;

		$bidcoaching_ip	= bidcoaching_ip();

		if (in_array($_SERVER['REMOTE_ADDR'], $bidcoaching_ip)) {


			$is_bidcoaching	= true;
		}

		return $is_bidcoaching;
	}
}
