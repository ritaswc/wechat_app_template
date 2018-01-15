Page({
    data: {
        searchHeight:110,
        orderFilterHeight:80,
        scrollHeight:1000,
        searchByOrder:false,
        searchByFilter:false
    },
    
    onLoad: function () {
        this.setScrollHeight();
    },
    scanCode:function(){
        wx.scanCode({
            success: (res) => {
                console.log(10,res)
            }
        })
    },

    
    // 按顺序搜索
    searchByOrder:function(){
        this.setData({
            searchByOrder:!this.data.searchByOrder,
            searchByFilter:false
        }) 
    },
    // 按过滤搜索
    searchByFilter:function(){
        this.setData({
            searchByOrder:false,
            searchByFilter:!this.data.searchByFilter
        })
    },
    confirmFilter:function(){
        this.setData({
            searchByOrder:false,
            searchByFilter:false
        })
    },
    inspectDiscount:function(){
        wx.navigateTo({
          url: '../discount-detail/discount-detail?from=inspect',
          success: function(res){
            wx.setNavigationBarTitle({
              title: '检查优惠券',
              success: function(res) {
                // success
              }
            })
          },
          fail: function() {
            // fail
          },
          complete: function() {
            // complete
          }
        })
    },
    addDiscount:function(){
        wx.navigateTo({
          url: '../discount-detail/discount-detail',
          success: function(res){
            // success
            wx.setNavigationBarTitle({
              title: '发放优惠券',
              success: function(res) {
                // success
              }
            })
          },
          fail: function() {
            // fail
          },
          complete: function() {
            // complete
          }
        })
    },
      // 设置混动区域高度
  setScrollHeight:function(){
        const _this = this;
        const searchHeight = _this.data.searchHeight;
        const orderFilterHeight = _this.data.orderFilterHeight;
        const leftHeight = searchHeight*1 + orderFilterHeight;
        wx.getSystemInfo({
        success: function(res) {
            const scrollHeight = res.windowHeight-(leftHeight/res.pixelRatio).toFixed(2) ;
            _this.setData({
                scrollHeight:scrollHeight,
                    scrollHeight:scrollHeight
                })
            }
        })
    },

    
})
