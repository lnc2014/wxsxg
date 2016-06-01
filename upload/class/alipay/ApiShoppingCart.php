<?php
/**
 * 购物车商品结算
 */   
require_once '../ApiFunc.php';
require_once '../Pay.php'; 
require_once '../User.php'; 

$apiFunc = new ApiFunc();  
$pay = new Pay();
$user = new User();


$ip = $apiFunc->get_ip();
$Cartlist = $apiFunc->getParams('cartList');//商品ID 
$uid = $apiFunc->getParams('uid'); //用户ID

$Cartlist = array(
    array(
        'goodsId'=>30,
        'num'=>2,
        'money'=>1.0
    ),
    array(
        'goodsId'=>13,
        'num'=>2,
        'money'=>1.0
    )
);

if(empty($Cartlist)||empty($uid)){
    $result = array('code'=>0,'msg'=>'传递参数不能为空');
}else{
    $userInfo = $user->findUserByUid($uid);
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
            $scookies_arr[$key]['shenyu'] = $shoplist[$key]['cart_shenyu'];
            $scookies_arr[$key]['num'] = $shoplist[$key]['cart_gorenci'];
            $scookies_arr[$key]['money'] = intval($shoplist[$key]['yunjiage']);
            $scookies_arr['MoenyCount'] += intval($shoplist[$key]['cart_xiaoji']);
        }else{
            unset($shoplist[$key]);
        }
    } 
    if($pay == true){//支付成功才插入记录
        $buyRecrod = $pay->cartPay($shoplist, $userInfo);
        if($buyRecrod){
            $result = array('code'=>2,'msg'=>'支付成功');
        }else{
            $result = array('code'=>3,'msg'=>'支付失败，返回购物车','data'=>$Cartlist);
        }
    }else{
        $result = array('code'=>1,'msg'=>'支付失败，返回购物车','data'=>$Cartlist);
    }
}      
echo json_encode($result);

 

