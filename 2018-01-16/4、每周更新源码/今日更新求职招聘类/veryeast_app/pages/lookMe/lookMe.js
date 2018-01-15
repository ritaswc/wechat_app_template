// pages/lookMe/lookMe.js
let requireMassage = require('../../utils/wxrequire').requireMassage;
Page({
  data:{
    lookimg:'img/_05.png',
    company:[],
    nodeliver:false
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    wx.showToast({
      title:'加载中...',
      icon:'loading',
      duration:100000
    })
  },
  onReady:function(){
    // 页面渲染完成
    let _that = this;
    let user_ticket = wx.getStorageSync('user_ticket');
    requireMassage('/user/resume_viewed_list',user_ticket,function(res){
      console.log( res )
      let status = res.data.status;
      if( status == 1 ){
        let data = res.data.data.list.slice(0,100) || [];
        data.map(function(item){
            Object.assign(item,{company_industry:item.company_industry == null ? '其他行业':item.company_industry})
        })
        if( data.length == 0 ){ //没有数据
            _that.setData({
              nodeliver:true
            })
        }else{
            _that.setData({
              company:data,
              nodeliver:false
            })
        }
      }else{
        let errdata = res.data.errMsg;
        wx.hideToast();
        wx.showModal({
          title: '失败',
          showCancel:false,
          content: errdata,
          success: function(res) {
          }
        })
      }
      wx.hideToast()
    },function(){
      console.log( '接口调用失败' )
    })
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  goCompanyList:function(e){
    let c_userid = e.currentTarget.dataset.c_userid;
    wx.navigateTo({
      url: '../companyDetail/companyDetail?c_userid='+c_userid,
      fail: function() {
        console.log( 'go公司详情页失败' )
      }
    })
  }
})