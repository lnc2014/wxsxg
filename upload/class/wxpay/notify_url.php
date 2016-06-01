<?php
/* *
 * 功能：微信服务器异步通知页面
 * 
 */
error_reporting(E_ALL | E_STRICT);
require_once("../Pay.php");
require_once("../User.php");   
require_once("lib/unifiedorder.class.php");   
//计算得出通知验证结果

$pay = new Pay();
$user = new User();

//$sign = new Unifiedorder();

$xml = file_get_contents("php://input");//PHP接收xml文件，是从微信服务器端传输过来的

file_put_contents('wx_pay_get_log.txt', $xml.date('Y-m-d H:i:s') . "\n", FILE_APPEND);

$responseObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);

//file_put_contents('wx_pay2.txt', $responseObj.date('Y-m-d H:i:s') . "\n", FILE_APPEND);

$responseArray = (array)$responseObj;//强制转换成数组

//file_put_contents('wx_pay3.txt', $responseArray['return_code'].date('Y-m-d H:i:s') . "\n", FILE_APPEND);

if($responseArray['return_code'] == 'SUCCESS') {//验证成功  签名验证，主要是用来对第三方截取数据的验证
   
	//交易通知的时间
 
    $wx_trade = $responseArray['attach'];
    $appid = $responseArray['appid'];
    $mch_id = $responseArray['mch_id'];
    $bank_type = $responseArray['bank_type'];
    $noce_str = $responseArray['nonce_str'];
    $result_code = $responseArray['result_code'];
    $openid = $responseArray['openid'];
    $total_fee = $responseArray['total_fee'];
    $trade_type = $responseArray['trade_type'];
    $transaction_id = $responseArray['transaction_id'];//商户订单号  唯一标识，用来作为不重复处理逻辑代码
    $time_end = $responseArray['time_end'];
    $out_trade_no = $responseArray['out_trade_no']; 
    $sign = $responseArray['sign'];
    //$body = '{"cart":[{"goodsId":"13","money":"1","num":"1"}],"type":"pay","uid":"10"}';
    
    $is_first = $pay->findIfWxPay($transaction_id);
    file_put_contents('wx_pay_last.txt', $wx_trade .date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    if($is_first){
        echo 'SUCCESS';
		
    }else{

        $cash_fee = '';//暂时为空
        
        $cart_info = $pay->findCartInfo($wx_trade);//找出微信支付购物车的相关信息
        $body = $cart_info['cart_info'];
        if(empty($body)){
            file_put_contents('wx_pay_error_cart_log.txt', "微信支付日志写入失败,失败原因,cartInfo没有数据".date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        } 
        $cartlist = json_decode($body,true);//将传递过来的数据解析成购物车的样子
        
        $uid = $cartlist['uid'];
        
        $trade_type = $cartlist['type'];
        
        
        //增加一个余额支付的
        $pay_type = $cart_info['pay_type'];
        
        if($pay_type == 'wxaccount'){
            $userInfo = $user->findUserByUid($uid);
            $userMoney = $userInfo['money'];
            $money = 0;
            $pay->payByAccount($uid, $money);//将用户的钱置0
        }
        
        $wx_pay_log = $pay->wxPayLog($uid, $appid, $mch_id, $noce_str, $sign, $result_code, $openid, $trade_type, $bank_type, $total_fee, $cash_fee, $time_end, $transaction_id,$out_trade_no);
        
        if(!$wx_pay_log){
            file_put_contents('wx_pay_error_log.txt', "微信支付日志写入失败".date('Y-m-d H:i:s') . "\n", FILE_APPEND);
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
            $buyRecrod = $pay->cartPay($shoplist, $userInfo);
            if($buyRecrod){
        
                file_put_contents('buy_recrod.txt', "购买记录写入成功".date('Y-m-d H:i:s'). "\n", FILE_APPEND);
                echo "SUCCESS";		//请不要修改或删除
            }else{
                file_put_contents('buy_recrod_error.txt', "购买记录写入失败".date('Y-m-d H:i:s'). "\n", FILE_APPEND);
            }
        }elseif ($cartlist['type']== 'charge'){
            $appAddMoney = $pay->addMoney($uid, $cartlist['money']);
            $appAddMoneyRecord = $pay->addWxMoneyRecord($uid, $cartlist['money']);
            if($appAddMoney && $appAddMoneyRecord){
                file_put_contents('charge_log.txt', "充值写入数据库成功".date('Y-m-d H:i:s'). "\n", FILE_APPEND);
                //                 logResult("充值写入数据库成功".date('Y-m-d H:i:s').'\n');
                echo "SUCCESS";		//请不要修改或删除
            }else{
                file_put_contents('charge_log_error.txt', "充值写入数据库失败".date('Y-m-d H:i:s'). "\n", FILE_APPEND);
            }
             
        }
    }
}else{
    file_put_contents('wx_pay_fail.txt', '解析xml失败'.date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    echo 'FAIL';
}
 

?>