// pages/details/details.js
Page({
  data:{
    goodsInf:"",
    weight:"1kg",
    count:1,
    minusStatus:false,
    currentIndex:'',
    attrValIdx:[0,0,0,0,0],
    attr:['','','','',''],
    attrLength:'',
    goods_price:'',
    goods_price:'',
    goods_id:'',
    goods_biref:'',
    goodsDesc:'',
    scaleList:[],
    showDesc:true,
    loading:false,
    noDesc:true
  },
  specsTap:function(e){
    var that = this;
    var i = parseInt(e.target.dataset.num);
    if(that.data.attrValIdx[i] === e.target.dataset.index){   
    }else{
       if(parseInt(i)==0){
        that.setData( {  
          'attrValIdx[0]':parseInt(e.target.dataset.index),
          'attr[0].goods_attr_id':e.target.dataset.attrid,
          'attr[0].val':e.target.dataset.val,
          goods_price:e.target.dataset.price
        })
      }else if(parseInt(i)==1){
        that.setData( {  
          'attrValIdx[1]':parseInt(e.target.dataset.index),
          'attr[1].goods_attr_id':e.target.dataset.attrid,
          'attr[1].val':e.target.dataset.val
        })
      }else if(i==2){
        that.setData( {  
          'attrValIdx[2]':parseInt(e.target.dataset.index),
          'attr[2].goods_attr_id':e.target.dataset.attrid,
          'attr[2].val':e.target.dataset.val
        })
      }else if(i==3){
        that.setData( {  
          'attrValIdx[3]':parseInt(e.target.dataset.index),
          'attr[3].goods_attr_id':e.target.dataset.attrid,
          'attr[3].val':e.target.dataset.val
        })
      }else if(i==4){
        that.setData( {  
          'attrValIdx[4]':parseInt(e.target.dataset.index),
          'attr[4].goods_attr_id':e.target.dataset.attrid,
          'attr[4].val':e.target.dataset.val
        })
      }
    }   
  },
  bindMinus:function(){
    var that = this;
    var num = that.data.count;
    if(num>2){
      num--;
    }else if(num == 2){
      that.setData({
        minusStatus:false
      });
      num--;
    }
    that.setData({
      count:num
    })
  },
  bindPlus:function(){
    var that = this;
    var num = that.data.count;
    num++;
    that.setData({
      minusStatus:true
    })
    that.setData({
      count:num
    })
  },
  preOrder:function(){
    var that = this;
    var arr = [];
    for(var i=0;i<that.data.attrLength;i++){
      arr.push(that.data.attr[i].goods_attr_id)
    }
    wx.getStorage({
      key:"session",
      success: function(res){
        var session=res.data.session;
        var url = 'https://shop.llzg.cn/weapp/buynow.php?session_id='+session+'&goods_id='+that.data.goods_id+'&goods_num='+that.data.count;
        wx.request({
          url: url,
          data:{
            specs:arr
          },
          method: 'POST',
          header: {'content-type': 'application/x-www-form-urlencoded'},
          success: function(res){
            if(res.data.order_id){
              var url = '../myOrder/myOrder?order_id='+res.data.order_id;
              wx.navigateTo({
                url:url
              })
            }
          }
        })
      },
      fail: function(res) {
        console.log("用户登录未登录，获取地址失败!")
      }
    })
  },
  imgLoad:function(e){
    var that =this;
    if(parseInt(e.target.dataset.index) == parseInt(that.data.imgNum)){
      setTimeout(function(){
        that.setData({
          loading:true,
          showDesc:false
        })
      },1000)
    }
  },
  onLoad:function(options){
    wx.setNavigationBarTitle({
      title: "商品详情"
    })
    var that = this;
    wx.request({
      url: 'https://shop.llzg.cn/weapp/specification.php?goods_id='+options.id,
      data: {},
      method: 'POST',
      header: {'content-type': 'application/x-www-form-urlencoded'},
      success: function(data){
        if(data.data){
          var gd = data.data;
          that.setData({
            specifications:gd,
            attrLength:gd.length
          });
          for(var i=0;i<gd.length;i++){
            if(parseInt(i)==0){
              that.setData( {  
                'attr[0].goods_attr_id':gd[i].data[0].goods_attr_id,
                'attr[0].val':gd[i].data[0].attr_value,
                goods_price:gd[i].data[0].attr_price
              })
            }else if(parseInt(i)==1){
              that.setData( {  
                'attr[1].goods_attr_id':gd[i].data[0].goods_attr_id,
                'attr[1].val':gd[i].data[0].attr_value
              })
            }else if(parseInt(i)==2){
              that.setData( {  
                'attr[2].goods_attr_id':gd[i].data[0].goods_attr_id,
                'attr[2].val':gd[i].data[0].attr_value
              })
            }else if(parseInt(i)==3){
              that.setData( {  
                'attr[3].goods_attr_id':gd[i].data[0].goods_attr_id,
                'attr[3].val':gd[i].data[0].attr_value
              })
            }else if(parseInt(i)==4){
              that.setData({  
                'attr[4].goods_attr_id':gd[i].data[0].goods_attr_id,
                'attr[4].val':gd[i].data[0].attr_value
              })
            }
          }
        } 
      }
    })
    wx.request({
      url: 'https://shop.llzg.cn/weapp/showgood.php?act=good&goods_id='+options.id,
      data: {},
      method: 'POST',
      header: {'content-type': 'application/x-www-form-urlencoded'},
      success: function(data){   
        that.setData({
          goodsInf:data.data,
          goods_id:options.id
        })
      }
    })
    wx.request({
      url: 'https://shop.llzg.cn/weapp/goodsdesc.php?goods_id='+options.id,
      data: {},
      method: 'POST',
      header: {'content-type': 'application/x-www-form-urlencoded'},
      success: function(res){
        if(res.data.length == 0){
          that.setData({
              loading:true,
              noDesc:false,
              showDesc:true
            })
        }else if(res.data.length > 0){
          setTimeout(function(){
            that.setData({
              goodsDesc:res.data,
              imgNum:res.data.length-1
            })
          },1000)
        }          
      }
    })
  }
})