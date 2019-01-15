<?php

class Client {

    public $url;

    public $token;

    protected $httpMethod = 'POST';

    public function __construct($url,$token)
    {
        $this->url = $url;

        $this->token = $token;      //私钥
    }

    /**
    * 发起请求
    * $params     业务级参数
    */
    public function execute ($query,$params) {
        
        // 准备query, headers, postData
        $query['timestamp'] = time();
        

        $headers['Pragma']        = 'no-cache';
        $headers['Cache-Control'] = 'no-cache';

        switch ($this->httpMethod) {

            case 'POST':
            case 'PUT':
                $postData = array_merge($params, $query);

                $headers['Content-Type']  = 'application/x-www-form-urlencoded';
            break;
        }

        // 生成数字签名
        $postData['sign'] = $this->generateSign($postData);

        // 发起请求 CURL/SOCKET
        return $this->curl($this->httpMethod, $this->url, $headers, $postData);

    }

    private function curl($http_method = 'GET', $url, $headers, $postData = null) {
        
        // 初始化一个cURL对象
        $curl = curl_init();

        // 设置你需要抓取的URL
        curl_setopt($curl, CURLOPT_URL, $url);

        // 设置是否返回header
        curl_setopt($curl, CURLOPT_HEADER, 0);

        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // 设置超时时间
        $time = 10;
        curl_setopt($curl, CURLOPT_TIMEOUT, $time);

        // 设置HTTP METHOD
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $http_method);

        // 设置UserAgent
        curl_setopt($curl, CURLOPT_USERAGENT, 'ERPSDK/PHP');

        // 设置ssl的版本
        curl_setopt($curl, CURLOPT_SSLVERSION, 3);

        // 添加Header
        $header_arr = array();
        foreach($headers as $key=>$value) {
            array_push($header_arr, "$key: $value");
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header_arr);

        // 添加postData
        if ($http_method == 'POST' || $http_method == 'PUT') {
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
        }

        // 运行cURL，发起请求
        $data = curl_exec($curl);

        // 遇到错误
        if ( curl_errno($curl) )
            throw new Exception( curl_error($curl) );

        // 关闭URL请求
        curl_close($curl);

        // 返回结果
        return json_decode($data,true);
    }


    public function generateSign($params)
    {
        return strtoupper(md5(strtoupper(md5($this->assemble($params))) . $this->token));
    }

    /**
     *
     * 签名参数组合函数
     * @param array $params
     */
    private function assemble($params)
    {
        if (!is_array($params)) {
            return null;
        }

        ksort($params, SORT_STRING);
        $sign = '';
        foreach ($params as $key => $val) {
            if (is_null($val)) {
                continue;
            }

            if (is_bool($val)) {
                $val = ($val) ? 1 : 0;
            }

            $sign .= $key . (is_array($val) ? $this->assemble($val) : $val);
        }
        return $sign;
    }
}

