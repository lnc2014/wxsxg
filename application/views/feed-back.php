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
         if(!feedback){
             alert('投诉与建议不能为空！');
         }
         $.ajax({
             url: "/index.php/sxg/add_feedback",
             type: "POST",
             data: {
                 feedback:feedback
             },
             success: function(json){
                 json = $.parseJSON(json);
                 if(json.result == '0000') {
                     alert('反馈成功！');
                     window.location = '/index.php/sxg/my_account';
                 }else {
                     alert('反馈失败');
                     window.location = '/index.php/sxg/my_account';
                 }
             },
             error: function(){
                 alert('加载失败');
             }
         });

     });
</script>
</body>
</html>