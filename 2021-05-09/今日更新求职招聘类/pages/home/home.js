//home.js
let app = getApp();
let myrequire = require('../../utils/wxrequire');
let selectAction = require('../../utils/util').selectAction;
const all = require('../../utils/AELACTION.js').all;
const url = require('../../utils/requireurl.js').url;
Page({
  data:{
    action:"定位中...",
    imgUrls:[],
    remenList:[],
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let _that = this;
    wx.setStorageSync( "home_page",1,);
    wx.showNavigationBarLoading() //顶部显示加载动画
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 2000000
    })
    app.getUserInfo(function(userInfo){//调用应用实例的方法获取全局数据
      //更新数据
      wx.setStorageSync( "userInfo",userInfo);
      let latitude = app.globalData.latitude;  //经度
      let longitude = app.globalData.longitude;  //纬度
      let rawData = app.globalData.rawData;
      let signature  = app.globalData.signature;
      let code = app.globalData.code;
      let encryptedData = app.globalData.encryptedData;
      let iv = app.globalData.iv;
      let upData={
        'latitude':latitude,
        'longitude':longitude,
        'signature':signature,
        'code':code,
        'rawData':rawData,
        'encryptedData':encryptedData,
        'iv':iv
      };
        wx.request({ //==============================用户免登陆
        url: 'https://mobile-interface.veryeast.cn/weChat/wechat-applet/login',
        data: upData,
        method: 'POST',
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){
          let status = res.data.status;
          if( status == 1 ){
              // console.log( res )
              let data = res.data.data;
              let userCityId =selectAction( all, data.city); //===用户当前定位的城市
              // console.log( userCityId )
           
              _that.setData({
                action:userCityId.current_location
              })
              wx.setStorage({ key:"user_id", data: data.user_id });
              wx.setStorage({ key:"user_ticket", data: data.user_ticket });
              wx.setStorage({ key:"user_name", data: data.user_name });
              wx.setStorage({ key:"is_binding", data: data.is_binding });
              wx.setStorage({ key:"userCityId", data: userCityId });
          }else{
            console.log( res )
           
          }
        },
        fail: function(res) {
          console.log( res )
        }
      })
    })
     wx.request({ //=======================banner图
      url: url+'/util/ads',
      method: 'POST',
      success: function(res) {
        let status = res.data.status;
        let data = res.data.data;
        if( status == 1 ){
            _that.setData({
              imgUrls : data
            })
        }else{
            console.log(res,)
        }
      },
      fail: function(res){
        console.log(res,"请求失败")
      }
    })
    wx.request({//================================职位推荐
      url: url+'/user/recommended_jobs',
      method: 'POST',
      success: function(res) {
        let status = res.data.status;
        let remenList = res.data.data.list.data;
        let allPage = res.data.data.list.pager.allPages;
         remenList.map(function(item){
           return Object.assign(item, { update_time: item.update_time.substring(0,10) })
        })
        if( status == 1 ){
            _that.setData({
              remenList : remenList
            })
            //缓存当前页数总页数list;
            wx.setStorageSync( "home_page",1,);
            wx.setStorageSync( "all_pages",allPage);
            wx.setStorageSync( "remenList",remenList);

            wx.hideToast()
            wx.hideNavigationBarLoading()
        }else{
          console.log(res)
        }
      },
      fail: function(res){
        console.log(res,"请求失败")
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
    
  },
  go_to_search:function(){
    wx.navigateTo({
      url: '../search/search'
    })
  },
   onShareAppMessage: function () { //用户的分享
    return {
      title: '最佳东方',
      desc: '最佳东方小程序1.0版本上线啦,呼朋唤友来求职.',
      path: '/pages/home/home?userShare=true'
    }
  },
  onPullDownRefresh:function(){  //上拉刷新 在home.josn中开启;
    
  },
  onReachBottom:function(){//下拉 继续加载
    wx.showNavigationBarLoading();
    let _that = this;
    let bol = true;
    try {
      let page = parseInt( wx.getStorageSync('home_page') )+1;
      let all_page = wx.getStorageSync('all_pages');
      page <= all_page ? bol =  true:bol = false;
      if (page && all_page && bol ) { //成功获取页码,并且小于总页码;
          wx.request({//职位推荐
            url: url+'/user/recommended_jobs',
            header: {
              'content-type': 'application/x-www-form-urlencoded'
            },
            data:{
              "page":page
            },
            method: 'POST',
            success: function(res) {
              let status = res.data.status;
              let newList = res.data.data.list.data;
              let allPage = res.data.data.list.pager.allPages;
              newList.map(function(item){
                return Object.assign(item, { update_time: item.update_time.substring(0,10) })
              })
              if( status == 1 ){
                  let remenList = wx.getStorageSync('remenList');
                  let zhiweiList = remenList.concat(newList);
                  _that.setData({
                    remenList : zhiweiList
                  })
                  //缓存当前页数总页数list;
                  wx.hideNavigationBarLoading();
                  wx.setStorageSync("home_page", page);
                  wx.setStorageSync("all_pages", allPage);
                  wx.setStorageSync("remenList", zhiweiList);
              }else{
                 console.log("接口挂了")
              }
            },
            fail: function(){
              console.log("请求失败")
            }
          })
      }else{ //已加载完所有page;
        wx.showModal({
          title: '提示',
          content: '正在努力加载...'
        })
      }
    } catch (e) {
      console.log( "获取page || all_page出错" )
    }
  },
  goZhihiWeiList:function(e){  //公司详情页
      let c_userid = e.currentTarget.dataset.c_userid;
      let job_id = e.currentTarget.dataset.job_id;
      if( c_userid != 0 && job_id != 0 ){
        wx.navigateTo({
          url: '../position/position?job_id='+job_id+'&c_userid='+c_userid,
          fail:function(){
            console.log("go公司详情页失败")
          }
        })
      }
  }
})

