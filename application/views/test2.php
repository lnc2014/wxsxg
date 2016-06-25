<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>UploadiFive Test</title>
<script src="/static/js/jquery.1.71.js" type="text/javascript"></script>
<script src="/static/js/jquery.uploadify.min.js" type="text/javascript"></script>

</head>

<input type="file" name="file_upload" id="file_upload" />
<script>
	$(function() {
		$("#file_upload").uploadify({
			'auto'     : true,
			'uploader' : '/index.php/sxg/upload',
			'swf' 	   : '/static/js/uploadify.swf',
			'fileTypeExts'  : '*.jpg;*.jpeg;*.png',
			'dataType' : 'json',
			'onUploadComplete' : function(file) {
				alert('The file ' + file.name + ' finished processing.');
			}
		});
	});
</script>