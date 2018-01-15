import common from '../../common/app'
const page = {
  onLoad(options){
    console.log('error onload...');
    const errorMsg = options.errorMsg || '发生了错误，请稍后重试~'
    this.setData({
      errorMsg: errorMsg
    })
  }
  ,toIndex(){
    setTimeout(() => {
      wx.navigateTo({redirect:true, url:'../index/index'})
    }, 120)
  }
}

Object.assign(page, common)
Page(page)
