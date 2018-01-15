var app = getApp()
Page({
	data: {
		hidden:false,//控制加载框的显示与隐藏
		curNav:1,
		curIndex:0,
		cart:[],
		cartTotal:0,
		navList:[
			{
				id:1,
				name:'零食'
			},
			{
				id:2,
				name:'饮料'
			},
			{
				id:3,
				name:'保健'
			},
			{
				id:4,
				name:'茶酒'
			}
		],
		classifyList:[
			[
				{
					name:"达利园",
					price:38,
					num:1,
					id:1
				},
				{
					name:"旺旺",
					price:58,
					num:1,
					id:19
				},
				{
					name:"徐福记",
					price:88,
					num:1,
					id:2
				}
			],
			[
				{
					name:"豆奶",
					price:18,
					num:1,
					id:3
				},
				{
					name:"绿茶",
					price:58,
					num:1,
					id:4
				}
			],
			[
				{
					name:"安利",
					price:18,
					num:1,
					id:5
				},
				{
					name:"脑白金",
					price:8,
					num:1,
					id:6
				}
			],
			[	{
					name:"五粮液",
					price:18,
					num:1,
					id:7
				},
				{
					name:"茅台",
					price:8,
					num:1,
					id:8
				}]
		],
	
	},
	loadingChange () {//时间延迟，模仿加载
		setTimeout(() => {
			this.setData({
				hidden:true
			})
		},1000)
	},
	//选择分类
	selectNav (event) {//event.target.dataset. 获取事件中的数据
		let id = event.target.dataset.id,
			index = parseInt(event.target.dataset.index);
			self = this;
		this.setData({
			curNav:id,
			curIndex:index
		})
	},
	//选择分类对应的数据
	selectNavData (event) {
		let classifyid = event.currentTarget.dataset.classify;
		let flag = true;
		let	cart = this.data.cart;
		
		if(cart.length > 0){
			cart.forEach(function(item,index){
				if(item == classifyid){
					cart.splice(index,1);//splice(删除的位置，删除的数量)
					flag = false;
			
				}
			})
		}
		if(flag) cart.push(classifyid);//把classifyid数据添加到cart中
		this.setData({
			cartTotal:cart.length
		})
		this.setStatus(classifyid)
	},
	setStatus (classifyId) {
		let classifyList = this.data.classifyList;
		for (let classify of classifyList){
			classify.forEach((item) => {
				if(item.id == classifyId){
					item.status = !item.status || false
				}
			})
		}
		
		this.setData({
			classifyList:this.data.classifyList
		})
	},
	onLoad () {
		this.loadingChange()
	}
})