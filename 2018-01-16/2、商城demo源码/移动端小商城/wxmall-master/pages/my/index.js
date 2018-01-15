var app = getApp()
Page( {
    data: {
        userInfo: {},
        userListInfo: [{
            text: '头像',
            key: 'avatar',
            avatar: true
        }, {
            text: '昵称',
            key: 'nickname',
            hastext: true
        }, {
            text: '手机号码',
            key: 'loginname',
            hastext: true
        }, {
            text: '二维码',
            qrcode: true
        }, {
            text: '性别',
            key: 'sex',
            hastext: true
        }, {
            text: '地区',
            hastext: true
        }, {
            text: '地址',
            hastext: true
        }, {
            text: '个性签名',
            hastext: true
        }, {
            text: '实名认证'
        }, {
            text: '收货地址管理'
        }]
    },

    onLoad: function() {
        var that = this
        //调用应用实例的方法获取全局数据
        app.getUserInfo( function( userInfo ) {
            //更新数据
            that.setData( {
                userInfo: userInfo
            })
        })
    }
})