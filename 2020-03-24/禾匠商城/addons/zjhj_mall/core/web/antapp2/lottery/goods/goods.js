if (typeof wx === 'undefined') var wx = getApp().core;
var utils = getApp().helper;
var videoContext = '';
var WxParse = require('../../wxParse/wxParse.js');
var lotteryInter;
var options_id;
var timer;
var luckyTimer;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        hide: "hide",
        time_list: {
            day: 0,
            hour: '00',
            minute: '00',
            second: '00'
        },
        p: 1,
        user_index: 0,
        show_animate: true,
        animationTranslottery:{},
        award_bg:false,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        this.getBinding();
        if(options.user_id){
            this.buyZero();           
        }
        if (typeof my === 'undefined') {
            var scene = decodeURIComponent(options.scene);
            if (typeof scene !== 'undefined') {
                var scene_obj = utils.scene_decode(scene);
                if (scene_obj.gid) {
                    options.id = scene_obj.gid;
                }
            }
        } else {
            if (getApp().query !== null) {
                var query = app.query;
                getApp().query = null;
                options.id = query.gid;
            }
        }
        options_id = options.id;
    },
    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function() {
        getApp().page.onShow(this);
        this.getGoods({id:options_id});
    },

    getBinding: function () {
        const userInfo = getApp().core.getStorageSync('USER_INFO');
        if (!userInfo) {
            return;
        }
        const binding = userInfo.binding;
        if (!binding) {
            this.setData({
                user_bind_show: true
            })
        }
    },

    getGoods: function(options) {
        var self = this;
        var id = options.id;
        console.log(options);
        getApp().core.showLoading({
            title: '加载中',
        });
        var self = this;
        getApp().request({
            url: getApp().api.lottery.goods,
            data: {
                id: id,
                user_id:options.user_id,
                page: 1,
            },
            success: function(res) {
                if (res.code == 0) {
                    var detail = res.data.goods.detail;
                    var time = res.data.lottery_info.end_time;
                    self.setTimeStart(time);
                    WxParse.wxParse("detail", "html", detail, self);
                    self.setData(res.data);
                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                        success: function(e) {
                            if (e.confirm) {
                                // getApp().core.navigateBack({
                                //     delta: -1
                                // })
                                getApp().core.redirectTo({
                                    url: "/lottery/index/index",
                                });
                            }
                        }
                    })
                }
            },
            complete: function(res) {
                getApp().core.hideLoading();
            }
        });
    },
    catchTouchMove:function(res){
        return false
    },
    onHide: function () {
        getApp().page.onHide(this);
        clearInterval(timer);
        clearInterval(lotteryInter);
        this.setData({
            award_bg: false,
        }) 
    },
    onUnload: function () {
        getApp().page.onUnload(this);
        clearInterval(timer);
    },

    setTimeStart: function (e) {
        var self  =this;
        var nowTime = new Date();
        var times = parseInt((e - nowTime.getTime() / 1000));
        clearInterval(timer);
        timer = setInterval(function(){
            var day=0,
                hour=0,
                minute=0,
                second=0;//时间默认值

            if(times > 0){
              day = Math.floor(times / (60 * 60 * 24));
              hour = Math.floor(times / (60 * 60)) - (day * 24);
              minute = Math.floor(times / 60) - (day * 24 * 60) - (hour * 60);
              second = Math.floor(times) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
            }
            var time_list = {
                'day': day,
                'hour':hour,
                'minute':minute,
                'second':second,
            };
            self.setData({
                time_list:time_list
            })
            times--;
        },1000);

        if(times<=0){
            clearInterval(timer);
        }

    },

    buyZero: function() {
        var self = this;
        var award_bg = self.data.award_bg ? false : true;
        self.setData({
            award_bg: award_bg,
        }) 

        var animation = getApp().core.createAnimation({
          duration: 1000,
            timingFunction: 'linear',
            transformOrigin: '50% 50%',
        })
        
        if(self.data.award_bg){
            animation.width('360rpx').height('314rpx').step();         
        }else {
            animation.scale(0,0).opacity(0).step();  
        }

        self.setData({
            animationTranslottery: animation.export()
        });

        var circleCount = 0;
        lotteryInter = setInterval(function () {
            if (circleCount % 2 == 0) {
                animation.scale(0.9).opacity(1).step();
            } else {
                animation.scale(1).opacity(1).step();
            }

            self.setData({
                animationTranslottery: animation.export()
            });

            circleCount++;
            if (circleCount == 500) {
                circleCount = 0;
            }
        }, 500)
    },

    submitTime:function() {
        var self = this;
        var animation = getApp().core.createAnimation({
            duration: 500,
            transformOrigin: '50% 50%',
        });
        var self = this;

        var circleCount = 0;
        lotteryInter = setInterval(function() {
            if (circleCount % 2 == 0) {
                animation.scale(2.3,2.3).opacity(1).step();
            } else {
                animation.scale(2.5,2.5).opacity(1).step();
            }

            self.setData({
                animationTranslottery: animation.export()
            });

            circleCount++;
            if (circleCount == 500) {
                circleCount = 0;
            }
        }, 500)
    },

    submit:function(e) {
        var self = this;
        var formId = e.detail.formId;
     
        var lottery_id = e.currentTarget.dataset.lottery_id;

        getApp().core.navigateTo({
            url: "/lottery/detail/detail?lottery_id=" + lottery_id + "&form_id=" + formId,
        });
        clearInterval(lotteryInter);
        self.setData({
            award_bg:false,
        }) 

    },
    play: function(e) {
        var url = e.target.dataset.url; //获取视频链接
        this.setData({
            url: url,
            hide: '',
            show: true,
        });
        videoContext = getApp().core.createVideoContext('video');
        videoContext.play();
    },

    close: function(e) {
        if (e.target.id == 'video') {
            return true;
        }
        this.setData({
            hide: "hide",
            show: false
        });
        videoContext.pause();
    },

    onGoodsImageClick: function(e) {
        var self = this;
        var urls = [];
        var index = e.currentTarget.dataset.index;
        for (var i in self.data.goods.pic_list) {
            urls.push(self.data.goods.pic_list[i].pic_url);
        }
        getApp().core.previewImage({
            urls: urls, // 需要预览的图片http链接列表
            current: urls[index],
        });
    },

    hide: function(e) {
        if (e.detail.current == 0) {
            this.setData({
                img_hide: ""
            });
        } else {
            this.setData({
                img_hide: "hide"
            });
        }
    },

    buyNow: function(e) {
        var self = this;
        var cart_list = [];
        let cart_list_goods = {
            goods_id: self.data.goods.id,
            num: 1,
            attr: JSON.parse(self.data.lottery_info.attr),
        }
        cart_list.push(cart_list_goods)

        var mch_list = [];
        mch_list.push({
            mch_id: 0,
            goods_list: cart_list
        });

        getApp().core.navigateTo({
            url: '/pages/new-order-submit/new-order-submit?mch_list=' + JSON.stringify(mch_list),
        });
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
        let user_info = getApp().getUser();
        let id = this.data.lottery_info.id;
        var res = {
            imageUrl:this.data.goods.pic_list[0].pic_url,
            path: "/lottery/goods/goods?id=" + id + "&user_id=" + user_info.id,
        };
        console.log(res);
        return res;
    },

    /**
    *  海报
    */
    showShareModal:function(){
        this.setData({
            share_modal_active: "active",
        });
    },

    shareModalClose:function(){
        this.setData({
            share_modal_active:'',
        })
    },

    getGoodsQrcode: function() {
        var self = this;
        self.setData({
            qrcode_active: "active",
            share_modal_active: "",
        });
        if (self.data.goods_qrcode) return true

        getApp().request({
            url: getApp().api.lottery.qrcode,
            data: {
                goods_id: self.data.lottery_info.id,
            },
            success: function(res) {
                if (res.code == 0) {
                    self.setData({
                        goods_qrcode: res.data.pic_url,
                    });
                }
                if (res.code == 1) {
                    self.goodsQrcodeClose();
                    getApp().core.showModal({
                        title: "提示",
                        content: res.msg,
                        showCancel: false,
                        success: function(res) {
                            if (res.confirm) {
                            }
                        }
                    });
                }
            },
        });
    },  

    qrcodeClick: function(e) {
        var src = e.currentTarget.dataset.src;
        getApp().core.previewImage({
            urls: [src],
        });
    },
    qrcodeClose: function() {
        var self = this; 
        self.setData({
            qrcode_active: "",
        });
    },

    goodsQrcodeClose: function() {
        var self = this;
        self.setData({
            goods_qrcode_active: "",
            no_scroll: false,
        });
    },

    saveQrcode: function() {
        var self = this;
        if (!getApp().core.saveImageToPhotosAlbum) {
            getApp().core.showModal({
                title: '提示',
                content: '当前版本过低，无法使用该功能，请升级到最新版本后重试。',
                showCancel: false,
            });
            return;
        }

        getApp().core.showLoading({
            title: "正在保存图片",
            mask: false,
        });

        getApp().core.downloadFile({
            url: self.data.goods_qrcode,
            success: function(e) {
                getApp().core.showLoading({
                    title: "正在保存图片",
                    mask: false,
                });
                getApp().core.saveImageToPhotosAlbum({
                    filePath: e.tempFilePath,
                    success: function() {
                        getApp().core.showModal({
                            title: '提示',
                            content: '商品海报保存成功',
                            showCancel: false,
                        });
                    },
                    fail: function(e) {
                        getApp().core.showModal({
                            title: '图片保存失败',
                            content: e.errMsg,
                            showCancel: false,
                        });
                    },
                    complete: function(e) {
                        getApp().core.hideLoading();
                    }
                });
            },
            fail: function(e) {
                getApp().core.showModal({
                    title: '图片下载失败',
                    content: e.errMsg + ";" + self.data.goods_qrcode,
                    showCancel: false,
                });
            },
            complete: function(e) {
                getApp().core.hideLoading();
            }
        });
    },

})