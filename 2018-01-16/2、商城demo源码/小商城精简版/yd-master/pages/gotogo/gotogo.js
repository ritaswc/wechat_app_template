import common from '../../common/app'
import { uniquePush, getLikesFromStorage, setLikesToStorage, removeLikesFromStorate, fetch, getShortCid, extend, throttle, isEmptyObject } from '../../utils/utils'
import API from '../../common/API'
/**
 * [page description]
 * @type {Object}
 *  逛一逛整体逻辑
 *   0. 创建一个队列，放置要进行动画的数据
 *   1. 从服务端拿到的数据放置到全局
 *     1.1 从本地取出历史上的“喜欢”数据
 *     1.2 从服务端拿到的数据若已经存在于上面的“喜欢”数据中，则过滤掉
 *   2. 从已经经过过滤操作的全局数据中拿2条数据
 *   3. 点击“喜欢” or “不喜欢”（若点击“喜欢”则放置到本地中的“喜欢列表中”）
 *     3.1 第一条数据卡片飞走
 *     3.2 飞走之后，偷偷地原路飞回，并把其zindex从1置为0
 *     3.3 飞回的卡片进行数据填充
 */

// 这个数字需要找到一个平衡点
// 如果太大的话，切换时会变卡，但是图片是提前加载出来的
// 如果太小的话，切换时不卡，但是图片可能正在下载...
const queueLength = 5
// 动画运动时间
const duration = 320
// 处理后的数据池指针。每次pointer都指向当前数据。
let pointer = queueLength - 1
let restLength = queueLength
// // 全局变量 --end
const page = {

  onLoad(){
    console.log('gotogo onload...');
    this.load()
  }

  ,viewAll(){
    const likes = wx.getStorageSync('likes')
    if(!likes || likes.length === 0){
      return wx.showModal({
        content: '暂时还没有喜欢的东西哦~',
        showCancel: false
      })
    }
    wx.navigateTo({url:`../all/all?key=likes`})
  }

  ,render(queue){
    console.log('render...');
    this.setData({ queue })
  }

  /**
   * [flag 是否需要处理返回参数]
   * @type {[Boolean]} 是否需要处理返回的参数
   */
  ,getReadInterval(flag = false){
    // debugger
    let read_interval = wx.getStorageSync('read_interval')
    const [start, end]  = read_interval
    const default_read_interval = [0, 0]
    if(!read_interval || isEmptyObject(read_interval) ){
      return default_read_interval
    }
    if(flag && (start === 0 || end === 0)){
      return default_read_interval
    }
    return read_interval.sort()
  }

  ,setReadInterval(read_interval = [0, 0]){
    // const [start, end] = read_interval
    wx.setStorage({ key: 'read_interval', data: read_interval.sort() })
  }

  ,createQueue(gotogos){
    this.setData({ gotogos })
    const queue = gotogos.slice(0, queueLength)
    console.log('createQueue...');
    console.log(queue)
    return queue
  }

  // ,filter(gotogos){
  //   console.log('filter...');
  //   const likes = getLikesFromStorage()
  //   // TODO
  //   return gotogos
  // }

  ,getDataFromServer(){
    console.log('getDataFromServer...');
    wx.showToast( { title: '玩命加载中',icon: 'loading', duration: 10000 } )
    const [start, end] = this.getReadInterval(true)
    const url = `${API.giftBrowser.url}/read_interval[0]=${start}&read_interval[1]=${end}`
    this.setData({loading: true})
    return fetch( url ).then(result => {
      const { errMsg, statusCode, data } = result
      const { meta_infos } = data
      // console.log(data);
      console.log(`${url}接口返回的数据：`, result);
      if(!meta_infos || meta_infos.length === 0){
        return wx.showToast({ title: '暂无数据~'})
      }
      const gotogos = meta_infos.map(meta_info => {
        meta_info.cid = getShortCid(meta_info.cid)
        return meta_info
      })
      this.setData({loading: false})
      wx.hideToast()
      return gotogos
    }).catch(result => {
      console.log(`${url}接口错误：`, result);
      this.setData({loading: false})
      wx.hideToast()
    })
  }

  ,animate(ani={rotate:-30, translateX:-400}){
    // const cids = this.data.cids
    // 如果到最后，提示用户并返回
    // if(pointer === cids.length - 1){
    //   wx.showToast({title: '已经到最后啦亲~', duration: 1000})
    //   return;
    // }
    const {rotate, translateX} = ani
    this.setData({
      loading: true,
      // currentCid,
      // 取消 scale 和 opaticy 增加动画流畅性
      animationData: wx.createAnimation({
                        timingFunction:'ease',
                        duration: duration
                    })
                      // .scale(1.5, 1.5)
                      .rotate(rotate)
                      // .translate3d(translateX,0,0)
                      .translateX(translateX)
                      // .opacity(0)
                      .step().export()
    })
    const {queue, gotogos} = this.data
    const ret = queue.shift()
    const gotogo = gotogos[++pointer]
    queue.push(gotogo)
    setTimeout(() => {
      this.setData({ queue, loading: false })
    }, duration)
    return ret
  }

  ,load(){
    // 为了防止页面缓存，每次刷新页面之后都会重置currentIndex
    pointer = queueLength - 1
    // this.renderByDataFromServer()
    // 拿到原始数据，然后过滤，然后生成队列，然后渲染
    this.getDataFromServer()
        // .then(this.filter)
        .then(this.createQueue)
        .then(this.render).catch(e => console.log(e))
  }

  ,shouldLoad(){
    console.log(this.data.gotogos.length);
    console.log(pointer);
    return this.data.gotogos.length - pointer <= restLength
  }

  /**
   * 喜欢和不喜欢需要做一下函数节流。
   * 防止用户点击过快
   */
  ,dislike(){

    const loading = this.data.loading
    if(loading){
      return console.log('dislike loading是true啦....');
    }

    if( this.shouldLoad() ){
      this.load()
    }

    const gotogo = this.animate()
    const [, end] = this.getReadInterval()
    this.setReadInterval([gotogo.gift_id, end])
  }

  ,like(){
    const loading = this.data.loading
    if(loading){
        return console.log('like loading是true啦....');
    }

    if( this.shouldLoad() ){
      this.load()
    }

    const {cid, title, thumb_image_url, price, gift_id} = this.animate({rotate:30,translateX:400})
    // 精简要存入本地的对象。只存需要的字段。
    const gotogo = {aid: cid, title, thumb_image_url, price, gift_id}
    let likes = wx.getStorageSync('likes')
    if( !likes ) {
      likes = [ gotogo ]
    } else {
      uniquePush( likes, gotogo, 'aid' )
    }
    wx.setStorage({ key: 'likes', data: likes })
    const [, end] = this.getReadInterval()
    this.setReadInterval([gotogo.gift_id, end])
  }
  ,onShareAppMessage: function () {
    return {
      title: '礼物挑选神器',
      desc: '寻找你的心动好礼'
    }
  }
}

// assign会进行递归拷贝
Object.assign(page, common)
Page(page)
