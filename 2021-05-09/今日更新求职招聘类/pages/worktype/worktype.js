// pages/worktype/worktype.js
const work = require('../../utils/AELPOSITION.js');
const select = require('../../utils/util.js');
Page({
  data:{
    show_img:false,
    has_post:[],
    post_type:[],
    show:true
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数;
    var post_type =select._type( work.type ); //全部类型
    let experience = options.status; //识别 是添加工作经验
    this.setData({
      post_type:post_type
    })
    if( experience  ){ //如果是添加工作经验,去除已选职位模块
      this.setData({
          show:false
      })
    }
    wx.setStorage({ //缓存状态
      key:"experience",
      data:experience
    })
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    var has_post= wx.getStorageSync('has_post') || [];
    this.setData({
        has_post:has_post
    })
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  select_type:function(e){
    let id = e.currentTarget.dataset.id;
    id.length == 3 ? id = '0'+id:id =id;
    var experience = wx.getStorageSync('experience');
    if( experience ){
        wx.redirectTo({
          url: '../worktypeTwo/worktypeTwo?id='+id,
        })
    }else{
        wx.navigateTo({
          url: '../worktypeTwo/worktypeTwo?id='+id,
        })
    }
    
  },
  del_this:function(e){ //删除选中职位
    let id = e.currentTarget.dataset.id;
    var has_post_temp = wx.getStorageSync('has_post') || [];
    var naw_post =[];
    for( let i =0,arr=has_post_temp;i<arr.length;i++ ){
        if( arr[i].id != id ){
          naw_post.push(arr[i])
        }
    }
    wx.setStorage({
        key: 'has_post',
        data:naw_post
    })
    this.setData({
        has_post:naw_post
    })
  }
})