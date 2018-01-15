const url = require('../../utils/requireurl.js').url;
Page({
  data:{
    loading:false,
    contact:'',
    contant:''
  },
  
  formSubmit:function(e){
    let _that = this;
    let content = e.detail.value.opinion;
    let contact = e.detail.value.contant;
    let regPhone = /^1[3578]\d{9}$/;
    let regEmail  = /^[a-z\d_\-\.]+@[a-z\d_\-]+\.[a-z\d_\-]+$/i;
    if( content == "" ){
      wx.showModal({
        title: '提示',
        content: '反馈内容不能为空!',
      })
      return false
    }
    if( contact == "" ){
      wx.showModal({
        title: '提示',
        content: '手机号或者邮箱不能为空!',
      })
      return false
    }
    if( contact == "" && content == "" ){
      wx.showModal({
        title: '提示',
        content: '反馈内容,手机号或者邮箱不能为空!',
      })
      return false
    }
    if( (!regPhone.test( contact ) && !regEmail.test( contact )) || (regPhone.test( contact ) && regEmail.test( contact )) ){ //验证手机号或者邮箱的其中一个对 这个关系饶了俩小时^_^
      wx.showModal({
        title: '提示',
        content: '您输入的手机号或者邮箱有误!',
      })
      return false
    }else {
      this.setData({
        loading:true
      })
      let model,system,platform;
      wx.getSystemInfo({
        success: function(res) {
           model = res.model;
           system = res.system;
           platform = res.platform;
        }
      })
      wx.request({
        url: url+'/util/feedback',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        data: {
          'content':content,
          'contact':contact,
          'device_model':model, //手机型号
          'device_system ':system, //操作系统版本
          'app_version':platform  //客户端平台
        },
        method: 'POST',
        success: function(res){
          let status = res.data.status;
          if( status == 1 ){
             _that.setData({
                loading:false,
                contact:'',
                contant:''
              })
              wx.showToast({
                title:'成功',
                icon: 'success',
                duration: 1500
              })
            }
        },
        fail: function() {
          console.log( "意见反馈接口调用失败" )
        }
      })
    }
  }
})
