// pages/shopinfo/shopinfo.js
var shopDialogInfo = {};
var selectIndex;
var attrIndex;
var selectIndexArray = [
];
Page({
  data: {
    indicatorDots: "true",//是否显示面板指示点
    autoplay: "true",//是否自动切换
    interval: "5000",//自动切换时间间隔
    duration: "1000",//滑动动画时长
    animationData: {},
    imgUrls: [
      "../../img/shop_info_one1.jpg",
      "../../img/shop_info_one2.jpg",
      "../../img/shop_info_one3.jpg",
      "../../img/shop_info_one4.jpg",
      "../../img/shop_info_one5.jpg"
    ],
    //商品详情
    shopInfo:
    {
      shopTitle: "【年货节|买赠99元大礼包】Huawei/华为 畅享6全网通4G智能手机",
      shopPrice: "￥1299",
      courier: "快递:0.00",
      sales: "月销7386笔",
      address: "广东深圳"
    },
    //商品图片详情
    shopInfoImgList: [
      {
        imgUrl: "../../img/shop_info_one_list_img1.jpg",
        height: "300rpx",
      },
      {
        imgUrl: "../../img/shop_info_one_list_img2.jpg",
        height: "300rpx",
      },
      {
        imgUrl: "../../img/shop_info_one_list_img3.jpg",
        height: "300rpx",
      },
      {
        imgUrl: "../../img/shop_info_one_list_img4.jpg",
        height: "804rpx",
      },
      {
        imgUrl: "../../img/shop_info_one_list_img5.jpg",
        height: "804rpx",
      },
      {
        imgUrl: "../../img/shop_info_one_list_img6.jpg",
        height: "490rpx",
      },
      {
        imgUrl: "../../img/shop_info_one_list_img7.jpg",
        height: "200rpx",
      },
      {
        imgUrl: "../../img/shop_info_one_list_img8.jpg",
        height: "1000rpx",
      },
      {
        imgUrl: "../../img/shop_info_one_list_img9.jpg",
        height: "1000rpx",
      },


    ],

    isShowSelectInfo: true,//是否隐藏选择信息详情
    shopBuyCount: 1,
    shopSelectInfo: {
      selectInfoImg: "../../img/shop_info_select1.jpg",//弹窗商品图片
      selectInfoprice: "￥1299",//弹窗商品价格
      selectInfoInventory: "库存3137件",//弹窗商品库存
      shopSelectInfoHaveSelect: "已选： ",
      selectInfoAttributeList: [
        {
          AttributeName: "机身颜色",
          AttributeNameList: [
            {
              AttributeID: "1001",
              Name: "金色",
              IsSelect: true,
            },
            {
              AttributeID: "1002",
              Name: "白色",
              IsSelect: false,
            },
            {
              AttributeID: "1003",
              Name: "灰色",
              IsSelect: false,
            },
            {
              AttributeID: "1004",
              Name: "粉色",
              IsSelect: false,
            },
            {
              AttributeID: "1005",
              Name: "蓝色",
              IsSelect: false,
            },

          ],
        },
        {
          AttributeName: "存储容量",
          AttributeNameList: [
            {
              AttributeID: "4001",
              Name: "16GB",
              IsSelect: true,
            },
            {
              AttributeID: "4002",
              Name: "32GB",
              IsSelect: false,
            },
            {
              AttributeID: "4003",
              Name: "64GB",
              IsSelect: false,
            },
            {
              AttributeID: "4004",
              Name: "128GB",
              IsSelect: false,
            },


          ],
        },
        {
          AttributeName: "网络类型",
          AttributeNameList: [
            {
              AttributeID: "2001",
              Name: "4G全网通",
              IsSelect: true,
            },
            {
              AttributeID: "2002",
              Name: "4G电信",
              IsSelect: false,
            },
            {
              AttributeID: "2003",
              Name: "4G移动",
              IsSelect: false,
            },

          ],
        },
        {
          AttributeName: "套餐类型",
          AttributeNameList: [
            {
              AttributeID: "3001",
              Name: "官方标配",
              IsSelect: true,
            },
            {
              AttributeID: "3002",
              Name: "套餐1",
              IsSelect: false,
            },
            {
              AttributeID: "3003",
              Name: "套餐2",
              IsSelect: false,
            },
            {
              AttributeID: "3004",
              Name: "套餐3",
              IsSelect: false,
            },
            {
              AttributeID: "3005",
              Name: "套餐4",
              IsSelect: false,
            },

          ],
        },
        {
          AttributeName: "套餐类型",
          AttributeNameList: [
            {
              AttributeID: "3001",
              Name: "官方标配",
              IsSelect: true,
            },
            {
              AttributeID: "3002",
              Name: "套餐1",
              IsSelect: false,
            },
            {
              AttributeID: "3003",
              Name: "套餐2",
              IsSelect: false,
            },
            {
              AttributeID: "3004",
              Name: "套餐3",
              IsSelect: false,
            },
            {
              AttributeID: "3005",
              Name: "套餐4",
              IsSelect: false,
            },

          ],
        },
      ],
    },







  },
  //显示弹窗
  showSelectInfo: function () {
    this.setData({
      isShowSelectInfo: false,
    });

  },

  //隐藏弹窗
  hiddenSelectInfo: function () {
    this.setData({
      isShowSelectInfo: true,
    });
  },
  //禁止滑动
  notScroll: function () {

  },
  //点击弹窗的商品属性
  clickAttr: function (e) {
    selectIndex = e.currentTarget.dataset.selectIndex;
    attrIndex = e.currentTarget.dataset.attrIndex;
    var count = shopDialogInfo.selectInfoAttributeList[selectIndex].AttributeNameList.length;
    for (var i = 0; i < count; i++) {
      shopDialogInfo.selectInfoAttributeList[selectIndex].AttributeNameList[i].IsSelect = false;
    }
    shopDialogInfo.selectInfoAttributeList[selectIndex].AttributeNameList[attrIndex].IsSelect = true;

    var name = shopDialogInfo.selectInfoAttributeList[selectIndex].AttributeNameList[attrIndex].Name;//点击属性的名称


    var shopSelectInfoHaveSelectName = "";
    //点击过，修改属性
    selectIndexArray[selectIndex].value = name;
    var selectIndexArraySize = selectIndexArray.length;
    //将数组的所有属性名拼接起来
    for (var i = 0; i < selectIndexArraySize; i++) {
      shopSelectInfoHaveSelectName += ' "' + selectIndexArray[i].value + '" ';
    }

    shopDialogInfo.shopSelectInfoHaveSelect = "已选:" + shopSelectInfoHaveSelectName;
    this.setData({
      shopSelectInfo: shopDialogInfo,
    });



  },

  //商品数量--
  shopCountSub: function () {
    if (this.data.shopBuyCount > 1) {
      this.setData({
        shopBuyCount: --this.data.shopBuyCount,
      });

    }

  },
  //商品数量++
  shopCountAdd: function () {
    this.setData({
      shopBuyCount: ++this.data.shopBuyCount,
    });
  },

  //商品详情页底部加入购物车
  AddToCart: function () {
    this.setData({
      isShowSelectInfo: false,
    });
  },

  //点击确定加入购物车
  sumbitShopInfo: function () {
    this.setData({
          isShowSelectInfo: true,
        });
  },

  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数

    shopDialogInfo = this.data.shopSelectInfo;
    var name = "";
    var size = shopDialogInfo.selectInfoAttributeList.length;
    for (var i = 0; i < size; i++) {
      selectIndexArray.push({ key: i, value: shopDialogInfo.selectInfoAttributeList[i].AttributeNameList[0].Name });
      name += ' "' + selectIndexArray[i].value + '" ';
    }

    shopDialogInfo.shopSelectInfoHaveSelect = "已选:" + name;
    this.setData({
      shopSelectInfo: shopDialogInfo,
    });

    console.log("shopinfo:" + options)
  },
  onReady: function () {
    // 页面渲染完成

  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  }
})