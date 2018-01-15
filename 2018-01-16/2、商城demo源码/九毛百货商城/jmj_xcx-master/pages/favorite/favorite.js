var util = require('../../common/common.js');
let URLINDEX=util.prefix();
Page({
  data:{
      showMessage:false,
      empty:{
          emptyCat:URLINDEX+"/jmj/icon/cat_car.png",
          text:"收藏的内容和商品都在这，赶紧去种草吧"
      },
      headState:true,
      left_red:URLINDEX+'/jmj/favorite/ware_red.png',
      left_black:URLINDEX+'/jmj/favorite/ware_black.png',
      right_red:URLINDEX+'/jmj/favorite/cate_red.png',
      right_black:URLINDEX+'/jmj/favorite/cate_black.png',
      bg1:'color:#3d4225',
      bg2:'color:#ff44a0',
      img_del:URLINDEX+'/jmj/icon/del_w.png'
  },
    changeHeadState:function (e) {
         let sta=e.currentTarget.dataset.sta;
        this.setData({
            headState:sta
        })
    },
    delGoods:function(e){
        let index=e.currentTarget.dataset.index;
        let self=this;
        delgoods(self,index);
    },
    delArticle:function(e){
        let index=e.currentTarget.dataset.index;
        let self=this;
        delarticles(self,index);
    },
    onShow:function(){
      var self=this;
    // 页面初始化 options为页面跳转所带来的参数
    wx.request({
              url: util.pre()+'/apic/favorite_list',
              data:{
                token:util.code()
              },
              success: function(res){
                  self.setData({
                      favorite:res.data,
                      showMessage:true
                  });
                  console.log(self.data.favorite)
              }
            })
  }
})

function delgoods(self,index){
    var goods_id=self.data.favorite.goods_data[index].id
    wx.showModal({
        title: '刪除商品',
        content: '是否刪除該商品',
        success: function(res) {
            if (res.confirm) {
                //請求移除購物車
                wx.request({
                    url: util.pre()+'/simple/favorite_add/_paramKey_/_paramVal_',
                    data:{
                        token:util.code(),
                        goods_id:goods_id,
                        random:Math.random()
                    },
                    header: {
                        'Content-Type': 'application/json'
                    },
                    success: function(res) {
                        self.data.favorite.goods_data.splice(index,1);
                        self.setData({
                            favorite:self.data.favorite
                        })
                    }
                })
            }
        }
    })
}
// 删除专辑函数
function delarticles(self,index){
    var goods_id=self.data.favorite.article_data[index].id
    wx.showModal({
        title: '刪除专辑',
        content: '是否刪除該专辑',
        success: function(res) {
            if (res.confirm) {
                //請求移除購物車
                wx.request({
                    url: util.pre()+'/apic/favorite_article_add',
                    data:{
                        token:util.code(),
                        id:goods_id,
                        random:Math.random()
                    },
                    header: {
                        'Content-Type': 'application/json'
                    },
                    success: function(res) {
                        self.data.favorite.article_data.splice(index,1);
                        self.setData({
                            favorite:self.data.favorite
                        })
                    }
                })
            }
        }
    })
}