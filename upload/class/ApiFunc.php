<?php
/**
 * api接口常用的函数方法
 */
require_once 'ApiConfig.php'; 

class ApiFunc{ 
	
    public function _htmtocode($content) {
        $content = str_replace('%','%&lrm;',$content);
        $content = str_replace("<", "&lt;", $content);
        $content = str_replace(">", "&gt;", $content);
        $content = str_replace("\n", "<br/>", $content);
        $content = str_replace(" ", "&nbsp;", $content);
        $content = str_replace('"', "&quot;", $content);
        $content = str_replace("'", "&#039;", $content);
        $content = str_replace("$", "&#36;", $content);
        $content = str_replace('}','&rlm;}',$content);
        $content = htmlspecialchars($content);
        return $content;
    }
    /**
     * 邀请码生成，32位大小写
     */
    public function generateInviteCode($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;
        
        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        
        return $str;
    }
 
	/**
	 * 获取客户端传值过来的参数
	 */
	public function getParams($param){
		if (isset($_POST["$param"])) return $_POST["$param"];
		elseif (isset($_GET["$param"])) return $_GET["$param"];
		else return null;
	}
	
	/**
	 * 验证码内容
	 */
	public function getContent($action){ 
		
		$checkcode=rand(100000,999999);//验证码 
	    if($action == 'res'){//注册短信
	        $content =  "你好,你的有鱼网注册验证码是:".strtolower($checkcode);
        }elseif ($action == 'findpsw'){//找回密码
            $content =  "你好,你现在正在找回密码，你的验证码是".strtolower($checkcode).'。';
        }elseif ($action == 'zhongjiang'){//中奖
            $content =  "恭喜你！您在有鱼网购买的商品已中,获得的云购码为：".strtolower($checkcode).'请登陆网站查看详情！客服QQ：32082224';
        }elseif ($action == 'bandmobile'){
            $content =  "您好,你正在有鱼网绑定你的手机号，你的验证码是".strtolower($checkcode).'。';
        }elseif ($action == 'findpaypsw'){//找回支付密码
            $content =  "你好,你正在找回支付密码，你的验证码是".strtolower($checkcode).'。';
        }
		$contentAll = array(
			'code'=>$checkcode,
			'content'=>$content
		);		  
		return $contentAll;
	}
	/**
	 * 获取物流的相关的信息
	 */
	public function getWuliuInfo(){
	    date_default_timezone_set("PRC");
	    $showapi_appid = '14321';  //替换此值
	    $showapi_sign = '42814e6d05ee4813a9919e84acbe0296';  //替换此值。
	    $showapi_timestamp = date('YmdHis');
	    
	    $company = 'yuantong';//快递公司英文简称
	    $num = '880998390888842126';//快递订单号
	    $paramArr = array(
	        'showapi_appid'=> $showapi_appid,
	        'com' => $company ,
	        'nu' => $num ,
	        'showapi_timestamp' => $showapi_timestamp
	        // other parameter
	    );
        $sign = $this->createSign($paramArr);
        $strParam = $this->createStrParam($paramArr);
        $strParam .= 'showapi_sign='.$sign;
        
        
        $url = 'http://route.showapi.com/64-19?'.$strParam;
        
        $result = file_get_contents($url);
        $result = json_decode($result);
	    return $result;
	}
	
	
	/**
	 * 物流接口相关的信息
	 * 接口地址:https://www.showapi.com/user/useOtherApiList
	 * 
	 * 签名算法
	 * @param unknown $paramArr
	 */
    public function createSign ($paramArr) {
        global $showapi_sign;
        $sign = "";
        ksort($paramArr);
        foreach ($paramArr as $key => $val) {
            if ($key != '' && $val != '') {
                $sign .= $key.$val;
            }
        }
        $sign.=$showapi_sign;
    //     echo "sorted para is:".$sign."\n";
        $sign = strtoupper(md5($sign));
    //     echo "md5 result is :".$sign."\n";
        return $sign;
    }
    
    public function createStrParam ($paramArr) {
        $strParam = '';
        foreach ($paramArr as $key => $val) {
            if ($key != '' && $val != '') {
                $strParam .= $key.'='.urlencode($val).'&';
            }
        }
        return $strParam;
    }
    	
	/**
	 * 发送验证码
	 * @param string $post_data
	 * @param string $target
	 * @param string $get_key
	 * @return unknown
	 */
	public function sendCode($mobile,$content){
		$post_data=null;
		$target=null; 
		 //帐号密码
		$account = ApiConfig::UID;
		$password = ApiConfig::MPASS;
		
		$content = rawurlencode($content); 
		/*发送短信*/ 
		$post_data = "account={$account}&password={$password}&mobile=".$mobile."&content=".$content;
		$target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
		/*curl*/
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $target);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		$return_str = curl_exec($curl);
		curl_close($curl);
		/*curl*/
		
		/*xml*/
		$arr = $this->xml_to_array($return_str);  
		/*xml*/ 
		
		$result_arr = $arr['SubmitResult'];	
		
// 		发送成功
		if($result_arr['code'] == 2){
			$result['code'] = 1;
			$result['msg'] =  $result_arr['msg'];
		}else{
			$result['code'] = -1;
			$result['msg'] =  $result_arr['msg'];			
		}
		  
		return $result;
	}
	
	/*互亿无线短信其他操作*/
	public function cfg_getdata_3(){
	
		/*获取条数*/
		$this->mobile = System::load_sys_config("mobile");
	
		$account = $this->mobile['cfg_mobile_3']['mid'];
		$password = $this->mobile['cfg_mobile_3']['mpass'];
	
		$post_data = "account={$account}&password={$password}";
		$target = "http://106.ihuyi.cn/webservice/sms.php?method=GetNum";
		return	$this->sendCode($post_data,$target,true);
	}
	/**
	 * xml转换成array
	 * @param unknown $xml
	 * @return unknown
	 */
	public function xml_to_array($xml){ 
		$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/"; 
		if(preg_match_all($reg, $xml, $matches)){
			$count = count($matches[0]);
			for($i = 0; $i < $count; $i++){
				$subxml= $matches[2][$i];
				$key = $matches[1][$i];
				if(preg_match( $reg, $subxml )){
					$arr[$key] = $this->xml_to_array( $subxml );
				}else{
					$arr[$key] = $subxml;
				}
			}
		}
		return $arr;
	}
	/*获取客户端ip*/
	function get_ip(){
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
	/*
	 * 
	 * 正则匹配出图片的路径
	 */
	function getImgUrl($content){
	    preg_match_all('/<img(.*?)src=("|\'|\s)?(.*?)(?="|\'|\s)/',$content,$result);
	    return $result[3];
	}
	//生成订单号  默认的是C开头的
	function pay_get_dingdan_code($dingdanzhui=''){
	    return $dingdanzhui.time().substr(microtime(),2,6).rand(0,9);
	}
}