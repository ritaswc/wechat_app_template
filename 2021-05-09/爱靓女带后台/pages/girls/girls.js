//index.js
//获取应用实例
var app = getApp();

var SystemInfo = wx.getSystemInfoSync()
Page({
    data: {
        list: [],
        page: 1,
        hasMore: true,
        loading: false,
        currentType: 0,
        windowHeight: SystemInfo.windowHeight,
        typeMap: {
            0: '全部',
            1: '清纯美女',
            2: '唯美写真',
            3: '性感美女',
            4: '明星美女',
            5: '丝袜美女',
            6: '美女模特'
        }
    },
    //事件处理函数
    bindViewTap: function() {
        wx.navigateTo({
            url: '../logs/logs'
        })
    },
    previewImage(event) {
        var imgs = event.currentTarget.dataset.imgs;
        var currentUrl = event.currentTarget.dataset.src;
        wx.previewImage({
            current: currentUrl, // 当前显示图片的http链接
            urls: imgs // 需要预览的图片http链接列表
        });
    },
    onLoad: function() {
        console.log('onLoad')
        var that = this
            //调用应用实例的方法获取全局数据
        app.getUserInfo(function(userInfo) {
            //更新数据
            that.setData({
                userInfo: userInfo
            })
        });
        this.loadData();
    },
    loadData() {
        var that = this;
        this.data.loading = true;
        wx.request({
            url: 'https://xxxx.com/allgirls.php?',
            data: {
                p: this.data.page,
                type: this.data.currentType
            },
            method: "GET",
            header: {
                'content-type': 'application/json'
            },
            success: function(res) {
                var list = res.data.girls;
                if (list.length === 0) {
                    that.hasMore = false;
                    return;
                }

                list.forEach(function(item) {
                    item.imgs = item.content.split(',');
                    var ret = [];
                    item.imgs.forEach(function(item) {
                        if (/^http/i.test(item)) {
                            ret.push(that.replaceDomain(item));
                        }
                    });
                    item.imgs = ret;
                    item.image = that.replaceDomain(item.image);
                    that.data.list.push(item);
                });

                that.setData({
                    list: that.data.list
                });
                that.data.page++;
                that.data.loading = false;
            }
        });
    },
    changeType(event) {
        this.setData({
            currentType: event.target.dataset.type,
            page: 1,
            list: []
        });
        this.loadData();
    },

    replaceDomain(imgUrl) {
        return imgUrl.replace('//ocnt0imhl.bkt.clouddn.com', '//file.13384.com')
    },
    loadMore() {
        if (!this.data.hasMore || this.data.loading) return;
        this.loadData();
    },
    onShareAppMessage: function() {
        return {
            title: '爱靓女，看美女，应有尽有',
            path: '/pages/girls/girls',
            success: function(res) {
                // 分享成功
            },
            fail: function(res) {
                // 分享失败
            }
        }
    }
})