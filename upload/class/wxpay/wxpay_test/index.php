<?php
include 'Weixin/WxPay.Api.php';
include 'Weixin/WxPay.Notify.php';
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



$cartInfo = $_POST['cartInfo'];

file_put_contents('wxpay_log.txt', $_POST['cartInfo'] .date('Y-m-d H:i:s') . "\n", FILE_APPEND);
if(!empty($cartInfo)){  
	//file_put_contents('test444444.txt', $cartInfo .date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    $cartInfo = $_POST['cartInfo'];
    $arr = json_decode($cartInfo,true);
    $cart = $arr['cart'];
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
	$total_fee = $total_fee * 100;
	//$total_fee  = 1;
}else {
    $total_fee  = 1;
}
//file_put_contents('test3.txt', $total_fee.date('Y-m-d H:i:s') . "\n", FILE_APPEND);
$wx = new WxPayUnifiedOrder();

$wx->SetOut_trade_no(time());
$wx->SetTotal_fee($total_fee);
$wx->SetTime_expire(date('YmdHis', time()+600));
$wx->SetTrade_type('APP');
$wx->SetBody($body);
$wx->SetAttach($cartInfo);

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