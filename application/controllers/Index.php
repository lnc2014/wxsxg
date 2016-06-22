<?php
/**
 * Description:微信端首页
 * Author: LNC
 * Date: 2016/5/25
 * Time: 22:51
 */
include_once "BaseController.php";

class Index extends BaseController{

    public function test(){
        echo "Hello world1";
    }

    public function index(){

        $this->load->view();
        echo 1;
    }
}
