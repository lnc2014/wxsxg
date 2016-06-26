<?php
session_start();
require_once "./lib/WxPayException.php";
require_once "./lib/WxPayApi.php";
require_once "./lib/WxPayConfig.php";
require_once "./lib/WxPayJsApiPay.class.php";

$tools = new JsApiPay();
if(isset($_SESSION['jspayOpenId'])) {
    $openid = $_SESSION['jspayOpenId'];
} else {
    $openid = $tools->GetOpenid();
    $_SESSION['jspayOpenId'] = $openid;
    setcookie("openid", $openid);
    $_COOKIE["openid"] = $openid;
}

$price = empty($_REQUEST['price'])?1:$_REQUEST['price'];
$user_id = empty($_REQUEST['user_id'])?0:$_REQUEST['user_id'];
$order_num = empty($_REQUEST['order_num'])?1:$_REQUEST['order_num'];
$driver_id = empty($_REQUEST['driver_id'])?0:$_REQUEST['driver_id'];
$attach = $user_id.','.$order_num.','.$driver_id;//附加数据，支付用户ID和订单编号
$total_fee = $price * 100;
$input = new WxPayUnifiedOrder();

$input->SetBody('包车订单支付');
$input->SetAttach($attach);
$input->SetOut_trade_no('ltbcw'.date("YmdHis").rand(1000,9999));    //订单号

$input->SetOut_trade_no(date("YmdHis"));
//$input->SetTotal_fee($total_fee * 100);   //总费用
$input->SetTotal_fee($total_fee);   //总费用

$input->SetTime_start(date("YmdHis"));
//$input->SetTime_expire(date("YmdHis", time() + 1200));
$input->SetNotify_url(WxPayConfig::NOTIFY_URL);   //支付回调地址，这里改成你自己的回调地址。
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openid);
$order = WxPayApi::unifiedOrder($input);
$jsApiParameters = $tools->GetJsApiParameters($order);

?>