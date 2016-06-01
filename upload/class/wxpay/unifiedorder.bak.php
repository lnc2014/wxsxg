<?php
/**
* 文件说明：微信统一下单
* ==============================================
* 版权所有 @lnc 
* ==============================================
* @date: 2015年12月8日
* @author: admin
* @version:1.0
*/
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL & ~E_NOTICE);
require_once("lib/unifiedorder.class.php"); 
$appid = 'wxb6a6a99c6a271a30';


if(isset($_POST['total_fee'])){
    $total_fee = $_POST['total_fee'];
}


$notify_url = 'http://www.china1yyg.com/api/class/wxpay/notify_url.php';

$unifiedorder = new Unifiedorder();


$result = $unifiedorder->sendOrder($appid, $total_fee, $notify_url);

//再次签名
$sign_arr = array();

//$sign_arr['appid'] = $result['appid'];
$sign_arr['appId'] = $result['appid'];
$sign_arr['partnerId'] = '1295719801';
$sign_arr['prepayId'] = $result['prepay_id'];
$sign_arr['pay_sign_key'] = '900A261DC5D62DA3D6A21A13283B8E50';

$sign_arr['nonceStr'] = $result['nonce_str'];
$sign_arr['timeStamp'] = time(); 

$sign_arr['package'] = 'Sign=WXPay'; 

$sign = $unifiedorder->getSign($sign_arr);
 
$sign_arr['sign'] = $sign;

$sign_arr['return_code'] = $result['return_code'];
$sign_arr['return_msg'] = $result['return_msg'];
  
echo json_encode($sign_arr);
//echo '{"appId":"wxb6a6a99c6a271a30","partnerId":"1295719801","prepayId":"wx2015121821043808547542540573473149","pay_sign_key":"9e6a08805da7f33e075ca44dc94ec00be0147c65","nonceStr":"WVPK4pXJS6eUlQPu","timeStamp":1450443855,"package":"Sign=WXPay","sign":"158180B218C648EA33EAD867238CC9F4","return_code":"SUCCESS","return_msg":"OK"}';
