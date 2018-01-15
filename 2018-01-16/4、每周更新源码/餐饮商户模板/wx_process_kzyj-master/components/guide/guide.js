//guide.js
//获取应用实例
var app = getApp()

  //事件处理函数
const chooseRole = (e) => {
    const role = e.currentTarget.id;  
    wx.redirectTo({
      url: `../../${role}/index/index`,
      success: function(res){
        // success
        wx.setStorageSync('role',role);
        // app.globalData.role = role;
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
}
//export default 
module.exports = {
    chooseRole
}