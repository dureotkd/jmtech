<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
/**
 * PANN 사이트 로그인 관련 헬퍼
 */
// ------------------------------------------------------------------------

/**
 * 
 * 로그인 정보를 쿠키 또는 세션으로 저장한다.
 * @param string 
 */
if (!function_exists('set_login')) {
	function set_login($param)
	{
		$member_seq	= !empty($param['member_seq'])	? $param['member_seq']	: "";		// 회원번호
		$user_no	= !empty($param['user_no'])		? $param['user_no']		: "";		// 회원번호(관)
		$user_id	= !empty($param['user_id'])		? $param['user_id']		: "";		// 회원 아이디
		$area		= !empty($param['area'])		? $param['area']		: "";		// 사용처(pann_home:사용자 /  admin:관리자)


		// 			//======== 쿠키인 경우 ==================================================================
		// 			if( $area == "pann_home" ){
		// 				set_cookie("[member_seq]", $member_seq, 0, "","/",$area);	// 회원일련번호
		// 				set_cookie("[user_id]", $user_id, 0, "","/",$area);			// 아이디
		// 			}else if($area == "admin"){
		// 				if(empty($member_seq) && !empty($user_no)){
		// 					$member_seq = $user_no;
		// 				}
		// 				set_cookie("[member_seq]", $member_seq, 0, "", "/", $area);
		// 				set_cookie("[user_no]", $member_seq, 0, "", "/", $area);
		// 				set_cookie("[user_id]", $user_id, 0, "", "/", $area);
		// 			}


		//====== DB 세션인 경우 ===================================================================
		$CI = &get_instance();
		$CI->load->library('pann_session');

		if ($area == "pann_home") {
			$data[$area] = array(
				'member_seq'  => $member_seq,
				'user_id'     => $user_id,
			);
			$CI->pann_session->set_userdata($data);
		} else if ($area == "admin") {
			if (empty($member_seq) && !empty($user_no)) {
				$member_seq = $user_no;
			}

			$data[$area] = array(
				'member_seq'  => $member_seq,
				'user_no'  	=> $member_seq,
				'user_id'     => $user_id,
			);
			$CI->pann_session->set_userdata($data);
		} else if ($area == "hyup_admin") {

			if (empty($member_seq) && !empty($user_no)) {
				$member_seq = $user_no;
			}

			$data[$area] = array(
				'member_seq'  => $member_seq,
				'user_no'  	=> $member_seq,
				'user_id'     => $user_id,
			);
			$CI->pann_session->set_userdata($data);
		}
	}
}



/**
 * 
 * 로그인 정보 파괴한다.
 * @param string 사용처(pann_home:사용자 /  admin:관리자)
 */
if (!function_exists('set_logout')) {
	function set_logout($area = "")
	{

		//======== 쿠키인 경우 ==================================================================
		// 쿠키 삭제
		// 			if( $area == "pann_home" ){
		// 				set_cookie("[member_seq]", "", "", "", "/", $area);
		// 				set_cookie("[user_id]", "", "", "", "/", $area);
		// 				set_cookie("menu_bbscode", "", "", "", "/");
		// 			}else if($area == "admin"){
		// 				set_cookie("[user_no]", "", "", "", "/", $area);
		// 				set_cookie("[member_seq]", "", "", "", "/", $area);
		// 				set_cookie("[user_id]", "", "", "", "/", $area);
		// 			}



		//====== DB 세션인 경우 ===================================================================
		$CI = &get_instance();
		$CI->load->library('pann_session');

		$data[$area] = array($area);
		$CI->pann_session->unset_userdata($data);
	}
}




/**
 * 
 * 로그인 정보를 배열로 반환한다.
 * @param string 사용처(pann_home:사용자 /  admin:관리자)
 */
if (!function_exists('get_login_info')) {
	function get_login_info($area = "")
	{

		//======== 쿠키인 경우 ==================================================================
		// 			$home_cookie_array = array();
		// 			if(!empty($area)){
		// 				$home_cookie_array 		= get_cookie($area);
		// 			}
		// 			return $home_cookie_array;




		//====== DB 세션인 경우 ===================================================================
		$CI = &get_instance();

		$home_cookie_array = array(
			'member_seq'  	=> '',
			'user_id'     	=> '',
			'user_no'		=> ''
		);

		if (!empty($area)) {

			switch ($area) {

				case 'admin':

					$login_user_no      = get_cookie('hyup_admin[hyup_user_no]');

					$home_cookie_array['user_no'] = $login_user_no;

					break;

				case '':

					break;
			}
		}
		return $home_cookie_array;
	}
}
