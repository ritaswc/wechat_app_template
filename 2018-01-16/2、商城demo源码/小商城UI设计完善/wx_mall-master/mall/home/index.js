Page({
    data: {
        mask : false,
        cart : false,
        test : "",
        number : 1,
        slider : [
            {
                img : "http://o86ac3exs.bkt.clouddn.com/89336def-f1f5-4663-99ad-5a77a1c358f4.jpg",
                url : "../../pages/wx/index"
            },
            {
                img : "http://o86ac3exs.bkt.clouddn.com/fe0ac8a8-f4dd-43cc-88bd-d85864b7cb0d.jpg",
                url : "../../pages/wx/index"
            },
            {
                img : "http://o86ac3exs.bkt.clouddn.com/9c691f1d-b3c3-4d1d-99b5-142d797db4e3.jpg",
                url : "../../pages/wx/index"
            }
        ],
        category : [
            {
                img : "../../icon/category_logo.png",
                text: "分类",
                url : "../column/column"
            },
            {
                img : "../../icon/category_logo.png",
                text: "分类",
                url : "../column/column"
            },
            {
                img : "../../icon/category_logo.png",
                text: "分类",
                url : "../column/column"
            },
            {
                img : "../../icon/category_logo.png",
                text: "分类",
                url : "../column/column"
            },
            {
                img : "../../icon/category_logo.png",
                text: "分类",
                url : "../column/column"
            },
            {
                img : "../../icon/category_logo.png",
                text: "分类",
                url : "../column/column"
            },
            {
                img : "../../icon/category_logo.png",
                text: "分类",
                url : "../column/column"
            },
            {
                img : "../../icon/category_logo.png",
                text: "分类",
                url : "../column/column"
            }
        ],
        hot : [
            {
                url : "../../pages/wx/index",
                text: "热点轮播1"
            },
            {
                url : "../../pages/wx/index",
                text: "热点轮播2"
            },
            {
                url : "../../pages/wx/index",
                text: "热点轮播3"
            },
            {
                url : "../../pages/wx/index",
                text: "热点轮播4"
            }
        ],
        product : [
            {
                url : "../../pages/wx/index",
                img : "http://o86ac3exs.bkt.clouddn.com/2d1eb528-4f90-41e4-88bc-6187eed95533.jpg?imageView2/1/w/200/h/200",
                name: "漳州平和精品红心蜜柚（5斤装）原价：30",
                cost: "28"
            },
            {
                url : "../../pages/wx/index",
                img : "http://o86ac3exs.bkt.clouddn.com/82be799b-377b-4aa7-8444-a6aa8b378fa4.jpg?imageView2/1/w/200/h/200",
                name: "坤晖碧根果（500克装）",
                cost: "52"
            },
            {
                url : "../../pages/wx/index",
                img : "http://o86ac3exs.bkt.clouddn.com/6e2066a0-024f-468b-8bda-aa83dce8aa2c.jpg?imageView2/1/w/200/h/200",
                name: "澳洲进口脐橙（10个装4.2斤左右）原价：68",
                cost: "60"
            },
            {
                url : "../../pages/wx/index",
                img : "http://o86ac3exs.bkt.clouddn.com/0697a102-833d-4ea8-85b7-d3ae0effbfe7.png?imageView2/1/w/200/h/200",
                name: "澳洲进口带骨沙朗牛排（600克）",
                cost: "99"
            },
            {
                url : "../../pages/wx/index",
                img : "http://o86ac3exs.bkt.clouddn.com/0697a102-833d-4ea8-85b7-d3ae0effbfe7.png?imageView2/1/w/200/h/200",
                name: "澳洲进口手工牛排套餐（5种口味单片装）",
                cost: "138"
            },
            {
                url : "../../pages/wx/index",
                img : "http://o86ac3exs.bkt.clouddn.com/535de142-3dd1-4956-871f-6c6553e583cb.jpg?imageView2/1/w/200/h/200",
                name: "漳州平和极品三红蜜柚（5斤装）原价：38",
                cost: "33"
            },
            {
                url : "../../pages/wx/index",
                img : "http://o86ac3exs.bkt.clouddn.com/6c6caf04-411d-4375-a1cc-f307d8bf70f0.jpg?imageView2/1/w/200/h/200",
                name: "台湾小潘凤凰酥",
                cost: "125"
            },
            {
                url : "../../pages/wx/index",
                img : "http://o86ac3exs.bkt.clouddn.com/3d94e254-0cc1-49b2-8c83-d21341fa0675.jpg?imageView2/1/w/200/h/200",
                name: "洛川苹果（7斤装）",
                cost: "52"
            }
        ]
    },
    /*点击遮罩层/商品选择界面点击关闭按钮 隐藏商品选择*/
    maskHidden : function(){
        this.setData({
            mask : false,
            cart : false
        })
    },
    /*点击购物车 显示商品选择*/
    addCost : function(){
        this.setData({
            mask : true,
            cart : true
        })
    },
    /*选择完商品 点击确认*/
    cartSure : function(){
        this.setData({
            mask : false,
            cart : false
        })
    },
    /*增加商品数量*/
    up : function(){
        var number = this.data.number;
        number++;
        if(number >= 99){
            number = 99
        }
        this.setData({
            number : number
        })
    },
    /*减少商品数量*/
    down : function(){
        var number = this.data.number;
        number--;
        if(number <= 1){
            number = 1
        }
        this.setData({
            number : number
        })
    },
    /*手动输入商品*/
    import : function(e){
        var number = Math.floor(e.detail.value);

        if(number <= 1){
            number = 1
        }

        if(number >= 99){
            number = 99
        }

        this.setData({
            number : number
        })
    },


    test : function(e){
        console.log(this.data.test)
    }
});