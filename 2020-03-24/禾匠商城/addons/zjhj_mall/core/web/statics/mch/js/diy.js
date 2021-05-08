/**
 * Created by Administrator on 2018/10/8.
 */

var app = new Vue({
    el: '#app',
    data: {
        icon: Url + '/mch/images/diy/',
        modules_list: [
            {
                name: '基础组件',
                class: 'pb-4',
                sub: [
                    {
                        name: '搜索',
                        type: 'search'
                    },
                    {
                        name: '导航图标',
                        type: 'nav'
                    },
                    {
                        name: '轮播广告',
                        type: 'banner'
                    },
                    {
                        name: '公告',
                        type: 'notice'
                    },
                    {
                        name: '专题',
                        type: 'topic'
                    },
                    {
                        name: '关联链接',
                        type: 'link'
                    },
                    {
                        name: '图片广告',
                        type: 'rubik'
                    },
                    {
                        name: '视频',
                        type: 'video'
                    },
                    {
                        name: '商品',
                        type: 'goods'
                    },
                    {
                        name: '门店',
                        type: 'shop'
                    },
                ]
            },
            {
                name: '营销组件',
                class: 'pb-4',
                sub: [
                    {
                        name: '优惠券',
                        type: 'coupon'
                    },
                    {
                        name: '倒计时',
                        type: 'time'
                    },
                    {
                        name: '拼团',
                        type: 'pintuan'
                    },
                    {
                        name: '秒杀',
                        type: 'miaosha'
                    },
                    {
                        name: '预约',
                        type: 'book'
                    },
                    {
                        name: '好店推荐',
                        type: 'mch'
                    },
                    {
                        name: '砍价',
                        type: 'bargain'
                    },
                    {
                        name: '积分商城',
                        type: 'integral'
                    },
                    {
                        name: '抽奖',
                        type: 'lottery'
                    },
                ]
            },
            {
                name: '其他组件',
                class: 'pb-4',
                sub: [
                    {
                        name: '空白占位',
                        type: 'line'
                    },
                    {
                        name: '流量主',
                        type: 'ad'
                    },
                    {
                        name: '弹窗广告',
                        type: 'modal'
                    },
                    {
                        name: '快捷导航',
                        type: 'float'
                    },
                ]
            },
        ],
        temp_list: [],
        detail: {},
        temp_index: -1,
        param: {
            search: {
                backgroundColorW: '#eeeeee',
                backgroundColor: '#ffffff',
                borderRadius: 4,
                color: '#b2b2b2',
                textPosition: 'left',
                text: '搜索'
            },
            banner: {
                style: 1,
                fill: 1,
                list: [],
                height: 360
            },
            nav: {
                backgroundColor: '#ffffff',
                count: 3,
                col: 1,
                list: [],
                default_list: [],
                is_slide: 'true'
            },
            notice: {
                bg_color: '#f67f79',
                color: '#ffffff',
                icon: '',
                name: '公告',
                content: ''
            },
            topic: {
                count: 1,
                logo_1: '',
                logo_2: '',
                heated: '',
                style: 0,
                list: [],
                is_cat: 0,

            },
            link: {
                backgroundColor: '#ffffff',
                color: '#353535',
                name: '',
                icon: '',
                is_icon: 1,
                url: '',
                left: 0,
                is_jiantou: 1,
                page_name: '',
                position: 0
            },
            line: {
                backgroundColor: '#eeeeee',
                height: 1
            },
            ad: {
                id: ''
            },
            rubik: {
                style: 0,
                fill: 1,
                space: 0,
                width: 4,
                height: 4,
                minHeight: 360,
                list: []
            },
            video: {
                url: '',
                pic_url: ''
            },
            coupon: {
                bg: Url + '/images/home-page/icon-coupon-index.png',
                bg_1: Url + '/images/home-page/icon-coupon-no.png',
                color: '#ffffff'
            },
            goods: {
                is_cat: 0,
                cat_position: 0,
                cat_style: 0,
                list: [],
                list_style: 0,
                fill: 1,
                per: 0,
                style: 0,
                name: 1,
                price: 1,
                buy: 1,
                buy_list: 0,
                buy_content: Url + '/mch/images/cat.png',
                mark: 0,
                mark_list: 0,
                mark_url: Url + '/mch/images/rx.png',
                cat_index: 0,
                buy_default: '购买'
            },
            time: {
                pic_url: '',
                url: '',
                open_type: '',
                start_time: '',
                end_time: '',
                pic_url_1: Url + '/mch/images/countdown_bg1.png'
            },
            modal: {
                show: 0,
                pic_url: '',
                url: '',
                open_type: '',
                status: 0,
                pic_width: 650,
                pic_height: 700,
                list: [
                    {
                        pic_width: 650,
                        pic_height: 700,
                        pic_url: '',
                        url: '',
                        open_type: '',
                    },
                    {
                        pic_width: 650,
                        pic_height: 700,
                        pic_url: '',
                        url: '',
                        open_type: '',
                    },
                    {
                        pic_width: 650,
                        pic_height: 700,
                        pic_url: '',
                        url: '',
                        open_type: '',
                    },
                ]
            },
            shop: {
                list: [],
                name: 1,
                score: 1,
                mobile: 1,
                distance: 1
            },
            mch: {
                is_goods: 0,
                list: [],
                price: 1,
            },
            integral: {
                bg_1: Url + '/images/home-page/icon-coupon-index.png',
                color: '#ffffff',
                is_coupon: 1,
                is_goods: 1,
                is_cat: 0,
                cat_position: 0,
                cat_style: 0,
                list: [],
                list_style: 0,
                fill: 1,
                per: 0,
                style: 0,
                name: 1,
                price: 1,
                buy: 1,
                buy_list: 0,
                buy_content: '',
                mark: 0,
                mark_list: 0,
                mark_url: Url + '/mch/images/rx.png',
                cat_index: 0,
                buy_default: '立即兑换'
            },
            float: {
                style: 0,
                cat_style: 0,
                home_img: '',
                show_customer_service: 0,
                service: '',
                dial: 0,
                dial_pic: '',
                dial_tel: '',
                web_service_status: 0,
                web_service_url: '',
                web_service: '',
                quick_map_status: 0,
                icon: '',
                address: '',
                lal: '',
                wxapp_status: 0,
                appid: '',
                path: '',
                pic_url: '',
                open: '',
                close: ''
            }
        },
        param_key: -1,
        defaultList: {
            banner: {
                title: '',
                page_url: '',
                pic_url: '',
                sort: '',
                open_type: '',
                page_name: ''
            },
            nav: {
                name: '',
                url: '',
                pic_url: '',
                sort: '',
                open_type: '',
                page_name: ''
            },
            notice: {
                icon: Url + '/wxapp/images/icon-notice.png',
            },
            topic: {
                logo_1: Url + '/images/home-page/icon-topic-1.png',
                logo_2: Url + '/images/home-page/icon-topic.png',
                heated: Url + '/images/home-page/icon-topic-r.png',
                list: [
                    {
                        cat_id: 0,
                        cat_name: '分类名称',
                        name: '分类名称',
                        goods_list: [
                            {
                                title: '这是专题标题',
                                cover_pic: Url + '/mch/images/default.png',
                                read_count: '999',
                                layout: 0
                            },
                            {
                                title: '这是专题标题',
                                cover_pic: Url + '/mch/images/default.png',
                                read_count: '999',
                                layout: 1
                            }
                        ],
                        goods_style: 0,
                        goods_num: 0
                    }
                ]
            },
            rubik: {
                common: {
                    url: '',
                    pic_url: '',
                    open_type: '',
                    page_name: ''
                },
                style_0: [
                    {
                        width: 750,
                        height: -1,
                        left: 0,
                        top: 0
                    },
                ],
                style_1: [
                    {
                        width: 300,
                        height: 360,
                        left: 0,
                        top: 0
                    },
                    {
                        width: 450,
                        height: 360,
                        left: 300,
                        top: 0
                    },
                ],
                style_2: [
                    {
                        width: 300,
                        height: 360,
                        left: 0,
                        top: 0
                    },
                    {
                        width: 450,
                        height: 180,
                        left: 300,
                        top: 0
                    },
                    {
                        width: 450,
                        height: 180,
                        left: 300,
                        top: 180
                    },
                ],
                style_3: [
                    {
                        width: 300,
                        height: 360,
                        left: 0,
                        top: 0
                    },
                    {
                        width: 450,
                        height: 180,
                        left: 300,
                        top: 0
                    },
                    {
                        width: 225,
                        height: 180,
                        left: 300,
                        top: 180
                    },
                    {
                        width: 225,
                        height: 180,
                        left: 525,
                        top: 180
                    },
                ],
                style_4: [
                    {
                        width: 375,
                        height: 240,
                        left: 0,
                        top: 0
                    },
                    {
                        width: 375,
                        height: 240,
                        left: 375,
                        top: 0
                    },
                ],
                style_5: [
                    {
                        width: 250,
                        height: 240,
                        left: 0,
                        top: 0
                    },
                    {
                        width: 250,
                        height: 240,
                        left: 250,
                        top: 0
                    },
                    {
                        width: 250,
                        height: 240,
                        left: 500,
                        top: 0
                    },
                ],
                style_6: [
                    {
                        width: 188,
                        height: 188,
                        left: 0,
                        top: 0
                    },
                    {
                        width: 188,
                        height: 188,
                        left: 188,
                        top: 0
                    },
                    {
                        width: 188,
                        height: 188,
                        left: 376,
                        top: 0
                    },
                    {
                        width: 188,
                        height: 188,
                        left: 564,
                        top: 0
                    },
                ],
                style_7: [
                    {
                        width: 375,
                        height: 186,
                        left: 0,
                        top: 0
                    },
                    {
                        width: 375,
                        height: 186,
                        left: 375,
                        top: 0
                    },
                    {
                        width: 375,
                        height: 186,
                        left: 0,
                        top: 186
                    },
                    {
                        width: 375,
                        height: 186,
                        left: 375,
                        top: 186
                    },
                ],
                style_8: []
            },
            goods: {
                list_style: [
                    {
                        name: '大图',
                        param: 0,
                        type: ['goods', 'miaosha', 'pintuan']
                    },
                    {
                        name: '一行一个',
                        param: 1,
                        type: ['goods', 'miaosha', 'pintuan']
                    },
                    {
                        name: '一行两个',
                        param: 2,
                        type: ['goods', 'miaosha', 'pintuan']
                    },
                    {
                        name: '一行三个',
                        param: 3,
                        type: ['goods']
                    },
                    {
                        name: '左右滑动',
                        param: 4,
                        type: ['goods']
                    },
                ],
                cat_one: {
                    cat_id: 0,
                    cat_name: '',
                    name: '',
                    goods_list: [],
                    goods_style: 0,
                    goods_num: 0
                },
                goods_one: {
                    id: 0,
                    pic_url: '',
                    name: '',
                    price: '',
                    attr_list: [],
                    attr_group: [],
                    is_negotiable: 0
                },
                list: [
                    {
                        cat_id: 0,
                        cat_name: '商品分类',
                        name: '商品分类',
                        goods_list: [
                            {
                                id: 0,
                                pic_url: Url + '/mch/images/default.png',
                                name: '此处显示商品名称',
                                price: '999.00',
                                original_price: '1000.00',
                                is_negotiable: 0,
                                integral_content: '999积分'
                            },
                            {
                                id: 0,
                                pic_url: Url + '/mch/images/default.png',
                                name: '此处显示商品名称',
                                price: '999.00',
                                original_price: '1000.00',
                                is_negotiable: 0,
                                integral_content: '999积分'
                            },
                            {
                                id: 0,
                                pic_url: Url + '/mch/images/default.png',
                                name: '此处显示商品名称',
                                price: '999.00',
                                original_price: '1000.00',
                                is_negotiable: 0,
                                integral_content: '999积分'
                            },
                            {
                                id: 0,
                                pic_url: Url + '/mch/images/default.png',
                                name: '此处显示商品名称',
                                price: '999.00',
                                original_price: '1000.00',
                                is_negotiable: 0,
                                integral_content: '999积分'
                            },
                        ],
                        goods_style: 0,
                        goods_num: 0
                    },
                ]
            },
            shop: {
                list: [
                    {
                        id: 0,
                        pic_url: Url + '/mch/images/default.png',
                        name: '此处显示门店名称',
                        score: 5,
                        mobile: 10086
                    }
                ],
                navigate: Url + '/wxapp/images/icon-shop-nav-1.png',
                love: Url + '/wxapp/images/icon-shop-love.png'
            },
            mch: {
                list: [
                    {
                        id: 0,
                        mch_id: 0,
                        name: '商户名称',
                        pic_url: Url + '/mch/images/default.png',
                        goods_list: [
                            {
                                id: 0,
                                pic_url: Url + '/mch/images/default.png',
                                name: '此处显示商品名称',
                                price: '999.00',
                            },
                            {
                                id: 0,
                                pic_url: Url + '/mch/images/default.png',
                                name: '此处显示商品名称',
                                price: '999.00',
                            },
                            {
                                id: 0,
                                pic_url: Url + '/mch/images/default.png',
                                name: '此处显示商品名称',
                                price: '999.00',
                            },
                            {
                                id: 0,
                                pic_url: Url + '/mch/images/default.png',
                                name: '此处显示商品名称',
                                price: '999.00',
                            }
                        ]
                    }
                ],
                mch_one: {
                    id: 0,
                    mch_id: 0,
                    name: '',
                    pic_url: '',
                    goods_list: []
                }
            },
        },
        modal_list: [],
        old_template_change: false
    },
    methods: {
        resetNav: function () {
            reset();
        },
        imagesLoad: function () {
            reset();
        }
    },
    // 监听值的变化
    watch: {
        style: function (val, oldVal) {
            if (typeof oldVal != 'undefined' && typeof val != 'undefined' && oldVal.style != 8 && val.style == 8 && oldVal.temp_index == val.temp_index) {
                this.temp_list[this.temp_index].param.list = [];
            }
            // 加载地图
            if(app.temp_list[app.temp_index].type == 'float' && app.temp_list[app.temp_index].param.style == 1) {
                setTimeout(function (e) {
                    init();
                }, 1000)
            }
        },


        newValue: function (val, oldVal) {
            console.log('newVal:' + val, 'oldVal:' + oldVal)
            reset();
        }
    },
    // 计算属性
    computed: {
        newValue: function () {
            if (this.temp_list.length > 0 && this.temp_index > -1) {
                return this.temp_list[this.temp_index].param.list
            }
        },

        style: function () {
            if (this.temp_list.length > 0 && this.temp_index > -1) {
                return {style: this.temp_list[this.temp_index].param.style, temp_index: this.temp_index}
            }
        },
    }
});

