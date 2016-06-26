define('common/js/mod/common', function(require, exports, module) {

  var $ = require('common/js/mod/jquery-2.2.4');
  var ddCommon = {
      /**
       * 位置搜索关键词提示
       * 使用这个函数的时候需要引入
       * <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=b2tdmZtPCKfwW8iwn5svaT03"><\/script>
       * 
       * @param {[style]} obj_name [input框 id名]
       * 
       */
      BMap_Autocomplete: function (obj_name, type, f_info){
          var type = type || false;
          var f_info = f_info || false;
          
          // 百度地图API功能
          function G(id) {
              return document.getElementById(id);
          }                 // 初始化地图,设置城市和地图级别。
  
          var _input = $('#'+obj_name);
          var current_val = _input.val();
          _input.after('<div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>');
  
          // 创建
          var myGeo = new BMap.Geocoder();
  
          var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
              {"input" : obj_name
              ,"location" : null
              ,"types" : type
          });
          ac.setInputValue(current_val);
          ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
          var str = "";
                  var _value = e.fromitem.value;
                  var value = "";
                  if (e.fromitem.index > -1) {
                          value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
                  }    
                  str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;
  
                  value = "";
                  if (e.toitem.index > -1) {
                          _value = e.toitem.value;
                          value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
                  }    
                  str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
                  G("searchResultPanel").innerHTML = str;
          });
  
          var myValue;
          ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
          var _value = e.item.value;
                  // 正常关键词提示
                  if (type === false) {
                          myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
  
                          G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;
                  } 
                  // 自动定位关键词所在区域 省市区
                  else {
                          myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
                          var _location;
                          var t_desc;
                          myGeo.getPoint(myValue, function(point){
                                  //console.log(point);
                                  if (point) {
                                          myGeo.getLocation(point, function(cr){
                                                  //console.log(cr);  
                                                  _location = cr.addressComponents;
                                                  //myValue = _location.province +  _location.city +  _location.district;
                                                  myValue =  _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
                                                  ac.setInputValue(myValue);
                                                  t_desc = _location.province+','+_location.city+','+_location.district+','+point.lng+','+point.lat;
                                                  if(f_info !== false){    //省市区放到隐藏域 
                                                      $("#"+f_info).val(t_desc);
                                                  }
                                                  
                                                  return false;
                                          });
                                  } else {
                                          alert('对不起, 该地址无法自动定位, 请手动输入');
                                          return false;
                                  }
                          });
                  }
          });
      }
  }
  
  module.exports = ddCommon;

});
