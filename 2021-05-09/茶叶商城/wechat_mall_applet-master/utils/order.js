const app = getApp()

function postBilling (data, resolve) {
  app.authRequest({
    method: 'POST',
    url: `${app.globalData.API_URL}/orders/create_applet_order`,
    data: data || {},
    header: { 'Content-Type': 'application/json'},
    success: resolve,
    fail: function(){}
  })
}

module.exports = {
  postBilling (data, resolve) {
    return postBilling(data, resolve)
  }
}