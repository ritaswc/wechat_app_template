import * as utils from './util.js';
import CONFIG from '../config.js';
let app = getApp();
let DOMIAN = CONFIG[CONFIG.WX_ENV];

const getUserId = () => {
    var userInfo = app.globalData.userInfo ? app.globalData.userInfo : app.getUserInfo();
    var user_id = userInfo ? userInfo.UserId : '';
    console.log('getUserId',user_id)
    return user_id;
};

let S_request = {
    index : {
        getGoodsList : function(page,cb){
            wx.request({
                header : utils.requestHeader(),
                url : DOMIAN.HOST.API_URL + '/Home/Api/getTab2V1',
                data : {
                    curPage : page,
                    pageNum : 10,
                    user_id : getUserId()
                },
                success : (res) => {
                    let data = res.data.data;

                    let goods = [],swiperData = [];
                    for(let i = 0; i < data.goods.length; i++){
                        goods.push({
                            id : data.goods[i].goods_id,
                            goods_img : data.goods[i].image,
                            goods_title : data.goods[i].title,
                            goods_desc : data.goods[i].description,
                            goods_price : data.goods[i].goods_price,
                            goods_del_price : data.goods[i].last_format_price
                        })
                    };
                    for(let i = 0; i < data.index_cover.length; i++){
                        if(data.index_cover[i] != null){
                            swiperData.push({
                                imgUrl : data.index_cover[i].cover_image
                            })
                        }
                    }
                    goods.statusCode = CONFIG.CODE.REQUESTSUCCESS;
                    typeof cb == "function" && cb(goods,swiperData);
                    console.log(res,data)
                },
                fail : (err) => {
                    err.statusCode = CONFIG.CODE.REQUESTERROR;
                    typeof cb == "function" && cb(err);
                    console.log(err)
                }
            })
        }
    },
    detail : {
        getGoods : function(id, cb){
            wx.request({
                header : utils.requestHeader(),
                url : DOMIAN.HOST.API_URL + "/Home/Api/getGoods",
                data : {
                    goods_id : id,
                    user_id : getUserId()
                },
                success : (res) => {
                    var data = res.data.data;
                    data.statusCode = CONFIG.CODE.REQUESTSUCCESS;
                    typeof cb == "function" && cb(data);
                    console.log(res,data)
                },
                fail : (err) => {
                    console.log('error',err);
                    err.statusCode = CONFIG.CODE.REQUESTERROR;
                    typeof cb == "function" && cb(err);
                }
            })
        },
        getMatchGoods : function(id, cb){
            wx.request({
                header : utils.requestHeader(),
                url :  DOMIAN.HOST.API_URL + "/Home/Api/getMatchGoods",
                data : {
                    goods_id : id,
                    user_id : getUserId()
                },
                success : (res) => {
                    var data = res.data.data.goods_list;
                    typeof cb == "function" && cb(data);
                    console.log(res,data)
                },
                fail : (err) => {
                    console.log('error',err);
                }
            })
        },
        getSameGoods : function(id, cb){
            wx.request({
                header : utils.requestHeader(),
                url : DOMIAN.HOST.API_URL + "/Home/Api/getSameGoods",
                data : {
                    goods_id : id,
                    user_id : getUserId()
                },
                success : (res) => {
                    var data = res.data.data.goods_list;
                    typeof cb == "function" && cb(data);
                    console.log(res,data)
                },
                fail : (err) => {
                    console.log('error',err);
                    //wx.navigateBack();
                }
            })
        }
    },
    brand : {
        getBrand : function(id, curPage, cb){
            wx.request({
                header : utils.requestHeader(),
                url : DOMIAN.HOST.API_URL + "/Home/Api/getBrandGoodsList",
                data : {
                    brand_id : id,
                    curPage : curPage,
                    pagenum : 20,
                    user_id : getUserId()
                },
                success : (res) => {
                    var data = res.data.data;
                    typeof cb == "function" && cb(data);
                    console.log(res,data)
                },
                fail : (err) => {
                    console.log('error',err);
                    wx.navigateBack();
                }
            })
        }
    },
    login : function(email, pwd, cb){
        wx.request({
            header : utils.requestHeader(),
            url : DOMIAN.HOST.API_URL + "/Home/Api/login",
            data : {
                email : email,
                pwd : pwd
            },
            success : (res) => {
                var data = res;
                typeof cb == "function" && cb(data);
                console.log(res,data)
            },
            fail : (err) => {
                console.log('error',err);
                wx.navigateBack();
            }
        })
    },
    colllet : {
        getCollect : function(cb){
            wx.request({
                header : utils.requestHeader(),
                url : DOMIAN.HOST.API_URL + "/Home/Api/getCollect",
                data : {
                    user_id : getUserId()
                },
                success : (res) => {
                    var data = res;
                    typeof cb == "function" && cb(data);
                    console.log(res,data)
                },
                fail : (err) => {
                    console.log('error',err);
                    //wx.navigateBack();
                }
            })
        },
        createCollect : function(name, cb){
            wx.request({
                header : utils.requestHeader(),
                url : DOMIAN.HOST.API_URL + "/Home/Api/createCollect",
                data : {
                    group_name : name,
                    user_id : getUserId()
                },
                success : (res) => {
                    var data = res;
                    typeof cb == "function" && cb(data);
                    console.log(res,data)
                },
                fail : (err) => {
                    console.log('error',err);
                    //wx.navigateBack();
                }
            })
        },
        makeCollect : function(goods_id, group_id = '', rec_type, attention, cb){
            wx.request({
                header : utils.requestHeader(),
                url : DOMIAN.HOST.API_URL + "/Home/Api/makeCollect",
                data : {
                    goods_id : goods_id,
                    rec_type : rec_type,
                    group_id : group_id,
                    attention : attention,
                    version : utils.requestHeader().version,
                    user_id : getUserId()
                },
                success : (res) => {
                    var data = res;
                    typeof cb == "function" && cb(data);
                    console.log(res,data)
                },
                fail : (err) => {
                    console.log('error',err);
                    //wx.navigateBack();
                }
            })
        }
    }
};
module.exports = S_request;