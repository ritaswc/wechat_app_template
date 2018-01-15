//上传图片到定制链
function uploadFile(filePath, success, fail) {
  wx.uploadFile({
    url: 'https://www.dingzhilian.com/common/uploadImg?type=3',//上传图片路径
    filePath: filePath,//微信图片路径
    name: 'image',//文件对应的 key , 开发者在服务器端通过这个 key 可以获取到文件二进制内容
    header: {
      'content-type': 'multipart/x-www-form-urlencoded;charset=UTF-8'
    }, // 设置请求的 header
    success: function (res) {
      console.log(res);
      var data = JSON.parse(res.data);
      if (res.statusCode == 200 && data.success) {
        typeof success == "function" && success(data.logoUrl);

      } else {
        typeof fail == "function" && fail(data.message);
      }
    },
    fail: function (res) {
      console.log(res);
      typeof fail == "function" && fail(res);
    }
  })
}

//发送请求到定制链
function requestSupply(method, params, success, fail) {
  wx.request({
    url: 'https://www.dingzhilian.com/weixin/' + method + params, //method为方法名,params为参数
    method: "GET",
    header: {
      'content-type': 'multipart/x-www-form-urlencoded;charset=UTF-8'
    },
    success: function (res) {
      console.log(res);
      if (res.statusCode == 200 && res.data.code == "0") {
        typeof success == "function" && success(res.data);
      } else {
        typeof fail == "function" && fail(res.data.message);
      }
    },
    fail: function (res) {
      console.log(res);
      typeof fail == "function" && fail(res);
    }
  })
}

//数组去重
function getArray(Arr) {
  var newArr = [Arr[0]];
  for (var i = 1; i < Arr.length; i++) {
    if (newArr.indexOf(Arr[i]) == -1) {//检测newArr数组里是否包含Arr数组的内容，==-1检索的字符串没有出现则为-1       
      newArr.push(Arr[i])//把Arr数组的第i项插入新数组           
    }
  };
  return newArr;//返回新数组newArr
}

module.exports = {
  uploadFile: uploadFile,
  requestSupply: requestSupply,
  getArray: getArray,
  ctx: "https://www.dingzhilian.com/upload_dz/",
  weixinCtx: "https://www.dingzhilian.com/upload_dz/weixin/"
}