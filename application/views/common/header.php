<?php
/**
 * Description:微信维修端公共部分
 * Author: LNC
 * Date: 2016/5/30
 * Time: 23:13
 */
$base_css_url = $this->config->item('css_url');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta HTTP-EQUIV="pragma" CONTENT="no-cache">
    <meta HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
    <meta http-equiv="Expires" content="0" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="apple-touch-fullscreen" content="yes" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="Access-Control-Allow-Origin" content="*">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, target-densitydpi=device-dpi">
    <title><?php echo $title ?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_css_url?>main.css?v=1.0" />
</head>
