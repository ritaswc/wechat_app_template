// pages/workplace/workplace.js
const select_city = require('../../utils/AELACTION.js');
const select = require('../../utils/util.js');
var app = getApp();
Page({
  data:{
    show_img:false,
    has_select:[],
    user_action:[],
    hot_city:[],
    province:[],
    length:'5',
    show:true
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数

    let basicInf = options.status;
    wx.setStorage({ //缓存状态
      key:"basicInf",
      data:basicInf
    })
    if( basicInf ){
      this.setData({
        show:false
      })
    }
    let all = select_city.all; //全部城市
    let province =  select_city.province; //全部省份
    let hot = select_city.hot; //热门城市

    let hot_city = select.hot_city(all,hot);
    let province_list = select.province(all,province)
    this.setData({
        hot_city:hot_city,
        province:province_list,
    })
    
    

  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    var has_select = wx.getStorageSync('has_select') || [];
    var userCityId = wx.getStorageSync('userCityId');
    // console.log( userCityId )
    this.setData({
        has_select:has_select,
        'user_action[0]':userCityId
    })

  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
    
  },
  del_this:function(e){ //删除选中城市
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
  select_action:function(e){//选择热门城市
    var basicInf = wx.getStorageSync('basicInf');
    
    let all = select_city.all; //全部城市
    let id = e.currentTarget.dataset.id;
    let city = e.currentTarget.dataset.city;
    var has_select_now = [{id:id,city:city}];
    var has_select_temp = wx.getStorageSync('has_select') || [];

    if( basicInf ){
      // console.log( id )
          wx.setStorage({ //缓存id
          key:"cityId",
          data:id,
          success:function(){
            wx.navigateBack({
              delta: 1
            })
          }
        })
        
    }else{
        if( has_select_temp.length <5 ){
          var  has_select;
          if( has_select_now[0].id ){
              has_select = has_select_temp.concat(has_select_now);
              wx.setStorage({
                key: 'has_select',
                data:select.quchong(has_select,'city',all)
              })
              this.setData({
                  has_select:select.quchong(has_select,'city',all)
              })
          }
      }else{
          wx.showModal({
            content: '工作地点最多支持选择5个',
            showCancel:false,
            success: function(res) {
              if (res.confirm) {
                wx.navigateBack({delta: 1})
              }
            }
          })
      }
    }
    
    
    

    
  },
  select_province:function(e){//选择省份下的城市
    let id = e.currentTarget.dataset.id;
    var basicInf = wx.getStorageSync('basicInf');
    if( basicInf ){
        wx.redirectTo({
          url: '../workplaceTwo/workplaceTwo?id='+id,
        })
    }else{
        wx.navigateTo({
          url: '../workplaceTwo/workplaceTwo?id='+id,
        })
    }
    
  }
})