if (typeof wx === 'undefined') var wx = getApp().core;
module.exports = {
    currentPage: null,
    /**
     * 注意！注意！！注意！！！
     * 由于组件的通用，部分变量名称需统一，在各自引用的xxx.js文件需定义，并给对应变量赋相应的值
     * 以下变量必须定义并赋值
     * 
     * goods.pic_list  商品图片数组
     * goods.video_url 视频链接
     * 持续补充...
     */
    init: function (self) {
        var _this = this;
        _this.currentPage = self;

        if (typeof self.onGoodsImageClick === 'undefined') {
            self.onGoodsImageClick = function (e) {
                _this.onGoodsImageClick(e);
            }
        }
        if (typeof self.hide === 'undefined') {
            self.hide = function (e) {
                _this.hide(e);
            }
        }
        if (typeof self.play === 'undefined') {
            self.play = function (e) {
                _this.play(e);
            }
        }
        if (typeof self.close === 'undefined') {
            self.close = function (e) {
                _this.close(e);
            }
        }
    },

    onGoodsImageClick: function (e) {
        var self = this.currentPage;
        var urls = [];
        var index = e.currentTarget.dataset.index;
        for (var i in self.data.goods.pic_list) {
            urls.push(self.data.goods.pic_list[i]);
        }

        getApp().core.previewImage({
            urls: urls, // 需要预览的图片http链接列表
            current: urls[index],
        });
    },
    // 大图显示/隐藏
    hide: function (e) {
        var self = this.currentPage;
        if (e.detail.current == 0) {
            self.setData({
                img_hide: ""
            });
        } else {
            self.setData({
                img_hide: "hide"
            });
        }
    },

    /**
     * 视频播放
     */
    play: function (e) {
        var self = this.currentPage;
        var url = e.target.dataset.url; //获取视频链接
        self.setData({
            url: url,
            hide: '',
            show: true,
        });
        var videoContext = getApp().core.createVideoContext('video');
        videoContext.play();
    },

    /**
     * 关闭视频
     */
    close: function (e) {
        var self = this.currentPage;
        if (e.target.id == 'video') {
            return true;
        }
        self.setData({
            hide: "hide",
            show: false
        });
        var videoContext = getApp().core.createVideoContext('video');
        videoContext.pause();
    },
}