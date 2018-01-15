var cartModel = require('cartModel.js');

var itemList = {
    // 购买加车
    add: function ( e ) {
        cartModel.add( e );
    },

    // 减车
    sub: function () {
        cartModel.sub();
    }

}



module.exports = itemList;