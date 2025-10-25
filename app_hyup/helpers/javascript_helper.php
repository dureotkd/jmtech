<?


/**
 * 자바스크립트 경고 메시지 출력 후 뒤로가기(옵션)
 * @param string $msg 경고메시지
 * @param string $url (옵션) 디폴트는 뒤로가기.. 다른 자바스크립트 작성해도 됨.
 */
if (! function_exists('alert')) {
	function alert($msg, $action = "history.go(-1)")
	{
		echo sprintf("<script>alert('%s');%s</script>", $msg, $action);
	}
}

if (! function_exists('custom_alert')) {
	function custom_alert($msg, $action = "history.go(-1)")
	{
		echo sprintf(
			"<script>Swal.fire({
        		text: {$msg},
        		icon: 'error',
        		confirmButtonText: '닫기',
      });</script>",
			$msg,
			$action
		);
	}
}

/**
 *  자바스크립트 경호 메시지 출력 후 창 닫기
 *  
 * @param string $msg 경고메시지
 * @param string $url (옵션) 디폴트는 창닫기.. 다른 자바스크립트 작성해도 됨.
 */
if (! function_exists('alert_close')) {
	function alert_close($msg, $action = "window.close()")
	{
		echo sprintf("<script>alert('%s');%s</script>", $msg, $action);
	}
}


/**
 * 경고메세지만 출력
 * @param string $msg 경고메시지
 */
if (! function_exists('alert_only')) {
	function alert_only($msg)
	{
		echo sprintf("<script>alert('%s');</script>", $msg);
	}
}


/**
 * 경고후 페이지 이동
 * @param
 * @return
 */
if (! function_exists('js_redirect')) {
	function js_redirect($url = "")
	{
		// 출력 버퍼링 시작
		ob_start();
		$url = "document.location.href = '{$url}'";
		echo "<script language='javascript'>{$url};</script>";
		// 출력 버퍼링 종료 및 출력
		ob_end_flush();
		exit;
	}
}

/**
 * 경고후 페이지 이동
 */
if (!function_exists('alert_redirect')) {
	function alert_redirect($msg = null, $location = null)
	{
		echo sprintf("<script>alert('%s'); location.href='%s';</script>", $msg, $location);
		exit;
	}
}

/**
 * 경고후 뒤로가기
 */
if (!function_exists('alert_back')) {
	function alert_back($msg = null)
	{
		echo sprintf("<script>alert('%s'); history.back();</script>", $msg);
		exit;
	}
}

// 새로고침
if (!function_exists('js_reload')) {
	function js_reload()
	{
		echo "<script language='javascript'>location.reload();</script>";
		exit;
	}
}

if (!function_exists('confirm_popup')) {
	/**
	 * confirm 창 - 팝업용
	 * 
	 * @param unknown $msg			알림 메시지
	 * @param unknown $yes_action	확인 시 액션
	 * @param unknown $no_action	취소 시 액션
	 */
	function confirm_popup($msg = null, $yes_action = null, $no_action = null)
	{
		echo "<script type=\"text/javascript\">";
		echo "if (confirm(\"{$msg}\")) {";
		echo "{$yes_action}";
		echo "}";

		if (!empty($no_action)) {
			echo "else {";
			echo "{$no_action}";
			echo "}";
		}

		echo "</script>";

		exit;
	}
}
