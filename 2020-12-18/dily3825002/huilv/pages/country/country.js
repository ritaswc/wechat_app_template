
var logic = require("../../logic/logic.js")
//获取应用实例
var app = getApp()
Page({
    data: {
        //cos: []
    },
    onLoad: function(options) {
        // 页面初始化 options为页面跳转所带来的参数
        
    },
    clickCountryFun: function(e) {
        logic.currency( e.currentTarget.id,"CNY", function(data){
            
if(data!=null)
{
var obj = { name:data.currencyT_Name, code:data.currencyT, l:data.result,m:data.exchange*100 };
obj.m = obj.m.toFixed(2);
app.change = obj;
}
wx.navigateBack();
        
        


        } )
    },
    onShow: function() {
        // 页面显示
        var coss = [];
        for(var p in app.couns){  
            coss.push( { name:p,code:app.couns[p] } )
        }
        //console.log( coss )
        this.setData( {
            cos: coss
        } )
    },
    onHide: function() {
        // 页面隐藏
    },
    onUnload: function() {
        // 页面关闭
    }
})