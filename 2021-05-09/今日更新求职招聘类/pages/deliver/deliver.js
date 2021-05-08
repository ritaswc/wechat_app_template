// pages/deliver/deliver.js
let myRequire = require('../../utils/wxrequire').myRequire;
let markClick = require('../../utils/wxrequire').markClick;
const url = require('../../utils/requireurl.js').url;
Page({
  data:{
    current:0,
    showloding:false,
    nodeliver:false,
    nolook:false,
    nointerview:false,
    noinappropriate:false,
    deliver_success:[],
    have_look:[],
    interview:[],
    inappropriate:[]
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 2000000
    })
    let _that = this;
    wx.setStorage({ key:"pullDown", data:1})
    try {
      let user_ticket = wx.getStorageSync('user_ticket');
      let deliver_success  ,nodeliver ;
      myRequire(user_ticket,1,_that)//==========投递成功
     

    } catch (e) {
        console.log( e, '异步获取信息失败' )
    }
  },
  onReady:function(){
    // 页面渲染完成

  },
  onShow:function(){
    // 页面显示

  },
  switchSlider:function(e){
    let current = e.currentTarget.dataset.index;
    let _type =  parseInt( current)+1 ; //用于对应板块下的下拉刷新;
    let user_ticket = wx.getStorageSync('user_ticket');
    this.setData({
        current:current
    })
    let _that = this;
    wx.setStorage({ key:"pullDown", data:_type})
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 2000000
    })
     myRequire(user_ticket,_type,_that) //从新请求数据

  },
  changeSlider:function(e){
    let current = e.detail.current;
    let _type =  parseInt( current)+1 ; //用于对应板块下的下拉刷新;
    let user_ticket = wx.getStorageSync('user_ticket');
    this.setData({
        current:current
    })
    let _that = this;
     wx.setStorage({ key:"pullDown", data:_type})
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 2000000
    })
    myRequire(user_ticket,_type,_that) //从新请求数据
  },
  remindHr:function(e){  //提醒HR
      let has_remind = e.currentTarget.dataset.click;
      let company_id = e.currentTarget.dataset.company_id;
      let job_id = e.currentTarget.dataset.job_id;
      let user_ticket = wx.getStorageSync('user_ticket');
      let _that = this;
      if( has_remind == 0 ){ //已经提醒过了,去职位详情页
            wx.navigateTo({
                url: '../jobDetail/jobDetail?job_id='+job_id,
                fail:function(){
                    console.log("goJOB详情页失败")
            }
        })
      }else{  //去提醒hr
            wx.showToast({
                title: '加载中...',
                icon: 'loading',
                duration: 2000000
            })
            wx.request({
              url: url+'/resume/remindhr',
              header: {
                  'content-type': 'application/x-www-form-urlencoded'
              },
              data: {
                  'user_ticket':user_ticket,
                  'company_id':company_id
              },
              method: 'POST',
              success: function(res){
                  let status = res.data.status;
                  if( status == 1 ){
                      myRequire(user_ticket,1,_that)
                      wx.showToast({
                        title: '提醒成功',
                        icon: 'success',
                        duration: 1000
                    })
                  }
              },
              fail: function() {
                console.log('提醒hr请求失败')
              }
            })
      }
  },
  goZhihiWeiList:function(e){  //查看职位详情页
      let job_id = e.currentTarget.dataset.job_id;
      let user_ticket = wx.getStorageSync('user_ticket');

      markClick( user_ticket,job_id,function(res){  //标记文章已读
        let status = res.data.status;
        if( status == 1 ){
            wx.navigateTo({
                url: '../jobDetail/jobDetail?job_id='+job_id,
                fail:function(){
                    console.log("go公司详情页失败")
                }
            })
        }
      },function(){
          console.log( '标记已读失败' )
      })
  },
  onPullDownRefresh:function(){  //下拉刷新 在home.josn中开启;
      let user_ticket = wx.getStorageSync('user_ticket');
      let _type = wx.getStorageSync('pullDown');
      let _that = this;
      myRequire(user_ticket,_type,_that) //从新请求数据
      
  },
  inappropriate:function(e){  //暂不合适详细详情 &&  查看详情详细页
      let message_id = e.currentTarget.dataset.message_id;
      wx.navigateTo({
        url: '../massageContant/massageContant?message_id='+message_id,
        fail:function(){
          console.log("go暂不合适详情页失败")
        }
      })
  }
})