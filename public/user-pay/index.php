<?php
session_start();
require_once "./lib/WxPayException.php";
require_once "./lib/WxPayApi.php";
require_once "./lib/WxPayConfig.production.php";
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

$price = empty($_REQUEST['price'])?0.01:$_REQUEST['price'];
$user_id = empty($_REQUEST['user_id'])?0:$_REQUEST['user_id'];
$order_num = empty($_REQUEST['order_num'])?1:$_REQUEST['order_num'];
$attach = $user_id.','.$order_num;//附加数据，支付用户ID和订单编号
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

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <title>微信安全支付</title>
	</head>
<style type="text/css">
        body{padding: 0;margin:0;background-color:#eeeeee;font-family: '黑体';}
        .pay-main{background-color: #4cb131;padding-top: 20px;padding-left: 20px;padding-bottom: 20px;}
        .pay-main img{margin: 0 auto;display: block;}
        .pay-main .lines{margin: 0 auto;text-align: center;color:#cae8c2;font-size:12pt;margin-top: 10px;}
        .tips .img{margin: 20px;}
        .tips .img img{width:20px;}
        .tips span{vertical-align: top;color:#ababab;line-height:18px;padding-left: 10px;padding-top:0px;}
        .action{background:#4cb131;padding: 10px 0;color:#ffffff;text-align: center;font-size:14pt;border-radius: 10px 10px; margin: 15px;}
        .action:focus{background:#4cb131;}
        .action.disabled{background-color:#aeaeae;}
        .footer{position: absolute;bottom:0;left:0;right:0;text-align: center;padding-bottom: 20px;font-size:10pt;color:#aeaeae;}
        .footer .ct-if{margin-top:6px;font-size:8pt;}
    </style>
    <script type="text/javascript">
 
        //调用微信JS api 支付
        function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $jsApiParameters; ?>,
                function(res){
                    WeixinJSBridge.log(res.err_msg);
                    if (res.err_msg == 'get_brand_wcpay_request:ok') {
                        alert('支付成功');
                        location.href = "http://".$_SERVER['HTTP_HOST']."/user/order_list/8";
                    } else if (res.err_msg == 'get_brand_wcpay_request:cancel') {
                        alert('您已取消支付，支付失败！');
                        location.href = "http://".$_SERVER['HTTP_HOST']."/user/order_list/8";
                    } else if (res.err_msg == 'get_brand_wcpay_request:fail') {
                        alert('支付失败！');
                        location.href = "http://".$_SERVER['HTTP_HOST']."/user/order_list/8";
                    } else {
                        alert(res.err_code+res.err_desc+res.err_msg);
                    }

                    return;
                }
            );
        }

        function callpay()
        {
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
        window.onload = function() {
            callpay();
        }
    </script>
	<body>
<div align="container">
    <div class="pay-main">
        <img src="images/pay_logo.png">
        <div class="lines"><span>微信安全支付</span></div>
    </div>
    <div class="tips">
        <div class="img">
            <img src="images/pay_ok.png">
            <span>已开启支付安全</span>
        </div>
    </div>
    <div class="action" type="button" onclick="callpay()" >立即支付</div>
    <div class="footer"><div>支付安全由中国人民财产保险股份有限公司承保</div><div class="ct-if">服务热线：0768-8888808</div></div>
</div>
</body>

<!--<body>

    <br/>

    <font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px"> </span>元钱</b></font><br/><br/>

	<div align="center">

		<button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>

	</div>

</body>-->
</html>