<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PHP Ajax Library
 * jQuery의 $.ajax()와 유사한 인터페이스를 제공하는 CURL 라이브러리
 * 
 * 사용 예시:
 * $this->load->library('php_ajax');
 * 
 * // GET 요청
 * $result = $this->php_ajax->get('https://api.example.com/data');
 * 
 * // POST 요청
 * $result = $this->php_ajax->post('https://api.example.com/data', ['key' => 'value']);
 * 
 * // ajax() 메서드로 모든 옵션 설정
 * $result = $this->php_ajax->ajax([
 *     'url' => 'https://api.example.com/data',
 *     'method' => 'POST',
 *     'data' => ['key' => 'value'],
 *     'headers' => ['Authorization' => 'Bearer token'],
 *     'dataType' => 'json',
 *     'timeout' => 30,
 *     'success' => function($response) { ... },
 *     'error' => function($error) { ... }
 * ]);
 */
class Php_ajax
{
    protected $defaultOptions = [
        'method' => 'GET',
        'data' => [],
        'headers' => [],
        'dataType' => 'json', // json, text, xml
        'timeout' => 30,
        'success' => null,
        'error' => null,
        'contentType' => 'application/json', // application/json, application/x-www-form-urlencoded, multipart/form-data
    ];

    protected $lastError = null;
    protected $lastResponse = null;
    protected $lastHttpCode = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        // 기본 설정
    }

    /**
     * GET 요청
     * 
     * @param string $url 요청 URL
     * @param array $data 쿼리 파라미터
     * @param array $options 추가 옵션
     * @return mixed
     */
    public function get($url, $data = [], $options = [])
    {
        $options['method'] = 'GET';
        $options['data'] = $data;
        return $this->ajax(array_merge($options, ['url' => $url]));
    }

    /**
     * POST 요청
     * 
     * @param string $url 요청 URL
     * @param array $data POST 데이터
     * @param array $options 추가 옵션
     * @return mixed
     */
    public function post($url, $data = [], $options = [])
    {
        $options['method'] = 'POST';
        $options['data'] = $data;
        return $this->ajax(array_merge($options, ['url' => $url]));
    }

    /**
     * PUT 요청
     * 
     * @param string $url 요청 URL
     * @param array $data PUT 데이터
     * @param array $options 추가 옵션
     * @return mixed
     */
    public function put($url, $data = [], $options = [])
    {
        $options['method'] = 'PUT';
        $options['data'] = $data;
        return $this->ajax(array_merge($options, ['url' => $url]));
    }

    /**
     * DELETE 요청
     * 
     * @param string $url 요청 URL
     * @param array $data DELETE 데이터
     * @param array $options 추가 옵션
     * @return mixed
     */
    public function delete($url, $data = [], $options = [])
    {
        $options['method'] = 'DELETE';
        $options['data'] = $data;
        return $this->ajax(array_merge($options, ['url' => $url]));
    }

    /**
     * Ajax 요청 (jQuery $.ajax()와 유사한 인터페이스)
     * 
     * @param array $options 요청 옵션
     *   - url: 요청 URL (필수)
     *   - method: HTTP 메서드 (GET, POST, PUT, DELETE 등)
     *   - data: 전송할 데이터
     *   - headers: 추가 헤더
     *   - dataType: 응답 데이터 타입 (json, text, xml)
     *   - timeout: 타임아웃 (초)
     *   - contentType: Content-Type 헤더
     *   - success: 성공 콜백 함수
     *   - error: 실패 콜백 함수
     * @return mixed
     */
    public function ajax($options = [])
    {
        // 필수 파라미터 체크
        if (empty($options['url'])) {
            $this->lastError = 'URL is required';
            if (isset($options['error']) && is_callable($options['error'])) {
                call_user_func($options['error'], $this->lastError);
            }
            return false;
        }

        // 옵션 병합
        $config = array_merge($this->defaultOptions, $options);
        $url = $config['url'];
        $method = strtoupper($config['method']);
        $data = $config['data'];
        $dataType = strtolower($config['dataType']);
        $timeout = (int)$config['timeout'];
        $contentType = $config['contentType'];

        // 헤더 설정
        $headers = [];

        // Content-Type 설정
        if ($method !== 'GET') {
            if ($contentType === 'application/json') {
                $headers[] = 'Content-Type: application/json';
            } elseif ($contentType === 'application/x-www-form-urlencoded') {
                $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            } elseif ($contentType === 'multipart/form-data') {
                // multipart/form-data는 boundary를 자동으로 설정해야 하므로 Content-Type 헤더를 설정하지 않음
            }
        }

        // 사용자 정의 헤더 추가
        if (!empty($config['headers']) && is_array($config['headers'])) {
            foreach ($config['headers'] as $key => $value) {
                if (is_numeric($key)) {
                    // 배열 형태: ['Header-Name: value']
                    $headers[] = $value;
                } else {
                    // 연관 배열 형태: ['Header-Name' => 'value']
                    $headers[] = $key . ': ' . $value;
                }
            }
        }

        // CURL 초기화
        $ch = curl_init();

        // GET 방식일 경우 쿼리스트링으로 데이터 추가
        if ($method === 'GET' && !empty($data)) {
            $url .= (strpos($url, '?') !== false ? '&' : '?') . http_build_query($data);
        }

        // CURL 옵션 설정
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // 헤더 설정
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        // POST, PUT, DELETE 등은 바디에 데이터 전송
        if ($method !== 'GET') {
            if ($contentType === 'application/json') {
                $postData = json_encode($data);
            } elseif ($contentType === 'application/x-www-form-urlencoded') {
                $postData = http_build_query($data);
            } elseif ($contentType === 'multipart/form-data') {
                $postData = $data; // CURLFile 객체 등을 포함할 수 있음
            } else {
                $postData = is_string($data) ? $data : json_encode($data);
            }

            if ($method === 'POST') {
                curl_setopt($ch, CURLOPT_POST, true);
            } else {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            }

            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }

        // 요청 실행
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        $curlErrno = curl_errno($ch);

        // 에러 처리
        if ($curlError || $curlErrno) {
            $this->lastError = [
                'message' => $curlError,
                'code' => $curlErrno,
                'http_code' => $httpCode
            ];
            curl_close($ch);

            if (isset($config['error']) && is_callable($config['error'])) {
                call_user_func($config['error'], $this->lastError);
            }

            return false;
        }

        curl_close($ch);

        // HTTP 상태 코드 체크
        if ($httpCode >= 400) {
            $this->lastError = [
                'message' => 'HTTP Error: ' . $httpCode,
                'code' => $httpCode,
                'http_code' => $httpCode,
                'response' => $response
            ];

            if (isset($config['error']) && is_callable($config['error'])) {
                call_user_func($config['error'], $this->lastError);
            }

            $this->lastResponse = $response;
            $this->lastHttpCode = $httpCode;
            return false;
        }

        // 응답 데이터 파싱
        $parsedResponse = null;

        if ($dataType === 'json') {
            $parsedResponse = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // JSON 파싱 실패 시 원본 텍스트 반환
                $parsedResponse = $response;
            }
        } elseif ($dataType === 'xml') {
            $parsedResponse = simplexml_load_string($response);
            if ($parsedResponse === false) {
                $parsedResponse = $response;
            }
        } else {
            $parsedResponse = $response;
        }

        $this->lastResponse = $parsedResponse;
        $this->lastHttpCode = $httpCode;
        $this->lastError = null;

        // 성공 콜백 실행
        if (isset($config['success']) && is_callable($config['success'])) {
            call_user_func($config['success'], $parsedResponse, $httpCode);
        }

        return $parsedResponse;
    }

    /**
     * 마지막 에러 반환
     * 
     * @return mixed
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * 마지막 응답 반환
     * 
     * @return mixed
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * 마지막 HTTP 상태 코드 반환
     * 
     * @return int
     */
    public function getLastHttpCode()
    {
        return $this->lastHttpCode;
    }

    /**
     * 요청이 성공했는지 확인
     * 
     * @return bool
     */
    public function isSuccess()
    {
        return $this->lastError === null && $this->lastHttpCode !== null && $this->lastHttpCode < 400;
    }
}
