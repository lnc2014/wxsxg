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
    <div class="div_complaint">
        <textarea class="ta_complaint" placeholder="请输入您的投诉建议，我们会尽力为您解决" id="feedback"></textarea>
    </div>

    <div class="btn_oprs align_center">
        <button class="btn btn_l" type="button">提 交</button>
    </div>
</div>
<script type="text/javascript" src="<?php echo $base_js_url ?>zepto.min.js"></script>
<script type="text/javascript">
     $(".btn").click(function(){
         var feedback = $("#feedback").val();
         console.log(feedback);
         if(!feedback){
             alert('投诉与建议不能为空！');
         }
         $.ajax({
             url: "<?php echo site_url('sxg/add_feedback') ?>",
             type: "POST",
             data: {
                 feedback:feedback
             },
             success: function(json){
                 var json = eval('(' + json + ')');
                 if (json.result != '0000') {
                     alert(json.info);
                 } else {
                     location.href = '<?php echo site_url('sxg/my_account')?>';
                 }
             },
             error: function(){}
         });

     });
</script>
</body>
</html>