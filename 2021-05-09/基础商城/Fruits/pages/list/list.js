// 字体tabtab切换
Page({
    data:{
        selected:true,
        selected1:false,
            items: [],
    slides: [],
    navs: [
{icon: "../../images/sp.png",
 name: "沙漠果",names:"￥59.9"},
{icon: "../../images/sp.png",
 name: "沙漠果",names:"￥59.9"},
{icon: "../../images/sp.png",
 name: "沙漠果",names:"￥59.9"},
{icon: "../../images/sp.png",     
name: "沙漠果",names:"￥59.9"},
{icon: "../../images/img9.jpg",     
name: "沙漠果",names:"￥59.9"},
{icon: "../../images/sp.png",     
name: "沙漠果",names:"￥59.9"},
{icon: "../../images/sp.png",     
name: "沙漠果",names:"￥59.9"},
{icon: "../../images/sp.png",     
name: "沙漠果",names:"￥59.9"}  
]
        },
    selected:function(e){
        this.setData({
           selected1:false,
            selected:true
        })
    },
    selected1:function(e){
        this.setData({
            selected:false,
            selected21:true
        })
    },
     selected1:function(e){
        this.setData({
            selected:false,
            selected31:true
        })
    },
     selected1:function(e){
        this.setData({
            selected:false,
            selected1:true
        })
    },
 
  
})