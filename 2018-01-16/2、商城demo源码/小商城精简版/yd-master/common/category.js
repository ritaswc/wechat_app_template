/**
 *  模块单例性
 *   定义好的模块，在其他一个页面
 */
export const defaultItem = '不限'
// 注意：iconPath并不是相对于当前文件的相对目录
// 而是用到icon字段的页面（filter.wxml）所相对的页面
const iconPath = './icon'
const categorys = {
  relation: {
    name: 'relation',
    icon:`${iconPath}/object.png`,
    title: '关系',
    // allTitle: '送礼对象', // 目前这个字段用不着
    items: [defaultItem, "爸爸","妈妈","老公男友","老婆女友","基友","闺蜜","朋友","同事","老板"],
    // items: [defaultItem, "爸爸","妈妈","老公男友","老婆女友","基友"],
    selectedIndex: 0 // 默认选中defaultItem即“不限”
  },
  scene: {
    name:'scene',
    title: '场景',
    // allTitle: '送礼场景',
    icon:`${iconPath}/scene.png`,
    items: [defaultItem, "圣诞节","新年","过年回家","情人节","生日","纪念日","告白","乔迁","新婚"],
    // items: [defaultItem, "圣诞节","新年","过年回家","情人节","生日"],
    selectedIndex: 0 // 默认选中defaultItem即“不限”
  },
  category: {
    name: 'category',
    icon:`${iconPath}/gifts.png`,
    title: '品类',
    // allTitle: '送礼品类',
    items: [defaultItem, "家具家装","生活日用","家用电器","办公用品","时尚悦己","个护化妆","科技数码","运动健康","母婴玩具","汽车户外"],
    // items: [defaultItem, "家具家装","生活日用","家用电器","办公用品","时尚悦己"],
    selectedIndex: 0 // 默认选中defaultItem即“不限”
  },
  price: {
    name: 'price',
    icon:`${iconPath}/price.png`,
    title: '价格',
    // allTitle: '礼物价格',
    items: [defaultItem, "0-200","200-500","500-800","800+"],
    selectedIndex: 0 // 默认选中defaultItem即“不限”
  }
}

// gift-result 礼物搜索页需要用到的排序字段
export const ORDER_BY = {
  zonghe:'综合排序',
  latest:'最新商品',
  price_up_to_down:'价格从高到底',
  price_down_to_up:'价格从低到高'
}

export default categorys
