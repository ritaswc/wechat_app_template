// pages/commonSelect/commonSelect.js
const method = require("../../utils/methond.js");
Page({
  data:{
    value:'',
    placeholder:'请输入岗位职责,限2000字',
    length:'2000'
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let status = options.status;
    
    if( status == 'duty' ){ //岗位职责
        wx.setNavigationBarTitle({
          title: '岗位职责'
        })
        wx.setStorage({ //缓存状态
          key:"status_ss",
          data:"duty"
        })
        
    }else if( status == 'Deeds' ){ //工作业绩
        wx.setNavigationBarTitle({
          title: '工作业绩'
        });
        this.setData({
          placeholder:'请输入工作业绩,限1000字',
          length:'1000'
        });
        wx.setStorage({ //缓存状态
          key:"status_ss",
          data:"Deeds"
        });
    }
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    let status =  wx.getStorageSync('status_ss');
    let duty = wx.getStorageSync('duty'); //岗位职责
    let Deeds = wx.getStorageSync('Deeds'); //岗位职责

    let now = wx.getStorageSync('now');  //是时候解释一下now了,他是上个页面中,对应工作经验id下的一个完整的工作经验,
    let job_responsibilities_cn = now.job_responsibilities_cn; //岗位职责
    let job_performance_cn = now.job_performance_cn; //工作业绩

    if( status == 'duty' ){
        this.setData({
            value:duty
        })
        if( job_responsibilities_cn != ''){
          this.setData({
              value:job_responsibilities_cn
          })
        }
    }else if( status == 'Deeds' ){
        this.setData({
            value:Deeds
        })
        if( job_performance_cn != ''){
          this.setData({
              value:job_performance_cn
          })
        }
    }
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  inputChange:function(e){
    let val = e.detail.value;
    let status =  wx.getStorageSync('status_ss');
    if( status == 'duty' ){//岗位职责
        wx.setStorage({ //缓存内容
          key:"duty",
          data:val
        })
    }else if( status == 'Deeds' ){//工作业绩
        wx.setStorage({ //缓存内容
          key:"Deeds",
          data:val
        })
    }
  }
})