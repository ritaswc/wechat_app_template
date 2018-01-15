const API_URL = 'http://localhost:3000'

function getProducts (resolve) {
  wx.request({
    url: `${API_URL}/products`,
    header: { 'Content-Type': 'application/json' },
    success: resolve,
    fail: function(){}
  })
}

function getSlides (resolve) {
  wx.request({
    url: `${API_URL}/home_slides`,
    header: { 'Content-Type': 'application/json' },
    success: resolve,
    fail: function(){}
  })
}

function postBilling (data, resolve) {
  wx.request({
    method: 'POST',
    url: `${API_URL}/carts/billings`,
    data: data,
    header: { 'Content-Type': 'application/json'},
    success: resolve,
    fail: function(){}
  })
}

function getCategories (data, resolve, reject) {
  wx.request({
    url: `${API_URL}/products?type=${data}`,
    header: { 'Content-Type': 'application/json'},
    success: resolve,
    fail: reject
  })
}


module.exports = {
  getProducts (resolve) {
    return getProducts(resolve)
  },

  getSlides (resolve) {
    return getSlides(resolve)
  },

  postBilling (data, resolve) {
    return postBilling(data, resolve)
  },

  getCategories (data, resolve, reject) {
    return getCategories(data, resolve, reject)
  }
}
