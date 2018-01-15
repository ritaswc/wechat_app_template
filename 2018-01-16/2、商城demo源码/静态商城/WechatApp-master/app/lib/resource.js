import request from '../lib/request';
import serviceData from '../data/config';
import Promise from '../lib/promiseEs6Fix';

const host = 'https://service.mediamall.cccwei.com';
const authHost = 'https://service.mediamall.cccwei.com/users';
export default {
  //订单礼包
  fetchOrderList(type) {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:serviceData.orderData});
    });
    return $promise;

    let param = '';
    if (type) param = `?type=${type}`;
    return request({ url: urlFor(`/clientOrder${param}`) });
  },
  //取消订单
  cancalOrder(id) {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({ url: urlFor('/abolishment'), data: { out_trade_no: id }, method: 'post' });
  },
  //退款申请
  drawbackOrder(id) {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({ url: urlFor('/aftermarket'), data: { sub_out_trade_no: id      ,reason:'前端退款'}, method: 'post' });
  },
  confirmOrder(id) {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({ url: urlFor('/receipt'), data: { sub_out_trade_no: id      }, method: 'post' });
  },
  fetchAddresses() {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:serviceData.addressData});
    });
    return $promise;
    return request({ url: urlFor('/users/addresses') });
  },
  fetchDetailAddress(id) {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:serviceData.addressDetailData});
    });
    return $promise;
    return request({ url: urlFor(`/users/addresses/${id}`) });
  },
  postDetailAddress(id, data) {
    const url = id ? urlFor(`/users/addresses/${id}`) : urlFor('/users/addresses');
    const method = id ? 'put' : 'post';
    return request({ url, data, method });
  },
  deleteAddress(id) {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({ url: authUrlFor(`/addresses/${id}`), method: 'delete' });
  },
  setDefaultAddress(id) {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({ url: authUrlFor(`/addresses/${id}`), data: { is_default: true }, method: 'put' });
  },
  fetchCartIndex() {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:serviceData.carData});
    });
    return $promise;
    //return request({ url: urlFor('/cart/indexCart') });
  },
  updCartStatus(cartId) {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({ url: urlFor(`/cart/changeStatus/${cartId}`) });
  },
  updCartNumber(cartId, optType) {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({ url: urlFor(`/cart/changeNum/${cartId}?type=${optType}`) } );
  },
  delCartProduct(cartId) {
    //模拟请求数据
    var $puromise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({ url: urlFor(`/cart/delCart/${cartId}`),method:'delete' });
  },
  getShipping(shopId,code){
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({url : urlFor( '/getShippingFare?shop_id='+ shopId + '&suppliers_id=1&code=' + code)})
  },
  postOrder(data){
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({url : urlFor('/orders'), data:data,method:'post'})
  },
  getUserInfo(data){
    return {statusCode:200, data:serviceData.userData};
    return request({url : urlFor('/users/wxAppToken'), data:data,method:'get'})
  }, 
  getPaySign(data){
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({url : urlFor('/payment'), data:data,method:'post'})
  }, 
  updateUserInfo(data) {
    //模拟请求数据
    var $promise = new Promise(function(resolve,reject) {
      resolve({statusCode:200, data:{}});
    });
    return $promise;
    return request({url : urlFor('/users/user'), data:data,method:'put'})
  },
  successToast(callback) {
    wx.showToast({
      title: '成功',
      icon: 'success',
      duartion: '80000',
      success: callback()
    });
  },
  loadingToast() {
    wx.showToast({
      title: '设置中，请稍后',
      icon: 'loading'
    });
  },
  confirmToast(callback) {
    wx.showModal({
      title: '提示框',
      content: '确定要删除吗？',
      showCancel: true,
      success: (res) => {
        if (res.confirm) callback();
      }
    });
  },
  //提示框
  showTips(event, msg) {
     event.setData({
      toast: {
        toastClass: 'yatoast',
        toastMessage: msg
      }
    });
    setTimeout(() => {
      event.setData({
        toast: {
          toastClass: '',
          toastMessage: ''
        }
      });
    }, 2000);
  },
  getAuthUrl(path){
    return authHost + path;
  },
  getUrl(path){
    return host + path;
  },
};
function urlFor(path) {
  return host + path;
}
function authUrlFor(path) {
  return authHost + path;
}
