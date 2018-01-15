import common from '../../common/app'
import API from '../../common/API'
import { fetch } from '../../utils/utils'
const page = {
  onLoad(options){
    this.setData({load: false})
    console.log('article onload...');
    // wx.showToast({ title: '玩命加载中',icon: 'loading',duration: 300 })
    wx.showToast({  title: '玩命加载中',icon: 'loading', duration: 10000})
    // const url = `${API.getArticle.url}/${options.id || 8108}.html`
    const url = `${API.getArticle.url}/${options.id}.html`
    fetch(url).then(result => {
      const {errMsg, statusCode, data} = result
      if( errMsg === 'request:ok' && statusCode == 200 ){
        console.log(`${url}接口返回的数据：`,result);
        const {header, contents} = data
        this.setData({header,contents})
        this.title = header.title
      } else {
        console.log(`${url}接口失败：`,result);
      }
      wx.hideToast()
      this.setData({load: true})
    }).catch(result => {
      console.log(`${url}接口错误：`,result);
      this.setData({
        header: {banners: [],title: '有调机器人',  price: {type: 'datetime',value: '-0-0'},  author: {url: 'http://c.diaox2.com/cms/diaodiao/people/robot.jpg',value: '有调机器人'}},
        contents: [{type: 'p',value: '发生了错误，我们正在紧张地排查，请您换一篇文章阅读'}]
      })
      wx.hideToast()
      this.setData({load: true})
    })
  }

  // ,onHide(){
  //   this.setData({load: false})
  // }
  //
  // ,onUnload() {
  //   this.setData({load: false})
  // }

  ,onShareAppMessage: function () {
    return {
      title: this.title,
      desc: '分享自「礼物挑选神器」，送礼不用愁'
    }
  }

}
Object.assign(page, common)
Page(page)
