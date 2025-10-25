<?php

/**
 * 모델 확장합니다.
 * 모든 모델은 이 클래스를 상속 받아야 합니다.
 *
 * @author junhan Lee <junes127@gmail.com>
 */
class MY_Model extends CI_Model
{
	protected $ci 				= null;
	private $is_real_conn 		= false;	// 로컬에서 실DB 연결 여부(true : 실 DB 사용 , false : 연구 DB 사용 )
	private $db_conn 			= null;
	private $debug 				= false;

	public function __construct()
	{


		$this->load->helper("access_ip");

		if ($_SERVER['REMOTE_ADDR'] == "127.0.0.1") {
			//////////////////////////////////////////
			//////////////////////////////////////////
			/*
    		 * 로컬에서 실DB 연결 여부 :
    		* 		- true : 실 DB 사용
    		* 		- false : 연구 DB 사용
    		*/
			$this->is_real_conn = true;
			//////////////////////////////////////////
			//////////////////////////////////////////
		}



		$this->ci = &get_instance();

		parent::__construct();
	}

	public function force_debug($type = true)
	{

		if (empty($type)) {

			echo '[강제] 디버그 유형을 선택해주세요';
			exit;
		}

		$this->debug = $type;
	}



	protected function excute($sSql = '', $sType = 'all', $sPrefix = null)
	{

		if ($this->debug == 'all') {

			echo "debug : {$sSql} <br />";
			return 1;
		} else if ($this->debug == 'exec' && $sType == 'exec') {

			echo "debug : {$sSql} <br />";
			return 1;
		}

		$aDsn = $this->getDsn($sSql, $sPrefix);
		if (!empty($aDsn)) {
			$this->db_conn = $this->getDbInstance($aDsn);
		} else {
			throw new Exception('Select Query : aDsn can not be empty !');
		}

		if ($_SERVER['REMOTE_ADDR'] == "211.238.132.190") {
			//  	echo "{$sSql}<bR>";
		}

		return $this->_excute($sSql, $sType, $sPrefix);
	}

	private function _excute($sSql = '', $sType = 'all', $sPrefix = null)
	{
		if (empty($sSql)) return false;

		//      if ($this->isSelect($sSql) === true) {

		if ($sType == 'all') {
			return $this->db_conn->query($sSql)->result_array();
		} else if ($sType == 'row') {
			return $this->db_conn->query($sSql)->row_array();
		} else if ($sType == "one") {
			$row = $this->db_conn->query($sSql)->row_array();

			if (!empty($row)) {
				foreach ($row as $_d) {
					return $_d;
				}
			}
		} else if ($sType == 'exec') {
			// $r = $this->db_conn->query($sSql);

			// $tmpSql = strtolower($sSql);
			// $tmpSql = trim($tmpSql);

			// // insert 인경우에는 반환을 last_indsert_id 를 반환한다.
			// if (preg_match("/^insert /", $tmpSql)) {
			// 	$row = $this->db_conn->query("SELECT LAST_INSERT_ID() as list_insert_id FROM DUAL")->row_array();
			// 	if (!empty($row['list_insert_id'])) {
			// 		$r = $row['list_insert_id'];
			// 	}
			// }

			// return $r;

			$this->db_conn->trans_begin(); // 트랜잭션 시작

			$r = $this->db_conn->query($sSql);

			$tmpSql = strtolower(trim($sSql));

			// insert 인 경우 insert_id 반환
			if (preg_match("/^insert /", $tmpSql)) {
				$row = $this->db_conn->query("SELECT LAST_INSERT_ID() as list_insert_id FROM DUAL")->row_array();
				if (!empty($row['list_insert_id'])) {
					$r = $row['list_insert_id'];
				}
			}

			// 트랜잭션 처리
			if ($this->db_conn->trans_status() === false) {
				$this->db_conn->trans_rollback();
				throw new Exception('Query failed. Rolled back.');
			} else {
				$this->db_conn->trans_commit();
			}

			return $r;
		} elseif ($sType == 'proc') {
			return $this->db_conn->query($sSql);
		} else {
			throw new Exception('Select Query : sType can not be exec or empty !');
		}
	}

