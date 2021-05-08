if (typeof wx === 'undefined') var wx = getApp().core;
// pages/pt/search/search.js


var pageNum = 1;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        history_show : false,
        search_val:'',
        list: [],
        history_info:[],
        show_loading_bar:false,
        emptyGoods:false,
        newSearch:true,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) { getApp().page.onLoad(this, options);
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function (options) { getApp().page.onReady(this);
    
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function (options) { getApp().page.onShow(this);
        var self = this;
        getApp().core.getStorage({
            key: 'history_info',
            success: function (res) {
                if(res.data.length > 0){
                    self.setData({
                        history_info: res.data,
                        history_show: true,
                    });
                }
            }
        });
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function (options) { getApp().page.onHide(this);
    
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function (options) { getApp().page.onUnload(this);
    
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function (options) { getApp().page.onPullDownRefresh(this);
    
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function (options) { getApp().page.onReachBottom(this);
        var self = this;
        if (self.data.emptyGoods){
            return;
        }
        if (self.data.page_count <= pageNum){
            self.setData({
                emptyGoods:true,
            })
        }
        pageNum++;
        self.getSearchGoods();
    },

    /**
     * 搜索
     */
    toSearch:function (e){
        var value = e.detail.value;
        var self = this;
        if (!value){
            return;
        }
        var history_info = self.data.history_info;

        history_info.unshift(value);
        for (var i in history_info) {
            if (history_info.length <= 20)
                break;
            history_info.splice(i, 1);
        }
        getApp().core.setStorageSync(getApp().const.HISTORY_INFO, history_info);
        self.setData({
            history_info: history_info,
            history_show:false,
            keyword:value,
            list:[],
        });

        self.getSearchGoods();
    },
    /**
     * 取消
     */
    cancelSearchValue:function(e){
        getApp().core.navigateBack({
            delta: 1,
        });
    },
    /**
     * 光标聚集input
     */
    newSearch:function(e){
        var self = this;
        var history_show = false;
        if (self.data.history_info.length > 0){
            history_show = true;
        }
        pageNum = 1;
        self.setData({
            history_show: history_show,
            list:[],
            newSearch:[],
            emptyGoods:false,
        });
    },
    /**
     * 清除历史搜索
     */
    clearHistoryInfo:function(e){
        var self = this;
        var history_info = [];
        getApp().core.setStorageSync(getApp().const.HISTORY_INFO, history_info);
        self.setData({
            history_info: history_info,
            history_show: false,
        });
    },
    /**
     * 请求接口获取搜索结果
     */
    getSearchGoods:function(){
        var self = this;
        var keyword = self.data.keyword;
        if(!keyword){
            return;
        }
        self.setData({
            show_loading_bar: true,
        });
        getApp().request({
            url: getApp().api.group.search,
            data: {
                keyword: keyword,
                page: pageNum,
            },
            success: function (res) {
                if (res.code == 0) {
                    if (self.data.newSearch){
                        var list = res.data.list;
                    }else{
                        var list = self.data.list.concat(res.data.list);
                    }
                    self.setData({
                        list: list,
                        page_count: res.data.page_count,
                        emptyGoods: true,
                        show_loading_bar:false,
                    });
                    if (res.data.page_count>pageNum){
                        self.setData({
                            newSearch:false,
                            emptyGoods:false,
                        })
                    };
                }
            },
            complete: function () {
            }
        });
    },
    /**
     * 历史记录点击
     */
    historyItem:function(e){
        var keyword = e.currentTarget.dataset.keyword;
        var self = this;
        self.setData({
            keyword: keyword,
            history_show:false,
        });
        self.getSearchGoods();
    }

})