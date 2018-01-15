var superagent = require('superagent');
var charset = require('superagent-charset');
charset(superagent);
var cheerio = require('cheerio');
var express = require('express');
var app = express();
var https = require('https');
var fs = require('fs');
var bodyParser = require('body-parser');

var urlencodeParser = bodyParser.urlencoded({extend: false});

app.use(express.static('public'));


var baseUrl = 'http://www.27270.com/star/';
const successCode = 0, failCode = -1;

function isEmpty(obj){
    for (let i in obj){
        return false;
    }
    return true;
}

function uniqueArr(arr) {
    var rs = [];
    for (var i in arr) {
        if (rs.indexOf(arr[i]) != -1) {
            rs.push(arr[i]);
        }
    }
    return rs;
}

function inArr(v, arr) {
    var rs = false;
    for (var i in arr) {
        if (arr[i] == v) {
            rs = true;
            break;
        }
    }
    return rs;
}

app.get('/', function(req, res){
    res.send('<h1>server started now!</h1>');
});

// app.get('/tags', function(req, res){
//     console.log("get tags");
//     res.header("Content-Type", "application/json; charset=utf-8");
//     superagent.get(baseUrl)
//     .charset('gb2312')
//     .end(function (err, sres) {
//         var items = [];
//         var hrefs = [];
//         if (err) {
//             console.log('ERR: ' + err);
//             res.json({code: failCode, msg: err, sets:items});
//             return;
//         }
//         var $ = cheerio.load(sres.text);
//         $('#container .tags span a').each(function (idx, element) {
//             var $element = $(element);
//             var hrefStr = $element.attr('href');
//             var cid = hrefStr.match(/\/a\/(\w+)\.htm[l]?/);
//             cid = isEmpty(cid) ? "" : cid[1];
//             if (!inArr(hrefStr, hrefs)) {
//                 hrefs.push(hrefStr);
//                 items.push({
//                     title : $element.text(),
//                     href : hrefStr,
//                     value : cid,
//                     is_show: true,
//                 });
//             }
//         });
//         res.send({code: successCode, msg: "", data:items});
//         console.log('types: ' + JSON.stringify(items));
//     });
// });

app.get('/pic', function(req, res){
    console.log("get pic");
    var type = req.query.type;
    // console.log("type: " + type);

    type = !isEmpty(type) ? type : 'nv';
    var route = '0_'+ type + '_0/list1.html';
    res.header("Content-Type", "application/json; charset=utf-8");
    superagent.get(baseUrl+route)
    .charset('gb2312')
    .end(function (err, sres) {
        if (err) {
            console.log('ERR: ' + err);
            // return next(err);
        }
        var $ = cheerio.load(sres.text);
        var items = [];
        $('.Bodyer .content ul li').each(function (idx, element) {
            var $element = $(element);
            var $subElement = $element.find('a img');
            var thumbImgSrc = $subElement.attr('src');
            var titleEle = $subElement.attr('alt');
            items.push({
                title : !isEmpty(titleEle) ? titleEle : '明星美图',
                href : $element.find('a').attr('href'),
                // largeSrc : isEmpty(thumbImgSrc) ? "" : thumbImgSrc.replace('limg', '01'),
                largeSrc : thumbImgSrc,
                thumbSrc : thumbImgSrc,
                smallSrc : thumbImgSrc,
            });
        });
        res.json({code: successCode, msg: "", data:items});
        console.log('girls data: ' + JSON.stringify(items));
    });
});

app.get('/detail', function(req, res){
    console.log("get pic detail");
    var cid = req.query.d;

    // console.log("cid: " + cid);

    var route = 'http://www.27270.com/ent/mingxingtuku/2015/';
    res.header("Content-Type", "application/json; charset=utf-8");
    // console.log(baseUrl+route);

    console.log(route + cid + '.html');
    superagent.get(route + cid + '.html')
    .charset('gb2312')
    .end(function (err, sres) {
        if (err) {
            console.log('ERR: ' + err);
            // return next(err);
        }
        var $ = cheerio.load(sres.text);

        var imgList = [];
        var title = "";
        var imgSrc = "";
    
        $('.articleV4Body p a img').each(function (idx, element) {
            var $element = $(element);
            imgSrc = $element.attr('src');
            imgList.push(
                imgSrc
            );
        });
        res.json({code: successCode, msg: "", title: !isEmpty(title) ? title : '明星美图', data:{imgList:imgList}});
    });
});

var server = app.listen(process.env.PORT || 8080, function(){
    var host = server.address().address;
    var port = server.address().port;
    console.log("应用实例，访问地址为：http://%s:%s",host,port);
})