// 拖拽
var sort = Sortable.create(document.getElementById('sortList'), {
    animation: 250,
    onUpdate: function (event) {
        var $ul = document.getElementById('sortList');
        var newIndex = event.newIndex,
            oldIndex = event.oldIndex,
            $li = $ul.children[newIndex],
            $oldLi = $ul.children[oldIndex];
        // 先删除移动的节点
        $ul.removeChild($li)
        // 再插入移动的节点到原有节点，还原了移动的操作
        if (newIndex > oldIndex) {
            $ul.insertBefore($li, $oldLi)
        } else {
            $ul.insertBefore($li, $oldLi.nextSibling)
        }
        var item = app.temp_list.splice(oldIndex, 1);
        app.temp_list.splice(newIndex, 0, item[0]);
        app.temp_index = newIndex;
    }
}); // That's all.

// 删除
$(document).on('click', '.delete', function () {
    app.temp_list.splice(app.temp_index, 1);
    app.temp_index = -1;
});

// 复制
$(document).on('click', '.copy', function () {
    if(app.temp_list[app.temp_index].type == 'ad') {
        return ;
    }
    if(app.temp_list.length >= MAX_DIY && MAX_DIY > 0) {
        $.alert({
            content: '组件最多添加'+MAX_DIY+'个'
        });
        return;
    } else {
        var temp = JSON.parse(JSON.stringify(app.temp_list[app.temp_index]));
        app.temp_list.push(temp);
    }
});

