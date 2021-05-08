//detail.js
let app = getApp();
import S_request from '../../utils/requestService.js';
import * as utils from '../../utils/util.js';
import CONFIG from '../../config.js';
import collect from '../../utils/collect.js';
let curPageRequsetNumber = 3; //设置当前页面请求数量

Page({
    data: {
        pageSetting: { //页面设置
            swiperHeight: 0 // 轮播图高度
        },
        loading: { //页面loading
            hidden: false,
            msg: "加载中...",
            isViewHidden: true
        },
        toast: { //页面消息提示
            hidden: true,
            icon: "clear",
            msg: "请求超时"
        },
        collect: {
            data: [],
            actionSheetHidden: true,
            createCollectName: ""
        },
        swiperData: { //轮播图数据
            "indicatorDots": true,
            "duration": 300,
            "autoplay": true,
            "interval": 3000,
            "imgMode": "aspectFit",
            "data": []
        },
        goodsData: {}, //商品详情
        matchGoods: {}, //可搭配数据
        sameGoods: {}, //相似单品
        goodsNumberInput: 1, //购买数量
        curColors: {}, //当前选择的商品颜色
        curSizes: {} //当前选择的尺寸大小
    },
    onLoad: function (e) {
        this.goodsDetailInit(e);
        this.setSwiperHeight();
        this.getMatchGoods(e);
        this.getSameGoods(e);
    },
    onShow: function () {

    },
    onReady: function (e) {
        console.log('渲染完成', e);
    },
    //初始化 商品详情
    goodsDetailInit: function (e) {
        var _this = this, goodsId = e.id;
        //初始化
        S_request.detail.getGoods(goodsId, (res) => {
            if (res.statusCode == CONFIG.CODE.REQUESTERROR) {
                this.setData({
                    "toast.hidden": false,
                    "loading.hidden": true
                });
                console.log(res);
                return;
            }
            let img = res.images,
                imgArr = [],
                size = _this.sizesReplace(res),
                colors = res.colors ? res.colors[0] : [];

            //处理描述说明\n不换行
            res.custom_size = size;
            res.goods_desc = utils.replaceSpace(res.goods_desc);
            res.logistics_intro = utils.replaceSpace(res.logistics_intro);
            res.tag_desc = utils.replaceSpace(res.tag_desc);

            this.setData({
                "goodsData": res,
                "swiperData.data": res.images,
                curColors: colors,
                curSizes: size[0]
            });

            app.MLoading(this, curPageRequsetNumber);
        });
    },

    //初始化 商品详情Swiper高度
    setSwiperHeight: function () {
        var systemInfo = app.getSystemInfo(),
            rpx = (750 / systemInfo.windowWidth);
        //设置swiperr 高度
        this.setData({
            "pageSetting.swiperHeight": (systemInfo.windowHeight - (systemInfo.windowHeight * .37)) * rpx
        })
    },
    //获取 可搭配单品
    getMatchGoods: function (e) {
        let goodsId = e.id;
        S_request.detail.getMatchGoods(goodsId, (res) => {
            this.setData({
                "matchGoods": res
            });
            app.MLoading(this, curPageRequsetNumber);
        })
    },
    //获取 可搭配单品
    getSameGoods: function (e) {
        let goodsId = e.id;
        S_request.detail.getSameGoods(goodsId,  (res) => {
            this.setData({
                "sameGoods": res
            });
            app.MLoading(this, curPageRequsetNumber);
        })
    },
    // 跳转详情页
    jump_detail_page: function (e) {
        let goodsId = e.currentTarget.dataset.id;
        wx.redirectTo({
            url: '/pages/goodsDetail/detail?id=' + goodsId
        });
    },
    // 查看品牌
    see_brand: function (e) {
        let brandId = e.currentTarget.dataset.id;
        wx.navigateTo({
            url: '/pages/brand/brand?id=' + brandId
        })

    },
    openCartPage: function () {
        //打开选择属性页面
        this.openPageAnimate();
    },
    //选择商品数量
    change_goods_number: function (e) {
        let type = e.currentTarget.dataset.type;

        if (type == "add") {
            this.setData({
                goodsNumberInput: this.data.goodsNumberInput + 1
            })
        } else if (type == "minus" && this.data.goodsNumberInput > 1) {
            this.setData({
                goodsNumberInput: this.data.goodsNumberInput - 1
            });
        }
    },
    //选择颜色和尺寸
    selectSizeAndColor: function (e) {
        let index = e.currentTarget.dataset.curindex,
            type = e.currentTarget.dataset.type,
            size = this.sizesReplace(this.data.goodsData);
        if (type == 'colors') {
            this.setData({
                curColors: this.data.goodsData.colors[index]
            });
        } else {
            this.setData({
                curSizes: size[index]
            });
        }
    },
    //加入购物车|| 购买
    details_bot_opts: function (e) {
        let type = e.currentTarget.dataset.type,
            size = this.data.curSizes,
            color = this.data.curColors,
            goodsnumber = this.data.goodsNumberInput,
            goodsId = this.data.goodsData.goods_id;
        if (type == 'join') {
            console.log('join', size, color, goodsnumber, goodsId)
        } else {
            console.log('buy', size, color, goodsnumber, goodsId)

        }
    },
    //打开内页
    openPageAnimate: function () {
        app.globalPageAnimate('left', (animate) => {
            this.setData({
                animationData: animate.export()
            });
            setTimeout(() => {
                this.setData({
                    "loading.isViewHidden": true
                })
            }, animate.option.transition.duration)
        });
    },
    //关闭内页
    closePageAnimate: function () {
        app.globalPageAnimate('right', (animate) => {
            this.setData({
                animationData: animate.export(),
                "loading.isViewHidden": false
            });

        });
    },
    //尺码处理
    sizesReplace: function (res) {
        var size = [], isSize = res.new_size ? res.new_size : res.sizes ? res.sizes : null;
        for (let i = 0; i < isSize.length; i++) {
            if (isSize != null) {
                console.log(isSize[i]);
                size.push({
                    goods_id: isSize[i].goods_attr_id ? isSize[i].goods_attr_id : isSize[i].goods_attr_id,
                    size: isSize[i].size ? isSize[i].size : isSize[i].attr_value,
                    size_country: isSize[i].size_country ? isSize[i].size_country : null,
                    cn_size_desc: isSize[i].cn_size_desc ? isSize[i].cn_size_desc : null,
                    size_desc: isSize[i].size_desc ? isSize[i].size_desc : null
                })
            }
        }
        return size;
    },
    //展开收藏夹
    showCollect: function (e) {
        collect.showCollect(this, e);
    },
    //关闭收藏夹
    closeCollect: function (e) {
        collect.closeCollect(this, e);
    },
    //添加收藏夹
    createCollect: function (e) {
        collect.createCollect(this, e);
    },
    //选择收藏夹收藏商品
    selectCollect: function (e) {
        collect.selectCollect(this, e);
    },
    //请求超时提醒
    toastChange: function () {
        this.setData({
            "toast.hidden": true
        });
    }
});
