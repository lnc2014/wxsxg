define('common/components/jidiao/index', function(require, exports, module) {

  function getQueryString(name){
       var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
       var r = window.location.search.substr(1).match(reg);
       if(r!=null)return  unescape(r[2]); return null;
  }
  window.order;
  window.car;
  var order_id = getQueryString('order_id');
    $.ajax({
        type: "POST",
        url: "/admin/order_detail",
        data: {
            order_id : order_id
        },
        dataType: "json",
        success: function(json){
            var data = json.data;
            order = data;
            if(json.result == '0000'){
                $('#from_place').text(data.from_place);
                $('#to_place').text(data.to_place);
                $('#order_num').text(data.order_num);
                $('#person_num').text(data.person_num);
                $('#user_remark').text(data.user_remark);
                $('#bus_num').text(data.bus_num);
                $('#name').text(data.contact_name);
                $('#mobile').text(data.contact_phone);
                $('#start_time').text(data.start_time);
                $('#title').text(data.status+'订单');
                if(data.charter_type == 0){
                    $('.back_time').hide();
                }
                if(data.mid_sites.length>0){
                    data.mid_sites.reverse();
                    $.each(data.mid_sites, function(index, item){
                        var html = '<div class="tk_it"><i class="i_cross"></i><div >'+item.address+'</div></div>';
                        $('.from_place').after(html);
                    });
                }
                $('#back_time').text(data.back_time);
                $('#other_info').text(data.other_info);
                if(data.is_invoice == 1){
                    $('#invoice').text(data.invoice_title);
                }else {
                    $('.invoice').hide();
                }
                //判断订单的状态
                if(data.status == '待付订金' || data.status == '待付尾款'){
                    $("#pay").css('display','block');
                    get_img(data.order_id);
                }
                if(data.status == '待排车'){
                    $("#paiche").css('display','block');
                    $(".car").css('display','block');
                    $.ajax({
                        type: "POST",
                        url: "/charter/charter/arrange_car",
                        data: {
                            order_id:data.order_id,
                            bus_num:data.bus_num,
                            person_num:data.person_num,
                            start_time:data.start_time2,
                            back_time:data.back_time2,
                            charter_type:data.charter_type,
                            is_driver_id:1
                        },
                        dataType: "json",
                        success: function(json){
                            console.log(json);
                            if(json.flag == 1){
                                var data = json.res;
                                car = data;
                                $.each(data, function(index, item){
                                    var html = '<div class="tk_wrap car">' +
                                        '<div class="tk_it"><span class="tk_it_title">车牌号</span><span class="tk_ipt" id="bus_code">'+item.bus_code+'</span></div>' +
                                        '<div class="tk_it"><span class="tk_it_title">司机</span><span class="tk_ipt" id="user_name">'+item.user_name+'</span></div>'
                                        +'<div class="tk_it"><span  class="tk_it_title">联系方式</span><span id ="phone_number" class="tk_ipt">'+item.phone_number+'</span></div>'
                                        +'</div>';
                                    $('#people').after(html);
                                });
                            }else{
                                alert('暂无司机可分配');
                            }
                        },
                        error: function(){
                            alert("加载失败");
                        }
                    });
                }

            }else if(json.result == '0004') {
                window.location = '/admin/login';
            }else if(json.result == '0005'){
                window.location = 'admin/register';
            }else{
                alert(json.info);
            }
        },
        error: function(){
            alert("加载失败");
        }
    });
    var get_img = function(order_id){
        $.ajax({
            type: "POST",
            url: "/admin/success_order_qcode",
            data: {
                order_id : order_id
            },
            dataType: "json",
            success: function(json){
                var data = json.data;
                console.log(data);

                if(json.result == '0000'){
                    $("#tdCode").attr('src',data.img_url);
                }else if(json.result == '0004') {
                    window.location = '/admin/login';
                }else if(json.result == '0005'){
                    window.location = 'admin/register';
                }else{
                    alert(json.info);
                }
            },
            error: function(){
                alert("加载失败");
            }
        });
    }
  console.dir(order_id);

});
