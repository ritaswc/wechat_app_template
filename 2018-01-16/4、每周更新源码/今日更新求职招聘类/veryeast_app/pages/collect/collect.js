// pages/collect/collect.js
let requireMassage = require('../../utils/wxrequire').requireMassage;
Page({
  data:{
    collect:[],
    nodelLIst:false
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
      wx.showToast({
        title: '加载中...',
        icon: 'loading',
        duration: 20000000
      })
    let user_ticket = wx.getStorageSync('user_ticket'),_that = this;
     requireMassage( '/user/favorited_jobs', user_ticket,function(res){
        let status = res.data.status;
        let list = res.data.data.list || [];
        list.map(function(item){
           return Object.assign(item,{favorite_date:item.favorite_date.substring(0,10)})
        })
        // console.log( list )
        if(status == 1){
            if( list.length == 0 ){ //== 数据为空
                _that.setData({
                  nodelLIst:true
                })
            }else{ //==  有数据
                _that.setData({
                  collect:list,
                  nodelLIst:false
                })
            }
            
            wx.hideToast();
        }else{
          console.log( res )
        }
    },function(){
      console.log( '接口调用失败' )
    })
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    let user_ticket = wx.getStorageSync('user_ticket'),_that = this;
     requireMassage( '/user/favorited_jobs', user_ticket,function(res){
     
        let status = res.data.status;
        let list = res.data.data.list || [];
        list.map(function(item){
           return Object.assign(item,{favorite_date:item.favorite_date.substring(0,10)})
        })
        if(status == 1){
            if( list.length == 0 ){ //== 数据为空
                _that.setData({
                  nodelLIst:true
                })
            }else{ //==  有数据
                _that.setData({
                  collect:list,
                  nodelLIst:false
                })
            }

            wx.hideToast();
        }else{
          console.log( res )
        }
    },function(){
      console.log( '接口调用失败' )
    })

  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  goZhiweiList:function(e){
    let c_userid = e.currentTarget.dataset.company_id;
    let job_id = e.currentTarget.dataset.job_id;
      wx.navigateTo({
        url: '../position/position?job_id='+job_id+'&c_userid='+c_userid,
        fail:function(){
          console.log("go公司详情页失败")
        }
      })
  }
})