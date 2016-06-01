<?php
/**
 * 用户登录信息相关的数据库处理
 */
define('PATH',dirname(dirname(__FILE__))); 
require_once PATH.'/db/db.php';
class User{
    
    
    //邀请码
    public function findUserCode($uid){
        $db = new db();
        $sql = "SELECT * FROM go_member WHERE uid = '{$uid}' and mobilecode = 1";//必须手机注册成功的才能有邀请码
        $rs = $db->query($sql);
        return $db->get_row($rs);
    }
    //给用户生成邀请码
    public function generateUserInviteCode($uid,$code){
        $db = new db();
        $sql = "UPDATE `go_member` SET `invite_code` = '$code' where `uid`='$uid'";
        return $db->query($sql);
    }
	
	//查找用户是否注册
	public function findUser($mobile){
		$db = new db();
		$sql = "SELECT * FROM go_member WHERE mobile = '{$mobile}'";
		$rs = $db->query($sql);
		return $db->get_row($rs); 
	}
	
	//第三方登录查找是否已经通过第三方登录过系统
	public function findUserByUuid($from,$uuid){
	    $db = new db();
	    $sql = "SELECT a.*,b.is_small_pay,b.small_pay_count,b.pay_psw FROM  `go_member` a  left join `go_app_setting` b on a.uid=b.uid  WHERE a.band = '{$uuid}' and a.from = '{$from}'";
	    $rs = $db->query($sql);
	    $res = $db->get_row($rs);
	    return $res;
	}
	public function findUserByUuid2($from,$uuid){
	    $db = new db();
	    $sql = "SELECT `uid` FROM  `go_member` WHERE band = '{$uuid}' and `from` = '{$from}'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	public function addUserAppSetting($uid){
	    $db = new db(); 
	    $time = time();
	    $sql = "INSERT INTO `go_app_setting`(uid,createtime)VALUES('$uid',$time)";
	    $rs = $db->query($sql);
	    return $rs;
	}
	//第三方登录插入
	public function addUserFromThird($from,$username,$psw,$ip,$headImg,$uuid,$sex){
	    $db = new db();
	    $username = mysql_real_escape_string($username);
	    $sex = empty($sex)?'0':$sex;//性别默认为男0女1
	    $time = time();
	    $sql = "INSERT INTO `go_member`(username,`password`,user_ip,img,emailcode,mobilecode,reg_key,`from`,score,band,`time`,sex)VALUES('$username','$psw','$ip','$headImg','-1','-1','$from','$from','100','$uuid','$time',$sex)";
	    $rs = $db->query($sql);
	    return $rs;
	}
	
	//绑定手机
	public function checkMobile($uid,$mobile){
	    $db = new db();
	    $sql = "UPDATE `go_member` SET `mobile` = '$mobile',`mobilecode`='1' where `uid`='$uid'";
	    return $db->query($sql); 
	}
 
