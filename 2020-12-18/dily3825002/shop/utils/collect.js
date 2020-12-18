/**
 * Created by chenruilong on 16/10/11.
 *
 * -----页面使用------
 * 1.引入 [收藏夹, Loading, toast] 模版:
 *  <template is="collect" data="{{...collect}}"></template>
 *  <loading hidden="{{loading.hidden}}">{{loading.msg}}</loading>
 *  <toast hidden="{{toast.hidden}}" icon="{{toast.icon}}" duration="3000" bindchange="toastChange">{{toast.msg}}</toast>
 *
 * 2.控制器里绑定以下默认数据:
    collect : {
       data : [],
      actionSheetHidden : true,
      createCollectName : ""
    },
    loading : { //页面loading
      hidden : false,
      msg : "加载中...",
      isViewHidden : true //配合页面display使用
    },
    toast : { //页面消息提示
      hidden : true,
      icon   : "clear",
      msg : ""
    }
 * 3.控制器需绑定收藏夹事件
 * (1). showCollect     {打开收藏夹, 对应showCollect}
 * (2). closeCollect    {关闭收藏夹, 对应closeCollect}
 * (3). createCollect   {添加收藏夹, 对应createCollect}
 * (4). selectCollect   {选择收藏夹收藏商品, 对应selectCollect}
 *
 *  页面只需要绑定 打开收藏就可以, 其他三个不用在页面绑定
     //打开收藏夹
     showCollect : function(e){
        collect.showCollect(this, e);
      },
     //关闭收藏夹
     closeCollect : function(e){
        collect.closeCollect(this, e);
      },
     //添加收藏夹
     createCollect : function(e){
        collect.createCollect(this, e);
      },
     //选择收藏夹收藏商品
     selectCollect : function(e){
        collect.selectCollect(this, e);
      }
 *
 *
 *
 * 4.打开收藏夹节点需绑定以下属性:
 *  catchtap="showCollect"              {绑定点击事件}
 *  data-id="{{item.id}}"               {绑定商品 id}
 *  data-index="{{index}}"              {绑定当前位置.下标}
 *  data-isCollect="{{item.isCollect}}" {绑定是否收藏flag}
 *  例: <view class="add_heart" catchtap="showCollect" data-id="{{item.id}}" data-index="{{index}}" data-isCollect="{{item.isCollect}}"><text class="fa heart {{item.isCollect == 0 ? 'fa-heart-o' : 'fa-heart'}}"></text></view>
 *
 * 5.这样正常就可以使用了.hahaha~~
 */
let app = getApp();
import * as S_request from './requestService.js';
import * as utils from './util.js';
let collectCache = {}; //收藏夹缓存

let collect = {
    //打开收藏夹
    showCollect: function (_this, e) {
        let goods_id = e.currentTarget.dataset.id,
            isCollecct = e.currentTarget.dataset.iscollect,
            index = e.currentTarget.dataset.index;
        //检查用户是否登录
        if (!app.getUserInfo()) return false;
        //loading加载数据
        _this.setData({
            "loading.hidden": false,
            "collect.curGoodsIndex": index
        });
        if (isCollecct == 1) {
            //取消收藏
            this.cancelCollect(_this, goods_id);
            console.log('取消收藏');

        } else {
            //设置缓存防止多次请求后台,只有在新增'添加商品到收藏夹才会请求.
            if (!!collectCache) {
                S_request.colllet.getCollect((res) => {
                    collectCache = {data: res.data.data, goods_id: goods_id};
                    _this.setData({
                        "collect.data": collectCache,
                        "collect.actionSheetHidden": !_this.data.collect.actionSheetHidden
                    });
                    app.MLoading(_this, 1);
                });
            } else {
                _this.setData({
                    "collect.data": collectCache,
                    "collect.actionSheetHidden": !_this.data.collect.actionSheetHidden
                });
                app.MLoading(_this, 1);
            }
        }
    },
    //关闭收藏夹
    closeCollect: function (_this, e) {
        _this.setData({
            "collect.createCollectName": "",
            "collect.actionSheetHidden": !_this.data.collect.actionSheetHidden
        })
    },
    //创建收藏夹
    createCollect: function (_this, e) {
        let name = e.detail.value.name;
        _this.setData({
            "collect.createCollectName": name
        });
        S_request.colllet.createCollect(name, (res) => {
            let status = res.data.status;
            if (status == 0) {
                _this.setData({
                    "toast.hidden": false,
                    "toast.icon": "success",
                    "toast.msg": "创建成功"
                });
                e.currentTarget.dataset.goods_id = e.detail.value.goods_id;
                e.currentTarget.dataset.group_id = res.data.data.group_id;
                _this.selectCollect(e);
            } else {
                _this.setData({
                    "toast.hidden": false,
                    "toast.icon": "warn",
                    "toast.msg": "创建失败"
                });
            }
        })
    },
    //选择收藏夹收藏商品
    selectCollect: function (_this, e) {
        let goods_id = e.currentTarget.dataset.goods_id,
            group_id = e.currentTarget.dataset.group_id;

        S_request.colllet.makeCollect(goods_id, group_id, 1, 1, (res) => {
            let status = res.data.status;
            let curGoodsIndex = _this.data.collect.curGoodsIndex;
            let isArray = utils.isArray(_this.data.goodsData);
            let isCollectKey = isArray ? "goodsData[" + curGoodsIndex + "].isCollect" : "goodsData.isCollect";
            //let isCollectVal = isArray? !_this.data.goodsData[curGoodsIndex].isCollect : !_this.data.goodsData.isCollect;
            let obj = {
                "toast.hidden": false,
                "toast.icon": "success",
                "toast.msg": "收藏成功"
            };
            obj[isCollectKey] = 1;
            if (status == 0) {
                _this.setData(obj);
                _this.closeCollect();
                collectCache = {}
            } else {
                _this.setData({
                    "toast.hidden": false,
                    "toast.icon": "warn",
                    "toast.msg": "收藏失败"
                });
            }
        })
    },
    //取消收藏
    cancelCollect: function (_this, goods_id) {
        _this.setData({
            "loading.hidden": false
        });
        S_request.colllet.makeCollect(goods_id, '', 1, 0, (res) => {
            let status = res.data.status;
            let curGoodsIndex = _this.data.collect.curGoodsIndex;
            let isArray = utils.isArray(_this.data.goodsData);
            let isCollectKey = isArray ? "goodsData[" + curGoodsIndex + "].isCollect" : "goodsData.isCollect";
            let obj = {
                "toast.hidden": false,
                "toast.icon": "success",
                "toast.msg": "取消成功",
                "loading.hidden": true
            };
            obj[isCollectKey] = 0;
            if (status == 0) {
                _this.setData(obj);
                collectCache = {}
            } else {
                _this.setData({
                    "toast.hidden": false,
                    "toast.icon": "warn",
                    "toast.msg": "取消失败",
                    "loading.hidden": true
                });
            }
        });
    }
};

module.exports = collect;