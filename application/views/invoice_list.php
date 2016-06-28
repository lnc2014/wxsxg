<?php
/**
 * Description:闪修哥发票列表
 * Author: LNC
 * Date: 2016/5/30
 * Time: 23:06
 */
$this->load->view('common/header',array('title'=>$title));
?>

<div class="container">
    <div class="inv_history_list">
        <?php foreach($invoice_list as $val){?>
            <a href='/index.php/sxg/invoice_detail/<?php echo $val['invoice_id'];?>'>
                <div class="inv_history">
                    <div class="color_base his_l">
                        <div>开票申请时间：<?php  echo date('Y-m-d H:i:s', $val['createtime'])?></div>
                        <div>开票金额：<span class="color_orange">¥<?php  echo $val['invoice_money'];?></span></div>
                    </div>
                    <div class="his_r"><span><?php
                            if($val['status'] == 1){
//                                1、受理中2、已开票（配送中）3、已完成
                                echo '受理中';
                            }else if($val['status'] == 2){
                                echo '已开票(配送中)';
                            }elseif($val['status'] == 3){
                                echo '已完成';
                            } ?></span><img src="/static/images/icon-arrow.png" class="arror_r" ></div>
                </div>
            </a>
                <?php } ?>
    </div>
    <div class="btn_oprs align_center">
        <a href="/index.php/sxg/add_invoice"><button class="btn btn_l" type="button">开票申请</button></a>
    </div>
</div>
</body>
</html>