// 上移
$(document).on('click', '.up', function () {
    if (app.temp_index <= 0) {
        return;
    }
    var temp = app.temp_list[app.temp_index];
    app.temp_list.splice(app.temp_index, 1);
    app.temp_list.splice(app.temp_index - 1, 0, temp);
    app.temp_index -= 1;

});

// 下移
$(document).on('click', '.down', function () {
    if (app.temp_index >= app.temp_list.length - 1) {
        return;
    }
    var temp = app.temp_list[app.temp_index];
    app.temp_list.splice(app.temp_index, 1);
    app.temp_list.splice(app.temp_index + 1, 0, temp);
    app.temp_index += 1;
});

// 组件中list的删除(轮播图、导航图标)
$(document).on('click', '.list-delete', function () {
    var key = $(this).parent('.block-handle').data('key');
    app.temp_list[app.temp_index].param.list.splice(key, 1);
});

// 组件中list上移
$(document).on('click', '.list-up', function () {
    var key = $(this).parent('.block-handle').data('key');
    if (key <= 0) {
        return;
    }
    var temp = app.temp_list[app.temp_index].param.list[key];
    app.temp_list[app.temp_index].param.list.splice(key, 1);
    app.temp_list[app.temp_index].param.list.splice(key - 1, 0, temp);

});

// 组件中list下移
$(document).on('click', '.list-down', function () {
    var key = $(this).parent('.block-handle').data('key');
    if (key >= app.temp_list[app.temp_index].param.list.length - 1) {
        return;
    }
    var temp = app.temp_list[app.temp_index].param.list[key];
    app.temp_list[app.temp_index].param.list.splice(key, 1);
    app.temp_list[app.temp_index].param.list.splice(key + 1, 0, temp);
});

// 选择组件添加到模板
$(document).on('click', '.modules', function () {
    var that = $(this);
    var type = that.data('type');
    var param = {};
    if (typeof app.param[type] == 'undefined') {
        param = JSON.parse(JSON.stringify(app.param.goods));
        param.buy_content = '';
        param.buy_list = 2;
        if (type == 'miaosha') {
            param.time = 1;
            param.buy_default = '马上秒'
        }
        if (type == 'pintuan') {
            param.buy_default = '去拼团'
        }
        if (type == 'bargain') {
            param.time = 1;
            param.buy_default = '去参与'
        }
        if (type == 'book') {
            param.buy_default = '预约'
        }
        if (type == 'lottery') {
            param.time = 1;
            param.buy_default = '去参与'
        }
    } else {
        param = JSON.parse(JSON.stringify(app.param[type]));
        if (type == 'integral') {
            param.buy_content = '';
            param.buy_list = 2;
            param.buy_default = '立即兑换'
        }
    }
    var temp = {
        type: type,
        id: 0,
        param: param,
    };
    if (type == 'ad') {
        for (var i in app.temp_list) {
            if (app.temp_list[i].type == 'ad') {
                return;
            }
        }
    }
    var modal_bool = false;
    if (type == 'modal') {
        for (var i in app.temp_list) {
            if (app.temp_list[i].type == 'modal') {
                app.temp_index = i;
                modal_bool = true;
            }
        }
        if (modal_bool) {
            app.temp_list[app.temp_index].param = $.extend(app.param[type], app.temp_list[app.temp_index].param);
        } else {
            if (app.temp_list.length >= MAX_DIY && MAX_DIY > 0) {
                $.alert({
                    content: '组件最多添加'+MAX_DIY+'个'
                });
                return;
            }
            app.temp_list.push(JSON.parse(JSON.stringify(temp)));
            app.temp_index = app.temp_list.length - 1;
        }
        $("#adModal").modal('show');
    } else if(type == 'float') {
        for (var i in app.temp_list) {
            if (app.temp_list[i].type == 'float') {
                app.temp_index = i;
                modal_bool = true;
            }
        }
        if (modal_bool) {
            app.temp_list[app.temp_index].param = $.extend(app.param[type], app.temp_list[app.temp_index].param);
        } else {
            if (app.temp_list.length >= MAX_DIY && MAX_DIY > 0) {
                $.alert({
                    content: '组件最多添加'+MAX_DIY+'个'
                });
                return;
            }
            app.temp_list.push(JSON.parse(JSON.stringify(temp)));
            app.temp_index = app.temp_list.length - 1;
        }
    } else {
        if (app.temp_list.length >= MAX_DIY && MAX_DIY > 0) {
            $.alert({
                content: '组件最多添加'+MAX_DIY+'个'
            });
            return;
        }
        app.temp_list.push(JSON.parse(JSON.stringify(temp)));
    }
});

// 选择具体的组件修改参数
$(document).on('click', '.block-default', function () {
    var index = $(this).data('index');
    app.temp_index = index;
});

// 颜色选择
$(document).on('click', '.start_click', function () {
    var color = $(this);
    var param = color.attr('data-param');
    if ($('#color-picker').length == 0) {
        color.append('<div id="color-picker" class="cp-default"></div>');
    } else if ($('#color-picker').length > 1) {
        $('div').remove("#color-picker");
        color.append('<div id="color-picker" class="cp-default"></div>');
    }
    var c = ColorPicker(
        document.getElementById('color-picker'),
        function (hex, hsv, rgb) {
            app.temp_list[app.temp_index].param[param] = hex;
        });
    if ($("#color-picker").is(":hidden")) {
        $("#color-picker").show(); //如果元素为隐藏,则将它显现
        c.setHex(app.temp_list[app.temp_index].param[param]);
    } else {
        $('div').remove("#color-picker");
        $("#color-picker").hide(); //如果元素为显现,则将其隐藏
    }
    return false;
});

// 单选框选择
$(document).on('click', '.radio-block, .banner-block', function () {
    var param = $(this).attr('data-param');
    var item = $(this).attr('data-item');
    app.temp_list[app.temp_index].param[param] = item;
    reset(param);
});

// 多选框选择
$(document).on('click', '.checkbox-block', function () {
    var param = $(this).attr('data-param');
    var checked = $(this).children('input').prop('checked');
    if (checked) {
        app.temp_list[app.temp_index].param[param] = 1;
    } else {
        app.temp_list[app.temp_index].param[param] = 0;
    }
    reset(param);
});

// 轮播图添加(轮播图、导航图标)
$(document).on('click', '.banner-add', function () {
    var param = app.defaultList[app.temp_list[app.temp_index].type];
    app.temp_list[app.temp_index].param.list.push(JSON.parse(JSON.stringify(param)));
});

