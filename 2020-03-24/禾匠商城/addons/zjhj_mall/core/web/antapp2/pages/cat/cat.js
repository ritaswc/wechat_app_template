if (typeof wx === 'undefined') var wx = getApp().core;
var is_no_more = false; 
var is_loading_more = false;
Page({

    /**
     * 页面的初始数据d
     */
    data: {
        cat_list: [],
        sub_cat_list_scroll_top: 0,
        scrollLeft: 0,
        page: 1,
        cat_style:0,
        height:0,
        catheight:120,
    }, 

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        var self = this;
        getApp().page.onLoad(self, options);
        var store = getApp().core.getStorageSync(getApp().const.STORE);
        var cat_id = options.cat_id;

        if(cat_id !== undefined && cat_id){
            self.data.cat_style = store.cat_style = -1;        
            getApp().core.showLoading({
                title: "正在加载",
                mask: true,
            });
            self.childrenCat(cat_id);                     
        };
        self.setData({
            store: store,
        });
    },

    onShow: function () {
        getApp().page.onShow(this);

        getApp().core.hideLoading();
        if(this.data.cat_style!==-1){
            this.loadData();
        }
    },

    loadData: function (options) {
        // 返回上一步  5 4
        var self = this;
        var store = getApp().core.getStorageSync(getApp().const.STORE);
        if(self.data.cat_list !='' && (store.cat_style==5 || store.cat_style==4 || store.cat_style==2) ){
            self.setData({
                cat_list: self.data.cat_list,
                current_cat: self.data.current_cat,
            });
            return;
        }

        var cat_list = getApp().core.getStorageSync(getApp().const.CAT_LIST);
        if (cat_list) {
            self.setData({
                cat_list: cat_list,
                current_cat: null,
            });
        }
        getApp().request({
            url: getApp().api.default.cat_list,
            success: function (res) {
                if(res.code == 0){
                    self.data.cat_list = res.data.list;
                    //初始化 5
                    if(store.cat_style===5){
                        self.goodsAll({'currentTarget':{'dataset':{'index':0}}});
                    };

                    if(store.cat_style===4  || store.cat_style===2){
                        self.catItemClick({'currentTarget':{'dataset':{'index':0}}});   
                    };

                    if(store.cat_style===1  || store.cat_style===3){
                        self.setData({
                            cat_list: res.data.list,
                            current_cat: null,
                        });
                        getApp().core.setStorageSync(getApp().const.CAT_LIST, res.data.list);
                    };
                };
            },
            complete: function () {
                getApp().core.stopPullDownRefresh();
            }
        });
    },

    childrenCat:function(cat_id){
        var self = this;

        is_no_more = false;
        var p = self.data.page || 2
        getApp().request({
            url: getApp().api.default.cat_list,
            success: function (res) {
                if (res.code == 0) {
                    var sentinel = true;
                    for(var i in res.data.list){
                        if(res.data.list[i].id == cat_id){
                            sentinel = false;
                            self.data.current_cat = res.data.list[i];
                            if(res.data.list[i].list.length > 0){
                                self.setData({
                                    catheight:100,
                                })
                                self.firstcat({'currentTarget':{'dataset':{'index':0}}});
                            }else{
                                self.firstcat({'currentTarget':{'dataset':{'index':0}}},false);
                            }
                        };
                        for(var c in res.data.list[i].list){
                            if(res.data.list[i].list[c].id == cat_id){
                                sentinel = false;
                                self.data.current_cat = res.data.list[i]; 
                                self.goodsItem({'currentTarget':{'dataset':{'index':c}}},false);
                            }
                        }
                    };

                    if(sentinel){
                        self.setData({
                            show_no_data_tip:true,
                        });
                    };
                };
            },
            complete: function () {
                getApp().core.stopPullDownRefresh();
                getApp().core.createSelectorQuery().select('#cat').boundingClientRect().exec((ret) => {
                    self.setData({
                        height:ret[0].height,
                    })
                });
            }
        });
        return;
    },

    catItemClick: function (e) {
        var self = this;
        var index = e.currentTarget.dataset.index;
        var cat_list = self.data.cat_list;
        var scroll_top = 0;
        var add_scroll_top = true;
        var current_cat = null;
        for (var i in cat_list) {
            if (i == index) {
                cat_list[i].active = true;
                add_scroll_top = false;
                current_cat = cat_list[i];
            } else {
                cat_list[i].active = false;
            }
        }
        self.setData({
            cat_list: cat_list,
            sub_cat_list_scroll_top: scroll_top,
            current_cat: current_cat,
        });
    },
    firstcat:function(e,a = true){
        var self = this;
        var current_cat = self.data.current_cat;
        self.setData({
            page: 1,
            goods_list: [],
            show_no_data_tip: false,
            current_cat: a ? current_cat:[],
        });
        self.list(current_cat.id,2);
    },
    goodsItem:function(e,a = true){
        var self = this;
        var index = e.currentTarget.dataset.index;

        var current_cat = self.data.current_cat;
        var t = 0;
        for(var i in current_cat.list){
            if(index == i){
              current_cat.list[i].active = true;
              t = current_cat.list[i].id;
            }else{
              current_cat.list[i].active = false;
            }
        };

        self.setData({
            page: 1,
            goods_list: [],
            show_no_data_tip: false,
            current_cat: a ? current_cat:[],
        });
        self.list(t,2);
    },

    goodsAll: function(e) {
        var self = this;
        //初始化 
        var index = e.currentTarget.dataset.index;
       
        var cat_list = self.data.cat_list;
        var current_cat = null;

        for (var i in cat_list) 
        {
            if (i == index) {
                cat_list[i].active = true;
                current_cat = cat_list[i];
            } else {
                cat_list[i].active = false;
            }
        }

        self.setData({
            page: 1,
            goods_list: [],
            show_no_data_tip: false,
            cat_list: cat_list,
            current_cat: current_cat,             
        });

        if(typeof my === undefined){
            //移动效果
            var offsetLeft = e.currentTarget.offsetLeft
            var scrollLeft = self.data.scrollLeft;
                scrollLeft = offsetLeft -80;
                self.setData({
                scrollLeft:scrollLeft
            });
        }else{
           cat_list.forEach(function(item,index,array){
               if(item.id == e.currentTarget.id){
                   if(index >= 1){
                        self.setData({
                            toView:cat_list[index-1].id
                        })
                   }else{
                       self.setData({
                           toView:cat_list[index].id
                       })
                   }
               }
           });
        }

        //调用方法
        self.list(current_cat['id'],1);

        //防抖动
        getApp().core.createSelectorQuery().select('#catall').boundingClientRect().exec((ret) => {
          self.setData({
            height: ret[0].height,
          })
        });
    },

    list: function(cat_id,type){
        var self = this;
        getApp().core.showLoading({
          title: "正在加载",
          mask: true,
        });
        
        is_no_more = false;
        var p = self.data.page || 2
        getApp().request({
            url: getApp().api.default.goods_list, 
            data: { 
                cat_id: cat_id,
                page: p,
            },
            success: function (res) {
                if (res.code == 0) {
                    getApp().core.hideLoading();
                    if (res.data.list.length == 0) is_no_more = true;
                    self.setData({page: (p + 1)});
                    self.setData({goods_list: res.data.list});
                    self.setData({cat_id:cat_id});
               }
               self.setData({
                show_no_data_tip: (self.data.goods_list.length == 0),
            });
            },
            complete: function () {
                if(type==1){
                    getApp().core.createSelectorQuery().select('#catall').boundingClientRect().exec((ret) => {
                        self.setData({
                            height:ret[0].height,
                        })
                    });
                }
            }
       });
    },

    onReachBottom: function () {
        getApp().page.onReachBottom(this);

        var self = this;
        if (is_no_more)
            return;
        if(getApp().core.getStorageSync(getApp().const.STORE).cat_style==5 || self.data.cat_style==-1){
            self.loadMoreGoodsList();
        }
    },

    loadMoreGoodsList: function () {
        var self = this;
        if (is_loading_more)
            return;
        self.setData({
            show_loading_bar: true,
        });
        is_loading_more = true;
        var cat_id = self.data.cat_id || "";
        var p = self.data.page || 2;

        getApp().request({
            url: getApp().api.default.goods_list,
            data: {
                page: p,
                cat_id: cat_id,
            },
            success: function (res) {
                if (res.data.list.length == 0)
                    is_no_more = true;
                var goods_list = self.data.goods_list.concat(res.data.list);
                self.setData({
                    goods_list: goods_list,
                    page: (p + 1),
                });
            },
            complete: function () {
                is_loading_more = false;
                self.setData({
                    show_loading_bar: false,
                });
            }
        });
    },
});