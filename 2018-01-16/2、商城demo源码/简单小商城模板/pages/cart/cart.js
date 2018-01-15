Page({
  data:{
    hiddening:false,
    goods:[
        {"name":"青菜","pz":"品种:小青菜 产地:四川","imgUrl":"../../image/dining-1-2.jpg","price":"￥8.90","imgSelected":"../../image/s2.png","delImg":"../../image/icon-delete.png"},
        {"name":"羊肉","pz":"品种:肥羊卷 产地:澳洲","imgUrl":"../../image/dining-18-2.jpg","price":"￥8.90","imgSelected":"../../image/s2.png","delImg":"../../image/icon-delete.png"},
        {"name":"猕猴桃","pz":"品种:黄心猕猴桃 产地:海南","imgUrl":"../../image/imported-14-1.jpg","price":"￥8.90","imgSelected":"../../image/s2.png","delImg":"../../image/icon-delete.png"},
        {"name":"红牛","pz":"品种:红牛 产地:中国","imgUrl":"../../image/drank-4.jpg","price":"￥8.90","imgSelected":"../../image/s2.png","delImg":"../../image/icon-delete.png"},
        {"name":"腰果","pz":"品种:丹迪腰果 产地:海南","imgUrl":"../../image/local-2-1.jpg","price":"￥8.90","imgSelected":"../../image/s2.png","delImg":"../../image/icon-delete.png"},
        {"name":"腰果","pz":"品种:丹迪腰果 产地:海南","imgUrl":"../../image/imported-12.jpg","price":"￥8.90","imgSelected":"../../image/s2.png","delImg":"../../image/icon-delete.png"},
        {"name":"腰果","pz":"品种:丹迪腰果 产地:海南","imgUrl":"../../image/imported-18.jpg","price":"￥8.90","imgSelected":"../../image/s2.png","delImg":"../../image/icon-delete.png"},
        {"name":"腰果","pz":"品种:丹迪腰果 产地:海南","imgUrl":"../../image/tea-11.png","price":"￥8.90","imgSelected":"../../image/s2.png","delImg":"../../image/icon-delete.png"},
        {"name":"腰果","pz":"品种:丹迪腰果 产地:海南","imgUrl":"../../image/tea-13-1.jpg","price":"￥8.90","imgSelected":"../../image/s2.png","delImg":"../../image/icon-delete.png"},
        {"name":"腰果","pz":"品种:丹迪腰果 产地:海南","imgUrl":"../../image/tea-17.jpg","price":"￥8.90","imgSelected":"../../image/s2.png","delImg":"../../image/icon-delete.png"},
        {"name":"腰果","pz":"品种:丹迪腰果 产地:海南","imgUrl":"../../image/tea-19.jpg","price":"￥8.90","imgSelected":"../../image/s2.png","delImg":"../../image/icon-delete.png"}]
  },
  selectState(e){
       let dish=e.currentTarget.dataset.dish;
       
       this.setStatus(dish)
  },
  setStatus(dishId){
      let dishes = this.data.goods;
      for (let dish of dishes){
        dish.forEach((item) => {
          if(item.id == dishId){
            item.status = !item.status || false
          }
        })
      }
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
  },
  onReady:function(){
    // 页面渲染完成
    var that=this;
    setTimeout(function(){
         that.setData({
           hiddening:true
         })
    },2000)
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
})