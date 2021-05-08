//app.js
App({
  onLaunch: function () {
    console.log('App Launch')
  },
  onShow: function () {
    console.log('App Show')
  },
  onHide: function () {
    console.log('App Hide')
  },
  globalData: {
    hasLogin: false
  },
  getData:function(len){
    var data = [
      {
        id:1,
        title:'男子吃旺旺雪饼运气没变旺 向多部门举报',
        url:'/pages/layout/result?title=navigate1',
        img:'http://img6.cache.netease.com/news/2016/9/25/20160925064001ca9a1.jpg'
      },
      {
        id:2,
        title:'14岁少年杀同学被判17年 检察院抗诉后改无期',
        url:'/pages/layout/result?title=navigate2',
        img:'http://k.sinaimg.cn/n/sports/transform/20160305/GTwE-fxqafhk7443442.jpg/w5709a2.jpg'
      },
      {
        id:3,
        title:'四川一护士拍患者妇检私密照 并传社交群"讨论"',
        url:'/pages/layout/result?title=navigate3',
        img:'http://img3.cache.netease.com/news/2016/9/25/20160925063916aed1f.jpg'
      },
      {
        id:4,
        title:'【悦悦来了】7成女性携HPV病毒？',
        url:'/pages/layout/result?title=navigate4',
        img:'http://img1.cache.netease.com/news/2016/9/26/201609260027512b2d1.png'
      },
      {
        id:5,
        title:'“篮球女孩”钱红艳里约残奥会圆梦，9年间她获得了国内无数个游泳冠军',
        url:'/pages/layout/result?title=navigate5',
        img:'http://cms-bucket.nosdn.127.net/c0f25e471e1c4063b74381866caaab6620160925085411.jpeg'
      },
      {
        id:6,
        title:'一个人奋斗没意思?易学有道陪你一起奋斗，国民大学士“大易”，知无不答!伴你学习，带你飞!!!',
        url:'/pages/layout/result?title=navigate6',
        img:'http://cms-bucket.nosdn.127.net/d5c7c82046d94188aa3eb400ddda58e020160925101704.jpeg'
      }
    ];

    var tmp = [];
    for(ndx=1;ndx<=len;ndx++){
      var item = data[parseInt(6*Math.random())];
      item.id = ndx;
      tmp.push(item);
    }
	  return tmp;
  },
  getShortData:function(len){
    var data = [
      {
        id:1,
        title:'曹操',
        url:'/pages/layout/result?title=navigate1',
        img:'http://img6.cache.netease.com/news/2016/9/25/20160925064001ca9a1.jpg'
      },
      {
        id:2,
        title:'刘备',
        url:'/pages/layout/result?title=navigate2',
        img:'http://k.sinaimg.cn/n/sports/transform/20160305/GTwE-fxqafhk7443442.jpg/w5709a2.jpg'
      },
      {
        id:3,
        title:'司马茅坑',
        url:'/pages/layout/result?title=navigate3',
        img:'http://img3.cache.netease.com/news/2016/9/25/20160925063916aed1f.jpg'
      },
      {
        id:4,
        title:'貂蝉小少妇',
        url:'/pages/layout/result?title=navigate4',
        img:'http://img1.cache.netease.com/news/2016/9/26/201609260027512b2d1.png'
      },
      {
        id:5,
        title:'赵云',
        url:'/pages/layout/result?title=navigate5',
        img:'http://cms-bucket.nosdn.127.net/c0f25e471e1c4063b74381866caaab6620160925085411.jpeg'
      },
      {
        id:6,
        title:'张飞',
        url:'/pages/layout/result?title=navigate6',
        img:'http://cms-bucket.nosdn.127.net/d5c7c82046d94188aa3eb400ddda58e020160925101704.jpeg'
      }
    ];

    var tmp = [];
    for(ndx=1;ndx<=len;ndx++){
      var item = data[parseInt(6*Math.random())];
      item.id = ndx;
      tmp.push(item);
    }
	  return tmp;
  }
})
