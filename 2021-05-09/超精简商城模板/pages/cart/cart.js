// 购物车页面
Page({
  data:{
    tabs: [
      {
        id: 1,
        name: '常温商品'
      }, {
        id: 2,
        name: '低温商品'
      }
    ],
    activeTab: 1, // 当前活跃的仓库
    showCheckBox: true, // 显示选择框
    // 商品列表
    itemList: [
      {
        sku_id: 10001,
        name: "可口可乐碳酸饮料330ml/听*24",
        boamTags: [
          {name: '畅销商品', color: '#19BEAE'}
        ],
        tags: [
          {name: '满减', color: '#19BEAE'},
          {name: '特价', color: '#A1D569'}
        ],
        errorCode: -10000, 
        errorInfo: '赠品不足', 
        checked: true 
      }, {
        name: "可口可乐碳酸饮料330ml/听*24可口可乐碳酸饮料330ml/听*24可口可乐碳酸饮料330ml/听*24", 
        errorCode: -10000, 
        errorInfo: '赠品不足', 
        checked: true 
      },
      {sku_id: 10002, name: "xxx", errorCode: 0,      errorInfo: '赠品不足', checked: false },
      {sku_id: 10003, name: "xxx", errorCode: -10000, errorInfo: '赠品不足', checked: true },
      {sku_id: 10004, name: "xxx", errorCode: 0,      errorInfo: '赠品不足', checked: false },
      {sku_id: 10005, name: "xxx", errorCode: -10000, errorInfo: '赠品不足', checked: true },
      {sku_id: 10006, name: "xxx", errorCode: -10000, errorInfo: '赠品不足', checked: true, disabled: true }
    ],
    testData: [
         {
        name: "可口可乐碳酸饮料330ml/听*24",
        boamTags: [
          {name: '畅销商品', color: '#19BEAE'}
        ],
        tags: [
          {name: '满减', color: '#19BEAE'},
          {name: '特价', color: '#A1D569'}
        ],
        errorCode: -10000, 
        errorInfo: '赠品不足', 
        checked: true 
      }, {
        name: "可口可乐碳酸饮料330ml/听*24可口可乐碳酸饮料330ml/听*24可口可乐碳酸饮料330ml/听*24", 
        errorCode: -10000, 
        errorInfo: '赠品不足', 
        checked: true 
      },
      {name: "xxx", errorCode: 0,      errorInfo: '赠品不足', checked: false },
      {name: "xxx", errorCode: -10000, errorInfo: '赠品不足', checked: true },
      {name: "xxx", errorCode: 0,      errorInfo: '赠品不足', checked: false },
      {name: "xxx", errorCode: -10000, errorInfo: '赠品不足', checked: true },
      {name: "xxx", errorCode: -10000, errorInfo: '赠品不足', checked: true, disabled: true }   
    ]
  },

  // 改变仓库
  changeTab: function ( e ) {
    this.setData({
      activeTab: e.target.dataset.tab
    });
    this.update(e.target.dataset.tab);
  },

  // 更新列表
  update: function ( tab ) {
    if ( tab == 1 ) {
      this.setData({itemList: this.data.testData});
    } else {
      this.setData({itemList: [
        {name: "xxx", errorCode: 0, errorInfo: '', checked: false },
        {name: "xxx", errorCode: 0, errorInfo: '', checked: true },
        {name: "xxx", errorCode: 0, errorInfo: '', checked: false },
        {name: "xxx", errorCode: 0, errorInfo: '', checked: true },   
      ]})
    }
  }
})