define('common/components/NewTask/NewTask.jsx', function(require, exports, module) {

  "use strict";
  var React = require('common/js/mod/react-with-addons');
  var tasks = [];
  var task = {
      start_time: '2015-01-01 08:00',
      end_time: '2015-01-02 09:00',
      start_place: '天堂',
      end_place: '九寨沟',
      pepole_num: '33',
      contacter: 'ladyPiaPia',
      contact_phone: '18565678256',
      eat_or_not: '是',
      live_or_not: '是',
      left_gold: '2000'
  };
  tasks[0] = task;
  var NewTask = React.createClass({
      render: function () {
          var items = tasks.map(function (name, index) {
              return (React.createElement("div", {key: index, className: "dd_card"}, React.createElement("div", {className: "weui_cells_title"}, "基本信息"), React.createElement("div", {className: "weui_cells"}, React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_bd"}, React.createElement("p", null, "发车时间")), React.createElement("div", {className: "weui_cell_ft weui_cell_primary"}, name.start_time)), React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_bd"}, React.createElement("p", null, "返程时间")), React.createElement("div", {className: "weui_cell_ft weui_cell_primary"}, name.end_time)), React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_bd"}, React.createElement("p", null, "上车地点")), React.createElement("div", {className: "weui_cell_ft weui_cell_primary"}, name.start_place)), React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_bd"}, React.createElement("p", null, "下车地点")), React.createElement("div", {className: "weui_cell_ft weui_cell_primary"}, name.end_place))), React.createElement("div", {className: "weui_cells_title"}, "乘客和联系人"), React.createElement("div", {className: "weui_cells"}, React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_bd"}, React.createElement("p", null, "乘车人数")), React.createElement("div", {className: "weui_cell_ft weui_cell_primary"}, name.pepole_num)), React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_bd"}, React.createElement("p", null, "联系人")), React.createElement("div", {className: "weui_cell_ft weui_cell_primary"}, name.contacter)), React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_bd"}, React.createElement("p", null, "联系人手机")), React.createElement("div", {className: "weui_cell_ft weui_cell_primary"}, name.contact_phone))), React.createElement("div", {className: "weui_cells_title"}, "其他"), React.createElement("div", {className: "weui_cells"}, React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_bd"}, React.createElement("p", null, "包餐饮")), React.createElement("div", {className: "weui_cell_ft weui_cell_primary"}, name.eat_or_not)), React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_bd"}, React.createElement("p", null, "包住宿")), React.createElement("div", {className: "weui_cell_ft weui_cell_primary"}, name.live_or_not)), React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_bd"}, React.createElement("p", null, "尾款金额")), React.createElement("div", {className: "weui_cell_ft weui_cell_primary"}, name.left_gold))), React.createElement("a", {href: "javascript:;", className: "weui_btn weui_btn_warn mt_15"}, "确认并呼叫用户")));
          });
          return React.createElement("div", {className: "card_container", className: "dpage"}, items);
      }
  });
  module.exports = NewTask;
  

});