	//验证支付密码，用于余额支付的
	public function checkPayPsw($uid,$psw){
	    $db = new db(); 
	    $sql = "SELECT * FROM go_app_setting WHERE uid = '{$uid}' and pay_psw = '{$psw}'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	//验证是否设置过密码
	public function isSetPsw($uid, $mobile){
	    $db = new db();
	    $psw = md5('123456789');
	    $sql = "SELECT * FROM go_member WHERE mobile = '{$mobile}' and uid = '{$uid}' and password = '{$psw}'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	//判断用户密码是不是正确
	public function checkUser($mobile, $psw){
	    $db = new db();
	    $sql = "SELECT * FROM go_member WHERE mobile = '{$mobile}' and password = '{$psw}'";
		$rs = $db->query($sql);
		return $db->get_row($rs); 
	}
	//通过UID查找用户的详细信息
	public function findUserByUid($uid){
	    $db = new db();
	    $sql = "SELECT a.*,b.id,b.sheng,b.shi,b.xian,b.jiedao,b.youbian,b.shouhuoren,b.qq,b.tell,b.default FROM  `go_member` a left join `go_member_dizhi` b on a.uid=b.uid  WHERE a.uid ='{$uid}'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	//获得个人购买的记录
	public function findUserBuyRecord($uid,$type,$page){
	    $db = new db();
	    $end=10;
	    $star=($page-1)*$end;
	    if($type == 'all'){
	       $sql = "select *,sum(gonumber) as gonumber from `go_member_go_record` a left join `go_shoplist` b on a.shopid=b.id where a.uid='{$uid}' GROUP BY shopid  order by a.time desc limit $star,$end ";
	    }elseif ($type == 'ing'){
	       $sql = "select *,sum(gonumber) as gonumber from `go_member_go_record` a left join `go_shoplist` b on a.shopid=b.id where a.uid='{$uid}' and b.q_end_time is null GROUP BY shopid order by a.time desc limit $star,$end";
	    }elseif ($type == 'end'){
	       $sql = "select *,sum(gonumber) as gonumber from `go_member_go_record` a left join `go_shoplist` b on a.shopid=b.id where a.uid='{$uid}' and b.q_end_time is not null GROUP BY shopid order by a.time desc limit $star,$end";
	    } 
	    $rs = $db->query($sql);
	    return $db->get_rows($rs);
	}
	//查找用户所有的收货地址
	public function findUserAddressList($uid){
	    $db = new db();
	    $sql = "SELECT * FROM go_member_dizhi WHERE uid = '{$uid}'";
	    $rs = $db->query($sql);
	    return $db->get_rows($rs);
	}
	//用户收货地址信息编辑
	public function editAddress($uid,$addressId,$province,$city,$jiedao,$area,$name,$mobile,$default){
	    $db = new db();
	    
	    if($default == 'Y'){
	        //增加个限制，如果已经有默认收货地址，则将原来的修改成N，将新增加的设置为Y
	        $user_address_sql = "select * from `go_member_dizhi` where uid='".$uid."'";
	        $user_address_rs = $db->query($user_address_sql);
	        $user_address = $db->get_rows($user_address_rs);
	        if($user_address){
	            foreach($user_address as $dizhi){
	                if($dizhi['default']=='Y'){
	                    $default_sql  = "UPDATE `go_member_dizhi` SET `default`='N' where uid='".$uid."'";
	                    $db->query($default_sql);
	                }
	            }
	        }
	    }
        $sql = "UPDATE go_member_dizhi SET `sheng` = '{$province}',`shi` = '{$city}',`xian` = '{$area}',`jiedao` = '{$jiedao}',`shouhuoren` = '{$name}',`mobile`='{$mobile}',`default`='{$default}' WHERE id='{$addressId}' and uid ='{$uid}'";
        return $db->query($sql); 
	}
	//用户收货地址信息增加
	public function addAddress($uid,$province,$city,$jiedao,$area,$name,$mobile,$default){
	    $db = new db();
	    $time = time();
	    
	    if($default == 'Y'){
	        //增加个限制，如果已经有默认收货地址，则将原来的修改成N，将新增加的设置为Y
	        $user_address_sql = "select * from `go_member_dizhi` where uid='".$uid."'";
	        $user_address_rs = $db->query($user_address_sql);
	        $user_address = $db->get_rows($user_address_rs);
	        if($user_address){
	            foreach($user_address as $dizhi){
	                if($dizhi['default']=='Y'){
	                    $default_sql  = "UPDATE `go_member_dizhi` SET `default`='N' where uid='".$uid."'";
	                    $db->query($default_sql);
	                }
	            }
	        }
	    }
	    
	    $sql = "INSERT INTO `go_member_dizhi`(`uid`,`sheng`,`shi`,`xian`,`jiedao`,`shouhuoren`,`mobile`,`default`,`time`)VALUES
			('$uid','$province','$city','$area','$jiedao','$name','$mobile','$default','$time')";
	    return $db->query($sql);
	}
	//判断能不能晒单
	public function judgeShaidan($uid,$goodsId){
	    $db = new db();
	    //判断改商品是不是该用户可以晒单
	    $is_can_shaidan_sql = "select * from `go_member_go_record` where `shopid`='$goodsId' and `uid` = '$uid'";
	    $is_can_rs = $db->query($is_can_shaidan_sql);
	    $is_can = $db->get_row($is_can_rs);
	   
	    if($is_can){
	        $is_shaidan_sql = "select sd_id from `go_shaidan` where `sd_shopid`='$goodsId' and `sd_userid` = '$uid'";
	        $is_can_rs2 = $db->query($is_shaidan_sql);
	        $is_can2 = $db->get_row($is_can_rs2); 
	        
	        if(!$is_can2){
	            return true;
	        }else{
	            return false;
	        } 
	    }else{
	        return false;
	    }  
	}
	//个人晒单
	public function addUserShaidan($uid,$goodsId,$ip,$title,$content,$path,$sd_thumb){
	    $db = new db();
	    $time = time();
	    
	    //找出商品的相关信息
	    $goodsSql = "select id,sid,qishu from `go_shoplist` where `id`='$goodsId' LIMIT 1";
	    $goodsRs = $db->query($goodsSql);
	    $ginfo = $db->get_row($goodsRs);
	    
	    $sd_userid = $uid;
	    $sd_shopid = $ginfo['id'];
	    $sd_shopsid = $ginfo['sid'];
	    $sd_qishu = $ginfo['qishu'];
	    
	    $sd_title = $title;
	    $sd_thumbs = $sd_thumb;
	    $sd_content = stripslashes($content);
	    $sd_photolist= $path;
	    $sd_time=time();
	    $sd_ip = $ip; 
	    
	    $sql = "INSERT INTO `go_shaidan`(`sd_userid`,`sd_shopid`,`sd_shopsid`,`sd_qishu`,`sd_ip`,`sd_title`,`sd_thumbs`,`sd_content`,`sd_photolist`,`sd_time`)VALUES
	        ('$sd_userid','$sd_shopid','$sd_shopsid','$sd_qishu','$sd_ip','$sd_title','$sd_thumbs','$sd_content','$sd_photolist','$sd_time')";
	    return $db->query($sql);
	}
	//收货地址的删除
	public function delAddress($uid,$addressId){
	    $db = new db();
	    $address = explode(',',$addressId);
	    if(!empty($address)){
	        foreach ($address as $v){
	            if(!empty($v)){
	                $sql = "DELETE FROM `go_member_dizhi` WHERE `uid`='$uid' and `id`='$v'";//批量删除
	                $db->query($sql);
	            }
	        }
	        return true;
	    }else {
	        return false;
	    }
	}
	//获得商品
	public function getGoodsList($uid,$page){
	    $db = new db();
	    //页码
	    $end=10;
	    $star=($page-1)*$end;
	    $sql = "select * from `go_shoplist` where q_uid='{$uid}' order by time desc limit $star,$end";
	    $rs = $db->query($sql);
	    return $db->get_rows($rs);	    
	}
 
	//修改用户的个人信息
	public function updateUserInfo($uid, $username, $sex, $headImgUrl,$province,$city){
        $db = new db();
        $sql = "UPDATE go_member SET `username` = '{$username}',`sex` = '{$sex}',`province` = '{$province}',`city` = '{$city}',`img` = '{$headImgUrl}' WHERE uid='{$uid}'";
        return $db->query($sql);
	}
	
	//查找用户的详细数据，用户返回给客户端
	public function userInfo($mobile){
	    $db = new db();
	    $sql = "SELECT * FROM go_member WHERE mobile = '{$mobile}' and mobilecode = '1'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	    
	}
	//用户app设置
	public function appSetting($uid){
	    $db = new db();
	    $sql = "SELECT * FROM go_app_setting WHERE uid = '{$uid}'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	//查找用户的地址信息
	public function userAddress($uid){
	    $db = new db();
	    $sql = "SELECT * FROM go_member_dizhi WHERE uid = '{$uid}' AND `default` = 'Y';";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	
	//注册新用户
	
	public function addUser($mobile,$psw,$ip){
		$username = $enname=substr($mobile,0,3).'****'.substr($mobile,7,10);
		$score = 20;//手机注册赠送福分20分
		$money = 10;
		$time = time();
		$db = new db();
		$sql="INSERT INTO `go_member`(username,password,mobile,user_ip,img,emailcode,mobilecode,reg_key,time,score,money) VALUES ('$username','$psw','$mobile','$ip','photo/member.jpg','-1','1','$mobile','$time','$score','$money')";
		return $db->query($sql);
	}
	//修改用户的密码 
	public function changePsw($mobile,$psw){
		$db = new db();
		$sql = "UPDATE `go_member` SET password='$psw' where `mobile`='$mobile'";
		return $db->query($sql);
	}
	//修改用户的登录密码 通过uid
	public function changePswByUid($uid,$psw){
		$db = new db();
		$sql = "UPDATE `go_member` SET password='$psw' where `uid`='$uid'";
		$rs = $db->query($sql);
		return $rs;
	} 
	
	//修改用户的支付密码 通过uid
	public function changePayPswByUid($uid,$psw){
		$db = new db();
		$sql = "UPDATE `go_app_setting` SET `pay_psw` = '$psw' where `uid`='$uid'";
		return $db->query($sql);
	}
	//查找原来的登录密码通过uid
	public function findOldPsw($uid){
	    $db = new db();
	    $sql = "SELECT `password` FROM go_member WHERE `uid` = '{$uid}'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	/**
	 * 设置支付密码
	 */
	public function setPayPsw($uid,$psw){
	    $time = time();
	    $db = new db();
	    $sql="UPDATE `go_app_setting` SET `pay_psw` = '$psw',`updatetime`= '$time' where `uid`='$uid'";
	    return $db->query($sql);
	}
	//查找原来的登录密码通过uid
	public function findOldPayPsw($uid){
	    $db = new db();
	    $sql = "SELECT `pay_psw` FROM go_app_setting WHERE `uid` = '{$uid}'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	//小额支付
	public function smallPaySetting($uid,$issmall,$smallcount) {
	    $db = new db();
	    $sql = "UPDATE `go_app_setting` SET `is_small_pay` = '$issmall',`small_pay_count`='$smallcount' where `uid`='$uid'";
	    return $db->query($sql);
	}
 
	//设置无图模式
	public function settingPic($uid, $is_pic) {
		$db = new db();
		$updatetime = time();
		$sql = "UPDATE `go_app_setting` SET `is_no_pic` = '$is_pic',`updatetime` = '$updatetime' where `uid`='$uid'";
		return $db->query($sql);
	}
	//版本更新问题
	public function checkVersion($uid){
	    $db = new db();
	    $updatetime = time();
	    $sql = "select version from `go_app_setting` where `uid`='$uid'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	//设置消息
	public function settingNotice($uid, $is_notice) {
		$db = new db();
		$updatetime = time();
		$sql = "UPDATE `go_app_setting` SET `is_recieve_notice` = '$is_notice',`updatetime` = '$updatetime' where `uid`='$uid'";
		return $db->query($sql);
	}
	//用户反馈的内容
	public function userFeedBack($uid,$content){
	    $db = new db();
	    $time = time();
	    $content = htmlspecialchars($content);
	    $sql = "INSERT INTO `go_app_feedback`(`uid`,`feedback`,`createtime`) VALUES ('$uid','$content','$time')";
	    return $db->query($sql);
	}
	
}