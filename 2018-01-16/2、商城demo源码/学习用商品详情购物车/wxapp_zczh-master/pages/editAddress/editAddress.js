var app = getApp();
Page({
    data:{
      address:{},
      old_address:{}//待修改地址
    },
    onLoad: function () {
      if(app.globalData.edit_type=="add"){//如果是增地址
        this.setData({
          old_address:{}
        })
      }else{//若是修改的地址
          this.setData({
          old_address:app.globalData.old_address
        })
        console.log(app.globalData.old_address)
      }  
    },
    // 确认按钮
    formSubmit: function(e) { 
        var that = this;  
        var formData = e.detail.value;
        console.log(formData);
        if(formData.number=="" || formData.name==""|| formData.district==""|| formData.detail==""){
          wx.showToast({
            title: '请填写完整信息',
            icon: 'success',
            duration: 2000
          })
        }else{
          if(app.globalData.edit_type=="add"){//如果是增地址
            wx.request({
                url: 'https://www.520hhy.cn/m.php?g=api&m=wxshop&a=add_address&sess_id='+wx.getStorageSync(
                      'sess_id'
                      ),
                data: {
                  receiver:formData.name,
                  phone:formData.number,
                  province:formData.province,
                  city:formData.city,
                  area:formData.district,
                  street:formData.detail,
                },
                method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
                header: {"content-type":'application/x-www-form-urlencoded'}, // 设置请求的 header
                success: function(res){
                  // success
                  console.log(res)
                  //跳转到地址页
                  wx.navigateBack({
                      delta: 1
                  })
                },
                fail: function() {
                  // fail
                },
                complete: function() {
                  // complete
                }
            })   
          }else{//编辑地址
              wx.request({
                url: 'https://www.520hhy.cn/m.php?g=api&m=wxshop&a=update_address&sess_id='+wx.getStorageSync('sess_id'),
                data: {
                  id:app.globalData.edit_type,
                  receiver:formData.name,
                  phone:formData.number,
                  province:formData.province,
                  city:formData.city,
                  area:formData.district,
                  street:formData.detail,
                },
                method: 'POST', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
                header: {"content-type":'application/x-www-form-urlencoded'}, // 设置请求的 header
                success: function(res){
                  // success
                  console.log(res)
                  //跳转到地址页
                  wx.navigateBack({
                      delta: 1
                  })
                },
                fail: function() {
                  // fail
                },
                complete: function() {
                  // complete
                }
              })
          }    
        }       
    }
})