const common = {
  app: getApp(),
  /*
    TODO:
    1. onLoad 为什么不执行？
       答：因为common.js中已经写了onLoad函数，然后使用Object.assign合并page和common时，common的onLoad给page上的onLoad覆盖了
    2. 在app.js中ajax回调中把数据挂到app对象上，为什么在其他文件引用不到？
  */
  // showModal(info) {
  //   this.setData({
  //     modalHidden: false,
  //     info: info
  //   })
  // }
  // ,hideModal() {
  //   this.setData({
  //     modalHidden: true,
  //     info: ''
  //   })
  // }
  // 执行时机 onLod -> onShow -> onReady
  // ,onLoad() {
  //   console.log('onLoad')
  // }
  // ,onReady(){
  //   console.log('onReady')
  // }
  // ,onShow(){
  //   console.log('onShow')
  // }
  // ,onHide(){
  //   console.log('onHide')
  // }
  // ,onUnload(){
  //   console.log('onUnload');
  // }
}
export default common
