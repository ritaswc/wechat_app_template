import common from '../../common/app'
import { handleTitle, extractPriceFromPriceString, objectToQueryString, convert, type, fetch } from '../../utils/utils'
import API from '../../common/API'
import category, { defaultItem, ORDER_BY } from '../../common/category'
const keys = Object.keys(category)
const categorys = keys.map(item => category[item])
console.log("category:", category);
console.log("keys:", Object.keys(category));
console.log("categorys:", categorys);

const loadingLength = 20
const loadingStart = 0

let pageLength = loadingLength
let start = loadingStart
const ACTION_SHEET_LENGTH = 6

const page = {
  data: {
    categorys,
    // orderBy排序字段
    orderByActionSheetItems: [
      ORDER_BY.zonghe, // 综合排序
      ORDER_BY.latest, // 最新
      ORDER_BY.price_up_to_down, // 价格从高到低
      ORDER_BY.price_down_to_up // 价格从低到高
    ],
    // 当前默认是综合排序
    currentPX: 0
  }
  ,handleMoreTap(itemList, group){
    // this.setData({currentIndex:index})
    wx.showActionSheet({
      itemList: itemList,
      success: res => {
        if (!res.cancel) {
          category[group].selectedIndex = ACTION_SHEET_LENGTH - 1 + res.tapIndex
          this.setData({ categorys })
          this.renderByDataFromServer(this.packageQueryParam())
        }
        this.setData({currentIndex:-1})
      }
    })
  }
  // 顶部tap操作 --start
  ,switchSelectCond(e) {
    if(this.data.loading) return;
    console.log('switchSelectCond exec...');
    const group = e.currentTarget.dataset.group
    const cat = category[group]
    const index = keys.indexOf(group)
    this.setData({currentIndex:index})
    /**
     * 数据处理，由于小程序action-sheet组件只允许最多有6个item，
     * 但现在我们的筛选条件基本上都超过6个，所以，需要对数据进行处理
     */
    const len = ACTION_SHEET_LENGTH - 1
    const items = cat.items
    const categoryObject = convert(items)
    const itemList = items.slice(0, len)
    if(cat.name !== 'price'){
      itemList.push('更多')
    }
    wx.showActionSheet({
      itemList: itemList,
      success: res => {
        const tapIndex = res.tapIndex
        if(tapIndex === len){
          // 点击了跟多
          this.handleMoreTap(items.slice(len), group)
        }else{
          if (!res.cancel) {
            cat.selectedIndex = tapIndex
            this.setData({ categorys })
            this.renderByDataFromServer(this.packageQueryParam())
          }
          this.setData({currentIndex:-1})
        }
      }
    })
  }
    // 顶部tap操作 --end
  ,onLoad(options) {
    console.log('gift-result onload...');
    wx.showToast({ title: '玩命搜索中',icon: 'loading',duration: 10000 })
    this.setData({load: false})
    pageLength = loadingLength
    start = loadingStart
    this.renderByDataFromServer(this.packageQueryParam(options.queryParameter))
  }
   // 滚动到底部事件监听 -start
   ,scrolltolower(){
     this.loadNewPage()
   }
   ,loadNewPage(goods = this.goods, isOrderBy = false){
     if(!goods || goods.length === 0 ) return;
     const alreadyDisplay = this.data.goods || []
     //  如果是在 orderBy* 方法中调用的
     //  需要重置所有的条件
     if(isOrderBy){
       alreadyDisplay.length = 0 // 清空已渲染数据，从新build数据并渲染
       this.setData({ goods: [] })
       pageLength = loadingLength
       start = loadingStart
     }
     const metas = alreadyDisplay.concat(goods.slice(start, start + pageLength))
     if(metas.length === goods.length){
      //  当数据已经ready，但是页面还没有渲染出来
      //  就会马上出现“已经到底啦~”，为了防止这种情况，需要异步地设置 done 为 true
       setTimeout(() => {
         this.setData({ done: true })
       }, 120)
     }else{
       this.setData({ done: false })
     }
     this.setData({ goods: metas })
     this.goods = goods
     start += pageLength
   }
  // 滚动到底部事件监听 -end
  // ,search(){
  //   this.renderByDataFromServer(this.packageQueryParam())
  // }

  ,renderByDataFromServer(queryObject){
    const url = `${API.giftq.url}/${objectToQueryString(queryObject)}`
    this.setData({loading: true})
    fetch( {url,complete:() => {
        this.setData({loading: false})
        this.setData({load: true})
      }}).then(result => {
      console.log(`${url}返回的数据：`, result);
      // console.log(result);
      result = result.data
      const aids = result.aids
      // raiders 攻略
      let raiders = []
      // goods 单品
      let goods = []
      const reg = /http:\/\/|https:\/\//i
      const prefix = 'http://a.diaox2.com/cms/sites/default/files'

      for(let each of aids){
        const [ aid, type ] = each
        const meta_info = result[`meta_infos_${type}`][aid]
        if(!meta_info) {
          console.log('存在空的meta_info：')
          continue
        }
        const {ctype, thumb_image_url} = meta_info

        if( thumb_image_url && !reg.test(thumb_image_url) ){
          meta_info.thumb_image_url = `${prefix}/${thumb_image_url}`
        }

        meta_info.aid = aid
        meta_info.title = handleTitle(meta_info.title || meta_info.format_title)

        if( ctype == void 0 ){
          // console.log('SKU数据：', meta_info);
          meta_info.price_num = extractPriceFromPriceString(meta_info.price)
          const [ pic ] = meta_info.pics
          meta_info.thumb_image_url = pic.url
          goods.push(meta_info)
        }else if( ctype === 2 ){
          meta_info.price_num = extractPriceFromPriceString(meta_info.price)
          goods.push(meta_info)
        }
         else if(ctype !== 2 && ctype !== 3) // 过滤掉专刊数据（ctype === 3）
        {
          const rendered_keywords = meta_info.rendered_keywords
          if(rendered_keywords){
            meta_info.rendered_keywords  = rendered_keywords.map(keywords => keywords[0])
          }
          raiders.push(meta_info)
        }

      }

      // 在本地记录下所有攻略，以供查看“全部”
      wx.setStorage({key: "allRaiders",data: [...raiders]})
      // 攻略最多只有2篇
      if( raiders.length > 2){
        raiders = raiders.slice(0, 2)
      }
      // console.log('攻略数据：', raiders);
      /**
       * 1. ctype不准  不是不准，是文章的ctype应该是2
       * 2. remove_aids数据不全
       * 3. 单品无price过滤掉
       *    price: 'N/A'
       */
      // 单品至少有2篇
      // 不足2篇，remove_aids来补
      if( goods.length < 2 ){
        // 做一下非空判定
        // 鹏哲说如果全部命中，则remove_aids这个字段就没有值
        const aids = result.remove_aids || []
        // console.log(aids);
        for(let each of aids){
          const [ aid, type ] = each
          const meta_info = result[`meta_infos_${type}`][aid]
          // let meta_info = meta_infos[aid]
          // if(!meta_info) continue
          if(meta_info.ctype === 2){
            meta_info.price_num = extractPriceFromPriceString(meta_info.price)
            goods.push(meta_info)
          }else{
            console.log('done else');
          }
        }
      }
      this.loadNewPage( goods , true)
      // console.log('单品数据：', goods);
      this.setData({raiders, goods_copy: goods, currentPX: 0})
      wx.hideToast()
      // console.log(goods);
    }).catch(result => {
      console.log(`${API.giftq.url}接口错误：`,result)
      wx.hideToast()
    })
  }
  /**
   * 组装查询参数，共有3个地方调用
   *   1. 从首页 or 筛选页跳转到结果页         --onLoad中调用
   *   2. 切换category item的值              --bindItemTap中调用
   *   3. 在筛选页搜索框中输入内容，并触发搜索  --search中调用
   * 该函数做3件事情
   *   1. 组装参数
   *   2. 调用“切换顶部的tab显示内容”的函数
   *   3. 根据参数发送请求，并调用 renderByDataFromServer 函数
   */
  ,packageQueryParam(queryParameter){
    // console.log(queryParameter);
    // 取出上游页面传递过来的数据
    // 从首页传过来的数据
    // or 从礼物筛选页传过来的数据
    // queryParameterString = '{"relation": "妈妈", "scene": "新年", "category": "生活日用", "price": [500, 800]}'
    wx.showToast({ title: '玩命搜索中', icon: 'loading',duration: 10000 })
    //  组装参数
    let queryObject = {}
    if(queryParameter){ // 从index和filter过来的请求走第一个
      if(type(queryParameter) === 'string'){
        queryObject = JSON.parse(queryParameter)
      }else if(type(queryParameter) === 'object'){
        queryObject = queryParameter
      }
    }
     else // bindItemTap 和 search走这个
    {
      this.data.categorys.forEach((category) => {
        const selectedItem = category.items[category.selectedIndex]
        if( selectedItem !== defaultItem ){
          queryObject[category.name] = selectedItem
        }
      })
    }
    const query = queryObject.query || this.data.query
    if (query) {
      queryObject.query = query
      this.setData({query})
    }else{
      this.setData({query:''})
    }
    // 无需判断是否是空对象
    // if(isNullObject(queryObject)) return;
    return queryObject
  }
  // 查看全部 start
  ,viewAll(){
    wx.navigateTo({url:'../all/all'})
  }
  // 查看全部 end

  // 排序相关 start
  ,orderBy(){
    const itemList = this.data.orderByActionSheetItems
    this.setData({order:true})
    wx.showActionSheet({
        itemList: itemList,
        success: res => {
          if (!res.cancel) {
            this.orderByBindItemTap(itemList[res.tapIndex])
          }
          this.setData({order:false})
        }
    })
  }

  ,orderByBindItemTap(item){
    // 取出当前的排序规则
    let currentPX = this.data.currentPX
    // 根据选取的item确定下次的排序规则
    let nextPX = this.data.orderByActionSheetItems.indexOf(item)
    // 如果本次排序规则和下次排序规则一致，则关掉ActiveSheet，直接返回即可
    if( currentPX === nextPX ) return this.orderByHideActiveSheet();
    switch(item){
      case ORDER_BY.zonghe:
        this.orderByZonghe()
      break
      case ORDER_BY.latest:
        this.orderByLatest()
      break
      case ORDER_BY.price_up_to_down:
        this.orderByPrice()
      break;
      case ORDER_BY.price_down_to_up:
        this.orderByPrice('down_to_up')
      break;
    }
    this.setData({currentPX: nextPX})
  }

  ,orderByZonghe(){
    // 把最初的综合排序记住，直接恢复即可
    this.loadNewPage(this.data.goods_copy, true)
  }

  ,orderByLatest(){
    const goods = [...this.data.goods_copy]
    this.loadNewPage(goods.sort((prev, next) => next.latest_version - prev.latest_version), true)
  }

  ,orderByPrice(seq = 'up_to_down'){
    const goods = [...this.data.goods_copy]
    seq === 'up_to_down'?
     this.loadNewPage( goods.sort((prev, next) => next.price_num - prev.price_num), true):
     this.loadNewPage( goods.sort((prev, next) => prev.price_num - next.price_num), true)
  }

  // ,onHide(){
  //   this.setData({load: false})
  // }
  //
  // ,onUnload() {
  //   this.setData({load: false})
  // }
  // ,onShareAppMessage: function () {
  //   return {
  //     title: '礼物挑选神器 -- 筛选结果',
  //     desc: '找到最好的礼物'
  //   }
  // }
}
// 排序相关 end
Object.assign(page, common)
Page(page)
