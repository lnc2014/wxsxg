<?php

if ($_SERVER['HTTP_HOST']==='cw.dudubashi.com') {
	require_once('WxPayConfig.production.php');
} else {
	require_once('WxPayConfig.testing.php');
}