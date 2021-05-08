function formatTime(date) {
  var year = date.getFullYear()
  var month = date.getMonth() + 1
  var day = date.getDate()

  var hour = date.getHours()
  var minute = date.getMinutes()
  var second = date.getSeconds()


  return [year, month, day].map(formatNumber).join('/') + ' ' + [hour, minute, second].map(formatNumber).join(':')
}

function formatNumber(n) {
  n = n.toString()
  return n[1] ? n : '0' + n
}
// 热门城市
function hot_city(all,hot){
  for(var x in all ){ //============处理移动端,小程序去掉json键首尾0问题
    let tmp = all[x]
    if( x.length == 5 ){
        let tmpX = '0'+x;
        delete all[x];
        all[tmpX] = tmp;
    }
  }
  return hot.map(function(item){
      return {
        id : item,
        city : all[item]
      }
  })
}
// 按省份选择
function province(all,province){
  for(var x in all ){ //============处理移动端,小程序去掉json键首尾0问题
    let tmp = all[x]
    if( x.length == 5 ){
        let tmpX = '0'+x;
        delete all[x];
        all[tmpX] = tmp;
    }
  }
  return province.map(function(item){
    var id = item + '0000'
      return {
        id:id,
        province:all[id]
    }
  })
}
// 选择城市
function selectCity(all,id){
  var allIndex = [];
  for(var x in all ){//============处理移动端,小程序去掉json键首尾0问题
    let tmp = all[x]
    if( x.length == 5 ){
        let tmpX = '0'+x;
        delete all[x];
        all[tmpX] = tmp;
    }
  }
  for (var index in all) {
    allIndex.push(index)
  }
  var tempId = id.substring(0,2)
  var id = allIndex.filter(function(id){
      return RegExp('^'+tempId).test(id) && !/000$/.test(id);
  })
  return id.map(function(item){
    return {
      id:item,
      city:all[item]
    }
  })
}
//职位类型
function _type(post_type){
  for(var x in post_type ){  //=======处理移动端,小程序去掉json键首尾0问题
    let tmp = post_type[x]
    if( x.length == 3 ){
        let tmpX = '0'+x;
        delete post_type[x];
        post_type[tmpX] = tmp;
    }
  }
  var id=[];
  for(var i in post_type ) id.push(i);
  return id.sort().map(function(item){
    return {
        id:item,
        _type:post_type[item]
    }
  })
}
//职位小类
function postlist(all,id){
  for(var x in all ){  //=======处理移动端,小程序去掉json键首尾0问题
    let tmp = all[x]
    if( x.length == 3 ){
        let tmpX = '0'+x;
        delete all[x];
        all[tmpX] = tmp;
    }
  }
  var temId = id.substring(0,2);
  var allId = [];
  for( let id in all ) allId.push(id);
  var id = allId.filter(function(item){
      return RegExp('^'+temId).test(item);
  })
  return id.map(function(item){
    return{
      id:item,
      post:all[item]
    }
  })
}

//数组对象去重;
function quchong(arr,_type,all){
  let tt=[];
  for( let i=0;i<arr.length;i++ ){
        tt.push( arr[i].id )
  }
  let id =[];
   tt.map(function(item){
   if( id.indexOf(item) == -1 ){
    return id.push(item);
    }
  })
	return id.map(function(item){
    return {
      id:item,
      [_type]:all[item]
    }
  });
}
//筛选 对用id下数组对象的text
function changetext(allchild , id ,val){
		return allchild.map(function(it){
			return {
				id:it.id,
				_type:it._type,
				text:id == it.id ? val : it.text
			}
		})
	}
  //选择性别
  function selectSex(id){
    if( id == 2 ){
      return {
        id:id,
        val:'男'
      }
    }else if ( id == 1 ){
      return {
        id:id,
        val:'女'
      }
    }else if( id == 0 ){
      return {
        id:id,
        val:''
      }
    }
  }

  function  selectAction(all,id){  //更具id配城市

  for(var x in all ){//=======处理移动端,小程序去掉json键首尾0问题
    let tmp = all[x]
    if( x.length == 5 ){
        let tmpX = '0'+x;
        delete all[x];
        all[tmpX] = tmp;
    }
  }
    if( id ){
        return {
          id:id,
          current_location:all[id]
        }
    }else{
        return {
          id:id,
          current_location:'定位失败'
        }
    }
  }

  function selectJob(all,id){ //更具id配职位
    for(var x in all ){ //=======处理移动端,小程序去掉json键首尾0问题
      let tmp = all[x]
      if( x.length == 3 ){ 
          let tmpX = '0'+x;
          delete all[x];
          all[tmpX] = tmp;
      }
    }
      return{
        id:id,
        job:all[id]
      }
  }

  function secectDegree(id){
    let arr = ["不限", "初中", "高中", "中技", "中专", "大专", "本科", "硕士", "博士"];
    return{
      id:id,
      val:arr[id]
    }
  }
module.exports = {
  formatTime: formatTime,
  hot_city:hot_city,
  province:province,
  selectCity:selectCity,
  quchong:quchong,
  _type:_type,
  postlist:postlist,
  changetext:changetext,
  selectSex:selectSex,
  selectAction:selectAction,
  secectDegree:secectDegree,
  selectJob:selectJob
}
