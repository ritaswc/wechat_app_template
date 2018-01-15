//json转字符串
function stringToJson(data){
    return JSON.parse(data);
}
//字符串转json
function jsonToString(data){
    return JSON.stringify(data);
}
//map转换为json
function mapToJson(map) {
    return JSON.stringify(strMapToObj(map));
}
//json转换为map
function jsonToMap(jsonStr){
    return  objToStrMap(JSON.parse(jsonStr));
}
//map转化为对象（map所有键都是字符串，可以将其转换为对象）
function strMapToObj(strMap){
    var obj= Object.create(null);
    for (var[k,v] of strMap) {
        obj[k] = v;
    }
    return obj;
}
//对象转换为Map
function  objToStrMap(obj){
    var strMap = new Map();
    for (var k of Object.keys(obj)) {
        strMap.set(k,obj[k]);
    }
    return strMap;
}

module.exports = {
    stringToJson: stringToJson,
    jsonToString:jsonToString,
    mapToJson:mapToJson,
    jsonToMap:jsonToMap,
    strMapToObj:strMapToObj,
    objToStrMap:objToStrMap,
}