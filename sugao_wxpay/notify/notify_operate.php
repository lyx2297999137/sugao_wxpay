<?php

/**
 * 支付成功操作
 * 
 */
require_once dirname(__DIR__).'/load.php';
require_once __DIR__.'/Mysql.php';
/**
 * 
 * @param type $out_trade_no订单号
 */
function notify_operate($out_trade_no){
$mysql=new Mysql();

//finnace_order
$finnace_order_sql="update finnace_order set paystatus=1 where oder_num='{$out_trade_no}'";
$result=$mysql->query($finnace_order_sql);
if($result){
    return '处理成功';
}else{
    return  '处理失败';
}
}

