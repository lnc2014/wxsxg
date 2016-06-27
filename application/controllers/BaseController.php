<?php
/**
 * Description:微信端基础类
 * Author: LNC
 * Date: 2016/5/25
 * Time: 22:51
 */

class BaseController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->check_user();
        session_start();
    }

    /**
     * 接口api统一结果处理
     * @param $result
     * @param $data
     * @param $info
     * @return string
     */
    public function apiReturn($result, $data, $info)
    {
        $arr["result"] = $result;
        $arr["data"] = $data === null ? '' : $data;
        $arr["info"] = $info;
        $res = json_encode($arr);
        return $res;
    }
    /**
     * 检测用户是否登录
     * @param $phone
     * @return bool
     */
    public function check_user(){
        $_SESSION['user_id'] = 1;//测试
        if(!isset($_SESSION['user_id'])){
            redirect('sxg/index');
        }
    }
}
