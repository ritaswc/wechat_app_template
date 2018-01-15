// //获取应用实例
var app = getApp()

//最大拖拽距离
var dragright = 150;
//touchstart点
var startX = 0,startY = 0;
//touchmove差值
var distanceX = 0,distanceY = 0;
//touchstart指向的item的index
var index = 0;
//记录touchmove的方向
var dir = "";
Page({
    data:{
        prodlist : [{
            open: false,
            right:0,
            transition:"none",
            prodTitle : "手机",
            prodDes:'和谁都不必惊慌失措家豪华酒店房间号谁都舍不得',
            prodImg:'/image/common/icon_add_pic.png'
        },{
            open: false,
            right:0,
            transition:"none",
            prodTitle : "手机",
            prodDes:'和谁都不必惊慌失措家豪华酒店房间号谁都舍不得',
            prodImg:'/image/common/icon_add_pic.png'
        },{
            open: false,
            right:0,
            transition:"none",
            prodTitle : "手机",
            prodDes:'和谁都不必惊慌失措家豪华酒店房间号谁都舍不得',
            prodImg:'/image/common/icon_add_pic.png'
        },{
            open: false,
            right:0,
            transition:"none",
            prodTitle : "手机",
            prodDes:'和谁都不必惊慌失措家豪华酒店房间号谁都舍不得',
            prodImg:'/image/common/icon_add_pic.png'
        },{
            open: false,
            right:0,
            transition:"none",
            prodTitle : "手机",
            prodDes:'和谁都不必惊慌失措家豪华酒店房间号谁都舍不得',
            prodImg:'/image/common/icon_add_pic.png'
        }],
        tagArr:['','',''],
        prodItemHeight:262,
        searchHeight:110,
        orderFilterHeight:80,
        scrollHeight:1000,
        searchByFilter:false,
        searchByOrder:false
    },
    //touchstart
    startProdItem : function(e){
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        index = parseInt(e.currentTarget.id);
        var cardItem = this.data.prodlist[index];
        var cData = {};
        cData['prodlist['+index+'].transition'] = "none";
        this.setData(cData);
    },
    //touchmove
    moveProdItem : function(e){
        distanceX = e.touches[0].clientX - startX;
        distanceY = e.touches[0].clientY - startY;
        console.dir(distanceX +"--"+ distanceY);
        if((Math.abs(distanceY)-20) >= Math.abs(distanceX)){
            console.log("%c%s", "border-bottom:1px solid green;", "识别为竖直拖拽");
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            return;
        }else{
            if(distanceX < 0){
                console.log("%c%s","color:#d46e17;","左滑动")
                dir = "left";
                var opt = {
                    x : distanceX,
                    y : distanceY,
                    dir: "left"
                }
                this.dragCardItem(opt)
            }else if(distanceX > 0){
                console.log("%c%s","color:#9e25dc","右滑动")
                dir = "right";
                var opt = {
                    x : distanceX,
                    y : distanceY,
                    dir: "right"
                }
                this.dragCardItem(opt)
            }else if(distanceX == 0){
                console.log("%c%s","color:25dc55","未滑动")
            }
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            return;
        }
    },
    //drag
    dragCardItem : function(e){
        var cardItem = this.data.prodlist[index];
        var cData = {};
        if(e.dir == "right" && cardItem.right <= 1){
            console.log("到右边了")
            cData['prodlist['+index+'].right'] = 0;
            this.setData(cData);
            return;
        }else if(e.dir == "left" && cardItem.right >= dragright){
             console.log("到左边了")
             cData['prodlist['+index+'].right'] = dragright;
             this.setData(cData);
             return;
        }
        cData['prodlist['+index+'].right'] =(cardItem.right -= e.x);
        console.log(cardItem.right -= e.x)
        this.setData(cData);
    },
    //touchend
    endProdItem : function(e){
        startX = 0,startY = 0;
        distanceX = 0,distanceY = 0;
        var cardItem = this.data.prodlist[index];
        var cData = {};
        cData['prodlist['+index+'].transition'] = "all .2s linear";
        this.setData(cData);
        //向左
        if(dir == "left"){
            if(cardItem.right >= 35){
                cData['prodlist['+index+'].right'] = dragright;
                this.setData(cData);
            }else{
                cData['prodlist['+index+'].right'] = 0;
                this.setData(cData);
            }
        }
        //向右
        else if(dir == "right"){
            if(cardItem.right <= (dragright-35)){
                cData['prodlist['+index+'].right'] = 0;
                this.setData(cData);
            }else{
                cData['prodlist['+index+'].right'] = dragright;
                this.setData(cData);
            }
        }  
    },
    deleteItem : function(e){
        this.data.prodlist.splice(index,1);
        var cData = {};
        cData.prodlist = this.data.prodlist
        this.setData(cData);
        wx.showToast({
            title: "删除成功"
        })
    },
    onReady : function(){
        this.setScrollHeight();
        const _that = this;
        wx.onAccelerometerChange(function(res) {
            var ilist = _that.data.prodlist;
            var cData = {};
            //x轴手机以左边为边垂直于地面为（-1），向反则为（1）；
            // console.log(res.x)
            //y轴手机以底部为边垂直于地面为（-1），向反则为（1）；
            // console.log(res.y)
            //z轴正面朝上（-1），背面朝上（1），从四个方西由正向反都会是此值增加
            // console.log(res.z)
            if(Math.abs(res.y) < 0.7 && res.z > -0.6 && res.x < 0){
                //手机向左倾斜
                ilist.forEach(function(item,index){
                    item.open = true;
                    item.transition = "all .2s linear";
                })
                cData.prodlist = ilist;
                _that.setData(cData)
            }
            if(Math.abs(res.y) < 0.7 && res.z > -0.75 && res.x > 0){
                //手机向右倾斜
                ilist.forEach(function(item,index){
                    item.open = false;
                    item.transition = "all .2s linear";
                })
                cData.prodlist = ilist;
                _that.setData(cData)
            }
        })
    },
    // 按顺序搜索
    searchByOrder:function(){
        this.setData({
            searchByOrder:!this.data.searchByOrder,
            searchByFilter:false
        }) 
    },
    // 按过滤搜索
    searchByFilter:function(){
        this.setData({
            searchByOrder:false,
            searchByFilter:!this.data.searchByFilter
        })
    },
    confirmFilter:function(){
        this.setData({
            searchByOrder:false,
            searchByFilter:false
        })
    },
    //添加服务
    addProduct:function(){
        wx.navigateTo({
          url: '../edit-product/edit-product',
          success: function(res){
            // success
          },
          fail: function() {
            // fail
          },
          complete: function() {
            // complete
          }
        })
    },
    //编辑服务
    editService:function(){
        wx.navigateTo({
          url: '../edit-service/edit-service',
          success: function(res){
            // success
          },
          fail: function() {
            // fail
          },
          complete: function() {
            // complete
          }
        })
    },
    // 设置混动区域高度
    setScrollHeight:function(){
        const _this = this;
        const searchHeight = _this.data.searchHeight;
        const orderFilterHeight = _this.data.orderFilterHeight;
        const leftHeight = searchHeight*1 + orderFilterHeight;
        wx.getSystemInfo({
        success: function(res) {
            const scrollHeight = res.windowHeight-(leftHeight/res.pixelRatio).toFixed(2) ;
            _this.setData({
                scrollHeight:scrollHeight,
                    scrollHeight:scrollHeight
                })
            }
        })
    },
})


