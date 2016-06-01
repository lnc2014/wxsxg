<?php
/**
 * 支付信息
 */
define('PATH',dirname(dirname(__FILE__))); 
require_once PATH.'/db/db.php';
class Pay{
    
    /**
     * 支付宝支付平台日志文件
     */
    public function aliPayLog($uid, $trade_no, $trade_status, $seller_id, $seller_mail,$buyer_id, $buyer_email,$total_fee,$trade_type){
        $db = new db();
        $time = date('Y-m-d H:i:s');
        $sql = sprintf("insert into `alipay_log` (`uid`, `trade_no`, `trade_status`, `seller_id`, `seller_email`, `buyer_id`, `buyer_email`, `total_fee`, `trade_type`, `time`) values ('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s','%s');",
            $uid, $trade_no, $trade_status, $seller_id, $seller_mail, $buyer_id,$buyer_email,$total_fee,$trade_type,$time);
        $rs = $db->query($sql);
        return $rs;
    }
    /**
     * 微信支付平台日志文件
     * 
     */
    public function wxPayLog($uid,$appid,$mch_id,$noce_str,$sign,$result_code,$openid,$trade_type,$bank_type,$total_fee,$cash_fee,$time_end,$transaction_id,$out_trade_no){
        $db = new db();
        $time = date('Y-m-d H:i:s');
        $sql = sprintf("insert into `wxpay_log` (`uid`, `appid`, `mch_id`, `nonce_str`, `sign`, `result_code`, `openid`, 
            `trade_type`, `bank_type`, `total_fee`, `cash_fee`, `time_end`,`out_trade_no`,`transaction_id`,`time`) values 
            ('%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s','%s');",
            $uid, $appid, $mch_id, $noce_str, $sign, $result_code,$openid,$trade_type,$bank_type,$total_fee,$cash_fee,$time_end,$out_trade_no,$transaction_id,$time);
        $rs = $db->query($sql);
        return $rs;
    }
    /**
     * 微信支付订单验证，避免重复处理逻辑代码
     */
    public function findIfWxPay($transaction_id){
        $db = new db();
        $sql = "SELECT * FROM wxpay_log WHERE transaction_id = '$transaction_id';";
        $rs = $db->query($sql);
        return $db->get_row($rs);
    }
    /**
     * 微信支付购物车相关的信息
     */
    public function addCartInfoByWx($cartInfo,$wx_trade,$paytype){
        $db = new db();
        $time = date('Y-m-d H:i:s');
        $sql = sprintf("insert into `wxpay_cart_info_log` (`wx_trade`, `cart_info`,`pay_type`, `createtime`) values ('%s', '%s','%s', '%s');", $wx_trade, $cartInfo, $paytype, $time);
        $rs = $db->query($sql);
        return $rs;
    }
    /**
     * 微信购物车的问题
     */
    public function findCartInfo($wx_trade){
        $db = new db();
        $sql = "SELECT * FROM wxpay_cart_info_log WHERE wx_trade = '$wx_trade';";
        $rs = $db->query($sql);
        return $db->get_row($rs);
    }
    /**
     * app充值
     */
    public function addMoneyRecord($uid,$money){
        $db = new db();
        $type = 1;//为充值
        $content = 'APP支付宝充值';
        $time = time();
        $pay = '账户';
        $sql = sprintf("insert into `go_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) values ('%d', '%d', '%s', '%s', '%d', '%s');",
            $uid, $type, $pay, $content, $money, $time); 
        $rs = $db->query($sql);
        return $rs;
    }
    /**
     * app充值
     */
    public function addWxMoneyRecord($uid,$money){
        $db = new db();
        $type = 1;//为充值
        $content = 'APP微信充值';
        $time = time();
        $pay = '账户';
        $sql = sprintf("insert into `go_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) values ('%d', '%d', '%s', '%s', '%d', '%s');",
            $uid, $type, $pay, $content, $money, $time); 
        $rs = $db->query($sql);
        return $rs;
    }
    /**
     * 修改用户的金额
     */
    public function addMoney($uid,$money){
        $db = new db(); 
        $sql = "UPDATE go_member SET `money`= `money`+'{$money}' WHERE uid = '{$uid}';";
        $rs = $db->query($sql);
        return $rs;
    
    }
    //充值记录
	 public function chargeList($uid,$page) {
	     $db = new db();
	     $end=10;
	     $star=($page-1)*$end;
	     //$sql = "SELECT money,pay_type as 'describe',`time` FROM go_member_addmoney_record WHERE uid='{$uid}' AND `status`='已付款' limit $star,$end;";
	     $sql = "SELECT money,content AS 'describe',`time` FROM go_member_account WHERE uid = '{$uid}' AND `type`=1 AND pay='账户' ORDER BY TIME DESC limit $star,$end;";
	     $rs = $db->query($sql);
	     return $db->get_rows($rs);
	 }
	//消费记录
	public function consumList($uid,$page) {
	     $db = new db();
	     $end=10;
	     $star=($page-1)*$end;
	     $sql = "SELECT shopname as 'describe',moneycount as money,`time` FROM go_member_go_record WHERE uid='{$uid}' ORDER BY id DESC limit $star,$end;";
	     $rs = $db->query($sql);
	     return $db->get_rows($rs);
	}
	//福分记录
	public function fufenList($uid,$page) {
	     $db = new db();
	     $end=10;
	     $star=($page-1)*$end;
	     $sql = "SELECT content as 'describe',money,`time` FROM go_member_account WHERE uid='{$uid}' AND `pay`= '福分' ORDER BY `time` DESC limit $star,$end;";
	     $rs = $db->query($sql);
	     return $db->get_rows($rs);
	}
	//购物车商品列表详情
	public function cartGoodsList($cartInfo){
	    //购物车内商品的详情 
	    
	    $db = new db(); 
	    $shopids='';			//商品ID
	    if(is_array($cartInfo)){
	        foreach($cartInfo as $key => $val){ 
	            $shopids.=intval($val['goodsId']).',';
	        }  
	        $shopids=str_replace(',0','',$shopids);
	        $shopids=trim($shopids,',');
	    }  
	    $shoplist=array();		//商品信息
	    $sql = "SELECT * FROM `go_shoplist` where `id` in($shopids) and `q_uid` is null";
	    $rs = $db->query($sql);
	    $goodsList = $db->get_rows($rs); 
	    return $goodsList;
	}
	
	public function cartGoods($goodsId){
	    $db = new db();
	    $sql = "SELECT * FROM `go_shoplist` where `id` ='{$goodsId}' and `q_uid` is null";
	    $rs = $db->query($sql);
	    $goodsList = $db->get_row($rs);
	    return $goodsList;
	}
    //使用账户余额来购买
    public function payByAccount($uid,$money){
        $db = new db();
        $sql = "UPDATE `go_member` SET `money`='$money' WHERE (`uid`='$uid')";
        return $db->query($sql);
    }
	
	//购物车处理
	public function cartPay($shoplist,$userInfo){
	    
	    $uid=$userInfo['uid'];
	    $uphoto = $userInfo['img'];
	    $username = addslashes($userInfo['username']);
	    $insert_html='';//插入到购买记录
	    
	    $dingdanzhui = 'A';
	    $dingdancode= self::dingdan_code($dingdanzhui);		//订单号
	    
	    if(count($shoplist)>1){
	        $dingdancode_tmp = 1;	//多个商品相同订单
	    }else{
	        $dingdancode_tmp = 0;	//单独商品订单
	    }
	    	
	    $ip =  $userInfo['user_ip'];
	    
	    /*订单时间*/
	    $time=sprintf("%.3f",microtime(true));
	    $MoenyCount=0;
	    foreach($shoplist as $key=>$shop){
	        $ret_data = array();
	        $ret_data = self::get_shop_codes($shop['cart_gorenci'],$shop,$ret_data);//购买的次数， 
	        $pay_type = 'APP支付宝支付';//TODO
	        $codes = $ret_data['user_code'];									//得到的云购码
	        $codes_len= intval($ret_data['user_code_len']);						//得到云购码个数
	        $money=$codes_len * $shop['yunjiage'];								//单条商品的总价格
	        $MoenyCount += $money;										//总价格
	        $status='已付款,未发货,未完成';
	        $shop['canyurenshu'] = intval($shop['canyurenshu']) + $codes_len;
	        $shop['goods_count_num'] = $codes_len;
	        $shop['title'] = addslashes($shop['title']);
	    
	        $shoplist[$key] = $shop;
	        if($codes_len){
	            $insert_html.="('$dingdancode','$dingdancode_tmp','$uid','$username','$uphoto','$shop[id]','$shop[title]','$shop[qishu]','$codes_len','$money','$codes','$pay_type','$ip','$status','$time'),";
	        }
	    }
	    $sql="INSERT INTO `go_member_go_record` (`code`,`code_tmp`,`uid`,`username`,`uphoto`,`shopid`,`shopname`,`shopqishu`,`gonumber`,`moneycount`,`goucode`,`pay_type`,`ip`,`status`,`time`) VALUES ";
	    $sql.=trim($insert_html,',');  
	    $db = new db();
	    //增加完购买记录，同时也要对商品购买以及剩余的数量进行处理 
	    foreach($shoplist as $shop):
	    if($shop['canyurenshu'] >= $shop['zongrenshu'] && $shop['maxqishu'] >= $shop['qishu']){
	        $shopListSql = "UPDATE `go_shoplist` SET `canyurenshu`=`zongrenshu`,`shenyurenshu` = '0' where `id` = '$shop[id]'"; 
	        $db->query($shopListSql);
	    }else{
	        $shenyurenshu = $shop['zongrenshu'] - $shop['canyurenshu'];
	        $shopListSql = "UPDATE `go_shoplist` SET `canyurenshu` = '$shop[canyurenshu]',`shenyurenshu` = '$shenyurenshu' WHERE `id`='$shop[id]'";
	        $db->query($shopListSql);
	    } 
	    endforeach;
	    
	    
	    $payOk = new db();
	    $rs  = $payOk->query($sql);
	    return $rs;
	}
