<?php
/**
 * Author:LNC
 * Description: 短信发送基础类
 * Date: 2016/7/18 0018
 * Time: 上午 10:22
 */
class sms{
    //企业ID
    private $userid = 3667;
    private $admin  = '一楠城市闪修';
    private $psw = 'YNsx787';

    private function send_sms($phone_number, $sms_content) {
        $time = time();
        $sign = md5($this->admin.$this->psw.$time);//签名逻辑：使用账号+密码+时间戳 生成MD5字符串作为签名。MD5生成32位，且需要小写
        $url = "http://211.147.242.161:8888/v2sms.aspx?action=send&userid={$this->userid}&timestamp={$time}&sign={$sign}&mobile={$phone_number}&content={$sms_content}&sendTime=&extno=";
        $result = $this->_http_get($url);
        $result = $this->xmlToArray($result);
        if($result['returnstatus'] == 'Success'){
            return true;
        }else{
            return false;
        }
    }
    //get请求
    private function _http_get($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    //post请求
    private function _http_post($url, $data) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
    //将XML转为array
    private function xmlToArray($xml)
    {
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }
    /****************************************
     * @description
     * 短信发送模板
     * **************************************
     */

    /**
     * 发送绑定短信
     */
    public function send_register($mobile, $code){
        if(empty($mobile)){
            return false;
        }
        $code = $code ? $code : rand(100000, 999999);
        $sms_content = "您的验证码为".$code."，此验证码10分钟内有效，请您尽快使用。【闪修哥】";
        return $this->send_sms($mobile, $sms_content);
    }
}