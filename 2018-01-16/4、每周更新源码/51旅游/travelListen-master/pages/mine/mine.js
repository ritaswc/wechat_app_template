// 获取应用实例
var app = getApp();
// 注册页面
Page({
    data:{
        userInfo:{},
        menuList:[
            {
                list:[
                    {
                        name:'下载中心',
                        page:'down-center/down-center',
                        icon:'../../images/down_center_icon.png',
                        arrow:'../../images/ic_arrow_right.png'
                    },
                    {
                        name:'我的订单',
                        page:'my-order/my-order',
                        icon:'../../images/myorder_icon.png',
                        arrow:'../../images/ic_arrow_right.png'
                    },
                    {
                        name:'购物车',
                        page:'shopping-car/shopping-car',
                        icon:'../../images/icon_shoppingcart_blue.png',
                        arrow:'../../images/ic_arrow_right.png'
                    },
                    {
                        name:'我的钱包',
                        page:'my-voucher/my-voucher',
                        icon:'../../images/voucher_icon.png',
                        arrow:'../../images/ic_arrow_right.png'
                    }
                ]
            }
        ]
        
    },
    onLoad:function(){
        var that = this;
        // 调用应用实例的方法获取全局数据
        app.getUserInfo(function(userInfo){
            // 更新数据
            that.setData({
                userInfo:userInfo
            })
        });
    }
})