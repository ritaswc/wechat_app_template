if (typeof wx === 'undefined') var wx = getApp().core;
Page({

    /**
     * 页面的初始数据
     */
    data: {
        contact_tel: "",
        show_customer_service: 0,
        //user_center_bg: "/images/img-user-bg.png",
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) { getApp().page.onLoad(this, options);
    },

    loadData: function (options) {
        var self = this;
        self.setData({
            store: getApp().core.getStorageSync(getApp().const.STORE),
        });
        getApp().request({
            url: getApp().api.user.index,
            success: function (res) {
                if (res.code == 0) {
                    if(self.data.__platform=='my'){
                        var menus = res.data.menus;
                        menus.forEach(function(item,index,array){
                            if(item.id==='bangding'){
                                res.data.menus.splice(index,1,0);
                            }
                        });
                    }
                    self.setData(res.data);
                    getApp().core.setStorageSync(getApp().const.PAGES_USER_USER, res.data);
                    getApp().core.setStorageSync(getApp().const.SHARE_SETTING, res.data.share_setting);
                    getApp().core.setStorageSync(getApp().const.USER_INFO, res.data.user_info);
                }
            }
        });
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function (options) { getApp().page.onReady(this);

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function (options) { getApp().page.onShow(this);
        var self = this;
        self.loadData();
    },

    callTel: function (e) {
        var tel = e.currentTarget.dataset.tel;
        getApp().core.makePhoneCall({
            phoneNumber: tel, //仅为示例，并非真实的电话号码
        });
    },
    apply: function (e) {
        var self = this;
        var share_setting = getApp().core.getStorageSync(getApp().const.SHARE_SETTING);
        var user_info = getApp().getUser();
        if (share_setting.share_condition == 1) {
            getApp().core.navigateTo({
                url: '/pages/add-share/index',
            })
        } else if (share_setting.share_condition == 0 || share_setting.share_condition == 2) {
            if (user_info.is_distributor == 0) {
                getApp().core.showModal({
                    title: "申请成为" + self.data.store.share_custom_data.words.share_name.name,
                    content: "是否申请？",
                    success: function (r) {
                        if (r.confirm) {
                            getApp().core.showLoading({
                                title: "正在加载",
                                mask: true,
                            });
                            getApp().request({
                                url: getApp().api.share.join,
                                method: "POST",
                                data: {
                                    form_id: e.detail.formId
                                },
                                success: function (res) {
                                    if (res.code == 0) {
                                        if (share_setting.share_condition == 0) {
                                            user_info.is_distributor = 2;
                                            getApp().core.navigateTo({
                                                url: '/pages/add-share/index',
                                            })
                                        } else {
                                            user_info.is_distributor = 1;
                                            getApp().core.navigateTo({
                                                url: '/pages/share/index',
                                            })
                                        }
                                        getApp().core.setStorageSync(getApp().const.USER_INFO, user_info);
                                    }
                                },
                                complete: function () {
                                    getApp().core.hideLoading();
                                }
                            });
                        }
                    },
                })
            } else {
                getApp().core.navigateTo({
                    url: '/pages/add-share/index',
                })
            }
        }
    },
    verify: function (e) {
        getApp().core.scanCode({
            onlyFromCamera: false,
            success: function (res) {
                getApp().core.navigateTo({
                    url: '/' + res.path,
                })
            }, fail: function (e) {
                getApp().core.showToast({
                    title: '失败'
                });
            }
        });
    },
    member: function () {
        getApp().core.navigateTo({
            url: '/pages/member/member',
        })
    },
    // 跳转积分商城
    integral_mall: function (e) {
        function isInArray(arr, val) {
            var testStr = ',' + arr.join(",") + ",";
            return testStr.indexOf("," + val + ",") != -1;
        }

        var plugin_id = 'integralmall';
        if (getApp().permission_list && getApp().permission_list.length && isInArray(getApp().permission_list, plugin_id)) {
            getApp().core.navigateTo({
                url: '/pages/integral-mall/index/index',
            });
        }
    },

    clearCache: function () {
        wx.showActionSheet({
            itemList: [
                '清除缓存',
            ],
            success(res) {
                if (res.tapIndex === 0) {
                    wx.showLoading({
                        title: '清除中...',
                        
                    })
                    var a = getApp().getStoreData();
                    setInterval(function(){
                        wx.hideLoading();
                    }, 1000)
                }
            }
        })
    },
    completemessage(e){
        let self = this;
        let cell = self.data.__wxapp_img.cell
        let url = [
            cell.cell_1.url, cell.cell_2.url, cell.cell_3.url, cell.cell_4.url, cell.cell_5.url
        ];
        getApp().core.previewImage({
            current: url[0],
            urls: url,
        });
    }
});