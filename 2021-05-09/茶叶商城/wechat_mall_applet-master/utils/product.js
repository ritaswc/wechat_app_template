const app = getApp()

function getProducts (resolve) {
  app.request({
    url: `${app.globalData.API_URL}/products`,
    success: resolve,
    fail: function(){}
  })
}

function getProduct (id, resolve) {
  app.request({
    url: `${app.globalData.API_URL}/products/${id}`,
    success: resolve,
    fail: function(){}
  })
}

function getSlides (resolve) {
  app.request({
    url: `${app.globalData.API_URL}/home_slides`,
    success: resolve,
    fail: function(){}
  })
}

function getCategories (data, resolve, reject) {
  app.request({
    url: `${app.globalData.API_URL}/products?type=${data}`,
    success: resolve,
    fail: reject
  })
}


module.exports = {
  getProducts (resolve) {
    return getProducts(resolve)
  },

  getProduct (id, resolve) {
    return getProduct(id, resolve)
  },

  getSlides (resolve) {
    return getSlides(resolve)
  },

  getCategories (data, resolve, reject) {
    return getCategories(data, resolve, reject)
  }
}
