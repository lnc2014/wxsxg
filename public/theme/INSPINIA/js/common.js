/**
* 基类js使用CW作为命名空间，其他自定义的类同理
*/
var CW = CW || {};
CW.demoFunction = function(){
	console.log('I\'m a demo function!');
};

//重新初始化checkbox
CW.initIcheckPlugin = function(){
    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green'
    });
};

function changeURLPar(destiny, par, par_value) { 
    var pattern = par+'=([^&]*)'; 
    var replaceText = par+'='+par_value; 
    if (destiny.match(pattern)) { 
        var tmp = '/\\'+par+'=[^&]*/'; 
        tmp = destiny.replace(eval(tmp), replaceText); 
        return (tmp); 
    } else { 
        if (destiny.match('[\?]')) { 
            return destiny+'&'+ replaceText; 
        } else { 
            return destiny+'?'+replaceText; 
        } 
    } 
    return destiny+'\n'+par+'\n'+par_value; 
}

function getIds(dom){
    var ids = '';
    dom.each(function (index, element) {
        ids += ',' + $(this).val();
    });
    return  ids.substring(1);
}

function checkAll() { //全选
    for (var i = 0; i < document.getElementsByName("checkInput[]").length; i++) {
        if(document.getElementsByName("checkInput[]")[i].disabled == false){
            document.getElementsByName("checkInput[]")[i].checked = document.getElementById("ifAll").checked;
        }
        var check_status = $("#ifAll").is(':checked');
        if(check_status === true){
            document.getElementsByName("checkInput[]")[i].parentNode.parentNode.style.backgroundColor="#ADD8E6";
        }
        else{
            document.getElementsByName("checkInput[]")[i].parentNode.parentNode.style.backgroundColor="";
        }
        
    }
}

//退出登录
function sign_out() {
    $.ajax({
        url: '/index.php/admin/sign_out',
        type: 'post',
        success: function(){
            window.location.href="/index.php/admin/login";
        }
    });
}

//按钮变灰/变亮
function show_button(button_id){
    $("#"+button_id).prop('disabled',false);
}

// 返回
function go_back() 
{
    history.go(-1);
}

//手机号验证
function checkPhoneNum(str) {  
    var pattern = /^1[34578]\d{9}$/;  
    if (pattern.test(str)) {  
        return true;  
    }
    return false;  
}

//车牌号验证
function checkBusCode(str) {
    return /(^[\u4E00-\u9FA5]{1}[A-Z0-9]{6}$)|(^[A-Z]{2}[A-Z0-9]{2}[A-Z0-9\u4E00-\u9FA5]{1}[A-Z0-9]{4}$)|(^[\u4E00-\u9FA5]{1}[A-Z0-9]{5}[挂学警军港澳]{1}$)|(^[A-Z]{2}[0-9]{5}$)|(^(08|38){1}[A-Z0-9]{4}[A-Z0-9挂学警军港澳]{1}$)/.test(str);
}

/**
 * 位置搜索关键词提示
 * 使用这个函数的时候需要引入
 * <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=b2tdmZtPCKfwW8iwn5svaT03"><\/script>
 * 
 * @param {[style]} obj_name [input框 id名]
 * 
 */
function BMap_Autocomplete(obj_name, type, f_info)
{
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
                                            //设置结果值
                                            ac.setInputValue(myValue);

                                            //省市区放到隐藏域 
                                            if(f_info !== false){    
                                                t_desc = _location.province+','+_location.city+','+_location.district+','+point.lng+','+point.lat;
                                                $("#"+f_info).val(t_desc);
                                            }

                                            return false;
                                    });
                            } else {
                                //console.log('我在公用的方法找不到位置啦~');
                                    ac.setInputValue('');
                                    swal('对不起, 该地址无法自动定位, 重新输入');
                                    
                                    return false;
                            }
                    });
            }
    });
    ac.addEventListener("onhighlight", function(e) {
        var _value = e.toitem.value;
        myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
        ac.setInputValue(myValue);
    });
}

/**
 * [用于需要清除jquery validate 错误提示的场景]
 * @param  {[string]} form [表单]
 * @param  {[string]} modal  [模态框]
 */
function clear_error_note(form_obj, modal_obj) {
    var modal_obj = modal_obj || null;

    // 清除非弹出框下的表单
    if (modal_obj === null) {
        form_obj.find('label.error').remove();
    } 
    // 清除弹出框下的表单
    else {
        modal_obj.on('hidden.bs.modal',function () {
            form_obj.find('label.error').remove();
        });
    }
    
}
        