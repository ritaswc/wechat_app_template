const app = getApp();

Page({

    data: {
        width: app.systemInfo.windowWidth,
        goods: {
            id: '001',
            name: '千成婚礼三金刚2999',
            describe: '仅售2999元，价值3740元千成指定主持人、摄像师、化妆师！九年品质，匠心巨惠！',
            photo: [
                'http://p1.meituan.net/dpdeal/8d609a9ea0261af8f448341928a212b5276750.jpg%40450w_280h_1e_1c_1l%7Cwatermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p0.meituan.net/dpdeal/8d1e7ea4dd6c02e115580f3624bd0155868651.jpg%40450w_280h_1e_1c_1l%7Cwatermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p0.meituan.net/dpdeal/65f39aedc8519c05b07768fd09ef8dbb992006.jpg%40450w_280h_1e_1c_1l%7Cwatermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p0.meituan.net/dpdeal/2404b5aaf0a7138340a59ce88a2f68a2918239.jpg%40450w_280h_1e_1c_1l%7Cwatermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20',
                'http://p1.meituan.net/dpdeal/02dfd8330865f388c2786583058dd715532873.jpg%40450w_280h_1e_1c_1l%7Cwatermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20'
            ],
            service: [
                '随时退', '过期退'
            ],
            selled: 20,
            store: {
                id: '001',
                name: '成都千成婚礼（天府新区华阳店）',
                star: 4.6,
                starcount: 4,
                buztype: '婚庆公司',
                address: '天府新区益州大道588号益州国际写字楼10楼',
                phone: '13438138861',
                km: '14.1km',
            },
            group: {
                goods: [
                    {
                        id: '01',
                        name: '千成婚礼三金刚套餐',
                        count: '1套',
                        value: '3500',
                    },
                    {
                        id: '02',
                        name: '千成婚礼摄影',
                        count: '1套',
                        value: '1500',
                    }
                ],
                oldValue: '5000',
                grounpValue: '3500',
                describe: '1、迎宾区\n赠送签到背景10桁架搭建（规格：3*5m以内，仅限室内使用，室外建议搭建2.0桁架\n签到背景主题色系帷幔搭建，帷幔拉幔设计（规格：3*5\n仿真花艺排插 规格：（0.4*0.5m\n签到桌主题色系帷幔装饰\n签到桌蓬蓬纱装饰\n桌面水晶瓶插仿真花艺一个（规格：花艺直径40cm)\n迎宾签到薄一套\n签到笔两支\n喜火柴20包\n2、主舞台\n主舞台背景室内升降桁架搭建 （规格：3＊6以内）\n主背景主题色系帷幔打底，拉幔设计\n铁艺拱门设计 （规格：1.8*2.6m）\n仿真花艺排插 （规格：40cm直径）\n蝴蝶道具装饰\n3、通道区\n白色一次性PVC地毯 （规格：2.8*15m以内）\n主题色系地毯（规格：1.8*12m）\n水晶瓶搭配发散型仿真花艺8组（规格：方柱30*30*40cm，仿真花艺直径40cm）\n仿真花艺拱门设计（规格：仿真花艺直径40cm）\n4、道具及设备\n双轮泡泡机1台\n流光溢彩香槟塔一套\n5、其他\n新娘手捧花、手腕花、胸花一套（基本款）\n婚车扎花一套（基本款）\n新娘免费区婚纱一套\n新娘免费敬酒服一套\n伴娘服3套以内免费\n小花童服饰免费',
            },
            guide: [
                {
                    id: '01',
                    name: '有效期',
                    info: [
                        '2016-06-04至2017-06-03', '团购券请您于有效期内验证，验证后，使用有效期至2016-10-31'
                    ],
                },
                {
                    id: '02',
                    name: '除外日期',
                    info: [
                        '元旦、春节、劳动节、国庆节、清明节、中秋节、情人节、圣诞节不可用'
                    ],
                },
                {
                    id: '03',
                    name: '使用时间',
                    info: [
                        '10:00-18:00',
                    ],
                },
                {
                    id: '04',
                    name: '预约信息',
                    info: [
                        '请您提前2天预约，请您提前1天改约', '为保证您的消费体验，请您每天18:00前预约'
                    ],
                },
                {
                    id: '05',
                    name: '规则提醒',
                    info: [
                        '每张团购券只适用于1对新人使用', '每次消费您最多可用1张团购券', '需您当日一次性体验完毕所有项目', '每天最多接待1张团购券，建议您到店前先和商家确认', '不可指定婚礼摄影、摄像、跟妆、司仪等人员', '团购套餐内包含的服务项目不可随意更改，如需增加其他服务项目，则须根据商户标准报价付费', '可与套餐及定制型婚礼优惠同享，但不再与其他优惠同享'
                    ],
                },
                {
                    id: '06',
                    name: '温馨提示',
                    info: [
                        '客户在团购前须咨询商户档期，以免给您带来不必要的损失。', '如需团购券发票，请您在消费时向商户咨询', '为了保障您的权益，建议使用美团、点评网线上支付。若使用其他支付方式导致纠纷，美团、点评网不承担任何责任，感谢您的理解和支持！'
                    ],
                },
                {
                    id: '07',
                    name: '优惠规则',
                    info: [
                        '本单为特惠单，不参与团购积分赠送和使用'
                    ],
                },
            ],
            comments: [
                {
                    id: '01',
                    comment: '之前去吃过仁和春天酒店的餐饮， 觉得菜品还不错，把这家酒店介绍给了朋友，终于上月完成了她的婚礼， 现场布置的很漂亮，宴会厅很高，没有柱子，应该是很不错的厅。餐标2888起，中高端的婚宴酒店，服务和品质不错。交通方便，就在2环路边上。酒店比较新，应该是最近几年开业的，整体好评',
                    star: 5,
                    name: 'llyjessica',
                    cover: 'http://photos.breadtrip.com/avatar_1c_24_a9be5921a59d1743f019c14e5999f51c.jpg-avatar.l',
                    photo: [
                        'http://p0.meituan.net/wedding/d12fbff776107a7517a943dbd7ee9983161817.jpg%40249w_249h_0e_1l%7Cwatermark%3D0',
                        'http://p1.meituan.net/wedding/ab53686fab6d5bb6c7071cebdbf3719a216669.jpg%40249w_249h_0e_1l%7Cwatermark%3D0',
                        'http://p1.meituan.net/wedding/8b97535a84f2a65992b0888010261409157060.jpg%40249w_249h_0e_1l%7Cwatermark%3D0'
                    ]
                },
                {
                    id: '02',
                    comment: '上星期去参加了朋友的婚宴，第一次来人和春天酒店，地理位置很好在二环高架旁，酒店是很漂亮，提前进去看了布景，灯光打下来也很美，真的是很精致的婚礼💒。餐也不错，里面的服务态度就不说了很不错。好像是20多桌左右吧！人数还是不能容纳太多，觉得还挺不错的！推荐以后的婚礼和宴席都可以来。',
                    star: 4.4,
                    name: '挽挽是碗碗',
                    cover: 'http://photos.breadtrip.com/avatar_17_66_dbd2fafd1ba92b26a9c5b5498527f7b7b074323f.jpg-avatar.l',
                    photo: [
                        'http://qcloud.dpfile.com/pc/ZkJc7sTnfN6TPfh3uZRKk_JAboF2uokfXw_zGxiA84KxD7i2kM-BC_cjA8iaaz3Z.jpg',
                        'http://qcloud.dpfile.com/pc/toSx1AwA9IzWdiWibOpjMYuStN8-efxUhDYTyE3dzdYh8ta1TbMUc98hxtu-DcaE.jpg',
                        'http://qcloud.dpfile.com/pc/bjVgsQ-36JVg5I_l5zg4nzbYRgzz4lw5qd8ladcKZiuNI2YwUBqhZfAFjKB6H7OK.jpg',
                        'http://qcloud.dpfile.com/pc/toSx1AwA9IzWdiWibOpjMYuStN8-efxUhDYTyE3dzdYh8ta1TbMUc98hxtu-DcaE.jpg'
                    ]
                },
                {
                    id: '03',
                    comment: '婚庆是父母帮偶们挑选的（偶婚前一直不在上海，老公公司很忙 加上大老爷们一个，也知道要看什么……最重要他那品味，不敢相信！品味最好的一次是找到了偶）记得第一次妈妈和偶说去看婚庆了，看了一家离家有点距离的婚庆，每次去都要做半小时的车，我就直接说不要定了，换一家，太远！妈妈说婚博会上 他们家人最多，接待的小...',
                    star: 3.2,
                    name: 'vwvent',
                    cover: 'http://photos.breadtrip.com/avatar_8a_9a_fa4953d7fa5d031e5ae27a3977f30ddab7242482.jpg-avatar.l',
                    photo: [
                        'http://qcloud.dpfile.com/pc/lBypaosyaHwArZJJfoDicae2Nhdu70WB3htMUHpfT7XFZDTpY1jTPBtBiShMGWuC.jpg',
                        'http://qcloud.dpfile.com/pc/dLY2-bno1t0KVqTLTi-IYHGU966Lb4v2BXMhH1UvE9ymIBGsFPwHnH7n9H0hfTx-.jpg',
                        'http://qcloud.dpfile.com/pc/m88QI7_3DnpXNzeaVljSTikbJUnStd-qhP_6RpwQyFGBqt4zHfH7ZxAF-vPQPR3g.jpg'
                    ]
                },
                {
                    id: '04',
                    comment: '第一次去婚博会酒店还没选好，所以就留了个号码，选完酒店后就确认来喜上了。其实本人太懒了不高兴挑来挑去，看看喜上也算婚庆品牌，而且工作人员小冷又态度不错所以其他也不高兴看了。第一次去浦东接待定是anna，首先她带我了解了个大致流程和情况，这让云里雾里的我稍微安了点心啊……其次是确认套餐，所有的都可以在套餐里加减。我觉得满方便的，有任何问题都可以沟通，不过不要以为来一次就可以了，我来来回回也有4次吧！还有婚庆配套的化妆是宇涵造型的妹子，比我婚纱照化的好多了，这里表扬下。结婚当天我什么都不用考虑，会有工作人员提前进场，安排好一切。总之还是很满意的。感谢所有的工作人员。',
                    star: 4.8,
                    name: '程萍',
                    cover: 'http://photos.breadtrip.com/avatar_7e_99_8666810bacfb8677ef21065623b95139f8e43729.jpg-avatar.l',
                    photo: [
                        'http://qcloud.dpfile.com/pc/Fz4Td_w4bgVvSiWpda3UOE40huPPApbPqrpIMA4D0lsGKtZVVy10_IHGzJTdp2vy.jpg',
                        'http://qcloud.dpfile.com/pc/4NcbDPrK74X1TWTV0qWQYgweas83AXNFmF229Z4CJ4j2FNh46a0BHyi7kZsZ1SM1.jpg',
                        'http://qcloud.dpfile.com/pc/vh1QKGNYkTnN9QkxU5KRzN-5-e17r35zD8vsfxxOmBrntScy6jnu4wG_uQx59kh-.jpg'
                    ]
                },

            ]
        }
    },
    //广告栏
    banner(event) {
        const that = this;
        const index = event.currentTarget.dataset.index;
        wx.previewImage({
            current: that.data.goods.photo[parseInt(index)], // 当前显示图片的链接，不填则默认为 urls 的第一张
            urls: that.data.goods.photo,
        })

    },
    gobuy(event) {
        wx.showToast({
            title: '功能未做',


        })
    },

    callPhone(event) {
    wx.makePhoneCall({
          phoneNumber: '18581885527',
        })
    },
    location(event) {
        const that = this;
wx.openLocation({
  latitude: that.data.latitude, // 纬度，范围为-90~90，负数表示南纬
  longitude: that.data.longitude, // 经度，范围为-180~180，负数表示西经
  scale: 28, // 缩放比例
  name: '这是那儿哦', // 位置名
  address: '当前位置定位...', // 地址的详细说明
  success: function(res){
    // success
  },
  fail: function() {
    // fail
  },
  complete: function() {
    // complete
  }
})
    },


        onReady(){
        const that = this;
        console.log('onReady');
        wx.getLocation({
          type: 'wgs84', // 默认为 wgs84 返回 gps 坐标，gcj02 返回可用于 wx.openLocation 的坐标
          success: function(res){
            // success
            console.log(res);
            that.setData({
                latitude:res.latitude,
                longitude:res.longitude,
            })
          },
        })
    },
})