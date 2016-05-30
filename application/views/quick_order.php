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
    input[type='checkbox'] { margin-right: -0.3em; margin-top: -0.1em; background: url(<?php echo $base_img_url?>unchecked.png) no-repeat; background-size: 100% 100%; }
    input[type='checkbox']:checked { background: url(<?php echo $base_img_url?>checked.png) no-repeat; background-size: 100% 100%; }
</style>
<body>
<div class="container cw">
    <div class="select_row border_bottom">
        <div class="city float_left">
            <select class="color_base select_city">
                <option value="广州">广州</option>
                <option value="深圳" selected>深圳</option>
                <option value="东莞">东莞</option>
            </select>
            <span class="arrow"></span>
        </div>
        <div class="head float_right">
            <img src="<?php echo $base_img_url?>user-head.png" >
        </div>
    </div>
    <div class="repair_devices full_width">
        <div class="repair_row">
            <div class="device">
                <div class="color_base float_left name">报修机器1</div>
                <img src="<?php echo $base_img_url?>icon-arrow.png" class="float_right expend_mark">
            </div>
            <div class="device_detail">
                <div class="detail_row border_bottom">
                    <input type="text" placeholder="机器品牌" name="brand" class="color_base input">
                    <input type="text" placeholder="机器型号" name="model" class="color_base input input2">
                    <div><label><input type="checkbox" value="1" name="isAddPowder">&nbsp;&nbsp;加粉（加墨）</label></div>
                </div>
                <div class="detail_row border_bottom">
                    <div><label><input type="checkbox" value="2" name="isAddPowder">&nbsp;&nbsp;打印质量差（需拍照上传质量差页）</label></div>
                </div>
                <div class="detail_row border_bottom">
                    <div><label><input type="checkbox" value="3" name="isAddPowder">&nbsp;&nbsp;不能开机</label></div>
                </div>
                <div class="detail_row border_bottom detail_rowl">
                    <div><label><input type="checkbox" value="4" name="isAddPowder">&nbsp;&nbsp;卡纸(卡纸的位置需拍照上传或详细说明)</label></div>
                </div>
                <div class="detail_row">
                    <textarea class="description" placeholder="如非上述四项问题，请在这里描述" id="other"></textarea>
                    <p>上传照片</p>
                    <div class="div_images">
                        <div class="img_li float_left">
                            <img src="<?php echo $base_img_url?>add.png" class="full_width full_height">
                        </div>
                        <div class="img_li float_left">
                            <img src="<?php echo $base_img_url?>add.png" class="full_width full_height">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="addDevice_row">
        <button type="button" class="btn_addDevice" onclick="addDevice()">添加报修器</button>
    </div>
    <div class="align_center">
        <a href="<?php echo site_url('sxg/order_detail')?>"><button class="btn btn_l" type="button">下一步</button></a>
    </div>
</div>
<script type="text/javascript" src="<?php echo $base_js_url ?>zepto.min.js"></script>
<script type="text/javascript">
    $(".repair_devices").on("click",'.device',function(){
        var _self = $(this);
        $(".device").not(_self).parent().addClass("shrink");
        var rep = _self.parent();
        if(!rep.hasClass("shrink")) {
            rep.addClass("shrink");
        } else {
            rep.removeClass("shrink");
        }
    }).on("click",'.close',function(){
        $(this).parent().hide();
    });
    //添加报修器
    function addDevice(){
        var currentSize = $(".repair_row").size(),
            $new = $('<div class="repair_row">'+$(".repair_row").eq(currentSize-1).html()+'</div>');
        $(".repair_row").addClass("shrink");
        $(".repair_row").eq(currentSize-1).addClass("border_bottom");
        $new.find(".close").parent().remove();
        $new.find(".device .name").text("报修机器" + (currentSize+1));
        $new.find("input[type='checkbox']").removeAttr("checked");
        $new.find("input[type='text']").val("");
        $new.appendTo('.repair_devices');
    }
</script>
</body>
</html>