var hotapp = require('./hotapp');

/**
 * 更新版本号
 */
function updateVersion(cb) {
    var version = (new Date()).valueOf().toString();
    wx.setStorageSync('version', version);
    hotapp.post('version', version, function (res) {
        if (res.ret == 0) {
            return typeof cb == "function" && cb(true);
        } else {
            return typeof cb == 'function' && cb(false);
        }
    });
}

/**
 * 对比版本号
 */
function checkVersion(cb) {
    var version = wx.getStorageSync('version');
    if (!version) {
        return typeof cb == 'function' && cb(true);
    }
    hotapp.get('version', function (res) {
        if (res.ret == 0 && res.data.value == version) {
            return typeof cb == 'function' && cb(false);
        } else {
            return typeof cb == 'function' && cb(true);
        }
    });
}

/**
 * 格式化数据
 */
function formatItem(item) {
    var create_time = new Date(parseInt(item.create_time) * 1000);
    item.date = create_time.getFullYear() + '-' + (create_time.getMonth() + 1) + '-' + create_time.getDate();
    var update_time = new Date(item.update_time * 1000);
    item.update_date = update_time.getFullYear() + "-" + (update_time.getMonth() + 1) + "-" + update_time.getDate();
    //今天的日记和昨天的日记显示不同的颜色

    var d = new Date();
    var today = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate();
    var yestoday = d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + (d.getDate() - 1);

    if (item.date == today) {
        item.class = 'today';
        item.date = '今天';
    };
    if (item.date == yestoday) {
        item.class = 'yesterday';
        item.date = '昨天';
    }

    return item;
}

/**
 * 获取所有数据
 */
function getItems(cb) {
    var that = this;
    var items = wx.getStorageSync('items');
    if (items) {
        return typeof cb == 'function' && cb(items);
    } else {
        var filters = {
            prefix: hotapp.getPrefix('item'),
            pageIndex: 1,
            pageSize: 1000000
        };

        hotapp.searchkey(filters, function (res) {
            if (res.ret == 0) {
                res.data.items.forEach(function (item) {
                    item = that.formatItem(item);
                    item.state = 2;
                });
                wx.setStorageSync('items', res.data.items);
                return typeof cb == 'function' && cb(res.data.items);
            } else {
                return typeof cb == 'function' && cb([]);
            }
        });
    }
}

/**
 * 获取指定的value
 */
function show(key, cb) {
    this.getItems(function (items) {
        items.forEach(function (item) {
            if (item.key == key) {
                return typeof cb == 'function' && cb(item);
            }
        });
        if (items.length == 0) {
            return typeof cb == 'function' && cb(false);
        }
    });
}

/**
 * 新增数据
 */
function store(item, cb) {
    var that = this;
    item = that.formatItem(item);
    this.getItems(function (items) {
        var isNew = true;
        items.forEach(function (oldItem, index, arr) {
            if (oldItem.key == item.key) {
                arr[index] = item;
                isNew = false;
            }
        });
        if (isNew) {
            // 向hotapp统计发送新增事件,可知道用户每天新增次数
            hotapp.onEvent('new');
            items.push(item);
            items.sort(function (a, b) {
                return a.create_time < b.create_time;
            });
            wx.setStorageSync('items', items);
        } else {
            // 向hotapp统计发送保存事件,可知道用户每天保存次数
            hotapp.onEvent('store');
            wx.setStorageSync('items', items);
        }
        wx.getNetworkType({
            success: function (res) {
                var networkType = res.networkType; // 返回网络类型2g，3g，4g，wifi
                if (networkType == 'none') {
                    wx.showToast({
                        title: "保存成功",
                        success: function () {
                            // 返回首页
                            setTimeout(function () {
                                wx.hideToast();
                                wx.navigateBack();
                            }, 1000)
                        }
                    });
                } else {
                    wx.showToast({
                        title: '正在同步数据',
                        icon: 'loading'
                    });
                    that.updateVersion(function (success) {
                        if (success) {
                            hotapp.post(item.key, item.value, function (res) {
                                if (res.ret == 0) {
                                    item = res.data;
                                    item.state = 2;
                                } else {
                                    item.state = 1;
                                }
                                items.forEach(function (oldItem, index, arr) {
                                    if (oldItem.key == item.key) {
                                        arr[index] = item;
                                    }
                                });
                                wx.setStorageSync('items', items);
                                return typeof cb == 'function' && cb(true);
                            })

                        }
                    });
                };
            },
        });
    })
}

/**
 * 删除数据
 */
function destroy(item, cb) {
    var that = this;
    this.getItems(function (items) {
        items.forEach(function (oldItem, index, arr) {
            if (oldItem.key == item.key) {
                oldItem.state = 3;
                wx.setStorageSync('items', arr);
            }
        });
        wx.getNetworkType({
            success: function (res) {
                var networkType = res.networkType; // 返回网络类型2g，3g，4g，wifi
                if (networkType != 'none') {
                    that.updateVersion(function (success) {
                        if (success) {
                            // 向hotapp统计发送删除事件,后台可知晓用户删除了哪些标题
                            hotapp.onEvent('delete', item.value.title);
                            hotapp.del(item.key, function (res) {
                                if (res.ret == 0) {
                                    that.updateVersion();
                                    return typeof cb == 'function' && cb(true);
                                };
                            });
                        };
                    });
                } else {
                    return typeof cb == 'function' && cb(true);
                };
            },
        });

    });
}

module.exports = {
    updateVersion: updateVersion,
    checkVersion: checkVersion,
    formatItem: formatItem,
    getItems: getItems,
    show: show,
    store: store,
    destroy: destroy
}
