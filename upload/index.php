<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>UploadiFive Test</title>
<script src="jquery.1.71.js" type="text/javascript"></script>
<script src="jquery.uploadify.min.js" type="text/javascript"></script>

</head>

<input type="file" name="file_upload" id="file_upload" />


<script>
	$(function() {
		$("#file_upload").uploadify({
			'auto'     : true,
			'uploader' : 'upload.php',
			'fileTypeExts'  : '*.jpg;*.jpeg;*.png',
			'dataType' : 'json',
			'onUploadComplete' : function(file) {
				alert('The file ' + file.name + ' finished processing.');
			}
		});
	});
</script>