var app = getApp();
import resource from '../../lib/resource';

Page({
  data: {
    loading: true,
    cartList: [],
    totalNumber: 0,
    totalPrice: 0,
    checkedStatus: true,
    buyNumber: 0,
    buyPrice: 0,
  },
  onShow() {
    const that = this;
    resource.fetchCartIndex().then(res => {
      var totalNumber = 0;
      var totalPrice = 0;
      var buyNumber = 0;
      var buyPrice = 0;
      res.data.forEach(item => {
        // 保留两位小数点
        item.real_price = item.real_price.toFixed(2);
        item.market_price = item.market_price.toFixed(2);
        if (!item.status) {
          that.setData({ checkedStatus: false });
        } else {
          buyNumber += item.goods_number;
          buyPrice += item.goods_number * item.real_price;
        }
        totalNumber += item.goods_number;
        totalPrice += item.goods_number * item.real_price;
        if (item.goods_number == item.stock_num) {
          item.plus_class = "disabled";
        } else {
          item.plus_class = "";
        }
        if (item.goods_number == 1) {
          item.decr_class = "disabled";
        } else {
          item.decr_class = "";
        }
      })
      that.setData({
        cartList: res.data,
        loading: false,
        totalNumber: totalNumber,
        totalPrice: totalPrice.toFixed(2),
        buyNumber: buyNumber,
        buyPrice: buyPrice.toFixed(2)
      });

    })
  },
  // 选择商品
  selectProduct(event) {
    var cartId = event.currentTarget.dataset.id;
    var checkedStatus = event.currentTarget.dataset.checkedStatus;
    var totalNumber = 0;
    var totalPrice = 0;
    var buyNumber = 0;
    var buyPrice = 0;
    var id = [];
    checkedStatus = checkedStatus === true;
    var changeStatus = true;
    this.data.cartList.forEach(item => {
      if (item.id == cartId || cartId == 0) {
        if (item.status != !checkedStatus) {
          id.push(item.id);
        }
        item.status = cartId == 0 ? !checkedStatus : !item.status;

      }
      if (!item.status) {
        changeStatus = false;
      } else {
        buyNumber += item.goods_number;
        buyPrice += item.goods_number * item.real_price;
      }
      totalNumber += item.goods_number;
      totalPrice += item.goods_number * item.real_price;
    });
    resource.updCartStatus(id.join()).then(res => {});
    changeStatus = cartId == 0 ? !checkedStatus : changeStatus;
    this.setData({
      cartList: this.data.cartList,
      checkedStatus: changeStatus,
      totalNumber: totalNumber,
      totalPrice: totalPrice.toFixed(2),
      buyNumber: buyNumber,
      buyPrice: buyPrice.toFixed(2)
    });
  },
  // 改变商品数量
  changeNumber(event) {
    var cartId = event.currentTarget.dataset.id;
    var optType = event.currentTarget.dataset.type;
    var totalNumber = 0;
    var totalPrice = 0;
    var buyNumber = 0;
    var buyPrice = 0;
    this.data.cartList.forEach(item => {
      if (item.id == cartId) {
        if (optType == 'plus') {
          if (item.stock_num == item.goods_number) {
            this.setData({
              toast: {
                toastClass: 'yatoast',
                toastMessage: '该宝贝不能购买更多哦'
              }
            });
            setTimeout(() => {
              this.setData({
                toast: {
                  toastClass: '',
                  toastMessage: ''
                }
              });
            }, 2000);
          } else {
            item.goods_number++;
            resource.updCartNumber(cartId, optType);
          }

        } else {

          if (item.goods_number <= 1) {
            this.setData({
              toast: {
                toastClass: 'yatoast',
                toastMessage: '亲，不能再减少了哦'
              }
            });
            setTimeout(() => {
              this.setData({
                toast: {
                  toastClass: '',
                  toastMessage: ''
                }
              });
            }, 2000);
          } else {
            item.goods_number--;
            resource.updCartNumber(cartId, optType);
          }
        }
        if (item.goods_number == item.stock_num) {
          item.plus_class = "disabled";
        } else {
          item.plus_class = "";
        }
        if (item.goods_number == 1) {
          item.decr_class = "disabled";
        } else {
          item.decr_class = "";
        }
      }
      if (!item.status) {

      } else {
        buyNumber += item.goods_number;
        buyPrice += item.goods_number * item.real_price;
      }
      totalNumber += item.goods_number;
      totalPrice += item.goods_number * item.real_price;
    });
    this.setData({
      cartList: this.data.cartList,
      totalNumber: totalNumber,
      totalPrice: totalPrice.toFixed(2),
      buyNumber: buyNumber,
      buyPrice: buyPrice.toFixed(2)
    });
  },
  // 去结算页面
  toSettlement() {
    if(this.data.buyNumber == 0) {
       resource.showTips(this, '你还未勾选商品'); return;
    }
    wx.navigateTo({
      url: '../settlement/settlement',
    });
  },
  // 去除购物车物品
  delProduct(event) {
    wx.showModal({
      content: '你确定在购物车中删除该商品',
      showCancel: true,
      success: (res) => {
        if(res.confirm == 0) {
          return;
        }
        var id = event.currentTarget.dataset.id;
        var cartList = this.data.cartList;
        var totalNumber = 0;
        var totalPrice = 0;
        var buyNumber = 0;
        var buyPrice = 0;
        var delKey = 0;
        cartList.forEach((item, key) => {
          if (item.id == id) {
            delKey = key;
          } else {
            if (!item.status) {

            } else {
              buyNumber += item.goods_number;
              buyPrice += item.goods_number * item.real_price;
            }
            totalNumber += item.goods_number;
            totalPrice += item.goods_number * item.real_price;
          }
        });
        cartList.splice(delKey, 1);
        resource.delCartProduct(id).then(res => {
          console.log(res);
          if (res.statusCode == 200) {
            this.setData({
              cartList: cartList,
              totalNumber: totalNumber,
              totalPrice: totalPrice.toFixed(2),
              buyNumber: buyNumber,
              buyPrice: buyPrice.toFixed(2)
            });
          }
        });
      }
    });
  },
  navigateTo() {
    wx.switchTab({
      url: '../home/home'
    });
  }
});
