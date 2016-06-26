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
<!--引入wenupload CSS-->
<link rel="stylesheet" type="text/css" href="/static/webupload/webuploader.css">
<body>
<div class="container cw">
    <div class="select_row border_bottom">
        <div class="city float_left">
            <select class="color_base select_city">
                <option value="2">广州</option>
                <option value="1" selected>深圳</option>
            </select>
            <span class="arrow"></span>
        </div>
        <div class="head float_right">
            <img src="/static/images/user-head.png" >
        </div>
    </div>
    <div class="repair_devices full_width " id="repair" >
        <div class="repair_row border_bottom" >
            <div class="device">
                <div class="color_base float_left name">报修机器1</div>
                <img src="/static/images/icon-arrow.png" class="float_right expend_mark">
            </div>
            <div class="device_detail">
                <div class="detail_row border_bottom">
                    <input type="text" placeholder="机器品牌" name="brand"  id='brand1' class="color_base input">
                    <input type="text" placeholder="机器型号" name="model"  id='model1' class="color_base input input2">
                    <div><label><input type="checkbox" id="problem1">&nbsp;&nbsp;加粉（加墨）</label></div>
                </div>
                <div class="detail_row border_bottom">
                    <div><label><input type="checkbox" id="problem2" name="isAddPowder">&nbsp;&nbsp;打印质量差（需拍照上传质量差页）</label></div>
                </div>
                <div class="detail_row border_bottom">
                    <div><label><input type="checkbox" id="problem3" name="isAddPowder">&nbsp;&nbsp;不能开机</label></div>
                </div>
                <div class="detail_row border_bottom detail_rowl">
                    <div><label><input type="checkbox" id="problem4" name="isAddPowder">&nbsp;&nbsp;卡纸(卡纸的位置需拍照上传或详细说明)</label></div>
                </div>
                <div class="detail_row">
                    <textarea class="description" id="description"  placeholder="如非上述四项问题，请在这里描述"></textarea>
                    <p>上传照片</p>
                    <div class="div_images">
                        <div class="img_li add_li float_left">
                            <div class="file" id="file_upload">选择图片</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="addDevice_row">
        <button type="button" class="btn_addDevice" onclick="addDevice()">添加报修器</button>
        <button type="button" class="btn_addDevice" onclick="delDevice()">删除报修器</button>
    </div>
    <input type="hidden" id="is_problem1" value="0">
    <input type="hidden" id="is_problem2" value="0">
    <input type="hidden" id="is_problem3" value="0">
    <input type="hidden" id="is_problem4" value="0">
    <input type="hidden" id="img1" value="">
    <input type="hidden" id="img2" value="">
    <input type="hidden" id="img3" value="">
    <div class="align_center">
<!--        <a href="/index.php/sxg/order_detail"><button class="btn btn_l" type="button">下一步</button></a>-->
        <button class="btn btn_l" type="button" id="submit_order">下一步</button>
    </div>
