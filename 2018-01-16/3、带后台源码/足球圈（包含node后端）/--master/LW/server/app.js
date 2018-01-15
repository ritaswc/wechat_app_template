var express = require('express');

var app = express();

app.get('/api/list',function(req,res){
   res.json({"List1": [{"imgSrc":"../../images/1_03.png","descripition":"小丸子啊小丸子","gender":0},
          {"imgSrc":"../../images/2_03.png","descripition":"小丸子丸子","gender":1},
          {"imgSrc":"../../images/3_03.png","descripition":"小丸三个子啊小丸子","gender":0},
          {"imgSrc":"../../images/1_03.png","descripition":"小丸三个子啊小丸子","gender":0},
          {"imgSrc":"../../images/4_03.png","descripition":"小丸子啊小丸子","gender":1},
          {"imgSrc":"../../images/2_03.png","descripition":"小丸子丸子","gender":1},
          {"imgSrc":"../../images/3_03.png","descripition":"小丸三个子啊小丸子","gender":0},
          {"imgSrc":"../../images/4_03.png","descripition":"小丸子啊小丸子","gender":1}],
          "List2": [{"imgSrc":"../../images/5_03.png","descripition":"效果","gender":0},
          {"imgSrc":"../../images/13.jpg","descripition":"地方官史蒂夫","gender":1},
          {"imgSrc":"../../images/14.jpg","descripition":"三方跟第三","gender":0},
          {"imgSrc":"../../images/13.jpg","descripition":"开奖号开始BASD","gender":0},
          {"imgSrc":"../../images/6_03.png","descripition":"该如何好推介会看见","gender":1},
          {"imgSrc":"../../images/5_03.png","descripition":"法规及点击","gender":1},
          {"imgSrc":"../../images/14.jpg","descripition":"工具工具房间风格","gender":0},
          {"imgSrc":"../../images/6_03.png","descripition":"法规及覆盖合计","gender":1}],
 		  "List3": [{"imgSrc":"../../images/9.jpg","descripition":"足球宝贝","gender":0},
          {"imgSrc":"../../images/11.jpg","descripition":"黑色丝袜诱惑","gender":1},
          {"imgSrc":"../../images/12.jpg","descripition":"足球美女暴露","gender":0},
          {"imgSrc":"../../images/10.jpg","descripition":"发送到法国","gender":0},
          {"imgSrc":"../../images/10.jpg","descripition":"多个风格","gender":1},
          {"imgSrc":"../../images/9.jpg","descripition":"法豆干点击","gender":1},
          {"imgSrc":"../../images/12.jpg","descripition":"豆干","gender":0},
          {"imgSrc":"../../images/11.jpg","descripition":"发根几号放假","gender":1}]})
})


app.listen('8080');