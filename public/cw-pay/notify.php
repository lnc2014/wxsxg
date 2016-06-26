<?php
/**
 * Author:LNC
 * Description: 支付成功微信回调地址
 * Date: 2016/5/31 0031
 * Time: 下午 2:36
 */

/**********************************************************
 *<xml>
    <appid><![CDATA[wx2421b1c4370ec43b]]></appid>
    <attach><![CDATA[支付测试]]></attach>
    <bank_type><![CDATA[CFT]]></bank_type>
    <fee_type><![CDATA[CNY]]></fee_type>
    <is_subscribe><![CDATA[Y]]></is_subscribe>
    <mch_id><![CDATA[10000100]]></mch_id>
    <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
    <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
    <out_trade_no><![CDATA[1409811653]]></out_trade_no>
    <result_code><![CDATA[SUCCESS]]></result_code>
    <return_code><![CDATA[SUCCESS]]></return_code>
    <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
    <sub_mch_id><![CDATA[10000100]]></sub_mch_id>
    <time_end><![CDATA[20140903131540]]></time_end>
    <total_fee>1</total_fee>
    <trade_type><![CDATA[JSAPI]]></trade_type>
    <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
</xml>
 * ********************************************************
 */
//微信回调返回的数据格式
include_once 'log_.php';
$log_ = new Log_();

$xml = file_get_contents("php://input");
$data = xmlToArray($xml);
$log_name = "./log/buy_notify_url_".gmdate('Y-m-d', time() + 3600 * 8).".log";
echo 'SUCCESS';
if ($data["return_code"] == "FAIL") {
    //此处应该更新一下订单状态，商户自行增删操作

    $log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
}elseif ($data["result_code"] == "FAIL") {
    //此处应该更新一下订单状态，商户自行增删操作
    $log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
}else{
    $out_trade_no = $data["out_trade_no"];
    $total_fee = $data['total_fee'];
    $appid = $data['appid'];
    $trade_no = $data['transaction_id'];    // 微信支付订单号
    $attach = $data['attach'];    // 用户订单编号
    $log_name = "./log/cw_notify_url_".gmdate('Y-m-d-H', time() + 3600 * 8).".log";//log文件路径
    $log_->log_result($log_name,"【接收消息】接收到的notify通知 : \n".$xml."\n");

    $host = "http://".$_SERVER['HTTP_HOST']."/";
    $url = $host."user/notify";
    $data = array(
        'attach' => $attach,
        'wx_order_str' => $trade_no,
        'out_trade_no' => $out_trade_no,
        'total_fee' => $total_fee,
    );
    $result = https_post($url,$data);
    //用户支付成功，发送微信，发送短信在处理完回调在发


}
/**
 * 	作用：将xml转为array
 */
function xmlToArray($xml)
{
    //将XML转为array
    $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    return $array_data;
}

function https_post($url,$data){
    $curl=curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($curl,CURLOPT_POST,1);
    curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    $result=curl_exec($curl);

    curl_close($curl);
    return $result;
}
