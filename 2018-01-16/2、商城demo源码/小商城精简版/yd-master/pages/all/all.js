import common from '../../common/app'
import category from '../../common/category'
const loadingLength = 20
const loadingStart = 0

let pageLength = loadingLength
let start = loadingStart
const page = {
  onLoad(options) {
    console.log(options);
    // 来自gotogo or viewall
    const key = options.key || 'allRaiders'
    console.log('all onload...');
    // 那次重启页面都重置初始条件，否则在手机上会缓存这两个变量的值
    // 下次进来时，会以上次设置的值作为初始值
    pageLength = loadingLength
    start = loadingStart
    wx.getStorage({
      key:key,
      success: (result) => {
        console.log('获取本地存储allRaiders的数据：', result)
        this.loadNewPage(result.data)
      },
      fail(result){
        console.log('获取本地存储allRaiders错误：', result)
      }
    })
  }
  ,scrolltolower(){
    this.loadNewPage()
  }
  // 滚动到底部事件监听 -start
 ,loadNewPage(allArticles = this.allArticles){
   if(!allArticles || allArticles.length === 0 ) return;
   const end = start + pageLength
   const alreadyDisplayArticles = this.data.articles || []
   const shouldLoadArticles = allArticles.slice(start, end)
   const articles = alreadyDisplayArticles.concat(shouldLoadArticles)
   console.log("articles.length:",articles.length);
   console.log("allArticles.length:",allArticles.length);
   if(articles.length === allArticles.length){
     setTimeout(() => {
       this.setData({ done: true })
     }, 120)
   }else{
     this.setData({ done: false })
   }
   this.setData({ articles })
   this.allArticles = allArticles
   start += pageLength
 }
 // 滚动到底部事件监听 -end
}
Object.assign(page, common)
Page(page)
