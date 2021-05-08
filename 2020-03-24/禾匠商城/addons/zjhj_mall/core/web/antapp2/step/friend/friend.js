if (typeof wx === 'undefined') var wx = getApp().core;
// step/friend/friend.js
Page({

    /**
     * 页面的初始数据
     */
    data: {
        invite_list: [],
        info: [],
        page: 2,
        loading: false,
        length:0
    },
    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        let that = this;
        getApp().core.showLoading({
            title: '数据加载中...',
            mask: true,
        });
        getApp().request({
            url: getApp().api.step.invite_detail,
            data: {
                page: 1
            },
            success(res) {
                getApp().core.hideLoading();
                let info = res.data.info;
                let invite_list = res.data.invite_list;
                let length = invite_list.length
                that.setData({
                    info: info,
                    length: length,
                    invite_list: invite_list
                })
            }
        })
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function() {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {

    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function() {

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function() {

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function() {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function() {
        let that = this;
        let over = that.data.over;
        let invite_list = that.data.invite_list;
        let user_id, code, iv, encrypted_data;
        if (!over) {
            let page = this.data.page;
            this.setData({
                loading: true
            })
            getApp().request({
                url: getApp().api.step.invite_detail,
                data: {
                    page: page
                },
                success(res) {
                    for (let i = 0; i < res.data.invite_list.length; i++) {
                        invite_list.push(res.data.invite_list[i]);
                    }
                    if (res.data.invite_list.length < 15) {
                        over = true;
                    }
                    that.setData({
                        page: page + 1,
                        over: over,
                        loading: false,
                        invite_list: invite_list
                    })
                }
            })
        }
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function() {

    }
})