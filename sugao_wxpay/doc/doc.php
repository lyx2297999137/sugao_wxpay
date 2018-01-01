<?php
/**
 * 创建time:2017年12月27日08:55:38
 * 参考

统一下单:https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=9_1
调起支付接口:https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=9_12&index=2
公众号支付php sdk:https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=11_1

 修改1:curl出错，错误码:60
WxPayApi.php:
//删	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
//删		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
                //sugao_add
		if(stripos($url,"https://")!==FALSE){
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }    else    {
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        } 
 
 http://www.wxpay12.com/unifiedorder.php
 说明：phplog可以参考sugaophp的phplog我就不重新弄过了
 */