// 选择某个组件参数的设置
$(document).on('click', '.param-key', function () {
    app.param_key = $(this).attr('data-key');
});

// 单选框选择(list中的)
$(document).on('click', '.list-radio-block', function () {
    var param = $(this).attr('data-param');
    var item = $(this).attr('data-item');
    app.param_key = $($(this).parents('.param-key')).attr('data-key');
    app.temp_list[app.temp_index].param.list[app.param_key][param] = item;
    reset(param);
});

// 图片上传
$(document).on('change', '.item-param', function () {
    var param = $(this).attr('data-param');
    app.temp_list[app.temp_index].param[param] = this.value;
});

// list图片上传(轮播图、导航图标)
$(document).on('change', '.model-param', function () {
    var param = $(this).attr('data-param');
    app.temp_list[app.temp_index].param.list[app.param_key][param] = this.value;
});

// 轮播图 轮播
$(document).ready(function () {
    var int = null;
    (function () {
        clearInterval(int);
        int = setInterval(function () {
            var list = $('.banner-carousel');
            $(list).each(function (i) {
                var count = $(list[i]).attr('data-count');
                if (typeof count == 'undefined') {
                    count = 0;
                    $(list[i]).attr('data-count', 0)
                }
                var left = 0;
                if (count >= list[i].children.length) {
                    count = 0;
                } else {
                    var style = $(list[i]).attr('data-style');
                    if (style == 2) {
                        left = '-' + (count * 315) + 'px';
                    } else {
                        left = '-' + (count * 375) + 'px';
                    }
                }
                $(list[i]).animate({left: left});
                $(list[i].children).removeClass('active');
                $(list[i].children[count]).addClass('active');
                count++;
                $(list[i]).attr('data-count', count);
            });
        }, 5000);
    })()
});

// 重置轮播图
function resetBanner(param) {
    var pic_list = $('.block.active').children('.block-default-img').children('.banner-carousel');

    $(pic_list).attr('data-count', 1);
    $(pic_list).animate({left: 0});
    $(pic_list.children()).removeClass('active');
    $($(pic_list).children()[0]).addClass('active');
    //var img_list = pic_list.find('img');
    //var height = 0;
    //$(img_list).each(function (i) {
    //    var temp = $(img_list[i]).height();
    //    if (temp > height) {
    //        height = temp;
    //    }
    //});
    //var width = $(img_list).width();
    //height = 180;
    //if (app.temp_list[app.temp_index].param.style == 1) {
    //    height = 180 * 375 / 375;
    //} else {
    //    height = 180 * 295 / 375;
    //}
    //$(pic_list.children('div')).css('height', height + 'px');
    if (app.temp_list[app.temp_index].param.fill == 2) {
        $(pic_list.children('div')).addClass('fill');
    } else {
        $(pic_list.children('div')).removeClass('fill');
    }
}

// 重置导航图标
function resetNav(param) {
    var param = app.temp_list[app.temp_index].param;
    var list = param.list;
    var default_list = [];
    for (var i = 0; i < list.length; i++) {
        default_list = addNav(default_list, list[i]);
    }
    app.temp_list[app.temp_index].param.default_list = default_list;
}

// 添加导航图标
function addNav(list, add) {
    var param = app.temp_list[app.temp_index].param;
    var count = param.count;
    var col = param.col;
    var ok = false;
    if (list.length > 0) {
        for (var i = 0; i < list.length; i++) {
            if (list[i].length >= (count * col)) {
                continue;
            }
            ok = true;
            if (list[i].length == 0) {
                list[i] = [add];
            } else {
                list[i].push(add);
            }
        }
    }
    if (!ok) {
        list.push([add]);
    }
    return list;
}

// 开关
$(document).on('click', '.switch', function () {
    var $switch = $(this);
    var param = $switch.attr('data-param');
    var value = $switch.attr('data-value');
    var left = 0;
    var backgroundColor = '#00c203';
    var color = '#ffffff';
    if (value == 1) {
        left = '24px';
        value = 0;
        backgroundColor = '#eeeeee';
        color = '#353535';
    } else {
        value = 1;
    }
    $switch.children('.switch-one').animate({
        left: left
    }, function () {
        $switch.css('color', color);
        $switch.css('backgroundColor', backgroundColor);
    });
    app.temp_list[app.temp_index].param[param] = value;
});

function reset(param) {
    if (app.temp_index == -1) {
        return;
    }
    var type = app.temp_list[app.temp_index].type;
    if (type == 'rubik') {
        resetBlock(param);
    }
    if (type == 'nav') {
        resetNav(param);
    }
    if (type == 'banner') {
        resetBanner(param);
    }
    if (type == 'goods') {
        resetGoods(param);
    }
    if (type == 'miaosha') {
        resetGoods(param);
    }
    if (type == 'pintuan') {
        resetGoods(param);
    }
    if (type == 'bargain') {
        resetGoods(param);
    }
    if (type == 'book') {
        resetGoods(param);
    }
    if (type == 'lottery') {
        resetGoods(param);
    }
    if (type == 'mch') {
        resetMch(param);
    }
    if (type == 'integral') {
        resetGoods(param);
    }
    if (type == 'topic') {
        resetTopic(param);
    }
}

// 重置图片魔方
function resetBlock(param) {
    var style = app.temp_list[app.temp_index].param.style;
    var common = app.defaultList.rubik.common;
    common = JSON.parse(JSON.stringify(common));
    var default_list = app.defaultList.rubik['style_' + style];
    default_list = JSON.parse(JSON.stringify(default_list));
    var list = app.temp_list[app.temp_index].param.list;
    if (style != 8) {
        if (list.length == 0) {
            list = default_list;
            for (var i = 0; i < list.length; i++) {
                list[i] = $.extend(list[i], common);
            }
        }
        for (var i = 0; i < list.length; i++) {
            list[i].width = 0;
            list[i].height = 0;
            list[i].top = 0;
            list[i].left = 0;
        }
    }
    if (default_list.length - 1 < app.param_key) {
        app.param_key = -1;
    }
    for (var i = 0; i < default_list.length; i++) {
        if (list.length <= i) {
            list.push($.extend(default_list[i], common));
        } else {
            list[i] = $.extend(list[i], default_list[i]);
        }
    }
    var minHeight = app.temp_list[app.temp_index].param.minHeight;
    if (style == 1 || style == 2 || style == 3) {
        minHeight = 360;
    } else if (style == 4 || style == 5) {
        minHeight = 240;
    } else if (style == 6) {
        minHeight = 186;
    } else if (style == 0) {
        minHeight = 100;
    } else if (style == 7) {
        minHeight = 372;
    } else {
        var width = app.temp_list[app.temp_index].param.width;
        var height = app.temp_list[app.temp_index].param.height;
        minHeight = height * 750 / width;
    }
    app.temp_list[app.temp_index].param.minHeight = minHeight * 2;
    list = resetWHLT(list);
    var space = app.temp_list[app.temp_index].param.space;
    app.temp_list[app.temp_index].param.new_minHeight = minHeight + space * 2;
    if (style == 8) {
        var newMinHeight = 'calc(';
        for (var i = 0; i < app.temp_list[app.temp_index].param.height; i++) {
            newMinHeight += Math.round(750 / width) + 'rpx + ';
        }
        newMinHeight += space * 2 + 'rpx)';
        app.temp_list[app.temp_index].param.n_minHeight = newMinHeight;
    } else {
        app.temp_list[app.temp_index].param.n_minHeight = 0;
    }
    app.temp_list[app.temp_index].param.list = list;
}

