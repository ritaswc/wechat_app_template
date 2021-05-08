'use strict';

// 获取全局应用程序实例对象
// const app = getApp()

// 创建页面实例对象
Page({
  /**
   * 页面的初始数据
   */
  data: {
    title: 'useroperation',
    operation: null,
    numberList: {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '人马大饭堂',
      kind: '湘菜',
      average: 88,
      distance: 453,
      desk: 'C2',
      wait: 5
    },
    message: [{
      title: '三太子三汁',
      id: 'message1',
      content: '阿斯顿飞那是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就卡死的李开复',
      time: '2012-12-12'
    }, {
      title: '三太子三汁2',
      id: 'message2',
      content: '阿斯顿飞那是的疯狂就拉上的了风景阿萨德是的疯狂就拉上的了风景阿萨德是的疯狂就拉上的了风景阿萨德是的疯狂就拉上的了风景阿萨德是的疯狂就拉上的了风景阿萨德是的疯狂就拉上的了风景阿萨德来房间爱绿色饭店就卡死的李开复',
      time: '2012-12-12'
    }],
    integral: [{
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      integralid: 'renma1',
      name: '人马大饭堂',
      delMoney: 20,
      useMoney: 100,
      needIntegral: 200
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      integralid: 'renma2',
      name: '人马大饭堂',
      delMoney: 20,
      useMoney: 100,
      needIntegral: 200
    }, {
      img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
      name: '人马大饭堂',
      integralid: 'renma3',
      delMoney: 20,
      useMoney: 100,
      needIntegral: 200
    }],
    currentCouponTab: 0,
    couponNumber: [{
      title: '未使用',
      count: 6
    }, {
      title: '使用记录',
      count: 0
    }, {
      title: '已过期',
      count: 0
    }],
    couponNoUseList: [{
      name: '人马科技大饭堂',
      id: 'shopId',
      delMoney: 100,
      useCondition: '消费即用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }, {
      name: '人马科技大饭堂',
      id: 'shopId',
      delMoney: 100,
      useCondition: '满1000可用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }, {
      name: '人马科技大饭堂',
      id: 'shopId',
      discount: 5,
      useCondition: '满100可用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }],
    couponUseList: [{
      name: '喜鹊楼',
      id: 'shopId',
      delMoney: 190,
      useCondition: '消费即用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }, {
      name: '哈哈',
      id: 'shopId',
      delMoney: 100,
      useCondition: '满1000可用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }, {
      name: '人马科技大饭堂',
      id: 'shopId',
      discount: 5,
      useCondition: '满100可用',
      starTime: '2015.12.01',
      endTime: '2016.12.03'
    }],
    orderNumber: ['待支付', '全部'],
    orderList: {
      pay: [{
        img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
        name: '人马大饭堂',
        code: 'No12312312',
        time: '2017-03-26 17:26',
        money: '238'
      }],
      finish: [{
        img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
        name: '人马大饭堂',
        code: 'No12312312',
        time: '2017-03-26 17:26',
        money: '238',
        delMoney: '23',
        actMoney: '215',
        restaurantId: 'No123123',
        waiterId: 'waiter123123'
      }],
      cancel: [{
        img: 'http://img02.tooopen.com/images/20150928/tooopen_sy_143912755726.jpg',
        name: '人马大饭堂',
        code: 'No12312312',
        time: '2017-03-26 17:26',
        money: '238'
      }]
    },
    shopArray: ['请选择经营品类', '湘菜', '川菜', '粤菜', '沙县小吃', '徽菜', '茶点'],
    index: 0,
    showMessage: null
  },
  /**
   * 输入店名保存
   * @param e
   */
  shopNameInput: function shopNameInput(e) {
    this.setData({
      shopName: e.detail.value
    });
  },

  /**
   * 选择消息显示
   */
  chooseMessage: function chooseMessage(e) {
    this.setData({
      showMessage: e.currentTarget.dataset.message
    });
  },

  /**
   * 设置couponTab
   * @param e
   */
  chooseCouponTab: function chooseCouponTab(e) {
    this.setData({
      currentCouponTab: e.currentTarget.dataset.tabid
    });
  },

  /**
   * 去支付
   * @param e
   */
  goPay: function goPay(e) {
    wx.navigateTo({
      url: '../payorder/payorder?id=' + e.currentTarget.dataset.id
    });
  },

  /**
   * 去打分或者打赏
   * @param e
   */
  goGratuity: function goGratuity(e) {
    var restaurantId = e.currentTarget.dataset.restaurantid;
    var waiterId = e.currentTarget.dataset.waiterid;
    var kind = e.currentTarget.dataset.kind;
    var url = '';
    if (kind === 'shop') {
      url = '../grade/grade?restaurantId=' + restaurantId;
    } else {
      url = '../gratuity/gratuity?waiterId=' + waiterId;
    }
    wx.navigateTo({
      url: url
    });
  },

  /**
   * 选择经营品类
   */
  chooseShopKind: function chooseShopKind(e) {
    this.setData({
      index: e.detail.value
    });
  },

  /**
   * 开始上传商家入驻相关信息
   */
  startShop: function startShop() {
    // todo 入驻信息添加到缓存中
    if (!this.data.shopName || this.data.index === 0) {
      return wx.showModal({
        title: '信息不完整',
        content: '请补充信息完整',
        showCancel: false
      });
    }
    wx.redirectTo({
      url: '../businessCooperation/businessCooperation?shopName=' + this.data.shopName + '&shopKind=' + this.data.shopArray[this.data.index]
    });
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function onLoad(params) {
    // 由跳转链接设置标题
    var operation = params.operation;
    // 设置operation
    this.setData({
      operation: params.operation
    });
    // 判断传入类型
    if (operation === 'number') {
      operation = '我的排单号';
    } else if (operation === 'message') {
      operation = '消息';
    } else if (operation === 'integral') {
      operation = '积分兑换';
    } else if (operation === 'order') {
      operation = '我的订单';
    } else if (operation === 'merchant') {
      operation = '商家入驻';
    } else {
      operation = '优惠券';
    }
    // 设置导航栏标题
    wx.setNavigationBarTitle({
      title: operation
    });
  },


  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function onReady() {
    // TODO: onReady
  },


  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function onShow() {
    // TODO: onShow
  },


  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function onHide() {
    // TODO: onHide
  },


  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function onUnload() {
    // TODO: onUnload
  },


  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function onPullDownRefresh() {
    // TODO: onPullDownRefresh
  }
});
//# sourceMappingURL=useroperation.js.map
