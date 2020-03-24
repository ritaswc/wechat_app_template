if (typeof wx === 'undefined') var wx = getApp().core;
Page({
    data: {
        backgrop:['navbar-item-active'],
        navbarArray: [],
        navbarShowIndexArray: 0,
        navigation:false,
        windowWidth: 375,
        scrollNavbarLeft: 0,
        currentChannelIndex: 0,
        articlesHide: false,
    },

    onLoad: function(options) {
        getApp().page.onLoad(this, options);

        var self = this;
        var type = options.type;
        if(type!==undefined && type){
            self.setData({
                typeid: type,
            });
        }; 

        self.loadTopicList({
            page: 1,
            reload: true,
        });

        getApp().core.getSystemInfo({
            success: (res) => {
                self.setData({
                    windowWidth: res.windowWidth
                });
            }
        });
    },

    loadTopicList: function (args) {
        var self = this;
        if (self.data.is_loading) return;
        if (args.loadmore && !self.data.is_more) return;

        self.setData({
            is_loading: true,
        });
        getApp().request({
            url: getApp().api.default.topic_type,
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        navbarArray: res.data.list,
                        navbarShowIndexArray:Array.from(Array(res.data.list.length).keys()),
                        navigation: res.data.list!='',
                    });
                };

                getApp().request({
                    url: getApp().api.default.topic_list,
                    data: {
                        page: args.page,
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            if(self.data.typeid !== undefined){
                                
                                var offsetLeft = 0;
                                for(var i=0;i<self.data.navbarArray.length;i++){
                                    offsetLeft +=66;
                                    if(self.data.navbarArray[i].id==self.data.typeid){
                                        break;
                                    }
                                }
                                self.setData({
                                    scrollNavbarLeft:offsetLeft
                                });
                                self.switchChannel(parseInt(self.data.typeid));
                                self.sortTopic({
                                    page: 1,
                                    type: self.data.typeid,
                                    reload: true,
                                });

                            }else{
                                if (args.reload) {
                                    self.setData({
                                        list: res.data.list,
                                        page: args.page,
                                        is_more: res.data.list.length > 0
                                    });
                                }
                                if (args.loadmore) {
                                    self.setData({
                                        list: self.data.list.concat(res.data.list),
                                        page: args.page,
                                        is_more: res.data.list.length > 0
                                    });
                                }

                            }
                        }
                    },
                    complete: function () {
                        self.setData({
                            is_loading: false,
                        });
                    }
                });
            },
        });
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        getApp().page.onShow(this);
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function () {
        getApp().page.onPullDownRefresh(this);

        let currentChannelIndex = this.data.currentChannelIndex;
        this.switchChannel(parseInt(currentChannelIndex));
        this.sortTopic({
            page: 1,
            type: parseInt(currentChannelIndex),
            reload: true,
        });
        getApp().core.stopPullDownRefresh();
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function () {
        getApp().page.onReachBottom(this);

        let currentChannelIndex = this.data.currentChannelIndex;
        this.switchChannel(parseInt(currentChannelIndex));
        this.sortTopic({
            page: this.data.page + 1,
            type: parseInt(currentChannelIndex),
            loadmore: true,
        });
    },

    /**
     *菜单切换事件的处理函数
     */
    onTapNavbar: function(e) {
        var self = this;

        if (typeof my === 'undefined'){
            var offsetLeft = e.currentTarget.offsetLeft;
            self.setData({
                scrollNavbarLeft:offsetLeft - 85
            });
        }else{
            var nav = self.data.navbarArray;
            var sentinel = true;
            nav.forEach(function (item, index, array) {
                if (e.currentTarget.id == item.id) {
                    sentinel = false;
                    if (index >= 1) { 
                        self.setData({
                            toView: nav[index - 1].id
                        })
                    } else {
                        self.setData({
                            toView: - 1
                        })
                    }
                }
            });
            if (sentinel) {
                self.setData({
                    toView: '0'
                })
            }
        }
        
        getApp().core.showLoading({
          title: "正在加载",
          mask: true,
        });

        //样式
        self.switchChannel(parseInt(e.currentTarget.id));

        self.sortTopic({
            page: 1,
            type: e.currentTarget.id,
            reload: true,
        });
    },

    /*
     * 查询专题分类下专题
     */
    sortTopic: function(args){
        var self = this;
        getApp().request({
            url: getApp().api.default.topic_list,
            data: args,
            success: function (res) {
                if (res.code == 0) {
                    if (args.reload) {
                         self.setData({
                             list: res.data.list,
                             page: args.page,
                             is_more: res.data.list.length > 0
                         });
                    }
                    if (args.loadmore) {
                        self.setData({
                            list: self.data.list.concat(res.data.list),
                            page: args.page,
                            is_more: res.data.list.length > 0
                        });
                    }
                    getApp().core.hideLoading();
                }
            },
        });
    },

    switchChannel: function(targetChannelIndex) {
        let navbarArray = this.data.navbarArray;
        var backgrop = new Array();
        if(targetChannelIndex==-1){
            backgrop[1] = 'navbar-item-active';
        }else if(targetChannelIndex==0){
            backgrop[0] = 'navbar-item-active';
        }
     
        navbarArray.forEach((item, index, array) => {
            item.type = '';
            if (item['id'] == targetChannelIndex) {
                item.type = 'navbar-item-active';
            }
        }); 
        this.setData({
            navbarArray: navbarArray,
            currentChannelIndex: targetChannelIndex,
            backgrop:backgrop,
        });
    },
    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function () {
        getApp().page.onShareAppMessage(this);
        var self = this;
        var res = {
            path: "/pages/topic-list/topic-list?user_id=" + self.data.__user_info.id + "&type=" + (self.data.typeid ? self.data.typeid : ''),
            success: function (e) { },
        };
        console.log(res.path);
        return res;
    },
});