// 重置商品信息
function resetGoods(param) {
    var is_cat = app.temp_list[app.temp_index].param.is_cat;
    var list = app.temp_list[app.temp_index].param.list;
    var buy_list = app.temp_list[app.temp_index].param.buy_list;
    var mark_list = app.temp_list[app.temp_index].param.mark_list;
    if (param == 'buy_list') {
        if (buy_list == 0) {
            app.temp_list[app.temp_index].param.buy_content = Url + '/mch/images/cat.png';
        } else if (buy_list == 1) {
            app.temp_list[app.temp_index].param.buy_content = Url + '/mch/images/add.png';
        } else {
            app.temp_list[app.temp_index].param.buy_content = '';
        }
    }
    if (param == 'mark_list') {
        if (mark_list == 0) {
            app.temp_list[app.temp_index].param.mark_url = Url + '/mch/images/rx.png';
        } else if (mark_list == 1) {
            app.temp_list[app.temp_index].param.mark_url = Url + '/mch/images/xp.png';
        } else if (mark_list == 2) {
            app.temp_list[app.temp_index].param.mark_url = Url + '/mch/images/zk.png';
        } else if (mark_list == 3) {
            app.temp_list[app.temp_index].param.mark_url = Url + '/mch/images/tj.png';
        } else {
            app.temp_list[app.temp_index].param.mark_url = '';
        }
    }
    if (param == 'goods_style') {
        var goods_style = app.temp_list[app.temp_index].param.list[app.param_key].goods_style;
        if (goods_style != 2) {
            app.temp_list[app.temp_index].param.list[app.param_key].goods_list = [];
        }
    }
    if (param == 'list_style') {
        app.temp_list[app.temp_index].param.time = 1;
    }
    if (is_cat == 0) {
        if (list.length == 0 || list[0].cat_id != 0) {
            list = [];
            var cat = JSON.parse(JSON.stringify(app.defaultList.goods.cat_one));
            cat.cat_id = 0;
            cat.cat_name = '默认';
            cat.name = '默认';
            list.push(cat);
        }
    } else {
        if (list.length > 0 && list[0].cat_id == 0) {
            list = [];
        }
    }
    app.temp_list[app.temp_index].param.list = list;
}

// 填写商品数量
$(document).on('input', '.input-num', function () {
    var _this = $(this);
    var label = _this.parents('.list-radio-block');
    var param = $(label).attr('data-param');
    var item = $(label).attr('data-item');
    app.param_key = $($(label).parents('.param-key')).attr('data-key');
    if (typeof app.param_key == 'undefined') {
        return;
    }
    app.temp_list[app.temp_index].param.list[app.param_key][param] = item;
    var _param = _this.attr('data-param');
    if (_this.val() < 0) {
        _this.val(0);
        app.temp_list[app.temp_index].param.list[app.param_key][_param] = 0;
    } else if (_this.val() > 30) {
        _this.val(30);
        app.temp_list[app.temp_index].param.list[app.param_key][_param] = 30;
    }
    reset(param);
});


$(document).on('click', '.checkbox-all', function () {
    var checked = $(this).prop('checked');
    $('.checkbox-one').prop('checked', checked);
});
$(document).on('click', '.checkbox-one', function () {
    var checked = $(this).prop('checked');
    var all = $('.checkbox-one');
    var is_all = true;//只要有一个没选中，全选按钮就不选中
    all.each(function (i) {
        if ($(all[i]).prop('checked')) {
        } else {
            is_all = false;
        }
    });
    if (is_all) {
        $('.checkbox-all').prop('checked', true);
    } else {
        $('.checkbox-all').prop('checked', false);
    }
});

// 商品分类添加
$(document).on('click', '.cat-add', function () {
    $('#catModal').modal('show');
    var type = app.temp_list[app.temp_index].type;
    var data = {
        type: 'goods'
    };
    if (type == 'topic') {
        data.type = 'topic';
    }
    get_cat(CATURL, data);
});

// 获取分类
function get_cat(url, data) {
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        data: data,
        success: function (res) {
            app.modal_list = res.data;
            var type = typeof data.type == 'undefined' ? app.temp_list[app.temp_index].type : data.type;
            if (type == 'mch') {
                app.modal_list.cat_list = res.data.goods_list;
                app.modal_list.goods_list = res.data.goods_list;
            }
        }
    });
    $('.checkbox-all').prop('checked', false);
    $('.checkbox-one').prop('checked', false);
}

// 分页点击
$(document).on('click', '.vue-pagination .page-link', function () {
    var _this = $(this);
    var url = _this.attr('data-url');
    var data = {
    };
    get_cat(url, data);
});

// 确定分类选择
$(document).on('click', '.cat-btn', function () {
    var check_list = $('.checkbox-one:checked');
    $(check_list).each(function (i) {
        if (app.temp_list[app.temp_index].param.list.length >= 8) {
            return;
        }
        var type = app.temp_list[app.temp_index].type;
        var index = $(check_list[i]).attr('data-index');
        var cat = app.modal_list.cat_list[index];
        if (type == 'mch') {
            var param = JSON.parse(JSON.stringify(app.defaultList.mch.mch_one));
            param.id = cat.id;
            param.mch_id = cat.mch_id;
            param.name = cat.name;
            param.pic_url = cat.pic_url;
            param.goods_style = 0;
            param.goods_count = cat.goods_count;
            param.goods_list = app.defaultList.mch.list[0].goods_list;
            app.temp_list[app.temp_index].param.list.push(param);
        } else {
            var param = JSON.parse(JSON.stringify(app.defaultList.goods.cat_one));
            param.cat_id = cat.id;
            param.cat_name = cat.name;
            param.name = cat.name;
            app.temp_list[app.temp_index].param.list.push(param);
        }
    });
    $('#catModal').modal('hide');
});

// 商品添加
$(document).on('click', '.goods-add', function () {
    $('#goodsModal').modal('show');
    var type = app.temp_list[app.temp_index].type;
    var data = {
        type: type ? type : 'goods'
    };
    if (type == 'shop') {

    } else if (type == 'mch') {
        data.type = 'goods';
        var key = $(this).attr('data-key');
        app.param_key = key;
        var mch = app.temp_list[app.temp_index].param.list[key].mch_id;
        data.mch = mch
    } else {
        var key = $(this).attr('data-key');
        app.param_key = key;
        app.temp_list[app.temp_index].param.list[key].goods_style = 2;
        var cat = app.temp_list[app.temp_index].param.list[key].cat_id;
        data.cat = cat;
    }
    get_cat(GOODSURL, data);
});