	private function isSelect($sSql = '')
	{
		$bSelect = true;
		if (substr(trim(strtolower($sSql)), 0, 6) != "select") {
			$bSelect = false;
		}
		return $bSelect;
	}

	private function isMainDB($sPrefix = '')
	{ //mainDB 인지판별 함수
		$bMain = true;
		if (substr(trim(strtolower($sPrefix)), -4, 4) != "main") {
			$bMain = false;
		}

		return $bMain;
	}


	private function getDsn($sSql = '', $sPrefix = null, $sDatabaseName = null)
	{
		// Is the config file in the environment folder?
		if (!defined('ENVIRONMENT') or !file_exists($file_path = APPPATH . 'config/' . ENVIRONMENT . '/database.php')) {
			if (!file_exists($file_path = APPPATH . 'config/database.php')) {
				show_error('The configuration file database.php does not exist.');
			}
		}

		include $file_path;

		if (!isset($db) or count($db) == 0) {
			show_error('No database connection settings were found in the database config file.');
		}



		if (!empty($sPrefix)) {
			$aDsn = $this->getBalancing($sSql, $sPrefix, $db);
		}


		return $aDsn;
	}

	// 로드밸런싱, 스위칭 등을 모두 처리
	private function getBalancing($sSql = '', $sPrefix = null, $db = array())
	{
		//직접적으로 메인DB select나 update delete 할 시에는 요기
		if ($this->isMainDB($sPrefix) == true) {

			if ($this->is_real_conn == true) {

				$db[$sPrefix]['hostname'] = $db[$sPrefix]['hostaddr'];
			} else if ($_SERVER['REMOTE_ADDR'] == "127.0.0.1") {

				$db[$sPrefix]['hostname'] = "211.238.132.180";
			}
		}
		//정상적 처리 시 요기
		else {
			//SELECT
			if ($this->isSelect($sSql) === true) {
				$db_acount_type = 'slave';

				/**
				 * 유찬주 
				 * 특정 DB 커넥션은 벨런싱 안타도록 처리
				 * 
				 */
				$not_balancing_dbname_array = array(
					"bid_coaching"
				);


				if ($_SERVER['REMOTE_ADDR'] == "127.0.0.1") {
					$db[$sPrefix]['hostname'] = "211.238.132.180";
					// 	            	$db[$sPrefix]['hostname'] = $db[$sPrefix]['hostaddr'];		//사설주소에서 원격 주소로 변경

					if ($this->is_real_conn == true) {

						$db[$sPrefix]['hostname'] = $db[$sPrefix]['hostaddr'];
					}
				} else if (in_array($sPrefix, $not_balancing_dbname_array)) { //특정 DB 커넥션은 벨런싱 안타도록 처리
					// 	            	$db[$sPrefix]['hostname'] = $db[$sPrefix]['hostaddr'];
				} else {

					//SELECT 시 DB12,DB13,DB14 중 부하가 제일 적은 서버로 연결 시킨다.
					//정보체크
					$check_ip_array = array(
						//             			"192.168.0.82",
						//             			"192.168.0.83",
						"192.168.0.40",
					);
					//각 서버 아이피 정보
					$server_name_ip_array = array(
						// 	            			"db12"	=>	"192.168.0.82",
						// 	            			"db13"	=>	"192.168.0.83",
						"db14"	=>	"192.168.0.40",
					);

					if (in_array($db[$sPrefix]['hostname'], $check_ip_array)) {
						/* 
            			require_once '/home2/server_moniter/DB_LOAD_INFO.inc';
            			if(!empty($DB_LOAD_INFO)){
            				$db_load_infos = $DB_LOAD_INFO;
            				 
            				$real_server_load_array = array(
            						"db12"	=>	$db_load_infos['DB12'],
            						"db13"	=>	$db_load_infos['DB13'],
//             						"db14"	=>	$db_load_infos['db14'],
            				);
            				$low_load_db_value = 90.1;
            				$low_load_db_name = "db13";
            				foreach ($real_server_load_array as $_key=>$_val){
            					if($low_load_db_value > $_val){
            						$low_load_db_value = $_val;
            						$low_load_db_name = $_key;
            					}
            				}
            				 
            			}else {
            				$low_load_db_name = "db13";
            			} */

						$low_load_db_name = "db14";

						$db[$sPrefix]['hostname'] = $server_name_ip_array[$low_load_db_name];
					}
				}
			}
			//INSERT UPDATE DELETE
			else {

				// * MAIN UPDATE 어미사 붙히는용
				$sPrefix .= '';


				if ($this->is_real_conn == true) {

					$db[$sPrefix]['hostname'] = $db[$sPrefix]['hostaddr'];
				} else if ($_SERVER['REMOTE_ADDR'] == "127.0.0.1") {
					$db[$sPrefix]['hostname'] = "211.238.132.180";
				}
			}
		}
		return array('sPrefix' => $sPrefix, 'aDsn' => $db[$sPrefix]);
	}

