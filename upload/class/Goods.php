<?php
/**
 * 商品的相关信息的处理
 */
define('PATH',dirname(dirname(__FILE__))); 
require_once PATH.'/db/db.php';
class Goods{ 
    
    //所有分类
    public function allCates(){
        $db  = new db();
        $sql = "SELECT * FROM go_category WHERE model = 1;";
        $rs = $db->query($sql);
        return $db->get_rows($rs);
    }
    //全部商品列表，不带条件
    public function goodsList($page){
        $db  = new db();
        $end=10;
        $star=($page-1)*$end; 
        $sql = "select * from `go_shoplist` where `q_uid` is null  limit $star,$end";
        $rs = $db->query($sql);
        return $db->get_rows($rs); 
    }
     //搜索
     public function searchGoodsList($title,$page){
         //页数
         $end=10;
         $star=($page-1)*$end;
         
         $db = new db();
         $sql = "select * from `go_shoplist` WHERE `title` LIKE '%".$title."%' limit $star,$end;";
         $rs = $db->query($sql);
         return $db->get_rows($rs); 
     }
    //全部商品列表，带分类帅选条件
    public function selectGoodsList($condition, $page, $cateId){
        //页数
        $end=10;
        $star=($page-1)*$end; 
        
        $select_w = '';
        if($condition == 'rs'){//人数
            $select_w = 'order by `shenyurenshu` ASC';
        }
        if($condition == 'hot'){//热度
            $select_w = "and `renqi` = '1'";
        } 
        if($condition == 'zx'){//最新
            $select_w = 'order by `time` DESC';
        }
        if($condition == 'moneyD'){//价格由高到低
            $select_w = 'order by `money` DESC';
        }
        if($condition == 'moneyA'){//价格由低到高
            $select_w = 'order by `money` ASC';
        }
        $db = new db();

        if($cateId){
            $sql = "select * from `go_shoplist` where `q_uid` is null and `cateid`='$cateId' $select_w limit $star,$end";
        }else{
            $sql = "select * from `go_shoplist` where `q_uid` is null $select_w limit $star,$end";
        } 
        $rs = $db->query($sql);
        $goodsList = $db->get_rows($rs);
        return $goodsList;
    }
    //拿到banner图
    public function getBanners(){
        $db = new db();
        $sql = "select * from `go_app_banner` limit 5";
        $rs = $db->query($sql);
        return $db->get_rows($rs); 
    }
    
	//查找人气产品
	public function findRenqiGoods(){
		$db = new db();
		//$sql = "select * from `go_shoplist` where `renqi`='1' and `q_end_time` is null ORDER BY id DESC LIMIT 12";
		$sql = "SELECT * FROM `go_shoplist` WHERE `renqi`='1' AND `q_end_time` IS NULL  AND money > 1000 ORDER BY shenyurenshu ASC LIMIT 20";
		$rs = $db->query($sql);
		return $db->get_rows($rs);
	}
	//最新揭晓
	public function findNewZj(){
	    $db = new db();
	    $sql = "select * from `go_shoplist` where `q_end_time` !='' ORDER BY `q_end_time` DESC LIMIT 4";
	    $rs = $db->query($sql);
	    return $db->get_rows($rs);
	}
	//最新揭晓带页码
	public function zxjxGoodsList($page){
	    //页数
	    $end=10;
	    $star=($page-1)*$end; 
	    
	    $db = new db();
	    $sql = "select * from `go_shoplist` where `q_end_time` !='' ORDER BY `q_end_time` DESC limit $star,$end";
	    $rs = $db->query($sql);
	    $goodsList = $db->get_rows($rs);
	    
	    return $goodsList;
	}
	//修改最新揭晓倒计时的状态
	public function zxjxUpdateStatus($goodsId){
	    $db = new db();
	    $sql = "UPDATE `go_shoplist` SET `q_showtime` = 'N'  WHERE `id`='{$goodsId}';";
	    $db->query($sql);
	    return $db->query($sql);
	}
	