$(document).on('click', '.goods-btn', function () {
    var check_list = $('.checkbox-one:checked');
    var type = app.temp_list[app.temp_index].type;
    $(check_list).each(function (i) {
        var index = $(check_list[i]).attr('data-index');
        var goods = app.modal_list.goods_list[index];
        if (type == 'shop') {
            if (app.temp_list[app.temp_index].param.list.length >= 10) {
                return;
            }
            app.temp_list[app.temp_index].param.list.push(goods);
        } else if (type == 'topic') {
            if (app.temp_list[app.temp_index].param.list[app.param_key].goods_list.length >= 10) {
                return;
            }
            var param = {
                id: goods.id,
                cover_pic: goods.cover_pic,
                title: goods.title,
                layout: goods.layout,
                read_count: goods.read_count,
            };
            app.temp_list[app.temp_index].param.list[app.param_key].goods_list.push(param);
        } else if (type == 'mch') {
            if (app.temp_list[app.temp_index].param.list[app.param_key].goods_list.length >= 10) {
                return;
            }
            var param = JSON.parse(JSON.stringify(app.defaultList.goods.goods_one));
            param.id = goods.id;
            param.goods_id = goods.id;
            param.pic_url = goods.pic_url;
            param.name = goods.name;
            param.price = goods.price;
            param.original_price = goods.original_price;
            app.temp_list[app.temp_index].param.list[app.param_key].goods_list.push(param);
        } else {
            if (app.temp_list[app.temp_index].param.list[app.param_key].goods_list.length >= 30) {
                return;
            }
            var param = JSON.parse(JSON.stringify(app.defaultList.goods.goods_one));
            param.id = goods.id;
            param.goods_id = goods.id;
            param.pic_url = goods.pic_url;
            param.name = goods.name;
            param.price = goods.price;
            param.original_price = goods.original_price;
            param.price_content = goods.price_content;
            if (app.temp_list[app.temp_index].type == 'goods') {
                param.is_negotiable = goods.is_negotiable;
            }
            if (app.temp_list[app.temp_index].type == 'miaosha') {
                param.goods_id = goods.goods_id;
                param.original_price_content = goods.original_price_content;
            }
            if (app.temp_list[app.temp_index].type == 'pintuan') {
                param.original_price_content = goods.original_price_content;
            }
            if (app.temp_list[app.temp_index].type == 'integral') {
                param.goods_id = goods.goods_id;
                param.integral_content = goods.integral_content;
            }
            app.temp_list[app.temp_index].param.list[app.param_key].goods_list.push(param);
        }
    });
    app.modal_list = [];
    $('#goodsModal').modal('hide');
});

$(document).on('dblclick', '.select-file-db', function () {
    $(this).children('.select-file').click();
});

$(document).on('input', "input[data-param='space']", function () {
    resetBlock('space');
});

// 图片广告 间隙变化
function resetWHLT(list) {
    var minHeight = app.temp_list[app.temp_index].param.minHeight;
    var space = app.temp_list[app.temp_index].param.space;
    var param = app.temp_list[app.temp_index].param;
    if (param.style == 0) {
        space = 0;
        app.temp_list[app.temp_index].param.space = 0;
    }
    app.temp_list[app.temp_index].param.new_minHeight = minHeight + space * 2;
    for (var j in list) {
        var obj = list[j];
        for (var i in obj) {
            var item = obj[i];
            // if(param.style == 6 && (j == 1 || j == 2) && i == 'width') {
            //     item -= 1;
            // }
            if (space > 0) {
                var newWidth = 375 + space * 2;
                var newHeight = minHeight + space * 2;
                if (i == 'width') {
                    var width = Math.ceil(item * newWidth / 375);
                    obj['new_' + i] = width - space * 2;
                    if (obj[i] == 0) {
                        obj['new_' + i] = 0;
                    }
                }
                if (i == 'height') {
                    var height = obj[i] * newHeight / minHeight;
                    obj['new_' + i] = height - space * 2;
                    if (obj[i] == 0) {
                        obj['new_' + i] = 0;
                    }
                }
                if (i == 'left') {
                    var left = obj[i] * newWidth / 375;
                    obj['new_' + i] = parseInt(left) + parseInt(space);
                    switch (param.style) {
                        case '6':
                            if (j == 2) {
                                obj['n_' + i] = 'calc(' + list[0].new_width + 'rpx + ' + list[1].new_width + 'rpx + ' + parseInt(space)*5 + 'rpx)'
                            }
                            if (j == 3) {
                                obj['n_' + i] = 'calc(' + list[0].new_width + 'rpx + ' + list[1].new_width + 'rpx + ' + list[2].new_width + 'rpx + ' + parseInt(space)*7 + 'rpx)'
                            }
                            break;
                        case '8':
                            var per = Math.round(750 / parseInt(param.width));
                            var max = Math.round(obj[i] / per);
                            var str = 'calc(';
                            for (var a = 0; a < max; a++) {
                                str += (per  * newWidth / 375) + 'rpx + ';
                            }
                            str += parseInt(space) + 'rpx';
                            str += ')';
                            obj['n_' + i] = str;
                            break;
                        default:
                            delete obj.n_left
                    }
                }
                if (i == 'top') {
                    var top = obj[i] * newHeight / minHeight;
                    obj['new_' + i] = parseInt(top) + parseInt(space);
                    switch (param.style) {
                        case '8':
                            var per = Math.round(750 / parseInt(param.width));
                            var max = Math.round(obj[i] / per);
                            var str = 'calc(';
                            for (var a = 0; a < max; a++) {
                                str += (per  * newWidth / 375) + 'rpx + ';
                            }
                            str += parseInt(space) + 'rpx';
                            str += ')';
                            obj['n_' + i] = str;
                            break;
                        default:
                            delete obj.n_top

                    }
                }
            } else {
                if (i == 'left' || i == 'top' || i == 'width' || i == 'height') {
                    obj['new_' + i] = item;
                    switch (param.style) {
                        case '8':
                            var per = Math.round(750 / parseInt(param.width));
                            var max = Math.round(obj[i] / per);
                            var str = 'calc(';
                            for (var a = 0; a < max; a++) {
                                str += per + 'rpx + ';
                            }
                            str += '0rpx)';
                            obj['n_' + i] = str;
                            break;
                        default:
                            delete obj['n_' + i];
                    }
                }
                if (i == 'left') {
                    switch (param.style) {
                        case '6':
                            if (j == 2) {
                                obj['n_' + i] = 'calc(' + list[0].new_width + 'rpx + ' + list[1].new_width + 'rpx + ' + parseInt(space)*5 + 'rpx)'
                            }
                            if (j == 3) {
                                obj['n_' + i] = 'calc(' + list[0].new_width + 'rpx + ' + list[1].new_width + 'rpx + ' + list[2].new_width + 'rpx + ' + parseInt(space)*7 + 'rpx)'
                            }
                            break;
                        default:
                            delete obj['n_left']
                    }
                }
            }
        }
        list[j] = obj;
    }
    return list;
}

// 图片魔方拉取
$(document).on('click', '.rubik-select', function () {
    var id = $(this).prev('input').val();
    $.ajax({
        url: RUBIKURL,
        type: 'get',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (res) {
            var data = res.data.pic_list;
            var list = app.temp_list[app.temp_index].param.list;
            for (var i in data) {
                if (list.length > i) {
                    list[i].url = data[i].url;
                    list[i].pic_url = data[i].pic_url;
                    list[i].open_type = data[i].open_type;
                }
            }
            app.temp_list[app.temp_index].param.list = list;
        }
    });
});

