<?php
/**
 * Description:微信端首页
 * Author: LNC
 * Date: 2016/5/25
 * Time: 22:51
 */
include_once "BaseController.php";

class Sxg extends BaseController{

    /**
     * 快速登录页面
     */
    public function index(){
        $title = '快速登录';
        $this->load->view('login',array(
            'title' => $title
        ));
    }

    /**
     *验证码判断
     */
    public function check_code(){
        $phone = $this->input->post('phone');
        $code = $this->input->post('code');

        if (empty($phone) || empty($code)) {
            echo $this->apiReturn('0003', new stdClass(), '请求参数不能为空');
            return;
        }
        session_start();

        if(!$this->check_user($phone)){
            echo $this->apiReturn('0002', new stdClass(), '用户不存在');
            return;
        }
        $phone_code = $phone.date('Ymd');
        $sms_code = empty($_SESSION[$phone_code])?0:$_SESSION[$phone_code];
        if($sms_code !== $code){//暂时定为验证正确
            echo $this->apiReturn('0000', new stdClass(), 'success');
            return;
        }else{
            echo $this->apiReturn('0001', new stdClass(), '验证码不正确');
            return;
        }
    }

    /**
     * 检测用户是否登录
     * @param $phone
     * @return bool
     */
    public function check_user($phone){

        if(!empty($_SESSION['user_id'])){
            return true;
        }
        $this->load->model("sxg_user");
        $user_id = $this->sxg_user->get_user_by_phone($phone);
        if($user_id >0 ) {
            $_SESSION['user_id'] = $user_id;
            return true;
        }
        return false;
    }
    /**
     * 发送验证码
     */
    public function get_code(){

    }

    /**
     * 快速下单的首页
     */
    public function quick_order(){
        $title = "快速下单";

        $this->load->view('quick_order',array(
            'title' => $title
        ));

    }

    /**
     * 订单详情
     */
    public function order_detail(){
        $title = "订单填写";

        $this->load->view('order_detail',array(
            'title' => $title
        ));
    }
    public function address(){
        $title = "选择地址";

        $this->load->view('address',array(
            'title' => $title
        ));
    }
    public function add_address(){
        $title = "新增地址";

        $this->load->view('add-address',array(
            'title' => $title
        ));
    }
    public function address_map(){
        $title = "新增地址";
        $this->load->view('address-map',array(
            'title' => $title
        ));
    }

    /**
     * 我的账户
     */
    public function my_account(){
        $title = "我的账户";
        $this->load->view('my-account',array(
            'title' => $title
        ));
    }

    /**
     * 意见反馈
     */
    public function feedback(){
        $title = "投诉与建议";
        $this->load->view('feed-back',array(
            'title' => $title
        ));
    }

    /**
     *反馈与意见
     */
    public function add_feedback(){
        $feedback = $this->input->post('feedback');
        if (empty($feedback)) {
            echo $this->apiReturn('0003', new stdClass(), '请求参数不能为空');
            return;
        }
        //TODO 意见反馈

    }
}
