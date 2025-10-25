<?php
class site_pagination {
	/**
	 * 페이징표현을 위한 데이터만 가져오기
	 * @param int $cur_page	현재페이지
	 * @param int $total_row_num	총페이지수
	 * @param int $view_row_num	한페이지에보여줄row수
	 * @param int $view_block_num	보여줄페이지블럭개수
	 */
	public function getPageNaviGationData($cur_page, $total_row_num, $view_row_num, $view_block_num, $cur_page_css_name="active"){
		$result_navi_data = null;
		
		if(empty($view_row_num)){
			$view_row_num = 10;
		}
		
		$pageRowCount = "";
		if ($cur_page == 1) {
			$pageRowCount = $total_row_num;
		} else {
			$pageRowCount = $total_row_num - (((int)$cur_page - 1) * $view_row_num);
		}
		$total_block 	= ceil($total_row_num / $view_row_num); 		//총블럭수			(총페이지수 / 보여줄 Row 개수)
		$cur_block 		= ceil($cur_page / $view_block_num); 			//현재 블럭페이지 번호	(현재페이지 / 보여줄 블럭 개수)
	
		$total_block_page_num = ceil($total_block / $view_block_num);	//블럭들의 총 페이지 수	(총블럭수 / 보여줄 블럭 개수)
	
		$start_page = ($cur_block - 1) * $view_block_num + 1;			//시작 블럭 수	( (현재 블럭번호 -1) * 보여줄블럭개수 )
		$end_check_num = $view_block_num-1;
		if( ($start_page+$view_block_num) > $total_block ){
			$end_check_num = $total_block - $start_page;
		}
		$end_page = ($start_page + $end_check_num) ;					//끝 블럭 수
	
		$page_num = null;
		//보여줄 페이지 숫자 뷰 데이터
		for( $i=$start_page; $i<=$end_page; $i++ ){
			$cur_page_css_data = "";
			if( $i == $cur_page ){
				$cur_page_css_data = $cur_page_css_name;
			}
			$page_num[$i] = $cur_page_css_data;
		}
	
		//이전페이지 존재여부
		$is_prev = false;
		if($cur_block > 1){
			$is_prev = true;
		}
	
		//다음페이지 존재여부
		$is_next = false;
		if($total_block_page_num > 1){
			if($cur_block != $total_block_page_num){
				$is_next = true;
			}
		}
	
		if(empty($page_num)){
			$page_num = array("1"=>"");
		}
	
		//limit 쿼리 만들기
		$start_limit_num = ($cur_page-1)*$view_row_num;
	
		$res_limit = "{$start_limit_num},{$view_row_num}";
	
		//유찬주 총 페이지수 계산하는 값 추가
		if($view_row_num > 0 && $total_row_num > 0 ){
			$total_page	= ceil($total_row_num/$view_row_num);
		} else {
			$total_page = 0;
		}
	
		/**
		 * array(1=>'',2=>'active'......)
		 */
		$result_navi_data = array(
				'start_page'	=> $start_page,		//블럭 시작 페이지
				'end_page'		=> $end_page,		//블럭 마지막 페이지
				'res_limit'		=> $res_limit,		//LIMIT 쿼리
				'page_num' 		=> $page_num,		//페이지블럭 들의 숫자들 array
				'is_prev' 		=> $is_prev,		//이전 페이지 존재여부
				'is_next' 		=> $is_next,		//다음 페이지 존재여부
				'pageRowCount' 	=> $pageRowCount,	//현재 페이지 기준 ROW 시작번호
				'total_page'	=> $total_page,		//유찬주 총 페이지수 계산하는 값 추가
				// 				'cur_block'		=> $cur_block
		);
	
		return $result_navi_data;
	
	}
	
	

