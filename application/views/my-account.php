<?php
/**
 * Description:闪修哥快速下单
 * Author: LNC
 * Date: 2016/5/30
 * Time: 23:06
 */
$this->load->view('common/header',array('title'=>$title));
$base_js_url = $this->config->item('js_url');
$base_img_url = $this->config->item('img_url');
?>
<body>
<div class="container">
    <div class="ct"></div>
    <a href="<?php echo site_url('sxg/address')?>"><div class="info_row border_bottom">
            <img src="<?php echo $base_img_url?>icon-address.png">
            <div class="lbl">地址管理</div>
        </div></a>
    <a href="#"><div class="info_row border_bottom">
            <img src="<?php echo $base_img_url?>icon-red.png">
            <div class="lbl">我的红包</div>
        </div></a>
    <a href="<?php echo site_url('sxg/feedback')?>"><div class="info_row border_bottom">
            <img src="<?php echo $base_img_url?>icon-question.png">
            <div class="lbl">问题反馈</div>
        </div></a>
</div>
</body>
</html>