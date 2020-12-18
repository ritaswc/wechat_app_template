import S_request from '../../utils/requestService.js';

let app = getApp();
let [curPage, isLoadMore] = [1, true];

Page({
    data : {
        brand_info : {},
        brand_list : {},
        loading : {
            hidden : false
        },
        loadMoreToastHidden : true,
        showEndTip : false
    },
    onLoad : function(e){
        this.setData({
            systemInfo : app.getSystemInfo()
        });
        this.getBrand(e);
    },
    onShow : function(){
        console.log('显示');
    },
    onReady : function(){
        console.log('ready');
        //动态设置标题
        let NBTitle = 'brand_name' in this.data.brand_info;
        wx.setNavigationBarTitle({
            title : NBTitle ? this.data.brand_info.brand_name : "品牌浏览"
        })
    },
    //获取品牌信息
    getBrand : function(e){
        let brandid = e.id;
        S_request.brand.getBrand(brandid, curPage, (res) => {
            this.setData({
                brand_info : res.brand_info,
                brand_list : res.data,
                "loading.hidden" : true
            });
            curPage += 1;
            console.log(res);
        })
    },
    // 跳转详情页
    jump_detail_page : function(e){
        let goodsId = e.currentTarget.dataset.id;
        wx.navigateTo({
            url : '/pages/goodsDetail/detail?id=' + goodsId
        });
    },
    //加载更多商品
    loadMoreGoods : function(e){
        let brandid = e.currentTarget.dataset.id;
        if(!isLoadMore)return;
        this.setData({
            "loading.hidden" : false
        });
        S_request.brand.getBrand(brandid, curPage, (res) => {
            if(res.data == null){
                this.setData({
                    "loading.hidden" : true,
                    loadMoreToastHidden : false,
                    showEndTip  : true
                });
                isLoadMore = false;
                return;
            }

            this.setData({
                brand_list : [...this.data.brand_list,...res.data],
                "loading.hidden" : true
            });
            curPage += 1;
        })
    },
    loadMoreMsgTip : function(){
        this.setData({
            loadMoreToastHidden : true
        })
    }
});
