<?php
/**
 * app下单例子
 */
//require '/extra/load.php';
require 'load.php';
$out_trade_no= get_order_sn();
$total_fee=0.01;
$body='测试';
$unifiedOrder = new sugao_wxpay\WxpayData\WxPayUnifiedOrder();
    $unifiedOrder->SetBody($body); //商品或支付单简要描述
    $unifiedOrder->SetOut_trade_no($out_trade_no);
    $unifiedOrder->SetTotal_fee($total_fee*100);
    $unifiedOrder->SetTrade_type("APP");
   $unifiedOrder->SetNotify_url('http://' . $_SERVER['HTTP_HOST'] . '/weixinpl/app/pay/sugao_wxpay/notify/notify.php');
    $result = sugao_wxpay\WxPayApi::unifiedOrder($unifiedOrder);
    if (is_array($result)) {
        echo json_encode($result);
    }

