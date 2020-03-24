if (typeof wx === 'undefined') var wx = getApp().core;
// pages/order-comment/order-comment.js
var app = getApp();
var api = getApp().api;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        goods_list: []
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var self = this;
        if (options.inId) {
            var data = {
                order_id: options.inId,
                type: 'IN'
            }
        } else {
            var data = {
                order_id: options.id,
                type: 'mall'
            }
        }
        self.setData({
            order_id: data.order_id,
            type:data.type
        });
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.order.comment_preview,
            data: data,
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 1) {
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        success: function (e) {
                            if (e.confirm) {
                                getApp().core.navigateBack();
                            }
                        }
                    });
                }
                if (res.code == 0) {
                    for (var i in res.data.goods_list) {
                        res.data.goods_list[i].score = 3;
                        res.data.goods_list[i].content = "";
                        res.data.goods_list[i].pic_list = [];
                        res.data.goods_list[i].uploaded_pic_list = [];
                    }
                    self.setData({
                        goods_list: res.data.goods_list,
                    });
                }
            }
        });
    },

    setScore: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var score = e.currentTarget.dataset.score;
        var goods_list = self.data.goods_list;
        goods_list[index].score = score;
        self.setData({
            goods_list: goods_list,
        });
    },
    contentInput: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        self.data.goods_list[index].content = e.detail.value;
        self.setData({
            goods_list: self.data.goods_list,
        });
    },
    chooseImage: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var max_count = 6;
        var goods_list = self.data.goods_list;
        var current_count = goods_list[index].pic_list.length;
        getApp().core.chooseImage({
            count: (max_count - current_count),
            success: function (res) {
                goods_list[index].pic_list = goods_list[index].pic_list.concat(res.tempFilePaths);
                self.setData({
                    goods_list: goods_list,
                });
            }
        });
    },
    deleteImage: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var pic_index = e.currentTarget.dataset.picIndex;
        var goods_list = self.data.goods_list;
        goods_list[index].pic_list.splice(pic_index, 1);
        self.setData({
            goods_list: goods_list
        });
    },

    commentSubmit: function (e) {
        var self = this;
        getApp().core.showLoading({
            title: "正在提交",
            mask: true,
        });
        var goods_list = self.data.goods_list;
        var siteinfo = getApp().siteInfo;
        var postData = {};
        uploadImages(0);

        function uploadImages(i) {
            if (i == goods_list.length) {
                return submit();
            }
            var complete_count = 0;
            if (!goods_list[i].pic_list.length || goods_list[i].pic_list.length == 0) {
                return uploadImages((i + 1));
            }
            for (var j in goods_list[i].pic_list) {
                (function (j) {
                    getApp().core.uploadFile({
                        url: getApp().api.default.upload_image,
                        name: "image",
                        formData: postData,
                        filePath: goods_list[i].pic_list[j],
                        complete: function (e) {
                            if (e.data) {
                                var res = JSON.parse(e.data);
                                if (res.code == 0) {
                                    goods_list[i].uploaded_pic_list[j] = res.data.url;
                                }
                            }
                            complete_count++;
                            if (complete_count == goods_list[i].pic_list.length) {
                                return uploadImages((i + 1));
                            }
                        }
                    });
                })(j);
            }
        }
        function submit() {
            getApp().request({
                url: getApp().api.order.comment,
                method: "post",
                data: {
                    order_id: self.data.order_id,
                    goods_list: JSON.stringify(goods_list),
                    type: self.data.type
                },
                success: function (res) {
                    getApp().core.hideLoading();
                    if (res.code == 0) {
                        getApp().core.showModal({
                            title: "提示",
                            content: res.msg,
                            showCancel: false,
                            success: function (e) {
                                if (e.confirm) {
                                    if(res.type == 'IN'){
                                        getApp().core.redirectTo({
                                            url: "/pages/integral-mall/order/order?status=3",
                                        });
                                    }else{
                                        getApp().core.redirectTo({
                                            url: "/pages/order/order?status=3",
                                        });
                                    }
                                }
                            }
                        });
                    }
                    if (res.code == 1) {
                        getApp().core.showToast({
                            title: res.msg,
                            image: "/images/icon-warning.png",
                        });
                    }
                }
            });

        }

    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {

    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function () {

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function () {

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {

    },
});