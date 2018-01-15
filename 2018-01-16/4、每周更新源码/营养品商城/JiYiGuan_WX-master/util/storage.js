const constant = require("./constant.js");

const token_key = ('token_' + constant.version);
const product_key = ('product_' + constant.version);
const cart_key = ('cart_' + constant.version);

function getToken() {
    var token = wx.getStorageSync(token_key);
    return 'eyJhbGciOiJIUzUxMiJ9.eyJpYXQiOjE0OTMwMjA5NTAsImV4cCI6MTUyNDU1Njk1MCwiYXV0aG9yaXphdGlvbl9pZCI6IjE3YzdkZjI2MTUyMDQyOWY4ZWM1ODhmYmUzYTJlZmY0IiwidXNlcl9pZCI6IjRhMzgwYmNiYTRlMTRjNDViODFmOTg4MmI2M2RjMjlhIn0.XmoZ11WByhYW16ighnlDjIc580tviJzna5oYdayca6xKaaG92Pw7F0_sa2LyxuI7X9__x3gIbZKE0p4sTRa90g';
}

function setToken(token) {
    wx.setStorageSync(token_key, token);
}

function getProduct() {
    var product = wx.getStorageSync(product_key);

    if (product == "") {
        return [];
    }

    return JSON.parse(product);
}

function setProduct(product) {
    wx.setStorageSync(product_key, JSON.stringify(product));
}

function removeProduct() {
    wx.removeStorageSync(product_key);
}

function getCart() {
    var cart = wx.getStorageSync(cart_key);

    if (cart == '') {
        return [];
    }

    return JSON.parse(cart);
}

function setCart(cart) {
    console.log(cart);
    wx.setStorageSync(cart_key, JSON.stringify(cart));
}

function addCart(product) {
    var cartList = getCart();
    var isNotExit = true;

    for (var i = 0; i < cartList.length; i++) {
        var cart = cartList[i];

        if (cart.product_id == product.product_id) {
            isNotExit = false;

            console.log(product);

            cart.sku_id = product.sku_id;
            cart.product_name = product.product_name;
            cart.product_image = product.product_image;
            cart.product_price = product.product_price;
            cart.product_quantity.quantity = product.product_quantity.quantity + cart.product_quantity.quantity;
            cart.product_stock = product.product_stock;
        }
    }

    if (isNotExit) {
        cartList.push(product);
    }

    wx.setStorageSync(cart_key, JSON.stringify(cartList));
}

function removeCart() {
    wx.removeStorageSync(cart_key);
}

module.exports = {
    getToken: getToken,
    setToken: setToken,
    getProduct: getProduct,
    setProduct: setProduct,
    removeProduct: removeProduct,
    getCart: getCart,
    setCart: setCart,
    addCart: addCart,
    removeCart: removeCart
};