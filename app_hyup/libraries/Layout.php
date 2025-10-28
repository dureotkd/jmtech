<?
if (!defined('BASEPATH')) exit('No direct script access allowed');

class layout
{
	// 레이아웃
	private $layout = '';

	// 웹 페이지 제목
	private $title = '제이엠테크 ERP';

	private $aside;

	private $pop_header = '';

	private $onlyConent;

	private $hostRegButton;

	private $blank = false;

	// 페이지 메타 글
	private $meta_keyword;

	// 페이지 소개 설명 글
	private $meta_description = '';

	// CSS 파일
	private $css_files;

	// JS 파일
	private $js_files;

	// 레이어 사용유뮤(상단,하단)
	private $is_tb_layer	= false;

	// 레이어 뷰 배열정보(key[top,bottom], value[view 경로]
	private $tb_layer_view_array;

	// 상단 레이어 뷰에서 필요한,사용될 데이터들
	private $top_layer_view_data;

	// 하단 레이어 뷰에서 필요한,사용될 데이터들
	private $bottom_layer_view_data;

	private $is_header = true;

	function __construct()
	{
		$this->obj = &get_instance();
	}

	function view($view, $data = null)
	{

		// view에서 사용할 데이터
		$loadedData					= array();

		// 레이아웃 경로 배열로
		$layout_src_explode_array	= explode("/", $this->layout);

		// 호출된 곳
		$area 						= $layout_src_explode_array[count($layout_src_explode_array) - 1];

		// 레이아웃 코드
		$layout_code 				= "LayoutCode/" . $area . "_code";

		// 셋팅한 라이브러리 로드
		$this->obj->load->library($layout_code, "", "layout_code");

		// 호출페이지 이름
		$view_array					= explode("/", $view);
		$view_page					= !empty($view_array) ? end($view_array) : "";

		$layout_code_data 			= !empty($data['layout_data']) ? $data['layout_data'] : '';
		$layout_data				= $this->obj->layout_code->getUseData($this->aside);

		$member_row					= !empty($layout_data['member_row']) ? $layout_data['member_row'] : [];
		$manager					= !empty($layout_data['manager']) ? $layout_data['manager'] : [];
		$menus						= !empty($layout_data['menus']) ? $layout_data['menus'] : [];
		$login_user					= !empty($layout_data['login_user']) ? $layout_data['login_user'] : [];
		$cart_data					= !empty($layout_data['cart_data']) ? $layout_data['cart_data'] : [];
		$order_status_group			= !empty($layout_data['order_status_group']) ? $layout_data['order_status_group'] : [];

		$top_banner_closed = $_COOKIE['top_banner_closed'] ?? false;

		$loadedData	= array(
			"menus"				=> $menus,
			"member_row"		=> $member_row,
			"manager"			=> $manager,									// 관리자 정보
			"title"				=> $this->title, // 웹 페이지 제목,
			"css_files"			=> $this->css_files,							// CSS 파일정보
			"js_files"			=> $this->js_files,								// JS 파일정보
			"aside"				=> $this->aside,
			"meta_description"	=> $this->meta_description,						// 페이지 설명
			"blank"				=> $this->blank,									// 레이아웃 블랭크 여부
			"onlyConent"		=> $this->onlyConent,
			"hostRegButton"		=> $this->hostRegButton,
			"is_header"			=> $this->is_header,
			"pop_header"		=> $this->pop_header,
			"content"			=> $this->obj->load->view($view, $data, true),	// content 부분(레이아웃 외)
			"view_page"			=> $view_page,									// 호출페이지 이름
			'layout_data'		=> $layout_data,
			'login_user'		=> $login_user,
			'cart_data'		=> $cart_data,									// 장바구니 데이터
			'order_status_group' => $order_status_group,
			'top_banner_closed'	=> $top_banner_closed,							// 상단 배너 닫힘 여부
		);

		$loadedData['top_menu_code']	= "";
		$loadedData['sub_menu_code']	= "";

		if (!empty($layout_code_data)) {

			$loadedData	= array_merge($loadedData, $layout_code_data);

			if (!empty($data['layout_data']['top_menu_code'])) {

				// 대메뉴 css
				$loadedData['top_menu_code']	= $data['layout_data']['top_menu_code'];

				if (!empty($data['layout_data']['sub_menu_code'])) {

					$loadedData['sub_menu_code']	= $data['layout_data']['sub_menu_code'];
				}
			}
		}

		$this->obj->load->view($this->layout, $loadedData, false);
	}

	/**
	 * 웹페이지 제목 셋팅
	 * @param unknown $title
	 */
	public function setTitle($title)
	{
		$this->title	= "{$title} | 제이엠테크";
	}

	public function setDescription($description)
	{
		$this->meta_description = $description;
	}

	public function setAside()
	{
		$this->aside	= true;
	}

	public function setHostRegButtons()
	{
		$this->hostRegButton = true;
	}

	public function setOnlyContent()
	{
		$this->onlyConent	= true;
	}

	/**
	 * 레이아웃 셋팅
	 * @param unknown $layout_src
	 */
	public function setLayout($layout_src)
	{
		$this->layout	= $layout_src;
	}

	public function setBlank()
	{
		$this->blank	= true;
	}

	public function setPopHeader($pop_header)
	{
		$this->pop_header	= $pop_header;
	}

	/**
	 * CSS 파일 셋팅
	 * @param unknown $css_files
	 */
	public function setCss($css_files)
	{
		$this->css_files	= $css_files;
	}

	/**
	 * JS 파일 셋팅
	 * @param unknown $js_files
	 */
	public function setScript($js_files)
	{
		$this->js_files	= $js_files;
	}

	public function setHeader($is_header = true)
	{
		$this->is_header	= $is_header;
	}
}
