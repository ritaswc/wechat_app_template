var app = getApp()
var common  = require('../../util/util.js');
Page( {
    data: {
        hasLogin: false,
        userInfo: {
            avatarUrl: '../../image/default-head.png',
            nickName: '未登陆'
        },
        userListInfo: [ {
            icon: '../../image/iconfont-dingdan.png',
            text: '全部订单',
            isunread: true,
            unreadNum: 2,
            url: "../order/order"
        }, {
            icon: '../../image/iconfont-card.png',
            text: '地址管理',
            isunread: false,
            unreadNum: 2,
            url: "address"
        }]
    },
    onLoad: function() {
        
    },
    jumpUrl: function(e){
        var url = e.currentTarget.dataset.url;
        wx.navigateTo({
            url: url
        })
    },

})