Page({
    data: {
      // list 
      list: [
      {
        id: 'view',
        name: '用户体验设计/陈妍',
        open: false,
        pages: ['编辑', '查看', '删除']
      }, {
        id: 'content',
        name: '大数据与云计算/某某',
        open: false,
        pages: ['编辑', '查看', '删除']
      }
    ],
    },
    kindToggle: function (e) {
    var id = e.currentTarget.id, list = this.data.list;
    for (var i = 0, len = list.length; i < len; ++i) {
      if (list[i].id == id) {
        list[i].open = !list[i].open
      } else {
        list[i].open = false
      }
    }
    this.setData({
      list: list
    });
  },

    
});