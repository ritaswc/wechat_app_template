// pages/workplaceTwo/workplaceTwo.js
const select_city = require('../../utils/AELACTION.js');
const select = require('../../utils/util.js');
var app = getApp();
Page({
  data:{
    has_select:[],
    select_city:[],
    show:true
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var basicInf = wx.getStorageSync('basicInf');
    let all = select_city.all; //全部城市
    let id = options.id;
    let selectCity = select.selectCity(all,id);
    var has_select = wx.getStorageSync('has_select') || [];
    if( basicInf ){
      this.setData({
        show:false
      })
    }
    this.setData({
        select_city:selectCity,
        has_select:has_select
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
    wx.removeStorage({ key: 'basicInf',})
  },
  del_this:function(e){ //删除已选择的地点
    let id = e.currentTarget.dataset.id;
    var has_select_temp = wx.getStorageSync('has_select') || [];
    var idArr =[];
    for(let i=0 ,len =has_select_temp ;i<len.length;i++){
        if( len[i].id != id ){
          idArr.push(len[i])
        }
    };
    wx.setStorage({
        key: 'has_select',
        data:idArr
    })
    this.setData({
        has_select:idArr
    })
  },
  select_action:function(e){ //选择工作地点
    var basicInf = wx.getStorageSync('basicInf');

    let all = select_city.all; //全部城市
    let id = e.currentTarget.dataset.id;
    let city = e.currentTarget.dataset.city;
    var has_select_now = [{id:id,city:city}];
    var has_select_temp = wx.getStorageSync('has_select') || [];
    if( basicInf ){
         wx.setStorage({ //缓存id
            key:"cityId",
            data:id,
            success:function(){
              wx.removeStorage({ key: 'basicInf',})
              wx.navigateBack({
                delta: 1
              })
            }
          })   
    }else{
        if( has_select_temp.length <5 ){
          var has_select = has_select_temp.concat(has_select_now);
          wx.setStorage({//缓存已选择的地点
            key: 'has_select',
            data:select.quchong(has_select,'city',all),
          })
          this.setData({ //更新数据
              has_select:select.quchong(has_select,'city',all)
          })
      }else{
        wx.showModal({
          content: '工作地点最多支持选择5个',
          showCancel:false,
          success: function(res) {
            if (res.confirm) {
              wx.navigateBack({
                delta: 2
              })
            }
          }
        })
      }
    }
  }
})