	private function getDbInstance($aDsn = array())
	{

		$prefix = $aDsn['sPrefix'];

		foreach (get_object_vars($this->ci) as $CI_object_name => $CI_object) {

			if (is_object($CI_object) && is_subclass_of(get_class($CI_object), 'CI_DB') && $CI_object_name == $prefix) {
				return $CI_object;
			}
		}
		@$this->ci->{$prefix} = $this->ci->load->database($aDsn['aDsn'], TRUE);


		return $this->ci->{$prefix};
	}

	protected function setIsRealCon()
	{
		$this->is_real_conn = true;
	}

	/**
	 * Insert Query문을 만들어줍니다.
	 *
	 */
	public static function getInsertQuery($table, $aData)
	{
		if (!is_array($aData)) {
			$this->errorDSP("Array 형태의 데이타가 필요합니다." . __LINE__, E_USER_ERROR);
			return false;
		} elseif (count($aData) == 0) {
			$this->errorDSP("데이터 없이 수행할수 없습니다." . __LINE__, E_USER_ERROR);
			return false;
		} else {

			$keys = array_keys($aData);
			$values = array_values($aData);
			$values = self::magic_quotes_gpc($values);
			$sql = 'INSERT INTO ' . $table . '(`' . implode('`,`', $keys) . '`) VALUES ("' . str_replace("''", "NULL", implode('","', $values)) . '")';
		}

		return $sql;
	}



	/**
	 * Delete Query문을 만들어줍니다.
	 *
	 */
	public function getDeleteQuery($table, $where = array())
	{
		if (empty($table)) {
			$this->errorDSP("테이블을 선택해주세요", E_USER_ERROR);
			return false;
		}

		if (!is_array($where)) {
			$this->errorDSP("조건절은 Array 형태입니다." . __LINE__, E_USER_ERROR);
			return false;
		} elseif (count($where) == 0) {
			$this->errorDSP("조건절이 없이 수행할수 없습니다." . __LINE__, E_USER_ERROR);
			return false;
		}

		$sql = sprintf("delete from %s where %s", $table, join(" AND ", $where));


		return $sql;
	}


	/**
	 * Update Query문을 만들어줍니다.
	 *
	 */
	public static function getUpdateQuery($table, $aData, $sWhere = array())
	{
		if (!is_array($sWhere)) {
			$this->errorDSP("조건절은 Array() 형태입니다." . __LINE__, E_USER_ERROR);
			return false;
		} elseif (count($sWhere) == 0) {
			$this->errorDSP("조건절이 없이 수행할수 없습니다." . __LINE__, E_USER_ERROR);
			return false;
		}

		if (!is_array($aData)) {
			$this->errorDSP("Array 형태의 데이타가 필요합니다." . __LINE__, E_USER_ERROR);
			return false;
		} elseif (count($aData) == 0) {
			$this->errorDSP("조건절이 없이 수행할수 없습니다." . __LINE__, E_USER_ERROR);
			return false;
		} else {
			$dataSet = array();
			$aData = self::magic_quotes_gpc($aData);
			foreach ($aData as $key => $val) {
				$dataSet[] = sprintf("`%s`='%s'", $key, $val);
			}
			$sql = sprintf("update %s set %s where %s", $table, join(",", $dataSet), join(" AND ", $sWhere));
		}

		return $sql;
	}


