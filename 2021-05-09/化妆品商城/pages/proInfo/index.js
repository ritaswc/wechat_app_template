import {rootUrl,rootUrl2} from '../../utils/params';
Page({
    data: {
        imgUrls: [],
        truePrice:'',
        title:'',
        info:'',
        specLst:[], //规格参数
        id:'',
        attr:'',
        num:1,
        onePrice: '',
        morePrice:'',
        infoImgs:[],
        indicatorDots: true,
        vertical: false,
        autoplay: true,
        interval: 3000,
        duration: 1000,
        
    },
     onShareAppMessage() {
        return {
        title: '佰露集微商城',
        desc: '一个专卖良心商品的小网站',
        path: `/pages/proInfo/index?id=${this.data.id}`
        }
    },
    querySpec(e){
       const attr = e.currentTaget.dataset.value;
       this.queryInfo({attr:attr})
    },
    inputVl(e){
        const num = e.detail.value;
        this.howMorePri(num)
    },
    add(){
        const num = this.data.num+1;
      this.howMorePri(num)
        // this.queryInfo({monParam:'morePrice'})
    },
    cut(){
        if (this.data.num < 2) return;
         const num = this.data.num-1;
        this.howMorePri(num)
    },
    howMorePri(num){
        this.setData({
                num:num,
                morePrice:num*this.data.onePrice
            });
    },
    queryInfo({id=false,monParam,num=false,attr=false}={}){
        const setDa = (money)=>{
            const json = {};
            json[monParam] = money
            return json;
        };
        wx.request({
                    url: `${rootUrl}/goods.php?act=price&id=${id?id:this.data.id}&attr=${attr?attr:this.data.attr}&number=${num?num:this.data.num}`,
                    method: 'GET',
                    data: {},
                    header: {
                        'Accept': 'application/json'
                    },
                    success: (res)=> {
                        this.setData(setDa(res.data.result));
                    }
                })
    },
    onLoad(options) {
        this.setData({
            id:options.id
        })
        this.proData(options.id);
       this.queryInfo({id:options.id,monParam:'onePrice',num:1,attr:254});
    },
     proData(id){
        switch(parseInt(id)){
                case 80: return this.setData({
                    title:'【佰露集】鲜嫩嫩蚕丝面膜（修护、舒缓、保湿）',
                    truePrice : '119.80',
                    specLst:[ 
                        {   value:'141',  text:`30g/ml （6片装）` }
                    ],
                    imgUrls:[
                        {url:`${rootUrl2}/images/201507/goods_img/80_P_1437472983048.jpg` },
                        {url:`${rootUrl2}/images/201507/goods_img/80_P_1437472983725.jpg` },
                        {url:`${rootUrl2}/images/201507/goods_img/80_P_1437702015809.jpg` }
                        ],
                    info:`鲜嫩嫩蚕丝面膜中添加了独特的辅酶Q10，辅酶Q10存在于每一个细胞中，是细胞活动不可缺少的成分；辅酶Q10是制造使肌肤丰润嫩白的胶原蛋白、透明质酸、水分等的动力来源，不仅具有维持肌肤看戏状态的功效，还能减缓细纹等老化现象对肌肤造成伤害 ，唤醒肌肤自身原有的机能，令肌肤恢复柔滑水嫩；鲜嫩嫩蚕丝面膜能给肌肤带来能量，恢复弹力；补水保湿美白；抗皱、修护受损肌肤等问题； 维持细胞基本功能，维持皮肤光泽；保护细胞不受自由基伤害，维持皮肤组织结构完整。`,
                    infoImgs:[
                        {url:`${rootUrl2}/images/upload/Image/鲜嫩嫩.jpg` }
                    ]
                    });     
                case 81: return this.setData({
                    title:'【佰露集】亮晶晶蚕丝面膜(白皙润颜、补水保湿)',
                    truePrice : '119.80',
                    specLst:[ 
                        {   value:'144',  text:`30g/ml （6片装）` }
                    ],
                    imgUrls:[
                        {url:`${rootUrl2}/images/201507/goods_img/81_P_1437701957631.jpg` },
                        {url:`${rootUrl2}/images/201507/goods_img/81_P_1437701957975.jpg` },
                        {url:`${rootUrl2}/images/201507/goods_img/81_P_1437701957483.jpg` }
                        ],
                    info:`亮晶晶蚕丝面膜中也特别添加了神经酰胺（Geramide）精华液，不仅保湿、抗氧化、温和滋润，还能缓解皮肤压力，充分补充肌肤不足水分和细胞间脂质，增强皮肤抵抗力，有效锁水，活化肌肤微循环；加速新城代谢，清除自由基，过滤包裹代谢皮肤中已经形成的重金属，深层次改善皮肤皱纹、干燥、衰老、松弛等肌肤问题，使肌肤柔软紧致、润泽、有弹性、重塑V脸。`,
                    infoImgs:[
                        {url:`${rootUrl2}/images/upload/Image/亮晶晶.jpg` }
                    ]
                    });       
                case 82: return this.setData({
                    title:'【佰露集】水灵灵蚕丝面膜（水润保湿、净肤清透）',
                    truePrice : '119.80',
                    specLst:[ 
                        {   value:'146',  text:`30g/ml （6片装）` }
                    ],
                    imgUrls:[
                        {url:`${rootUrl2}/images/201507/goods_img/82_P_1437473839218.jpg` },
                        {url:`${rootUrl2}/images/201507/goods_img/82_P_1437473839877.jpg` },
                        {url:`${rootUrl2}/images/201507/goods_img/82_P_1437701889783.jpg` }
                        ],
                    info:`水灵灵蚕丝面膜中特别添加的神经酰胺（Geramide）精华液是高效保湿剂，非常易被皮肤吸收，并能促进其它营养物质渗透，与皮肤的结构和功能也有着极为密切的关系，在皮肤的修复、保湿抗衰老等方面也发挥着重要作用；对于皮肤粗糙，干燥保湿有效率为80%，通过氢键形成层状结构的分子复合体，促进表皮的水合作用，从而改善皮肤保持水分的能力，帮助皮肤再恢复，改善皮肤外观，令皮肤光滑有弹性；如果你的皮肤敏感又很干燥，在这种情况下皮肤不仅仅因为缺水，还因肌肤自我屏障功能低下，肌肤无法锁住水分，这时使用水灵灵面膜就像黏着剂一样。`,
                    infoImgs:[
                        {url:`${rootUrl2}/images/upload/Image/水灵灵(1).jpg` }
                    ]
                    });    
                case 83: return this.setData({
                    title:'【佰露集】薰衣草蚕丝面膜（补水控油、收缩毛孔）',
                    truePrice : '99.80',
                    specLst:[ 
                        {   value:'148',  text:`25g/ml （6片装）` }
                    ],
                    imgUrls:[
                        {url:`${rootUrl2}/images/201507/goods_img/83_P_1437702355738.jpg` },
                        {url:`${rootUrl2}/images/201507/goods_img/83_P_1437702355522.jpg` },
                        {url:`${rootUrl2}/images/201507/goods_img/83_P_1437702355292.jpg` }
                        ],
                    info:`薰衣草对肌肤有很好的调整效果，增强皮肤对环境影响的防护力，全面排出肌肤表面与积累在毛孔中的老废物杂质，唤醒明亮的健康美肌，使用薰衣草面膜后肌肤变得更明亮、润泽和更有弹性；薰衣草面膜能增加肌肤的保水性，使肌肤的保湿效果增大，因而促使肌肤更显亮丽且不易产生过敏反应；增加肌肤的紧缩性及弹性，使松弛的肌肤回复年轻状态，并可因此达到去除皱纹的目的。因保水性及紧缩性的双重作用，薰衣草面膜可改善干性及油性肌肤的分泌状态，使之渐趋于中性肌肤，并促使毛细孔收缩细致。电脑辐射、油脂分泌过剩造成毛孔粗大，使用后平复`,
                    infoImgs:[
                        {url:`${rootUrl2}/images/upload/Image/薰衣.jpg` }
                    ]
                    });   
                case 84: return this.setData({
                    title:'【佰露集】玫瑰蚕丝面膜（补水保湿滋润、提亮）25ML*6片装',
                    truePrice : '99.80',
                    specLst:[ 
                        {   value:'150',  text:`25g/ml （6片装）` }
                    ],
                    imgUrls:[
                        {url:`${rootUrl2}/images/201507/goods_img/84_P_1437474122041.jpg` },
                        {url:`${rootUrl2}/images/201507/goods_img/84_P_1437701295398.jpg` }
                        ],
                    info:`玫瑰面膜温和淡化肤色，改善暗沉无光泽之肌肤，达到细致，透明感，亮丽，有光泽的白皙肤质。瞬间补充肌肤水分，提升肌肤补水能力并使肌肤苏醒，强化保湿能力使肌肤再现光泽与亮丽，保持肌肤的年轻水嫩，拥有真正的美肌；可以让皮肤变得更加细嫩，起到修复皮肤的作用；从本质上改善肤质，让女人变得越来越美丽，还可以提供犹如丝一般的美白效果，让皮肤感到清爽柔滑；玫瑰面膜还具备很强大的抗氧化功效，可以让肌肤细胞更佳的活跃，缓解肌肤过敏，且长时间保持肌肤有充足的水分。`,
                    infoImgs:[
                        {url:`${rootUrl2}/images/upload/Image/玫瑰.jpg` }
                    ]
                    
                    });    
                case 85: return this.setData({
                    title:'【佰露集】蓝莓蚕丝面膜(补水抗氧化、清爽平衡）25ML*6片装',
                    truePrice : '99.80',
                    specLst:[ 
                        {   value:'152',  text:`25g/ml （6片装）` }
                    ],
                    imgUrls:[
                        {url:`${rootUrl2}/images/201507/goods_img/85_P_1437701232387.jpg` },
                        {url:`${rootUrl2}/images/201507/goods_img/85_P_1437701232533.jpg` },
                        {url:`${rootUrl2}/images/201507/goods_img/85_P_1437701232201.jpg` }
                        ],
                    info:`蓝莓面膜能增加皮肤营养及弹性，调护肌肤新生，蓝莓提取物，亦可激发皮肤本身营养再生，快速去除黑色素，加快皮肤吸收；蓝莓提取物中的花青素是最有效的抗氧化剂，比维E高出五十倍，维C高出二十倍；蓝莓面膜具有保湿和抗氧化等多种功效，并补充肌肤水分，使黯淡的肌肤充满活力，宛如新生；淡化黑色素，提亮肤色，保湿补水，提升肌肤弹性，感觉疲惫干燥时，敷上一片蓝莓面膜，能使肌肤水润光泽，无瑕透亮。`,
                    infoImgs:[
                        {url:`${rootUrl2}/images/upload/Image/蓝莓.jpg` }
                    ]
                    
                    });
                case 86: return this.setData({
                    title:'【佰露集】白竹炭净颜洗面奶男女通用100ML',
                    truePrice : '168.00',
                    specLst:[ 
                        {   value:'156',  text:'100ml' }
                    ],
                    imgUrls:[
                        {url:`${rootUrl2}/images/201602/goods_img/86_P_1456193250267.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/86_P_1456193250646.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/86_P_1456193250298.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/86_P_1456193250529.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/86_P_1456193250239.jpg` },
                        ],
                    info:`采集日本樱岛火山岩上生长的青竹，用1200度高温和9道过滤工序烧制提纯出来的有效成份，经双层包裹技术包裹后的竹炭微粒子，在按摩使用过程中完全溶解，温和去除污垢及杂质，减轻肌肤负担，高效清洁能力也可用于面部卸妆，像强大的磁铁一样吸出毛孔中残留的污垢和老化角质，吸走黑头，收紧毛孔，卸妆加清洁，双倍洁净力，使肌肤立刻呈现净白无暇水润弹滑。`,
                    infoImgs:[
                        {url:`${rootUrl2}/images/upload/Image/洗面奶_01(3).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/洗面奶_02(3).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/洗面奶_03(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/洗面奶_04(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/洗面奶_05(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/洗面奶_06(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/洗面奶_07(1).jpg` }
                        
                    ]
                    
                    });
                case 87: return this.setData({
                    title:'【佰露集】青春密码天才水爽肤水125ML',
                    truePrice : '218.00',
                    specLst:[ 
                        {   value:'158',  text:'125ml' }
                    ],
                    imgUrls:[
                        {url:`${rootUrl2}/images/201602/goods_img/87_P_1456193439775.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/87_P_1456193439162.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/87_P_1456193439885.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/87_P_1456193439728.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/87_P_1456193439163.jpg` },
                        ],
                    info:`密集保湿、深入滋养、卓效锁水！表层化妆水般的补水力，轻拍即刻化水里层精华般的滋养力，轻揉即刻吸收底层乳液般的锁水力，深层滋养保湿水油相融，保湿滋润不油腻，锁水更出众。`,
                    infoImgs:[
                        {url:`${rootUrl2}/images/upload/Image/密码水效果图_01(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/密码水效果图_02(2).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/密码水效果图_03(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/密码水效果图_04(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/密码水效果图_05.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/密码水效果图_06.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/密码水效果图_07.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/密码水效果图_08.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/密码水效果图_09.jpg` }
                    ]
                    
                    });    
                case 88: return this.setData({
                    title:'【佰露集】玻尿酸精华液25ML',
                    truePrice : '218.00',
                    specLst:[ 
                        {   value:'162',  text:'25ml' }
                    ],
                    imgUrls:[
                        {url:`${rootUrl2}/images/201602/goods_img/88_P_1456193526214.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/88_P_1456193526320.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/88_P_1456193525748.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/88_P_1456193525737.jpg` },
                        {url:`${rootUrl2}/images/201602/goods_img/88_P_1456193525145.jpg` },
                        ],
                    info:`采用大小分子量两种不同的透明质酸，100万分子量的大分子在肌肤表面成膜锁水，1万分子量的小分子快速渗透进皮肤深层补水，增加肌肤的弹性和紧致度，焕发迷人光彩。`,
                    infoImgs:[
                        {url:`${rootUrl2}/images/upload/Image/精华液_01(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_02(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_03(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_04(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_05(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_07(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_08(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_09(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_10(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_11(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_12(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_13(1).jpg` },
                        {url:`${rootUrl2}/images/upload/Image/精华液_14(1).jpg` }
                    ]
                    
                    });
                case 93: return this.setData({
                    title:'白竹炭洁面乳1盒+天才水1盒+玻尿酸1盒',
                    truePrice : '594.00',
                    info:`年终巨惠：白竹炭净颜洁面乳1盒+天才水1盒+玻尿酸精华1盒=158元.`,
                    specLst:[ 
                        {   value:'218',  text:'100ML+125ML+25ML' }
                    ],
                    imgUrls:[{url:`${rootUrl2}/images/201612/goods_img/93_P_1481183289987.jpg` }],
                    infoImgs:[
                        {url:`${rootUrl2}/images/upload/Image/158.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E6%B4%97%E9%9D%A2%E5%A5%B6_01.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E6%B4%97%E9%9D%A2%E5%A5%B6_02.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E6%B4%97%E9%9D%A2%E5%A5%B6_03.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E6%B4%97%E9%9D%A2%E5%A5%B6_04.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E6%B4%97%E9%9D%A2%E5%A5%B6_05.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E6%B4%97%E9%9D%A2%E5%A5%B6_06.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E5%AF%86%E7%A0%81%E6%B0%B4_01.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E5%AF%86%E7%A0%81%E6%B0%B4_02.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E5%AF%86%E7%A0%81%E6%B0%B4_03.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E5%AF%86%E7%A0%81%E6%B0%B4_04.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E5%AF%86%E7%A0%81%E6%B0%B4_05.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E5%AF%86%E7%A0%81%E6%B0%B4_06.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E5%AF%86%E7%A0%81%E6%B0%B4_07.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E5%AF%86%E7%A0%81%E6%B0%B4_08.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_01.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_02.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_03.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_04.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_05.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_06.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_07.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_08.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_09.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_10.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_11.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_12.jpg` },
                        {url:`${rootUrl2}/images/upload/Image/%E7%B2%BE%E5%8D%8E%E6%B6%B2_13.jpg` }
                    ]
                    });
                 case 95: return this.setData({
                    title:'天杞园特殊膳食 科学 安全 有效 不反弹（5盒为1个周期，瘦10斤肉左右））',
                    truePrice : '268.00',
                    info:'',
                    specLst:[ 
                        {   value:'210',  text:'1盒' },
                        {   value:'211',  text:'5盒' },
                        {   value:'212',  text:'30盒' },
                        {   value:'213',  text:'3箱' },
                    ],
                    imgUrls:[
                        {url:`${rootUrl2}/images/201608/goods_img/95_P_1472460746999.jpg` },
                        {url:`${rootUrl2}/images/201608/goods_img/95_P_1472460746650.jpg` }
                        ],
                    infoImgs:[
                        {url:`${rootUrl2}/images/upload/Image/6(2).jpg` }
                    ]
                    });   

        }
    }
})