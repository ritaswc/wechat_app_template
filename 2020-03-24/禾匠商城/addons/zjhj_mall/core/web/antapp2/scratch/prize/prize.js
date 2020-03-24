if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
    * 页面的初始数据
    */
    data: {
        args:false,
        page:1,
    },

    /**
    * 生命周期函数--监听页面加载
    */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
    },

    onShow:function(){
        getApp().page.onShow(this);
        
        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        })
        getApp().request({
            url: getApp().api.scratch.prize,
            data:{page:1},
            success: function (res) {
                if(res.code==0){
                    self.setData({
                        list:res.data
                    })
                }
            },          
            complete: function (res) {
                getApp().core.hideLoading();
            }
        });
    },

    /**
    * 页面上拉触底事件的处理函数
    */
    onReachBottom: function () {
        getApp().page.onReachBottom(this);

        var self = this;
        if (self.data.args) return;
        var page = self.data.page + 1;

        getApp().request({
            url: getApp().api.scratch.prize,
            data: {page:page},
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        list: self.data.list.concat(res.data),
                        page:page
                    })
                }else{
                    self.data.args = true;
                }
            },
        });
    },

    submit:function(e){
        var gift = e.currentTarget.dataset.gift;
        var attr = JSON.parse(e.currentTarget.dataset.attr);
        var id = e.currentTarget.dataset.id;

        getApp().core.navigateTo({
            url: "/pages/order-submit/order-submit?scratch_id="+ id +"&goods_info=" + JSON.stringify({
                goods_id: gift,
                attr: attr,
                num: 1,
            }),
        });
    },

})