var event=require('../../../utils/event')
Page({
  data:{
   pol:{
   }
  },
  onLoad:function(options){
 var lat=options.lat
 var lon=options.lon
 var name=options.name
 var adress=options.adress
 this.setData({
'pol.lat':lat,
'pol.lon':lon,
'pol.name':name,
'pol.adress':adress

 })
  },
  
 
})