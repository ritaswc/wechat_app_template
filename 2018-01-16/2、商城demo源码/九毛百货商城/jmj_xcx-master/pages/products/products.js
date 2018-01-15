var util = require('../../common/common.js');
let URLINDEX=util.prefix();
Page({
  data:{
    actionSheetHidden1:true,
    actionSheetHidden2:true,
    actionSheet1Items:[
      {
        logo:URLINDEX+"/jmj/img/plane_icon.png",
        title:"海外直邮",
        text:"汇集日本本土尖货 千万人口碑信赖 价格透明 日本直邮 足不出户 轻松到手"
      },
      {
        logo:URLINDEX+"/jmj/img/man_icon.png",
        title:"新手满288包邮",
        text:"全程物流透明 轻松下单 日本同价正品 满288包邮"
      },
      {
        logo:URLINDEX+"/jmj/img/shouhou.png",
        title:"售后服务",
        text:"目前不支持无理由退换货服务。出现包装破损或者内件破损，商品错发的情况请拍照联系客服核实。我们会及时处理您的退换货事宜。"
      },
      {
        logo:URLINDEX+"/jmj/img/wuliu1.png",
        title:"物流配送说明",
        text:"订单支付后2-3天日本供应链配货至中转仓；5-7天内从日本中转仓发至国内玖猫专线。正常情况下10-15天货物就会到您手上哦~"
      },
    ],
    // 静态图片
    img1:URLINDEX+"/jmj/product/left-log.png",
    img2:URLINDEX+"/jmj/img/title_icon01.png",
    img3:URLINDEX+"/jmj/img/288.png",
    img4:URLINDEX+"/jmj/product/riya-logo.png",
    img5:URLINDEX+"/jmj/img/plane_icon.png",
    // 图文静态图片
    img6:URLINDEX+"/xinzeng/fuwu.png",
    img7:URLINDEX+"/xinzeng/authorization.jpg",
    img8:URLINDEX+"/jmj/icon/shuoming.png",
    img9:URLINDEX+"/jmj/product/zheng_icon.png",
    img10:URLINDEX+"/jmj/product/zhi_you.png",
    img11:URLINDEX+"/jmj/product/renbao.png",
    img12:URLINDEX+"/jmj/product/tongjia.png",
    //种草相关信息
    isfavorite:0,
    imgred:URLINDEX+"/jmj/product/already.png",
    imgblack:URLINDEX+"/jmj/product/remove.png",
    grasstextred:"已种草",
    grasstextblack:"种草",
    stylered:"color:#ff4aa0",
    styleblack:"color:#bbb",
    //购物车相关
    styTop:"top:-60rpx",
    styHide:"top:0",
    buyNum:1
  },
  // 第一个弹窗
  action: function(){
      this.setData({
        actionSheetHidden1:false
      })
  },
  actionSheetChange:function(){
    this.setData({
        actionSheetHidden1:!this.data.actionSheetHidden1
      })
  },
  //第二个弹窗
  action2: function(e){
      var sta=e.target.dataset.buy;
      this.setData({
        actionSheetHidden2:false,
        buyy:sta
      })
  },
  actionSheet2Change:function(){
    this.setData({
        actionSheetHidden2:!this.data.actionSheetHidden2
      })
  },
  //购物处理函数
  reduce: function(){
    if(this.data.buyNum<=1){
      wx.showToast({
        title: '购买数量不能小于1件',
        duration: 1000
      })
    }else{
      this.setData({
        buyNum:--this.data.buyNum
      })
    }
  },
  add:function(){
    if(this.data.buyNum>=this.data.goodsDetail.store_nums){
      wx.showToast({
        title: '购买数量超出库存',
        duration: 1000
      })
    }else{
      this.setData({
        buyNum:++this.data.buyNum
      })
    }
  },
  buynow: function(){
    var self=this;
    buyNowFun(self);
  },
  joincar: function(){
    var self=this;
    joinCarFun(self);
  },
  grass: function(){
    var that=this;
    getGrass(that);
  },
  onLoad:function(options){
    var that=this;
    that.setData({
      id:options.id
    });
    wx.setNavigationBarTitle({
      title:options.title
    });
    getProductsDetail(that)
  }
})

function getProductsDetail(that){
  wx.request({
      url: util.pre()+'/apic/goods_detail',
      data:{
          id:that.data.id,
          token:util.code() 
      },
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
        var reduce=(res.data.data.market_price - res.data.data.sell_price).toFixed(2);
        var weight=parseInt(res.data.data.weight);
         that.setData({
          goodsDetail:res.data.data,
          reduce:reduce,
          weight:weight,
          isfavorite:res.data.data.is_favorite
        })
      } 
    }) 
}
function getGrass(that){
  wx.request({
      url: util.pre()+'/simple/favorite_add/_paramKey_/_paramVal_',
      data:{
          goods_id:that.data.goodsDetail.id,
					random:Math.random(),
          token:util.code()  
      },
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
        that.setData({
          isfavorite:!that.data.isfavorite
        })
      } 
    }) 
}
function buyNowFun(self)
		{ 
      let id=self.data.goodsDetail.id;
      let buyNums=self.data.buyNum;
      checkNum(self)
      wx.navigateTo({
        url: '../cart2/cart2?id='+id+'&num='+buyNums+'&type=goods'
      })  
		}
function joinCarFun(self){
    checkNum(self)
    wx.request({
      url: util.pre()+'/simple/joinCart/_paramKey_/_paramVal_?type=goods',
      data:{
          goods_id:self.data.goodsDetail.id,
					random:Math.random(),
          token:util.code(),
          goods_num:self.data.buyNum,
      },
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
          showCart(self);
      } 
    }) 
}
//前端检查是否能加入购物车
function checkNum(self){
       let id=self.data.goodsDetail.id;
      let storeNum=self.data.goodsDetail.store_nums;
      let buyNums=self.data.buyNum;
      if(storeNum<=0||storeNum<buyNums){
        wx.showModal({
          title: '提示',
          content: '商品库存不足',
          success: function(res) {
            if (res.confirm) {
              self.setData({
                actionSheetHidden2:true,
              })
            }
          }
        })
        return false;
      }
}
function showCart(self){
    wx.request({
      url: util.pre()+'/simple/showCart/_paramKey_/_paramVal_',
      data:{
          token:util.code(), 
          random:Math.random(),
      },
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
          wx.showModal({
              title: '加入购物车成功',
              content: '是否立即进入购物车',
              success: function(res) {
                self.setData({
                    actionSheetHidden2:true,
                  })
                if (res.confirm) {
                  //还要显示购物车。。。
                   wx.switchTab({
                      url: '../cart/cart'
                    })
                }
              }
            })
      } 
    }) 
}