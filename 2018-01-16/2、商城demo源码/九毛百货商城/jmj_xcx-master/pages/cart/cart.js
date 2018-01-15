// pages/cart/cart.js
var util = require('../../common/common.js');
let URLINDEX=util.prefix();
Page({
  data:{
    //购物车为空的属性
    empty:{
      emptyCat:URLINDEX+"/jmj/icon/cat_car.png",
      text:"购物车还是空的哦"
    },
    countState:true,
    //提供的静态图片
    img1:URLINDEX+"/jmj/cart/cart-banner.png",
    img2:URLINDEX+"/jmj/cart/plane.png",
    del:URLINDEX+"/jmj/icon/del_w.png",
    reduce:URLINDEX+"/jmj/cart/ruduceable.png",
    add:URLINDEX+"/jmj/cart/add.png",
  },
  onShow:function(options){
    var self=this;
    getCart(self)
  },
  delgoods: function(e){
     var self=this;
     var item=e.currentTarget.dataset.ite;
     var index=e.currentTarget.dataset.index;
     delFun(self,item,index);
  },
  reduce: function(e){
    var self=this;
    var diff=-1;
    var index=e.currentTarget.dataset.index;
    var item=self.data.cart.goodsList[index];
    if(item.count<2){
        return false;
    }else if(self.data.countState){
         self.setData({
           countState:false
         })
      countFun(self,item,index,diff);
    }
    
  },
  add:function(e){
    var self=this;
    var diff=1;
    var index=e.currentTarget.dataset.index;
    var item=self.data.cart.goodsList[index];
    if(item.count>=item.store_nums){
        self.data.cart.goodsList[index].count=item.store_nums;
        self.setData({
          cart:self.data.cart
        })
        return false;
    }else if(self.data.countState){
          self.setData({
           countState:false
         })
      countFun(self,item,index,diff);
    }
  },
  toCart2: function(){
    wx.navigateTo({
      url: '../cart2/cart2'
    })
  }
})
function getCart(self){
  wx.request({
      url: util.pre()+'/apic/cart',
      data:{
          token:util.code() 
      },
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
         self.setData({
           cart:res.data,
         })
      } 
    }) 
}

//刪除購物車中商品
function delFun(self,item,index){
    var goods_id = item.product_id > 0 ? item.product_id : item.goods_id;
	  var goods_type = item.product_id > 0 ? "product" : "goods";
    wx.showModal({
            title: '刪除商品',
            content: '是否刪除該商品',
            success: function(res) {
              if (res.confirm) {
                //請求移除購物車
                wx.request({
                    url: util.pre()+'/simple/removeCart',
                    data:{
                        token:util.code(),
                        goods_id:goods_id,
                        type:goods_type,
                        random:Math.random()
                    },
                    header: {
                        'Content-Type': 'application/json'
                    },
                    success: function(res) {
                        self.data.cart.final_sum=(self.data.cart.final_sum-item.sum).toFixed(2);
                        self.data.cart.count-=item.count;
				                self.data.cart.goodsList.splice(index,1);
                        self.setData({
                          cart:self.data.cart
                        })
                    } 
                  }) 
              }
            }
          })
}
// 购物车数量改变
function countFun(self,item,index,diff){
    var goods_id = item.product_id > 0 ? item.product_id : item.goods_id;
    var goods_type = item.product_id > 0 ? "product" : "goods";
      wx.request({
                  url: util.pre()+'/simple/joinCart',
                  data:{
                      token:util.code(),
                      goods_id:goods_id,
                      type:goods_type,
                      goods_num:diff,
                      random:Math.random()
                  },
                  header: {
                      'Content-Type': 'application/json'
                  },
                  success: function(res) {
                      item.count= parseInt(item.count)+diff;
                      item.sum=(item.sell_price*item.count).toFixed(2);
                      self.data.cart.final_sum=(parseFloat(self.data.cart.final_sum)+parseFloat(item.sell_price)*diff).toFixed(2);
                      self.data.cart.count=parseInt(self.data.cart.count)+diff;
                      self.setData({
                        cart:self.data.cart,
                        countState:true
                      })
                  } 
                }) 
}