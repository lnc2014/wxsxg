<?php
include 'Weixin/WxPay.Api.php';
include 'Weixin/WxPay.Notify.php';
require_once("../Pay.php");
require_once("../User.php");
require_once("../ApiFunc.php");
header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL & ~E_NOTICE);
defined('WX_APP_ID') ?: define('WX_APP_ID', 'wxb6a6a99c6a271a30');
defined('WX_MCH_ID') ?: define('WX_MCH_ID', '1295719801');
defined('WX_KEY') ?: define('WX_KEY', '900A261DC5D62DA3D6A21A13283B8E50');
defined('WX_APP_SECRET') ?: define('WX_APP_SECRET', 'd4624c36b6795d1d99dcf0547af5443d');
defined('WX_NOTIFY_URL') ?: define('WX_NOTIFY_URL', 'http://www.china1yyg.com/api/class/wxpay/notify_url.php');

class WxSign extends WxPayDataBase
{
    public function sign($data)
    {
        $this->values = $data;
        return $this->MakeSign();
    }
}

//增加微信支付和余额支付
$func = new ApiFunc();
$pay = new pay();
$cartInfo = $_POST['cartInfo'];
$paytype = $_POST['paytype'];
//$cartInfo = '{"cart":[{"goodsId":"13","money":"1","num":"1"}],"type":"pay","uid":"10","paytype":"wxaccount"}';

file_put_contents('wxpay_log.txt', $_POST['cartInfo'] .date('Y-m-d H:i:s') . "\n", FILE_APPEND);
if(!empty($cartInfo)){  
	//file_put_contents('test444444.txt', $cartInfo .date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    $cartInfo = $_POST['cartInfo'];
    $arr = json_decode($cartInfo,true);
    
    $pay_type = $paytype;//支付类型
    
    $cart = $arr['cart'];
    $uid = $arr['uid'];
    $total_fee = '';
    if( $arr['type']== 'charge'){
        $total_fee = $arr['money'];
        $body = '有鱼网充值';
    }elseif ($arr['type']== 'pay'){
        foreach ($cart as $v){
            $total_fee = $total_fee + $v['money'];
        }
        $body = '有鱼网商品购买';
    }
    
    if ($pay_type== 'wxaccount'){
        $user = new user();
        $userInfo = $user->findUserByUid($uid);
        $userMoney = $userInfo['money'];
//         $money = 0;
//         $pay->payByAccount($uid, $money);//将用户的钱置0
        $total_fee = $total_fee - $userMoney;
        $total_fee = $total_fee * 100;
    }else{
        $total_fee = $total_fee * 100;
    } 
    
	//$total_fee  = 1;
}else {
    $total_fee  = 1;
}
//因为微信支付长度的限制,所以将客户端的信息存入数据库中,作为一个唯一的标志
$wx_trade = $func->generateInviteCode(32);
$cart = $pay->addCartInfoByWx($cartInfo, $wx_trade,$pay_type);

if(!$cart){
    file_put_contents('wx_cart_info.txt', $cartInfo.date('Y-m-d H:i:s') . "\n", FILE_APPEND);
}
//file_put_contents('test3.txt', $total_fee.date('Y-m-d H:i:s') . "\n", FILE_APPEND);
$wx = new WxPayUnifiedOrder();

$wx->SetOut_trade_no(time());
$wx->SetTotal_fee($total_fee);
$wx->SetTime_expire(date('YmdHis', time()+600));
$wx->SetTrade_type('APP');
$wx->SetBody($body);
$wx->SetAttach($wx_trade);

$return = WxPayApi::unifiedOrder($wx);

if ($return['result_code'] == 'FAIL')
{
    exit('微信订单异常');
}

// 二次签名
$data = array(
    'appid'     => $return['appid'],
    'noncestr'  => $return['nonce_str'],
    'package'   => 'Sign=WXPay',
    'partnerid' => $return['mch_id'],
    'prepayid'  => $return['prepay_id'],
    'timestamp' => time(),
);

$data['sign'] = (new WxSign())->sign($data);

$return = array(
    'appId'     => $data['appid'],
    'partnerId' => $data['partnerid'],
    'prepayId'  => $data['prepayid'],
    'nonceStr'  => $data['noncestr'],
    'timeStamp' => $data['timestamp'],
    'package'   => $data['package'],
    'sign'      => $data['sign'],
    'return_code'   => $return['result_code'],
    'return_msg'    => $return['return_msg']

);
exit(json_encode($return));