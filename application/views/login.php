<?php
/**
 * Description:闪修哥快速下单
 * Author: LNC
 * Date: 2016/5/30
 * Time: 23:06
 */
$this->load->view('common/header',array('title'=>$title));
$base_js_url = $this->config->item('js_url');
?>

<body>
<div class="container">
    <div class="ct"></div>
    <div class="form_row border_bottom">
        <span class="font_base color_gray">手机号</span>
        <input type="tel" name="mobile" id="mobile" maxLength="11" class="input_phone color_base">
    </div>
    <div class="form_row">
        <span class="font_base color_gray">验证码</span>
        <input type="tel" name="verify" id="verify" maxLength="6" class="input_verify color_base">
        <button class="btn btn_verify" id="sendcode" type="button">发送验证码</button>
    </div>
    <div class="form_submit align_center">
        <button class="btn btn_l"  id = "login" type="button">下一步</button>
    </div>
</div>
<script type="text/javascript" src="<?php echo $base_js_url ?>/zepto.min.js"></script>
<script type="text/javascript">

    $("#sendcode").click(function(){
        var mobile = $("#mobile").val();
        if(mobile.length==0)
        {
            alert('请输入手机号码！');
            return;
        }
        if(mobile.length!=11)
        {
            alert('请输入有效的手机号码！');
            return;
        }
        //13开头，18开头，17开头，14开头
        var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1})|(14[0-9]{1}))+\d{8})$/;
        if(!myreg.test(mobile)) {
            alert('请输入有效的手机号码！');
            return;
        }
        //发送验证码
        var sender = document.getElementById("sendcode");
        countdown(sender);
        //发送验证码TODO

    });
    $("#login").click(function(){
        var number = $("#mobile").val();
        if(!number){
            alert('手机号码不能为空！');
            return;
        }
        if (!check_phone(number)) {
            alert('请输入正确的手机号码');
            return false;
        }
        var code = $("#verify").val();
        if (!code) {
            alert('请输入验证码');
            return false;
        }
        if (isNaN(code)) {
            alert('验证码为6位阿拉伯数字');
            return false;
        }
        $.ajax({
            url: "/index.php/sxg/check_code",
            type: "POST",
            data: {
                code: code,
                phone:number
            },
            success: function(json){
                var json = eval('(' + json + ')');
                if (json.result != '0000') {
                    alert(json.info);
                } else {
                    location.href = '/index.php/sxg/quick_order?>';
                }
            },
            error: function(){}
        });
    });

    function countdown(sender){
        $(".btn_verify").attr("disabled","").text("验证码(60s)");
        var time = 10, cd = function(){
            time--;
            if(time == 0) {
                sender.innerHTML = "重新获取";
                if($("#mobile").val().length == 11) {
                    $(".btn_verify").removeAttr("disabled");
                } else {
                    $(".btn_verify").attr("disabled","");
                }
            } else {
                sender.innerHTML = "验证码(" + time + "s)"
                setTimeout(cd, 1000);
            }
        };
        setTimeout(cd, 1000);
    }

    function check_phone(number){
        var pattern = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1})|(14[0-9]{1}))+\d{8})$/;
        return pattern.test(number) ? true : false;
    }

</script>
</body>
</html>
