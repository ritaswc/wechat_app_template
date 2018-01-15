function has_(tem, post) {
    if (tem == '') {
        return '不限'
    } else {
        var str = [];
        for (let i = 0; i < tem.length; i++) {
            str.push(tem[i][post]);
        }
        return str.join();
    }
}
//转译HTML代码
function  HTMLEnCode(str)
{
    let arrEntities={'lt':'<','gt':'>','nbsp':' ','amp':'&','quot':'"'};
    return str.replace(/&(lt|gt|nbsp|amp|quot);/ig,function(all,t){return arrEntities[t];});
}
function mate(_type) {
    switch (_type) {
        case "sex":
            return {
                title: "性别",
                arr: ["男", "女"]
            }
            break;
        case "education":
            return {
                title: "请选择",
                arr: ["不限", "初中", "高中", "中技", "中专", "大专", "本科", "硕士", "博士"]
            }
            break;
        case "job":
            return {
                title: '求职状态',
                arr: ["正在找工作", "我愿意考虑好的工作机会", "暂时不想找工作"]
            }
            break;
        case "codeType":
            return {
                title: '请选择',
                arr: ["不限", "身份证", "军人证", "港澳身份证", "台胞证", "护照", "其他证件"]
            }
            break;
        case "marriage":
            return {
                title: '婚姻状况',
                arr: ["未婚", "已婚", "离异", "保密"]
            }
            break;
        case "mianmao":
            return {
                title: '政治面貌',
                arr: ["党员", "团员", "群众", "民主党派", "其他党派"]
            }
            break;
        case "nation":
            return {
                title: '民族',
                arr: ["汉族", "蒙古族", "回族", "藏族", "维吾尔族", "苗族", "彝族", "壮族", "布依族", "朝鲜族", "满足", "侗族", "瑶族", "白族", "土家族", "哈尼族", "哈萨克族", "傣族", "黎族", "傈僳族", "佤族", "畲族", "高山族", "拉祜族", "水族", "东乡族", "纳西族", "景颇族", "柯尔克孜族", "土族", "达翰尔族", "羌族", "布朗族", "撒拉族", "毛南族", "仡佬族", "锡伯族", "阿昌族", "普米族", "塔吉克族", "怒族", "乌孜别克族", "俄罗斯族", "鄂温克族", "德昂族", "保安族", "裕固族", "京族", "塔塔尔族", "独龙族", "鄂伦春族", "门巴族", "珞巴族", "基诺族"]
            }
            break;
    }
}

module.exports = {
    has_: has_,
    mate: mate,
    HTMLEnCode:HTMLEnCode
}