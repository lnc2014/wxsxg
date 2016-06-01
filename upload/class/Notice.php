<?php
/**
 * 公告栏信息处理
 */
define('PATH',dirname(dirname(__FILE__))); 
require_once PATH.'/db/db.php';
class Notice{
	
    //公告栏接口列表
    public function noticeList($page){
        $db = new db();
        $end=10;
        $star=($page-1)*$end;
        $sql = "SELECT id,title,`time` FROM go_app_notice ORDER BY id DESC limit $star,$end;";
        $rs = $db->query($sql);
        return $db->get_rows($rs);
    }
    //通过公告ID拿到公告详情
    public function noticeDetail($noticeId){
        $db = new db();
        $sql = "SELECT * FROM go_app_notice where id = '{$noticeId}'";
        $rs = $db->query($sql);
        return $db->get_rows($rs);
    }
    
	  
}