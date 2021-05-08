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

        if (typeof self.goods_recommend === 'undefined') {
            self.goods_recommend = function (e) {
                _this.goods_recommend(e);
            }
        }

    },

    goods_recommend: function(args) {
        var self = this.currentPage;
        self.setData({
            is_loading: true,
        });

        var p = self.data.page || 2;
        getApp().request({
            url: getApp().api.default.goods_recommend,
            data: {
                goods_id: args.goods_id,
                page: p,
            },
            success: function(res) {
                if (res.code == 0) {
                    if (args.reload) {
                        var goods_list = res.data.list;
                    };
                    if (args.loadmore) {
                        var goods_list = self.data.goods_list.concat(res.data.list);
                    };
                    self.data.drop = true;
                    self.setData({
                        goods_list: goods_list
                    })
                    self.setData({
                        page: (p + 1)
                    });
                };

            },
            complete: function() {
                self.setData({
                    is_loading: false,
                });
            }
        });
    },
}