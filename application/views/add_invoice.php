<?php
/**
 * Description:闪修哥发票申请
 * Author: LNC
 * Date: 2016/5/30
 * Time: 23:06
 */
$this->load->view('common/header',array('title'=>$title));
?>
<style>
    .checked{
        background: url(/static/images/checked.png) no-repeat;
        background-size: 100% 100%;
    }
    .unchecked{
        margin-right: -0.3em;
        margin-top: -0.1em;
        background: url(/static/images/unchecked.png) no-repeat;
        background-size: 100% 100%;
    }
</style>
<div class="container">
    <div class="inv_header">
        <div class="h_l color_a8">可开票订单列表</div>
        <a href="/index.php/sxg/invoice"><div class="h_r color_orange">开票历史</div></a>
    </div>
    <div class="invoice_list">
        <?php foreach($order_list as $val){?>
        <div class="inv_row">
            <div class="inv_status border_bottom">
                <div class="color_base inv_st">已结束</div>
                <div class="inv_sc">
                    <input type="checkbox" class="click checked" value="0"></div>
                <div class="color_base inv_date">下单时间：<?php echo date('Y-m-d H:i:s', $val['createtime']) ?></div>
            </div>
            <input type="hidden" value="<?php echo $val['repair_money'];?>" class="repair_money">
            <div class="inv_pay">实际支付金额：<span class="color_orange">¥<?php  echo $val['repair_money'];?></span></div>
        </div>
        <?php }?>
    </div>
    <div class="inv_next">
        <div class="total float_left">
            <div>开票金额：<span class="color_orange" id="money">¥234</span></div>
            <div>开票金额满200元方可开票</div>
        </div>
        <a href="/index.php/sxg/add_invoice_next" id="next_url"><button type="button" id="next" class="btn full_height btn_next">下一步</button></a>
    </div>
</div>
<script type="text/javascript" src="/static/js/zepto.min.js"></script>
<script>
    var all_repair_money = 0;
    $(function(){
        comulate();
        $('#money').text('￥'+all_repair_money);
        $('#next_url').attr('href','/index.php/sxg/add_invoice_next/'+all_repair_money);
        //checked 为1 unchecked为0
        $('.click').on('change', function(i){
            all_repair_money = 0;
            var input = $(this).val();
            if(input == 1){
                $(this).val(0);
                $(this).removeClass('unchecked');
                $(this).addClass('checked');
            }else{
                $(this).val(1);
                $(this).removeClass('checked');
                $(this).addClass('unchecked');
            }
            $(".inv_row").each(function (i) {
                var checked = $(this).find("input[class='click checked']").length;
                if(checked == 1){
                    var repair_money = $(this).find(".repair_money").val();
                    all_repair_money = all_repair_money*1 + repair_money*1;//加减法
                }
            });
            $('#money').text('￥'+all_repair_money);
            $('#next_url').attr('href','/index.php/sxg/add_invoice_next/'+all_repair_money);
            if(all_repair_money < 200){
                $('#next').attr('disabled',"true");
            }else{
                $('#next').removeAttr("disabled");
            }
        });
    });
    function comulate(){
        $(".inv_row").each(function (i) {
            var checked = $(this).find("input[class='click checked']").length;
            if(checked == 1){
                var repair_money = $(this).find(".repair_money").val();
                all_repair_money = all_repair_money*1 + repair_money*1;//加减法
            }
        });
        return all_repair_money;
    }

</script>
</body>
</html>