	public function get_page_result($pageSet=array()){
		$g_rows = $pageSet['g_rows'];
		$totalCnt = $pageSet['totalCnt'];
		$page_href = !empty($pageSet['page_href']) ? $pageSet['page_href']:"";
		$page_type = !empty($pageSet['page_type']) ? $pageSet['page_type']:"";
		$page_last = !empty($pageSet['last_opt']) ? true : false;
		// 		$base_url  = $pageSet['base_url'];
	
		$page = !empty($pageSet['page']) ? $pageSet['page'] : "";
		if( !empty($_REQUEST['page'])){
			$page = $_REQUEST['page'];
		}
	
		$pagingOut_data = null;
		/************************ 페이지 계산 시작 ***************************************/
		$total_page  = ceil($totalCnt / $g_rows);
		$now_page = $page ? $page : 1;
	
		$limitArr = array(0, $g_rows);
	
		$limitArr[0] = ($now_page - 1) * $g_rows;
	
		//배열 타입
		if ($page_type == "array") {
			$limitArr[1] = $limitArr[0] + $limitArr[1] - 1;
		}
		//기본 타입
		else {
			$limitArr[1] = $g_rows;
		}
		//start = $limitArr[0], end = $limitArr[1]
		//$limit		=sprintf("%d,%d",$limitArr[0],$limitArr[1]);
	
		$QUERY_STRING = preg_replace("/&page=[0-9]*/", "", $_SERVER['QUERY_STRING']);
	
		if ($page_href) {
			$pagingOut = $this->get_page_listing($now_page, $total_page, $limitArr[1], $page_href, $page_last);
	
	
		} else {
			$pagingOut = $this->get_page_listing($now_page, $total_page, $limitArr[1], "{$_SERVER['PHP_SELF']}?$QUERY_STRING&page=", $page_last);
		}
		$pagingOut_data = $this->page_nav_data($now_page, $total_page, $limitArr[1], "");
		//			$pagingOut		= get_page_listing($now_page, $total_page, $limitArr[1], "$PHP_SELF?$QUERY_STRING&page=");
		//			$pagingOut		= get_page_listing($now_page, $total_page, $limitArr[1], "javascript:pageSumit()");
	
		/* 시작 레코드 구함 */
		$pageRowCount = "";
		if ($now_page == 1) {
			$pageRowCount = $totalCnt;
		} else {
			$pageRowCount = $totalCnt - (((int)$now_page - 1) * $g_rows);
		}
	
		$from_record = ($page - 1) * $g_rows;
	
		$sort1 = "";
		$sort2 = "";
		if (!$sort1) $sort1 = "1";
		if (!$sort2) $sort2 = "desc";
		/************************ 페이지 계산 끝 ***************************************/
		$page_result['limitArr'] = $limitArr;
		$page_result['pagingOut'] = $pagingOut;
		$page_result['pagingOut_data'] = $pagingOut_data;
		$page_result['pageRowCount'] = $pageRowCount;
	
		return $page_result;
	}
	
	
	/**
	 *  현재페이지,총페이지수,한페이지에 보여줄 목록수,URL
	 * @param unknown_type $cur_page
	 * @param unknown_type $total_page
	 * @param unknown_type $n
	 * @param unknown_type $url
	 */
	function get_page_listing($cur_page, $total_page, $n, $url,$last_option=null) {
		$page_block = 10;
		$cur_page = $cur_page ? $cur_page : 1;
		$total_block = ceil($total_page / $page_block); #총블럭수
		$cur_block = ceil($cur_page / $page_block); #현재 블럭번호
	
		//10 페이지씩 이동 시
		$start_page = (ceil($cur_page / $page_block) - 1) * $page_block + 1;
		$end_page = $start_page + $page_block - 1;
	
		if ($end_page > $total_page)
			$end_page = $total_page;
	
			$page_str = "";
	
			if (preg_match("/javascript/", $url)) {
				$url_array = explode("()", $url);
				$url_array[0] = $url_array[0] . "(";
				$url_array[1] = ");";
			} else {
				$url_array[0] = $url;
				$url_array[1] = "";
			}
			$prev_str = "";
			$btnPrev_css = "";
			$btnNext_css = "";
	
			if (!$last_option) {
				$btnPrev_css = " btnPrev";
				$btnNext_css = " btnNext";
			}
	
			//이전 페이지
			if ($cur_block > 1) {
				$prev_str .= "<li><a href=\"" . $url_array[0] . ($start_page - 1) . $url_array[1] . "\" class='imgSample" . $btnPrev_css . "'>이전</a></li>";
			} else {
				//2018-09-27 규남
				// 			$prev_str .= "<li><a href=\"javascript:void(0)\" class='imgSample" . $btnPrev_css . "'>이전</a></li>";
			}
	
			//페이지 링크
			for ($i = $start_page; $i <= $end_page; $i++) {
				if ($i == $cur_page) {
					$page_str .= "<li><a href=\"" . $url_array[0] . $i . $url_array[1] . "\" class='linkPage on first' >" . $i . "</a></li>";
				} else {
					$page_str .= "<li><a href=\"" . $url_array[0] . $i . $url_array[1] . "\" class='linkPage' >" . $i . "</a></li>";
				}
			}
	
			//다음 페이지
			$next_str = "";
			if ($cur_block < $total_block) {
				$next_str .= "<li><a href=\"" . $url_array[0] . ($end_page + 1) . $url_array[1] . "\" class='imgSample" . $btnNext_css . "'>다음</a></li>";
			} else {
				//2018-09-27 규남
				// 			$next_str = "<li><a href=\"javascript:void(0)\" class='imgSample" . $btnNext_css . "'>다음</a></li>";
			}
	
			$page_first = ""; //맨 처음
			$page_end = "";   //맨 마지막
			if ($last_option != null) {
				//마지막 페이지
				//			($total_block * 10) - 1
				if ($cur_page > 1) {
					$page_first = "<li><a href=\"" . $url_array[0] . "1" . $url_array[1] . "\" class='imgSample'>처음</a></li>";
				}  else {
					$page_first = "<li><a href=\"javascript:void(0)\" class='imgSample'>처음</a></li>";
				}
				//다음 페이지
				if ($cur_page < $total_page) {
					$page_end = "<li><a href=\"" . $url_array[0] . $total_page . $url_array[1] . "\" class='imgSample'>마지막</a></li>";
				} else {
					$page_end = "<li><a href=\"javascript:void(0)\" class='imgSample'>마지막</a></li>";
				}
			}
	
			$page_str = "<ul>
			{$page_first}
			{$prev_str}
			{$page_str}
			{$next_str}
			{$page_end}</ul>";
	
			return $page_str;
	}
	
	
	/**
	 *  현재페이지,총페이지수,한페이지에 보여줄 목록수,URL
	 * @param unknown_type $cur_page	현재페이지
	 * @param unknown_type $total_page	총페이지수
	 * @param unknown_type $n		한페이지에 보여줄 목록수
	 * @param unknown_type $url	URL
	 * @param unknown_type $page_block	보여줄 블럭 수
	 */
	public function page_nav_data($cur_page, $total_page, $n, $url,$last_option=null,$page_block=5) {
		$result_nav_data = null;
	
		//$page_block = 10;
		$cur_page = $cur_page ? $cur_page : 1;
		$total_block = ceil($total_page / $page_block); #총블럭수
		$cur_block = ceil($cur_page / $page_block); #현재 블럭번호
	
		//10 페이지씩 이동 시
		$start_page = (ceil($cur_page / $page_block) - 1) * $page_block + 1;
		$end_page = $start_page + $page_block - 1;
	
		if ($end_page > $total_page)
			$end_page = $total_page;
	
			$page_str = "";
	
			if (preg_match("/javascript/", $url)) {
				$url_array = explode("()", $url);
				$url_array[0] = $url_array[0] . "(";
				$url_array[1] = ");";
			} else {
				$url_array[0] = $url;
				$url_array[1] = "";
			}
	
			$prev_str = "";
			$btnPrev_css = "";
			$btnNext_css = "";
	
			if (!$last_option) {
				$btnPrev_css = " btnPrev";
				$btnNext_css = " btnNext";
			}
	
			//이전 페이지
			if ($cur_block > 1) {
				$prev_str .= "<a href=\"" . $url_array[0] . ($start_page - 1) . $url_array[1] . "\" class='imgSample" . $btnPrev_css . "'><img src='/assets/pann/common/imgprevButton.gif' alt='이전' /></a>";
				//$result_nav_data['prev'] = "";
			} else {
				$prev_str .= "<a href=\"javascript:void(0)\" class='imgSample" . $btnPrev_css . "'><img src='/assets/pann/common/imgprevButtonOff.gif' alt='이전' /></a>";
				$result_nav_data['prev'] = "";
			}
	
			//페이지 링크
			for ($i = $start_page; $i <= $end_page; $i++) {
				if ($i == $cur_page) {
					$result_nav_data[$i] = "active";
	
				} else {
	
					$result_nav_data[$i] = "";
	
				}
			}
	
			//다음 페이지
			$next_str = "";
			if ($cur_block < $total_block) {
				$next_str .= "<a href=\"" . $url_array[0] . ($end_page + 1) . $url_array[1] . "\" class='imgSample" . $btnNext_css . "'><img src='/assets/pann/common/imgnextButton.gif' alt='다음' /></a>";
			} else {
				$next_str = "<a href=\"javascript:void(0)\" class='imgSample" . $btnNext_css . "'><img src='/assets/pann/common/imgnextButtonOff.gif' alt='다음' /></a>";
				$result_nav_data['next'] = "";
			}
	
			$page_first = ""; //맨 처음
			$page_end = "";   //맨 마지막
			if ($last_option != null) {
				//마지막 페이지
				//			($total_block * 10) - 1
				if ($cur_page > 1) {
					$page_first = "<a href=\"" . $url_array[0] . "1" . $url_array[1] . "\" class='imgSample'><img src='/assets/pann/common/imgfirstButtonOn.gif' alt='처음 페이지로 이동' /></a>";
				}  else {
					$page_first = "<a href=\"javascript:void(0)\" class='imgSample'><img src='/assets/pann/common/imgfirstButtonOff.gif' alt='처음 페이지로 이동' /></a>";
				}
				//다음 페이지
				if ($cur_page < $total_page) {
					//				$page_end = "<a href=\"" . $url_array[0] . (($total_block * 10) - 1) . $url_array[1] . "\" class='imgSample btnNext'><img src='/assets/pann/common/imgnextButton.gif' alt='다음' /></a>";
					$page_end = "<a href=\"" . $url_array[0] . $total_page . $url_array[1] . "\" class='imgSample'><img src='/assets/pann/common/imglastButtonOn.gif' alt='마지막 페이지로 이동' /></a>";
				} else {
					$page_end = "<a href=\"javascript:void(0)\" class='imgSample'><img src='/assets/pann/common/imglastButtonOff.gif' alt='마지막 페이지로 이동' /></a>";
				}
			}
	
			$page_str = "
			{$page_first}
			{$prev_str}
			{$page_str}
			{$next_str}
			{$page_end}
			";
	
			return $result_nav_data;
	}
	
	
}