const app = getApp();
var utils = require('../../../utils/utils.js')
var wxParse = require('../../../wxParse/wxParse.js')

// 定义一个全局变量保存从接口获取到的数据，以免重复请求接口
var resut;
Page({

  /**
   * 页面的初始数据
   */
  data: {
    article:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // wx.showToast({ title: '玩命加载中', icon: 'loading', duration: 10000 });
    var that = this;
    //将字符串转换成对象
    var article = decodeURIComponent(options.pagesource)
    // var article ='<div id="content_box" ><header><h1 class="title single-title entry-title">HiddenMe &#8211; 快速隐藏 macOS 桌面所有图标、文件</h1><div class="post-info"> <span class="thecategory"><a href="https://www.appinn.com/category/mac/" title="View all posts in macOS">macOS</a></span> <span class="thetime updated"><i class="fa fa-clock-o"></i> <span>2020/03/07</span></span> <span class="theauthor"><i class="fa fa-user"></i> <span><a href="https://www.appinn.com/author/qingwa/" title="由青小蛙发布" rel="author">青小蛙</a></span></span> <span class="thecomment"><i class="fa fa-comments"></i> <a href="https://www.appinn.com/hiddenme-for-macos/#respond" itemprop="interactionCount">0</a></span></div></header><div class="post-single-content box mark-links entry-content"><p><a href="https://www.appinn.com/hiddenme-for-macos/" class="rank-math-link">HiddenMe</a> 是一款简单的 macOS 小工具，它能帮你快速隐藏桌面上的所有图标，还你一个清爽的屏幕，以用来欣赏、截图、录屏等。@Appinn</p><figure class="wp-block-image size-large"><img	src="https://img3.appinn.net/images/202003/hiddenme.jpg!o"><img	src="https://img3.appinn.net/images/202003/screenshot_2020-03-06_at_22_41_11.jpg!o"><img	src="https://img3.appinn.net/images/202003/screenshot-2020-03-06-at-22-56-35.jpg!o"><img src="https://img3.appinn.net/images/202003/screenshot-2020-03-06-at-22-56-35.jpg!o" alt="HiddenMe - 快速隐藏 macOS 桌面所有图标、文件 3" title="HiddenMe - 快速隐藏 macOS 桌面所有图标、文件 3"></noscript><figcaption>左一：HiddenMe</figcaption></figure></div><p>当 HiddenMe 的小图标上显示很多点的时候，意味着未隐藏图标；当小点变暗的时候，隐藏图标。</p><p>可以在 <a href="https://apps.apple.com/us/app/hiddenme-hide-desktop-icons/id467040476?mt=12" class="rank-math-link" target="_blank" rel="noopener">Mac App Store</a> 免费安装 HiddenMe。</p>'
  
    this.setData({
      article: article
    })
  
    //用于测试--
    // url ='https://recod.cn:8081/api/v2.0/topic/?pageNum=1&num=4&kind=娱乐'
      
        //成功获取数据后调用渲染函数使用this调用
        that.renderHtml()
        // WxParse.wxParse('article', 'html', that.data.article, that, 5);
      // wx.hideToast()

  },
  handlerGobackClick(delta) {
    utils.handlerClick.GobackClick(delta)
  },
  handlerGohomeClick() {
    utils.handlerClick.GohomeClick()
  },
  //渲染函数
  renderHtml:function(){
    var that=this
    var article = that.data.article
    
    wxParse.wxParse('article', 'html', article, that, 5);
  }
})