// /**
//  * Created by Wandergis on 2015/7/8.
//  * 提供了百度坐标（BD09）、国测局坐标（火星坐标，GCJ02）、和WGS84坐标系之间的转换
//  */

// //定义一些常量
// var x_PI = 3.14159265358979324 * 3000.0 / 180.0;
// var PI = 3.1415926535897932384626;
// var a = 6378245.0;
// var ee = 0.00669342162296594323;

// /**
//  * 百度坐标系 (BD-09) 与 火星坐标系 (GCJ-02)的转换
//  * 即 百度 转 谷歌、高德
//  * @param bd_lon
//  * @param bd_lat
//  * @returns {*[]}
//  */
// function bd09togcj02(bd_lon, bd_lat) {
//   var x_pi = 3.14159265358979324 * 3000.0 / 180.0;
//   var x = bd_lon - 0.0065;
//   var y = bd_lat - 0.006;
//   var z = Math.sqrt(x * x + y * y) - 0.00002 * Math.sin(y * x_pi);
//   var theta = Math.atan2(y, x) - 0.000003 * Math.cos(x * x_pi);
//   var gg_lng = z * Math.cos(theta);
//   var gg_lat = z * Math.sin(theta);
//   return [gg_lng, gg_lat]
// }

// /**
//  * 火星坐标系 (GCJ-02) 与百度坐标系 (BD-09) 的转换
//  * 即谷歌、高德 转 百度
//  * @param lng
//  * @param lat
//  * @returns {*[]}
//  */
// function gcj02tobd09(lng, lat) {
//   var z = Math.sqrt(lng * lng + lat * lat) + 0.00002 * Math.sin(lat * x_PI);
//   var theta = Math.atan2(lat, lng) + 0.000003 * Math.cos(lng * x_PI);
//   var bd_lng = z * Math.cos(theta) + 0.0065;
//   var bd_lat = z * Math.sin(theta) + 0.006;
//   return [bd_lng, bd_lat]
// }

// /**
//  * WGS84转GCj02
//  * @param lng
//  * @param lat
//  * @returns {*[]}
//  */
// function wgs84togcj02(lng, lat) {
//   if (out_of_china(lng, lat)) {
//     return [lng, lat]
//   }
//   else {
//     var dlat = transformlat(lng - 105.0, lat - 35.0);
//     var dlng = transformlng(lng - 105.0, lat - 35.0);
//     var radlat = lat / 180.0 * PI;
//     var magic = Math.sin(radlat);
//     magic = 1 - ee * magic * magic;
//     var sqrtmagic = Math.sqrt(magic);
//     dlat = (dlat * 180.0) / ((a * (1 - ee)) / (magic * sqrtmagic) * PI);
//     dlng = (dlng * 180.0) / (a / sqrtmagic * Math.cos(radlat) * PI);
//     var mglat = lat + dlat;
//     var mglng = lng + dlng;
//     return [mglng, mglat]
//   }
// }

// /**
//  * GCJ02 转换为 WGS84
//  * @param lng
//  * @param lat
//  * @returns {*[]}
//  */
// function gcj02towgs84(lng, lat) {
//   if (out_of_china(lng, lat)) {
//     return [lng, lat]
//   }
//   else {
//     var dlat = transformlat(lng - 105.0, lat - 35.0);
//     var dlng = transformlng(lng - 105.0, lat - 35.0);
//     var radlat = lat / 180.0 * PI;
//     var magic = Math.sin(radlat);
//     magic = 1 - ee * magic * magic;
//     var sqrtmagic = Math.sqrt(magic);
//     dlat = (dlat * 180.0) / ((a * (1 - ee)) / (magic * sqrtmagic) * PI);
//     dlng = (dlng * 180.0) / (a / sqrtmagic * Math.cos(radlat) * PI);
//     mglat = lat + dlat;
//     mglng = lng + dlng;
//     return [lng * 2 - mglng, lat * 2 - mglat]
//   }
// }

// function transformlat(lng, lat) {
//   var ret = -100.0 + 2.0 * lng + 3.0 * lat + 0.2 * lat * lat + 0.1 * lng * lat + 0.2 * Math.sqrt(Math.abs(lng));
//   ret += (20.0 * Math.sin(6.0 * lng * PI) + 20.0 * Math.sin(2.0 * lng * PI)) * 2.0 / 3.0;
//   ret += (20.0 * Math.sin(lat * PI) + 40.0 * Math.sin(lat / 3.0 * PI)) * 2.0 / 3.0;
//   ret += (160.0 * Math.sin(lat / 12.0 * PI) + 320 * Math.sin(lat * PI / 30.0)) * 2.0 / 3.0;
//   return ret
// }

