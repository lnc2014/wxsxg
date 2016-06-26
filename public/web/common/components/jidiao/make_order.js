define('common/components/jidiao/make_order', function(require, exports, module) {

  var $ = require('common/js/mod/jquery-2.2.4');
  var ddCommon = require('common/js/mod/common');
  var crossTemp = require('common/js/mod/template/cross');//途径站点模板


  $(function(){
  	//建立百度地图自动完成input输入框
      ddCommon.BMap_Autocomplete('from_place',true,'hidden_fplace');
      ddCommon.BMap_Autocomplete('to_place',true,'hidden_tplace');

      //增加途径站点按钮
      $("#addCrossBtn").on('click',function(){
      	crossStationFunc('add');
      });
      //删除途径站点按钮
      $("#charterOrder").on('click','.i_del',function(e){
      	var cellObj = $(e.target).parent().parent();//站点dom对象
      	crossStationFunc('del',cellObj);
      });

      $("#invoice").change(function () {
          var invoice = $("#invoice").val();
          var invoice_title = $("#invoice_title2").val();

          if(invoice == 'on' && invoice_title == 0){
              $("#invoice_show").css('display','block');
              $("#invoice_title2").val(1);
          }else{
              $("#invoice_show").css('display','none');
              $("#invoice_title2").val(0);
          }
      });
      $("#charter_type").change(function(){
          var charter_type = $("#charter_type").val();
          $("#charter_type_value").val(charter_type);
          //单程不显示回程时间
          if(charter_type == 0){
              $("#back_show").hide();
          }else{
              $("#back_show").show();
          }
      });
      $('#eat').change(function(){
          var eat = $("#eat").val();
          var is_eat = $("#is_eat").val();
          if(eat == 'on' && is_eat == ''){
              $("#is_eat").val('0001');
          }else {
              $("#is_eat").val('');
          }
      });
      $('#live').change(function(){
          var live = $("#live").val();
          var is_live = $("#is_live").val();
          if(live == 'on' && is_live == ''){
              $("#is_live").val('0002');
          }else {
              $("#is_live").val('');
          }
      });
      $('#invoice').change(function(){
          var invoice = $("#invoice").val();
          var is_invoice = $("#is_invoice").val();
          if(invoice == 'on' && is_invoice == ''){
              $("#is_invoice").val('0003');
          }else {
              $("#is_invoice").val('');
          }
      });

  });
      var submit_ti = function(){
          var from_place = $('#from_place').val();
          var to_place = $('#to_place').val();
          var start_time = $('#start_time').val();
          var back_time = $('#back_time').val();
          var person_num = $('#person_num').val();
          var bus_num = $('#bus_num').val();
          var contact_name = $('#contact_name').val();
          var contact_phone = $('#contact_phone').val();
          var added_rule = $('#added_rule').val();
          var charter_type =  $("#charter_type_value").val();
          var user_remark =  $("#user_remark").val();//备注
          var invoice_title =  $("#invoice_title").val();//发票抬头
          var neg_price =  $("#neg_price").val();//协商价格

          if (!check_phone(contact_phone)) {
              alert('请输入正确的手机号码');
              return false;
          }
          if(!from_place){
              alert('出发地不能为空！');
              return;
          }
          if(!to_place){
              alert('目的地不能为空！');
              return;
          }
          if(!start_time){
              alert('出发时间不能为空！');
              return;
          }
          if(!person_num){
              alert('用车人数不能为空！');
              return;
          }
          if(!contact_name){
              alert('联系人不能为空！');
              return;
          }
          if(!contact_phone){
              alert('联系方式能为空！');
              return;
          }
          if(!contact_name){
              alert('联系人不能为空！');
              return;
          }
          if(!bus_num){
              bus_num = 1;
          }
          var is_invoice = $('#is_invoice').val();
          var is_eat = $('#is_eat').val();
          var is_live = $('#is_live').val();
          var added_rule = '';
          if(is_live && is_eat && is_invoice){
              is_live = ',' +is_live + ',';
          }else if(is_live && is_invoice && !is_eat){
              is_live = is_live + ',';
          }else if(is_live && !is_invoice && is_eat){
              is_live = ',' + is_live;
          }
          if(!is_live && is_invoice && is_eat){
              is_eat = is_eat + ',';
          }
          var hidden_fplace = $.trim($("#hidden_fplace").val());
          var hidden_tplace = $.trim($("#hidden_tplace").val());
          added_rule = is_eat + is_live + is_invoice;//附加规则
          var invoice_need = 0;
          if(is_invoice && !invoice_title){
              alert('发票抬头不能为空！');
              return;
          }
          if(is_invoice){
              invoice_need = 1;
          }
          if(!neg_price){
              alert('协商价格不能为空！');
              return;
          }

          $.ajax({
              type: "POST",
              url: "/user/add_user_order",
              data: {
                  from_place : from_place,
                  to_place : to_place,
                  start_time : start_time,
                  back_time : back_time,
                  person_num : person_num,
                  bus_num : bus_num,
                  contact_name : contact_name,
                  contact_phone : contact_phone,
                  added_rule : added_rule,
                  charter_type : charter_type,
                  user_remark : user_remark,
                  invoice_title : invoice_title,
                  invoice_need : invoice_need,
                  hidden_fplace : hidden_fplace,
                  hidden_tplace : hidden_tplace,
                  neg_price : neg_price,
                  motorcade_id : 1
              },
              dataType: "json",
              success: function(json){
                  if(json.result == '0000'){
                      window.location = '/user/order_list';
                  }else {
                      alert(json.info);
                  }
              },
              error: function(){
                  alert("加载失败");
              }
          });
      }

  /**
   * [检查电话号码]
   * @param  {[type]} number [号码]
   * @return {[boolean]}     [是否合格]
   */
      function check_phone(number){
          var pattern = /^(((13[0-9]{1})|(15[0-9]{1})|(17[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
          return pattern.test(number) ? true : false;
      }

  /**
   * [操作途径站点函数]
   * @param  {[string]} action [增加或者删除]
   * @param  {[jq对象]} delobj [要删除的dom对象]
   */
      function crossStationFunc(action,delobj){
      	//action 增加or删除
      	switch(action){
      		case 'add'://增加
      			var data = {};
  				var tempdata = crossTemp(data);
  				$("#fromPlaceCell").after(tempdata);
  				break;

  			case 'del'://删除
  				delobj.remove();
  				break;
      	}

      }

});
