const AV = require('../../utils/av-weapp.js')

Page({
    data: {
        topCategories: [],
        subCategories: [],
        highlight:['highlight','',''],
        banner: ''
    },
    onLoad: function(){
        this.getCategory(null);
        // hard code to read default category,maybe this is a recommend category later.
        var category = AV.Object.createWithoutData('Category', '5815b0d5d203090055c24a19');
        this.getCategory(category);
        this.getBanner(category);
        this.setImageWidth();
        this.setSideHeight();
    },
    setImageWidth: function () {
        var screenWidth = getApp().screenWidth;
        var imageWidth = (screenWidth - 130) / 3 - 5;
        this.setData({
            imageWidth: imageWidth
        });
    },
    setSideHeight: function () {
        this.setData({
            sidebarHeight: getApp().screenHeight
        });
    },
    tapTopCategory: function(e){
        // 拿到objectId，作为访问子类的参数
        var objectId = e.currentTarget.dataset.objectId;
        // 查询父级分类下的所有子类
        var parent = AV.Object.createWithoutData('Category', objectId);
        this.getCategory(parent);
        // 设定高亮状态
        var index = parseInt(e.currentTarget.dataset.index);
        this.setHighlight(index);
        // get banner local
        this.getBanner(parent);

    },
    getCategory: function(parent){
        var that = this;
        var query = new AV.Query('Category');
        // 查询顶级分类，设定查询条件parent为null
        query.equalTo('parent',parent);
        query.ascending('index');
        query.find().then(function (categories) {
            if (parent){
                that.setData({
                    subCategories: categories
                });
            }else{
                that.setData({
                    topCategories: categories
                });
            }
        }).catch(function(error) {
        });
    },
    setHighlight: function(index){
        var highlight = [];
        for (var i = 0; i < this.data.topCategories; i++) {
            highlight[i] = '';
        }
        highlight[index] = 'highlight';
        this.setData({
            highlight: highlight
        });
    },
    avatarTap: function(e){
        // 拿到objectId，作为访问子类的参数
        var objectId = e.currentTarget.dataset.objectId;
        wx.navigateTo({
            url: "../../../../goods/list/list?categoryId="+objectId
        });
    },
    getBanner: function (parent) {
        var that = this;
        parent.fetch().then(function () {
            that.setData({
                banner: parent.get('banner').get('url') 
            });       
        });
    },
    showGoods: function () {
        wx.navigateTo({
            url: '../goods/detail/detail?objectId=5816e3b22e958a0054a1d711'
        });
    }
})