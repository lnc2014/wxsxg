<?php
/**
 * Description:闪修哥发票第二步
 * Author: LNC
 * Date: 2016/5/30
 * Time: 23:06
 */
$this->load->view('common/header',array('title'=>$title));
?>

<div class="container">
    <?php if(empty($address)){?>
        <div class="div_address no_addr" onclick="location.href='/index.php/sxg/address/'">
            <div class="default">请填写地址</div>
            <span class="arrow"></span>
            <input type="hidden" value="<?= $address['address_id'] ?>" id="address">
        </div>
    <?php }else{ ?>
        <div class="div_address no_addr" onclick="location.href='/index.php/sxg/address/'">
            <div class="contact"><?= $address['name'] ?><?php if($address['sex']==1){echo '先生';}else{echo '女士';}?> &nbsp;&nbsp; <?= $address['mobile'] ?></div>
            <div class="address color_base">地址：<?php  echo $address['province'].$address['city'].$address['area'].$address['street']?></div>
            <span class="arrow"></span>
            <input type="hidden" value="<?= $address['address_id'] ?>" id="address">
        </div>
    <?php }?>
    <br><br>
    <div class="form_row border_bottom">
        <span class="font_base color_base no_l">发票抬头</span>
        <input type="text" name="invoice_title" id="invoice_title" placeholder="请输入发票抬头" class="input_invoice_title color_base"/>
    </div>
    <div class="form_row kp_ct">
        <span class="font_base color_gray no_l">开票内容</span>
        <div class="inv_content">
            <select class="select_inv_type" id="invoice_content">
                <option value="0">打印耗材</option>
                <option value="1">办公用品</option>
                <option value="2">设备维修</option>
            </select>
            <i></i>
        </div>
        <textarea class="ta_inv_content"></textarea>
    </div>
    <div class="btn_oprs align_center">
        <button class="btn btn_l" type="button" id="submit">提 交</button>
    </div>
</div>
<script type="text/javascript" src="/static/js/zepto.min.js"></script>
<script>
    $(function(){
        $('#submit').click(function(){
            var address_id = $('#address').val();
            var invoice_title = $('#invoice_title').val();
            var invoice_content = $('#invoice_content').val();
            if(!address_id){
                alert('地址信息不能为空！');
                return;
            }
            if(!invoice_title){
                alert('发票抬头不能为空！');
                return;
            }
            $.ajax({
                type: "POST",
                url: "/index.php/sxg/add_invoice_data",
                data: {
                    address_id : address_id,
                    invoice_header : invoice_title,
                    invoice_content : invoice_content,
                    invoice_money : '<?php echo $money ?>'
                },
                dataType: "json",
                success: function(json){
                    if(json.result == '0000'){
                        window.location = '/index.php/sxg/invoice_success';
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
</script>

</body>
</html>