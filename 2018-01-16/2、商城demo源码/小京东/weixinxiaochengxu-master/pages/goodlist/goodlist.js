// pages/goodlist/goodlist.js
var request = require('../../utils/https.js')
var uri = 'goods/api/goodslist' //商品列表的的uri
var app = getApp();
var id = '';
var navlist = [
    { id: " ", title: "综合", icon: "" },
    { id: "salenum", title: "销量", icon: "" },
    { id: "goodsStorePrice", title: "价格", icon: "../../images/pop_select_pray.png" },
    { id: "shaixuan", title: "筛选", icon: "../../images/list_sx.png" },
];
Page({
    data: {
        pageNo: 1,
        activeIndex: 0,
        navList: navlist,
        systemInfo: [],
        loadingHidden: false,
        list: [],
        num: 1,
        limt: 20,
        tab: ''
    },
    //切换TAB
    onTapTag: function (e) {
        var that = this;
        var tab = e.currentTarget.id;
        var index = e.currentTarget.dataset.index;
        that.setData({
            activeIndex: index,
            tab: tab,
            pageNo: 1
        })
        if (tab == 'shaixuan') {    //筛选跳转到specValue
            wx.navigateTo({
                url: '../specValue/specValue',    //加参数
                //获取specValue


            })
        } else {
            that.getData();
        }
    },
    getData: function () {
        var that = this;
        var tab = that.data.tab;
        var pageNo = that.data.pageNo;
        that.setData({ loadingHidden: false });
        if (pageNo == 1) {
            that.setData({ list: [] });
        }
        request.req(uri, {
            sortField: tab,    //搜索过滤     
            keyword: id,
            searchType: 'keywordSearch',
            pageNo: pageNo,
        }, (err, res) => {
            that.setData({
                loadingHidden: true,
                list: that.data.list.concat(res.data.data)
            });
            console.log(res); 
        })

    },
    //加载更多
    bindscrolltolower: function () {
        console.log("加载更多");
        var that = this;
        that.setData({
            pageNo: that.data.pageNo + 1
        });
        that.getData();  //pageNo++,并传到数组中
    },
    clickitem: function (e) {   //带着specId 去详情界面
        var specId = e.currentTarget.dataset.specid;
        wx.navigateTo({
            url: '../goodsDetail/goodsDetail?specId=' + specId,
        })
    },
    onLoad: function (options) {
        // 页面初始化 options为页面跳转所带来的参数
        id = options.id;
        var that = this;
        //封装https请求
        that.getData();
    },

    onReady: function () {
        // 页面渲染完成
    },
    onShow: function () {
        // 页面显示
    },
    onHide: function () {
        // 页面隐藏
    },
    onUnload: function () {
        // 页面关闭
    }
})