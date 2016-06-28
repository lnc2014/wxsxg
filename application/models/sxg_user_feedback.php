<?php
/**
 * Description：闪修哥意见反馈数据模型
 * Author: LNC
 * Date: 2016/6/2
 * Time: 22:58
 */

class sxg_user_feedback extends CI_Model{

    private $table_name = 'sxg_user_feedback';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /**
     * 添加反馈意见
     */
    public function insert_data($data){
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

}

