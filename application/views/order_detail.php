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
    .div_address { background: url(<?php echo $base_img_url?>bg_address.png) no-repeat; background-size: 100% 100%;}
    input[type='radio'] { margin-right: 0.3em; margin-top: -0.1em; background: url(<?php echo $base_img_url?>radio.png) no-repeat; background-size: 100% 100%; }
    input[type='radio']:checked { background: url(<?php echo $base_img_url?>radio_checked.png) no-repeat; background-size: 100% 100%; }

</style>
<body>
<div class="container">
    <?php if(empty($address)){?>
        <div class="div_address no_addr" onclick="location.href='/index.php/sxg/address'">
            <div class="default">请填写地址</div>
            <span class="arrow"></span>
            <input type="hidden" value="<?= $address['address_id'] ?>">
        </div>
        <?php }else{ ?>
        <div class="div_address no_addr">
            <div class="contact"><?= $address['name'] ?><?php if($address['sex']==1){echo '先生';}else{echo '女士';}?> &nbsp;&nbsp; <?= $address['mobile'] ?></div>
            <div class="address color_base">地址：<?php  echo $address['province'].$address['city'].$address['area'].$address['street']?></div>
            <span class="arrow"></span>
            <input type="hidden" value="<?= $address['address_id'] ?>">
        </div>
    <?php }?>

    <div class="div_maintenance">
        <div class="main_n border_bottom">
            <div class="main_l float_left">维修员</div>
            <div class="main_r float_left color_base">
                <div class="main_option border_bottom"><label><input name="maintenance_man" type="radio" checked value="1">随机指派<label></div>
                <div class="main_option">
                    <label><input type="radio" id="des_radio" name="maintenance_man" value="2">指定维修人员</label><br>
                    <input type="text" placeholder="请输入维修员工号" id="repair_id" class="input_designated">
                </div>
            </div>
        </div>
        <div class="main_t border_bottom">
            <div class="main_l float_left">上门时间</div>
            <div class="main_r float_left color_base">
                <select class="select_visit">
                    <option value="1">立即上门（快马加鞭）</option>
                    <option value="2">指定时间</option>
                </select>
                <i></i>
                <div class="uncertain">上门时间请于指定维修员协商</div>
            </div>
        </div>
        <div class="main_t border_bottom">
            <div class="main_l float_left">指定时间</div>
            <div class="main_r float_left color_base">
                <input type="time" style="width: 200px"/>
            </div>
        </div>
    </div>
    <div class="div_order_detail">
        <div class="d_row d_title border_bottom">订单详情</div>
        <div class="d_row color_base border_bottom">维修机器1</div>
        <div class="d_row color_base border_bottom">机器品牌:<?php echo $repair_detail['print_band']?>&nbsp;&nbsp;机器型号:<?php echo $repair_detail['print_model']?></div>
        <div class="d_row color_base">故障描述:<?php echo $repair_detail['repair_info']?></div>
    </div>

    <div class="remarks">
        <textarea class="textarea_remark" placeholder="备注"></textarea>
    </div>
    <div class="align_center">
        <button class="btn btn_l" type="button">下一步</button>
    </div>
</div>
<script type="text/javascript" src="<?php echo $base_js_url ?>zepto.min.js"></script>
<script type="text/javascript">
    $("[name='maintenance_man']").on("click",function(){
        if($(this).is(":checked") && $(this).val() == "2") {
            $(".div_maintenance").addClass("designated");
        } else {
            $(".div_maintenance").removeClass("designated");
        }
    });
</script>
</body>
</html>