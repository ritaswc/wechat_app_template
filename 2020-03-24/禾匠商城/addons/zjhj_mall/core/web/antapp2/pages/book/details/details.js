if (typeof wx === 'undefined') var wx = getApp().core;
var utils = require('../../../utils/helper.js');
var WxParse = require('../../../wxParse/wxParse.js');
var gSpecificationsModel = require('../../../components/goods/specifications_model.js');//商城多规格选择
var goodsBanner = require('../../../components/goods/goods_banner.js');
var goodsInfo = require('../../../components/goods/goods_info.js');
var goodsBuy = require('../../../components/goods/goods_buy.js');
var p = 1;
var is_loading_comment = false;
var is_more_comment = true;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        pageType: 'BOOK',
        hide: "hide",
        form: {
            number: 1,
        },
        tab_detail: "active",
        tab_comment: "",
        comment_list: [],
        comment_count: {
            score_all: 0,
            score_3: 0,
            score_2: 0,
            score_1: 0,
        },
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);
        var parent_id = 0;
        var user_id = options.user_id;
        var scene = decodeURIComponent(options.scene);
        if (typeof user_id !== 'undefined') {
            parent_id = user_id;
        } else if (typeof scene !== 'undefined') {
            var scene_obj = utils.scene_decode(scene);
            if (scene_obj.uid && scene_obj.gid) {
                parent_id = scene_obj.uid;
                options.id = scene_obj.gid;
            } else {
                parent_id = scene;
            }
        } else {
            if (getApp().query !== null) {
                var query = getApp().query;
                getApp().query = null;
                options.id = query.gid;
                parent_id = query.uid;
            }
        }

        this.setData({
            id: options.id,
        });
        p = 1;
        this.getGoodsInfo(options);
        this.getCommentList(false);
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function (options) {
        getApp().page.onReady(this);

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function (options) {
        getApp().page.onShow(this);
        gSpecificationsModel.init(this);
        goodsBanner.init(this);
        goodsInfo.init(this);
        goodsBuy.init(this);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function (options) {
        getApp().page.onHide(this);

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function (options) {
        getApp().page.onUnload(this);

    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function (options) {
        getApp().page.onPullDownRefresh(this);

    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function (options) {
        getApp().page.onReachBottom(this);
        var self = this;
        self.getCommentList(true);
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function (options) {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var user_info = getApp().core.getStorageSync(getApp().const.USER_INFO);
        return {
            title: self.data.goods.name,
            path: '/pages/book/details/details?id=' + self.data.goods.id + '&user_id=' + user_info.id,
            imageUrl: self.data.goods.pic_list[0],
            success: function (res) {
                // 转发成功
            }
        }
    },
    /**
     * 获取商品详情
     */
    getGoodsInfo: function (e) {
        var gid = e.id;
        var self = this;
        getApp().core.showLoading({
            title: "正在加载",
            mask: true,
        });
        getApp().request({
            url: getApp().api.book.details,
            method: "get",
            data: { gid: gid },
            success: function (res) {
                if (res.code == 0) {
                    var detail = res.data.info.detail;
                    WxParse.wxParse("detail", "html", detail, self);
                    var sales = (parseInt(res.data.info.virtual_sales) + parseInt(res.data.info.sales));
                    //todo 兼容
                    if (res.data.attr_group_list.length <= 0) {
                        res.data.attr_group_list = [{
                            attr_group_name: '规格',
                            attr_list: [{
                                attr_id: 0,
                                attr_name: '默认',
                                checked: true,
                            }]
                        }];
                    };
                    var goods = res.data.info;
                    goods.num = res.data.info.stock;
                    goods.min_price = res.data.info.price > 0.01 ? res.data.info.price : '免费预约';
                    goods.price = res.data.info.price > 0.01 ? res.data.info.price : '免费预约';
                    goods.sales_volume = res.data.info.sales;
                    goods.service_list = res.data.info.service;
                    self.setData({
                        goods: res.data.info,
                        shop: res.data.shopList,
                        sales: sales,
                        attr_group_list: res.data.attr_group_list
                    });

                    self.selectDefaultAttr();
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function (res) {
                            if (res.confirm) {
                                getApp().core.redirectTo({
                                    url: '/pages/book/index/index'
                                });
                            }
                        }
                    });
                }
            },
            complete: function (res) {
                getApp().core.hideLoading();
            }
        });
    },

    tabSwitch: function (e) {
        var self = this;
        var tab = e.currentTarget.dataset.tab;
        if (tab == "detail") {
            self.setData({
                tab_detail: "active",
                tab_comment: "",
            });
        } else {
            self.setData({
                tab_detail: "",
                tab_comment: "active",
            });
        }
    },
    commentPicView: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var pic_index = e.currentTarget.dataset.picIndex;
        getApp().core.previewImage({
            current: self.data.comment_list[index].pic_list[pic_index],
            urls: self.data.comment_list[index].pic_list,
        });
    },
    /**
     * 立即预约
     */
    bespeakNow: function (e) {
        var self = this;
        if (!self.data.show_attr_picker) {
            self.setData({
                show_attr_picker: true,
            });
            return true;
        }

        var book_list = [];
        let sentinel = true;
        var list = self.data.attr_group_list;
        for (var i = 0; i < list.length; i++) {
            let attr_list = list[i]['attr_list'];
            sentinel = true;
            for (var j = 0; j < attr_list.length; j++) {
                if (attr_list[j]['checked']) {
                    book_list.push({
                        attr_group_id: list[i]['attr_group_id'],
                        attr_id: attr_list[j]['attr_id'],
                        attr_group_name: list[i]['attr_group_name'],
                        attr_name: attr_list[j]['attr_name']
                    })
                    sentinel = false;
                    continue;
                }
            }
            if (sentinel) {
                getApp().core.showModal({
                    title: "提示",
                    content: '请选择' + list[i].attr_group_name,
                    showCancel: false,
                });
                return
            }
        }

        let goods_info = [{
            id: self.data.goods.id,
            attr: book_list
        }]
        getApp().core.redirectTo({
            url: '/pages/book/submit/submit?goods_info=' + JSON.stringify(goods_info),
        })
    },
    
    /**
     * 门店列表
     */
    goToShopList: function (e) {
        getApp().core.navigateTo({
            url: '/pages/book/shop/shop?ids=' + this.data.goods.shop_id,
            success: function (res) { },
            fail: function (res) { },
            complete: function (res) { },
        });
    },
    /**
     * 获取评论列表
     */
    getCommentList: function (more) {
        var self = this;
        if (more && self.data.tab_comment != "active")
            return;
        if (is_loading_comment)
            return;
        // if (!is_more_comment)
        //     return;
        is_loading_comment = true;
        getApp().request({
            url: getApp().api.book.goods_comment,
            data: {
                goods_id: self.data.id,
                page: p,
            },
            success: function (res) {
                if (res.code != 0)
                    return;
                is_loading_comment = false;
                p++;
                self.setData({
                    comment_count: res.data.comment_count,
                    comment_list: more ? self.data.comment_list.concat(res.data.list) : res.data.list,
                });
                if (res.data.list.length == 0)
                    is_more_comment = false;
            }
        });
    },
    // showShareModal: function () {
    //     var self = this;
    //     self.setData({
    //         share_modal_active: "active",
    //         no_scroll: true,
    //     });
    // },

    // shareModalClose: function () {
    //     var self = this;
    //     self.setData({
    //         share_modal_active: "",
    //         no_scroll: false,
    //     });
    // },
    // getGoodsQrcode: function () {
    //     var self = this;
    //     self.setData({
    //         goods_qrcode_active: "active",
    //         share_modal_active: "",
    //     });
    //     if (self.data.goods_qrcode)
    //         return true;
    //     getApp().request({
    //         url: getApp().api.book.goods_qrcode,
    //         data: {
    //             goods_id: self.data.id,
    //         },
    //         success: function (res) {
    //             if (res.code == 0) {
    //                 self.setData({
    //                     goods_qrcode: res.data.pic_url,
    //                 });
    //             }
    //             if (res.code == 1) {
    //                 self.goodsQrcodeClose();
    //                 getApp().core.showModal({
    //                     title: "提示",
    //                     content: res.msg,
    //                     showCancel: false,
    //                     success: function (res) {
    //                         if (res.confirm) {

    //                         }
    //                     }
    //                 });
    //             }
    //         },
    //     });
    // },
    // goodsQrcodeClose: function () {
    //     var self = this;
    //     self.setData({
    //         goods_qrcode_active: "",
    //         no_scroll: false,
    //     });
    // },
    // goodsQrcodeClose: function () {
    //     var self = this;
    //     self.setData({
    //         goods_qrcode_active: "",
    //         no_scroll: false,
    //     });
    // },

    // saveGoodsQrcode: function () {
    //     var self = this;
    //     if (!getApp().core.saveImageToPhotosAlbum) {
    //         // 如果希望用户在最新版本的客户端上体验您的小程序，可以这样子提示
    //         getApp().core.showModal({
    //             title: '提示',
    //             content: '当前版本过低，无法使用该功能，请升级到最新版本后重试。',
    //             showCancel: false,
    //         });
    //         return;
    //     }

    //     getApp().core.showLoading({
    //         title: "正在保存图片",
    //         mask: false,
    //     });

    //     getApp().core.downloadFile({
    //         url: self.data.goods_qrcode,
    //         success: function (e) {
    //             getApp().core.showLoading({
    //                 title: "正在保存图片",
    //                 mask: false,
    //             });
    //             getApp().core.saveImageToPhotosAlbum({
    //                 filePath: e.tempFilePath,
    //                 success: function () {
    //                     getApp().core.showModal({
    //                         title: '提示',
    //                         content: '商品海报保存成功',
    //                         showCancel: false,
    //                     });
    //                 },
    //                 fail: function (e) {
    //                     getApp().core.showModal({
    //                         title: '图片保存失败',
    //                         content: e.errMsg,
    //                         showCancel: false,
    //                     });
    //                 },
    //                 complete: function (e) {
    //                     getApp().core.hideLoading();
    //                 }
    //             });
    //         },
    //         fail: function (e) {
    //             getApp().core.showModal({
    //                 title: '图片下载失败',
    //                 content: e.errMsg + ";" + self.data.goods_qrcode,
    //                 showCancel: false,
    //             });
    //         },
    //         complete: function (e) {
    //             getApp().core.hideLoading();
    //         }
    //     });

    // },

    // goodsQrcodeClick: function (e) {
    //     var src = e.currentTarget.dataset.src;
    //     getApp().core.previewImage({
    //         urls: [src],
    //     });
    // },
    // goHome: function (e) {
    //     getApp().core.redirectTo({
    //         url: '/pages/book/index/index',
    //         success: function (res) { },
    //         fail: function (res) { },
    //         complete: function (res) { },
    //     })
    // }
})