//index.js
function initSubMenuDisplay() { 
     return ['hidden', 'hidden', 'hidden'];
}

Page({
    data:{
        subMenuDisplay:initSubMenuDisplay(),
        
       
        store_list: [
      {
        text1: '店铺名称：xxx',
        text2: '店铺地址：xxx',
        text3: '营业时间：xxx',
        src:'../../images/img6.png'
      },
      {
        text1: '店铺名称：xxx',
        text2: '店铺地址：xxx',
        text3: '营业时间：xxx',
        src:'../../images/img7.png'
      },
      {
        text1: '店铺名称：xxx',
        text2: '店铺地址：xxx',
        text3: '营业时间：xxx',
        src:'../../images/img8.png'
      }
      ]
    },
    tapMainMenu: function(e) {//        获取当前显示的一级菜单标识
        var index = parseInt(e.currentTarget.dataset.index);        // 生成数组，全为hidden的，只对当前的进行显示
        var newSubMenuDisplay = initSubMenuDisplay();//        如果目前是显示则隐藏，反之亦反之。同时要隐藏其他的菜单
        if(this.data.subMenuDisplay[index] == 'hidden') {
            newSubMenuDisplay[index] = 'show';
        } else {
            newSubMenuDisplay[index] = 'hidden';
        }        // 设置为新的数组
        this.setData({
            subMenuDisplay: newSubMenuDisplay
        });
    },
    tapSubMenu: function(e) { 
    var index = parseInt(e.currentTarget.dataset.index);
    console.log(index);  // 隐藏所有一级菜单
    this.setData({ 
       subMenuDisplay: initSubMenuDisplay() 
    }); 
    }
    

});

