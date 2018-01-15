// pages/addaddr/addaddr.js
let util = require('../../common/common.js');
let URLINDEX=util.prefix();
let city = require('../../common/city.js');
let cityData=city.city1;
Page({
  data:{
    substate:false,
    cityData:cityData,
    showPicker:false,
    style1:"border-top:1rpx solid #eee",
    style2:"border-top:none",
    disbutton:true
  },
  choCity: function(){
      this.setData({
        showPicker:!this.data.showPicker
      })
    console.log(this.showPicker)
  },
  bindChange:function(e){
    const val = e.detail.value;
    console.log(e);
    this.setData({
      city: this.data.cityData[val[0]].children,
    })
    this.setData({
      area: this.data.city[val[1]].children,
    })
    this.setData({
      ptocho:this.data.cityData[val[0]].text,
      citcho:this.data.city[val[1]].text,
      arecho:this.data.area[val[2]].text,
      proid:this.data.cityData[val[0]].value,
      citid:this.data.city[val[1]].value,
      areid:this.data.area[val[2]].value,
    })
  },
  bindnameInput:function(e){
      var that=this;
        this.setData({
          name: e.detail.value
        })
      checkButton(that)
  },
  bindmobileInput:function(e){
      var that=this;
        this.setData({
          mobile: e.detail.value
        })
      checkButton(that)
  },
  bindaddrInput:function(e){
      var that=this;
        this.setData({
          addr: e.detail.value
        })
      checkButton(that)
  },
  bindzipInput:function(e){
      var that=this;
    this.setData({
      zip: e.detail.value
    })
      checkButton(that)
  },
  bindcardInput:function(e){
      var that=this;
        this.setData({
          card: e.detail.value
        })
      checkButton(that)
  },
  saveAddr:function(){
      var that=this;
    //编辑完成收货地址
      saveAdd(that);
  },
  onLoad:function(options){
    var that=this;
    wx.setNavigationBarTitle({
      title:options.title
    });
    //三级联动数据处理
    var pro=that.data.cityData;
    var city=[];
    var area=[];
    var addrList = wx.getStorageSync('addrList');
    pro.map(function(item){
        if(item.value==addrList.province){
          city=item.children;
          city.map(function(ite){
             if(ite.value==addrList.city&&ite.children){
               area=ite.children
             }else{
               if(city[0].children){
                 area=city[0].children
               }
             }
          })
        }else{
          city=pro[0].children;
          area=city[0].children
        }
    })
    this.setData({
        id:addrList.id,
        name:addrList.accept_name,
        mobile:addrList.mobile,
        addr:addrList.address,
        zip:addrList.zip,
        ptocho:addrList.province_val,
        citcho:addrList.city_val,
        arecho:addrList.area_val,
        proid:addrList.province,
        citid:addrList.city,
        areid:addrList.area,
        card:addrList.card?addrList.card:0,
        pro:pro,
        city:city,
        area:area
    })
  checkButton(that)
  }
})
function saveAdd(that){
    if(!(/^1[34578]\d{9}$/.test(that.data.mobile))){
        wx.showToast({
            title: '你输入的电话号码有误',
            duration: 1000
        });
        that.setData({
            mobile:''
        })
        return false;
    }else {
        var reg = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
        if (reg.test(that.data.card) === false) {
            wx.showToast({
                title: '你输入的身份证号码有误',
                duration: 1000
            });
            that.setData({
                card: ''
            });
            return false;
        }
    }
        wx.request({
            url: util.pre()+'/apic/address_add',
            data: {
                token:util.code(),
                id:that.data.id,
                accept_name:that.data.name,
                province:that.data.proid,
                city:that.data.citid,
                area:that.data.areid,
                address:that.data.addr,
                mobile:that.data.mobile,
                zip:that.data.zip,
                card:that.data.card
            },
            success: function(res){
                wx.navigateBack();
            }
        });
}
function checkButton(that){
    if(that.data.name&&that.data.proid&&that.data.addr&&that.data.mobile&&that.data.card){
        that.setData({
            disbutton:false
        })
    }else{
        that.setData({
            disbutton:true
        })
    }
}