// pages/toRegister/toRegister.js
var app=getApp();
Page({
  data:{
     userTel:'',
     userPassw:'',
     inviteCode:''
  },
  telEvent:function(){
      wx.makePhoneCall({
        phoneNumber: '400-600-2063',
        success: function(res) {
          console.log('打电话成功')
        }
      })
  },
  userTel:function(event){
       this.setData({
           userTel:event.detail.value
       })
      //  var mobile=event.detail.value;
      //  console.log(mobile.length);
      //  if(mobile.length>0){
      //     this.setData({
      //        isDisabled:false
      //     })
      //     console.log(this.data.isDisabled)
      //  }

  },
  userPassw:function(event){
       this.setData({
           userPassw:event.detail.value
       })
  },
  inviteCode:function(event){
        this.setData({
          inviteCode:event.detail.value
       })
  },
  toRegist:function(){
      var that=this;
      var userTel=that.data.userTel;
      var userPassw=that.data.userPassw;
      var inviteCode=that.data.inviteCode;
      if(!userTel || userTel==""){
          wx.showModal({
            title:'提示',
            content:'手机号不能为空',
            success:function(res){
              if(res.confirm){
                console.log('点击确认键')
              }
            }
          })
          return;
      }
      var reg = /^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/;
      if(!reg.test(userTel)) {
         wx.showModal({
            content:'无效手机号',
            showCancel:false
         })
         return;
      }
      if(!userPassw || userPassw.length<6){
         wx.showModal({
            title:'提示',
            content:'密码不能低于6位'
         })
         return;
      }
      app.registCheck(userTel,inviteCode,function(success){
           console.log(success);
           if(success.data.success=="true"){
               wx.navigateTo({
                 url: '../registConfirm/registConfirm?mobile='+userTel+'&password='+userPassw+'&inviteCode='+inviteCode,
                 success: function(res){
                     app.getVerCode(userTel,function(success){
                         console.log(success);
                         if(success.data.success=="true"){
                             console.log('验证码发送成功');
                         }else{ 
                            wx.showModal({
                                content:success.data.error.msg,
                                showCancel:false
                            })
                         }
                     })
                 }
               })
           }else{
              //  console.log(success.data.error.code)
               if(success.data.error.code == 4800){
                   wx.showModal({
                    content:"您输入的邀请码不正确，是否继续注册?",
                    confirmText:"继续注册",
                    confirmColor:"#000000",
                    success:function(res){
                       if(res.confirm){
                           wx.navigateTo({
                             url: '../registConfirm/registConfirm',
                             success: function(res){
                               // success
                             }
                           })
                       }
                    }
                  })
               }else{
                  wx.showModal({
                        content:success.data.error.msg
                     })
               }
      
           }
      })
  },
  onLoad:function(options){     
       
       
  }

})