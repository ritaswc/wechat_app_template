var json = require('../../utils/address.js');
Page({
  data:{
    provinces:json.getProvinces(),
    citys:[],
    areas:[],
    provinceClass:"",
    cityClass:"",
    areaClass:"",
    consignee:'',
    mobile:'',
    area:'',
    def_addr:1,
    warnInf:'',
    address_id:'',
    add_address:false,
    session_id:''
  },

  //获取对应的城市
  getCitysByCode:function(code){
    var pro=code.substr(0,2);//获取省代码，截取前两位 340000->34
    var citysArray=json.getCitys();//获取所有市
    var citysArr=[];
    for(var index in citysArray){  //遍历找到该省下面的所有市
      if(citysArray[index].code.substr(0,2)==pro){
          citysArr.push(citysArray[index])
      }
    }
    this.setData({
        citys:citysArr
    })
  },

  //获取对应的县区
  getAreasByCode:function(code){
     var are=code.substr(0,4);//获取县区代码，获取截取前四位 340288->3402 
     var areasArray=json.getAreas();//获取所有县区
     var areasArr=[];
    for(var index in areasArray){ //遍历找到该市下面的所有县区
      if(areasArray[index].code.substr(0,4)==are){
          areasArr.push(areasArray[index])
      }
    }
    this.setData({
        areas:areasArr
    })
  },

  //统一方法bindchange
  change:function(e){
    var category=e.currentTarget.dataset.value;//获取方法带的参数
    var index=e.detail.value;//获取下标
    if(category=='province'){
      var province=this.data.provinces[index];
      this.setData({
        province:province,
        provinceClass:'actived',
        citys:[],
        city:'',
        cityClass:'',
        areas:[],
        area:'',
        areaClass:'',
      })
     this.getCitysByCode(province.code)
    }else if(category=='city'){
      var city=this.data.citys[index];
      this.setData({
        city:city,
        cityClass:'actived',
        areas:[],
        area:'',
        areaClass:''
      }),
      this.getAreasByCode(city.code);//给县区赋值
    }else if(category=='area'){
       var area=this.data.areas[index];//获取该县区
       this.setData({
        area:area,
        areaClass:'actived',
      })
    }
  },
  switchIcon:function(){
    var that = this;
    if(that.data.def_addr == 0){
      that.setData({
        def_addr:1,
      })
    }else{
      that.setData({
        def_addr:0,
      })
    }
  },
  consigneeBlur:function(e){
    this.setData({
      consignee:e.detail.value,
    })
  },
  mobileBlur:function(e){
    this.setData({
      mobile:e.detail.value,
    })
  },
  addrBlur:function(e){
    this.setData({
      address:e.detail.value,
    })
  },
  myToast:function(inf){
    var that = this;
    if(inf){
      that.setData({
        warnInf:inf
      });
      setTimeout(function(){
        that.setData({
          warnInf:'',
        });
      },2000);
    }
  },
  keepTap:function(){
    var that = this;
    var addrObj = {};
    var flag = false;
    addrObj.consignee = that.data.consignee;
    addrObj.mobile = that.data.mobile;
    addrObj.address = that.data.address;
    addrObj.def_addr = that.data.def_addr;
    addrObj.addr_code = that.data.area.code;
    //addrObj.address_id = this.data.address_id;
    if(!addrObj.consignee){
      that.myToast("请填写收货人姓名！");
      flag = false;
    }else if(/[^\u4e00-\u9fa5a-zA-Z]/.test(addrObj.consignee)){
      that.myToast("收货人只支持中文名或英文名");
      flag = false;
    }
    else if(/^[\u4e00-\u9fa5]{2,10}|[A-Za-z]{3,50}|[\u4e00-\u9fa5a-zA-Z]{3,50}$/.test(addrObj.consignee)){
      flag = true;
    }else if(/^[\u4e00-\u9fa5a-zA-Z]{1,2}$/.test(addrObj.consignee)){
      that.myToast("姓名过短，请填写正确的收货人姓名")
      flag = false;
    };
    if(flag){
      if(!addrObj.mobile){
        flag = false;
      }else if(/^((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))[0-9]{8}$/.test(addrObj.mobile)){
        flag = true;
      }else{
        that.myToast("手机号码格式不正确")
        flag = false;
      }
    };
    if(flag){
      if(!addrObj.addr_code){
        that.myToast("请选择一个地区")
        flag = false;
      }else{
        flag = true;
      }
    };
    if(flag){
      if(!addrObj.address){
        that.myToast("详细地址不能为空")
        flag = false;
      }else if(addrObj.address.length<4){
        that.myToast("详细地址过短，请填写正确的详细地址")
        flag = false;
      }else{
        flag = true;
      }
    }
    if(flag){
      if(that.data.add_address){
        wx.request({
          url:'https://shop.llzg.cn/weapp/showaddr.php?act=add'+'&session_id='+that.data.session_id,
          data: addrObj,
          method: 'POST', 
          header: {'content-type': 'application/x-www-form-urlencoded'},
          success: function(res) {
            if(res.data == 1){
              wx.showToast({
                title: '添加地址成功',
                icon: 'success',
                duration: 10000
              })
              setTimeout(function(){
                wx.hideToast()
              },2000) 
              wx.navigateBack({
                delta: 1
              })
            }else{
              that.myToast("添加地址失败")
            }    
          }
        });
      }else{
        var url='https://shop.llzg.cn/weapp/showaddr.php?act=update'+'&address_id='+that.data.address_id+'&session_id='+that.data.session_id;
        wx.request({
          url:url,
          data:addrObj,
          method: 'POST', 
          header: {'content-type': 'application/x-www-form-urlencoded'},
          success: function(res) {
            wx.showToast({
              title: '编辑地址成功',
              icon: 'success',
              duration: 10000
            })
            setTimeout(function(){
              wx.hideToast()
            },2000)
            wx.navigateBack({
              delta: 1
            })
          }
        });
      }
      //console.log(addrObj);
    }
  },
  deleteTap:function(){
    var that = this;
    if(that.data.def_addr){
      that.myToast("选定的默认地址不可删除~");
    }else{
      wx.request({
        url:'https://shop.llzg.cn/weapp/showaddr.php?act=delete&address_id='+that.data.address_id+'&session_id='+that.data.session_id,
        data: {},
        method: 'POST', 
        header: {'content-type': 'application/x-www-form-urlencoded'},
        success: function(res) {
          if(res.data=='删除成功'){
            wx.showToast({
              title: '删除地址成功',
              icon: 'success',
              duration: 10000
            })
            setTimeout(function(){
              wx.hideToast()
            },2000)  
            wx.navigateBack({
              delta: 1
            })
          }
        }
      });
    }
    
  },
  onLoad: function (options){
    wx.setNavigationBarTitle({
      title: "地址管理"
    })
    var that = this;
    //获取session
    wx.getStorage({
      key:"session",
      success: function(res){
        that.setData({
          session_id:res.data.session
        }) 
        //console.log(that.data.session_id)
        if(options.address_id){
          var url = 'https://shop.llzg.cn/weapp/showaddr.php?act=showupdate&address_id='+options.address_id+'&session_id='+that.data.session_id;
          //console.log(url)
          wx.request({
            url:url,
            data: {},
            header: {'content-type': 'application/json'},
            success: function(res) {
              
              var pca_code = res.data.addr_code;
              var def_addr = res.data.def_addr;
              var pro = '';
              var ci = '';
              var ar = '';
              //获取对应的省
              var proCode=pca_code.substr(0,2)+'0000';//获取省代码
              var provincesArray=json.getProvinces();
              for(var i in provincesArray){  
                if(provincesArray[i].code==proCode){
                    pro = provincesArray[i];
                }
              }
              that.getCitysByCode(pro.code)//给市赋值
              //获取对应的市
              var cityCode=pca_code.substr(0,4)+'00';//获取市代码
              var citysArray=json.getCitys();
              for(var j in citysArray){  
                if(citysArray[j].code==cityCode){
                    ci = citysArray[j];
                }
              }    
              that.getAreasByCode(ci.code);//给县区赋值
              //获取对应的区(县)
              var areasArray=json.getAreas();
              for(var k in areasArray){  
                if(areasArray[k].code==pca_code){
                    ar = areasArray[k];
                }
              }
              //console.log(addrData);
              that.setData({
                consignee:res.data.consignee,
                mobile:res.data.mobile,
                address:res.data.address,
                def_addr:res.data.def_addr,
                province:pro,
                city:ci,
                area:ar,
                provinceClass:'actived',
                cityClass:'actived',
                areaClass:'actived',
                address_id:options.address_id,
              })
            }
          });   
        }else if(options.add_address){
          that.setData({
            add_address:true
          })
        }
      },
      fail: function(res) {
        console.log("用户登录未登录，获取地址失败!")
      }
    })
    
    
  }
})