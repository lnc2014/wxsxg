/*TMODJS:{"version":1,"md5":"693171517d2c60686cec3700500e2965"}*/
var template=require("./template");module.exports=template("cross",function(a){"use strict";var b=this,c=(b.$helpers,b.$escape),d=a.hidden_id,e=a.val_id,f=a.del_id,g="";return g+=' <div class="weui_cell"> <div class="weui_cell_hd"><i class="i_cross"></i></div> <div class="weui_cell_bd weui_cell_primary"> <input type="hidden" id="',g+=c(d),g+='"> <input class="weui_input" id="',g+=c(e),g+='" placeholder="\u8bf7\u8f93\u5165\u9014\u5f84\u5730\u70b9"> </div> <div class="weui_cell_hd"><i class="i_clear"></i></div> <div class="weui_cell_hd"><i class="i_del" id="',g+=c(f),g+='"></i></div> </div> ',new String(g)});