var FIRST_CLICK = true;
$(document).on('click', '.rubik-custom-one', function () {
    var list = $('.rubik-custom-one');
    if ($(this).attr('class').indexOf('active-2') != -1) {
        return;
    }
    if (FIRST_CLICK) {
        $(this).addClass('active-1');
        var click_p = $(this).position();
        list.bind('mouseover', function (e) {
            var new_p = $(this).position();

            var active_list = $('.rubik-custom-one.active-2');
            var max_p = JSON.parse(JSON.stringify(new_p));
            var min_p = JSON.parse(JSON.stringify(click_p));
            max_p.top = Math.max(new_p.top, click_p.top);
            min_p.top = Math.min(new_p.top, click_p.top);
            max_p.left = Math.max(new_p.left, click_p.left);
            min_p.left = Math.min(new_p.left, click_p.left);
            var active = false;
            var active_p = {};
            $(active_list).each(function (i) {
                active_p = $(this).position();
                if ((active_p.top <= max_p.top && active_p.left <= max_p.left && active_p.top >= min_p.top && active_p.left >= min_p.left)) {
                    max_p = JSON.parse(JSON.stringify(active_p));
                    active = true;
                    return;
                }
            });
            if (!(click_p.top <= max_p.top && click_p.left <= max_p.left && click_p.top >= min_p.top && click_p.left >= min_p.left)) {
                return;
            }

            var class_active = false;
            $(list).each(function (i) {
                var _this_p = $(this).position();
                if (_this_p.top >= min_p.top && _this_p.left >= min_p.left) {
                    if (_this_p.top <= max_p.top && ((active && click_p.top == max_p.top && _this_p.left < max_p.left) || (!active && _this_p.left <= max_p.left))) {
                        $(this).addClass('active-1');
                    } else if (_this_p.left <= max_p.left && ((active && click_p.left == max_p.left && _this_p.top < max_p.top) || (!active && _this_p.top <= max_p.top))) {
                        $(this).addClass('active-1');
                    } else {
                        $(this).removeClass('active-1');
                    }
                } else {
                    $(this).removeClass('active-1');
                }
                if ($(this).attr('class').indexOf('active-1') != -1) {
                    class_active = true;
                }
            });
            if (!class_active) {
                list.unbind('mouseover');
                FIRST_CLICK = true;
            }
        });
        FIRST_CLICK = false;
    } else {
        var max = {
            top: -1,
            left: -1
        };
        var min = {
            top: -1,
            left: -1
        };
        var width = 0;
        var height = 0;
        $(list).each(function (i) {
            var rubik_class = $(list[i]).attr('class');
            if (rubik_class.indexOf('active-1') != -1) {
                width = $(this).width();
                height = $(this).height();
                var _this = $(this).position();
                if (max.top == -1) {
                    max.top = _this.top;
                }
                if (max.left == -1) {
                    max.left = _this.left;
                }
                if (min.top == -1) {
                    min.top = _this.top;
                }
                if (min.left == -1) {
                    min.left = _this.left;
                }
                if (max.top == -1) {
                    max.top = _this.top;
                }
                max.top = Math.max(_this.top, max.top);
                max.left = Math.max(_this.left, max.left);
                min.top = Math.min(_this.top, min.top);
                min.left = Math.min(_this.left, min.left);
                $(list[i]).removeClass('active-1');
                $(list[i]).addClass('active-2');
            }
        });
        var w = app.temp_list[app.temp_index].param.width;
        var mWidth = (750 / w).toFixed(0);

        var whlt = {
            //width: ((750 / w) * (Math.ceil((max.left - min.left) / ((375 / w).toFixed(0))) + 1)).toFixed(0),
            //height: ((750 / w) * (Math.ceil((max.top - min.top) / ((375 / w).toFixed(0))) + 1)).toFixed(0),
            //left: min.left * 2,
            //top: min.top * 2,
            width: ((Math.floor((max.left - min.left) / mWidth * 2) + 1) * (750 / w)).toFixed(0),
            height: ((Math.floor((max.top - min.top) / mWidth * 2) + 1) * (750 / w)).toFixed(0),
            left: (Math.floor(min.left / mWidth * 2) * (750 / w)).toFixed(0),
            top: (Math.floor(min.top / mWidth * 2) * (750 / w)).toFixed(0)
        };
        var common = app.defaultList.rubik.common;
        common = JSON.parse(JSON.stringify(common));
        app.temp_list[app.temp_index].param.list.push($.extend(whlt, common));
        list.unbind('mouseover');
        FIRST_CLICK = true;
    }
});

// 魔方宽高变化
$(document).on('change', '.param-wh', function () {
    $('.rubik-custom-one').removeClass('active-1');
    $('.rubik-custom-one').removeClass('active-2');
    $('.rubik-custom-one').unbind('mouseover');
    FIRST_CLICK = true;
    app.temp_list[app.temp_index].param.list = [];
});

$(document).on('mouseover', '.border-active-1,.img-list', function () {
    $(this).children('.chacha').show();
});

$(document).on('mouseout', '.border-active-1,.img-list', function () {
    $(this).children('.chacha').hide();
});

$(document).on('click', '.chacha-rubik', function () {
    var key = $(this).parent('.border-active-1').attr('data-key');
    var list = app.temp_list[app.temp_index].param.list[key];
    list = JSON.parse(JSON.stringify(list));
    var rubik_custom = $('.rubik-custom-one');
    var max_top = (list.top + list.height) / 2;
    var max_left = (list.left + list.width) / 2;
    $(rubik_custom).each(function (i) {
        var _this_p = $(this).position();
        if (_this_p.top <= max_top && _this_p.top >= (list.top / 2) && _this_p.left <= max_left && _this_p.left >= (list.left / 2)) {
            $(this).removeClass('active-2');
        }
    });
    $('.border-active-1').children('.chacha').hide();
    app.temp_list[app.temp_index].param.list.splice(key, 1);
});

$(document).on('click', '.chacha-goods', function () {
    var type = app.temp_list[app.temp_index].type;
    var k = $(this).parent('.img-list').attr('data-k');
    if (type == 'shop') {
        app.temp_list[app.temp_index].param.list.splice(k, 1);
    } else {
        var is_cat = app.temp_list[app.temp_index].param.is_cat;
        if (is_cat == 1 || type == 'mch') {
            var key = $(this).parents('.img-list').attr('data-key');
            app.temp_list[app.temp_index].param.list[key].goods_list.splice(k, 1);
        } else {
            app.temp_list[app.temp_index].param.list[0].goods_list.splice(k, 1);
        }
    }
});

$(document).on('click', '.chacha-mch', function () {
    var type = app.temp_list[app.temp_index].type;
    var k = $(this).parent('.img-list').attr('data-k');
    app.temp_list[app.temp_index].param.list.splice(k, 1);
});


var template_id = $('.template_id').val()
getTemplateDetail();

// 模板详情
function getTemplateDetail() {
    $.ajax({
        url: SUBMITURL,
        dataType: 'json',
        type: 'get',
        data: {
            template_id: template_id
        },
        success: function (res) {
            if (res.code == 0) {
                Vue.set(app, 'temp_list', res.data.detail.template);
                Vue.set(app, 'detail', res.data.detail);
            }
            Vue.set(app, 'modules_list', res.data.modules_list);
        }
    })
}

//  模板保存
$(document).on('click', '.auto-form-btn-diy', function () {
    var btn = $(this);
    btn.btnLoading('保存中');
    var temp_list = app.temp_list;
    var data = {
        list: JSON.stringify(temp_list),
        template_id: template_id,
        name: app.detail.name,
        type: btn.attr('data-type'),
        _csrf: _csrf
    };
    $.ajax({
        url: SUBMITURL,
        dataType: 'json',
        type: 'post',
        data: data,
        success: function (res) {
            btn.btnReset();
            if (res.code == 0) {
                template_id = res.data.template_id;
                $('.template_id').val(res.data.template_id);
                $.myAlert({
                    content: res.msg,
                    confirm: function (res) {
                    }
                });
            } else {
                $.myAlert({
                    content: res.msg
                })
            }
        }
    })
});

$(document).on('click', '.date_start', function () {
    $('.date_start').datetimepicker({
        datepicker: true,
        timepicker: true,
        format: 'Y-m-d H:i',
        dayOfWeekStart: 1,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        onShow: function (ct) {
            this.setOptions({
                maxDate: $('.date_end').val() ? $('.date_end').val() : false,
            })
        },
        onClose: function (ct) {
            if ($('.date_end').val() && $('.date_start').val() > $('.date_end').val()) {
                $('.date_end').val($('.date_start').val());
            }
        }
    });
    $('.date_start').datetimepicker('show');
});

