<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 
 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */
error_reporting(E_ALL | E_STRICT);
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
require_once("../ApiFunc.php");
require_once("../Pay.php");
require_once("../User.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

$apiFunc = new ApiFunc();
$pay = new Pay();
$user = new User();
$verify_result  = true;
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号
     
// 	$out_trade_no = $_POST['out_trade_no'];
	$out_trade_no = $apiFunc->getParams('out_trade_no');
	
	
	$notify_time = $apiFunc->getParams('notify_time');//交易通知的时间
	//支付宝交易号

// 	buyer_email 买家支付宝账号
// 	buyer_id  买家支付宝用户号
	
	$body = $apiFunc->getParams('body');//获得支付成功之后的相关的商品的信息，用于处理自己的业务
	$body = '{ "uid": "10", "type": "charge", "money":"55"}';
	$buyer_emali = $apiFunc->getParams('buyer_email');
	$buyer_id = $apiFunc->getParams('buyer_id');
	
// 	$trade_no = $_POST['trade_no'];
 
	$trade_no = $apiFunc->getParams('trade_no');

	//交易状态
	$trade_status = $apiFunc->getParams('trade_status');
// 	$trade_status = $_POST['trade_status'];

	/*
	 * 交易状态码
	WAIT_BUYER_PAY	交易创建，等待买家付款。
	TRADE_CLOSED	在指定时间段内未支付时关闭的交易；
	在交易完成全额退款成功时关闭的交易。
	TRADE_SUCCESS	交易成功，且可对该交易做操作，如：多级分润、退款等。
	TRADE_FINISHED	交易成功且结束，即不可再做任何操作。
    */
    if($trade_status == 'TRADE_FINISHED') { 
        //调试用，写文本函数记录程序运行情况是否正常
        file_put_contents('success.txt', "交易成功且结束,交易的支付宝账号为".$buyer_emali.'交易的订单号为：'.$trade_no.'交易的时间为:'.$notify_time . "\n", FILE_APPEND);
        //logResult("交易成功且结束,交易的支付宝账号为".$buyer_emali.'交易的订单号为：'.$trade_no.'交易的时间为:'.$notify_time); 
    }
    else if ($trade_status == 'TRADE_SUCCESS') {
        file_put_contents('success2.txt', "交易成功且结束,交易的支付宝账号为".$buyer_emali.'交易的订单号为：'.$trade_no.'交易的时间为:'.$notify_time . "\n", FILE_APPEND);
        //logResult("交易成功且结束,交易的支付宝账号为".$buyer_emali.'交易的订单号为：'.$trade_no);
    }
    
    $cartlist = json_decode($body,true);//将传递过来的数据解析成购物车的样子
    file_put_contents('cartlist.txt', "传递过来的数据".$cartlist. "\n", FILE_APPEND);
    
    if($cartlist['type']== 'pay'){
        $uid = $cartlist['uid'];
        $Cartlist = $cartlist['cart'];
        
        $userInfo = $user->findUserByUid($uid);
        $userMoney = $userInfo['money'];
        $shoplist = $pay->cartGoodsList($Cartlist);
        
        foreach($Cartlist as $key => $val){
            $key=intval($key);
            if(isset($shoplist[$key]) && $shoplist[$key]['shenyurenshu'] != 0){
                if(($shoplist[$key]['xsjx_time'] != '0') && $shoplist[$key]['xsjx_time'] < time()){
                    unset($shoplist[$key]);
                    $shopguoqi = 1;
                    continue;
                }
                $shoplist[$key]['cart_gorenci']=$val['num'] ? $val['num'] : 1;
                if($shoplist[$key]['cart_gorenci'] >= $shoplist[$key]['shenyurenshu']){//购物车的数量大于剩余人数的话，就默认为剩余人数
                    $shoplist[$key]['cart_gorenci'] = $shoplist[$key]['shenyurenshu'];
                }
                $MoenyCount+=$shoplist[$key]['yunjiage']*$shoplist[$key]['cart_gorenci'];//支付的价格
                $shoplist[$key]['cart_xiaoji']=substr(sprintf("%.3f",$shoplist[$key]['yunjiage'] * $shoplist[$key]['cart_gorenci']),0,-1);
                $shoplist[$key]['cart_shenyu']=$shoplist[$key]['zongrenshu']-$shoplist[$key]['canyurenshu'];
            }else{
                unset($shoplist[$key]);
            }
        }
        
        file_put_contents('shoplist.txt', "传递过来的数据".$shoplist. "\n", FILE_APPEND);
        file_put_contents('userInfo.txt', "传递过来的数据".$userInfo. "\n", FILE_APPEND);
        $buyRecrod = $pay->cartPay($shoplist, $userInfo);
        file_put_contents('buyRecrod.txt', "传递过来的数据".$userInfo. "\n", FILE_APPEND);
        if($buyRecrod){
          logResult("购买记录写入成功".date('Y-m-d H:i:s'));
           }else{
           logResult("购买记录写入失败".date('Y-m-d H:i:s'));
          } 
    }elseif ($cartlist['type']== 'charge'){
        
        $appAddMoney = $pay->addMoney($uid, $cartlist['money']);
        $appAddMoneyRecord = $pay->addMoneyRecord($uid, $cartlist['money']);
        if($appAddMoney && $appAddMoneyRecord){
            logResult("充值写入数据库成功".date('Y-m-d H:i:s').'\n');
        }else{
            logResult("充值写入数据库失败".date('Y-m-d H:i:s').'\n');
        }
         
    }
    
 
    
     
/** ====================================
     必须保证服务器异步通知页面（notify_url）上无任何字符，如空格、HTML标签、开发系统自带抛出的异常提示信息等；
     支付宝是用POST方式发送通知信息，因此该页面中获取参数的方式，如：
     request.Form(“out_trade_no”)、$_POST[‘out_trade_no’]；
     支付宝主动发起通知，该方式才会被启用；
     服务器间的交互，不像页面跳转同步通知可以在页面上显示出来，这种交互方式是不可见的；
     程序执行完后必须打印输出“success”（不包含引号）。如果商户反馈给支付宝的字符不是success这7个字符，支付宝服务器会不断重发通知，直到超过24小时22分钟。
     一般情况下，25小时以内完成8次通知（通知的间隔频率一般是：2m,10m,10m,1h,2h,6h,15h）；
     程序执行完成后，该页面不能执行页面跳转。如果执行页面跳转，支付宝会收不到success字符，会被支付宝服务器判定为该页面程序运行出现异常，而重发处理结果通知；
     cookies、session等在此页面会失效，即无法获取这些数据；
     该方式的调试与运行必须在服务器上，即互联网上能访问；
     该方式的作用主要防止订单丢失，即页面跳转同步通知没有处理订单更新，它则去处理；
     当商户收到服务器异步通知并打印出success时，服务器异步通知参数notify_id才会失效。也就是说在支付宝发送同一条异步通知时（包含商户并未成功打印出success导致支付宝重发数次通知），服务器异步通知参数notify_id是不变的。
     ====================================
     */
    echo "success";		//请不要修改或删除
}
else {
    logResult("交易失败,交易的支付宝账号为".$buyer_emali.'交易的订单号为：'.$trade_no.'交易的时间为:'.$notify_time);  
    //验证失败
    echo "fail";
} 

?>