if (typeof wx === 'undefined') var wx = getApp().core;
// components/quick-navigation-components/index.js
Component({
    /**
     * 组件的属性列表
     */
    properties: {
        setnavi: {
            type: 'object',
            value: {}
        },
        __device: {
            type: 'string',
            value: ''
        },
        home_icon: {
            type: 'boolean',
            value: true
        },
        options: {
            type: 'object',
            value: {}
        },
        store: {
            type: 'object',
            value: {}
        },
        __platform: {
            type: 'string',
            value: 'wx'
        },
        __alipay_mp_config: {
            type: 'object',
            value: {}
        },
        __user_info: {
            type: 'object',
            value: {}
        },
        click_pic: {
            type: 'object',
            value: false
        },
    },

    /**
     * 组件的初始数据
     */
    data: {
        animationPlus: false,
        animationcollect: false,
        animationPic: false,
        animationTranspond: false,
        animationInput: false,
        animationMapPlus: false,
        quick_icon: false
    },
    options: {
        addGlobalClass: true,
    },

    /**
     * 组件的方法列表
     */
    methods: {
        setNavi: function () {
            let self = this;
            let arr = [
                'pages/index/index',
                'pages/book/details/details',
                'pages/pt/details/details',
                'pages/goods/goods',
            ];

            if (arr.indexOf(this.getCurrentPageUrl()) != -1) {
                self.setData({
                    home_icon: true,
                })
            }
            if (typeof my === 'undefined') {
                var setnavi = self.data.store.quick_navigation;
            } else {
                var setnavi = self.props.store.quick_navigation;
            }
            if (!setnavi.home_img) {
                setnavi.home_img = "/images/quick-home.png";
            }
            self.setData({
                setnavi: setnavi
            })
        },

        getCurrentPageUrl: function () {
            var pages = getCurrentPages()    //获取加载的页面
            var currentPage = pages[pages.length - 1]    //获取当前页面的对象
            var url = currentPage.route    //当前页面url
            return url
        },

        to_dial: function () {
            if (typeof my === 'undefined') {
                var contact_tel = this.data.store.contact_tel;
            } else {
                var contact_tel = this.props.store.contact_tel;
            }
            getApp().core.makePhoneCall({
                phoneNumber: contact_tel
            })
        },

        map_power: function () {
            var self = this;
            if (typeof my === 'undefined') {
                var map = self.data.store.option.quick_map;
            } else {
                var map = self.props.store.option.quick_map;
            }

            if (typeof my === 'undefined') {
                self.map_goto(map);
            } else {
                getApp().core.getSetting({
                    success: function (res) {
                        if (!res.authSetting['scope.userLocation']) {
                            getApp().getauth({
                                content: '需要获取您的地理位置授权，请到小程序设置中打开授权！',
                                cancel: false,
                                author: 'scope.userLocation',
                                success: function (res) {
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
        },

        map_goto: function (map) {
            var self = this;
            var lal = map.lal.split(',');
            getApp().core.openLocation({
                latitude: parseFloat(lal[0]),
                longitude: parseFloat(lal[1]),
                name: map.address,
                address: map.address,
            })

        },

        cutover: function () {
            var self = this;

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
            if (typeof my === 'undefined') {
                var store = self.data.store;
            } else {
                var store = self.props.store;
            }
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
        }
    }
})
