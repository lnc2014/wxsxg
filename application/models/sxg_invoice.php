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
     * 查找发票列表
     * @param $user_id
     */

    public function get_invoice_list($user_id){
        $this->db->select()->from($this->table_name)->where('user_id', $user_id);
        $query = $this->db->get();
        return $query->result_array();
    }
}

