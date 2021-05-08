//app.js
var service=require('utils/service.js')
App({
  onLaunch: function () {
    console.log('appLaunch......')
  },
  config:{
     host:'http://www.pcw365.com/ecshop2/MobileAPI/v4.1'
  },
  //登录
  login:function(userTel,userPassw,success){
      service.request('/myecshop2/user/index/login',{
            act:"login",
            temp_buyers_mobile:userTel,
            temp_buyers_password:userPassw
      },success);
  },
  // 退出账号
  logOut:function(res){
     service.request('/myecshop2/user/index/logout',{},res); 
  },
  //检查邀请码
  registCheck:function(mobile,inviteCode,success){
      // console.log(mobile,inviteCode) 
      var data={
        mobile:mobile
      }
      if(inviteCode){
         data.invitation=inviteCode
      }
      service.request('/myecshop2/user/index/invitation',
      data
      ,success)
  },
  //获取短信验证码
  getVerCode:function(mobile,success){
      service.request('/myecshop2/user/index/getCheckCode',{mobile:mobile,type:1},success)
  },
  //完成注册
  registSuccess:function(mobile,password,inviteCode,verCode,success){
      service.request('/myecshop2/user/index/reg',{
           temp_buyers_mobile: mobile,
           temp_buyers_password:password,
           invitation:inviteCode,
           checkcode:verCode
      },success)  
  },
  //获取辅材一级目录
  getCataList:function(area_id,success){
       service.request('/myecshop2/home/material/sub',{
            area_id:area_id,
            type:1
       },success)
  },
  //获取全部城市
  getAllCity:function(success){
       service.request('/myecshop2/city/show',{
            
       },success)
  },
  //定位当前城市
  getLocalCity:function(success){
       service.request('/myecshop2/city/location',{

       },success) 
  },
  //获取辅材二级目录
  getGoodsList:function(area_id,brand_id,success){
    console.log(area_id,brand_id)
       service.request('/myecshop2/home/material/brandlist2',{
             area_id:area_id,
             brand_id:brand_id,
             page:1,
             type:1,
             pageSize:50
       },success)
  },
  globalData:{
    userInfo:null
  }
})