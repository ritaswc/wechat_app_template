Page({
    data: {
        typeTree: {},       // 数据缓存
          currType: 0 ,
       
         
            // 当前类型
     "types": [
    
            {
                "shopAddr":"飞马牌服饰",
                "name":"PUMA Kids",
               "typeId":"0",
            },   
            {
                "shopAddr":"飞马牌服饰",
                "name":"银泰",
               "typeId":"2",
            },
            {
                "name":"银泰西湖店",
               "typeId":"3",
            }, 
        ],
     "typeTree": [
         
            {
               'pic':"../../images/im.jpg",
                "shopAddr":"飞马牌服饰",
                "name":"PUMA Kids",
               "typeId":"1",
            },   
         
            {
               'pic':"../../images/im.jpg",
                "shopAddr":"飞马牌服饰",
                "name":"PUMA Kids",
               "typeId":"1",
            },   
                     
            {
               'pic':"../../images/im.jpg",
                "shopAddr":"飞马牌服饰",
                "name":"PUMA Kids",
               "typeId":"1",
            },   
        ],


    },
        
    onLoad (){
        var me = this;
        request({
            url: ApiList.goodsType,
            success: function (res) {
                me.setData({
                    types: res.data.data
                });
            }
        });
        this.getTypeTree(this.data.currType);
        this.setData({
      currType: 0,
      // parseInt(options.currentTab)
    });
    },
    tapType(e){
        const currType = e.currentTarget.dataset.typeId;
        this.setData({
            currType: currType
        });
        this.getTypeTree(currType);
    },
    // 加载品牌、二级类目数据
    getTypeTree (currType) {
        const me = this, _data = me.data;
        if(!_data.typeTree[currType]){
            request({
                url: ApiList.goodsTypeTree,
                data: {typeId: +currType},
                success: function (res) {
                    _data.typeTree[currType] = res.data.data;
                    me.setData({
                        typeTree: _data.typeTree
                    });
                }
            });
        }
    },
    onShareAppMessage: function () {
      return {
        title: '微信小程序联盟',
        desc: '最具人气的小程序开发联盟!',
        path: '/page/user?id=123'
      }
    },
})