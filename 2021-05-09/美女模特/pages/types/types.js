var app = getApp()
var dialog = require("../../utils/dialog")
var wxNotificationCenter = require("../../utils/WxNotificationCenter")

Page({
    data:{
      types:[]
    },
    onLoad:function(){
        this.setData({
           types:wx.getStorageSync('types')
        })
    },
    changeTypeStatus:function(e){
        var value = e.currentTarget.dataset.value
        var currentType = wx.getStorageSync('currentType') 
        var showCount = 0, isCurrentHide = false
        var types = this.data.types.map(function(item){
            if(item.value == value){
                item.is_show = !item.is_show
                if(value == currentType && !item.is_show){
                    isCurrentHide = true;
                }
            }
            if(item.is_show){
                showCount++;
            }
            return item
        })
        //当前选中的被隐藏了
        if(showCount < 1){
            dialog.toast("不能全部隐藏")
            return;
        }
        if(isCurrentHide){
            types.every(function(item){
                if(item.is_show){
                     wx.setStorageSync('currentType', item.value)
                     return false
                }else{
                    return true
                }
            })
        }
        this.setData({types:types})
        wx.setStorageSync("types", types)
        wxNotificationCenter.postNotificationName("typesChangeNotification")
    }
})