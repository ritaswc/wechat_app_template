
//获取常用数据列表
function getExchange(callback)
{
     wx.request({
  url: 'http://op.juhe.cn/onebox/exchange/query?key=775b6f435f5967f55170ea58f5c40806',
  header: {
      'Content-Type': 'application/json'
  },
  success: function(res) {
    //console.log(res.data)
    callback(res.data.result.list)
  }
})
}


//获取货币列表
function getList(callback)
{
    wx.request({
  url: 'http://op.juhe.cn/onebox/exchange/list?key=775b6f435f5967f55170ea58f5c40806',
  header: {
      'Content-Type': 'application/json'
  },
  success: function(res) {
    //console.log(res.data.result)
    callback(res.data.result.list)
  }
})
}

//兑换
function currency(f,t,callback){
  wx.request({
  url: 'http://op.juhe.cn/onebox/exchange/currency?key=775b6f435f5967f55170ea58f5c40806&from='+f+'&to='+t,
  header: {
      'Content-Type': 'application/json'
  },
  success: function(res) {
    if(res.data.result==null)
    {
      callback(null)
    }else{
    callback(res.data.result[1])
    }
  }
})
}

module.exports = {
  getList: getList,
  getExchange: getExchange,
  currency: currency
}