if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        url: "",
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function(options) {
        getApp().page.onLoad(this, options);
        if (!getApp().core.canIUse("web-view")) {
            getApp().core.showModal({
                title: "提示",
                content: "您的版本过低，无法打开本页面，请升级至最新版。",
                showCancel: false,
                success: function(res) {
                    if (res.confirm) {
                        getApp().core.navigateBack({
                            delta: 1,
                        });
                    }
                }
            });
            return;
        }
        let url = decodeURIComponent(options.url)
        let arr = url.split('?');
        let uri = arr[0];
        let param = arr[1] ? arr[1].split('&') : '';
        let self = this;
        if (param && self.data.__user_info) {
            url = uri;
            for (let i in param) {
                if (param[i] == 'visiter_id=') {
                    param[i] += self.data.__user_info.access_token;
                }
                if (param[i] == 'visiter_name=') {
                    param[i] += encodeURIComponent(self.data.__user_info.nickname);
                }
                if (param[i] == 'avatar=') {
                    param[i] += encodeURIComponent(self.data.__user_info.avatar_url);
                }
            }
            let pages = getCurrentPages();
            if (pages.length > 2) {
                let page = pages[pages.length - 2];
                let goods = '';
                let product = '';
                if (page.data.goods) {
                    goods = page.data.goods;
                    product = {
                        pid: goods.id,
                        title: goods.name,
                        img: goods.cover_pic,
                        info: '',
                        price: "￥" + goods.price,
                        url: ""
                    };
                }
                if (page.route == 'pages/integral-mall/goods-info/index') {
                    product.price = goods.integral + "积分 + ￥" + goods.price;
                }
                if (page.route == 'step/goods/goods') {
                    product.price = goods.price;
                }
                if (product) {
                    param.push("product=" + encodeURIComponent(JSON.stringify(product)));
                }
            }
            url = uri + '?' + param.join('&');
            console.log(url);
        }
        this.setData({
            url: url,
        });
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function(options) {
        getApp().page.onReady(this);

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function(options) {
        getApp().page.onShow(this);

    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function(options) {
        getApp().page.onHide(this);

    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function(options) {
        getApp().page.onUnload(this);

    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function(options) {
        getApp().page.onShareAppMessage(this);
        return {
            path: 'pages/web/web?url=' + encodeURIComponent(options.webViewUrl)
        };
    }
});