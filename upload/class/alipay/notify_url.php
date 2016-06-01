<?php

header("Content-type: text/html; charset=utf-8");
error_reporting(E_ALL | E_STRICT);
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php"); 
require_once("../Pay.php");
require_once("../User.php");   
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

$pay = new Pay();
$user = new User();  
//$verify_result = true;
if($verify_result) {//验证成功  签名验证，主要是用来对第三方截取数据的验证
	
	//商户订单号
	$out_trade_no = $_POST['out_trade_no']; 
	
	//交易通知的时间
	$notify_time = $_POST['notify_time']; 
	 
	//支付宝交易号

// 	buyer_email 买家支付宝账号
// 	buyer_id  买家支付宝用户号
	
	$body = $_POST['body'];//获得支付成功之后的相关的商品的信息，用于处理自己的业务
	 
	//$body = '{"cart":[{"goodsId":"438","money":"1","num":"1"}],"type":"pay","uid":"10"}';
	 
	$buyer_emali = $_POST['buyer_email'];
	$buyer_id = $_POST['buyer_id'];
	
	$trade_no = $_POST['trade_no'];
	//交易状态 
	$trade_status = $_POST['trade_status']; 
	//$trade_status ='TRADE_SUCCESS';
	
	$seller_id = $_POST['seller_id']; //卖家支付宝用户号
	$seller_email = $_POST['seller_email']; //卖家支付宝账号
	$buyer_id = $_POST['buyer_id']; //买家支付宝用户号
	$buyer_email = $_POST['buyer_email']; //买家支付宝用户号
	$total_fee = $_POST['total_fee']; //交易金额  
	
	file_put_contents('trade_status.txt', "交易状态为".$trade_status.'支付宝返回到服务器的时间为:'.date('Y-m-d H:i:s') . "\n", FILE_APPEND);
 
    if($trade_status == 'TRADE_FINISHED') { 
        //调试用，写文本函数记录程序运行情况是否正常 
        file_put_contents('finish.txt', "交易成功且结束,交易的支付宝账号为".$buyer_emali.'交易的订单号为：'.$trade_no.'交易的时间为:'.$notify_time . "\n", FILE_APPEND); 
    }
    else if ($trade_status == 'TRADE_SUCCESS') {
        
        file_put_contents('trade_success.txt', "交易成功,交易的支付宝账号为".$buyer_emali.'交易的订单号为：'.$trade_no.'交易的时间为:'.$notify_time . "\n", FILE_APPEND);
       
        $cartlist = json_decode($body,true);//将传递过来的数据解析成购物车的样子
        //var_dump($cartlist);exit;
        $uid = $cartlist['uid'];
        
        $trade_type = $cartlist['type'];
        
        
        $ali_pay_log = $pay->aliPayLog($uid, $trade_no, $trade_status, $seller_id, $seller_email, $buyer_id, $buyer_email, $total_fee, $trade_type);
        if(!$ali_pay_log){
            file_put_contents('ali_pay_error_log.txt', "支付宝日志写入失败".date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        } 
        
        if($cartlist['type']== 'pay'){
        
            $Cartlist = $cartlist['cart'];
        
            $userInfo = $user->findUserByUid($uid);
            $userMoney = $userInfo['money'];
//             $shoplist = $pay->cartGoodsList($Cartlist); 
            $shoplist = array();
            foreach($Cartlist as $key => $val){
                $key=intval($key);
                $shoplist[$key] = $pay->cartGoods($val['goodsId']);
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
			
			//增加一个余额支付的  
            if($MoenyCount !== $total_fee){
                $userInfo = $user->findUserByUid($uid);
                $money = 0;
                $pay->payByAccount($uid, $money);//将用户的钱置0
            }
			
            $buyRecrod = $pay->cartPay($shoplist, $userInfo);
            if($buyRecrod){
                
                file_put_contents('buy_recrod.txt', "购买记录写入成功".date('Y-m-d H:i:s'). "\n", FILE_APPEND); 
				
            }else{
                file_put_contents('buy_recrod_error.txt', "购买记录写入失败".date('Y-m-d H:i:s'). "\n", FILE_APPEND); 
            }
        }elseif ($cartlist['type']== 'charge'){ 
            $appAddMoney = $pay->addMoney($uid, $cartlist['money']);
            $appAddMoneyRecord = $pay->addMoneyRecord($uid, $cartlist['money']);
            if($appAddMoney && $appAddMoneyRecord){
                file_put_contents('charge_log.txt', "充值写入数据库成功".date('Y-m-d H:i:s'). "\n", FILE_APPEND); 
				 
            }else{
                file_put_contents('charge_log_error.txt', "充值写入数据库失败".date('Y-m-d H:i:s'). "\n", FILE_APPEND);
            }
             
        }        
         
    }
	echo "success";		//请不要修改或删除
}
else {
    file_put_contents('fail_log.txt', "验证失败，支付宝服务器返回的时间为：".date('Y-m-d H:i:s'). "\n", FILE_APPEND); 
    //验证失败
    echo "fail";
} 

?>
