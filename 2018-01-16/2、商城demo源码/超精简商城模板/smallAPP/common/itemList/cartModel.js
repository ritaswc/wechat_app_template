// 购物车模型
// 主要处理用户购买逻辑，同步数据到本地存储
function CartModel () {

}
CartModel.prototype = {
    constructor: CartModel,
    
    // 初始化
    init: function () {

    },

    // 购物车加车
    add: function ( e ) {
        console.log( e );
    },

    // 购物车减车
    sub: function () {

    },

    // 购物车删除
    remove: function () {

    },

    // 购物车清空
    empty: function () {

    },

    // 批量添加到购物车
    batchAdd: function () {

    },

    // 是否可添加到购物车
    canAdd: function () {

    },

    // storage key
    KEY: 'model-cart-buy-counter',

    // 保存到微信存储，异步进行
    setLocal: function ( data ) {
        wx.setStorage({
            key: this.KEY,
            data: data, 
            success: function () {

            },
            fail: function () {

            }
        })
    },

    // 从微信存储取数据，异步进行
    getLcal: function () {
        wx.getStorage({
            key: this.KEY,
            success: function () {

            },
            fail: function () {

            }
        })
    }
}
module.exports = new CartModel();