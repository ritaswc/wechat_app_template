//获取应用实例
var app = getApp();
var network_util = require('../../utils/network_util.js');
Page({
    data: {
        navLeftItems: [],
        navRightItems: [],
        curNav: 0,
		curIndex: 0
    },
    onLoad: function() {
        var that = this;
        var url = app.globalData.apiUrl + 'mall_commodity/parentCategory';
        //parentCateList
        network_util._get(url,
            function(res){
                that.setData({
                    navLeftItems: res.data.data.categoryList
                });
                var url = app.globalData.apiUrl + 'mall_commodity/childCategory?parentId=' + res.data.data.categoryList[0].id;
                //subCateList
                network_util._get(url,
                    function(res){
                        that.setData({
                            navRightItems: res.data.data.categoryList
                        })
                    },function(res){
                        console.log(res);
                    }
                );
            },function(res){
                console.log(res);
            }
        );
    },

    //事件处理函数
    switchRightTab: function(e) {
        var that = this
        var id = e.target.dataset.id;
		var	index = parseInt(e.target.dataset.index);
		this.setData({
			curNav: index,
			curIndex: id
		})
        var url = app.globalData.apiUrl + 'mall_commodity/childCategory?parentId=' + id;
        network_util._get(url,
            function(res){
                that.setData({
                    navRightItems: res.data.data.categoryList
                })
            },function(res){
                console.log(res);
            }
        );
    }
})