	//最新揭晓数据成功之后返回获得者的相关的信息
	public function zxjxGotUser($goodsId){
	    //页数
	    $db = new db();
	    $sql = "SELECT * FROM go_shoplist a LEFT JOIN go_member_go_record b ON a.`q_uid` = b.`uid` WHERE a.`id` = '{$goodsId}' AND b.goucode LIKE CONCAT('%', a.q_user_code, '%');";
	    $rs = $db->query($sql);
	    $goodsList = $db->get_row($rs);
	    return $goodsList;
	}
	//限时揭晓
	public function findXsGoods(){
	    $w_jinri_time = strtotime(date('Y-m-d'));
	    $w_minri_time = strtotime(date('Y-m-d',strtotime("+1 day")));
	    $w_hinri_time = strtotime(date('Y-m-d',strtotime("+2 day")));
	    $db = new db();
	    $today_sql = "select * from `go_shoplist` where `xsjx_time` > '$w_jinri_time' and `xsjx_time` < '$w_minri_time' order by id DESC limit 0,4";
	    $tommrow_sql = "select * from `go_shoplist` where `xsjx_time` > '$w_minri_time' and `xsjx_time` < '$w_hinri_time' order by id DESC limit 0,3";
	    //今天限时揭晓
	    $today = $db->query($today_sql);
	    $today_goods = $db->get_rows($today);
	    //明天限时揭晓
	    $tommrow = $db->query($tommrow_sql);
	    $tommrow_goods = $db->get_rows($tommrow);
	    return $today_goods;
	}
	//最新购买
	public function zxBuyRecord(){
	    $db = new db();
	    $sql = "SELECT * FROM `go_member_go_record` ORDER BY `go_member_go_record`.`id` DESC limit 12";
	    $rs = $db->query($sql);
	    return $db->get_rows($rs);
	}
	//最新晒单
	public function shaiDan(){
	    $db = new db();
	    $sql = "select * from `go_shaidan` order by `sd_id` DESC LIMIT 4"; 
	    $rs = $db->query($sql);
	    return $db->get_rows($rs);
	}
	//晒单信息的判断
	public function findShaidan($sdId){
	    $db = new db();
	    $sql = "SELECT * FROM go_shaidan WHERE sd_id = '{$sdId}'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	//判断商品是不是存在
	public function findGoods($goodsId) {
	    $db = new db();
	    $sql = "SELECT * FROM go_shoplist WHERE id = '{$goodsId}'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	} 
	//查找出这期商品揭晓的产品的相关信息
	public function findNowGoods($goodsId) {
	    $db = new db();
	    $sql = "SELECT * FROM go_shoplist WHERE id = '{$goodsId}' and q_uid is not null";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	} 
	//查找商品的物流信息，后台录入，暂时未调用第三方
	public function findGoodswuliu($uid, $goodsId){
	    $db = new db();
	    $sql = "SELECT * FROM go_member_go_record WHERE `uid`='{$uid}' AND shopid = '{$goodsId}';";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	//确认收货
	public function confirmGoods($uid, $goodsId){
	    
	    $db = new db();
	    $status = '已付款,已发货,已完成';
	    $sql = "UPDATE `go_member_go_record` SET `status` = '{$status}'  WHERE `uid`='{$uid}' AND shopid = '{$goodsId}';";
	    return $db->query($sql); 
	}
	//增加校验是否该商品购买记录存在
	public function checkConfirmGoods($uid, $goodsId){
	    $db = new db();
	    $sql = "SELECT * FROM go_member_go_record WHERE `uid`='{$uid}' AND shopid = '{$goodsId}';";
	    $rs = $db->query($sql);
	    $goods = $db->get_row($rs);
	    if($goods){
	        return true;
	    }else{
	        return false;
	    }  
	}
	
	//晒单的评论列表
	public function pinglunList($sdId,$page){
	    $db = new db();
	    //页数
	    $end=10;
	    $star=($page-1)*$end;
	    $sql = "SELECT * FROM go_shaidan_hueifu WHERE `sdhf_id`='{$sdId}'order by `id` DESC limit $star,$end";
	    $rs = $db->query($sql);
	    return $db->get_rows($rs);
	}
	//增加晒单评论
	public function addPinglun($sdId, $uid,$username,$headImg,$content){
	    $db = new db();
	    $time = time();
	    $content = htmlspecialchars($content);
	    $sql = "INSERT INTO `go_shaidan_hueifu`(`sdhf_id`,`sdhf_userid`,`sdhf_time`,`sdhf_username`,`sdhf_img`,`sdhf_content`) VALUES ('$sdId','$uid','$time','$username','$headImg','$content')";
	    $add = $db->query($sql);
	    
	    
	    //增加评论的时候,晒单相应的评论数也应该要跟上
	    
	    $pinglun = 1;//评论数
	    $pinglun_sql = "UPDATE `go_shaidan` SET `sd_ping`= `sd_ping`+'{$pinglun}' WHERE sd_id = '{$sdId}';";
	    $update = $db->query($pinglun_sql);
	    return $add && $update ;
	}
	//点赞
	public function addDianzan($sdId){
	    $db = new db();  
	    $sql = "UPDATE `go_shaidan` SET `sd_zhan` = `sd_zhan`+1  where `sd_id`='$sdId'";
	    return $db->query($sql);
	}
	public function firstDianzan($uid,$sdId,$ip){
	    $db = new db();
	    $time = time();
	    $sql = "INSERT INTO `go_app_dianzan`(`uid`,`sd_id`,`is_dianzan`,`time`,`ip`) VALUES ('$uid','$sdId','1','$time','$ip')";
	    return $db->query($sql);
	}
	//判断是否已经点赞
	public function checkDianzan($sdId, $uid){
	    $db = new db();
	    $sql = "SELECT * FROM go_app_dianzan WHERE sd_id = '{$sdId}' and uid = '{$uid}';";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	//晒单的总数
	public function countShaidan($goodsId){
	    $db = new db();
	    $sql = "SELECT COUNT(*) as shandanshu FROM go_shaidan WHERE sd_shopid = '{$goodsId}' ;";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	//我的晒单列表
	public function myShaidanList($uid, $page) {
	    //页数
	    $end=10;
	    $star=($page-1)*$end;
	    
	    $db = new db();
	    $sql = "SELECT * FROM go_shaidan WHERE sd_userid = '{$uid}' ORDER BY sd_id DESC limit $star,$end;";
	    $rs = $db->query($sql);
	    return $db->get_rows($rs);
	}
	//晒单分享
	public function shaidanAll($goodsId,$page){
	    $db = new db();
	    //页数
	    $end=10;
	    $star=($page-1)*$end;
	     
	    $goodsList_sql  = "select * from `go_shoplist` where `id`='{$goodsId}'"; 
	    $goods = $db->findOne($goodsList_sql); 
	    
	    if(!empty($goods)){
	        $goods_sid = $goods['sid'];
	        $shandan_sql = "select * from `go_shaidan` a left join `go_member` b on a.sd_userid = b.uid where a.sd_shopsid='{$goods_sid}' order by `sd_id` DESC limit $star,$end";
	        $shandan_rs = $db->query($shandan_sql);
	        $shaidan = $db->get_rows($shandan_rs);//所有的晒单
	        return $shaidan;
	    }else {
	        return false;
	    }
	    
	}
	//上期该商品的获得者
	public function getLastUser($sid){
	    $db = new db();
	    $sql = "select * from `go_shoplist` where `sid`='$sid' and `q_end_time` is not null order by `qishu` DESC";
	    $rs = $db->query($sql);
	    return $db->get_row($rs); 
	}
	//上期该商品的获得者所有信息
	public function getLastUserInfo($uid,$goodsId,$qishu){
	    $db = new db();
	    $sql = "SELECT * FROM go_member a LEFT JOIN go_member_go_record b ON a.`uid`= b.`uid` WHERE b.`uid`= '{$uid}' AND b.`shopid`= '{$goodsId}' AND b.`shopqishu`= '{$qishu}' ;";
	    $rs = $db->query($sql);
	    return $db->get_row($rs); 
	}
	//上期该商品的相关信息
	public function getLastGoods($goodsSid,$qishu){
	    $db = new db();
	    $qishu = $qishu -1;
	    $sql = "SELECT * FROM go_shoplist WHERE sid= '{$goodsSid}' AND qishu = '{$qishu}';";
	    $rs = $db->query($sql);
	    return $db->get_row($rs); 
	}
	//这期商品的获得者
	public function getNowUser($goodsId){
	    $db = new db();
	    $sql = "select * from `go_shoplist` where `id`='$goodsId'";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	
	//商品购买详情
	public function getUserBuyRecords($uid, $goodsId,$qishu){
	    $db = new db();
	    $sql = "SELECT * FROM go_member_go_record WHERE uid = '{$uid}' AND shopid = '{$goodsId}' and shopqishu = '{$qishu}';";
	    $rs = $db->query($sql);
	    return $db->get_row($rs);
	}
	//商品的购买人员记录
	public function goodsBuyRecords($goodsId,$page){
	    //页数
	    $end=10;
	    $star=($page-1)*$end;
	    $db = new db();
	    $sql = "select * from `go_member_go_record` where `shopid`='$goodsId' order by id desc limit $star,$end";
	    $rs = $db->query($sql);
	    return $db->get_rows($rs);
	}
	 
}
 
