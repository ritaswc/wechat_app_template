// pages/worktypeTwo/worktypeTwo.js
const work = require('../../utils/AELPOSITION.js');
const select = require('../../utils/util.js');
Page({
  data:{
    has_post:[],
    post:[],
    show:true
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let all = work.all //全部职位分类
    let id = options.id; //页面带来的id
    let post = select.postlist(all,id) //更具id选择所有职位分类
    var experience = wx.getStorageSync('experience');
    this.setData({
      post:post
    })
    if( experience  ){
      this.setData({
          show:false
      })
    }
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
  select_post:function(e){ //选择职位
    var experience = wx.getStorageSync('experience');

    let all = work.all //全部职位分类
    let id = e.currentTarget.dataset.id;
    let post = e.currentTarget.dataset.post;
    let has_post_naw =[{id:id,post:post}];
    var has_post_temp = wx.getStorageSync('has_post') || [];
    if( experience ){
        wx.setStorage({ //缓存id
            key:"experienceId",
            data:id,
            success:function(){
                wx.removeStorage({ key: 'experience',})
                wx.navigateBack({
                  delta: 1
              })
            }
          }) 
    }else{
        if( has_post_temp.length < 5 ){
          var has_post = has_post_temp.concat(has_post_naw);

          // console.log( select.quchong(has_post,'post',all) )

          wx.setStorage({ //缓存已选择的职位
            key: 'has_post',
            data:select.quchong(has_post,'post',all),
          })
          this.setData({
              has_post:select.quchong(has_post,'post',all)
          })
      }else{
          wx.showModal({
          content: '职位类别最多支持选5个',
          showCancel:false,
          success: function(res) {
            if (res.confirm) {
              wx.navigateBack({delta: 2})
            }
          }
        })
      }
    }
  },
  del_this:function(e){ //删除已选择的职位
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