</div>
<script type="text/javascript" src="/static/js/zepto.min.js"></script>
<script type="text/javascript" src="/static/js/jquery.1.71.js"></script>
<script type="text/javascript" src="/static/webupload/webuploader.js"></script>
<script type="text/javascript">
    var i = 1;
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
    $(function(){
        $("#repair2").live("click",function(){
            if(!$("#repair2").hasClass("shrink")) {
                $("#repair2").addClass("shrink");
            } else {
                $("#repair2").removeClass("shrink");
            }
        });
        $("#repair3").live("click",function(){
            if(!$("#repair3").hasClass("shrink")) {
                $("#repair3").addClass("shrink");
            } else {
                $("#repair3").removeClass("shrink");
            }
        });
        $("#problem1").click(function () {
            var is_problem1 = $("#is_problem1").val();
            if(is_problem1 == 0){
                $("#is_problem1").val(1);
            }else if(is_problem1 == 1){
                $("#is_problem1").val(0);
            }
        });
        $("#problem2").click(function () {
            var is_problem2 = $("#is_problem2").val();
            if(is_problem2 == 0){
                $("#is_problem2").val(1);
            }else if(is_problem2 == 1){
                $("#is_problem2").val(0);
            }
        });
        $("#problem3").click(function () {
            var is_problem3 = $("#is_problem3").val();
            if(is_problem3 == 0){
                $("#is_problem3").val(1);
            }else if(is_problem3 == 1){
                $("#is_problem3").val(0);
            }
        });
        $("#problem4").click(function () {
            var is_problem4 = $("#is_problem4").val();
            if(is_problem4 == 0){
                $("#is_problem4").val(1);
            }else if(is_problem4 == 1){
                $("#is_problem4").val(0);
            }
        });
        //提交表单
        $("#submit_order").click(function(){
            var problem1 = $("#is_problem1").val();
            var problem2 = $("#is_problem2").val();
            var problem3 = $("#is_problem3").val();
            var problem4 = $("#is_problem4").val();
            var brand1 = $("#brand1").val();
            var model1 = $("#model1").val();
            var description1 = $("#description").val();
            if(!brand1){
                alert('机器名牌不能为空');
                return;
            }
            if(!model1){
                alert('机器型号不能为空');
                return;
            }
            if(!description1 && (problem1 == 0) && (problem2 == 0)&& (problem3 == 0)&& (problem4 == 0) ){
                alert('需要维修的问题不能为空');
                return;
            }
            if(problem1 == 1){
                problem1 = '0001' + ',';
            }
            if(problem2 == 1){
                problem2 = '0002' + ',';
            }
            if(problem3 == 1){
                problem3 = '0003' + ',';
            }
            if(problem4 == 1){
                problem4 = '0004' + ',';
            }
            var img1 = $('#img1').val();
            var img2 = $('#img2').val();
            var img3 = $('#img3').val();

            if(img1){
                img1 = img1 + ';';
            }
            if(img2){
                img2 = img2 + ';';
            }
            if(img3){
                img3 = img3 + ';';
            }
            var img = img1 + img2 + img3;
            console.log(img);
            var repair_option = problem1 +   problem2 +  problem3 + problem4;//附加规则
            $.ajax({
                type: "POST",
                url: "/index.php/sxg/add_order",
                data: {
                    print_band : brand1,
                    print_model : model1,
                    repair_option : repair_option,
                    repair_problem : description1,
                    img:img
                },
                dataType: "json",
                success: function(json){
                    if(json.result == '0000'){
                        window.location = '/index.php/sxg/order_detail/'+json.data.order_id;
                    }else {
                        alert(json.info);
                    }
                },
                error: function(){
                    alert("加载失败");
                }
            });

        });

    });
    function delDevice(){
        var currentSize = $(".repair_row").size();
        if(currentSize == 1){
            alert("最少要有一个报修机器");
            return;
        }else {
            $('#repair'+currentSize).remove();
            i = i -1;
        }
    }
    function addDevice(){
        if(i >= 3){
            alert("最多添加三个报修机器");
            return;
        }
        i = i +1;
        console.log(i);
        var html = '<div class="repair_row shrink border_bottom" id="repair'+i+ '">'+
            '<div class="device">'+
            '<div class="color_base float_left name">报修机器'+i+ '</div>'+
            '<img src="/static/images/icon-arrow.png" class="float_right expend_mark">'+
            '</div>'+
            '<div class="device_detail">'+
            '<div class="detail_row border_bottom">'+
            '<input type="text" placeholder="机器品牌" name="brand" id ="brand" class="color_base input">'+
            '<input type="text" placeholder="机器型号" name="model"  id ="model"  class="color_base input input2">'+
            '<div><label><input type="checkbox" value="1" name="isAddPowder">&nbsp;&nbsp;加粉（加墨）</label></div>'+
            '</div>'+
            '<div class="detail_row border_bottom">'+
            '<div><label><input type="checkbox" value="2" name="isAddPowder">&nbsp;&nbsp;打印质量差（需拍照上传质量差页）</label></div>'+
            '</div>'+
            '<div class="detail_row border_bottom">'+
            '<div><label><input type="checkbox" value="3" name="isAddPowder">&nbsp;&nbsp;不能开机</label></div>'+
            '</div>'+
            '<div class="detail_row border_bottom detail_rowl">'+
            '<div><label><input type="checkbox" value="4" name="isAddPowder">&nbsp;&nbsp;卡纸(卡纸的位置需拍照上传或详细说明)</label></div>'+
            '</div>'+
            '<div class="detail_row">'+
            '<textarea class="description" placeholder="如非上述四项问题，请在这里描述" id="other"></textarea>'+
            '<p>上传照片</p>'+
            '<div class="div_images">'+
            '<div class="img_li float_left">'+
            '<img src="/static/images/add.png" class="full_width full_height">'+
            '</div>'+
            '<div class="img_li float_left">'+
            '<img src="/static/images/add.png" class="full_width full_height">'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>'+
            '</div>';
            var test = "repair_"+ (i-1);
            if(i == 2){
                $('#repair').after(html);
            }else{
                $('#repair2').after(html);
            }

            $(".repair_row").addClass('shrink');
    }
</script>
<!--上次图片-->
<script>
    var images = 0;
    // 图片上传demo
    jQuery(function() {
        var $ = jQuery,
        // Web Uploader实例
            uploader;
        // 初始化Web Uploader
        uploader = WebUploader.create({
            // 自动上传。
            auto: true,
            // swf文件路径
            swf: '/static/webupload/Uploader.swf',
            // 文件接收服务端。
            server: '/index.php/sxg/upload',
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#file_upload',
            // 只允许选择文件，可选。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });

        uploader.on( 'fileQueued', function() {
            if(images >= 3){
                alert('最多只能上传三张图片');
                destroy();
                return;
            }
        });
        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        uploader.on( 'uploadSuccess', function( file, data ) {
            if(data.result == '0000'){
                images = images + 1;
                alert('上传成功');
                var html = '<div class="img_li float_left"  id="show_img'+images+'">'+
                    '<img src="'+data.data.path+'" class="full_width full_height">'+
                    '<img src="/static/images/close.png" class="close1" id="del_img'+images+'">'+
                    '</div>';
                $('.div_images').append(html);
                $('#img'+images).val(data.data.path2);
            }
        });
        // 文件上传失败，现实上传出错。
        uploader.on( 'uploadError', function( file, data) {
            alert(data.info);
            destroy();
        });
    });
    $(function(){
        $("#del_img1").live("click",function(){
            $('#show_img1').remove();
            $('#img1').val('');
            images = images - 1;
        });
        $("#del_img2").live("click",function(){
            $('#show_img2').remove();
            $('#img2').val('');
            images = images - 1;
        });
        $("#del_img3").live("click",function(){
            $('#show_img3').remove();
            $('#img3').val('');
            images = images - 1;
        });
    });
</script>
</body>
</html>