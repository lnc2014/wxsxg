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
<style>
    input[type='radio'] { margin-right: 0.3em; margin-top: -0.1em; background: url(<?php echo $base_img_url?>radio.png) no-repeat; background-size: 100% 100%; }
    input[type='radio']:checked { background: url(<?php echo $base_img_url?>radio_checked.png) no-repeat; background-size: 100% 100%; }
</style>
<body>
<div class="container">
    <div class="contact_form border_bottom">
        <div class="div_contact border_bottom">
            <div class="contact_title color_a8 float_left">联系人</div>
            <div class="contact_info float_left">
                <div class="contact_name border_bottom"><input type="text" id="input_name" name="contact_name" placeholder="您的姓名"></div>
                <div class="contact_gender">
                    <label class="gender1"><input type="radio" name="gender" value='1' checked>先生</label>
                    <label><input type="radio" name="gender" value='0'>女士</label>
                </div>
            </div>
        </div>
        <div class="div_visit_addr border_bottom">
            <div class="visit_addr_title color_a8 float_left">上门地址</div>
            <div class="visit_addr_info float_left">
                <div class="addr_city"><select class="prov"></select><select class="city"></select><select class="dist"></select></div>
                <div class="addr_street">
                    <img src="<?php echo $base_img_url?>annotation.png" onclick="location='<?php echo site_url('sxg/address_map')?>'"><input type="text" name="input_street" placeholder="街道，小区">
                </div>
            </div>
        </div>
        <div class="div_house border_bottom">
            <span class="q_til">&nbsp;</span>
            <input type="text" class="input_qh" placeholder="楼号/门牌号，楼层" name="input_floor">
        </div>
        <div class="div_tel border_bottom">
            <span class="q_til color_a8">联系电话</span>
            <input type="text" class="input_qh" placeholder="请输入联系人电话" name="input_tel" >
        </div>
        <div class="div_default_addr">
            <label class="color_base"><input type="radio" name="input_default_addr" value="1">设为默认地址</label>
        </div>
    </div>
    <div class="btn_oprs align_center">
        <a href="address.html"><button class="btn btn_l" type="button" id="btn-sure">确&nbsp;认</button></a>
    </div>
</div>
<script type="text/javascript" src="<?php echo $base_js_url ?>zepto.min.js"></script>
<script type="text/javascript" src="<?php echo $base_js_url ?>citySelect.js"></script>
<script type="text/javascript">
    $(function(){
        $(".addr_city").citySelect({ url:'<?php echo $base_js_url ?>city.json', prov:'', city:'', dist:'', required:false, nodata:'none' });
    });
</script>
</body>
</html>