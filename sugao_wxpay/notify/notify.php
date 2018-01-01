<?php

/**
 * 支付回调
 */

namespace sugao_wxpay;
use sugao_wxpay\WxpayData\WxPayOrderQuery;
require_once dirname(__DIR__) . '/load.php';
//这是回调的返回值
$xml = file_get_contents('php://input');
my_error_log(array('1' => '1', 'xml' => $xml), 'notify_log', 'notify');

class PayNotifyCallBack extends WxPayNotify {

    //查询订单
    public function Queryorder($transaction_id) {
        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        if (array_key_exists("return_code", $result) && array_key_exists("result_code", $result) && $result["return_code"] == "SUCCESS" && $result["result_code"] == "SUCCESS") {
            return true;
        }
        return false;
    }

    //重写回调处理函数
     //一定要返回true,false,不然它间隔15/15/30/180/1800/1800/1800/1800/3600秒一直请求
    public function NotifyProcess($data, &$msg) {

        $notfiyOutput = array();
        my_error_log(array('data' => $data, 'sign' => 'notifyprogress'), 'notify_log', 'notify');
        if (!array_key_exists("transaction_id", $data)) {
            $msg = "输入参数不正确";
            my_error_log(array('sign' => $msg), 'notify_log', 'notify');
            return false;
        }
        //查询订单，判断订单真实性
        if (!$this->Queryorder($data["transaction_id"])) {
            $msg = "订单查询失败";
            my_error_log(array('sign' => $msg), 'notify_log', 'notify');
            return false;
        }
        //自己的一些处理
        require_once __DIR__.'/notify_operate.php';
        if ($data['result_code'] == 'SUCCESS' && $data['return_code'] == 'SUCCESS') {
            $rmsg = notify_operate($data['out_trade_no']);
            my_error_log(array('msg' => $rmsg, 'out_trade_no' => $data['out_trade_no']), 'notify_log', 'notify');
            return true;
        } else {
            $rmsg = '交易失败';
            my_error_log(array('msg' => $rmsg, 'out_trade_no' => $data['out_trade_no']), 'notify_log', 'notify');
            return false;
        }
        return true;
    }

}

$notify = new PayNotifyCallBack();
$notify->Handle(false);

