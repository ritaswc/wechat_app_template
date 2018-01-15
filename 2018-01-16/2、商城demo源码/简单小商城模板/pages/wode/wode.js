var WxAutoImage = require('../../js/wxAutoImageCal.js');
// pages/wode/wode.js
var app=getApp();
Page({
  data: {
    userImg:"../../image/defult_userimg.png",
    imgHeight:"80px",
    imgWidth:"80px",
    marginAuto:"0 auto",
    displayBlock:"block",
    borderCircle:"50%",
    userName:"xf_4072025",
    userOoperation1:[
      {"imgUrl":"../../image/m2.png","text":"我的订单"},
      {"imgUrl":"../../image/m6.png","text":"我的地址"},
      ],
    userOoperation2:[
      {"imgUrl":"../../image/m16.png","text":"客服中心"},
      {"imgUrl":"../../image/m13.png","text":"修改密码"}
    ]
  }
})
