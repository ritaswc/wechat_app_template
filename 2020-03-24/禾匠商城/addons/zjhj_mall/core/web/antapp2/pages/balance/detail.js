if (typeof wx === 'undefined') var wx = getApp().core;
var is_more = false;
Page({

    /**
     * 页面的初始数据
     */
    data: {

    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);

        var self = this;
        self.setData(options);
        getApp().core.showLoading({
            title: '加载中',
        });
        getApp().request({
            url: getApp().api.recharge.detail,
            method: 'GET',
            data: {
                order_type: options.order_type,
                id: options.id
            },
            success:function(res){
                getApp().core.hideLoading();
                if(res.code == 0){
                    self.setData({
                        list: res.data
                    });
                }else{
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                    })
                }
            }
        });
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {
        getApp().page.onReady(this);
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {
        getApp().page.onHide(this);
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {
        getApp().page.onUnload(this);
    },
})