$(document).on('click', '.date_end', function () {
    $('.date_end').datetimepicker({
        datepicker: true,
        timepicker: true,
        format: 'Y-m-d H:i',
        dayOfWeekStart: 1,
        scrollMonth: false,
        scrollTime: false,
        scrollInput: false,
        onShow: function (ct) {
            this.setOptions({
                minDate: $('.date_start').val() ? $('.date_start').val() : false,
            })
        },
        onClose: function (ct) {
            if ($('.date_start').val() && $('.date_start').val() > $('.date_end').val()) {
                $('.date_end').val($('.date_start').val())
            }
        }
    });
    $('.date_end').datetimepicker('show');
});

$(document).on('click', '.ad-modal-btn', function () {
    $("#adModal").modal('hide');
});

$(document).on('click', '.nav-banner-add', function () {
    var param = $(this).attr('data-param');
    var data = {
        type: param
    };
    $("#nbModal").modal('show');
    get_cat(GETURL, data);
});

$(document).on('click', '.nav-banner-btn', function () {
    var key = $(this).attr('data-key');
    var check_list = $('.checkbox-one:checked');
    $(check_list).each(function (i) {
        var index = $(check_list[i]).attr('data-index');
        var one = app.modal_list.list[index];
        console.log(key)
        var param = JSON.parse(JSON.stringify(app.defaultList[key]));
        if (key == 'nav') {
            param.name = one.name;
            param.url = one.url;
        } else if (key == 'banner') {
            param.title = one.name;
            param.page_url = one.url;
        }
        param.open_type = one.open_type;
        param.pic_url = one.pic_url;
        app.temp_list[app.temp_index].param.list.push(param);
    });
    $('#nbModal').modal('hide');
});

function resetMch(param) {
    var is_goods = app.temp_list[app.temp_index].param.is_goods;
    var list = app.temp_list[app.temp_index].param.list;
    if (is_goods == 0) {
        for (var i in list) {
            list[i].goods_list = [];
        }
    }
    if (param == 'goods_style') {
        var param_key = app.param_key;
        if (app.temp_list[app.temp_index].param.list[param_key].goods_style != 2) {
            app.temp_list[app.temp_index].param.list[param_key].goods_list = app.defaultList.mch.list[0].goods_list;
        } else {
            app.temp_list[app.temp_index].param.list[param_key].goods_list = [];
        }
    }
    app.temp_list[app.temp_index].param.list = list;
}

$(document).on('click', '.mch-add', function () {
    $('#catModal').modal('show');
    var data = {
        type: 'mch'
    };
    get_cat(GOODSURL, data);
});

$(document).on('submit', '.goods-form', function () {
    var _this = $(this);
    var url = _this.attr('action');
    var type = app.temp_list[app.temp_index].type;
    url = url + '&type=' + type;
    get_cat(url, _this.serialize());
    return false;
});

$(document).on('click', '.goods-form-btn', function () {
    $('.goods-form').submit();
    return false;
});

$(document).on('submit', '.cat-form', function () {
    var _this = $(this);
    var url = _this.attr('action');
    var type = app.temp_list[app.temp_index].type;
    url = url + '&type=' + type;
    get_cat(url, _this.serialize());
    return false;
});

$(document).on('click', '.cat-form-btn', function () {
    $('.cat-form').submit();
    return false;
});

$(document).on('mouseover', '.modal-handle', function () {
    $($(this).children('.block-handle')).show();
});

$(document).on('mouseout', '.modal-handle', function () {
    $($(this).children('.block-handle')).hide();
});

$(document).on('click', '.modal-delete', function () {
    var key = $(this).parent('.block-handle').data('key');
    var param = {
        pic_width: 650,
        pic_height: 700,
        pic_url: '',
        url: '',
        open_type: '',
    };
    app.temp_list[app.temp_index].param.list.splice(key, 1, param);
});

$(document).on('click', '.modal-add', function () {
    var param = {
        pic_width: 650,
        pic_height: 700,
        pic_url: '',
        url: '',
        open_type: '',
    };
    app.temp_list[app.temp_index].param.list.push(param);
});

function resetTopic(param) {
    var is_cat = app.temp_list[app.temp_index].param.is_cat;
    var list = app.temp_list[app.temp_index].param.list;
    if (is_cat == 0) {
        if (list.length == 0 || list[0].cat_id != 0) {
            list = [];
            var cat = {
                cat_id: 0,
                cat_name: '默认',
                name: '默认',
                goods_list: [],
                goods_style: 0,
                goods_num: 0
            };
            list.push(cat);
        }
    } else {
        if (list.length > 0 && list[0].cat_id == 0) {
            list = [];
        }
    }
    app.temp_list[app.temp_index].param.list = list;
}

/*---- 视频上传 start ----*/
$(document).on('click', '.video-picker-btn', function () {
    var el = $(this).parents('.video-picker');
    var btn = el.find('.video-picker-btn');
    var url = el.data('url');
    var input = el.find('.video-picker-input');
    var view = el.find('.video-preview');
    $.upload_file({
        accept: 'video/mp4',
        url: url,
        start: function () {
            btn.btnLoading(btn.text());
        },
        success: function (res) {
            btn.btnReset();
            if (res.code === 1) {
                $.alert({
                    content: res.msg
                });
                return;
            }
            input.val(res.data.url).trigger('change');
        },
    });
});
/*---- 视频上传 end ----*/

/*---- 地图组件加载 -----*/
var searchService, map, markers = [];
//        window.onload = function(){
//直接加载地图
//初始化地图函数  自定义函数名init
function init() {
    //定义map变量 调用 qq.maps.Map() 构造函数   获取地图显示容器
    var map = new qq.maps.Map(document.getElementById("container"), {
        center: new qq.maps.LatLng(39.916527, 116.397128),      // 地图的中心地理坐标。
        zoom: 15                                                 // 地图的中心地理坐标。
    });
    var latlngBounds = new qq.maps.LatLngBounds();
    //调用Poi检索类
    searchService = new qq.maps.SearchService({
        complete: function (results) {
            var pois = results.detail.pois;
            $('.map-error').hide();
            if (!pois) {
                $('.map-error').show().html('关键字搜索不到，请重新输入');
                return;
            }
            for (var i = 0, l = pois.length; i < l; i++) {
                (function (n) {
                    var poi = pois[n];
                    latlngBounds.extend(poi.latLng);
                    var marker = new qq.maps.Marker({
                        map: map,
                        position: poi.latLng,
                    });

                    marker.setTitle(n + 1);

                    markers.push(marker);
                    //添加监听事件
                    qq.maps.event.addListener(marker, 'click', function (e) {
                        var address = poi.address;
                        app.temp_list[app.temp_index].param.address = address;
                        app.temp_list[app.temp_index].param.lal = e.latLng.lat + ',' + e.latLng.lng;
                    });
                })(i);
            }
            map.fitBounds(latlngBounds);
        }
    });
}
//清除地图上的marker
function clearOverlays(overlays) {
    var overlay;
    while (overlay = overlays.pop()) {
        overlay.setMap(null);
    }
}
function searchKeyword() {
    var keyword = $(".keyword").val();
    var region = $(".region").val();
    clearOverlays(markers);
    searchService.setLocation(region);
    searchService.search(keyword);
}
$(document).on('click', '.float-search', function () {
    searchKeyword();
})
/*---- 地图组件加载 -----*/