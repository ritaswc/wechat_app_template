var dialog = {}

dialog.loading = function(title = "加载中"){
    wx.showToast({title:title,icon:'loading',duration:10000})
}

dialog.hide = function(){
    wx.hideToast();
}

dialog.toast = function(title="提示您"){
    wx.showToast({title:title,icon:'success'})
}


module.exports = dialog