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
    <div class="align_center div_search empty">
        <input type="text" class="input_search" placeholder="搜索地名"><span class="btn_cancel">取消</span>
    </div>
    <div class="div_map border_bottom">
        <img src="<?php echo $base_img_url?>map.png" class="full_width">
    </div>
    <div class="div_sel_addrs">
        <div class="addr_r middle_box border_bottom">
            <span class="color_base">深圳市南山区1200号蝴蝶湾</span>
        </div>
        <div class="addr_r middle_box border_bottom">
            <span class="color_base">双湖湾</span><br>
            <span class="color_a8">深圳市南山区1201号</span>
        </div>
        <div class="addr_r middle_box border_bottom">
            <span class="color_base">双湖湾</span><br>
            <span class="color_a8">深圳市南山区1201号</span>
        </div>
        <div class="addr_r middle_box border_bottom">
            <span class="color_base">双湖湾</span><br>
            <span class="color_a8">深圳市南山区1201号</span>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo $base_js_url ?>zepto.min.js"></script>
<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>
<script type="text/javascript">
    $(function(){
        var search = $(".div_search");
        search.find(".input_search").on('input',function(){
            var val = $(this).val(),
                isEmpty = search.hasClass("empty");
            if(!val && !isEmpty) {
                search.addClass("empty");
                $(".addr_r").show().find("label").each(function(){
                    var txt = $(this).text();
                    $(this).before(txt);$(this).remove();
                });
            } else if(val && isEmpty){
                search.removeClass("empty");
            }
            if(val) {
                $(".addr_r").each(function(){
                    $(this).find("label").each(function(){
                        var txt = $(this).text();
                        $(this).before(txt);$(this).remove();
                    });
                    $(this).find("span").each(function(){
                        var htl = $(this).html();
                        if(htl.indexOf(val) > -1){
                            $(this).parent().show();
                            $(this).html(htl.replace(new RegExp(val, "ig"),'<label style="color:#fc9625">'+val+'</label>'));
                            return false;
                        } else {
                            $(this).parent().hide();
                        }
                    });
                })
            }
        });
        $(".btn_cancel").on("click",function(){
            search.addClass("empty");
            search.find(".input_search").val("");
            $(".addr_r").show().find("label").each(function(){
                var txt = $(this).text();
                $(this).before(txt);$(this).remove();
            });
        });
        var center = new qq.maps.LatLng(39.916527,116.397128);
        map = new qq.maps.Map($('.div_map')[0],{
            center: center,
            zoom: 13
        });
        citylocation = new qq.maps.CityService({
            complete : function(result){
                map.setCenter(result.detail.latLng);
            }
        });
        citylocation.searchLocalCity();
    });
</script>
</body>
</html>