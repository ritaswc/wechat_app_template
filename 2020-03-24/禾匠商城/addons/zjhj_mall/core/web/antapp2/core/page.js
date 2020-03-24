/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author LuWei
 */

module.exports = {
    currentPage: null,
    currentPageOptions: {},
    //加入底部导航的页面
    navbarPages: [
        'pages/index/index',
        'pages/cat/cat',
        'pages/cart/cart',
        'pages/user/user',
        'pages/list/list',
        'pages/search/search',
        'pages/topic-list/topic-list',
        'pages/video/video-list',
        'pages/miaosha/miaosha',
        'pages/shop/shop',
        'pages/pt/index/index',
        'pages/book/index/index',
        'pages/share/index',
        'pages/quick-purchase/index/index',
        'mch/m/myshop/myshop',
        'mch/shop-list/shop-list',
        'pages/integral-mall/index/index',
        'pages/integral-mall/register/index',
        'pages/article-detail/article-detail',
        'pages/article-list/article-list',
        'pages/order/order'
    ],
    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (self, options) {
        this.currentPage = self;
        this.currentPageOptions = options;
        var _this = this;
        this.setUserInfo();
        this.setWxappImg();
        this.setStore();
        this.setParentId(options);
        this.getNavigationBarColor();
        this.setDeviceInfo();
        this.setPageClasses();
        this.setPageNavbar(null);
        this.setBarTitle();
        this.setNavi();
        if (typeof self.onSelfLoad === 'function') {
            self.onSelfLoad(options);
        }
        _this._setFormIdSubmit()
        if (typeof my !== 'undefined' && self.route != 'pages/login/login' && options) {
            if (!self.options)
                self['options'] = options;
            getApp().core.setStorageSync('last_page_options', options);
        }
        ;

        if (self.route == 'lottery/goods/goods' && options) {
            if (options.user_id) {
                var user_id = options.user_id;
                var lottery_id = options.id;

            } else if (options.scene) {
                if (isNaN(options.scene)) {
                    let scene = decodeURIComponent(options.scene);
                    if (scene) {
                        scene = getApp().helper.scene_decode(scene);
                        if (scene && scene.uid) {
                            var user_id = scene.uid;
                            var lottery_id = scene.gid;
                        }
                    }
                }
            }
            ;
            getApp().request({
                data: {
                    user_id: user_id,
                    lottery_id: lottery_id
                },
                url: getApp().api.lottery.clerk,
                success: function (res) {
                    if (res.code == 0) {

                    }
                }
            });
        }
        self.navigatorClick = function (e) {
            _this.navigatorClick(e, self);
        };
        // 设置平台标识
        self.setData({
            __platform: getApp().platform,
        });
        if (typeof self.showToast === 'undefined') {
            self.showToast = function (e) {
                _this.showToast(e);
            };
        }
        getApp().shareSendCoupon = function (self) {
            _this.shareSendCoupon(self);
        }
        if (typeof self.setTimeList === 'undefined') {
            self.setTimeList = function (e) {
                return _this.setTimeList(e);
            }
        }
        if (typeof self.showLoading === 'undefined') {
            self.showLoading = function (e) {
                _this.showLoading(e);
            }
        }
        if (typeof self.hideLoading === 'undefined') {
            self.hideLoading = function (e) {
                _this.hideLoading(e);
            }
        }
        if (typeof self.modalConfirm === 'undefined') {
            self.modalConfirm = function (e) {
                _this.modalConfirm(e);
            }
        }
        if (typeof self.modalClose === 'undefined') {
            self.modalClose = function (e) {
                _this.modalClose(e);
            }
        }
        if (typeof self.modalShow === 'undefined') {
            self.modalShow = function (e) {
                _this.modalShow(e);
            }
        }
        if (typeof self.myLogin === 'undefined') {
            self.myLogin = function () {
                _this.myLogin();
            }
        }
        if (typeof self.getUserInfo === 'undefined') {
            self.getUserInfo = function (res) {
                _this.getUserInfo(res);
            }
        }
        if (typeof self.getPhoneNumber === 'undefined') {
            self.getPhoneNumber = function (e) {
                _this.getPhoneNumber(e);
            }
        }
        if (typeof self.bindParent === 'undefined') {
            self.bindParent = function (e) {
                _this.bindParent(e);
            }
        }
        if (typeof self.closeCouponBox === 'undefined') {
            self.closeCouponBox = function (e) {
                _this.closeCouponBox(e);
            }
        }

        if (typeof self.relevanceSuccess === 'undefined') {
            self.relevanceSuccess = function (e) {
                _this.relevanceSuccess(e);
            }
        }

        if (typeof self.relevanceError === 'undefined') {
            self.relevanceError = function (e) {
                _this.relevanceError(e);
            }
        }

        if (typeof self.saveQrcode === 'undefined') {
            self.saveQrcode = function (e) {
                _this.saveQrcode(e);
            }
        }
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function (self) {
        this.currentPage = self;
    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function (self) {
        const app = getApp();
        this.currentPage = self;
        if (app.onShowData && app.onShowData.scene) {
            var sceneArr = [1045, 1046, 1058, 1067, 1084, 1091];
            if (sceneArr.indexOf(app.onShowData.scene) >= 0) {
                this.setPageNavbar(app.onShowData.scene);
            } else {
                console.log('no in array-->', app.onShowData.scene);
                console.log('the array-->', sceneArr);
            }
        }
        getApp().orderPay.init(self, getApp());
        var quickNavigation = require('../components/quick-navigation/quick-navigation.js');
        quickNavigation.init(this.currentPage);
    },

    /**
     * 生命周期函数--监听页面隐藏
     */
    onHide: function (self) {
        this.currentPage = self;
    },

    /**
     * 生命周期函数--监听页面卸载
     */
    onUnload: function (self) {
        this.currentPage = self;
    },

    /**
     * 页面相关事件处理函数--监听用户下拉动作
     */
    onPullDownRefresh: function (self) {
        this.currentPage = self;
    },

    /**
     * 页面上拉触底事件的处理函数
     */
    onReachBottom: function (self) {
        this.currentPage = self;
    },

    /**
     * 用户点击右上角分享
     */
    onShareAppMessage: function (self) {
        this.currentPage = self;
        setTimeout(function () {
            getApp().shareSendCoupon(self);
        }, 1000);
    },

    imageClick: function (e) {
        console.log('image click', e);
    },

    textClick: function (e) {
        console.log('text click', e);
    },

    tap1: function (e) {
        console.log('tap1', e);
    },

    tap2: function (e) {
        console.log('tap2', e);
    },

    formSubmit_collect: function (e) {
        let formId = e.detail.formId;
        console.log('formSubmit_collect-->', e);
    },

    setUserInfo: function () {
        var self = this.currentPage;
        var userInfo = getApp().getUser();
        if (userInfo) {
            self.setData({
                __user_info: userInfo,
            });
        }
    },

    setWxappImg: function (e) {
        var self = this.currentPage;
        getApp().getConfig(function (config) {
            self.setData({
                __wxapp_img: config.wxapp_img,
                store: config.store,
            });
        });
    },

    setStore: function (e) {
        var self = this.currentPage;
        getApp().getConfig(function (config) {
            if (config.store) {
                self.setData({
                    store: config.store,
                    __is_comment: config.store ? config.store.is_comment : 1, //全局评价开关
                    __is_sales: config.store ? config.store.is_sales : 1, //全局销量开关
                    __is_member_price: config.store ? config.store.is_member_price : 1, //全局会员价开关
                    __is_share_price: config.store ? config.store.is_share_price : 1, //全局分销价开关
                    __alipay_mp_config: config.alipay_mp_config
                });
            }
        });
    },

    setParentId: function (options) {
        var self = this.currentPage;
        var _this = this;
        if (self.route == '/pages/index/index') {
            _this.setOfficalAccount();
        }
        var parent_id = 0;
        if (options) {
            if (options.user_id) {
                parent_id = options.user_id;
            } else if (options.scene) {
                if (isNaN(options.scene)) {
                    var scene = decodeURIComponent(options.scene);
                    if (scene) {
                        scene = getApp().helper.scene_decode(scene);
                        if (scene && scene.uid) {
                            parent_id = scene.uid;
                        }
                    }
                } else {
                    if (self.route.indexOf('clerk') == -1) {
                        parent_id = options.scene;
                    }
                }
                _this.setOfficalAccount();
            }
        } else if (getApp().query !== null) {
            var query = getApp().query;
            if (typeof query.uid != 'undefined') {
                parent_id = query.uid;
            } else if (typeof query.user_id != 'undefined') {
                parent_id = query.user_id;
            }
        }
        if (parent_id && typeof parent_id != 'undefined' && parent_id > 0) {
            getApp().core.setStorageSync(getApp().const.PARENT_ID, parent_id);
            getApp().trigger.remove(getApp().trigger.events.login, 'TRY_TO_BIND_PARENT');
            getApp().trigger.add(getApp().trigger.events.login, 'TRY_TO_BIND_PARENT', function () {
                self.bindParent({
                    parent_id: parent_id,
                    condition: 0
                })
            });
        }
    },

    showToast: function (e) {
        var self = this.currentPage;
        var duration = e.duration || 2500;
        var title = e.title || '';
        var success = e.success || null;
        var fail = e.fail || null;
        var complete = e.complete || null;
        if (self._toast_timer) {
            clearTimeout(self._toast_timer);
        }
        self.setData({
            _toast: {
                title: title,
            },
        });
        self._toast_timer = setTimeout(function () {
            var _toast = self.data._toast;
            _toast.hide = true;
            self.setData({
                _toast: _toast,
            });
            if (typeof complete == 'function') {
                complete();
            }
        }, duration);
    },

    setDeviceInfo: function () {
        var self = this.currentPage;
        //iphonex=>iPhone X(GSM+CDMA)<iPhone10,3>
        var device_list = [{
            id: 'device_iphone_5',
            model: 'iPhone 5',
        },
            {
                id: 'device_iphone_x',
                model: 'iPhone X',
            },
        ];
        //设置设备信息
        var device_info = getApp().core.getSystemInfoSync();
        if (device_info.model) {
            if (device_info.model.indexOf('iPhone X') >= 0) {
                device_info.model = 'iPhone X';
            }
            for (var i in device_list) {
                if (device_list[i].model == device_info.model) {
                    self.setData({
                        __device: device_list[i].id,
                    });
                }
            }
        }
    },

    setPageNavbar: function (scene) {
        var _this = this;
        var self = this.currentPage;
        var navbar = getApp().core.getStorageSync('_navbar');
        if (navbar) {
            setNavbar(navbar);
        }
        var in_array = false;
        for (var i in _this.navbarPages) {
            if (self.route == _this.navbarPages[i]) {
                in_array = true;
                break;
            }
        }
        if (!in_array) {
            return;
        }
        getApp().request({
            url: getApp().api.default.navbar,
            success: function (res) {
                if (res.code == 0) {
                    setNavbar(res.data);
                    getApp().core.setStorageSync('_navbar', res.data);
                }
            }
        });

        function setNavbar(navbar) {
            var in_navs = false;
            for (var i in navbar.navs) {
                var url = navbar.navs[i].url;
                var route = self.route || (self.__route__ || null);
                url = navbar.navs[i].new_url;
                if (typeof navbar.navs[i].params != 'undefined' || navbar.navs[i].url == '/pages/index/index') {
                    for (var key in self.options) {
                        if (getApp().helper.inArray(key, ['scene', 'user_id', 'uid'])) {
                            continue;
                        }
                        if (key == 'page_id' && self.options[key] == -1) {
                            continue;
                        }
                        if (route.indexOf('?') == -1) {
                            route += '?';
                        } else {
                            route += '&';
                        }
                        route += key + '=' + self.options[key];
                    }
                }
                console.log(route)
                let tempRoute = route;
                if (scene == 1058 && tempRoute.indexOf('?appid=') != -1) {
                    tempRoute = tempRoute.substr(0, tempRoute.indexOf('?appid='));
                }
                if (url === "/" + tempRoute) {
                    navbar.navs[i].active = true;
                    in_navs = true;
                } else {
                    navbar.navs[i].active = false;
                }
            }
            if (!in_navs)
                return;
            self.setData({
                _navbar: navbar
            });
            _this.setPageClasses();
        }

    },

    setPageClasses: function () {
        var self = this.currentPage;
        var device = self.data.__device;
        var classes = device;
        if (self.data._navbar && self.data._navbar.navs && self.data._navbar.navs.length > 0) {
            classes += ' show_navbar';
        }
        if (classes) {
            self.setData({
                __page_classes: classes,
            });
        }
    },

    showLoading: function (e) {
        var self = self;
        self.setData({
            _loading: true
        });
    },

    hideLoading: function (e) {
        var self = this.currentPage;
        self.setData({
            _loading: false
        });
    },

    setTimeList: function (reset_time) {
        // 补零
        function fillZero(time) {
            if (time <= 0) {
                time = 0;
            }
            return time < 10 ? '0' + time : time;
        }

        var _s = '00';
        var _m = '00';
        var _h = '00';
        var _d = 0;
        var content = '';
        var content_1 = '';
        var content_ms = '';
        if (reset_time >= 86400) {
            _d = parseInt(reset_time / 86400);
            reset_time = reset_time % 86400;
            content += _d + '天';
            content_1 += _d + '天';
            content_ms += _d + '天';
        }
        if (reset_time < 86400) {
            var h = parseInt(reset_time / 3600);
            _h = fillZero(h);
            reset_time = reset_time % 3600;
            content += _h + '小时';
            content_1 += _h + ':';
            content_ms = (_d > 0 || h > 0) ? (content_ms + _h + ':') : '';
        }
        if (reset_time < 3600) {
            _m = fillZero(parseInt(reset_time / 60));
            reset_time = reset_time % 60;
            content += _m + '分';
            content_1 += _m + ':';
            content_ms += _m + ':';
        }

        if (reset_time < 60) {
            _s = fillZero(reset_time);
            content += _s + '秒';
            content_1 += _s;
            content_ms += _s;
        }
        return {
            d: _d,
            h: _h,
            m: _m,
            s: _s,
            content: content,
            content_1: content_1,
            content_ms: content_ms
        }
    },

    setBarTitle: function (e) {
        var route = this.currentPage.route;
        var list = getApp().core.getStorageSync(getApp().const.WX_BAR_TITLE);
        for (var i in list) {
            if (list[i].url === route) {
                getApp().core.setNavigationBarTitle({
                    title: list[i].title,
                })
            }
        }
    },

    getNavigationBarColor: function () {
        var app = getApp();
        var _this = this;
        app.request({
            url: app.api.default.navigation_bar_color,
            success: function (res) {
                if (res.code == 0) {
                    app.core.setStorageSync(getApp().const.NAVIGATION_BAR_COLOR, res.data);
                    _this.setNavigationBarColor();
                    if (app.navigateBarColorCall && typeof app.navigateBarColorCall == 'function') {
                        app.navigateBarColorCall(res);
                    }
                }
            }
        });
    },

    setNavigationBarColor: function () {
        var self = this.currentPage;
        var navigation_bar_color = getApp().core.getStorageSync(getApp().const.NAVIGATION_BAR_COLOR);
        if (navigation_bar_color) {
            getApp().core.setNavigationBarColor(navigation_bar_color);
            self.setData({
                _navigation_bar_color: navigation_bar_color, //底部导航颜色
            });
        }
        getApp().navigateBarColorCall = function (res) {
            getApp().core.setNavigationBarColor(res.data);
            self.setData({
                _navigation_bar_color: res.data, //底部导航颜色
            });
        }
    },

    navigatorClick: function (e, self) {
        var open_type = e.currentTarget.dataset.open_type;
        if (open_type == 'redirect') {
            return true;
        }
        if (open_type == 'wxapp') {
            return;
        }
        if (open_type == 'tel') {
            var contact_tel = e.currentTarget.dataset.tel;
            getApp().core.makePhoneCall({
                phoneNumber: contact_tel
            })
        }
        return false;

        function parseQueryString(url) {
            var reg_url = /^[^\?]+\?([\w\W]+)$/,
                reg_para = /([^&=]+)=([\w\W]*?)(&|$|#)/g,
                arr_url = reg_url.exec(url),
                ret = {};
            if (arr_url && arr_url[1]) {
                var str_para = arr_url[1],
                    result;
                while ((result = reg_para.exec(str_para)) != null) {
                    ret[result[1]] = result[2];
                }
            }
            return ret;
        }
    },
    /**
     * 分享送优惠券
     */
    shareSendCoupon: function (self) {
        var app = getApp();
        app.core.showLoading({
            mask: true,
        });
        if (!self.hideGetCoupon) {
            self.hideGetCoupon = function (e) {
                var url = e.currentTarget.dataset.url || false;
                self.setData({
                    get_coupon_list: null,
                });
                if (url) {
                    app.core.navigateTo({
                        url: url,
                    });
                }
            };
        }
        app.request({
            url: app.api.coupon.share_send,
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        get_coupon_list: res.data.list
                    });
                }
            },
            complete: function () {
                app.core.hideLoading();
            }
        });
    },

    /**
     * 分销绑定上下级关系
     */
    bindParent: function (object) {
        var app = getApp();
        if (object.parent_id == "undefined" || object.parent_id == 0)
            return;
        var user_info = app.getUser();
        var share_setting = app.core.getStorageSync(app.const.SHARE_SETTING);
        if (share_setting.level > 0) {
            var parent_id = object.parent_id;
            if (parent_id != 0) {
                app.request({
                    url: app.api.share.bind_parent,
                    data: {
                        parent_id: object.parent_id,
                        condition: object.condition
                    },
                    success: function (res) {
                        if (res.code == 0) {
                            user_info.parent = res.data
                            app.setUser(user_info);
                        }
                    }
                });
            }
        }
    },

    _setFormIdSubmit: function (e) {
        let self = this.currentPage;
        if (self._formIdSubmit) {
            return;
        }
        self._formIdSubmit = function (e) {
            let dataset = e.currentTarget.dataset;
            let form_id = e.detail.formId;
            let bind = dataset.bind || null;
            let type = dataset.type || null;
            let url = dataset.url || null;
            let appId = dataset.appId || null;

            // 保存formId
            {
                let form_id_list = getApp().core.getStorageSync(getApp().const.FORM_ID_LIST);
                if (!form_id_list || !form_id_list.length) {
                    form_id_list = [];
                }

                var oldFormId = [];
                for (var wf in form_id_list) {
                    oldFormId.push(form_id_list[wf]['form_id']);
                }
                console.log('form_id');

                //重复的formId不添加
                if ('the formId is a mock one' !== form_id && !getApp().helper.inArray(form_id, oldFormId)) {
                    form_id_list.push({
                        time: getApp().helper.time(),
                        form_id: form_id,
                    });

                    getApp().core.setStorageSync(getApp().const.FORM_ID_LIST, form_id_list);
                }
            }

            // 调用自定义事件function
            if (self[bind] && typeof self[bind] === 'function') {
                self[bind](e);
            }

            // 页面跳转
            switch (type) {
                case 'navigate':
                    if (url)
                        getApp().core.navigateTo({
                            url: url,
                        });
                    break;
                case 'redirect':
                    if (url)
                        getApp().core.redirectTo({
                            url: url,
                        });
                    break;
                case 'switchTab':
                    if (url)
                        getApp().core.switchTab({
                            url: url,
                        });
                    break;
                case 'reLaunch':
                    if (url)
                        getApp().core.reLaunch({
                            url: url,
                        });
                    break;
                case 'navigateBack':
                    if (url)
                        getApp().core.navigateBack({
                            url: url,
                        });
                    break;
                case 'wxapp':
                    if (appId)
                        getApp().core.navigateToMiniProgram({
                            url: url,
                            appId: appId,
                            path: dataset.path || ''
                        });
                    break;
                default:
                    break;
            }
        };

    },

    modalClose: function (e) {
        var self = this.currentPage;
        self.setData({
            modal_show: false
        });
        console.log('你点击了关闭按钮')
    },

    modalConfirm: function (e) {
        var self = this.currentPage;
        self.setData({
            modal_show: false
        });
        console.log('你点击了确定按钮')
    },

    modalShow: function (e) {
        var self = this.currentPage;
        self.setData({
            modal_show: true
        });
        console.log('点击会弹出弹框')
    },


    getUserInfo: function (res) {
        var _this = this;
        if (res.detail.errMsg != 'getUserInfo:ok') {
            return;
        }
        getApp().core.login({
            success: function (login_res) {
                var code = login_res.code;
                _this.unionLogin({
                    code: code,
                    user_info: res.detail.rawData,
                    encrypted_data: res.detail.encryptedData,
                    iv: res.detail.iv,
                    signature: res.detail.signature
                });
            },
            fail: function (res) {
            },
        });

    },

    //支付宝小程序登录
    myLogin: function (event) {
        var _this = this;
        if (getApp().platform !== 'my')
            return;
        if (getApp().login_complete) {
            return;
        }
        getApp().login_complete = true;
        my.getAuthCode({
            scopes: 'auth_base',
            success: function (res) {
                my.getOpenUserInfo({
                    success: function (userinfoResponse) {
                        const userinfo = JSON.parse(userinfoResponse.response);
                        if (userinfo.response && userinfo.response.code && userinfo.response.code == '10000') {
                            _this.unionLogin({
                                code: res.authCode,
                                user_info: JSON.stringify(userinfo.response),
                            });
                        }
                    }
                });
            },
            fail: function (res) {
                getApp().login_complete = false;
                getApp().core.redirectTo({
                    url: '/pages/index/index'
                });
            }
        });
    },

    unionLogin: function (data) {
        var self = this.currentPage;
        var _this = this;
        getApp().core.showLoading({
            title: "正在登录",
            mask: true,
        });
        getApp().is_login = true;
        getApp().request({
            url: getApp().api.passport.login,
            method: 'POST',
            data: data,
            success: function (res) {
                if (res.code == 0) {
                    self.setData({
                        __user_info: res.data
                    });
                    getApp().setUser(res.data);
                    getApp().core.setStorageSync(getApp().const.ACCESS_TOKEN, res.data.access_token);
                    getApp().trigger.run(getApp().trigger.events.login);
                    var store = getApp().core.getStorageSync(getApp().const.STORE);
                    if (res.data.binding || (!store.option.phone_auth) || (store.option.phone_auth && store.option.phone_auth == 0)) {
                        _this.loadRoute();
                    } else {
                        if (typeof wx === 'undefined') {
                            _this.loadRoute();
                        }
                        _this.setPhone();
                    }
                    _this.setUserInfoShowFalse();
                } else {
                    getApp().is_login = false;
                    getApp().login_complete = false;
                    getApp().core.showModal({
                        title: '提示',
                        content: res.msg,
                        showCancel: false,
                    });
                }
            },
            fail: function () {
                getApp().login_complete = false;
            },
            complete: function () {
                getApp().core.hideLoading();
            }
        });
    },


    getPhoneNumber: function (e) {
        var self = this.currentPage;
        var _this = this;
        if (e.detail.errMsg == 'getPhoneNumber:fail user deny') {
            getApp().core.showModal({
                title: '提示',
                showCancel: false,
                content: '未授权',
            })
        } else {
            getApp().core.showLoading({
                title: '授权中',
            });
            getApp().core.login({
                success: function (res) {
                    if (res.code) {
                        var code = res.code;
                        getApp().request({
                            url: getApp().api.user.user_binding,
                            method: 'POST',
                            data: {
                                iv: e.detail.iv,
                                encryptedData: e.detail.encryptedData,
                                code: code,
                            },
                            success: function (res) {
                                if (res.code == 0) {
                                    var user_info = self.data.__user_info;
                                    user_info.binding = res.data.dataObj;

                                    getApp().setUser(user_info);

                                    self.setData({
                                        PhoneNumber: res.data.dataObj,
                                        __user_info: user_info,
                                        binding: true,
                                        binding_num: res.data.dataObj
                                    });
                                    _this.loadRoute();
                                } else {
                                    getApp().core.showToast({
                                        title: '授权失败,请重试'
                                    });
                                }
                            },
                            complete: function (res) {
                                getApp().core.hideLoading();
                            }
                        });
                    } else {
                        getApp().core.showToast({
                            title: '获取用户登录态失败！' + res.errMsg,
                        });
                    }
                },
            });
        }
    },
    setUserInfoShow: function () {
        this.currentPage.setData({
            user_info_show: true
        });
    },

    setPhone: function () {
        var self = this.currentPage;
        if (typeof my === 'undefined') {
            self.setData({
                user_bind_show: true
            });
        }
    },
    setUserInfoShowFalse: function () {
        var self = this.currentPage;
        self.setData({
            user_info_show: false
        });
    },

    closeCouponBox: function (e) {
        var self = this.currentPage;
        self.setData({
            get_coupon_list: ""
        });
    },

    // 关联公众号组件加载成功
    relevanceSuccess: function (e) {
        console.log(e)
    },

    // 关联公众号组件加载失败
    relevanceError: function (e) {
        console.log(e)
    },

    setOfficalAccount: function (e) {
        var self = this.currentPage;
        self.setData({
            __is_offical_account: true
        });
    },
    loadRoute: function () {
        var self = this.currentPage;
        var _this = this;
        if (self.route == 'pages/index/index') {
        } else {
            getApp().core.redirectTo({
                url: '/' + self.route + '?' + getApp().helper.objectToUrlParams(self.options),
            });
        }
        _this.setUserInfoShowFalse();
    },

    setNavi: function () {
        let self = this.currentPage;
        let arr = [
            'pages/index/index',
            'pages/book/details/details',
            'pages/pt/details/details',
            'pages/goods/goods',
        ];

        if (arr.indexOf(this.currentPage.route) != -1) {
            self.setData({
                home_icon: true,
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

    saveQrcode: function () {
        var self = this.currentPage;
        if (!getApp().core.saveImageToPhotosAlbum) {
            getApp().core.showModal({
                title: '提示',
                content: '当前版本过低，无法使用该功能，请升级到最新版本后重试。',
                showCancel: false,
            });
            return;
        }

        getApp().core.showLoading({
            title: "正在保存图片",
            mask: false,
        });

        getApp().core.downloadFile({
            url: self.data.qrcode_pic,
            success: function (e) {
                getApp().core.showLoading({
                    title: "正在保存图片",
                    mask: false,
                });
                getApp().core.saveImageToPhotosAlbum({
                    filePath: e.tempFilePath,
                    success: function () {
                        getApp().core.showModal({
                            title: '提示',
                            content: '保存成功',
                            showCancel: false,
                        });
                    },
                    fail: function (e) {
                        getApp().core.showModal({
                            title: '图片保存失败',
                            content: e.errMsg,
                            showCancel: false,
                        });
                    },
                    complete: function (e) {
                        getApp().core.hideLoading();
                    }
                });
            },
            fail: function (e) {
                getApp().core.showModal({
                    title: '图片下载失败',
                    content: e.errMsg + ";" + self.data.goods_qrcode,
                    showCancel: false,
                });
            },
            complete: function (e) {
                getApp().core.hideLoading();
            }
        });
    },
};