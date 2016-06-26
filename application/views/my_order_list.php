<?php
/**
 * Description:闪修哥快速下单
 * Author: LNC
 * Date: 2016/5/30
 * Time: 23:06
 */
$this->load->view('common/header',array('title'=>$title));
?>
<!--1,待接单2，待上门3,检测中4,调配件5,维修中6,待点评7,已结束8,已取消-->
<div class="container">
    <div class="com_status_options">
        <div class="option" <?php if($status_info == '全部'){echo 'selected';} ?> onclick="location.href='/index.php/sxg/my_order_list/0'" >全部</div>
        <div class="option <?php if($status_info == '待接单'){echo 'selected';} ?>" onclick="location.href='/index.php/sxg/my_order_list/1'">待接单</div>
        <div class="option <?php if($status_info == '待上门'){echo 'selected';} ?>" onclick="location.href='/index.php/sxg/my_order_list/2'">待上门</div>
        <div class="option <?php if($status_info == '检测中'){echo 'selected';} ?>" onclick="location.href='/index.php/sxg/my_order_list/3'">检测中</div>
        <div class="option <?php if($status_info == '调配件'){echo 'selected';} ?>" onclick="location.href='/index.php/sxg/my_order_list/4'">调配件</div>
        <div class="option <?php if($status_info == '维修中'){echo 'selected';} ?>" onclick="location.href='/index.php/sxg/my_order_list/5'">维修中</div>
        <div class="option <?php if($status_info == '待点评'){echo 'selected';} ?>" onclick="location.href='/index.php/sxg/my_order_list/6'">待点评</div>
        <div class="option <?php if($status_info == '已结束'){echo 'selected';} ?>" onclick="location.href='/index.php/sxg/my_order_list/7'">已结束</div>
        <div class="option <?php if($status_info == '已取消'){echo 'selected';} ?>" onclick="location.href='/index.php/sxg/my_order_list/8'">已取消</div>
    </div>
    <div class="com_cur_status">
        <span class="color_base"><?php echo $status_info?></span><i></i>
    </div>
    <div class="com_order_list">
        <?php
            foreach($order as $val){?>
                <div class="com_order">
                    <div class="color_orange com_s_title"><?php echo $val['status']?></div>
                    <div class="color_base com_s_date">下单时间：<?php echo $val['status']?></div>
                    <div class="com_button">
                        <button type="button" class="btn btn_half" onclick="order_detail(<?php echo $val['order_id']?>)"><?php if($val['status'] == '待接单' || $val['status'] == '待上门'){echo '取消订单';}elseif($val['status'] == '维修中' || $val['status'] == '调配件'|| $val['status'] == '检测中'){echo '维修结束并支付';}elseif($val['status'] == '待点评' || $val['status'] == '已结束'){echo '我要点评';}else{echo '已取消';}?></button><a href="/index.php/sxg/add_feedback"><button type="button" class="btn btn_half bg_orange">投 诉</button></a>
                    </div>
                </div>
            <?}
        ?>
    </div>
</div>
<script type="text/javascript" src="/static/js/zepto.min.js"></script>
<script type="text/javascript">
    $(".com_cur_status").on("click",function(){
        var so = $(".com_status_options");
        if(!so.hasClass("show")) {
            so.addClass("show");
        } else {
            so.removeClass("show");
        }
    });
    $(".com_status_options").on("click",function(e){
        var cur_option = $(e.target);
        $(".com_status_options").removeClass("show");
        if(!cur_option.hasClass("selected")) {
            $(this).find(".option").removeClass("selected");
            cur_option.addClass("selected");
            var s = cur_option.attr("data-status");
            if(s == 0) {
                $(".com_order").show();
            } else {
                $(".com_order").not("[data-status='"+s+"']").hide();
                $(".com_order[data-status='"+s+"']").show();
            }
            $(".com_cur_status span").text(cur_option.text());
        }
    });
    function order_detail(order_id){
        location.href = '/index.php/sxg/pay_order_detail/'+ order_id;
    }
</script>
</body>
</html>