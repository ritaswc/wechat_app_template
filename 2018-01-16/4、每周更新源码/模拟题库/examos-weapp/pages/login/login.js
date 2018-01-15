var app = getApp();
Page({
    data: {
        version: '',
        tips: '',
		pageType: 0, //0为登陆，1为注册，2为关于        
    },
    onReady: function(){
        var that = this;
        wx.request({
            url: app.url.host + 'apis/v2/', 
            success: function(res) {
                that.setData({
                    version: res.data.version
                });
            }
        });
    },
    login: function(e){
        console.log(e.detail.value);
        var that = this;
        that.data.tips = '登录中';
        wx.request({
            url: app.url.host + app.url.logInUrl, 
            method: 'POST',
            header: {
            //默认content-type为json，登陆时不允许
                'content-type': 'application/x-www-form-urlencoded'
            },
            data: e.detail.value,
            success: function(res) {
                if(res.statusCode != '200') {
                    console.log(res);
                    that.setData({tips: res.data.Message});
                } else {
                    that.setData({tips: '登陆成功'});
                    wx.setStorageSync('Authorization',app.bearer+' '+res.data.token);
                    wx.switchTab({url: '../index'})
                }
            }
        }); 
    },
    register: function(e){
        console.log(e.detail.value);
        var rData = e.detail.value;
        var that = this;
        that.setData({tips: '请稍后'});
        if(rData.password != rData.rePassword) {
            that.setData({tips: '两次密码输入不一样，请检查'});
            return;
        } else {
            wx.request({
                url: app.url.host + app.url.registerUrl, 
                method: 'POST',
                data: {
                    username: rData.username,
                    email: rData.email,
                    password: rData.password                    
                },
                header: {
                    'content-type': 'application/x-www-form-urlencoded'
                },
                success: function(res) {
                    var data = res.data;
                    if(res.statusCode != '200') {
                        console.log(res);
                        that.setData({tips: data.Message});
                    } else {
                        that.setData({tips: '注册成功，正在登陆'});
                        wx.request({
                            url: app.url.host + app.url.logInUrl, 
                            method: 'POST',
                            header: {
                            //默认content-type为json，登陆时不允许
                                'content-type': 'application/x-www-form-urlencoded'
                            },
                            data: {
                                username: rData.username,
                                password: rData.password 
                            },
                            success: function(res) {
                                if(res.statusCode == '200') {
                                    that.setData({tips: '登陆成功'});
                                    wx.setStorageSync('Authorization',app.bearer+' '+res.data.token);
                                    wx.switchTab({url: '../index'})
                                }
                            }
                        });                         
                    }
                }
            }); 
        }

    },    
    turnPage: function(e){
        console.log(e.currentTarget.dataset.page);
        this.setData({pageType:e.currentTarget.dataset.page});
    }
});