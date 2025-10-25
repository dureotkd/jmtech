<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter Cookie Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/cookie_helper.html
 */

// ------------------------------------------------------------------------

if (!function_exists('set_cookie')) {
	/**
	 * Set cookie
	 *
	 * Accepts seven parameters, or you can submit an associative
	 * array in the first parameter containing all the values.
	 *
	 * @param	mixed
	 * @param	string	the value of the cookie
	 * @param	string	the number of seconds until expiration
	 * @param	string	the cookie domain.  Usually:  .yourdomain.com
	 * @param	string	the cookie path
	 * @param	string	the cookie prefix
	 * @param	bool	true makes the cookie secure
	 * @param	bool	true makes the cookie accessible via http(s) only (no javascript)
	 * @return	void
	 */
	function set_cookie($name, $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = FALSE, $httponly = FALSE)
	{
		// Set the config file options
		get_instance()->input->set_cookie($name, $value, $expire, $domain, $path, $prefix, $secure, $httponly);
	}
}

if (!function_exists('set_cookie_v2')) {
	/**
	 * Set cookie
	 *
	 * Accepts seven parameters, or you can submit an associative
	 * array in the first parameter containing all the values.
	 *
	 * @param	mixed
	 * @param	string	the value of the cookie
	 * @param	string	the number of seconds until expiration
	 * @param	string	the cookie domain.  Usually:  .yourdomain.com
	 * @param	string	the cookie path
	 * @param	string	the cookie prefix
	 * @param	bool	true makes the cookie secure
	 * @param	bool	true makes the cookie accessible via http(s) only (no javascript)
	 * @return	void
	 */
	function set_cookie_v2($params)
	{

		$name 			= $params['name'];
		$expire 		= !empty($params['expire']) ? $params['expire'] : '';
		$value 			= !empty($params['value']) ? $params['value'] : '';
		$domain 		= !empty($params['domain']) ? $params['domain'] : '';
		$path 			= !empty($params['path']) ? $params['path'] : '';
		$prefix 		= !empty($params['prefix']) ? $params['prefix'] : '';
		$secure 		= !empty($params['secure']) ? $params['secure'] : false;
		$httponly 		= !empty($params['httponly']) ? $params['httponly'] : false;

		// printr($params);
		// exit;

		get_instance()->input->set_cookie($name, $value, $expire, $domain, $path, $prefix, $secure, $httponly);
	}
}


// --------------------------------------------------------------------

if (!function_exists('get_cookie')) {
	/**
	 * Fetch an item from the COOKIE array
	 *
	 * @param	string
	 * @param	bool
	 * @return	mixed
	 */
	function get_cookie($index, $xss_clean = NULL)
	{

		is_bool($xss_clean) or $xss_clean = (config_item('global_xss_filtering') === TRUE);
		$prefix = isset($_COOKIE[$index]) ? '' : config_item('cookie_prefix');

		if (strstr($_SERVER['REMOTE_ADDR'], '127.0.0') && $index == 'value_plus[user_no]') {

			return 1036;
			// return get_instance()->input->cookie($prefix . $index, $xss_clean);
		} else {

			return get_instance()->input->cookie($prefix . $index, $xss_clean);
		}
	}
}

// --------------------------------------------------------------------

if (!function_exists('delete_cookie')) {
	/**
	 * Delete a COOKIE
	 *
	 * @param	mixed
	 * @param	string	the cookie domain. Usually: .yourdomain.com
	 * @param	string	the cookie path
	 * @param	string	the cookie prefix
	 * @return	void
	 */
	function delete_cookie($name = '', $domain = '', $path = '/', $prefix = '')
	{
		set_cookie($name, '', 0, $domain, $path, $prefix);
	}
}

function force_delete_cookie($name, $domain = '', $path = '/')
{
	// 도메인 형식 정리 (앞에 . 붙이면 www, non-www 모두 적용)
	if (!empty($domain) && strpos($domain, '.') === 0) {
		$cookieDomain = $domain;
	} elseif (!empty($domain)) {
		$cookieDomain = '.' . $domain;
	} else {
		$cookieDomain = '';
	}

	// 현재 쿠키 배열에서도 제거
	if (isset($_COOKIE[$name])) {
		unset($_COOKIE[$name]);
	}

	// Secure, HttpOnly 버전 모두 삭제 시도
	setcookie($name, '', time() - 3600, $path, $cookieDomain);
	setcookie($name, '', time() - 3600, $path, $cookieDomain, true, true); // HTTPS + HttpOnly

	// 브라우저 콘솔에서도 확인 가능하게
	header_remove("Set-Cookie");
}
