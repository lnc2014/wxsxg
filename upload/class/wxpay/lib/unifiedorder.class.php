<?php
/**
* 文件说明：统一下单的类
* ==============================================
* 版权所有 @lnc 
* ==============================================
* @date: 2015年12月8日
* @author: admin
* @version:1.0
*/
class Unifiedorder{
    
    /**
     * 发送现金红包
     */
    public function sendOrder($appid,$total_fee,$notify_url) {
        
        $data['appid'] = 'wxb6a6a99c6a271a30';
        
        $mch_id = '1295719801';//微信支付分配的商户号
        
        //商户后台的秘钥   
    
        $data['mch_id'] = '1295719801';//微信支付分配的商户号
    
        $body = '有鱼网APP商品购买';//TODO 到时候需要传递商品标题
        $body = iconv("utf-8","utf-8",$body);
        $data['body'] = $body;//商品或支付单简要描述
    
        $data['out_trade_no'] = $this->getOrderNo($mch_id);
        
		if(empty($total_fee)){
			$total_fee = 10;
		}
        $data['total_fee'] = $total_fee;//订单总金额，单位为分，详见支付金额
    
        $data['spbill_create_ip'] = $this->get_ip();//终端IP
       
        $data['time_start'] = date("YmdHis");//订单生成时间，格式为yyyyMMddHHmmss，如2009年12月25日9点10分10秒表示为20091225091010。其他详见时间规则
        $data['time_expire'] = date("YmdHis", time() + 60000);//订单失效时间，格式为yyyyMMddHHmmss，如2009年12月27日9点10分10秒表示为20091227091010。其他详见时间规则注意：最短失效时间间隔必须大于5分钟
        
        $data['notify_url'] = $notify_url; //接收微信支付异步通知回调地址
        
        $data['trade_type'] = 'APP';//交易类型
         
        $data['nonce_str'] = $this->randStr(32);//随机字符串，不长于32位
        
        $sign = $this->getSign($data);
        
        $data['sign'] = $sign;
		//var_dump($data);exit;
        $xml = $this->arrayToXml($data);
        
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';//统一下单的接口
        
        $responseXml = $this->curl_post_ssl($url, $xml);
        $responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA); 
		
		$responseArray = (array)$responseObj;//强制转换成数组
        
        return $responseArray;
//         if($responseObj->return_code == "SUCCESS"){
//             $return['return_code'] = $responseObj->return_code;
//             $return['result_code'] = $responseObj->result_code;
//             $return['sign'] = $responseObj->sign;
//             $return['appid'] = $responseObj->appid;
//             $return['mch_id'] = $responseObj->mch_id;
//             $return['device_info'] = $responseObj->device_info;
//             $return['nonce_str'] = $responseObj->nonce_str;
        
//             //以下字段在return_code 和result_code都为SUCCESS的时候有返回
//             $return['trade_type'] = $responseObj->trade_type;
//             $return['prepay_id'] = $responseObj->prepay_id;
//         }else{
//             $return['return_code'] = $responseObj->return_code;
//         }
//         return $return;
    }
    
    
    /**
     * 产生30随机字符串
     */
    
   public function randStr($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;
    
        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
    
        return $str;
    }
 
    /**
     * 商户订单号（每个订单号必须唯一）
            组成：mch_id+yyyymmdd+10位一天内不能重复的数字
     */
    public function getOrderNo($mch_id){
        return $mch_id.date("Ymd",time()).date("His",time()).rand(1111,9999);
    }
    
    /**
     * 发送红包的金额
     */
    public function sendNum($min,$max){
        $num = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        $num = substr($num, 0,4);
        return $num*100;
    }
    /**
     * 获得客户端IP
     */
    public function get_ip(){
        if (isset($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], "unknown"))
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], "unknown"))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else if (isset($_SERVER['REMOTE_ADDR']) && isset($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else if (isset($_SERVER['HTTP_X_REAL_IP']) && strcasecmp($_SERVER['HTTP_X_REAL_IP'], "unknown"))
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        else $ip = "";
        return ($ip);
    }
    /**
     * 输出xml字符
     * @throws WxPayException
     **/
    public function arrayToXml($arr)
    {
        if(!is_array($arr)
            || count($arr) <= 0)
        {
            throw new ErrorException("数组数据异常！");
        }
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
    /**
     *  作用：生成签名
     */
    public function getSign($arr)
    {
        foreach ($arr as $k => $v)
        {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
    
        //签名步骤二：在string后加入KEY
        $String = $String."&key="."900A261DC5D62DA3D6A21A13283B8E50"; // 商户后台设置的key
        //签名步骤三：MD5加密
        $String = md5($String);
        //         echo "【string3】 ".$String."</br>";
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        //         echo "【result】 ".$result_."</br>";
        return $result_;
    }
    /**
     * 格式化
     * @param unknown $paraMap
     * @param unknown $urlencode
     */
    
    public  function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v)
        {
            if($urlencode)
            {
                $v = urlencode($v);
            }
            //$buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }
    /**
     * 请求链接
     * @param unknown $url
     * @param unknown $vars
     * @param number $second
     * @param unknown $aHeader
     */
    public function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
    {
        $ch = curl_init();
        //超时时间
        curl_setopt($ch,CURLOPT_TIMEOUT,$second);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        //这里设置代理，如果有的话
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
    
        if( count($aHeader) >= 1 ){
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
        }
    
        curl_setopt($ch,CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
        $data = curl_exec($ch);
        if($data){
            curl_close($ch);
            return $data;
        }
        else {
            $error = curl_errno($ch);
            curl_close($ch);
            return false;
        }
    }
    
}