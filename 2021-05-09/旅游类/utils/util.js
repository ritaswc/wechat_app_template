// 发送请求
class View {
  constructor(url, method) {
    this.url = url;
    this.method = method;

  }
  sendhttp(url, method, fn) {
    wx.request({
      url: url,
      data: {},
      method: method, // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
      // header: {}, // 设置请求的 header
      success: function (res) {
        // success
        fn && fn(res);
      }
    })
  }
  send(cb) {
    this.cb = cb;
    this.sendhttp(this.url, this.method, this.getData.bind(this))
  }
  getData(res) {
    this.cb(res);
  }
}
// 处理评分
function star(score) {
  let arr = [];
  let intScore = parseInt(score);
  if (score != intScore) {
    score = score > intScore && score < (intScore + 0.5) ? Math.floor(score) : intScore + 0.5
  }

  for (let i = 1; i <= 5; i++) {
    // 全星
    if (i <=score) {
      arr.push(2)
      // 半星
    } else if (i - score == 0.5) {
      arr.push(1)
    } else {
      // 没有星
      arr.push(0)
    }

  }
  return arr
}
// 处理景区数据
function getViewData(allCity, cityName,fn) {
  let arr=[];
  // 获取城市的id
  for (let i = 0; i < allCity.length; i++) {
    if (allCity[i].cityName == cityName) {
      let provinceId = allCity[i].provinceId
      
      // 获取景区的的sid
      new View("http://70989421.appservice.open.weixin.qq.com/data/view.json", "get").send((res) => {
        // 搜索到相应的景区
        if (res.data.error_code == 0) {
          let data = res.data.result;
          arr=dealData(data, provinceId);
          fn&fn(arr);
        } else {
          // 没有获取到该地的景区
          //  此处调用查找全国景区api
          // 此处模拟数据全部置为四川
          new View("http://70989421.appservice.open.weixin.qq.com/data/view.json", "get").send((res) => {
            // 处理数据
            let data = res.data.result;  
           arr= dealData(data, 26);
              fn&fn(arr);
          })
        }
      })
    }
  }; 
}
function dealData(data, provinceId) {
  let resultArr = [];
  for (let i = 0, len = data.length; i < len; i++) {
    let dataIn = data[i];
    if (provinceId == dataIn.provinceId) {
      let item = {
        imgurl: dataIn.imgurl,
        title: dataIn.title,
        sid: dataIn.sid,
        now_price: dataIn.now_price,
        old_price: dataIn.old_price,
        address: dataIn.address,
        hot: dataIn.hot,
        free: dataIn.free,
        meal: dataIn.meal,
        desc: dataIn.desc,
        seller: dataIn.seller
      }
      resultArr.push(item)
    }
  }
  return resultArr;
}
function dateNow(){
  let arr=[];
  let date=new Date();
  let year=date.getFullYear();
  let month=date.getMonth()+1;
  let day=date.getDate();
  let week=date.getDay();
   switch(week){
      case 0: week=["日","一","二","三","四","五","六"];
      break;
      case 1: week=["一","二","三","四","五","六","日"];
      break;
      case 2: week=["二","三","四","五","六","日","一"];
      break;
      case 3: week=["三","四","五","六","日","一","二"];
      break;
      case 4: week=["四","五","六","日","一","二","三"];
      break;
      case 5: week=["五","六","日","一","二","三","四"];
      break;
      case 6: week=["六","日","一","二","三","四","五"];
      break;
    };
  for(let i=0;i<4;i++){
    arr.push({
        year:year,
        month:month,
        day:day+i,
        week:week[i]
    })
  }
  return arr;
   
}
export { View, star, getViewData,dateNow}


