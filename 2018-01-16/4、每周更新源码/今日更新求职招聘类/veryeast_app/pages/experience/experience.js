// pages/experience/experience.js
let requireMassage = require('../../utils/wxrequire').requireMassage;
let downLoadExperience = require('../../utils/wxrequire').downLoadExperience;
let upDataExperiesce = require('../../utils/wxrequire').upDataExperiesce;
Page({
  data:{
    list:[]
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    wx.showToast({
        title: '加载中...',
        icon: 'loading',
        duration: 20000000
    })
    let _that = this;
    downLoadExperience(_that);
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    let require_two = wx.getStorageSync('require_two');
    let _that = this;
    if( require_two ){
      downLoadExperience(_that);
    }
  },
  onHide:function(){
    // 页面隐藏
    wx.removeStorage({
      key: 'require_two',
      success: function(res) {
        // console.log(res.data)
      } 
    })
  },
  onUnload:function(){
    // 页面关闭
    wx.removeStorage({ key: 'require_two',}) //移除是否再次请求工作经验列表的状态
  },
  go_work_exps:function(e){  //去工作经验详情页
    let id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '../objecTive/objecTive?id='+id,
      fail:function(){
        console.log("go工作经验详情页失败")
      }
    })
  },
  delete_exp:function(e){ //==============删除工作经验
      let id = e.currentTarget.dataset.id;
      let user_ticket = wx.getStorageSync('user_ticket');
      let data = {
        'user_ticket':user_ticket,
        'work_exp_id':id
      };
      let _that = this;
      wx.showModal({
      title: '提示',
      content: '确定要删除此工作经验',
      success: function(res) {
        if (res.confirm) { // 确定删除
          upDataExperiesce('/resume/delete_work_exp',data,function(res){
           
            let status = res.data.status;
            if( status == 1 ){ //请求成功
                downLoadExperience(_that); //从新加载工作经验
            }else{
                wx.showModal({
                  title: '提示',
                  showCancel:false,
                  content: '删除失败',
                })
            }

          },function(res){
              console.log( res ,'接口挂了' )
          })
        }
      }
    })
  }
})