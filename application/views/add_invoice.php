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
    #checked{
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
        <div class="inv_row">
            <div class="inv_status border_bottom">
                <div class="color_base inv_st">已结束</div>
                <div class="inv_sc">
                    <input type="checkbox" id="checked"></div>
                <div class="color_base inv_date">下单时间：2016-02-16</div>
            </div>
            <div class="inv_pay">实际支付金额：<span class="color_orange">¥12</span></div>
        </div>
        <div class="inv_row">
            <div class="inv_status border_bottom">
                <div class="color_base inv_st">已结束</div>
                <div class="inv_sc">
                    <input type="checkbox" class="unchecked"></div>
                <div class="color_base inv_date">下单时间：2016-02-16</div>
            </div>
            <div class="inv_pay">实际支付金额：<span class="color_orange">¥12</span></div>
        </div>
    </div>
    <div class="inv_next">
        <div class="total float_left">
            <div>开票金额：<span class="color_orange">¥234</span></div>
            <div>开票金额满200元方可开票</div>
        </div>
        <a href="invoice_fill.html"><button type="button" class="btn full_height btn_next">下一步</button></a>
    </div>
</div>
</body>
</html>