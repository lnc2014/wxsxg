<?php
/**
 * Description：闪修哥订单模型
 * Author: LNC
 * Date: 2016/6/2
 * Time: 22:58
 */

class sxg_order extends CI_Model{

    private $table_name = 'sxg_order';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 添加订单
     */
    public function insert_data($data){
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    /**
     * 通过订单ID查找订单的信息
     * @param $order_id
     * @return mixed
     */
    public function find_order_by_id($order_id){
        $this->db->select();
        $this->db->where("id", $order_id);
        $this->db->from($this->table_name);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     *  通过条件来修改订单信息
     *
     */
    public function update_order_by_condition($data, $where){
        return $this->db->update($this->table_name, $data, $where);
    }

    /**
     * 通过用户ID查找用户的所有的订单
     * @param $user_id
     * @return mixed
     */

    public function find_all_order_by_user_id($user_id, $status = 0){
//        1,待接单2，待上门3,检测中4,调配件5,维修中6,待点评7,已结束8,已取消
        if($status != 0){
            $this->db->where("status", $status);
        }
        $this->db->select();
        $this->db->where("user_id", $user_id);
        $this->db->from($this->table_name);
        $this->db->order_by('updatetime', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

}


