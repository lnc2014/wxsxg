<?php
/**
 * Description：闪修哥地址数据模型
 * Author: LNC
 * Date: 2016/6/2
 * Time: 22:58
 */

class sxg_address extends CI_Model{

    private $table_name = 'sxg_address';

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
     * 通过user_id查找地址
     * @param $user_id
     * @return mixed
     */
    public function find_address_by_user_id($user_id){
        $this->db->select();
        $this->db->where('user_id', $user_id);
        $this->db->from($this->table_name);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 通过条件来查找地址信息
     * @param $where
     * @param int $is_one
     * @return bool
     */
    public function find_address_by_condition($where, $is_one = 1){

        if(!is_array($where)){
            return false;
        }
        $this->db->select();
        $this->db->where($where);
        $this->db->from($this->table_name);
        $query = $this->db->get();
        if($is_one == 1){
            return $query->row_array();
        }else{
            return $query->result_array();
        }
    }
}

