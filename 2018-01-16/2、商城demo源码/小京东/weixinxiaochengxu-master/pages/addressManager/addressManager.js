
var request = require('../../utils/https.js')
var uri_address_list = 'address/api/addressList' //地址列表
var uri_address_delete = 'address/api/delAddress' //删除地址

Page({
  data:{
    addressData:[],
  },
  addressClick:function(e){
    wx.navigateBack({
      delta: 1, // 回退前 delta(默认为1) 页面
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
        var addr = {};
        addr = e.currentTarget.dataset.item;
        wx.setStorageSync('address', addr);
      }
    })
  },
  addrEdit:function(e){
    wx.navigateTo({
      url: '../addressAdd/addressAdd?address='+e.currentTarget.dataset.item.address + '&addressId='+e.currentTarget.dataset.item.addressId +'&areaId='+e.currentTarget.dataset.item.areaId +'&areaInfo='+e.currentTarget.dataset.item.areaInfo +'&cityId='+e.currentTarget.dataset.item.cityId +'&mobPhone='+e.currentTarget.dataset.item.mobPhone +'&provinceId='+e.currentTarget.dataset.item.provinceId + '&trueName='+e.currentTarget.dataset.item.trueName + '&zipCode='+e.currentTarget.dataset.item.zipCode,
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
        // console.log('kkkkkkk');
        // console.log(e.currentTarget.dataset.item);
      }
    })
  },
  addrDelete:function(e){
    console.log('ffffff',e);
    var that = this;
    wx.showModal({
      title: '确认删除该地址吗？',
      success: function(res) {
        if (res.confirm) {
          request.req(uri_address_delete,{
            addressId: e.currentTarget.dataset.item.addressId
          },(err,res) =>{
            request.req(uri_address_list,{
            },(err,res) =>{
              console.log('aaaaaaaaaa',res);
              if (res.data.result == 1) {
                var addresss = wx.getStorageSync('address');
                if(addresss.addressId == e.currentTarget.dataset.item.addressId){
                  try {
                    wx.removeStorageSync('address')
                  } catch (e) {
                    // Do something when catch error
                  } 
                }
                if(res.data.msg == '无数据'){
                  that.setData({
                    addressData: [],//接数组
                  })
                }else{
                  that.setData({
                    addressData: res.data.data,//接数组
                  })
                }
              }
            });
          });
        }
      }
    })
  },
  addressAdd:function(){
    wx.navigateTo({
      url: '../addressAdd/addressAdd',
    })
  },
  onLoad:function(options){
    
  },
  onReady: function() {
    // Do something when page ready.
  },
  onShow: function() {
    var that = this;
    // 生命周期函数--监听页面加载
    request.req(uri_address_list,{
    },(err,res) =>{
      if (res.data.result == 1) {
          that.setData({
            addressData: res.data.data,//接数组
          })
      }
    });
  },
})