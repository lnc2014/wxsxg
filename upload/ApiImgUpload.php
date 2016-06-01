<?php
/**
 * 上传图片的接口
 */

//require_once 'class/ApiFunc.php';
require_once 'class/ApiUpload.php';
//$apiFuc = new ApiFunc(); 

$file = new ApiUpload();
//收到客户端传递过来的_FILE_的相关信息
$upload = $file->upload('file');
if(is_array($upload)){ 
	$result = array('code'=>1,'msg'=>'上传成功','data'=>array(
		'path'=>"http://www.china1yyg.com/".$upload['path']
	));
}else{
	$result = array('code'=>0,'msg'=>'上传失败');
}
echo json_encode($result);
?> 