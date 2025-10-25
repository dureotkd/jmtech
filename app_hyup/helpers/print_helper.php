<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
/**
 * 출력에 관련된 함수를 모아놓은 Helper입니다.
 * 
 */
// ------------------------------------------------------------------------



/**
 * printr
 * 
 * 첫번째 인자값을 그래로 출력하게 하는 역할을 한다.
 * 두번째 인자(옵션)가 없을경우에는  <xmp></xmp>를 감싸서 출력한다.
 * 
 * @param string $var 배열 또는 문자열
 * @param string $cover_tag 기본은 xmp다. 'pre'를 기술하면 <pre>첫번째인자출력</pre> 형태로 출력한다.
 */
if ( ! function_exists('printr')){
	function printr($var,$cover_tag="xmp"){
		echo "<{$cover_tag}>";
		print_r($var);
		echo "</{$cover_tag}>";
	}
}


?>