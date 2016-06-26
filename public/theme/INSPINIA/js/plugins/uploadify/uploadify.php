<?php
require('./../../../../../../application/libraries/oss/Alioss.php');
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
error_reporting(0);

function rename_timestamp($name) {
        $name_arr = explode('.',$name);
        $new_name = time().'.'.$name_arr[count($name_arr)-1];
        return $new_name;
}

function api_return($result, $data, $info, $encode = 'utf8') {
        $arr["result"] = $result;
        $arr["data"] = $data === null ? '' : $data;
        $arr["info"] = $info;

        $res = json_encode($arr);
		
        return $res;
    }

// Define a destination
//$targetFolder = '/uploads'; // Relative to the root

	$tempFile = $_FILES['Filedata']['tmp_name'];
	//var_dump($_FILES['Filedata']);exit;
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	$file_name = rename_timestamp($_FILES['Filedata']['name']);
	$file_path = "images/car/{$file_name}";
	$uplaod = array(
			'object' => $file_path,
			'content' => $_FILES['Filedata']['tmp_name']
	);
	//var_dump($uplaod, $file_name, $file_path);exit;
	
	if ($_FILES['Filedata']['tmp_name']) {
		$object = $uplaod['object'];
		$content  = file_get_contents($uplaod['content']);

		$options = array(
			'content' => $content,
			'length' => strlen($content),
		);
		$alioss = new ALIOSS();
		$ret = $alioss->upload_file_by_content('dudubashi', $object, $options);
		
		$res = array();
		
		if ($ret->status == '200') {
			$res = array(
				'file_url'  => $ret->header['_info']['url']
				,'file_path' => '/'.$file_path
			);
			
			echo  api_return('0000', $res, 'success'); exit;
		} else {
			echo  api_return('0001', $res, 'error'); exit;
		}
	}
	
	
	
	
	
/*	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo '1';
	} else {
		echo 'Invalid file type.';
	}*/
//}
?>