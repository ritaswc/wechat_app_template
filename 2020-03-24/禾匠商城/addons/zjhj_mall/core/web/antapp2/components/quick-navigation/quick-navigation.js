if (typeof wx === 'undefined') var wx = getApp().core;
module.exports = {

    init: function(self) {
        var _this = this;
        _this.currentPage = self;
        _this.setNavi();

        if (typeof self.cutover === 'undefined') {
            self.cutover = function(e) {
                _this.cutover(e);
            }
        }

        if (typeof self.to_dial === 'undefined') {
            self.to_dial = function(e) {
                _this.to_dial(e);
            }
        }

        if (typeof self.map_goto === 'undefined') {
            self.map_goto = function(e) {
                _this.map_goto(e);
            }
        }

        if (typeof self.map_power === 'undefined') {
            self.map_power = function(e) {
                _this.map_power(e);
            }
        }
    },


    setNavi: function() {
        let self = this.currentPage;
        let arr = [
            'pages/index/index',
            'pages/book/details/details',
            'pages/pt/details/details',
            'pages/goods/goods',
        ];

        if(arr.indexOf(this.getCurrentPageUrl())!=-1){
            self.setData({
                home_icon:true,
            })
        }
        getApp().getConfig(function (config) {
            var setnavi = config.store.quick_navigation;
            if (!setnavi.home_img) {
                setnavi.home_img = "/images/quick-home.png";
            }
            self.setData({
                setnavi: setnavi
            })
        })
    },

    getCurrentPageUrl: function(){
        var pages = getCurrentPages()    //获取加载的页面
        var currentPage = pages[pages.length-1]    //获取当前页面的对象
        var url = currentPage.route    //当前页面url
        return url
    },

    to_dial: function () {
        getApp().getConfig(function (config) {
            var contact_tel = config.store.contact_tel;
            console.log(contact_tel);
            getApp().core.makePhoneCall({
                phoneNumber: contact_tel
            })
        });
    },

    map_power: function () {
        var self = this.currentPage;
        getApp().getConfig(function (config) {
            var map = config.store.option.quick_map;

            if (typeof map !== 'undefined') {
                self.map_goto(map);
            } else {
                getApp().core.getSetting({
                    success: function(res) {
                        if (!res.authSetting['scope.userLocation']) {
                            getApp().getauth({
                                content: '需要获取您的地理位置授权，请到小程序设置中打开授权！',
                                cancel: false,
                                author:'scope.userLocation',
                                success: function(res) {
                                    if (res.authSetting['scope.userLocation']) {
                                        self.map_goto(map);
                                    }
                                }
                            });
                        } else {
                            self.map_goto(map);
                        }
                    }
                })
            }

        });
    },

    map_goto: function (map){
        var self = this.currentPage;
        var lal = map.lal.split(',');
        getApp().core.openLocation({
            latitude: parseFloat(lal[0]),
            longitude: parseFloat(lal[1]),
            name: map.address,
            address: map.address,
        })

    },

    cutover: function() {
        var self = this.currentPage;
    
        var status = 0;
        self.setData({
            quick_icon: !self.data.quick_icon
        });


        let animationPlus = getApp().core.createAnimation({
            duration: 350,
            timingFunction: 'ease-out',
        })
        let animationPic = getApp().core.createAnimation({
            duration: 350,
            timingFunction: 'ease-out',
        });
        let animationcollect = getApp().core.createAnimation({
            duration: 350,
            timingFunction: 'ease-out',
        });
        
        let animationTranspond = getApp().core.createAnimation({
            duration: 350,
            timingFunction: 'ease-out',
        });
        let animationInput = getApp().core.createAnimation({
            duration: 350,
            timingFunction: 'ease-out',
        });

        let animationMapPlus = getApp().core.createAnimation({
            duration: 350,
            timingFunction: 'ease-out',
        });
        getApp().getConfig(function (config) {
        var store = self.data.store;
            var x = -50;
            if (self.data.quick_icon) {

                if (store['option'] && store['option']['wxapp'] && store['option']['wxapp']['status'] == 1) {
                    animationInput.translateY(x).opacity(1).step();
                    x = x - 50;
                }
                if (store['show_customer_service'] && store['show_customer_service'] == 1 && store['service']) {
                    animationTranspond.translateY(x).opacity(1).step();
                    x = x - 50;
                }
                if (store['option'] && store['option']['web_service_status'] == 1) {
                    animationcollect.translateY(x).opacity(1).step();
                    x = x - 50;
                }

                if (store['dial'] == 1 && store['dial_pic']) {
                    animationPic.translateY(x).opacity(1).step();
                    x = x - 50;
                }

                if (store['option'] && store['option']['quick_map']['status'] == 1) {
                    animationMapPlus.translateY(x).opacity(1).step();
                    x = x - 50;
                }


                animationPlus.translateY(x).opacity(1).step();
            } else {
                animationPlus.opacity(0).step();
                animationcollect.opacity(0).step();
                animationPic.opacity(0).step();
                animationTranspond.opacity(0).step();
                animationInput.opacity(0).step();
                animationMapPlus.opacity(0).step();
            }
            self.setData({
                animationPlus: animationPlus.export(),
                animationcollect: animationcollect.export(), 
                animationPic: animationPic.export(),
                animationTranspond: animationTranspond.export(),
                animationInput: animationInput.export(),
                animationMapPlus: animationMapPlus.export(),
            });
        });
    }

    /*quickNavigation: function () {
        var status = 0;
        this.setData({
            quick_icon: !this.data.quick_icon
        })
        var store = this.data.store;
        var animationPlus = getApp().core.createAnimation({
            duration: 300,
            timingFunction: 'ease-out',
        });

        var x = -55;
        if (!this.data.quick_icon) {
            animationPlus.translateY(x).opacity(1).step();
        } else {
            animationPlus.opacity(0).step();
        }
        this.setData({
            animationPlus: animationPlus.export(),
        });
    },*/
}