<?php
/**
 * Description:闪修哥订单详情页面，支付页面
 * Author: LNC
 * Date: 2016/5/30
 * Time: 23:06
 */
$this->load->view('common/header',array('title'=>$title));

?>
<div class="container">
    <div class="order_detail_infos">
        <div class="order_group">
            <div class="ct_row">
                <span class="color_a8 s_title">订单状态：</span>
                <span class="s_status"><?php  echo $order['status']?></span>
            </div>
            <div class="ct_row">
                <span class="color_a8 s_title">下单时间：</span>
                <span class="color_base"><?php  echo date('Y-m-d H:i:s', $order['createtime'])?></span>
            </div>
            <div class="ct_row">
                <span class="color_a8 s_title">订 单 号：</span>
                <span class="color_base"><?php  echo $order['order_no']?></span>
            </div>
        </div>
        <div class="repair_group div_repair_price">
            <div class="d_row repair_price">
                维修费用：<span class="color_price">¥60</span>
                <img src="/static/images/icon-arrow.png" class="float_right arror_r" >
            </div>
        </div>
        <div class="repair_group div_repair_project hide">
            <div class="repair_subject">
                <div class="d_row color_base border_bottom">维修项目1</div>
                <div class="sp_row color_a8"><span>机器品牌</span><span>机器型号</span></div>
                <div class="sp_row sub_value">
                    <input type="text" name="input_device_name" value="爱普生">
                    <input type="text" name="input_device_model" value="vs212312">
                </div>
                <div class="sp_row color_a8"><span>故障现象</span></div>
                <div class="sp_row"><input type="text" name="input_device_fault" class="rsf_input" value="辅导解放军抵抗力"></div>
                <div class="sp_row color_a8"><span>更换配件/耗材</span><span>人工费</span></div>
                <div class="sp_row">
                    <select name="input_parts_replace"><option value='1'>是</option><option value='0'>否</option></select>
                    <input type="text" name="input_device_labour" value="100">
                </div>
                <div class="sp_row color_a8"><span>配件/耗材名称</span><span>配件/耗材价格</span></div>
                <div class="sp_row">
                    <input type="text" name="input_parts_name" value="墨盒">
                    <input type="text" name="input_parts_price" value="100">
                </div>
            </div>
        </div>
        <div class="order_group">
            <div class="ct_row">
                <span class="color_base"><?= $address['name'] ?><?php if($address['sex']==1){echo '先生';}else{echo '女士';}?></span>
                <a href="tel:<?= $address['mobile'] ?>"><img src="/static/images/phone.png" class="img_phone" ><span class="phone_num"><?= $address['mobile'] ?></span></a>
            </div>
            <div class="ct_row">
                <span class="color_a8">地址：</span>
                <span class="color_base"><?php  echo $address['province'].$address['city'].$address['area'].$address['street']?></span>
            </div>
        </div>
        <div class="repair_group">
            <div class="d_row border_bottom">
                <span class="color_a8 r_title">维修员</span>
                <div class="r_cont">
                    <label><input type="radio" checked><?php if($order['repair_assign'] == 1){ echo '随机指派';}else{ echo '指定维修人员';}?></label>
                    <div class="color_a8 nominee_info">
                        <div>姓名：张三&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;工号：123345</div>
                        <div>联系电话：13578656765</div>
                    </div>
                </div>
            </div>
            <div class="d_row">
                <span class="color_a8 r_title">上门时间</span>
                <span class=""><?php if($order['visit_option'] == 2){ echo date('Y-m-d H:i:s',$order['visit_time']);}elseif($order['visit_option'] == 3){echo '立即上门';}else{echo'跟维修人员商定';} ?></span>
            </div>
        </div>
        <div class="repair_group">
            <div class="d_row d_title border_bottom">订单详情</div>
            <div class="d_row color_base border_bottom">维修机器1</div>
            <div class="d_row color_base border_bottom">机器品牌:<?php echo $order['print_band']?>&nbsp;&nbsp;机器型号:<?php echo $order['print_model']?></div>
            <div class="d_row color_base">故障描述:<?php echo $order['repair_info']?></div>
        </div>
        <div class="repair_group remark_gp">
            <div class="d_row color_a8">备注:<?php echo $order['remark'] ?></div>
        </div>
    </div>
    <div class="align_center">
        <a href="javascript:;" onclick="jspay()"><button class="btn btn_xl" type="button">维修结束并支付</button></a>
    </div>
</div>
<script type="text/javascript" src="/static/js/zepto.min.js"></script>
<script type="text/javascript">
    $(".repair_price").on("click",function(){
        var rep = $(".div_repair_project");
        if(!rep.hasClass("hide")) {
            rep.addClass("hide");
            $(".repair_price .arror_r").removeClass("rx");
        } else {
            rep.removeClass("hide");
            $(".repair_price .arror_r").addClass("rx");
        }
    });
</script>
<script type="text/javascript">

    function jspay(){

        $.ajax({
            type: "POST",
            url: "/index.php/sxg/wxpay_params",
            data: {
            },
            dataType: "json",
            success: function(json){
                callpay(json);
            },
            error: function(){
                alert('加载失败');
            }
        });
    }
    //调用微信JS api 支付
    function jsApiCall(jsApiParameters)
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            jsApiParameters,
            function(res){
                if (res.err_msg == 'get_brand_wcpay_request:ok') {
                    location.href = location.href;
                } else if (res.err_msg == 'get_brand_wcpay_request:cancel') {
                    alert('您已取消支付，支付失败！');
                } else if (res.err_msg == 'get_brand_wcpay_request:fail') {
                    alert('支付失败！');
                } else {
                    alert(res.err_code+res.err_desc+res.err_msg);
                }
                return;
            }
        );
    }

    function callpay(jsApiParameters)
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall(jsApiParameters);
        }
    }
</script>
</body>
</html>