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
    public function check_user($phone = ''){
        $_SESSION['user_id'] = 1;//测试
        if(!empty($_SESSION['user_id'])){
            return true;
        }
        if(empty($phone)){
            return false;
        }else{
            $this->load->model("sxg_user");
            $user_id = $this->sxg_user->get_user_by_phone($phone);
            if($user_id > 0 ) {
                $_SESSION['user_id'] = $user_id;
                return true;
            }
            return false;
        }

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
     * 用户快速下单
     */
    public function add_order(){
        $_SESSION['user_id'] = 1;
        if(!$this->check_user()){
            echo $this->apiReturn('0004', new stdClass(), '用户尚未登录');
            exit();
        };
        $post = $this->input->post(NULL, TRUE);
        $post['repair_pic'] = trim($post['img'], ';');
        unset($post['img']);
        $post['repair_option'] = trim($post['repair_option'],',');
        $post['user_id'] = $_SESSION['user_id'];

        $post['order_no'] = 'SXG'.date('YmdHis').rand(10000,99999);
        $post['createtime'] = time();
        $post['updatetime'] = time();
        $this->load->model("sxg_order");
        $order_id = $this->sxg_order->insert_data($post);
        if($order_id > 0 ){
            $data['order_id'] =  $order_id;
            echo $this->apiReturn('0000', $data, 'success');
            exit();
        }

    }
    /**
     * 订单详情
     */
    public function order_detail($order_id = ''){
        if(empty($order_id)){
            exit("<script>alert('非法请求!');location.href='/index.php/sxg/index';</script>");
        }
        if(!$this->check_user()){
            echo $this->apiReturn('0004', new stdClass(), '用户尚未登录');
            exit();
        };
        $user_id  = $_SESSION['user_id'];
        $this->load->model("sxg_order");
        $this->load->model("sxg_address");
        $order = $this->sxg_order->find_order_by_id($order_id);
        if(empty($order)){
            exit("<script>alert('订单信息不存在!');location.href='/index.php/sxg/index';</script>");
        }
        //地址信息
        $address = $this->sxg_address->find_address_by_condition(array(
            'user_id' => $user_id,
            'is_default' => 1
        ));

        $repair_detail['print_band'] = empty($order['print_band'])?'':$order['print_band'];
        $repair_detail['print_model'] = empty($order['print_model'])?'':$order['print_model'];
        $repair_option = explode(',', $order['repair_option']);
        $repair_info = '';
        foreach($repair_option as $value){
            if(is_numeric(strpos($value,'0001'))){
                $repair_info = $repair_info.';'.'加粉';
            }elseif(is_numeric(strpos($value,'0002'))){
                $repair_info = $repair_info.';'.'打印质量差';
            }elseif(is_numeric(strpos($value,'0003'))){
                $repair_info = $repair_info.';'.'不能开机';
            }elseif(is_numeric(strpos($value,'0004'))){
                $repair_info = $repair_info.';'.'卡纸';
            }
        }
        $repair_info = trim($repair_info, ';');
        $repair_detail['repair_info'] = $repair_info.';'.$order['repair_problem'];
        $title = "订单填写";
        $this->load->view('order_detail',array(
            'title' => $title,
            'repair_detail' => $repair_detail,
            'address'   =>  $address,
            'order_id' => $order_id
        ));
    }

    /**
     * 订单的修改
     */
    public function update_order(){
        if(!$this->check_user()){
            echo $this->apiReturn('0004', new stdClass(), '用户尚未登录');
            exit();
        };
        $data = $this->input->post();
        if(empty($data)){
            echo $this->apiReturn('0003', new stdClass(), '参数不正确！');
            exit();
        }
        $order_id = intval($data['order_id']);
        if($order_id < 0){
            echo $this->apiReturn('0003', new stdClass(), '参数不正确！');
            exit();
        }
        unset($data['order_id']);

        $data['visit_time'] = strtotime($data['visit_time']);
        $data['updatetime'] = time();
        $this->load->model("sxg_order");
        $update_order = $this->sxg_order->update_order_by_condition($data,array(
            'id' => $order_id
        ));
        if($update_order){
            echo $this->apiReturn('0000', new stdClass(), 'success');
            exit();
        }else{
            echo $this->apiReturn('0002', new stdClass(), '内部错误！');
            exit();
        }

    }
    public function address(){
        $title = "选择地址";
        if(!$this->check_user()){
            echo $this->apiReturn('0004', new stdClass(), '用户尚未登录');
            exit();
        };
        $this->load->model("sxg_address");
        $address = $this->sxg_address->find_address_by_user_id($_SESSION['user_id']);
        $this->load->view('address',array(
            'title' => $title,
            'address' => $address,
        ));
    }
    public function add_address(){
        $title = "新增地址";
        $this->load->view('add-address',array(
            'title' => $title,

        ));
    }

    /**
     * 用户添加地址接口
     */
    public function add_user_address(){
        $_SESSION['user_id'] = 1;
        if(!$this->check_user()){
            echo $this->apiReturn('0004', new stdClass(), '用户尚未登录');
            exit();
        };
        $post = $this->input->post(NULL, TRUE);
        $post['user_id'] = $_SESSION['user_id'];

        $post['create_time'] = time();
        $post['update_time'] = time();
        $this->load->model("sxg_address");
        $address_id = $this->sxg_address->insert_data($post);
        if($address_id > 0 ){
            $data['address_id'] =  $address_id;
            echo $this->apiReturn('0000', $data, 'success');
            exit();
        }

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
    /**
     * 上传
     */
    public function upload(){
        $this->load->library('upload_image');
        $ret = $this->upload_image->upload('file');

        if($ret['is_success']){
            $ret['path2'] = str_replace(ROOTPATH, '', $ret['path']);//将路径换成相对路径
            $ret['path'] = 'http://'.$_SERVER['HTTP_HOST'].'/'.$ret['path2'];//将路径换成相对路径
            echo $this->apiReturn('0000', $ret, 'success');
            return;
        }
        echo $this->apiReturn('0002', $ret, '上传失败');
        return;
    }

    public function test(){
        $this->load->view('test');
    }
}
