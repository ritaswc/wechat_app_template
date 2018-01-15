// pages/address/address.js
var util = require('../../common/common.js');
let URLINDEX=util.prefix();
Page({
  data:{
    img_edit:URLINDEX+"/jmj/icon/editaddr.png",
    img_del:URLINDEX+"/jmj/icon/del.png",
    img_default:URLINDEX+"/jmj/icon/choose.png",
    img_ci:URLINDEX+"/jmj/icon/circle.png",
    style1:"color:#f3026a",
    style2:"color:#bbb",
    showMessage:false
  },
  changeDefault:function(e){
     var that=this;
     var index=e.currentTarget.dataset.index;
    changedefault(that,index);
  },
  delAddr:function(e){
    var that=this;
    var index=e.currentTarget.dataset.index;
    deladdr(that,index);
  },
  editAddr:function(e){
    var that=this;
    var index=e.currentTarget.dataset.index;
    var store=that.data.addr[index];
    //同步存储要编辑的地址
    wx.setStorageSync('addrList',store);
    wx.navigateTo({
      url: '../addaddr/addaddr?title=编辑收货地址'
    })
  },
  addAddr:function(){
    //同步存储要编辑的地址
    wx.setStorageSync('addrList',{});
    wx.navigateTo({
      url: '../addaddr/addaddr?title=添加收货地址'
    })
  },
  onLoad:function(options){
    var that=this;
    that.setData({
      from:options.from
    })
  },
  onShow:function(){
    var that=this;
    getAddrList(that);
  }
})

function getAddrList(that){
  wx.request({
    url: util.pre()+'/apic/address_list',
    data:{
      token:util.code()
    },
    header: {
      'Content-Type': 'application/json'
    },
    success: function(res) {
      that.setData({
        addr:res.data,
        showMessage:true
      })
    }
  })
}
function changedefault(that,index){
    var def=that.data.addr[index].is_default;
   var id=that.data.addr[index].id;
  wx.request({
    url: util.pre()+'/apic/address_default',
    data:{
      token:util.code(),
      id:id,
      is_default:1
    },
    header: {
      'Content-Type': 'application/json'
    },
    success: function(res) {
      that.data.addr.map(function(item){
        item.is_default=0;
      });
      that.data.addr[index].is_default=1;
      that.setData({
        addr:that.data.addr,
      });
      if(that.data.from=='cart2'){
        wx.navigateBack();
      }
    }
  })
}
function deladdr(that,index){
  var id=that.data.addr[index].id;
  wx.showModal({
    title: '刪除地址',
    content: '是否刪除該地址',
    success: function(res) {
      if (res.confirm) {
        //請求移除購物車
        wx.request({
          url: util.pre()+'/apic/address_del',
          data:{
            token:util.code(),
            id:id,
          },
          header: {
            'Content-Type': 'application/json'
          },
          success: function(res) {
            that.data.addr.splice(index,1);
            that.setData({
              addr:that.data.addr
            })
          }
        })
      }
    }
  })
}