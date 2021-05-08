// pages/jobDetail/jobDetail.js
var WxParse = require('../../wxParse/wxParse.js');
let upDataExperiesce = require('../../utils/wxrequire').upDataExperiesce;
const url = require('../../utils/requireurl.js').url;
Page({
  data:{
    position:{},
    otherList:[],
    is_applied:false,
    showloding:false,
    is_favorited:'../position/image/_06.png',
    has_favorited:''
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let job_id = options.job_id;
    let _that = this;
    let user_ticket = wx.getStorageSync('user_ticket');
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 2000000
    })
    wx.request({ //职位详情
      url: url+'/job/detail',
      data: {'job_id':job_id, 'user_ticket':user_ticket},
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      method: 'POST',
      success: function(res){
        let status = res.data.status;
        
        // console.log( otherList )
        if( status == 1 ){
          
          let data = res.data.data;
          let decription = data.decription;
          
          WxParse.wxParse('article', 'html', decription, _that,); //解析html;

          let otherList = data.list.map(function(item){
            return Object.assign({},item,{update_time:item.update_time.substring(0,10)})
          });

          wx.setNavigationBarTitle({
            title: data.job_name
          })
          _that.setData({
            position : data,
            otherList:otherList,
            decription:decription,
            has_favorited:data.is_favorited
        })
          let is_applied = data.is_applied;
          let is_favorited = data.is_favorited;
          if( is_applied == 1 ){ //职位是否申请
            _that.setData({
                is_applied:true
            })
          }
          if( is_favorited ==1 ){ //职位是否收藏
              _that.setData({
                is_favorited:'../position/image/_09.png'
            })
          }  

           wx.hideToast()
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

      },
      fail: function() {
        console.log("请求职位详情失败")
      }
    });
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  goZhihiWeiList:function(e){
      let job_id = e.currentTarget.dataset.job_id;
      wx.redirectTo({
        url: '../jobDetail/jobDetail?job_id='+job_id,
        fail:function(){
          console.log("go公司详情页失败")
        }
      })
  },

   positionCollect:function(e){ //========================================职位收藏按钮
    let job_id = e.currentTarget.dataset.job_id;
    let _that = this;
    let user_ticket = wx.getStorageSync('user_ticket');
    let is_applied = e.currentTarget.dataset.is_applied; //0没有收藏; 1收藏
    let data={
      'user_ticket':user_ticket,
      'job_id':job_id
    }
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 20000000
    })
    if( is_applied == 0 ){ //==============去收藏
        upDataExperiesce('/user/add_favorite_job',data,function(res){
            console.log( res )
            let status = res.data.status;
            if( status == 1 ){
                wx.showToast({
                  title: '收藏成功',
                  icon: 'success',
                  duration: 1000
                })
                _that.setData({
                    is_favorited:'../position/image/_09.png',
                    has_favorited:1
                })
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
        },function(res){
            console.log( res ,'接口调用失败' )
        })
    }else if( is_applied == 1 ){ //=============取消收藏

        upDataExperiesce('/user/delete_favorite_job',data,function(res){
            // console.log( res )
            let status = res.data.status;
            if( status == 1 ){
                wx.showToast({
                  title: '取消收藏',
                  icon: 'success',
                  duration: 1000
                })
                _that.setData({
                    is_favorited:'../position/image/_06.png',
                    has_favorited:0
                })
            }else{
                wx.showModal({
                  title: '失败',
                  showCancel:false,
                  content: '职位取消收藏失败',
                  success: function(res) {
                  }
                })
            }
        },function(res){
            console.log( res ,'接口调用失败' )
        })
    }
  },
  positionApply:function(e){ //===============================立即申请按钮
    this.setData({
      showloding:true
    })
    let job_id = e.currentTarget.dataset.job_id;
    let user_ticket = wx.getStorageSync('user_ticket');
    let _that = this;
    let client_id ;
     wx.getSystemInfo({
        success: function(res) {
          if( res.platform == 'ios' ){
              client_id = 2;
          }else{
              client_id = 1;
          }
        }
      })
    let data={
      'user_ticket':user_ticket,
      'job_id':job_id,
      'client_id':client_id
    }
    // console.log( data );

    upDataExperiesce('/user/apply',data,function(res){
      // console.log( res );
      let status = res.data.status;
      if( status == 1 ){
        wx.showToast({
          title: '申请成功',
          icon: 'success',
          duration: 1000
        })
        _that.setData({
          showloding:false,
          is_applied:true
        })
      }else{
        let errdata = res.data.errMsg;
        // console.log( errdata )
        wx.showModal({
          title: '失败',
          content: errdata,
          showCancel:false,
          success: function(res) {
            if (res.confirm) {
              _that.setData({
                showloding:false
              })
            }
          }
        })
      }
    },function(res){
      console.log( res,'接口调用失败' )
    })
  }
})