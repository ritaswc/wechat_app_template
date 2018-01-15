//封装post请求
function post(url,data,success){
   wx.request({
        url: url,
        data:data,
        method: 'POST',
        header: {'content-type': 'application/x-www-form-urlencoded'},
        success: function(res) {
          success(res)
        }
    })
}

//封装get请求
function got(url,data,success){
   wx.request({
        url: url,
        data:data,
        header: {'content-type': 'application/json'},
        success: function(res) {
          success(res)
        }
    })
}

//获取地理位置
function getLoaction(success,fail){
    wx.getLocation({
        type: 'wgs84',
        success: function(res) {success(res)},
        fail:function(res){fail(res)}
     })
}

//一些弹窗
function saveOk(success){
    wx.showToast({
      title: '下单成功！当前正在派件中',
      duration: 10000 
    });
    success()
}
function saveErr(){
    wx.showToast({
      title: '下单失败！请重试',
      duration: 10000 
    })
}



module.exports = {
  post:post,
  got:got,
  getLoaction:getLoaction,
  saveOk:saveOk,
  saveErr:saveErr
}