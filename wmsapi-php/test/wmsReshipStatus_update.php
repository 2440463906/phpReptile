<?php
header("content-type:text/html;charset=utf-8");
include_once('../Client.php');

//接口地址
$url = 'http://www.yunqi.com/index.php/openapi/process/handle';
//私钥
$token = '49fc477a8ee9747f5d9ba924a0ae8120';


$query   = array(
    //节点
    'node_id'   => 'o2754629045',
    //ERP接口方法
    'method'    => 'wms.reship.status_update'
);


$params  = array(
    'reship_bn' => '201901151413000790',
    'logi_no' => '1234567',
    'status' => 'FINISH',
    'item' => '[{"product_bn":"00000001","normal_num":"1","defective_num":"0"}]',
);

















$Client = new Client($url,$token);
$data = $Client->execute($query,$params);
var_dump($data);