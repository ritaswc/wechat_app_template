// pages/myresume/myresume.js
let requireMassage = require('../../utils/wxrequire').requireMassage;
Page({
  data:{
    percent:'',
    job_status_text:'',
    privacy:'',
    info_text:'',
    education_text:'',
    experience_text:''
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    wx.showToast({
        title: '加载中...',
        icon: 'loading',
        duration: 20000000
    })
      let user_ticket = wx.getStorageSync('user_ticket');
      let _that = this;
    requireMassage('/resume/status',user_ticket,function(res){
      // console.log( res )
      let status = res.data.status;
      let data = res.data.data;
      let completion  =  data.completion *100;//简历百分比
      let job_status_text =data.job_status_text; //求职状态
      let privacy ; // 公开程度，1;2;3
      //let education_text = data.education_text; //教育经历状态 暂时不加待版本升级
      let experience_text = data.experience_text; //工作经验状态
      let info_text = data.info_text //基本信息;
      if( data.privacy == '1' ){
          privacy = '对所有公开'
      }else if( data.privacy == '2' ){
          privacy = '只公开Email'
      }else if( data.privacy == '3' ){
          privacy = '完全保密'
      }
      
      wx.setStorage({ //缓存状态
        key:"job_status_text",
        data:job_status_text
      })
      wx.setStorage({ //缓存状态
        key:"privacy_two",
        data:privacy
      })
      _that.setData({
        job_status_text:job_status_text,
        info_text:info_text,
        percent:completion,
        privacy:privacy,
        experience_text:experience_text
      })
      wx.hideToast();
    },function(res){
      console.log(res,"接口调用失败")
    })
  },
  onReady:function(){
    // 页面渲染完成
    
  },
  onShow:function(){
    // 页面显示
    let job_status = wx.getStorageSync('job_status') || wx.getStorageSync('job_status_text');
    let privacy =  wx.getStorageSync('privacy') || wx.getStorageSync('privacy_two');
    let info_text =  wx.getStorageSync('info_text');
    let experience_text = wx.getStorageSync('experience_text');
    // console.log( job_status )
    this.setData({
      job_status_text:job_status,
      privacy:privacy
    })
    if( info_text ){
      this.setData({
        info_text:info_text
      })
    }
    if( experience_text ){
      this.setData({
        experience_text:experience_text
      })
    }
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})