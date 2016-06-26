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

<body>
<div class="container">
    <div class="address_list">
        <div class="sel_add_lbl color_base border_bottom">可选地址</div>
        <?php
            foreach($address as $val){?>
                <div class="div_addr border_bottom">
                    <div class="float_left" onclick="location.href='/index.php/sxg/order_detail/<?php echo $order_id?>/<?php echo $val['address_id'] ?>'">
                        <div class="contact"><?php echo $val['name'].'&nbsp'; if($val['sex'] == 1){ echo '先生'.'&nbsp';}else{ echo '女士'.'&nbsp';} echo $val['mobile'];?>  </div>
                        <div class="address color_base">地址：<?php  echo $val['province'].$val['city'].$val['area'].$val['street']?></div>
                    </div>
                    <a href="/index.php/sxg/add_address/<?php echo $val['address_id'] ?>"><div class="edit float_right">编辑</div></a>
                    <i></i>
                </div>
         <?php  } ?>

    </div>
    <div class="btn_oprs align_center">
        <a href="/index.php/sxg/add_address/<?php echo $order_id?>"><button class="btn btn_l" type="button">新增地址</button></a>
    </div>
</div>
<script type="text/javascript" src="<?php echo $base_js_url ?>zepto.min.js"></script>
<script type="text/javascript">
    $(".div_addr").on("click",function(){
        $(".div_addr").removeClass("selected");
        $(this).addClass("selected");
    });
</script>
</body>
</html>