var app = getApp();
var uctoo = require('../../utils/uctoo-payorder.js')
var promise = require('../../utils/es6-promise.js')
var login=require('../../utils/uctoo-login.js')
Page({
  data: {
    modalHidden: false,
    shoppingCartInfo: [],
    allMoney: 0,
    goods_name: '',
    goods_price: 0,
    sum: 0,

  },
  onLoad: function () {
    // console.log('loaded.');
  },
  onShow: function () {
    var that = this;
    if (wx.getStorageSync('shoppingCartInfo')) {
      var arrInfo = wx.getStorageSync('shoppingCartInfo');
      var money = this.calcuteAllMoney(arrInfo);

      console.log(arrInfo, 'setData')


      that.setData({
        modalHidden: true,
        shoppingCartInfo: arrInfo,
        allMoney: money
      });
    } else {
      that.setData({
        modalHidden: false
      });
    }
  },
  signConfirm: function () {
    wx.switchTab({
      url: '../index/index',
    })
  },
  sub: function (event) {
    var index = event.target.dataset.index;
    var arrInfo = this.data.shoppingCartInfo;
    if (arrInfo[index].goodSum > 1) {
      arrInfo[index].goodSum--;
    }
    var money = this.calcuteAllMoney(arrInfo);
    this.setData({
      shoppingCartInfo: arrInfo,
      allMoney: money
    });
    wx.setStorageSync('shoppingCartInfo', arrInfo);
  },
  add: function (event) {
    var index = event.target.dataset.index;
    var arrInfo = this.data.shoppingCartInfo;
    arrInfo[index].goodSum++;
    var money = this.calcuteAllMoney(arrInfo);
    this.setData({
      shoppingCartInfo: arrInfo,
      allMoney: money
    });
    wx.setStorageSync('shoppingCartInfo', arrInfo);
  },

  deleteGood: function (event) {
    var index = event.target.dataset.index;
    var arrInfo = this.data.shoppingCartInfo;
    arrInfo.splice(index, 1);
    var money = this.calcuteAllMoney(arrInfo);
    this.setData({
      shoppingCartInfo: arrInfo,
      allMoney: money
    });
    wx.setStorageSync('shoppingCartInfo', arrInfo);
  },
  //点击遮区域关闭弹窗
  // cascadeDismiss: function () {
  //   this.animation.translateY(285).step();
  //   this.setData({
  //     animationData: this.animation.export(),
  //     maskVisual: 'hidden'
  //   });
  // },
  //计算总价
  calcuteAllMoney: function (arr) {
    var that = this;
    var sum = 0;
    for (var i = 0; i < arr.length; i++) {
      sum += parseFloat(arr[i].goodPrice) * parseFloat(arr[i].goodSum);
    }
    that.setData({
      sum: sum,
    })
    return sum;

  },

  //结算提交
  checkOut: function () {
    var that=this
    var price = this.data.sum;
    var user = wx.getStorageSync('login');
    if (!user) {
      wx.showModal({
        title: '微友货温馨提醒',
        content: '你没有授权登录！如有问题请联系客服电话：18679654648',
        success: function (res) {
          if (res.confirm) {
           login.login();
          }
        }
      })
    }else{
        let goods_id = 0;
    var shop = this.data.shoppingCartInfo;

    wx.request({
      url: `${app.globalData.API_URL}` + '/order',
      data: {
        goods_price: this.data.sum,
        user_id: user.mid,
        pay_name: user.nickName
      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        var order_id = res.data.order_sn;
        console.log(res.data.order_sn)
        var id = res.data.id;

        var promises = new promise(function (resolve, reject) {
          resolve(shop);
        })
        promises.then(function (value) {
          value.forEach(function (value) {
            console.log(value, 'foreach');
            wx.request({
              url: `${app.globalData.API_URL}/ordergoods`,
              data: {
                order_id: id,
                goods_id: value.goodId,
                goods_name: value.goodName,
                goods_price: value.goodPrice,
                goods_num: value.goodSum,
                spec_key: value.goodImg
              },
              method: 'POST',
              success: function (res) {
                console.log(res);

              },
              fail: function (res) {

              },

            });
          });
        }).
          then(function () {
            wx.setStorageSync('orderid', order_id)
            //提交订单后清空购物车
            wx.removeStorageSync('shoppingCartInfo');
            that.setData({
              shoppingCartInfo:[],
              allMoney: 0
            })
            wx.navigateTo({
              url: '/pages/payorder/payorder?id=' + id,
            })
          }).
          catch(function (error) {
            console.error(error);
          });

      }

    })
    }

  
    ///uctoo.payorder()
    // var  order = [];
    // var datas = [];
    // var id = '';
    // var count = '';
    // var price = this.data.sum;

    // let goods_id=0;
    // this.data.shoppingCartInfo.forEach(function(value,i){
    //   // console.log(value,'订单数据');

    //     //  goods_name[i] = value.goodName,
    //     //  goods_price[i] = value.goodPrice,
    //     count += value.goodSum + '+';
    //      id += value.goodId + '+';

    //  // order.push(item);
    // });

    // wx.request({
    //   url: `${app.globalData.API_URL}`+'/order',
    //   data:{
    //     product_id : id,
    //     product_img : count,
    //     order_total_price : price,
    //   },
    //   method: 'POST',
    //   header :{
    //     'content-type':'application/json'
    //   },
    //   success : function(res){
    //     console.log(res,'return waht');
    //   }

    // })

  }
});
