<?php
/**
 * Description：闪修哥用户数据模型
 * Author: LNC
 * Date: 2016/6/2
 * Time: 22:58
 */

class sxg_user extends CI_Model{

    private $table_name = 'sxg_user';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 通过手机号查找出用户
     * @param $phone
     * @return mixed
     */
    public function get_user_by_phone($phone){

        $this->db->select()->from($this->table_name)->where('mobile', $phone);
        $query = $this->db->get();
        $result = $query->row_array();

        if(empty($result)){
            $array = array(
                'mobile' => $phone,
                'create_time' => time(),
            );
            $this->db->insert($this->table_name, $array);
            return $this->db->insert_id();
        }
        return $result['user_id'];
    }
}