//生成订单号
     public function dingdan_code($dingdanzhui=''){
	   return $dingdanzhui.time().substr(microtime(),2,6).rand(0,9);
}
//获得云购码
public function get_shop_codes($user_num=1,$shopinfo=null,&$ret_data=null){

    $db = new db(); 
    $table = 'go_'.$shopinfo['codes_table'];
    $codes_arr = array();
    $codesSql = "select id,s_id,s_cid,s_len,s_codes from `$table` where `s_id` = '$shopinfo[id]' order by `s_cid` DESC  LIMIT 1 for update";
    $codesRs = $db->query($codesSql);
    $codes_one = $db->get_row($codesRs); 
    //将改商品剩余云购码全部拿出来
    $codes_arr[$codes_one['s_cid']] = $codes_one;
    $codes_count_len = $codes_arr[$codes_one['s_cid']]['s_len'];//云购码的长度，看看是不是还有云购码，若是长度为零表明已经购买完了

    if($codes_count_len < $user_num) $user_num = $codes_count_len;

    $ret_data['user_code'] = '';
    $ret_data['user_code_len'] = 0;

    foreach($codes_arr as $icodes){
        $u_num = $user_num;
        	
        $icodes['s_codes'] = unserialize($icodes['s_codes']);
        $code_tmp_arr = array_slice($icodes['s_codes'],0,$u_num);//从云购码中筛选出你需要的云购码
        $ret_data['user_code'] .= implode(',',$code_tmp_arr);	//将云购码用,拼接成  数组转成字符串
        	
        $code_tmp_arr_len = count($code_tmp_arr);
        	
        if($code_tmp_arr_len < $u_num){
            $ret_data['user_code'] .= ',';
        }
        	
        $icodes['s_codes'] = array_slice($icodes['s_codes'],$u_num,count($icodes['s_codes']));//把后面的云购码截取出来放到后面去
        $icode_sub = count($icodes['s_codes']);
        $icodes['s_codes'] = serialize($icodes['s_codes']);
        
        $update = new db();
        //将拿出去了的删除掉，这样就能保证云购码始终是唯一的
        if(!$icode_sub){
            $codeUpdateSql = "UPDATE `$table` SET `s_cid` = '0',`s_codes` = '$icodes[s_codes]',`s_len` = '$icode_sub' where `id` = '$icodes[id]'";
            $codeUpdateRs = $update->query($codeUpdateSql);  
        }else{
            $codeUpdateSql = "UPDATE `$table` SET `s_codes` = '$icodes[s_codes]',`s_len` = '$icode_sub' where `id` = '$icodes[id]'";
            $codeUpdateRs = $update->query($codeUpdateSql); 
        } 
        
        $ret_data['user_code_len'] += $code_tmp_arr_len;
        $user_num  = $user_num - $code_tmp_arr_len; 
        
        }
        return $ret_data;
    }
}