if (typeof wx === 'undefined') var wx = getApp().core;
function setOnShowScene(scene) {
    if (!getApp().onShowData)
        getApp().onShowData = {};
    getApp().onShowData['scene'] = scene;
}

Page({

    /**
    * 页面的初始数据
    */
    data: {
        list:'',
    },

    /**
    * 生命周期函数--监听页面加载
    */
    onLoad: function (options) {
        getApp().page.onLoad(this, options);

        var self = this;
        self.setData({
            my:typeof(my) !== 'undefined',
        })

        getApp().core.showLoading({
            title: '加载中',
        })
        getApp().request({
            url: getApp().api.user.member,
            method: 'POST',
            success: function (res) {
                getApp().core.hideLoading();
                if (res.code == 0) {
                    self.setData(res.data);
                    self.setData({current_key:0});
                    if(res.data.list){
                        self.setData({buy_price:res.data.list[0].price});
                    }
                }
            }
        });
    },

    showDialogBtn: function() {
        this.setData({
            showModal: true
        })
    },

    /**
    * 弹出框蒙层截断touchmove事件
    */
    preventTouchMove: function () { 
    },

    /**
    * 隐藏模态对话框
    */
    hideModal: function () {
        this.setData({
            showModal: false
        });
    },

    /**
    * 对话框取消按钮点击事件
    */
    onCancel: function () {
        this.hideModal();
    },


    pay: function(e) {
        var key = e.currentTarget.dataset.key;
        var level_id = this.data.list[key].id;
        var pay_type = e.currentTarget.dataset.payment;

        this.hideModal();

        getApp().request({
            url: getApp().api.user.submit_member,
            data:{level_id:level_id,pay_type:pay_type},
            method: 'POST',
            success: function (res) {
                if (res.code == 0) {
                    setTimeout(function () {
                        getApp().core.hideLoading();
                    }, 1000);

                    if(pay_type == "WECHAT_PAY"){
                        setOnShowScene('pay');
                        getApp().core.requestPayment({
                            _res: res,
                            timeStamp: res.data.timeStamp,
                            nonceStr: res.data.nonceStr,
                            package: res.data.package,
                            signType: res.data.signType,
                            paySign: res.data.paySign,
                            complete: function (e) {
                                if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") {
                                    getApp().core.showModal({
                                        title: "提示",
                                        content: "订单尚未支付",
                                        showCancel: false,
                                        confirmText: "确认",
                                    });
                                    return;
                                }
                                if (e.errMsg == "requestPayment:ok") {
                                    getApp().core.showModal({
                                        title: "提示",
                                        content: "充值成功",
                                        showCancel: false,
                                        confirmText: "确认",
                                        success: function (res) {
                                            getApp().core.navigateBack({
                                                delta: 1
                                            })
                                        }
                                    });
                                }
                            },
                        });
                        return;
                    }

                    if (pay_type == 'BALANCE_PAY') {
                        getApp().core.showModal({
                            title: "提示",
                            content: "充值成功",
                            showCancel: false,
                            confirmText: "确认",
                            success: function (res) {
                                getApp().core.navigateBack({
                                    delta: 1
                                })
                            }
                        });
                    }

                } else {
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false
                    });
                    getApp().core.hideLoading();
                }
            }
        });
    },

    /**
    * 
    */
    changeTabs:function(e){
        if(typeof my === 'undefined'){
            // weixin
            var current_id = e.detail.currentItemId;
        }else{
            // zhifubao
            var current_id = this.data.list[e.detail.current].id;
        }
        var current_key = e.detail.current;
        var buy_price = parseFloat(this.data.list[0].price);

        var list = this.data.list;
        for(var i = 0;i < current_key;i++){
            buy_price += parseFloat(list[i+1].price);
        }

        this.setData({
            current_id:current_id,
            current_key:current_key,
            buy_price: parseFloat(buy_price),
        })
    },
    det:function(e){
        var ids = e.currentTarget.dataset.index;
        var idxs = e.currentTarget.dataset.idxs;
        if(ids==this.data.ids){
            this.setData({
                ids:-1,
                cons:false,
                idx:idxs,
            });
            return;
        }
        var content = e.currentTarget.dataset.content;
        this.setData({
            ids:ids,
            cons:true,
            idx:idxs,
            content:content
        })

    }
})