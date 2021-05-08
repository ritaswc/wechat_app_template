//index.js  
//获取应用实例  
var app = getApp()
Page({
  data:{
      src: 'http://wxsnsdy.tc.qq.com/105/20210/snsdyvideodownload?filekey=30280201010421301f0201690402534804102ca905ce620b1241b726bc41dcff44e00204012882540400&bizid=1023&hy=SH&fileparam=302c020101042530230204136ffd93020457e3c4ff02024ef202031e8d7f02030f42400204045a320a0201000400',
    hiddenVideo: true,
    bannerApp:true,
    winWidth: 0,
    winHeight: 0,
    currentTab: 0, //tab切换  
    productId:0,
    itemData:{},
    buynum:1,
    imgUrls: [
      {'photo':'../../images/zs02.png'},
       {'photo':'../../images/zs02.png'},
    ],
    indicatorDots: true,
    autoplay: true,
    interval: 2000,
    duration: 1000,
    circular: true,
// 属性选择
      firstIndex: -1,
    //准备数据
    //数据结构：以一组一组来进行设定
    commodityAttr: [
      {
        priceId: 1,
        price: 35.0,
        "stock": 8,
        "attrValueList": [
          {
            "attrKey": "型号",
            "attrValue": "2"
          },
          {
            "attrKey": "颜色",
            "attrValue": "白色"
          },
          {
            "attrKey": "大小",
            "attrValue": "小"
          },
          {
            "attrKey": "尺寸",
            "attrValue": "1m"
          }
        ]
      },
      {
        priceId: 2,
        price: 35.1,
        "stock": 9,
        "attrValueList": [
          {
            "attrKey": "型号",
            "attrValue": "1"
          },
          {
            "attrKey": "颜色",
            "attrValue": "红色"
          },
          {
            "attrKey": "大小",
            "attrValue": "小"
          },
          {
            "attrKey": "尺寸",
            "attrValue": "2m"
          }
        ]
      },
      {
        priceId: 3,
        price: 35.2,
        "stock": 10,
        "attrValueList": [
          {
            "attrKey": "型号",
            "attrValue": "1"
          },
          {
            "attrKey": "颜色",
            "attrValue": "绿色"
          },
          {
            "attrKey": "大小",
            "attrValue": "大空军建军节"
          },
          {
            "attrKey": "尺寸",
            "attrValue": "1m"
          }
        ]
      },
      {
        priceId: 4,
        price: 35.2,
        "stock": 10,
        "attrValueList": [
          {
            "attrKey": "型号",
            "attrValue": "1"
          },
          {
            "attrKey": "颜色",
            "attrValue": "绿色"
          },
          {
            "attrKey": "大小",
            "attrValue": "大"
          },
          {
            "attrKey": "尺寸",
            "attrValue": "2m"
          }
        ]
      }
    ],
    attrValueList: []
  },
   bindplay:function(){//开始播放按钮或者继续播放函数
      console.log("开始播放")
    },
    bindpause:function(){//暂停播放按钮函数
      console.log("停止播放")
    },
    bindend:function(){//播放结束按钮函数
      console.log("播放结束")
    },
    bindtimeupdate:function(res){//播放中函数，查看当前播放时间等
      console.log(res)//查看正在播放相关变量
      console.log("播放到第"+res.detail.currentTime+"秒")//查看正在播放时间，以秒为单位
    },
  // 弹窗
   setModalStatus: function (e) {
    console.log("设置显示状态，1显示0不显示", e.currentTarget.dataset.status);
    var animation = wx.createAnimation({
      duration: 200,
      timingFunction: "linear",
      delay: 0
    })
    this.animation = animation
    animation.translateY(300).step()
    this.setData({
      animationData: animation.export()
    })
    if (e.currentTarget.dataset.status == 1) {
      this.setData(
        {
          showModalStatus: true
        }
      );
    }
    setTimeout(function () {
      animation.translateY(0).step()
      this.setData({
        animationData: animation
      })
      if (e.currentTarget.dataset.status == 0) {
        this.setData(
          {
            showModalStatus: false
          }
        );
      }
    }.bind(this), 200)
  },
  // 加减
  changeNum:function  (e) {
    var that = this;
    if (e.target.dataset.alphaBeta == 0) {
        if (this.data.buynum <= 1) {
            buynum:1
        }else{
            this.setData({
                buynum:this.data.buynum - 1
            })
        };
    }else{
        this.setData({
            buynum:this.data.buynum + 1
        })
    };
  },
  // 传值
  onLoad: function (option) {
        console.log( this.setData)      
    this.initNavHeight();
    this.setData({
      productId: option.productId,
      
    });
    this.loadProductDetail();
  },
// 属性选择
  onShow: function () {
    this.setData({
      includeGroup: this.data.commodityAttr
    });
    this.distachAttrValue(this.data.commodityAttr);
    // 只有一个属性组合的时候默认选中
    // console.log(this.data.attrValueList);
    if (this.data.commodityAttr.length == 1) {
      for (var i = 0; i < this.data.commodityAttr[0].attrValueList.length; i++) {
        this.data.attrValueList[i].selectedValue = this.data.commodityAttr[0].attrValueList[i].attrValue;
      }
      this.setData({
        attrValueList: this.data.attrValueList
      });
    }
  },
  /* 获取数据 */
  distachAttrValue: function (commodityAttr) {
    /**
      将后台返回的数据组合成类似
      {
        attrKey:'型号',
        attrValueList:['1','2','3']
      }
    */
    // 把数据对象的数据（视图使用），写到局部内
    var attrValueList = this.data.attrValueList;
    // 遍历获取的数据
    for (var i = 0; i < commodityAttr.length; i++) {
      for (var j = 0; j < commodityAttr[i].attrValueList.length; j++) {
        var attrIndex = this.getAttrIndex(commodityAttr[i].attrValueList[j].attrKey, attrValueList);
        // console.log('属性索引', attrIndex); 
        // 如果还没有属性索引为-1，此时新增属性并设置属性值数组的第一个值；索引大于等于0，表示已存在的属性名的位置
        if (attrIndex >= 0) {
          // 如果属性值数组中没有该值，push新值；否则不处理
          if (!this.isValueExist(commodityAttr[i].attrValueList[j].attrValue, attrValueList[attrIndex].attrValues)) {
            attrValueList[attrIndex].attrValues.push(commodityAttr[i].attrValueList[j].attrValue);
          }
        } else {
          attrValueList.push({
            attrKey: commodityAttr[i].attrValueList[j].attrKey,
            attrValues: [commodityAttr[i].attrValueList[j].attrValue]
          });
        }
      }
    }
    // console.log('result', attrValueList)
    for (var i = 0; i < attrValueList.length; i++) {
      for (var j = 0; j < attrValueList[i].attrValues.length; j++) {
        if (attrValueList[i].attrValueStatus) {
          attrValueList[i].attrValueStatus[j] = true;
        } else {
          attrValueList[i].attrValueStatus = [];
          attrValueList[i].attrValueStatus[j] = true;
        }
      }
    }
    this.setData({
      attrValueList: attrValueList
    });
  },
  getAttrIndex: function (attrName, attrValueList) {
    // 判断数组中的attrKey是否有该属性值
    for (var i = 0; i < attrValueList.length; i++) {
      if (attrName == attrValueList[i].attrKey) {
        break;
      }
    }
    return i < attrValueList.length ? i : -1;
  },
  isValueExist: function (value, valueArr) {
    // 判断是否已有属性值
    for (var i = 0; i < valueArr.length; i++) {
      if (valueArr[i] == value) {
        break;
      }
    }
    return i < valueArr.length;
  },
  /* 选择属性值事件 */
  selectAttrValue: function (e) {
    /*
    点选属性值，联动判断其他属性值是否可选
    {
      attrKey:'型号',
      attrValueList:['1','2','3'],
      selectedValue:'1',
      attrValueStatus:[true,true,true]
    }
    console.log(e.currentTarget.dataset);
    */
    var attrValueList = this.data.attrValueList;
    var index = e.currentTarget.dataset.index;//属性索引
    var key = e.currentTarget.dataset.key;
    var value = e.currentTarget.dataset.value;
    if (e.currentTarget.dataset.status || index == this.data.firstIndex) {
      if (e.currentTarget.dataset.selectedvalue == e.currentTarget.dataset.value) {
        // 取消选中
        this.disSelectValue(attrValueList, index, key, value);
      } else {
        // 选中
        this.selectValue(attrValueList, index, key, value);
      }

    }
  },
  /* 选中 */
  selectValue: function (attrValueList, index, key, value, unselectStatus) {
    // console.log('firstIndex', this.data.firstIndex);
    var includeGroup = [];
    if (index == this.data.firstIndex && !unselectStatus) { // 如果是第一个选中的属性值，则该属性所有值可选
      var commodityAttr = this.data.commodityAttr;
      // 其他选中的属性值全都置空
      // console.log('其他选中的属性值全都置空', index, this.data.firstIndex, !unselectStatus);
      for (var i = 0; i < attrValueList.length; i++) {
        for (var j = 0; j < attrValueList[i].attrValues.length; j++) {
          attrValueList[i].selectedValue = '';
        }
      }
    } else {
      var commodityAttr = this.data.includeGroup;
    }

    // console.log('选中', commodityAttr, index, key, value);
    for (var i = 0; i < commodityAttr.length; i++) {
      for (var j = 0; j < commodityAttr[i].attrValueList.length; j++) {
        if (commodityAttr[i].attrValueList[j].attrKey == key && commodityAttr[i].attrValueList[j].attrValue == value) {
          includeGroup.push(commodityAttr[i]);
        }
      }
    }

    attrValueList[index].selectedValue = value;

    this.setData({
      attrValueList: attrValueList,
      includeGroup: includeGroup
    });
        console.log(attrValueList);
    var count = 0;
    for (var i = 0; i < attrValueList.length; i++) {
      for (var j = 0; j < attrValueList[i].attrValues.length; j++) {
        if (attrValueList[i].selectedValue) {
          count++;
          break;
        }
      }
    }
    if (count < 2) {// 第一次选中，同属性的值都可选
      this.setData({
        firstIndex: index
      });
    } else {
      this.setData({
        firstIndex: -1
      });
    }
  },
  /* 取消选中 */
  disSelectValue: function (attrValueList, index, key, value) {
    var commodityAttr = this.data.commodityAttr;
    attrValueList[index].selectedValue = '';

    // 判断属性是否可选
    for (var i = 0; i < attrValueList.length; i++) {
      for (var j = 0; j < attrValueList[i].attrValues.length; j++) {
        attrValueList[i].attrValueStatus[j] = true;
      }
    }
    this.setData({
      includeGroup: commodityAttr,
      attrValueList: attrValueList
    });

    for (var i = 0; i < attrValueList.length; i++) {
      if (attrValueList[i].selectedValue) {
        this.selectValue(attrValueList, i, attrValueList[i].attrKey, attrValueList[i].selectedValue, true);
      }
    }
  },

   list:function(e){
    console.log(e.currentTarget.dataset.title)
    wx.navigateTo({
      url: '../order/pay?title='+e.currentTarget.dataset.title,
      success: function(res){
        // success
      },  
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },

// 数据
  loadProductDetail:function(){
    var that = this;
    wx.request({
      url: app.d.hostUrl + '/ztb/productDetailsZBT/GetProductDetails',
      method:'post',
      data: {
        ProID: that.data.productId,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        console.log(res)
        //--init data        
        var data = res.data.data[0];
        console.log(data);
        that.initProductData(data);
        that.setData({
          itemData:data,
        });
        //endInitData
      },
    });
  },


  initProductData: function(data){
    data["LunBoProductImageUrl"] = [];

    var imgs = data.LunBoProductImage.split(';');
    for(let url of imgs){
      url && data["LunBoProductImageUrl"].push(app.d.hostImg + url);
    }

    data.Price = data.Price/100;
    data.VedioImagePath = app.d.hostVideo + '/' +data.VedioImagePath;
    data.videoPath = app.d.hostVideo + '/' +data.videoPath;
  },
  addFavorites:function(e){//添加到收藏
    var that = this;
    console.log(this.data);
      wx.showToast({
                title: '已添加',
                icon: 'success',
                duration: 2000
        });
    wx.request({
      url: app.d.hostUrl + '/ztb/productZBT/AddCollectCategory',
      method:'post',
      data: {
        userID: app.d.userId,
        ProID: that.data.productId,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        // //--init data        
        var data = res.data;
        console.log(data);
        wx.showToast({
            title: data.message,
            icon: 'success',
            duration: 2000
        });

        if(data.result == 'ok'){
          console.log('add to shope favs ok');
          //变成已收藏，但是目前小程序可能不能改变图片，只能改样式
          that.data.itemData.isCollect = true;
          console.log(that.data.itemData);
        }
      },
    });
  },
  bindBtnBuy:function(){//添加购物车，并且购买
    this.addShopCart('buy');
    console.log(buy);
  },

  addShopCart:function(act){ //添加到购物车
    var that = this;
    console.log(this.data);
    wx.request({
      url: app.d.hostUrl + '/ztb/shopCartZBT/AddShopCart',
      method:'post',
      data: {
        userID: app.d.userId,
        ProID: that.data.productId,
        buycount:1,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        // //--init data        
        var data = res.data;
        console.log(data);
        
        if(data.result == 'ok'){
          // 不能跳转到下单页，因为没有购物车id
          // if(act == 'buy'){
          //   wx.navigateTo({
          //     url: '/pages/order/pay?productId='+that.data.productId+'&cartId='+data.message+'&buyCount=1;',
          //   });
          //   return;
          // }
          //console.log('add to shope card ok')
          if(act == 'buy'){
            wx.switchTab({
              url: '/pages/cart/cart'
            });
            return;
          }else{
            wx.showToast({
                title: '加入成功',
                icon: 'success',
                duration: 2000
            });
          }     
        }//endok
      },
    });
  },
  bindChange: function (e) {//滑动切换tab 
    var that = this;
    that.setData({ currentTab: e.detail.current });
  },
  initNavHeight:function(){////获取系统信息
    var that = this;
    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          winWidth: res.windowWidth,
          winHeight: res.windowHeight
        });
      }
    });
  },
  bannerClosed:function(){
    this.setData({
      bannerApp:false,
    })
  },
  swichNav: function (e) {//点击tab切换
    var that = this;
    if (this.data.currentTab === e.target.dataset.current) {
      return false;
    } else {
      that.setData({
        currentTab: e.target.dataset.current
      })
    }
  },
    onShareAppMessage: function () {
    return {
      title: '微信小程序联盟',
      desc: '最具人气的小程序开发联盟!',
      path: '/page/user?id=123'
    }
  },
});
