if (typeof wx === 'undefined') var wx = getApp().core;
// step/share.js
Page({
    data: {
        img: [],
        todayStep: 0,
        pic_list: '',
        save: true,
        page: 1,
        left: false,
        right: true,
        get: false
    },
    tagChoose: function(options) {
        var that = this;
        let id = options.currentTarget.dataset.id;
        let todayStep = that.data.num;
        that.setData({
            'currentItem': id,
        })
        getApp().core.showLoading({
            title: '分享图片生成中...',
            mask: true,
        });
        getApp().request({
            url: getApp().api.step.qrcode,
            data: {
                goods_id: id,
                num: todayStep
            },
            success(res) {
                console.log(res)
                if (res.code == 0) {
                    that.setData({
                        img: res.data.pic_url,
                    })
                } else {
                    getApp().core.showModal({
                        content: res.msg,
                        showCancel: false,
                    });
                }
                setTimeout(function() {
                    getApp().core.hideLoading();
                }, 1000);
            },
            fail(res) {
                getApp().core.showModal({
                    content: res.msg,
                    showCancel: false,
                });
            }
        })
    },
    screen: function() {
        let img = this.data.img
        getApp().core.previewImage({
            urls: [img]
        })
    },
    saveImage: function() {
        let that = this;
        getApp().core.authorize({
            scope: 'scope.writePhotosAlbum',
            success(res) {
                if (res.errMsg == "authorize:ok") {
                    getApp().core.getImageInfo({
                        src: that.data.img,
                        success(res) {
                            getApp().core.saveImageToPhotosAlbum({
                                filePath: res.path,
                                success(res) {
                                    getApp().core.showToast({
                                        title: '保存成功，快去发朋友圈吧！',
                                        icon: 'success',
                                        duration: 2000
                                    })
                                },
                                fail(res) {
                                    getApp().core.showModal({
                                        content: "授权失败",
                                        showCancel: false,
                                    });
                                }
                            })
                        }
                    })
                }
            },
            fail(res) {
                getApp().core.showModal({
                    content: "为确保您的正常使用，请点击下方按钮授权",
                    showCancel: false,
                });
                that.setData({
                    save: false,
                    get: true
                })
            }
        })
    },
    openSetting() {
        let that = this;
        wx.openSetting({
            success(res) {
                if (res.authSetting['scope.writePhotosAlbum'] == true) {
                    that.setData({
                        save: true,
                        get: false
                    })
                }
            },
            fail(res) {
                getApp().core.showModal({
                    content: "为确保您的正常使用，请点击下方按钮授权",
                    showCancel: false,
                });
            }
        })
    },
    chooseImg(e) {
        let that = this;
        let page = this.data.page;
        let left = true;
        let right = true;
        let btn = e.currentTarget.dataset.id;
        if (btn == 1) {
            page--;
        } else if (btn == 2) {
            page++;
        }
        getApp().request({
            url: getApp().api.step.pic_list,
            data: {
                page: page
            },
            success(res) {
                let pic_list = res.data.pic_list;
                if (pic_list.length == 0) {
                    getApp().core.showToast({
                        title: '没有更多了',
                        icon: 'none',
                        duration: 1000
                    })
                    right = false;
                    that.setData({
                        right: right
                    })
                } else {
                    if (page == 1) {
                        left = false;
                    }
                    if (pic_list.length < 4) {
                        right = false;
                    }
                    that.setData({
                        page: page,
                        pic_list: pic_list,
                        left: left,
                        right: right
                    })
                }
            }
        })
    },
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        let that = this;
        // 获取步数
        let todayStep = 0;
        if (options.todayStep) {
            todayStep = options.todayStep;
        }
        that.setData({
            num: todayStep
        })
        let id;
        let pic_list;
        getApp().core.showLoading({
            title: '分享图片生成中...',
            mask: true,
        });
        getApp().request({
            url: getApp().api.step.pic_list,
            data: {
                page: 1
            },
            success(res) {
                let pic_list = res.data.pic_list;
                let right = true;
                if (pic_list[0].pic_url.length > 0) {
                    if (pic_list.length < 4) {
                        right = false;
                    }
                    that.setData({
                        pic_list: pic_list,
                        "currentItem": pic_list[0].id,
                        right: right
                    })
                    getApp().request({
                        url: getApp().api.step.qrcode,
                        data: {
                            goods_id: pic_list[0].id,
                            num: todayStep,
                        },
                        success(res) {
                            setTimeout(function() {
                                if (res.code == 0) {
                                    that.setData({
                                        img: res.data.pic_url,
                                    })
                                } else {
                                    getApp().core.showModal({
                                        content: res.msg,
                                        showCancel: false,
                                    });
                                }
                                getApp().core.hideLoading();
                            }, 1000);
                        }
                    })
                } else {
                    getApp().core.hideLoading();
                    getApp().core.showToast({
                        title: '暂无海报模板',
                        icon: 'none',
                        duration: 1000
                    })
                    setTimeout(function() {
                        getApp().core.navigateTo({
                            url: "../index/index"
                        })
                    }, 1000)
                }
            }
        })
    }
})