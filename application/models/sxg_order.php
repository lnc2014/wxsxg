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

}

