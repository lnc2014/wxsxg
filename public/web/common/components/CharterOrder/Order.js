define('common/components/CharterOrder/Order.jsx', function(require, exports, module) {

  "use strict";
  var React = require('common/js/mod/react-with-addons');
  // var Link = require('/common/js/mod/ReactRouter.min.js').Link;
  var tasklist = [];
  var atask = { name: '西双版纳 - 江南', desc: '2016/6/5 8:00 --> 2016/9/2 9:00，点击查看详情' };
  tasklist[0] = atask;
  tasklist[1] = atask;
  tasklist[2] = atask;
  tasklist[3] = atask;
  var CharterOrder = React.createClass({
      render: function () {
          return (React.createElement("div", {className: "weui_cells weui_cells_form"}, React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_hd"}, React.createElement("label", {className: "weui_label"}, "qq")), React.createElement("div", {className: "weui_cell_bd weui_cell_primary"}, React.createElement("input", {className: "weui_input", type: "number", pattern: "[0-9]*", placeholder: "请输入qq号"}))), React.createElement("div", {className: "weui_cell weui_vcode"}, React.createElement("div", {className: "weui_cell_hd"}, React.createElement("label", {className: "weui_label"}, "验证码")), React.createElement("div", {className: "weui_cell_bd weui_cell_primary"}, React.createElement("input", {className: "weui_input", type: "number", placeholder: "请输入验证码"})), React.createElement("div", {className: "weui_cell_ft"}, React.createElement("img", {src: "./images/vcode.jpg"}))), React.createElement("div", {className: "weui_cell"}, React.createElement("div", {className: "weui_cell_hd"}, React.createElement("label", {className: "weui_label"}, "银行卡")), React.createElement("div", {className: "weui_cell_bd weui_cell_primary"}, React.createElement("input", {className: "weui_input", type: "number", pattern: "[0-9]*", placeholder: "请输入银行卡号"}))), React.createElement("div", {className: "weui_cell weui_vcode weui_cell_warn"}, React.createElement("div", {className: "weui_cell_hd"}, React.createElement("label", {className: "weui_label"}, "验证码")), React.createElement("div", {className: "weui_cell_bd weui_cell_primary"}, React.createElement("input", {className: "weui_input", type: "number", placeholder: "请输入验证码"})), React.createElement("div", {className: "weui_cell_ft"}, React.createElement("i", {className: "weui_icon_warn"}), React.createElement("img", {src: "./images/vcode.jpg"})))));
      }
  });
  module.exports = CharterOrder;
  

});
