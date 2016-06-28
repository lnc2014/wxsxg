<?php
/**
 * Description:闪修哥发票申请
 * Author: LNC
 * Date: 2016/5/30
 * Time: 23:06
 */
$this->load->view('common/header',array('title'=>$title));
?>
<div class="container">
    <div class="inv_detail_list">
        <div class="inv_detail">
            <div class="color_base det_title">开票信息</div>
            <div class="color_a8 det_content">
                <p>开票时间：<?php  echo date('Y-m-d H:i:s', $invoice['createtime'])?></p>
                <p>开票金额：¥<?php  echo $invoice['invoice_money']?></p>
                <p>发票抬头：<?php  echo  $invoice['invoice_header']?></p>
                <p>配送方式：<?php  if($invoice['delivery_way'] == 1){ echo '人工送达';}else{echo '物流配送';}?></p>
            </div>
        </div>
        <div class="inv_detail">
            <div class="color_base det_title">收货人详情</div>
            <div class="color_a8 det_content">
                <p>收件人：<?php echo $address['name'];?></p>
                <p>联系电话：<?php echo $address['mobile'];?></p>
                <p>配送地址：<?php  echo $address['province'].$address['city'].$address['area'].$address['street'];?></p>
            </div>
        </div>
        <?php if(!empty($delivery)){ ?>
            <div class="inv_detail">
                <div class="color_base det_title">物流信息</div>
                <div class="color_a8 det_content">
                    <p>快递公司：<?php echo $delivery['company'];?></p>
                    <p>快递单号：<?php echo $delivery['delivery_num'];?></p>
                    <p>快递费用：包邮</p>
                </div>
            </div>
        <?php }?> 
    </div>
</div>
</body>
</html>