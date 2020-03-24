if (typeof wx === 'undefined') var wx = getApp().core;
var p_animation;
var bout;
var animation;
Page({
    data: { 
        circleList: [],  //圆点数组
        awardList: [],  //奖品数组
        colorCircleFirst: '#F12416',  //圆点颜色1
        colorCircleSecond: '#FFFFFF',  //圆点颜色2
        colorAwardDefault: '#F5F0FC',  //奖品默认颜色
        colorAwardSelect: '#ffe400',  //奖品选中颜色
        indexSelect: 0,  //被选中的奖品index
        isRunning: false,  //是否正在抽奖
        prize:false,
        close:false,
        lottert:0,
        animationData:'',
        time:false, 
        title:'',
    },
    onLoad: function (options) {
        getApp().page.onLoad(this, options);

        var self = this;
        getApp().request({
            url: getApp().api.pond.setting,
            success: function (res) {
                if(res.code==0){
                    var title = res.data.title;
                    if(title){
                        getApp().core.setNavigationBarTitle({
                            title:title,
                        })
                        self.setData({
                            title:title
                        })
                    }
                }
            },
        }); 
    },
    onShow: function () {
        getApp().page.onShow(this);

        var self = this;
        getApp().core.showLoading({
            title: '加载中',
        })
        getApp().request({
            url: getApp().api.pond.index,
            success: function (res) {
                self.setData(res.data);

                var list = res.data.list;
                var awardList = [];
                var topAward = 18;
                var leftAward = 18;
                for (var j = 0; j < 8; j++) {
                    if (j == 0) {
                        topAward = 18;
                        leftAward = 18;
                    } else if (j < 3) {
                        topAward = topAward;
                        //166.6666是宽.8是间距.下同
                        leftAward = leftAward + 196 + 8;
                    } else if (j < 5) {
                        leftAward = leftAward;
                        //150是高,15是间距,下同
                        topAward = topAward + 158 + 8;
                    } else if (j < 7) {
                        leftAward = leftAward - 196 - 8;
                        topAward = topAward;
                    } else if (j < 8) {
                        leftAward = leftAward;
                        topAward = topAward - 158 - 8;
                    }
                    list[j].topAward=topAward;
                    list[j].leftAward=leftAward;
                }
                self.setData({
                    awardList: list
                })
            },
            complete: function (res) {
                getApp().core.hideLoading();
                //圆点设置
                var leftCircle = 4;
                var topCircle = 4;
                var circleList = [];
                for (var i = 0; i < 24; i++) {
                    if (i == 0) {
                        topCircle = 8;
                        leftCircle = 8;
                    } else if (i < 6) {
                        topCircle = 4;
                        leftCircle = leftCircle + 110;
                    } else if (i == 6) {
                        topCircle = 8
                        leftCircle = 660;
                    } else if (i < 12) {
                        topCircle = topCircle + 92;
                        leftCircle = 663;
                    } else if (i == 12) {
                        topCircle = 545;
                        leftCircle = 660;
                    } else if (i < 18) {
                        topCircle = 550;
                        leftCircle = leftCircle - 110;
                    } else if (i == 18) {
                        topCircle = 545;
                        leftCircle = 10;
                    } else if (i < 24) {
                        topCircle = topCircle - 92;
                        leftCircle = 5;
                    } else {
                        return
                    }
                    circleList.push({ topCircle: topCircle, leftCircle: leftCircle });
                }
                self.setData({
                    circleList: circleList
                })  
                //圆点闪烁
                bout = setInterval(function () {
                    if (self.data.colorCircleFirst == '#FFFFFF') {
                        self.setData({
                            colorCircleFirst: '#F12416',
                            colorCircleSecond: '#FFFFFF',
                        })
                    } else {
                        self.setData({
                            colorCircleFirst: '#FFFFFF',
                            colorCircleSecond: '#F12416',
                        })
                    }
                }, 900)  //设置圆点闪烁的效果
                self.pond_animation();
            }
        });
    },

    //开始抽奖
    startGame: function () {
        var self = this;
        if (self.data.isRunning) return

        var title = '';
        if(self.data.oppty == 0){
            title = '抽奖机会不足';
        }
        if(!self.data.integral) {
            title = '积分不足';
        }
        if(!self.data.time) {
            title = '活动未开始或已经结束';
        }

        if(title){
            getApp().core.showModal({
                title: '很抱歉',
                content: title,
                showCancel: false,
                success: function (res) {
                    if (res.confirm) {
                        self.setData({
                            isRunning: false
                        })
                    }
                }
            })
            return;
        }

        clearInterval(p_animation);
        animation.translate(0,0).step();     
        self.setData({
            animationData: animation.export(),
            isRunning: true,
            lottert:0,
        })

        //循环
        var indexSelect = self.data.indexSelect;
        var i = 0;
        var list = self.data.awardList;

        var timer = setInterval(function () {
            indexSelect++;
            indexSelect = indexSelect % 8;
            i += 30;
            self.setData({
                indexSelect: indexSelect
            })
            if(self.data.lottert > 0 && indexSelect +1 == self.data.lottert){
                clearInterval(timer);
                self.pond_animation();
                if(list[indexSelect].type==5){
                    var prize = 1;
                }else{
                    var prize = 2;
                }
                self.setData({
                    isRunning: false,
                    name:list[indexSelect].name,
                    num:list[indexSelect].id,
                    prize:prize
                })       
            }
        }, (200 + i))


        getApp().request({
            url: getApp().api.pond.lottery,
            success: function (res) {
                if(res.code==1){
                    clearInterval(timer);
                    getApp().core.showModal({
                        title: '很抱歉',
                        content: res.msg ? res.msg : '网络错误',
                        showCancel: false,
                        success: function (res) {
                            if (res.confirm) {
                                _this.setData({
                                    isRunning: false
                                })
                            }
                        }
                    })
                    self.pond_animation();
                    return;
                }

                if (res.msg =='积分不足'){
                    self.setData({
                        integral:false
                    })
                }

                var id = res.data.id;      
                list.forEach(function(item,index,array){
                    if(item.id==id){
                        setTimeout(function(){
                            self.setData({
                                lottert:index + 1,
                                oppty:res.data.oppty
                            })
                        },2000);
                    }
                });
            }
        });
    },

    pondClose:function(){
        this.setData({
            prize:false,
        })
    },

    pond_animation:function(){
        var self = this;
        animation = getApp().core.createAnimation({
            duration: 500,
            timingFunction: "step-start",
            delay: 0,
            transformOrigin: "50% 50%",
        });

        var sentinel = true;
        p_animation = setInterval(function () {
            if(sentinel){
                animation.translate(0,0).step();
                sentinel = false;       
            }else{
                animation.translate(0,-3).step();
                sentinel = true;
            }
            self.setData({
                animationData: animation.export(),
            })
        }, 900)
    },

    /**
    * 生命周期函数--监听页面隐藏
    */
    onHide: function () {
        getApp().page.onHide(this);
        clearInterval(bout);
        clearInterval(p_animation);
    },

    /**
    * 用户点击右上角分享
    */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
        this.setData({
          share_modal_active: false
        });
        var user_info = getApp().getUser();
        var res = {
            path: "/pond/pond/pond?user_id=" + user_info.id,
            title: this.data.title?this.data.title:'九宫格抽奖',
        };
        setTimeout(function () {
          return res;
        }, 500);
    },

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


    /**
    *  海报
    */
    getGoodsQrcode: function() {
        var self = this;
        self.setData({
            qrcode_active: "active",
            share_modal_active: "",
        });
        if (self.data.goods_qrcode) return true

        getApp().request({
            url: getApp().api.pond.qrcode,
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
    
    goodsQrcodeClose: function() {
        var self = this;
        self.setData({
            goods_qrcode_active: "",
            no_scroll: false,
        });
    },
})
