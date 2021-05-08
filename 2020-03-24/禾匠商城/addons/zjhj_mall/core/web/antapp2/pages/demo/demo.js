if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {},

// <view class="page {{__page_classes}}">
//     <view class="body after-navber">

// 1  self
// 2 showToast 
// 3 StorageSync 
// 5 setInterval setTimeout
// 6 getApp(). 
// 7.getApp().page.onLoad(this, options);
    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;

        // 获取商城配置信息
        getApp().getConfig(function (res) {
            self.setData({ 
                store: res.store
            }); 
        });
        // 获取用户信息 
        var user_info = getApp().getUser();
        // 存储用户信息
        getApp().setUser(user_info);
        self.showToast({
            title:'提示'
        });
        // 获取缓存
        var store = getApp().core.getStorageSync(getApp().const.STORE);
        // 存储缓存
        getApp().core.setStorageSync(getApp().const.STORE,store);
        // 添加延时事件
        getApp().trigger.add(getApp().trigger.events.login,'测试e',function(res){
            console.log('add-->添加了一个事件')
            console.log('传递的参数-->' + res);
        });
        // 触发延时事件
        getApp().trigger.run(getApp().trigger.events.login,function(){
            console.log('callback-->这里执行延时事件的回调函数')
        },1);
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

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {
        getApp().page.onPullDownRefresh(this);
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {
        getApp().page.onReachBottom(this);
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
    },

    myBtnClick: function (e) {
        console.log('myBtnClick', e);
    },

});
