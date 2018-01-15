Page({
    data:{
        buynum:1
    },
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
   accountOrder:function(){
    wx.navigateTo({
        url: '../accountOrder/accountOrder'
      })
  }
})