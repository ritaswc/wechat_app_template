// pages/registConfirm/registConfirm.js
var app=getApp();
Page({
  data:{
    mobile:'',
    password:'',
    inviteCode:'',
    checkCode:'',
    buttonText:'获取中',
    disabled:false,
    new_text:''
  },
  getEvrCode:function(){
      console.log('按钮')
      var that=this;
      var mobile=that.data.mobile;
      console.log(mobile);
      app.getVerCode(mobile,function(success){
             console.log(success);
             if(success.data.success=="true"){
                console.log('短信发送成功');
                var time=function(waitTime){
                    if(waitTime<=0){
                       that.setData({
                           disabled:false,
                           buttonText:'获取中',
                           buttonText:'重新获取',
                           new_text:''
                         })
                    }else{
                         that.setData({
                            disabled:true,
                            buttonText:waitTime+'s后重发',
                            new_text:'new_style'
                          }) 
                         setTimeout(function(){
                           waitTime--; 
                           time(waitTime); 
                          },1000)
                    }
                
               }
               time(60);
             }else{
                wx.showModal({
                        content:success.data.error.msg,
                        showCancel:false
                   })
             }
      })
      
  },
  getCheckCode:function(event){
       var that=this;
       var checkCode=event.detail.value;
       that.setData({
            checkCode:checkCode
       })
  },
  //完成注册
  registSuccess:function(){
       var that=this;
       var mobile=that.data.mobile;
       var password=that.data.password;
       var inviteCode=that.data.inviteCode;
       var checkCode=that.data.checkCode
       app.registSuccess(mobile,password,inviteCode,checkCode,function(success){
               console.log(success);
               if(success.data.success=="true"){
                    wx.navigateBack({
                      delta: 1, // 回退前 delta(默认为1) 页面
                      success: function(res){
                        // success
                      }
                    })
               }else{
                      if(success.data.error.code==4122){
                            wx.showModal({
                            content:'验证码不能为空',
                            showCancel:false
                           })
                      }else{
                            wx.showModal({
                            content:success.data.error.msg,
                            showCancel:false
                           })                           
                      }
                     
               }
       })   
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    // console.log(options.mobile,options.password,options.inviteCode)
    var that=this;
    that.setData({
        mobile:options.mobile,
        password:options.password,
        inviteCode:options.inviteCode || ''
    })
    
    var time=function(waitTime){
          if(waitTime<=0){
             that.setData({
                   disabled:false,
                   buttonText:'获取中',
                   buttonText:'重新获取',
                   new_text:''
              })
          }else{
                         that.setData({
                            disabled:true,
                            buttonText:waitTime+'s后重发',
                            new_text:'new_style'
                          }) 
                         setTimeout(function(){
                           waitTime--; 
                           time(waitTime); 
                          },1000)
          }
          
      }
       app.getVerCode(options.mobile,function(success){
             console.log(success);
             if(success.data.success=="true"){
                  time(60)
             }else{
                 that.setData({
                     buttonText:'重新获取'
                 })
             }
      })
       
    
   
  }
})