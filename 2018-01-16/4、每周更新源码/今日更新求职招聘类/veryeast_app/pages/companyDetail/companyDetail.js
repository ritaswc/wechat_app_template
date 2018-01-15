// pages/companyDetail/companyDetail.js
var WxParse = require('../../wxParse/wxParse.js');
const url = require('../../utils/requireurl.js').url;
Page({
  data:{
    c_user:[],
    xingji:'',
    c_list:[],
    biaoqian:''
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let c_userid = options.c_userid//企业详情id
    let user_ticket =  wx.getStorageSync('user_ticket');
    console.log( c_userid, user_ticket)
    let _that = this;
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 20000000
    })

     wx.request({//企业详情
      url: url+'/job/company_detail',
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      data: {'company_id':c_userid},
      method: 'POST',
      success: function(res){
        let status = res.data.status;
        let data = res.data.data;
        // console.log( data )
        
        let description = data.description;

        WxParse.wxParse('description', 'html', description, _that);

        let biaoqian = data.label;
        let c_list = data.c_list;
        let xingji = data.star_level,xingxing;
        if( status == 1 ){
          wx.setNavigationBarTitle({
            title: data.company_name
          })
          _that.setData({
              biaoqian : biaoqian,
              c_user:data
          })
          switch (xingji){
              case '五星' :
                xingxing=5;
                break;
              case '准五星':
                xingxing =5;
                break;
              case '四星':
                xingxing=4;
                break;
              case '三星':
                xingxing=3;
                break;
              case '二星':
                xingxing=2;
                break;
              case '一星':
                xingxing=1;
              break;
          }
          _that.setData({
              xingji:xingxing,
              c_list:c_list
          })
          wx.hideToast()
        }else{
          console.log("企业详情接口挂了")
        }
      },
      fail: function() {
        console.log("请求企业详情失败")
      }
    })

  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})