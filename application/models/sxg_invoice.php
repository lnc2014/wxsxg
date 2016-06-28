<?php
/**
 * Description：闪修哥发票数据模型
 * Author: LNC
 * Date: 2016/6/2
 * Time: 22:58
 */

class sxg_invoice extends CI_Model{

    private $table_name = 'sxg_invoice';

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
     * 查找发票列表
     * @param $user_id
     */

    public function get_invoice_list($user_id){
        $this->db->select()->from($this->table_name)->where('user_id', $user_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * 获取发票详情
     */
    public function get_invoice_detail($invoice_id){
        $this->db->select()->from($this->table_name)->where('invoice_id', $invoice_id);
        $query = $this->db->get();
        return $query->row_array();
    }
}