// function transformlng(lng, lat) {
//   var ret = 300.0 + lng + 2.0 * lat + 0.1 * lng * lng + 0.1 * lng * lat + 0.1 * Math.sqrt(Math.abs(lng));
//   ret += (20.0 * Math.sin(6.0 * lng * PI) + 20.0 * Math.sin(2.0 * lng * PI)) * 2.0 / 3.0;
//   ret += (20.0 * Math.sin(lng * PI) + 40.0 * Math.sin(lng / 3.0 * PI)) * 2.0 / 3.0;
//   ret += (150.0 * Math.sin(lng / 12.0 * PI) + 300.0 * Math.sin(lng / 30.0 * PI)) * 2.0 / 3.0;
//   return ret
// }

// /**
//  * 判断是否在国内，不在国内则不做偏移
//  * @param lng
//  * @param lat
//  * @returns {boolean}
//  */
// function out_of_china(lng, lat) {
//   return (lng < 72.004 || lng > 137.8347) || ((lat < 0.8293 || lat > 55.8271) || false);
// }


//----------------------------------------------------

//////////////////////////////////////////
//////////////转换核心代码////////////////
//////////////////////////////////////////
var pi = 3.14159265358979324;
var a = 6378245.0;
var ee = 0.00669342162296594323;
var x_pi = 3.14159265358979324 * 3000.0 / 180.0;


//世界大地坐标转为百度坐标
function wgs2bd(lat, lon) {
  var wgs2gcjR = wgs2gcj(lat, lon);
  var gcj2bdR = gcj2bd(wgs2gcjR[0], wgs2gcjR[1]);
  return gcj2bdR;
}

function gcj2bd(lat, lon) {
  var x = lon, y = lat;
  var z = Math.sqrt(x * x + y * y) + 0.00002 * Math.sin(y * x_pi);
  var theta = Math.atan2(y, x) + 0.000003 * Math.cos(x * x_pi);
  var bd_lon = z * Math.cos(theta) + 0.0065;
  var bd_lat = z * Math.sin(theta) + 0.006;
  var result = [];
  result.push(bd_lat);
  result.push(bd_lon);
  return result;
}

function bd2gcj(lat, lon) {
  var x = lon - 0.0065, y = lat - 0.006;
  var z = Math.sqrt(x * x + y * y) - 0.00002 * Math.sin(y * x_pi);
  var theta = Math.atan2(y, x) - 0.000003 * Math.cos(x * x_pi);
  var gg_lon = z * Math.cos(theta);
  var gg_lat = z * Math.sin(theta);
  var result = [];
  result.push(gg_lat);
  result.push(gg_lon);
  return result;
}

function wgs2gcj(lat, lon) {
  var dLat = transformLat(lon - 105.0, lat - 35.0);
  var dLon = transformLon(lon - 105.0, lat - 35.0);
  var radLat = lat / 180.0 * pi;
  var magic = Math.sin(radLat);
  magic = 1 - ee * magic * magic;
  var sqrtMagic = Math.sqrt(magic);
  dLat = (dLat * 180.0) / ((a * (1 - ee)) / (magic * sqrtMagic) * pi);
  dLon = (dLon * 180.0) / (a / sqrtMagic * Math.cos(radLat) * pi);
  var mgLat = lat + dLat;
  var mgLon = lon + dLon;
  var result = [];
  result.push(mgLat);
  result.push(mgLon);
  return result;
}

function transformLat(lat, lon) {
  var ret = -100.0 + 2.0 * lat + 3.0 * lon + 0.2 * lon * lon + 0.1 * lat * lon + 0.2 * Math.sqrt(Math.abs(lat));
  ret += (20.0 * Math.sin(6.0 * lat * pi) + 20.0 * Math.sin(2.0 * lat * pi)) * 2.0 / 3.0;
  ret += (20.0 * Math.sin(lon * pi) + 40.0 * Math.sin(lon / 3.0 * pi)) * 2.0 / 3.0;
  ret += (160.0 * Math.sin(lon / 12.0 * pi) + 320 * Math.sin(lon * pi / 30.0)) * 2.0 / 3.0;
  return ret;
}

function transformLon(lat, lon) {
  var ret = 300.0 + lat + 2.0 * lon + 0.1 * lat * lat + 0.1 * lat * lon + 0.1 * Math.sqrt(Math.abs(lat));
  ret += (20.0 * Math.sin(6.0 * lat * pi) + 20.0 * Math.sin(2.0 * lat * pi)) * 2.0 / 3.0;
  ret += (20.0 * Math.sin(lat * pi) + 40.0 * Math.sin(lat / 3.0 * pi)) * 2.0 / 3.0;
  ret += (150.0 * Math.sin(lat / 12.0 * pi) + 300.0 * Math.sin(lat / 30.0 * pi)) * 2.0 / 3.0;
  return ret;
}

module.exports = {
  // gcj02tobd09: gcj02tobd09,
  // wgs84togcj02: wgs84togcj02
  wgs2bd: wgs2bd,
  gcj2bd: gcj2bd
}