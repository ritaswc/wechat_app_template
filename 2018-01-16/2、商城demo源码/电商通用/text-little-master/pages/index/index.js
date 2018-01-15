//index.js
//获取应用实例
Page({
  data: {
    imgUrls: [
      'http://img2.xyyzi.com/Upload/images/20160726/57974a2d07674.jpg',
      'http://img2.xyyzi.com/Upload/images/20161218/58564c6a071c8.jpg',
      'http://img2.xyyzi.com/Upload/images/20161207/5847bac77e157.jpg'
    ],
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 500,
    circular: true,
    topPicData: [
      {
        id:'',
        pic:'http://img2.xyyzi.com/Upload/images/20160916/57db6edca2672.jpg'
      },
       {
        id:'',
        pic:'http://img2.xyyzi.com/Upload/images/20160916/57db93b5d85f1.jpg'
      },
       {
        id:'',
        pic:'http://img2.xyyzi.com/Upload/images/20160916/57dbaa52c8f5b.jpg'
      },
       {
        id:'',
        pic:'http://img2.xyyzi.com/Upload/images/20160916/57dbb3efeca7f.jpg'
      },
       {
        id:'',
        pic:'http://img2.xyyzi.com/Upload/images/20160916/57dbb57f7c374.jpg'
      },
       {
        id:'',
        pic:'http://img2.xyyzi.com/Upload/images/20160916/57dbb67f8af00.jpg'
      },
       {
        id:'',
        pic:'http://img2.xyyzi.com/Upload/images/20160916/57dbb758362ce.jpg'
      },
       {
        id:'',
        pic:'http://img2.xyyzi.com/Upload/images/20160916/57dbb7b715284.jpg'
      }
    ],
    mainPicData: [
      {
        cname:'男鞋',
        cid: '1',
        cates: [
          {
            cid: '31',
            pic: 'http://img2.xyyzi.com/Public/img/cat_img/31.jpg'
          },
           {
            cid: '31',
            pic: 'http://img2.xyyzi.com/Public/img/cat_img/31.jpg'
          },
           {
            cid: '31',
            pic: 'http://img2.xyyzi.com/Public/img/cat_img/31.jpg'
          },
           {
            cid: '31',
            pic: 'http://img2.xyyzi.com/Public/img/cat_img/31.jpg'
          }
        ],
        goods: [
          {
            gid: '6658',
            pic: 'http://img2.xyyzi.com/Upload/images/20160828/57c28da55b6b5.jpg?style=200x200',
            title: '【淘得6】Adidas阿迪达斯男鞋 2016新款训练鞋耐磨竞技网球鞋运动鞋AQ2279',
            nprice: '346.00',
            oprice: '499.00'
          },
           {
            gid: '6658',
            pic: 'http://img2.xyyzi.com/Upload/images/20160828/57c28da55b6b5.jpg?style=200x200',
            title: '【淘得6】Adidas阿迪达斯男鞋 2016新款训练鞋耐磨竞技网球鞋运动鞋AQ2279',
            nprice: '346.00',
            oprice: '499.00'
          },
           {
            gid: '6658',
            pic: 'http://img2.xyyzi.com/Upload/images/20160828/57c28da55b6b5.jpg?style=200x200',
            title: '【淘得6】Adidas阿迪达斯男鞋 2016新款训练鞋耐磨竞技网球鞋运动鞋AQ2279',
            nprice: '346.00',
            oprice: '499.00'
          },
           {
            gid: '6658',
            pic: 'http://img2.xyyzi.com/Upload/images/20160828/57c28da55b6b5.jpg?style=200x200',
            title: '【淘得6】Adidas阿迪达斯男鞋 2016新款训练鞋耐磨竞技网球鞋运动鞋AQ2279',
            nprice: '346.00',
            oprice: '499.00'
          }
        ]
      },
        {
        cname:'女鞋',
        cid: '2',
        cates: [
          {
            cid: '31',
            pic: 'http://img2.xyyzi.com/Public/img/cat_img/31.jpg'
          },
           {
            cid: '31',
            pic: 'http://img2.xyyzi.com/Public/img/cat_img/31.jpg'
          },
           {
            cid: '31',
            pic: 'http://img2.xyyzi.com/Public/img/cat_img/31.jpg'
          },
           {
            cid: '31',
            pic: 'http://img2.xyyzi.com/Public/img/cat_img/31.jpg'
          }
        ],
        goods: [
          {
            gid: '6658',
            pic: 'http://img2.xyyzi.com/Upload/images/20160828/57c28da55b6b5.jpg?style=200x200',
            title: '【淘得6】Adidas阿迪达斯男鞋 2016新款训练鞋耐磨竞技网球鞋运动鞋AQ2279',
            nprice: '346.00',
            oprice: '499.00'
          },
           {
            gid: '6658',
            pic: 'http://img2.xyyzi.com/Upload/images/20160828/57c28da55b6b5.jpg?style=200x200',
            title: '【淘得6】Adidas阿迪达斯男鞋 2016新款训练鞋耐磨竞技网球鞋运动鞋AQ2279',
            nprice: '346.00',
            oprice: '499.00'
          },
           {
            gid: '6658',
            pic: 'http://img2.xyyzi.com/Upload/images/20160828/57c28da55b6b5.jpg?style=200x200',
            title: '【淘得6】Adidas阿迪达斯男鞋 2016新款训练鞋耐磨竞技网球鞋运动鞋AQ2279',
            nprice: '346.00',
            oprice: '499.00'
          },
           {
            gid: '6658',
            pic: 'http://img2.xyyzi.com/Upload/images/20160828/57c28da55b6b5.jpg?style=200x200',
            title: '【淘得6】Adidas阿迪达斯男鞋 2016新款训练鞋耐磨竞技网球鞋运动鞋AQ2279',
            nprice: '346.00',
            oprice: '499.00'
          }
        ]
      }
    ]
  },
  onLoad: function () {
  
  }
})