	public static function magic_quotes_gpc($data)
	{
		if (is_array($data)) {
			foreach ($data as $num => $val) {
				$data[$num] = addslashes($val);
			}
		} else {
			$data = addslashes($data);
		}
		return $data;
	}




	/**
	 * multi Insert Query문을 만들어줍니다.
	 *
	 */
	public function getMultiInsertQuery($table, $aDataAll)
	{
		if (empty($aDataAll[0]) || count($aDataAll[0]) == 0) {
			$this->errorDSP("데이터 없이 수행할수 없습니다." . __LINE__, E_USER_ERROR);
			return false;
		} else if ($this->array_deep($aDataAll) < 2) {
			$this->errorDSP("2차원 배열이 필요합니다." . __LINE__, E_USER_ERROR);
			return false;
		} else {

			$keys = array_keys($aDataAll[0]);
			$values = null;
			foreach ($aDataAll as $_aDataRow) {
				$values[] = "('" . implode("','", array_values($_aDataRow)) . "')";
			}

			$sql = 'INSERT INTO ' . $table . '(`' . implode('`,`', $keys) . '`) VALUES
					' . implode('
				,', $values);
		}

		return $sql;
	}


	/**
	 * multi Insert ignore Query문을 만들어줍니다.
	 *
	 */
	public function getMultiInsertIgnoreQuery($table, $aDataAll)
	{
		if (empty($aDataAll[0]) || count($aDataAll[0]) == 0) {
			$this->errorDSP("데이터 없이 수행할수 없습니다." . __LINE__, E_USER_ERROR);
			return false;
		} else if ($this->array_deep($aDataAll) < 2) {
			$this->errorDSP("2차원 배열이 필요합니다." . __LINE__, E_USER_ERROR);
			return false;
		} else {

			$keys = array_keys($aDataAll[0]);
			$values = null;
			foreach ($aDataAll as $_aDataRow) {
				$values[] = "('" . implode("','", array_values($_aDataRow)) . "')";
			}

			$sql = 'INSERT IGNORE INTO ' . $table . '(`' . implode('`,`', $keys) . '`) VALUES
					' . implode('
				,', $values);
		}

		return $sql;
	}



	/**
	 * 해당배열이 몇차원 배열인지 리턴
	 * @param unknown $arr
	 * @param number $deep
	 * @return number|mixed
	 */
	private function array_deep(&$arr, $deep = 0)
	{
		if (!is_array($arr)) {
			return $deep;
		}
		$deep++;
		foreach ($arr as $key => $value) {
			$deeps[] = $this->array_deep($arr[$key], $deep);
		}
		return max($deeps);
	}



	private function errorDSP($str = "", $type = E_USER_ERROR)
	{
		throw new Exception($str);
	}



	protected function connect_sqlite($db_file)
	{
		$dir = "sqlite:{$db_file}";
		$db_conn  = new PDO($dir, '', '') or die("cannot open the database");

		return $db_conn;
	}
	/*  private static function checkValue($value){

        //예외처리
        if($value == "null" || strtolower($value) == "now()"){
            return $value;
        }


        switch (strtolower(gettype($value))){
            case 'string':
                settype($value, 'string');
                $value = "'".mysql_real_escape_string($value)."'";
                break;
            case 'integer':
                settype($value, 'integer');
                break;
            case 'double' || 'float':
                settype($value, 'float');
                break;
            case 'boolean':
                settype($value, 'boolean');
                break;
            case 'array':
                $value = "'".mysql_real_escape_string($value)."'";
                break;
        }

        return $value;
    }

     */
}
