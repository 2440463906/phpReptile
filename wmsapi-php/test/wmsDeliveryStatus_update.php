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
    'method'    => 'wms.delivery.status_update'
);


$params  = array(
    'delivery_bn' => '1901150000001',
    'status' => 'DELIVERY',
    'logi_no' => '11111111111ds',
    'item' => '[{"product_bn":"00000001","num":"1"}]'
);

















$Client = new Client($url,$token);
$data = $Client->execute($query,$params);
var_dump($data);