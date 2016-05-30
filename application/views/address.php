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
    <div class="address_list">
        <div class="sel_add_lbl color_base border_bottom">可选地址</div>
        <div class="div_addr border_bottom selected">
            <div class="float_left">
                <div class="contact">张三  先生 13245678967</div>
                <div class="address color_base">地址：深圳市南山区1202号（双湖湾）</div>
            </div>
            <a href="address_edit.html"><div class="edit float_right">编辑</div></a>
            <i></i>
        </div>
        <div class="div_addr">
            <div class="float_left">
                <div class="contact">张三  先生 13245678967</div>
                <div class="address color_base">地址：深圳市南山区1202号（双湖湾）</div>
            </div>
            <a href="address_edit.html"><div class="edit float_right">编辑</div></a>
            <i></i>
        </div>
    </div>
    <div class="btn_oprs align_center">
        <a href="<?php echo site_url('sxg/add_address')?>"><button class="btn btn_l" type="button">新增地址</button></a>
    </div>
</div>
<script type="text/javascript" src="<?php echo $base_js_url ?>zepto.min.js"></script>
<script type="text/javascript">
    $(".div_addr").on("click",function(){
        $(".div_addr").removeClass("selected");
        $(this).addClass("selected");
    });
</script>
</body>
</html>