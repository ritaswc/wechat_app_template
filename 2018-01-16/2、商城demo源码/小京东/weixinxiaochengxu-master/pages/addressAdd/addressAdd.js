
var request = require('../../utils/https.js')
var uri_save_address = 'address/api/saveAddress' //确认订单

Page({
  data:{
    array:[
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150102",
                        "areaName": "新城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150103",
                        "areaName": "回民区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150104",
                        "areaName": "玉泉区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150105",
                        "areaName": "赛罕区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150121",
                        "areaName": "土左旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150122",
                        "areaName": "托克托县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150123",
                        "areaName": "和林格尔县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150124",
                        "areaName": "清水河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150125",
                        "areaName": "武川县"
                    }
                ],
                "areaId": "1501",
                "areaName": "呼和浩特市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150202",
                        "areaName": "东河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150203",
                        "areaName": "昆都仑区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150204",
                        "areaName": "青山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150205",
                        "areaName": "石拐区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150206",
                        "areaName": "白云鄂博矿区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150207",
                        "areaName": "九原区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150221",
                        "areaName": "土默特右旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150222",
                        "areaName": "固阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150223",
                        "areaName": "达茂联合旗"
                    }
                ],
                "areaId": "1502",
                "areaName": "包头市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150301",
                        "areaName": "乌海市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150302",
                        "areaName": "海勃湾区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150303",
                        "areaName": "海南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150304",
                        "areaName": "乌达区"
                    }
                ],
                "areaId": "1503",
                "areaName": "乌海市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150402",
                        "areaName": "红山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150403",
                        "areaName": "元宝山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150404",
                        "areaName": "松山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150421",
                        "areaName": "阿鲁科尔沁旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150422",
                        "areaName": "巴林左旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150423",
                        "areaName": "巴林右旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150424",
                        "areaName": "林西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150425",
                        "areaName": "克什克腾旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150426",
                        "areaName": "翁牛特旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150428",
                        "areaName": "喀喇沁旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150429",
                        "areaName": "宁城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150430",
                        "areaName": "敖汉旗"
                    }
                ],
                "areaId": "1504",
                "areaName": "赤峰市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150502",
                        "areaName": "科尔沁区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150521",
                        "areaName": "科尔沁左翼中旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150522",
                        "areaName": "科左后旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150523",
                        "areaName": "开鲁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150524",
                        "areaName": "库伦旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150525",
                        "areaName": "奈曼旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150526",
                        "areaName": "扎鲁特旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150581",
                        "areaName": "霍林郭勒市"
                    }
                ],
                "areaId": "1505",
                "areaName": "通辽市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150602",
                        "areaName": "东胜区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150621",
                        "areaName": "达拉特旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150622",
                        "areaName": "准格尔旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150623",
                        "areaName": "鄂托克前旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150624",
                        "areaName": "鄂托克旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150625",
                        "areaName": "杭锦旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150626",
                        "areaName": "乌审旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150627",
                        "areaName": "伊金霍洛旗"
                    }
                ],
                "areaId": "1506",
                "areaName": "鄂尔多斯市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150702",
                        "areaName": "海拉尔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150721",
                        "areaName": "阿荣旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150722",
                        "areaName": "莫力达瓦达斡尔族自治旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150723",
                        "areaName": "鄂伦春自治旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150724",
                        "areaName": "鄂温克族自治旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150725",
                        "areaName": "陈巴尔虎旗镇"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150726",
                        "areaName": "新巴尔虎左旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150727",
                        "areaName": "新巴尔虎右旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150781",
                        "areaName": "满洲里市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150782",
                        "areaName": "牙克石市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150783",
                        "areaName": "扎兰屯市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150784",
                        "areaName": "额尔古纳市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150785",
                        "areaName": "根河市"
                    }
                ],
                "areaId": "1507",
                "areaName": "呼伦贝尔市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150802",
                        "areaName": "临河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150821",
                        "areaName": "五原县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150822",
                        "areaName": "磴口县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150823",
                        "areaName": "乌拉特前旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150824",
                        "areaName": "乌拉特中旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150825",
                        "areaName": "乌拉特后旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150826",
                        "areaName": "杭锦后旗"
                    }
                ],
                "areaId": "1508",
                "areaName": "巴彦淖尔市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150902",
                        "areaName": "集宁区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150921",
                        "areaName": "卓资县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150922",
                        "areaName": "化德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150923",
                        "areaName": "商都县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150924",
                        "areaName": "兴和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150925",
                        "areaName": "凉城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150926",
                        "areaName": "察哈尔右翼前旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150927",
                        "areaName": "察右中旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150928",
                        "areaName": "察哈尔右翼后旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150929",
                        "areaName": "四子王旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "150981",
                        "areaName": "丰镇市"
                    }
                ],
                "areaId": "1509",
                "areaName": "乌兰察布市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152201",
                        "areaName": "乌兰浩特市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152202",
                        "areaName": "阿尔山市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152221",
                        "areaName": "科右前旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152222",
                        "areaName": "科右中旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152223",
                        "areaName": "扎赉特旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152224",
                        "areaName": "突泉县"
                    }
                ],
                "areaId": "1522",
                "areaName": "兴安盟"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152501",
                        "areaName": "二连浩特市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152502",
                        "areaName": "锡林浩特市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152522",
                        "areaName": "阿巴嘎旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152523",
                        "areaName": "苏尼特左旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152524",
                        "areaName": "苏尼特右旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152525",
                        "areaName": "东乌珠穆沁旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152526",
                        "areaName": "西乌珠穆沁旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152527",
                        "areaName": "太仆寺旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152528",
                        "areaName": "镶黄旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152529",
                        "areaName": "正镶白旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152530",
                        "areaName": "正蓝旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152531",
                        "areaName": "多伦县"
                    }
                ],
                "areaId": "1525",
                "areaName": "锡林郭勒盟"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152921",
                        "areaName": "阿拉善左旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152922",
                        "areaName": "阿拉善右旗"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "152923",
                        "areaName": "额济纳旗"
                    }
                ],
                "areaId": "1529",
                "areaName": "阿拉善盟"
            }
        ],
        "areaId": "15",
        "areaName": "内蒙古自治区"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210102",
                        "areaName": "和平区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210103",
                        "areaName": "沈河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210104",
                        "areaName": "大东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210105",
                        "areaName": "皇姑区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210106",
                        "areaName": "铁西区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210111",
                        "areaName": "苏家屯区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210112",
                        "areaName": "东陵区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210113",
                        "areaName": "新城子区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210114",
                        "areaName": "于洪区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210122",
                        "areaName": "辽中县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210123",
                        "areaName": "康平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210124",
                        "areaName": "法库县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210181",
                        "areaName": "新民市"
                    }
                ],
                "areaId": "2101",
                "areaName": "沈阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210202",
                        "areaName": "中山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210203",
                        "areaName": "西岗区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210204",
                        "areaName": "沙河口区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210211",
                        "areaName": "甘井子区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210212",
                        "areaName": "旅顺口区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210213",
                        "areaName": "金州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210224",
                        "areaName": "长海县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210281",
                        "areaName": "瓦房店市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210282",
                        "areaName": "普兰店市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210283",
                        "areaName": "庄河市"
                    }
                ],
                "areaId": "2102",
                "areaName": "大连市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210302",
                        "areaName": "铁东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210303",
                        "areaName": "铁西区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210304",
                        "areaName": "立山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210311",
                        "areaName": "千山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210321",
                        "areaName": "台安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210323",
                        "areaName": "岫岩县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210381",
                        "areaName": "海城市"
                    }
                ],
                "areaId": "2103",
                "areaName": "鞍山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210402",
                        "areaName": "新抚区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210403",
                        "areaName": "东洲区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210404",
                        "areaName": "望花区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210411",
                        "areaName": "顺城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210421",
                        "areaName": "抚顺县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210422",
                        "areaName": "新宾满族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210423",
                        "areaName": "清原满族自治县"
                    }
                ],
                "areaId": "2104",
                "areaName": "抚顺市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210502",
                        "areaName": "平山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210503",
                        "areaName": "溪湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210504",
                        "areaName": "明山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210505",
                        "areaName": "南芬区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210521",
                        "areaName": "本溪满族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210522",
                        "areaName": "桓仁满族自治县"
                    }
                ],
                "areaId": "2105",
                "areaName": "本溪市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210602",
                        "areaName": "元宝区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210603",
                        "areaName": "振兴区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210604",
                        "areaName": "振安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210624",
                        "areaName": "宽甸满族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210681",
                        "areaName": "东港市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210682",
                        "areaName": "凤城市"
                    }
                ],
                "areaId": "2106",
                "areaName": "丹东市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210702",
                        "areaName": "古塔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210703",
                        "areaName": "凌河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210711",
                        "areaName": "太和区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210726",
                        "areaName": "黑山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210727",
                        "areaName": "义县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210781",
                        "areaName": "凌海市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210782",
                        "areaName": "北镇市"
                    }
                ],
                "areaId": "2107",
                "areaName": "锦州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210802",
                        "areaName": "站前区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210803",
                        "areaName": "西市区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210804",
                        "areaName": "鲅鱼圈区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210811",
                        "areaName": "老边区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210881",
                        "areaName": "盖州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210882",
                        "areaName": "大石桥市"
                    }
                ],
                "areaId": "2108",
                "areaName": "营口市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210902",
                        "areaName": "海州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210903",
                        "areaName": "新邱区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210904",
                        "areaName": "太平区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210905",
                        "areaName": "清河门区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210911",
                        "areaName": "细河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210921",
                        "areaName": "阜新蒙古族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "210922",
                        "areaName": "彰武县"
                    }
                ],
                "areaId": "2109",
                "areaName": "阜新市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211002",
                        "areaName": "白塔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211003",
                        "areaName": "文圣区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211004",
                        "areaName": "宏伟区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211005",
                        "areaName": "弓长岭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211011",
                        "areaName": "太子河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211021",
                        "areaName": "辽阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211081",
                        "areaName": "灯塔市"
                    }
                ],
                "areaId": "2110",
                "areaName": "辽阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211102",
                        "areaName": "双台子区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211103",
                        "areaName": "兴隆台区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211121",
                        "areaName": "大洼县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211122",
                        "areaName": "盘山县"
                    }
                ],
                "areaId": "2111",
                "areaName": "盘锦市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211202",
                        "areaName": "银州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211204",
                        "areaName": "清河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211221",
                        "areaName": "铁岭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211223",
                        "areaName": "西丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211224",
                        "areaName": "昌图县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211281",
                        "areaName": "调兵山市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211282",
                        "areaName": "开原市"
                    }
                ],
                "areaId": "2112",
                "areaName": "铁岭市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211302",
                        "areaName": "双塔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211303",
                        "areaName": "龙城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211321",
                        "areaName": "朝阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211322",
                        "areaName": "建平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211324",
                        "areaName": "喀喇沁左翼蒙古族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211381",
                        "areaName": "北票市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211382",
                        "areaName": "凌源市"
                    }
                ],
                "areaId": "2113",
                "areaName": "朝阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211402",
                        "areaName": "连山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211403",
                        "areaName": "龙港区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211404",
                        "areaName": "南票区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211421",
                        "areaName": "绥中县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211422",
                        "areaName": "建昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "211481",
                        "areaName": "兴城市"
                    }
                ],
                "areaId": "2114",
                "areaName": "葫芦岛市"
            }
        ],
        "areaId": "21",
        "areaName": "辽宁省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110101",
                        "areaName": "东城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110102",
                        "areaName": "西城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110103",
                        "areaName": "崇文区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110104",
                        "areaName": "宣武区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110105",
                        "areaName": "朝阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110106",
                        "areaName": "丰台区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110107",
                        "areaName": "石景山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110108",
                        "areaName": "海淀区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110109",
                        "areaName": "门头沟区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110111",
                        "areaName": "房山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110112",
                        "areaName": "通州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110113",
                        "areaName": "顺义区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110114",
                        "areaName": "昌平区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110115",
                        "areaName": "大兴区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110116",
                        "areaName": "怀柔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110117",
                        "areaName": "平谷区"
                    }
                ],
                "areaId": "1101",
                "areaName": "市辖区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110228",
                        "areaName": "密云县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "110229",
                        "areaName": "延庆县"
                    }
                ],
                "areaId": "1102",
                "areaName": "县"
            }
        ],
        "areaId": "11",
        "areaName": "北京市"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120101",
                        "areaName": "和平区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120102",
                        "areaName": "河东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120103",
                        "areaName": "河西区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120104",
                        "areaName": "南开区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120105",
                        "areaName": "河北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120106",
                        "areaName": "红桥区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120107",
                        "areaName": "塘沽区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120108",
                        "areaName": "汉沽区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120109",
                        "areaName": "大港区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120110",
                        "areaName": "东丽区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120111",
                        "areaName": "西青区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120112",
                        "areaName": "津南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120113",
                        "areaName": "北辰区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120114",
                        "areaName": "武清区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120115",
                        "areaName": "宝坻区"
                    }
                ],
                "areaId": "1201",
                "areaName": "市辖区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120221",
                        "areaName": "宁河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120223",
                        "areaName": "静海县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "120225",
                        "areaName": "蓟县"
                    }
                ],
                "areaId": "1202",
                "areaName": "市辖县"
            }
        ],
        "areaId": "12",
        "areaName": "天津市"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130102",
                        "areaName": "长安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130103",
                        "areaName": "桥东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130104",
                        "areaName": "桥西区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130105",
                        "areaName": "新华区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130107",
                        "areaName": "井陉矿区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130108",
                        "areaName": "裕华区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130121",
                        "areaName": "井陉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130123",
                        "areaName": "正定县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130124",
                        "areaName": "栾城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130125",
                        "areaName": "行唐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130126",
                        "areaName": "灵寿县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130127",
                        "areaName": "高邑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130128",
                        "areaName": "深泽县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130129",
                        "areaName": "赞皇县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130130",
                        "areaName": "无极县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130131",
                        "areaName": "平山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130132",
                        "areaName": "元氏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130133",
                        "areaName": "赵县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130181",
                        "areaName": "辛集市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130182",
                        "areaName": "藁城市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130183",
                        "areaName": "晋州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130184",
                        "areaName": "新乐市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130185",
                        "areaName": "鹿泉市"
                    }
                ],
                "areaId": "1301",
                "areaName": "石家庄市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130202",
                        "areaName": "路南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130203",
                        "areaName": "路北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130204",
                        "areaName": "古冶区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130205",
                        "areaName": "开平区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130207",
                        "areaName": "丰南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130208",
                        "areaName": "丰润区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130223",
                        "areaName": "滦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130224",
                        "areaName": "滦南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130225",
                        "areaName": "乐亭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130227",
                        "areaName": "迁西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130229",
                        "areaName": "玉田县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130230",
                        "areaName": "唐海县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130281",
                        "areaName": "遵化市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130283",
                        "areaName": "迁安市"
                    }
                ],
                "areaId": "1302",
                "areaName": "唐山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130302",
                        "areaName": "海港区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130303",
                        "areaName": "山海关区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130304",
                        "areaName": "北戴河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130321",
                        "areaName": "青龙满族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130322",
                        "areaName": "昌黎县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130323",
                        "areaName": "抚宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130324",
                        "areaName": "卢龙县"
                    }
                ],
                "areaId": "1303",
                "areaName": "秦皇岛市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130402",
                        "areaName": "邯山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130403",
                        "areaName": "丛台区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130404",
                        "areaName": "复兴区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130406",
                        "areaName": "峰峰矿区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130421",
                        "areaName": "邯郸县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130423",
                        "areaName": "临漳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130424",
                        "areaName": "成安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130425",
                        "areaName": "大名县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130426",
                        "areaName": "涉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130427",
                        "areaName": "磁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130428",
                        "areaName": "肥乡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130429",
                        "areaName": "永年县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130430",
                        "areaName": "邱县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130431",
                        "areaName": "鸡泽县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130432",
                        "areaName": "广平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130433",
                        "areaName": "馆陶县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130434",
                        "areaName": "魏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130435",
                        "areaName": "曲周县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130481",
                        "areaName": "武安市"
                    }
                ],
                "areaId": "1304",
                "areaName": "邯郸市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130502",
                        "areaName": "桥东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130503",
                        "areaName": "桥西区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130521",
                        "areaName": "邢台县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130522",
                        "areaName": "临城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130523",
                        "areaName": "内邱县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130524",
                        "areaName": "柏乡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130525",
                        "areaName": "隆尧县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130526",
                        "areaName": "任县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130527",
                        "areaName": "南和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130528",
                        "areaName": "宁晋县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130529",
                        "areaName": "巨鹿县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130530",
                        "areaName": "新河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130531",
                        "areaName": "广宗县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130532",
                        "areaName": "平乡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130533",
                        "areaName": "威县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130534",
                        "areaName": "清河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130535",
                        "areaName": "临西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130581",
                        "areaName": "南宫市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130582",
                        "areaName": "沙河市"
                    }
                ],
                "areaId": "1305",
                "areaName": "邢台市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130602",
                        "areaName": "新市区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130603",
                        "areaName": "北市区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130604",
                        "areaName": "南市区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130621",
                        "areaName": "满城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130622",
                        "areaName": "清苑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130623",
                        "areaName": "涞水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130624",
                        "areaName": "阜平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130625",
                        "areaName": "徐水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130626",
                        "areaName": "定兴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130627",
                        "areaName": "唐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130628",
                        "areaName": "高阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130629",
                        "areaName": "容城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130630",
                        "areaName": "涞源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130631",
                        "areaName": "望都县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130632",
                        "areaName": "安新县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130633",
                        "areaName": "易县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130634",
                        "areaName": "曲阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130635",
                        "areaName": "蠡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130636",
                        "areaName": "顺平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130637",
                        "areaName": "博野县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130638",
                        "areaName": "雄县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130681",
                        "areaName": "涿州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130682",
                        "areaName": "定州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130683",
                        "areaName": "安国市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130684",
                        "areaName": "高碑店市"
                    }
                ],
                "areaId": "1306",
                "areaName": "保定市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130702",
                        "areaName": "桥东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130703",
                        "areaName": "桥西区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130705",
                        "areaName": "宣化区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130706",
                        "areaName": "下花园区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130721",
                        "areaName": "宣化县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130722",
                        "areaName": "张北县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130723",
                        "areaName": "康保县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130724",
                        "areaName": "沽源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130725",
                        "areaName": "尚义县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130726",
                        "areaName": "蔚县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130727",
                        "areaName": "阳原县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130728",
                        "areaName": "怀安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130729",
                        "areaName": "万全县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130730",
                        "areaName": "怀来县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130731",
                        "areaName": "涿鹿县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130732",
                        "areaName": "赤城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130733",
                        "areaName": "崇礼县"
                    }
                ],
                "areaId": "1307",
                "areaName": "张家口市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130802",
                        "areaName": "双桥区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130803",
                        "areaName": "双滦区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130804",
                        "areaName": "鹰手营子矿区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130821",
                        "areaName": "承德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130822",
                        "areaName": "兴隆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130823",
                        "areaName": "平泉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130824",
                        "areaName": "滦平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130825",
                        "areaName": "隆化县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130826",
                        "areaName": "丰宁满族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130827",
                        "areaName": "宽城满族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130828",
                        "areaName": "围场满族蒙古族自治县"
                    }
                ],
                "areaId": "1308",
                "areaName": "承德市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130902",
                        "areaName": "新华区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130903",
                        "areaName": "运河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130921",
                        "areaName": "沧县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130922",
                        "areaName": "青县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130923",
                        "areaName": "东光县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130924",
                        "areaName": "海兴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130925",
                        "areaName": "盐山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130926",
                        "areaName": "肃宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130927",
                        "areaName": "南皮县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130928",
                        "areaName": "吴桥县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130929",
                        "areaName": "献县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130930",
                        "areaName": "孟村回族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130981",
                        "areaName": "泊头市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130982",
                        "areaName": "任邱市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130983",
                        "areaName": "黄骅市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "130984",
                        "areaName": "河间市"
                    }
                ],
                "areaId": "1309",
                "areaName": "沧州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131002",
                        "areaName": "安次区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131003",
                        "areaName": "广阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131022",
                        "areaName": "固安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131023",
                        "areaName": "永清县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131024",
                        "areaName": "香河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131025",
                        "areaName": "大城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131026",
                        "areaName": "文安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131028",
                        "areaName": "大厂回族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131081",
                        "areaName": "霸州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131082",
                        "areaName": "三河市"
                    }
                ],
                "areaId": "1310",
                "areaName": "廊坊市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131102",
                        "areaName": "桃城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131121",
                        "areaName": "枣强县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131122",
                        "areaName": "武邑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131123",
                        "areaName": "武强县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131124",
                        "areaName": "饶阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131125",
                        "areaName": "安平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131126",
                        "areaName": "故城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131127",
                        "areaName": "景县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131128",
                        "areaName": "阜城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131181",
                        "areaName": "冀州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "131182",
                        "areaName": "深州市"
                    }
                ],
                "areaId": "1311",
                "areaName": "衡水市"
            }
        ],
        "areaId": "13",
        "areaName": "河北省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140202",
                        "areaName": "大同市城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140203",
                        "areaName": "矿区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140211",
                        "areaName": "南郊区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140212",
                        "areaName": "新荣区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140221",
                        "areaName": "阳高县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140222",
                        "areaName": "天镇县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140223",
                        "areaName": "广灵县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140224",
                        "areaName": "灵丘县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140225",
                        "areaName": "浑源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140226",
                        "areaName": "左云县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140227",
                        "areaName": "大同县"
                    }
                ],
                "areaId": "1402",
                "areaName": "大同市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140302",
                        "areaName": "城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140303",
                        "areaName": "矿区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140311",
                        "areaName": "郊区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140321",
                        "areaName": "平定县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140322",
                        "areaName": "盂县"
                    }
                ],
                "areaId": "1403",
                "areaName": "阳泉市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140402",
                        "areaName": "长治市城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140411",
                        "areaName": "长治市郊区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140421",
                        "areaName": "长治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140423",
                        "areaName": "襄垣县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140424",
                        "areaName": "屯留县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140425",
                        "areaName": "平顺县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140426",
                        "areaName": "黎城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140427",
                        "areaName": "壶关县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140428",
                        "areaName": "长子县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140429",
                        "areaName": "武乡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140430",
                        "areaName": "沁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140431",
                        "areaName": "沁源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140481",
                        "areaName": "潞城市"
                    }
                ],
                "areaId": "1404",
                "areaName": "长治市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140502",
                        "areaName": "晋城市城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140521",
                        "areaName": "沁水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140522",
                        "areaName": "阳城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140524",
                        "areaName": "陵川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140525",
                        "areaName": "泽州县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140581",
                        "areaName": "高平市"
                    }
                ],
                "areaId": "1405",
                "areaName": "晋城市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140602",
                        "areaName": "朔城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140603",
                        "areaName": "平鲁区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140621",
                        "areaName": "山阴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140622",
                        "areaName": "应县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140623",
                        "areaName": "右玉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140624",
                        "areaName": "怀仁县"
                    }
                ],
                "areaId": "1406",
                "areaName": "朔州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140702",
                        "areaName": "榆次区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140721",
                        "areaName": "榆社县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140722",
                        "areaName": "左权县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140723",
                        "areaName": "和顺县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140724",
                        "areaName": "昔阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140725",
                        "areaName": "寿阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140726",
                        "areaName": "太谷县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140727",
                        "areaName": "祁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140728",
                        "areaName": "平遥县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140729",
                        "areaName": "灵石县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140781",
                        "areaName": "介休市"
                    }
                ],
                "areaId": "1407",
                "areaName": "晋中市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140802",
                        "areaName": "盐湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140821",
                        "areaName": "临猗县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140822",
                        "areaName": "万荣县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140823",
                        "areaName": "闻喜县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140824",
                        "areaName": "稷山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140825",
                        "areaName": "新绛县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140826",
                        "areaName": "绛县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140827",
                        "areaName": "垣曲县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140828",
                        "areaName": "夏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140829",
                        "areaName": "平陆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140830",
                        "areaName": "芮城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140881",
                        "areaName": "永济市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140882",
                        "areaName": "河津市"
                    }
                ],
                "areaId": "1408",
                "areaName": "运城市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140902",
                        "areaName": "忻府区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140921",
                        "areaName": "定襄县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140922",
                        "areaName": "五台县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140923",
                        "areaName": "代县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140924",
                        "areaName": "繁峙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140925",
                        "areaName": "宁武县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140926",
                        "areaName": "静乐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140927",
                        "areaName": "神池县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140928",
                        "areaName": "五寨县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140929",
                        "areaName": "岢岚县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140930",
                        "areaName": "河曲县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140931",
                        "areaName": "保德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140932",
                        "areaName": "偏关县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140981",
                        "areaName": "原平市"
                    }
                ],
                "areaId": "1409",
                "areaName": "忻州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141002",
                        "areaName": "尧都区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141021",
                        "areaName": "曲沃县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141022",
                        "areaName": "翼城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141023",
                        "areaName": "襄汾县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141024",
                        "areaName": "洪洞县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141025",
                        "areaName": "古县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141026",
                        "areaName": "安泽县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141027",
                        "areaName": "浮山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141028",
                        "areaName": "吉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141029",
                        "areaName": "乡宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141030",
                        "areaName": "大宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141031",
                        "areaName": "隰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141032",
                        "areaName": "永和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141033",
                        "areaName": "蒲县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141034",
                        "areaName": "汾西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141081",
                        "areaName": "侯马市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141082",
                        "areaName": "霍州市"
                    }
                ],
                "areaId": "1410",
                "areaName": "临汾市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141102",
                        "areaName": "离石区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141121",
                        "areaName": "文水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141122",
                        "areaName": "交城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141123",
                        "areaName": "兴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141124",
                        "areaName": "临县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141125",
                        "areaName": "柳林县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141126",
                        "areaName": "石楼县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141127",
                        "areaName": "岚县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141128",
                        "areaName": "方山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141129",
                        "areaName": "中阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141130",
                        "areaName": "交口县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141181",
                        "areaName": "孝义市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "141182",
                        "areaName": "汾阳市"
                    }
                ],
                "areaId": "1411",
                "areaName": "吕梁市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140121",
                        "areaName": "清徐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140122",
                        "areaName": "阳曲县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140123",
                        "areaName": "娄烦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140181",
                        "areaName": "古交市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140105",
                        "areaName": "小店区(人口含高新经济区)"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140106",
                        "areaName": "迎泽区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140107",
                        "areaName": "杏花岭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140108",
                        "areaName": "尖草坪区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140109",
                        "areaName": "万柏林区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "140110",
                        "areaName": "晋源区"
                    }
                ],
                "areaId": "1401",
                "areaName": "太原市"
            }
        ],
        "areaId": "14",
        "areaName": "山西"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350102",
                        "areaName": "鼓楼区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350103",
                        "areaName": "台江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350104",
                        "areaName": "仓山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350105",
                        "areaName": "马尾区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350111",
                        "areaName": "晋安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350121",
                        "areaName": "闽侯县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350122",
                        "areaName": "连江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350123",
                        "areaName": "罗源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350124",
                        "areaName": "闽清县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350125",
                        "areaName": "永泰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350128",
                        "areaName": "平潭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350181",
                        "areaName": "福清市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350182",
                        "areaName": "长乐市"
                    }
                ],
                "areaId": "3501",
                "areaName": "福州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350203",
                        "areaName": "思明区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350205",
                        "areaName": "海沧区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350206",
                        "areaName": "湖里区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350211",
                        "areaName": "集美区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350212",
                        "areaName": "同安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350213",
                        "areaName": "翔安区"
                    }
                ],
                "areaId": "3502",
                "areaName": "厦门市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350302",
                        "areaName": "城厢区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350303",
                        "areaName": "涵江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350304",
                        "areaName": "荔城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350305",
                        "areaName": "秀屿区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350322",
                        "areaName": "仙游县"
                    }
                ],
                "areaId": "3503",
                "areaName": "莆田市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350402",
                        "areaName": "梅列区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350403",
                        "areaName": "三元区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350421",
                        "areaName": "明溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350423",
                        "areaName": "清流县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350424",
                        "areaName": "宁化县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350425",
                        "areaName": "大田县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350426",
                        "areaName": "尤溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350427",
                        "areaName": "沙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350428",
                        "areaName": "将乐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350429",
                        "areaName": "泰宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350430",
                        "areaName": "建宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350481",
                        "areaName": "永安市"
                    }
                ],
                "areaId": "3504",
                "areaName": "三明市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350502",
                        "areaName": "鲤城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350503",
                        "areaName": "丰泽区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350504",
                        "areaName": "洛江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350505",
                        "areaName": "泉港区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350521",
                        "areaName": "惠安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350524",
                        "areaName": "安溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350525",
                        "areaName": "永春县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350526",
                        "areaName": "德化县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350527",
                        "areaName": "金门县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350581",
                        "areaName": "石狮市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350582",
                        "areaName": "晋江市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350583",
                        "areaName": "南安市"
                    }
                ],
                "areaId": "3505",
                "areaName": "泉州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350602",
                        "areaName": "芗城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350603",
                        "areaName": "龙文区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350622",
                        "areaName": "云霄县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350623",
                        "areaName": "漳浦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350624",
                        "areaName": "诏安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350625",
                        "areaName": "长泰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350626",
                        "areaName": "东山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350627",
                        "areaName": "南靖县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350628",
                        "areaName": "平和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350629",
                        "areaName": "华安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350681",
                        "areaName": "龙海市"
                    }
                ],
                "areaId": "3506",
                "areaName": "漳州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350702",
                        "areaName": "延平区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350721",
                        "areaName": "顺昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350722",
                        "areaName": "浦城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350723",
                        "areaName": "光泽县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350724",
                        "areaName": "松溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350725",
                        "areaName": "政和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350781",
                        "areaName": "邵武市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350782",
                        "areaName": "武夷山市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350783",
                        "areaName": "建瓯市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350784",
                        "areaName": "建阳市"
                    }
                ],
                "areaId": "3507",
                "areaName": "南平市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350802",
                        "areaName": "新罗区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350821",
                        "areaName": "长汀县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350822",
                        "areaName": "永定县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350823",
                        "areaName": "上杭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350824",
                        "areaName": "武平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350825",
                        "areaName": "连城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350881",
                        "areaName": "漳平市"
                    }
                ],
                "areaId": "3508",
                "areaName": "龙岩市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350902",
                        "areaName": "蕉城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350921",
                        "areaName": "霞浦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350922",
                        "areaName": "古田县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350923",
                        "areaName": "屏南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350924",
                        "areaName": "寿宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350925",
                        "areaName": "周宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350926",
                        "areaName": "柘荣县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350981",
                        "areaName": "福安市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "350982",
                        "areaName": "福鼎市"
                    }
                ],
                "areaId": "3509",
                "areaName": "宁德市　"
            }
        ],
        "areaId": "35",
        "areaName": "福建省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360102",
                        "areaName": "东湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360103",
                        "areaName": "西湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360104",
                        "areaName": "青云谱区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360105",
                        "areaName": "湾里区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360111",
                        "areaName": "青山湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360121",
                        "areaName": "南昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360122",
                        "areaName": "新建县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360123",
                        "areaName": "安义县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360124",
                        "areaName": "进贤县"
                    }
                ],
                "areaId": "3601",
                "areaName": "南昌市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360202",
                        "areaName": "昌江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360203",
                        "areaName": "珠山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360222",
                        "areaName": "浮梁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360281",
                        "areaName": "乐平市"
                    }
                ],
                "areaId": "3602",
                "areaName": "景德镇市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360302",
                        "areaName": "安源区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360313",
                        "areaName": "湘东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360321",
                        "areaName": "莲花县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360322",
                        "areaName": "上栗县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360323",
                        "areaName": "芦溪县"
                    }
                ],
                "areaId": "3603",
                "areaName": "萍乡市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360402",
                        "areaName": "庐山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360403",
                        "areaName": "浔阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360421",
                        "areaName": "九江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360423",
                        "areaName": "武宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360424",
                        "areaName": "修水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360425",
                        "areaName": "永修县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360426",
                        "areaName": "德安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360427",
                        "areaName": "星子县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360428",
                        "areaName": "都昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360429",
                        "areaName": "湖口县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360430",
                        "areaName": "彭泽县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360481",
                        "areaName": "瑞昌市"
                    }
                ],
                "areaId": "3604",
                "areaName": "九江市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360502",
                        "areaName": "渝水区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360521",
                        "areaName": "分宜县"
                    }
                ],
                "areaId": "3605",
                "areaName": "新余市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360602",
                        "areaName": "月湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360622",
                        "areaName": "余江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360681",
                        "areaName": "贵溪市"
                    }
                ],
                "areaId": "3606",
                "areaName": "鹰潭市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360702",
                        "areaName": "章贡区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360721",
                        "areaName": "赣县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360722",
                        "areaName": "信丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360723",
                        "areaName": "大余县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360724",
                        "areaName": "上犹县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360725",
                        "areaName": "崇义县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360726",
                        "areaName": "安远县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360727",
                        "areaName": "龙南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360728",
                        "areaName": "定南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360729",
                        "areaName": "全南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360730",
                        "areaName": "宁都县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360731",
                        "areaName": "于都县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360732",
                        "areaName": "兴国县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360733",
                        "areaName": "会昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360734",
                        "areaName": "寻乌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360735",
                        "areaName": "石城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360781",
                        "areaName": "瑞金市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360782",
                        "areaName": "南康市"
                    }
                ],
                "areaId": "3607",
                "areaName": "赣州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360802",
                        "areaName": "吉州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360803",
                        "areaName": "青原区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360821",
                        "areaName": "吉安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360822",
                        "areaName": "吉水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360823",
                        "areaName": "峡江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360824",
                        "areaName": "新干县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360825",
                        "areaName": "永丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360826",
                        "areaName": "泰和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360827",
                        "areaName": "遂川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360828",
                        "areaName": "万安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360829",
                        "areaName": "安福县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360830",
                        "areaName": "永新县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360881",
                        "areaName": "井冈山市"
                    }
                ],
                "areaId": "3608",
                "areaName": "吉安市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360902",
                        "areaName": "袁州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360921",
                        "areaName": "奉新县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360922",
                        "areaName": "万载县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360923",
                        "areaName": "上高县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360924",
                        "areaName": "宜丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360925",
                        "areaName": "靖安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360926",
                        "areaName": "铜鼓县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360981",
                        "areaName": "丰城市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360982",
                        "areaName": "樟树市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "360983",
                        "areaName": "高安市"
                    }
                ],
                "areaId": "3609",
                "areaName": "宜春市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361002",
                        "areaName": "临川区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361021",
                        "areaName": "南城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361022",
                        "areaName": "黎川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361023",
                        "areaName": "南丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361024",
                        "areaName": "崇仁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361025",
                        "areaName": "乐安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361026",
                        "areaName": "宜黄县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361027",
                        "areaName": "金溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361028",
                        "areaName": "资溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361029",
                        "areaName": "东乡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361030",
                        "areaName": "广昌县"
                    }
                ],
                "areaId": "3610",
                "areaName": "抚州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361102",
                        "areaName": "信州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361121",
                        "areaName": "上饶县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361122",
                        "areaName": "广丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361123",
                        "areaName": "玉山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361124",
                        "areaName": "铅山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361125",
                        "areaName": "横峰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361126",
                        "areaName": "弋阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361127",
                        "areaName": "余干县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361128",
                        "areaName": "鄱阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361129",
                        "areaName": "万年县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361130",
                        "areaName": "婺源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "361181",
                        "areaName": "德兴市"
                    }
                ],
                "areaId": "3611",
                "areaName": "上饶市"
            }
        ],
        "areaId": "36",
        "areaName": "江西省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370102",
                        "areaName": "历下区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370103",
                        "areaName": "市中区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370104",
                        "areaName": "槐荫区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370105",
                        "areaName": "天桥区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370112",
                        "areaName": "历城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370113",
                        "areaName": "长清区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370124",
                        "areaName": "平阴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370125",
                        "areaName": "济阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370126",
                        "areaName": "商河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370181",
                        "areaName": "章丘市"
                    }
                ],
                "areaId": "3701",
                "areaName": "济南市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370202",
                        "areaName": "市南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370203",
                        "areaName": "市北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370205",
                        "areaName": "四方区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370211",
                        "areaName": "黄岛区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370212",
                        "areaName": "崂山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370213",
                        "areaName": "李沧区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370214",
                        "areaName": "城阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370281",
                        "areaName": "胶州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370282",
                        "areaName": "即墨市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370283",
                        "areaName": "平度市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370284",
                        "areaName": "胶南市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370285",
                        "areaName": "莱西市"
                    }
                ],
                "areaId": "3702",
                "areaName": "青岛市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370302",
                        "areaName": "淄川区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370303",
                        "areaName": "张店区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370304",
                        "areaName": "博山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370305",
                        "areaName": "临淄区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370306",
                        "areaName": "周村区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370321",
                        "areaName": "桓台县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370322",
                        "areaName": "高青县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370323",
                        "areaName": "沂源县"
                    }
                ],
                "areaId": "3703",
                "areaName": "淄博市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370402",
                        "areaName": "市中区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370403",
                        "areaName": "薛城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370404",
                        "areaName": "峄城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370405",
                        "areaName": "台儿庄区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370406",
                        "areaName": "山亭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370481",
                        "areaName": "滕州市"
                    }
                ],
                "areaId": "3704",
                "areaName": "枣庄市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370502",
                        "areaName": "东营区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370503",
                        "areaName": "河口区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370521",
                        "areaName": "垦利县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370522",
                        "areaName": "利津县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370523",
                        "areaName": "广饶县"
                    }
                ],
                "areaId": "3705",
                "areaName": "东营市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370602",
                        "areaName": "芝罘区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370611",
                        "areaName": "福山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370612",
                        "areaName": "牟平区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370613",
                        "areaName": "莱山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370634",
                        "areaName": "长岛县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370681",
                        "areaName": "龙口市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370682",
                        "areaName": "莱阳市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370683",
                        "areaName": "莱州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370684",
                        "areaName": "蓬莱市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370685",
                        "areaName": "招远市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370686",
                        "areaName": "栖霞市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370687",
                        "areaName": "海阳市"
                    }
                ],
                "areaId": "3706",
                "areaName": "烟台市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370702",
                        "areaName": "潍城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370703",
                        "areaName": "寒亭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370704",
                        "areaName": "坊子区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370705",
                        "areaName": "奎文区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370724",
                        "areaName": "临朐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370725",
                        "areaName": "昌乐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370781",
                        "areaName": "青州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370782",
                        "areaName": "诸城市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370783",
                        "areaName": "寿光市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370784",
                        "areaName": "安丘市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370785",
                        "areaName": "高密市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370786",
                        "areaName": "昌邑市"
                    }
                ],
                "areaId": "3707",
                "areaName": "潍坊市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370802",
                        "areaName": "市中区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370811",
                        "areaName": "任城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370826",
                        "areaName": "微山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370827",
                        "areaName": "鱼台县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370828",
                        "areaName": "金乡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370829",
                        "areaName": "嘉祥县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370830",
                        "areaName": "汶上县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370831",
                        "areaName": "泗水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370832",
                        "areaName": "梁山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370881",
                        "areaName": "曲阜市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370882",
                        "areaName": "兖州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370883",
                        "areaName": "邹城市"
                    }
                ],
                "areaId": "3708",
                "areaName": "济宁市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370902",
                        "areaName": "泰山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370903",
                        "areaName": "岱岳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370921",
                        "areaName": "宁阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370923",
                        "areaName": "东平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370982",
                        "areaName": "新泰市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "370983",
                        "areaName": "肥城市"
                    }
                ],
                "areaId": "3709",
                "areaName": "泰安市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371002",
                        "areaName": "环翠区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371081",
                        "areaName": "文登市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371082",
                        "areaName": "荣成市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371083",
                        "areaName": "乳山市"
                    }
                ],
                "areaId": "3710",
                "areaName": "威海市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371102",
                        "areaName": "东港区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371103",
                        "areaName": "岚山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371121",
                        "areaName": "五莲县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371122",
                        "areaName": "莒县"
                    }
                ],
                "areaId": "3711",
                "areaName": "日照市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371202",
                        "areaName": "莱城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371203",
                        "areaName": "钢城区"
                    }
                ],
                "areaId": "3712",
                "areaName": "莱芜市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371301",
                        "areaName": "临沂市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371302",
                        "areaName": "兰山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371311",
                        "areaName": "罗庄区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371312",
                        "areaName": "河东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371321",
                        "areaName": "沂南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371322",
                        "areaName": "郯城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371323",
                        "areaName": "沂水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371324",
                        "areaName": "苍山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371325",
                        "areaName": "费县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371326",
                        "areaName": "平邑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371327",
                        "areaName": "莒南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371328",
                        "areaName": "蒙阴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371329",
                        "areaName": "临沭县"
                    }
                ],
                "areaId": "3713",
                "areaName": "临沂市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371402",
                        "areaName": "德城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371421",
                        "areaName": "陵县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371422",
                        "areaName": "宁津县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371423",
                        "areaName": "庆云县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371424",
                        "areaName": "临邑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371425",
                        "areaName": "齐河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371426",
                        "areaName": "平原县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371427",
                        "areaName": "夏津县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371428",
                        "areaName": "武城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371481",
                        "areaName": "乐陵市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371482",
                        "areaName": "禹城市"
                    }
                ],
                "areaId": "3714",
                "areaName": "德州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371502",
                        "areaName": "东昌府区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371521",
                        "areaName": "阳谷县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371522",
                        "areaName": "莘县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371523",
                        "areaName": "茌平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371524",
                        "areaName": "东阿县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371525",
                        "areaName": "冠县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371526",
                        "areaName": "高唐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371581",
                        "areaName": "临清市"
                    }
                ],
                "areaId": "3715",
                "areaName": "聊城市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371602",
                        "areaName": "滨城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371621",
                        "areaName": "惠民县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371622",
                        "areaName": "阳信县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371623",
                        "areaName": "无棣县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371624",
                        "areaName": "沾化县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371625",
                        "areaName": "博兴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371626",
                        "areaName": "邹平县"
                    }
                ],
                "areaId": "3716",
                "areaName": "滨州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371702",
                        "areaName": "牡丹区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371721",
                        "areaName": "曹县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371722",
                        "areaName": "单县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371723",
                        "areaName": "成武县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371724",
                        "areaName": "巨野县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371725",
                        "areaName": "郓城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371726",
                        "areaName": "鄄城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371727",
                        "areaName": "定陶县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "371728",
                        "areaName": "东明县"
                    }
                ],
                "areaId": "3717",
                "areaName": "菏泽市"
            }
        ],
        "areaId": "37",
        "areaName": "山东省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220101",
                        "areaName": "长春市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220102",
                        "areaName": "南关区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220103",
                        "areaName": "宽城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220104",
                        "areaName": "朝阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220105",
                        "areaName": "二道区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220106",
                        "areaName": "绿园区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220112",
                        "areaName": "双阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220122",
                        "areaName": "农安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220181",
                        "areaName": "九台市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220182",
                        "areaName": "榆树市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220183",
                        "areaName": "德惠市"
                    }
                ],
                "areaId": "2201",
                "areaName": "长春市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220201",
                        "areaName": "吉林市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220202",
                        "areaName": "昌邑区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220203",
                        "areaName": "龙潭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220204",
                        "areaName": "船营区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220211",
                        "areaName": "丰满区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220221",
                        "areaName": "永吉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220281",
                        "areaName": "蛟河市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220282",
                        "areaName": "桦甸市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220283",
                        "areaName": "舒兰市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220284",
                        "areaName": "磐石市"
                    }
                ],
                "areaId": "2202",
                "areaName": "吉林市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220301",
                        "areaName": "四平市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220302",
                        "areaName": "铁西区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220303",
                        "areaName": "铁东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220322",
                        "areaName": "梨树县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220323",
                        "areaName": "伊通满族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220381",
                        "areaName": "公主岭市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220382",
                        "areaName": "双辽市"
                    }
                ],
                "areaId": "2203",
                "areaName": "四平市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220401",
                        "areaName": "辽源市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220402",
                        "areaName": "龙山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220403",
                        "areaName": "西安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220421",
                        "areaName": "东丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220422",
                        "areaName": "东辽县"
                    }
                ],
                "areaId": "2204",
                "areaName": "辽源市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220501",
                        "areaName": "通化市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220502",
                        "areaName": "东昌区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220503",
                        "areaName": "二道江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220521",
                        "areaName": "通化县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220523",
                        "areaName": "辉南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220524",
                        "areaName": "柳河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220581",
                        "areaName": "梅河口市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220582",
                        "areaName": "集安市"
                    }
                ],
                "areaId": "2205",
                "areaName": "通化市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220601",
                        "areaName": "白山市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220602",
                        "areaName": "八道江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220604",
                        "areaName": "江源区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220621",
                        "areaName": "抚松县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220622",
                        "areaName": "靖宇县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220623",
                        "areaName": "长白朝鲜族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220681",
                        "areaName": "临江市"
                    }
                ],
                "areaId": "2206",
                "areaName": "白山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220701",
                        "areaName": "松原市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220702",
                        "areaName": "宁江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220721",
                        "areaName": "前郭尔罗斯蒙古族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220722",
                        "areaName": "长岭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220723",
                        "areaName": "乾安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220724",
                        "areaName": "扶余县"
                    }
                ],
                "areaId": "2207",
                "areaName": "松原市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220801",
                        "areaName": "白城市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220802",
                        "areaName": "洮北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220821",
                        "areaName": "镇赉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220822",
                        "areaName": "通榆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220881",
                        "areaName": "洮南市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "220882",
                        "areaName": "大安市"
                    }
                ],
                "areaId": "2208",
                "areaName": "白城市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "222401",
                        "areaName": "延吉市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "222402",
                        "areaName": "图们市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "222403",
                        "areaName": "敦化市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "222404",
                        "areaName": "珲春市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "222405",
                        "areaName": "龙井市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "222406",
                        "areaName": "和龙市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "222424",
                        "areaName": "汪清县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "222426",
                        "areaName": "安图县"
                    }
                ],
                "areaId": "2224",
                "areaName": "延边朝鲜族自治州"
            }
        ],
        "areaId": "22",
        "areaName": "吉林省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230102",
                        "areaName": "道里区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230103",
                        "areaName": "南岗区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230104",
                        "areaName": "道外区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230108",
                        "areaName": "平房区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230109",
                        "areaName": "松北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230110",
                        "areaName": "香坊区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230111",
                        "areaName": "呼兰区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230112",
                        "areaName": "阿城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230123",
                        "areaName": "依兰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230124",
                        "areaName": "方正县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230125",
                        "areaName": "宾县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230126",
                        "areaName": "巴彦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230127",
                        "areaName": "木兰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230128",
                        "areaName": "通河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230129",
                        "areaName": "延寿县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230182",
                        "areaName": "双城市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230183",
                        "areaName": "尚志市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230184",
                        "areaName": "五常市"
                    }
                ],
                "areaId": "2301",
                "areaName": "哈尔滨市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230202",
                        "areaName": "龙沙区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230203",
                        "areaName": "建华区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230204",
                        "areaName": "铁锋区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230205",
                        "areaName": "昂昂溪区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230206",
                        "areaName": "富拉尔基区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230207",
                        "areaName": "碾子山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230208",
                        "areaName": "梅里斯达斡尔族区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230221",
                        "areaName": "龙江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230223",
                        "areaName": "依安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230224",
                        "areaName": "泰来县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230225",
                        "areaName": "甘南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230227",
                        "areaName": "富裕县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230229",
                        "areaName": "克山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230230",
                        "areaName": "克东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230231",
                        "areaName": "拜泉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230281",
                        "areaName": "讷河市"
                    }
                ],
                "areaId": "2302",
                "areaName": "齐齐哈尔市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230302",
                        "areaName": "鸡冠区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230303",
                        "areaName": "恒山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230304",
                        "areaName": "滴道区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230305",
                        "areaName": "梨树区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230306",
                        "areaName": "城子河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230307",
                        "areaName": "麻山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230321",
                        "areaName": "鸡东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230381",
                        "areaName": "虎林市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230382",
                        "areaName": "密山市"
                    }
                ],
                "areaId": "2303",
                "areaName": "鸡西市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230402",
                        "areaName": "向阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230403",
                        "areaName": "工农区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230404",
                        "areaName": "南山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230405",
                        "areaName": "兴安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230406",
                        "areaName": "东山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230407",
                        "areaName": "兴山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230421",
                        "areaName": "萝北县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230422",
                        "areaName": "绥滨县"
                    }
                ],
                "areaId": "2304",
                "areaName": "鹤岗市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230502",
                        "areaName": "尖山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230503",
                        "areaName": "岭东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230505",
                        "areaName": "四方台区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230506",
                        "areaName": "宝山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230521",
                        "areaName": "集贤县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230522",
                        "areaName": "友谊县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230523",
                        "areaName": "宝清县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230524",
                        "areaName": "饶河县"
                    }
                ],
                "areaId": "2305",
                "areaName": "双鸭山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230602",
                        "areaName": "萨尔图区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230603",
                        "areaName": "龙凤区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230604",
                        "areaName": "让胡路区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230605",
                        "areaName": "红岗区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230606",
                        "areaName": "大同区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230621",
                        "areaName": "肇州县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230622",
                        "areaName": "肇源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230623",
                        "areaName": "林甸县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230624",
                        "areaName": "杜尔伯特县"
                    }
                ],
                "areaId": "2306",
                "areaName": "大庆市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230702",
                        "areaName": "伊春区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230703",
                        "areaName": "南岔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230704",
                        "areaName": "友好区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230705",
                        "areaName": "西林区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230706",
                        "areaName": "翠峦区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230707",
                        "areaName": "新青区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230708",
                        "areaName": "美溪区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230709",
                        "areaName": "金山屯区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230710",
                        "areaName": "五营区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230711",
                        "areaName": "乌马河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230712",
                        "areaName": "汤旺河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230713",
                        "areaName": "带岭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230714",
                        "areaName": "乌伊岭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230715",
                        "areaName": "红星区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230716",
                        "areaName": "上甘岭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230722",
                        "areaName": "嘉荫县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230781",
                        "areaName": "铁力市"
                    }
                ],
                "areaId": "2307",
                "areaName": "伊春市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230803",
                        "areaName": "向阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230804",
                        "areaName": "前进区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230805",
                        "areaName": "东风区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230811",
                        "areaName": "郊区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230822",
                        "areaName": "桦南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230826",
                        "areaName": "桦川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230828",
                        "areaName": "汤原县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230833",
                        "areaName": "抚远县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230881",
                        "areaName": "同江市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230882",
                        "areaName": "富锦市"
                    }
                ],
                "areaId": "2308",
                "areaName": "佳木斯市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230902",
                        "areaName": "新兴区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230903",
                        "areaName": "桃山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230904",
                        "areaName": "茄子河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "230921",
                        "areaName": "勃利县"
                    }
                ],
                "areaId": "2309",
                "areaName": "七台河市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231002",
                        "areaName": "东安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231003",
                        "areaName": "阳明区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231004",
                        "areaName": "爱民区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231005",
                        "areaName": "西安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231024",
                        "areaName": "东宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231025",
                        "areaName": "林口县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231081",
                        "areaName": "绥芬河市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231083",
                        "areaName": "海林市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231084",
                        "areaName": "宁安市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231085",
                        "areaName": "穆棱市"
                    }
                ],
                "areaId": "2310",
                "areaName": "牡丹江市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231102",
                        "areaName": "爱辉区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231121",
                        "areaName": "嫩江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231123",
                        "areaName": "逊克县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231124",
                        "areaName": "孙吴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231181",
                        "areaName": "北安市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231182",
                        "areaName": "五大连池市"
                    }
                ],
                "areaId": "2311",
                "areaName": "黑河市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231202",
                        "areaName": "北林区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231221",
                        "areaName": "望奎县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231222",
                        "areaName": "兰西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231223",
                        "areaName": "青冈县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231224",
                        "areaName": "庆安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231225",
                        "areaName": "明水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231226",
                        "areaName": "绥棱县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231281",
                        "areaName": "安达市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231282",
                        "areaName": "肇东市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "231283",
                        "areaName": "海伦市"
                    }
                ],
                "areaId": "2312",
                "areaName": "绥化市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "232701",
                        "areaName": "加格达奇区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "232702",
                        "areaName": "松岭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "232703",
                        "areaName": "新林区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "232704",
                        "areaName": "呼中区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "232721",
                        "areaName": "呼玛县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "232722",
                        "areaName": "塔河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "232723",
                        "areaName": "漠河县"
                    }
                ],
                "areaId": "2327",
                "areaName": "大兴安岭地区"
            }
        ],
        "areaId": "23",
        "areaName": "黑龙江省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310101",
                        "areaName": "黄浦区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310103",
                        "areaName": "卢湾区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310104",
                        "areaName": "徐汇区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310105",
                        "areaName": "长宁区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310106",
                        "areaName": "静安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310107",
                        "areaName": "普陀区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310108",
                        "areaName": "闸北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310109",
                        "areaName": "虹口区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310110",
                        "areaName": "杨浦区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310112",
                        "areaName": "闵行区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310113",
                        "areaName": "宝山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310114",
                        "areaName": "嘉定区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310115",
                        "areaName": "浦东新区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310116",
                        "areaName": "金山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310117",
                        "areaName": "松江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310118",
                        "areaName": "青浦区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310119",
                        "areaName": "南汇区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310120",
                        "areaName": "奉贤区"
                    }
                ],
                "areaId": "3101",
                "areaName": "市辖区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "310230",
                        "areaName": "崇明县"
                    }
                ],
                "areaId": "3102",
                "areaName": "县"
            }
        ],
        "areaId": "31",
        "areaName": "上海市"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320102",
                        "areaName": "玄武区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320103",
                        "areaName": "白下区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320104",
                        "areaName": "秦淮区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320105",
                        "areaName": "建邺区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320106",
                        "areaName": "鼓楼区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320107",
                        "areaName": "下关区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320111",
                        "areaName": "浦口区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320113",
                        "areaName": "栖霞区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320114",
                        "areaName": "雨花台区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320115",
                        "areaName": "江宁区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320116",
                        "areaName": "六合区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320124",
                        "areaName": "溧水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320125",
                        "areaName": "高淳县"
                    }
                ],
                "areaId": "3201",
                "areaName": "南京市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320202",
                        "areaName": "崇安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320203",
                        "areaName": "南长区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320204",
                        "areaName": "北塘区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320205",
                        "areaName": "锡山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320206",
                        "areaName": "惠山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320211",
                        "areaName": "滨湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320281",
                        "areaName": "江阴市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320282",
                        "areaName": "宜兴市"
                    }
                ],
                "areaId": "3202",
                "areaName": "无锡市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320302",
                        "areaName": "鼓楼区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320303",
                        "areaName": "云龙区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320304",
                        "areaName": "九里区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320305",
                        "areaName": "贾汪区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320311",
                        "areaName": "泉山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320321",
                        "areaName": "丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320322",
                        "areaName": "沛县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320323",
                        "areaName": "铜山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320324",
                        "areaName": "睢宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320381",
                        "areaName": "新沂市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320382",
                        "areaName": "邳州市"
                    }
                ],
                "areaId": "3203",
                "areaName": "徐州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320401",
                        "areaName": "常州市区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320402",
                        "areaName": "天宁区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320404",
                        "areaName": "钟楼区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320405",
                        "areaName": "戚墅堰区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320411",
                        "areaName": "新北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320412",
                        "areaName": "武进区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320481",
                        "areaName": "溧阳市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320482",
                        "areaName": "金坛市"
                    }
                ],
                "areaId": "3204",
                "areaName": "常州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320502",
                        "areaName": "沧浪区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320503",
                        "areaName": "平江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320504",
                        "areaName": "金阊区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320505",
                        "areaName": "苏州高新区虎丘区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320506",
                        "areaName": "吴中区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320507",
                        "areaName": "相城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320581",
                        "areaName": "常熟市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320582",
                        "areaName": "张家港市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320583",
                        "areaName": "昆山市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320584",
                        "areaName": "吴江市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320585",
                        "areaName": "太仓市"
                    }
                ],
                "areaId": "3205",
                "areaName": "苏州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320602",
                        "areaName": "崇川区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320611",
                        "areaName": "港闸区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320621",
                        "areaName": "海安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320623",
                        "areaName": "如东"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320681",
                        "areaName": "启东市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320682",
                        "areaName": "如皋市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320683",
                        "areaName": "通州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320684",
                        "areaName": "海门市"
                    }
                ],
                "areaId": "3206",
                "areaName": "南通市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320703",
                        "areaName": "连云区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320705",
                        "areaName": "新浦区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320706",
                        "areaName": "海州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320721",
                        "areaName": "赣榆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320722",
                        "areaName": "东海县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320723",
                        "areaName": "灌云县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320724",
                        "areaName": "灌南县"
                    }
                ],
                "areaId": "3207",
                "areaName": "连云港市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320802",
                        "areaName": "清河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320803",
                        "areaName": "楚州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320804",
                        "areaName": "淮阴区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320811",
                        "areaName": "清浦区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320826",
                        "areaName": "涟水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320829",
                        "areaName": "洪泽县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320830",
                        "areaName": "盱眙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320831",
                        "areaName": "金湖县"
                    }
                ],
                "areaId": "3208",
                "areaName": "淮安市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320902",
                        "areaName": "亭湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320903",
                        "areaName": "盐都区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320921",
                        "areaName": "响水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320922",
                        "areaName": "滨海县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320923",
                        "areaName": "阜宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320924",
                        "areaName": "射阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320925",
                        "areaName": "建湖县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320981",
                        "areaName": "东台市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "320982",
                        "areaName": "大丰市"
                    }
                ],
                "areaId": "3209",
                "areaName": "盐城市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321002",
                        "areaName": "广陵区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321003",
                        "areaName": "邗江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321011",
                        "areaName": "维扬区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321023",
                        "areaName": "宝应县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321081",
                        "areaName": "仪征市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321084",
                        "areaName": "高邮市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321088",
                        "areaName": "江都市"
                    }
                ],
                "areaId": "3210",
                "areaName": "扬州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321101",
                        "areaName": "市区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321102",
                        "areaName": "京口区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321111",
                        "areaName": "润州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321112",
                        "areaName": "丹徒区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321181",
                        "areaName": "丹阳市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321182",
                        "areaName": "扬中市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321183",
                        "areaName": "句容市"
                    }
                ],
                "areaId": "3211",
                "areaName": "镇江市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321202",
                        "areaName": "海陵区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321203",
                        "areaName": "高港区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321281",
                        "areaName": "兴化市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321282",
                        "areaName": "靖江市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321283",
                        "areaName": "泰兴市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321284",
                        "areaName": "姜堰市"
                    }
                ],
                "areaId": "3212",
                "areaName": "泰州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321302",
                        "areaName": "宿城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321311",
                        "areaName": "宿豫区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321322",
                        "areaName": "沭阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321323",
                        "areaName": "泗阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "321324",
                        "areaName": "泗洪县"
                    }
                ],
                "areaId": "3213",
                "areaName": "宿迁市"
            }
        ],
        "areaId": "32",
        "areaName": "江苏省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330102",
                        "areaName": "上城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330103",
                        "areaName": "下城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330104",
                        "areaName": "江干区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330105",
                        "areaName": "拱墅区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330106",
                        "areaName": "西湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330108",
                        "areaName": "滨江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330109",
                        "areaName": "萧山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330110",
                        "areaName": "余杭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330122",
                        "areaName": "桐庐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330127",
                        "areaName": "淳安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330182",
                        "areaName": "建德市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330183",
                        "areaName": "富阳市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330185",
                        "areaName": "临安市"
                    }
                ],
                "areaId": "3301",
                "areaName": "杭州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330203",
                        "areaName": "海曙区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330204",
                        "areaName": "江东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330205",
                        "areaName": "江北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330206",
                        "areaName": "北仑区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330211",
                        "areaName": "镇海区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330212",
                        "areaName": "鄞州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330225",
                        "areaName": "象山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330226",
                        "areaName": "宁海县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330281",
                        "areaName": "余姚市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330282",
                        "areaName": "慈溪市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330283",
                        "areaName": "奉化市"
                    }
                ],
                "areaId": "3302",
                "areaName": "宁波市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330302",
                        "areaName": "鹿城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330303",
                        "areaName": "龙湾区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330304",
                        "areaName": "瓯海区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330322",
                        "areaName": "洞头县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330324",
                        "areaName": "永嘉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330326",
                        "areaName": "平阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330327",
                        "areaName": "苍南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330328",
                        "areaName": "文成县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330329",
                        "areaName": "泰顺县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330381",
                        "areaName": "瑞安市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330382",
                        "areaName": "乐清市"
                    }
                ],
                "areaId": "3303",
                "areaName": "温州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330402",
                        "areaName": "南湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330411",
                        "areaName": "秀洲区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330421",
                        "areaName": "嘉善县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330424",
                        "areaName": "海盐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330481",
                        "areaName": "海宁市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330482",
                        "areaName": "平湖市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330483",
                        "areaName": "桐乡市"
                    }
                ],
                "areaId": "3304",
                "areaName": "嘉兴市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330502",
                        "areaName": "吴兴区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330503",
                        "areaName": "南浔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330521",
                        "areaName": "德清县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330522",
                        "areaName": "长兴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330523",
                        "areaName": "安吉县"
                    }
                ],
                "areaId": "3305",
                "areaName": "湖州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330602",
                        "areaName": "越城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330621",
                        "areaName": "绍兴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330624",
                        "areaName": "新昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330681",
                        "areaName": "诸暨市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330682",
                        "areaName": "上虞市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330683",
                        "areaName": "嵊州市"
                    }
                ],
                "areaId": "3306",
                "areaName": "绍兴市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330702",
                        "areaName": "婺城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330703",
                        "areaName": "金东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330723",
                        "areaName": "武义县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330726",
                        "areaName": "浦江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330727",
                        "areaName": "磐安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330781",
                        "areaName": "兰溪市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330782",
                        "areaName": "义乌市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330783",
                        "areaName": "东阳市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330784",
                        "areaName": "永康市"
                    }
                ],
                "areaId": "3307",
                "areaName": "金华市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330802",
                        "areaName": "柯城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330803",
                        "areaName": "衢江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330822",
                        "areaName": "常山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330824",
                        "areaName": "开化县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330825",
                        "areaName": "龙游县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330881",
                        "areaName": "江山市"
                    }
                ],
                "areaId": "3308",
                "areaName": "衢州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330902",
                        "areaName": "定海区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330903",
                        "areaName": "普陀区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330921",
                        "areaName": "岱山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "330922",
                        "areaName": "嵊泗县"
                    }
                ],
                "areaId": "3309",
                "areaName": "舟山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331002",
                        "areaName": "椒江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331003",
                        "areaName": "黄岩区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331004",
                        "areaName": "路桥区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331021",
                        "areaName": "玉环县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331022",
                        "areaName": "三门县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331023",
                        "areaName": "天台县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331024",
                        "areaName": "仙居县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331081",
                        "areaName": "温岭市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331082",
                        "areaName": "临海市"
                    }
                ],
                "areaId": "3310",
                "areaName": "台州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331102",
                        "areaName": "莲都区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331121",
                        "areaName": "青田县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331122",
                        "areaName": "缙云县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331123",
                        "areaName": "遂昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331124",
                        "areaName": "松阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331125",
                        "areaName": "云和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331126",
                        "areaName": "庆元县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331127",
                        "areaName": "景宁畲族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "331181",
                        "areaName": "龙泉市"
                    }
                ],
                "areaId": "3311",
                "areaName": "丽水市"
            }
        ],
        "areaId": "33",
        "areaName": "浙江省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341302",
                        "areaName": "墉桥区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341321",
                        "areaName": "砀山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341322",
                        "areaName": "萧县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341323",
                        "areaName": "灵璧县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341324",
                        "areaName": "泗县"
                    }
                ],
                "areaId": "3413",
                "areaName": "宿州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341402",
                        "areaName": "居巢区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341421",
                        "areaName": "庐江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341422",
                        "areaName": "无为县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341423",
                        "areaName": "含山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341424",
                        "areaName": "和县"
                    }
                ],
                "areaId": "3414",
                "areaName": "巢湖市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341502",
                        "areaName": "金安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341503",
                        "areaName": "裕安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341521",
                        "areaName": "寿县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341522",
                        "areaName": "霍邱县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341523",
                        "areaName": "舒城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341524",
                        "areaName": "金寨县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341525",
                        "areaName": "霍山县"
                    }
                ],
                "areaId": "3415",
                "areaName": "六安市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341602",
                        "areaName": "谯城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341621",
                        "areaName": "涡阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341622",
                        "areaName": "蒙城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341623",
                        "areaName": "利辛县"
                    }
                ],
                "areaId": "3416",
                "areaName": "亳州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341702",
                        "areaName": "贵池区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341721",
                        "areaName": "东至县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341722",
                        "areaName": "石台县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341723",
                        "areaName": "青阳县"
                    }
                ],
                "areaId": "3417",
                "areaName": "池州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341802",
                        "areaName": "宣州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341821",
                        "areaName": "郎溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341822",
                        "areaName": "广德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341823",
                        "areaName": "泾县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341824",
                        "areaName": "绩溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341825",
                        "areaName": "旌德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341881",
                        "areaName": "宁国市"
                    }
                ],
                "areaId": "3418",
                "areaName": "宣城市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340102",
                        "areaName": "瑶海区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340103",
                        "areaName": "庐阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340104",
                        "areaName": "蜀山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340111",
                        "areaName": "包河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340121",
                        "areaName": "长丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340122",
                        "areaName": "肥东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340123",
                        "areaName": "肥西县"
                    }
                ],
                "areaId": "3401",
                "areaName": "合肥市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340202",
                        "areaName": "镜湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340203",
                        "areaName": "弋江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340207",
                        "areaName": "鸠江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340208",
                        "areaName": "三山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340221",
                        "areaName": "芜湖县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340222",
                        "areaName": "繁昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340223",
                        "areaName": "南陵县"
                    }
                ],
                "areaId": "3402",
                "areaName": "芜湖市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340302",
                        "areaName": "龙子湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340303",
                        "areaName": "蚌山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340304",
                        "areaName": "禹会区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340311",
                        "areaName": "淮上区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340321",
                        "areaName": "怀远县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340322",
                        "areaName": "五河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340323",
                        "areaName": "固镇县"
                    }
                ],
                "areaId": "3403",
                "areaName": "蚌埠市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340402",
                        "areaName": "大通区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340403",
                        "areaName": "田家庵区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340404",
                        "areaName": "谢家集区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340405",
                        "areaName": "八公山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340406",
                        "areaName": "潘集区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340421",
                        "areaName": "凤台县"
                    }
                ],
                "areaId": "3404",
                "areaName": "淮南市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340502",
                        "areaName": "金家庄区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340503",
                        "areaName": "花山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340504",
                        "areaName": "雨山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340521",
                        "areaName": "当涂县"
                    }
                ],
                "areaId": "3405",
                "areaName": "马鞍山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340602",
                        "areaName": "杜集区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340603",
                        "areaName": "相山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340604",
                        "areaName": "烈山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340621",
                        "areaName": "濉溪县"
                    }
                ],
                "areaId": "3406",
                "areaName": "淮北市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340702",
                        "areaName": "铜官山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340703",
                        "areaName": "狮子山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340711",
                        "areaName": "铜陵市郊区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340721",
                        "areaName": "铜陵县"
                    }
                ],
                "areaId": "3407",
                "areaName": "铜陵市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340802",
                        "areaName": "迎江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340803",
                        "areaName": "大观区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340811",
                        "areaName": "宜秀区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340822",
                        "areaName": "怀宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340823",
                        "areaName": "枞阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340824",
                        "areaName": "潜山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340825",
                        "areaName": "太湖县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340826",
                        "areaName": "宿松县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340827",
                        "areaName": "望江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340828",
                        "areaName": "岳西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "340881",
                        "areaName": "桐城市"
                    }
                ],
                "areaId": "3408",
                "areaName": "安庆市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341002",
                        "areaName": "屯溪区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341003",
                        "areaName": "黄山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341004",
                        "areaName": "徽州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341021",
                        "areaName": "歙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341022",
                        "areaName": "休宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341023",
                        "areaName": "黟县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341024",
                        "areaName": "祁门县"
                    }
                ],
                "areaId": "3410",
                "areaName": "黄山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341102",
                        "areaName": "琅琊区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341103",
                        "areaName": "南谯区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341122",
                        "areaName": "来安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341124",
                        "areaName": "全椒县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341125",
                        "areaName": "定远县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341126",
                        "areaName": "凤阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341181",
                        "areaName": "天长市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341182",
                        "areaName": "明光市"
                    }
                ],
                "areaId": "3411",
                "areaName": "滁州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341221",
                        "areaName": "临泉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341222",
                        "areaName": "太和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341225",
                        "areaName": "阜南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341226",
                        "areaName": "颍上县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341282",
                        "areaName": "界首市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341202",
                        "areaName": "颍州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341203",
                        "areaName": "颍东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "341204",
                        "areaName": "颍泉区"
                    }
                ],
                "areaId": "3412",
                "areaName": "阜阳市"
            }
        ],
        "areaId": "34",
        "areaName": "安徽省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "460101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "460105",
                        "areaName": "秀英区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "460106",
                        "areaName": "龙华区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "460107",
                        "areaName": "琼山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "460108",
                        "areaName": "美兰区"
                    }
                ],
                "areaId": "4601",
                "areaName": "海口市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "460201",
                        "areaName": "市辖区"
                    }
                ],
                "areaId": "4602",
                "areaName": "三亚市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469001",
                        "areaName": "五指山市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469002",
                        "areaName": "琼海市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469003",
                        "areaName": "儋州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469005",
                        "areaName": "文昌市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469006",
                        "areaName": "万宁市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469007",
                        "areaName": "东方市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469025",
                        "areaName": "定安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469026",
                        "areaName": "屯昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469027",
                        "areaName": "澄迈县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469028",
                        "areaName": "临高县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469030",
                        "areaName": "白沙黎族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469031",
                        "areaName": "昌江黎族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469033",
                        "areaName": "乐东黎族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469034",
                        "areaName": "陵水黎族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469035",
                        "areaName": "保亭黎族苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469036",
                        "areaName": "琼中黎族苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469037",
                        "areaName": "西沙群岛"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469038",
                        "areaName": "南沙群岛"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "469039",
                        "areaName": "中沙群岛的岛礁及其海域"
                    }
                ],
                "areaId": "4690",
                "areaName": "省属虚拟市"
            }
        ],
        "areaId": "46",
        "areaName": "海南省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410102",
                        "areaName": "中原区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410103",
                        "areaName": "二七区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410104",
                        "areaName": "管城回族区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410105",
                        "areaName": "金水区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410106",
                        "areaName": "上街区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410108",
                        "areaName": "惠济区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410122",
                        "areaName": "中牟县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410181",
                        "areaName": "巩义市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410182",
                        "areaName": "荥阳市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410183",
                        "areaName": "新密市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410184",
                        "areaName": "新郑市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410185",
                        "areaName": "登封市"
                    }
                ],
                "areaId": "4101",
                "areaName": "郑州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410202",
                        "areaName": "龙亭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410203",
                        "areaName": "顺河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410204",
                        "areaName": "鼓楼区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410205",
                        "areaName": "禹王台区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410211",
                        "areaName": "金明区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410221",
                        "areaName": "杞县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410222",
                        "areaName": "通许县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410223",
                        "areaName": "尉氏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410224",
                        "areaName": "开封县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410225",
                        "areaName": "兰考县"
                    }
                ],
                "areaId": "4102",
                "areaName": "开封市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410302",
                        "areaName": "老城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410303",
                        "areaName": "西工区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410304",
                        "areaName": "廛河回族区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410305",
                        "areaName": "涧西区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410306",
                        "areaName": "吉利区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410307",
                        "areaName": "洛龙区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410322",
                        "areaName": "孟津县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410323",
                        "areaName": "新安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410324",
                        "areaName": "栾川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410325",
                        "areaName": "嵩县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410326",
                        "areaName": "汝阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410327",
                        "areaName": "宜阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410328",
                        "areaName": "洛宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410329",
                        "areaName": "伊川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410381",
                        "areaName": "偃师市"
                    }
                ],
                "areaId": "4103",
                "areaName": "洛阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410402",
                        "areaName": "新华区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410403",
                        "areaName": "卫东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410404",
                        "areaName": "石龙区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410411",
                        "areaName": "湛河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410421",
                        "areaName": "宝丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410422",
                        "areaName": "叶  县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410423",
                        "areaName": "鲁山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410425",
                        "areaName": "郏  县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410481",
                        "areaName": "舞钢市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410482",
                        "areaName": "汝州市"
                    }
                ],
                "areaId": "4104",
                "areaName": "平顶山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410502",
                        "areaName": "文峰区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410503",
                        "areaName": "北关区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410505",
                        "areaName": "殷都区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410506",
                        "areaName": "龙安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410522",
                        "areaName": "安阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410523",
                        "areaName": "汤阴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410526",
                        "areaName": "滑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410527",
                        "areaName": "内黄县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410581",
                        "areaName": "林州市"
                    }
                ],
                "areaId": "4105",
                "areaName": "安阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410602",
                        "areaName": "鹤山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410603",
                        "areaName": "山城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410611",
                        "areaName": "淇滨区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410621",
                        "areaName": "浚县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410622",
                        "areaName": "淇县"
                    }
                ],
                "areaId": "4106",
                "areaName": "鹤壁市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410702",
                        "areaName": "红旗区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410703",
                        "areaName": "卫滨区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410704",
                        "areaName": "凤泉区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410711",
                        "areaName": "牧野区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410721",
                        "areaName": "新乡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410724",
                        "areaName": "获嘉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410725",
                        "areaName": "原阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410726",
                        "areaName": "延津县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410727",
                        "areaName": "封丘县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410728",
                        "areaName": "长垣县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410781",
                        "areaName": "卫辉市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410782",
                        "areaName": "辉县市"
                    }
                ],
                "areaId": "4107",
                "areaName": "新乡市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410802",
                        "areaName": "解放区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410803",
                        "areaName": "中站区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410804",
                        "areaName": "马村区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410811",
                        "areaName": "山阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410821",
                        "areaName": "修武县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410822",
                        "areaName": "博爱县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410823",
                        "areaName": "武陟县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410825",
                        "areaName": "温县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410881",
                        "areaName": "济源市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410882",
                        "areaName": "沁阳市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410883",
                        "areaName": "孟州市"
                    }
                ],
                "areaId": "4108",
                "areaName": "焦作市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410902",
                        "areaName": "华龙区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410922",
                        "areaName": "清丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410923",
                        "areaName": "南乐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410926",
                        "areaName": "范县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410927",
                        "areaName": "台前县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "410928",
                        "areaName": "濮阳县"
                    }
                ],
                "areaId": "4109",
                "areaName": "濮阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411002",
                        "areaName": "魏都区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411023",
                        "areaName": "许昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411024",
                        "areaName": "鄢陵县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411025",
                        "areaName": "襄城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411081",
                        "areaName": "禹州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411082",
                        "areaName": "长葛市"
                    }
                ],
                "areaId": "4110",
                "areaName": "许昌市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411102",
                        "areaName": "源汇区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411103",
                        "areaName": "郾城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411104",
                        "areaName": "召陵区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411121",
                        "areaName": "舞阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411122",
                        "areaName": "临颖县"
                    }
                ],
                "areaId": "4111",
                "areaName": "漯河市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411202",
                        "areaName": "湖滨区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411221",
                        "areaName": "渑池县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411222",
                        "areaName": "陕县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411224",
                        "areaName": "卢氏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411281",
                        "areaName": "义马市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411282",
                        "areaName": "灵宝市"
                    }
                ],
                "areaId": "4112",
                "areaName": "三门峡市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411302",
                        "areaName": "宛城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411303",
                        "areaName": "卧龙区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411321",
                        "areaName": "南召县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411322",
                        "areaName": "方城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411323",
                        "areaName": "西峡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411324",
                        "areaName": "镇平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411325",
                        "areaName": "内乡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411326",
                        "areaName": "淅川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411327",
                        "areaName": "社旗县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411328",
                        "areaName": "唐河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411329",
                        "areaName": "新野县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411330",
                        "areaName": "桐柏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411381",
                        "areaName": "邓州市"
                    }
                ],
                "areaId": "4113",
                "areaName": "南阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411402",
                        "areaName": "梁园区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411403",
                        "areaName": "睢阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411421",
                        "areaName": "民权县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411422",
                        "areaName": "睢县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411423",
                        "areaName": "宁陵县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411424",
                        "areaName": "柘城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411425",
                        "areaName": "虞城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411426",
                        "areaName": "夏邑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411481",
                        "areaName": "永城市"
                    }
                ],
                "areaId": "4114",
                "areaName": "商丘市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411502",
                        "areaName": "浉河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411503",
                        "areaName": "平桥区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411521",
                        "areaName": "罗山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411522",
                        "areaName": "光山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411523",
                        "areaName": "新县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411524",
                        "areaName": "商城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411525",
                        "areaName": "固始县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411526",
                        "areaName": "潢川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411527",
                        "areaName": "淮滨县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411528",
                        "areaName": "息县"
                    }
                ],
                "areaId": "4115",
                "areaName": "信阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411602",
                        "areaName": "川汇区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411621",
                        "areaName": "扶沟县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411622",
                        "areaName": "西华县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411623",
                        "areaName": "商水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411624",
                        "areaName": "沈丘县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411625",
                        "areaName": "郸城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411626",
                        "areaName": "淮阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411627",
                        "areaName": "太康县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411628",
                        "areaName": "鹿邑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411681",
                        "areaName": "项城市"
                    }
                ],
                "areaId": "4116",
                "areaName": "周口市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411702",
                        "areaName": "驿城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411721",
                        "areaName": "西平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411722",
                        "areaName": "上蔡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411723",
                        "areaName": "平舆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411724",
                        "areaName": "正阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411725",
                        "areaName": "确山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411726",
                        "areaName": "泌阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411727",
                        "areaName": "汝南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411728",
                        "areaName": "遂平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "411729",
                        "areaName": "新蔡县"
                    }
                ],
                "areaId": "4117",
                "areaName": "驻马店市"
            }
        ],
        "areaId": "41",
        "areaName": "河南省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420102",
                        "areaName": "江岸区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420103",
                        "areaName": "江汉区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420104",
                        "areaName": "硚口区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420105",
                        "areaName": "汉阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420106",
                        "areaName": "武昌区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420107",
                        "areaName": "青山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420111",
                        "areaName": "洪山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420112",
                        "areaName": "东西湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420113",
                        "areaName": "汉南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420114",
                        "areaName": "蔡甸区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420115",
                        "areaName": "江夏区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420116",
                        "areaName": "黄陂区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420117",
                        "areaName": "武汉市新洲区"
                    }
                ],
                "areaId": "4201",
                "areaName": "武汉市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420202",
                        "areaName": "黄石港区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420203",
                        "areaName": "西塞山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420204",
                        "areaName": "下陆区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420205",
                        "areaName": "铁山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420222",
                        "areaName": "阳新县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420281",
                        "areaName": "大冶市"
                    }
                ],
                "areaId": "4202",
                "areaName": "黄石市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420302",
                        "areaName": "茅箭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420303",
                        "areaName": "张湾区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420321",
                        "areaName": "郧县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420322",
                        "areaName": "郧西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420323",
                        "areaName": "竹山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420324",
                        "areaName": "竹溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420325",
                        "areaName": "房县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420381",
                        "areaName": "丹江口市"
                    }
                ],
                "areaId": "4203",
                "areaName": "十堰市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420502",
                        "areaName": "西陵区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420503",
                        "areaName": "伍家岗区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420504",
                        "areaName": "点军区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420505",
                        "areaName": "猇亭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420506",
                        "areaName": "夷陵区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420525",
                        "areaName": "远安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420526",
                        "areaName": "兴山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420527",
                        "areaName": "秭归县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420528",
                        "areaName": "长阳土家族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420529",
                        "areaName": "五峰土家族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420581",
                        "areaName": "宜都市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420582",
                        "areaName": "当阳市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420583",
                        "areaName": "枝江市"
                    }
                ],
                "areaId": "4205",
                "areaName": "宜昌市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420602",
                        "areaName": "襄城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420606",
                        "areaName": "樊城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420607",
                        "areaName": "襄阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420624",
                        "areaName": "南漳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420625",
                        "areaName": "谷城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420626",
                        "areaName": "保康县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420682",
                        "areaName": "老河口市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420683",
                        "areaName": "枣阳市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420684",
                        "areaName": "宜城市"
                    }
                ],
                "areaId": "4206",
                "areaName": "襄樊市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420702",
                        "areaName": "粱子湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420703",
                        "areaName": "华容区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420704",
                        "areaName": "鄂城区"
                    }
                ],
                "areaId": "4207",
                "areaName": "鄂州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420802",
                        "areaName": "东宝区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420804",
                        "areaName": "掇刀区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420821",
                        "areaName": "京山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420822",
                        "areaName": "沙洋县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420881",
                        "areaName": "钟祥市"
                    }
                ],
                "areaId": "4208",
                "areaName": "荆门市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420902",
                        "areaName": "孝南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420921",
                        "areaName": "孝昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420922",
                        "areaName": "大悟县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420923",
                        "areaName": "云梦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420981",
                        "areaName": "应城市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420982",
                        "areaName": "安陆市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "420984",
                        "areaName": "汉川市"
                    }
                ],
                "areaId": "4209",
                "areaName": "孝感市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421002",
                        "areaName": "沙市区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421003",
                        "areaName": "荆州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421022",
                        "areaName": "公安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421023",
                        "areaName": "监利县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421024",
                        "areaName": "江陵县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421081",
                        "areaName": "石首市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421083",
                        "areaName": "洪湖市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421087",
                        "areaName": "松滋市"
                    }
                ],
                "areaId": "4210",
                "areaName": "荆州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421102",
                        "areaName": "黄州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421121",
                        "areaName": "团风县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421122",
                        "areaName": "红安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421123",
                        "areaName": "罗田县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421124",
                        "areaName": "英山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421125",
                        "areaName": "浠水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421126",
                        "areaName": "蕲春县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421127",
                        "areaName": "黄梅县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421181",
                        "areaName": "麻城市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421182",
                        "areaName": "武穴市"
                    }
                ],
                "areaId": "4211",
                "areaName": "黄冈市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421202",
                        "areaName": "咸安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421221",
                        "areaName": "嘉鱼县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421222",
                        "areaName": "通城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421223",
                        "areaName": "崇阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421224",
                        "areaName": "通山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421281",
                        "areaName": "赤壁市"
                    }
                ],
                "areaId": "4212",
                "areaName": "咸宁市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421302",
                        "areaName": "曾都区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "421381",
                        "areaName": "广水市"
                    }
                ],
                "areaId": "4213",
                "areaName": "随州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "422801",
                        "areaName": "恩施市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "422802",
                        "areaName": "利川市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "422822",
                        "areaName": "建始县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "422823",
                        "areaName": "巴东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "422825",
                        "areaName": "宣恩县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "422826",
                        "areaName": "咸丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "422827",
                        "areaName": "来凤县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "422828",
                        "areaName": "鹤峰县"
                    }
                ],
                "areaId": "4228",
                "areaName": "恩施州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "429004",
                        "areaName": "仙桃市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "429005",
                        "areaName": "潜江市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "429006",
                        "areaName": "天门市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "429021",
                        "areaName": "神农架林区"
                    }
                ],
                "areaId": "4290",
                "areaName": "省直辖行政单位"
            }
        ],
        "areaId": "42",
        "areaName": "湖北省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430102",
                        "areaName": "芙蓉区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430103",
                        "areaName": "天心区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430104",
                        "areaName": "岳麓区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430105",
                        "areaName": "开福区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430111",
                        "areaName": "雨花区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430121",
                        "areaName": "长沙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430122",
                        "areaName": "望城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430124",
                        "areaName": "宁乡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430181",
                        "areaName": "浏阳市"
                    }
                ],
                "areaId": "4301",
                "areaName": "长沙市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430202",
                        "areaName": "荷塘区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430203",
                        "areaName": "芦淞区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430204",
                        "areaName": "石峰区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430211",
                        "areaName": "天元区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430221",
                        "areaName": "株洲县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430223",
                        "areaName": "攸县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430224",
                        "areaName": "茶陵县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430225",
                        "areaName": "炎陵县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430281",
                        "areaName": "醴陵市"
                    }
                ],
                "areaId": "4302",
                "areaName": "株洲市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430302",
                        "areaName": "雨湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430304",
                        "areaName": "岳塘区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430321",
                        "areaName": "湘潭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430381",
                        "areaName": "湘乡市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430382",
                        "areaName": "韶山市"
                    }
                ],
                "areaId": "4303",
                "areaName": "湘潭市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430405",
                        "areaName": "珠晖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430406",
                        "areaName": "雁峰区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430407",
                        "areaName": "石鼓区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430408",
                        "areaName": "蒸湘区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430412",
                        "areaName": "南岳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430421",
                        "areaName": "衡阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430422",
                        "areaName": "衡南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430423",
                        "areaName": "衡山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430424",
                        "areaName": "衡东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430426",
                        "areaName": "祁东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430481",
                        "areaName": "耒阳市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430482",
                        "areaName": "常宁市"
                    }
                ],
                "areaId": "4304",
                "areaName": "衡阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430502",
                        "areaName": "双清区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430503",
                        "areaName": "大祥区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430511",
                        "areaName": "北塔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430521",
                        "areaName": "邵东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430522",
                        "areaName": "新邵县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430523",
                        "areaName": "邵阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430524",
                        "areaName": "隆回县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430525",
                        "areaName": "洞口县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430527",
                        "areaName": "绥宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430528",
                        "areaName": "新宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430529",
                        "areaName": "城步苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430581",
                        "areaName": "武冈市"
                    }
                ],
                "areaId": "4305",
                "areaName": "邵阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430602",
                        "areaName": "岳阳楼区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430603",
                        "areaName": "云溪区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430611",
                        "areaName": "君山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430621",
                        "areaName": "岳阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430623",
                        "areaName": "华容县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430624",
                        "areaName": "湘阴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430626",
                        "areaName": "平江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430681",
                        "areaName": "汩罗市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430682",
                        "areaName": "临湘市"
                    }
                ],
                "areaId": "4306",
                "areaName": "岳阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430702",
                        "areaName": "武陵区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430703",
                        "areaName": "鼎城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430721",
                        "areaName": "安乡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430722",
                        "areaName": "汉寿县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430723",
                        "areaName": "澧县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430724",
                        "areaName": "临澧县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430725",
                        "areaName": "桃源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430726",
                        "areaName": "石门县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430781",
                        "areaName": "津市市"
                    }
                ],
                "areaId": "4307",
                "areaName": "常德市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430802",
                        "areaName": "永定区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430811",
                        "areaName": "武陵源区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430821",
                        "areaName": "慈利县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430822",
                        "areaName": "桑植县"
                    }
                ],
                "areaId": "4308",
                "areaName": "张家界市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430902",
                        "areaName": "资阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430903",
                        "areaName": "赫山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430921",
                        "areaName": "南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430922",
                        "areaName": "桃江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430923",
                        "areaName": "安化县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "430981",
                        "areaName": "沅江市"
                    }
                ],
                "areaId": "4309",
                "areaName": "益阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431002",
                        "areaName": "北湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431003",
                        "areaName": "苏仙区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431021",
                        "areaName": "桂阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431022",
                        "areaName": "宜章县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431023",
                        "areaName": "永兴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431024",
                        "areaName": "嘉禾县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431025",
                        "areaName": "临武县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431026",
                        "areaName": "汝城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431027",
                        "areaName": "桂东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431028",
                        "areaName": "安仁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431081",
                        "areaName": "资兴市"
                    }
                ],
                "areaId": "4310",
                "areaName": "郴州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431102",
                        "areaName": "零陵区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431103",
                        "areaName": "冷水滩区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431121",
                        "areaName": "祁阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431122",
                        "areaName": "东安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431123",
                        "areaName": "双牌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431124",
                        "areaName": "道县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431125",
                        "areaName": "江永县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431126",
                        "areaName": "宁远县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431127",
                        "areaName": "蓝山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431128",
                        "areaName": "新田县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431129",
                        "areaName": "江华县"
                    }
                ],
                "areaId": "4311",
                "areaName": "永州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431202",
                        "areaName": "鹤城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431221",
                        "areaName": "中方县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431222",
                        "areaName": "沅陵县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431223",
                        "areaName": "辰溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431224",
                        "areaName": "溆浦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431225",
                        "areaName": "会同县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431226",
                        "areaName": "麻阳苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431227",
                        "areaName": "新晃侗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431228",
                        "areaName": "芷江侗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431229",
                        "areaName": "靖州苗族侗族县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431230",
                        "areaName": "通道侗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431281",
                        "areaName": "洪江市"
                    }
                ],
                "areaId": "4312",
                "areaName": "怀化市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431302",
                        "areaName": "娄星区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431321",
                        "areaName": "双峰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431322",
                        "areaName": "新化县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431381",
                        "areaName": "冷水江市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "431382",
                        "areaName": "涟源市"
                    }
                ],
                "areaId": "4313",
                "areaName": "娄底市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "433101",
                        "areaName": "吉首市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "433122",
                        "areaName": "泸溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "433123",
                        "areaName": "凤凰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "433124",
                        "areaName": "花垣县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "433125",
                        "areaName": "保靖县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "433126",
                        "areaName": "古丈县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "433127",
                        "areaName": "永顺县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "433130",
                        "areaName": "龙山县"
                    }
                ],
                "areaId": "4331",
                "areaName": "湘西土家族苗族自治州"
            }
        ],
        "areaId": "43",
        "areaName": "湖南省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440103",
                        "areaName": "荔湾区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440104",
                        "areaName": "越秀区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440105",
                        "areaName": "海珠区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440106",
                        "areaName": "天河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440111",
                        "areaName": "白云区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440112",
                        "areaName": "黄埔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440113",
                        "areaName": "番禺区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440114",
                        "areaName": "花都区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440115",
                        "areaName": "南沙区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440116",
                        "areaName": "萝岗区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440183",
                        "areaName": "增城市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440184",
                        "areaName": "从化市"
                    }
                ],
                "areaId": "4401",
                "areaName": "广州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440203",
                        "areaName": "武江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440204",
                        "areaName": "浈江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440205",
                        "areaName": "曲江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440222",
                        "areaName": "始兴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440224",
                        "areaName": "仁化县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440229",
                        "areaName": "翁源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440232",
                        "areaName": "乳源瑶族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440233",
                        "areaName": "新丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440281",
                        "areaName": "乐昌市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440282",
                        "areaName": "南雄市"
                    }
                ],
                "areaId": "4402",
                "areaName": "韶关市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440303",
                        "areaName": "罗湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440304",
                        "areaName": "福田区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440305",
                        "areaName": "南山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440306",
                        "areaName": "宝安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440307",
                        "areaName": "龙岗区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440308",
                        "areaName": "盐田区"
                    }
                ],
                "areaId": "4403",
                "areaName": "深圳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440402",
                        "areaName": "香洲区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440403",
                        "areaName": "斗门区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440404",
                        "areaName": "金湾区"
                    }
                ],
                "areaId": "4404",
                "areaName": "珠海市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440507",
                        "areaName": "龙湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440511",
                        "areaName": "金平区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440512",
                        "areaName": "濠江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440513",
                        "areaName": "潮阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440514",
                        "areaName": "潮南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440515",
                        "areaName": "澄海区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440523",
                        "areaName": "南澳县"
                    }
                ],
                "areaId": "4405",
                "areaName": "汕头市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440604",
                        "areaName": "禅城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440605",
                        "areaName": "南海区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440606",
                        "areaName": "顺德区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440607",
                        "areaName": "三水区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440608",
                        "areaName": "高明区"
                    }
                ],
                "areaId": "4406",
                "areaName": "佛山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440703",
                        "areaName": "蓬江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440704",
                        "areaName": "江海区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440705",
                        "areaName": "新会区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440781",
                        "areaName": "台山市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440783",
                        "areaName": "开平市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440784",
                        "areaName": "鹤山市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440785",
                        "areaName": "恩平市"
                    }
                ],
                "areaId": "4407",
                "areaName": "江门市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440802",
                        "areaName": "湛江市赤坎区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440803",
                        "areaName": "湛江市霞山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440804",
                        "areaName": "湛江市坡头区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440811",
                        "areaName": "湛江市麻章区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440823",
                        "areaName": "遂溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440825",
                        "areaName": "徐闻县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440881",
                        "areaName": "廉江市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440882",
                        "areaName": "雷州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440883",
                        "areaName": "吴川市"
                    }
                ],
                "areaId": "4408",
                "areaName": "湛江市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440902",
                        "areaName": "茂南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440903",
                        "areaName": "茂港区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440923",
                        "areaName": "电白县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440981",
                        "areaName": "高州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440982",
                        "areaName": "化州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "440983",
                        "areaName": "信宜市"
                    }
                ],
                "areaId": "4409",
                "areaName": "茂名市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441202",
                        "areaName": "端州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441203",
                        "areaName": "鼎湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441223",
                        "areaName": "广宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441224",
                        "areaName": "怀集县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441225",
                        "areaName": "封开县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441226",
                        "areaName": "德庆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441283",
                        "areaName": "高要市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441284",
                        "areaName": "四会市"
                    }
                ],
                "areaId": "4412",
                "areaName": "肇庆市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441302",
                        "areaName": "惠城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441303",
                        "areaName": "惠阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441322",
                        "areaName": "博罗县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441323",
                        "areaName": "惠东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441324",
                        "areaName": "龙门县"
                    }
                ],
                "areaId": "4413",
                "areaName": "惠州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441402",
                        "areaName": "梅江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441421",
                        "areaName": "梅县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441422",
                        "areaName": "大埔县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441423",
                        "areaName": "丰顺县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441424",
                        "areaName": "五华县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441426",
                        "areaName": "平远县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441427",
                        "areaName": "蕉岭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441481",
                        "areaName": "兴宁市"
                    }
                ],
                "areaId": "4414",
                "areaName": "梅州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441502",
                        "areaName": "城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441521",
                        "areaName": "海丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441523",
                        "areaName": "陆河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441581",
                        "areaName": "陆丰市"
                    }
                ],
                "areaId": "4415",
                "areaName": "汕尾市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441602",
                        "areaName": "源城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441621",
                        "areaName": "紫金县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441622",
                        "areaName": "龙川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441623",
                        "areaName": "连平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441624",
                        "areaName": "和平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441625",
                        "areaName": "东源县"
                    }
                ],
                "areaId": "4416",
                "areaName": "河源市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441702",
                        "areaName": "江城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441721",
                        "areaName": "阳西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441723",
                        "areaName": "阳东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441781",
                        "areaName": "阳春市"
                    }
                ],
                "areaId": "4417",
                "areaName": "阳江市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441802",
                        "areaName": "清城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441821",
                        "areaName": "佛冈县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441823",
                        "areaName": "阳山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441825",
                        "areaName": "连山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441826",
                        "areaName": "连南瑶族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441827",
                        "areaName": "清新县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441881",
                        "areaName": "英德市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "441882",
                        "areaName": "连州市"
                    }
                ],
                "areaId": "4418",
                "areaName": "清远市"
            },
            {
                "countChildren": 0,
                "childern": [],
                "areaId": "4419",
                "areaName": "东莞市"
            },
            {
                "countChildren": 0,
                "childern": [],
                "areaId": "4420",
                "areaName": "中山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445102",
                        "areaName": "潮州市湘桥区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445121",
                        "areaName": "潮州市潮安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445122",
                        "areaName": "潮州市饶平县"
                    }
                ],
                "areaId": "4451",
                "areaName": "潮州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445202",
                        "areaName": "榕城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445221",
                        "areaName": "揭东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445222",
                        "areaName": "揭西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445224",
                        "areaName": "惠来县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445281",
                        "areaName": "普宁市"
                    }
                ],
                "areaId": "4452",
                "areaName": "揭阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445302",
                        "areaName": "云城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445321",
                        "areaName": "新兴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445322",
                        "areaName": "郁南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445323",
                        "areaName": "云安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "445381",
                        "areaName": "罗定市"
                    }
                ],
                "areaId": "4453",
                "areaName": "云浮市"
            }
        ],
        "areaId": "44",
        "areaName": "广东省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450302",
                        "areaName": "秀峰区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450303",
                        "areaName": "叠彩区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450304",
                        "areaName": "象山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450305",
                        "areaName": "七星区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450311",
                        "areaName": "雁山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450321",
                        "areaName": "阳朔县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450322",
                        "areaName": "临桂县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450323",
                        "areaName": "灵川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450324",
                        "areaName": "全州县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450325",
                        "areaName": "兴安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450326",
                        "areaName": "永福县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450327",
                        "areaName": "灌阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450328",
                        "areaName": "龙胜各族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450329",
                        "areaName": "资源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450330",
                        "areaName": "平乐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450331",
                        "areaName": "荔浦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450332",
                        "areaName": "恭城县"
                    }
                ],
                "areaId": "4503",
                "areaName": "桂林市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450403",
                        "areaName": "万秀区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450404",
                        "areaName": "蝶山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450405",
                        "areaName": "长洲区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450421",
                        "areaName": "苍梧县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450422",
                        "areaName": "藤县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450423",
                        "areaName": "蒙山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450481",
                        "areaName": "岑溪市"
                    }
                ],
                "areaId": "4504",
                "areaName": "梧州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450502",
                        "areaName": "海城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450503",
                        "areaName": "银海区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450512",
                        "areaName": "铁山港区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450521",
                        "areaName": "合浦县"
                    }
                ],
                "areaId": "4505",
                "areaName": "北海市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450602",
                        "areaName": "港口区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450603",
                        "areaName": "防城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450621",
                        "areaName": "上思县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450681",
                        "areaName": "东兴市"
                    }
                ],
                "areaId": "4506",
                "areaName": "防城港市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450702",
                        "areaName": "钦南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450703",
                        "areaName": "钦北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450721",
                        "areaName": "灵山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450722",
                        "areaName": "浦北县"
                    }
                ],
                "areaId": "4507",
                "areaName": "钦州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450802",
                        "areaName": "港北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450803",
                        "areaName": "港南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450804",
                        "areaName": "覃塘区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450821",
                        "areaName": "平南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450881",
                        "areaName": "桂平市"
                    }
                ],
                "areaId": "4508",
                "areaName": "贵港市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450902",
                        "areaName": "玉州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450921",
                        "areaName": "容县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450922",
                        "areaName": "陆川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450923",
                        "areaName": "博白县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450924",
                        "areaName": "兴业县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450981",
                        "areaName": "北流市"
                    }
                ],
                "areaId": "4509",
                "areaName": "玉林市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451002",
                        "areaName": "右江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451021",
                        "areaName": "田阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451022",
                        "areaName": "田东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451023",
                        "areaName": "平果县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451024",
                        "areaName": "德保县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451025",
                        "areaName": "靖西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451026",
                        "areaName": "那坡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451027",
                        "areaName": "凌云县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451028",
                        "areaName": "乐业县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451029",
                        "areaName": "田林县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451030",
                        "areaName": "西林县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451031",
                        "areaName": "隆林各族自治县"
                    }
                ],
                "areaId": "4510",
                "areaName": "百色市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451102",
                        "areaName": "八步区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451121",
                        "areaName": "昭平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451122",
                        "areaName": "钟山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451123",
                        "areaName": "富川瑶族自治县"
                    }
                ],
                "areaId": "4511",
                "areaName": "贺州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451202",
                        "areaName": "金城江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451221",
                        "areaName": "南丹县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451222",
                        "areaName": "天峨县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451223",
                        "areaName": "凤山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451224",
                        "areaName": "东兰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451225",
                        "areaName": "罗城仫佬族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451226",
                        "areaName": "环江毛南族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451227",
                        "areaName": "巴马瑶族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451228",
                        "areaName": "都安瑶族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451229",
                        "areaName": "大化瑶族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451281",
                        "areaName": "宜州市"
                    }
                ],
                "areaId": "4512",
                "areaName": "河池市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451302",
                        "areaName": "兴宾区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451321",
                        "areaName": "忻城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451322",
                        "areaName": "象州县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451323",
                        "areaName": "武宣县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451324",
                        "areaName": "金秀瑶族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451381",
                        "areaName": "合山市"
                    }
                ],
                "areaId": "4513",
                "areaName": "来宾市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451402",
                        "areaName": "江州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451421",
                        "areaName": "扶绥县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451422",
                        "areaName": "宁明县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451423",
                        "areaName": "龙州县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451424",
                        "areaName": "大新县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451425",
                        "areaName": "天等县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "451481",
                        "areaName": "凭祥市"
                    }
                ],
                "areaId": "4514",
                "areaName": "崇左市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450102",
                        "areaName": "兴宁区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450103",
                        "areaName": "青秀区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450105",
                        "areaName": "江南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450107",
                        "areaName": "西乡塘区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450108",
                        "areaName": "良庆区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450109",
                        "areaName": "邕宁区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450122",
                        "areaName": "武鸣县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450123",
                        "areaName": "隆安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450124",
                        "areaName": "马山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450125",
                        "areaName": "上林县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450126",
                        "areaName": "宾阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450127",
                        "areaName": "横县"
                    }
                ],
                "areaId": "4501",
                "areaName": "南宁市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450202",
                        "areaName": "城中区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450203",
                        "areaName": "鱼峰区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450204",
                        "areaName": "柳南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450205",
                        "areaName": "柳北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450221",
                        "areaName": "柳江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450222",
                        "areaName": "柳城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450223",
                        "areaName": "鹿寨县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450224",
                        "areaName": "融安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450225",
                        "areaName": "融水苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "450226",
                        "areaName": "三江侗族自治县"
                    }
                ],
                "areaId": "4502",
                "areaName": "柳州市"
            }
        ],
        "areaId": "45",
        "areaName": "广西壮族自治区"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610102",
                        "areaName": "新城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610103",
                        "areaName": "碑林区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610104",
                        "areaName": "莲湖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610111",
                        "areaName": "灞桥区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610112",
                        "areaName": "未央区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610113",
                        "areaName": "雁塔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610114",
                        "areaName": "阎良区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610115",
                        "areaName": "临潼区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610116",
                        "areaName": "长安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610122",
                        "areaName": "蓝田县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610124",
                        "areaName": "周至县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610125",
                        "areaName": "户县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610126",
                        "areaName": "高陵县"
                    }
                ],
                "areaId": "6101",
                "areaName": "西安市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610202",
                        "areaName": "王益区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610203",
                        "areaName": "印台区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610204",
                        "areaName": "耀州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610222",
                        "areaName": "宜君县"
                    }
                ],
                "areaId": "6102",
                "areaName": "铜川市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610302",
                        "areaName": "渭滨区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610303",
                        "areaName": "金台区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610304",
                        "areaName": "陈仓区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610322",
                        "areaName": "凤翔县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610323",
                        "areaName": "岐山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610324",
                        "areaName": "扶风县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610326",
                        "areaName": "眉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610327",
                        "areaName": "陇县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610328",
                        "areaName": "千阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610329",
                        "areaName": "麟游县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610330",
                        "areaName": "凤县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610331",
                        "areaName": "太白县"
                    }
                ],
                "areaId": "6103",
                "areaName": "宝鸡市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610402",
                        "areaName": "秦都区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610403",
                        "areaName": "杨凌区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610404",
                        "areaName": "渭城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610422",
                        "areaName": "三原县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610423",
                        "areaName": "泾阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610424",
                        "areaName": "乾县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610425",
                        "areaName": "礼泉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610426",
                        "areaName": "永寿县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610427",
                        "areaName": "彬县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610428",
                        "areaName": "长武县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610429",
                        "areaName": "旬邑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610430",
                        "areaName": "淳化县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610431",
                        "areaName": "武功县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610481",
                        "areaName": "兴平市"
                    }
                ],
                "areaId": "6104",
                "areaName": "咸阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610502",
                        "areaName": "临渭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610521",
                        "areaName": "华县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610522",
                        "areaName": "潼关县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610523",
                        "areaName": "大荔县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610524",
                        "areaName": "合阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610525",
                        "areaName": "澄城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610526",
                        "areaName": "蒲城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610527",
                        "areaName": "白水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610528",
                        "areaName": "富平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610581",
                        "areaName": "韩城市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610582",
                        "areaName": "华阴市"
                    }
                ],
                "areaId": "6105",
                "areaName": "渭南市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610602",
                        "areaName": "宝塔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610621",
                        "areaName": "延长县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610622",
                        "areaName": "延川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610623",
                        "areaName": "子长县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610624",
                        "areaName": "安塞县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610625",
                        "areaName": "志丹县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610626",
                        "areaName": "吴起县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610627",
                        "areaName": "甘泉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610628",
                        "areaName": "富县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610629",
                        "areaName": "洛川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610630",
                        "areaName": "宜川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610631",
                        "areaName": "黄龙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610632",
                        "areaName": "黄陵县"
                    }
                ],
                "areaId": "6106",
                "areaName": "延安市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610702",
                        "areaName": "汉台区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610721",
                        "areaName": "南郑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610722",
                        "areaName": "城固县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610723",
                        "areaName": "洋县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610724",
                        "areaName": "西乡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610725",
                        "areaName": "勉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610726",
                        "areaName": "宁强县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610727",
                        "areaName": "略阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610728",
                        "areaName": "镇巴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610729",
                        "areaName": "留坝县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610730",
                        "areaName": "佛坪县"
                    }
                ],
                "areaId": "6107",
                "areaName": "汉中市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610802",
                        "areaName": "榆阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610821",
                        "areaName": "神木县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610822",
                        "areaName": "府谷县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610823",
                        "areaName": "横山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610824",
                        "areaName": "靖边县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610825",
                        "areaName": "定边县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610826",
                        "areaName": "绥德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610827",
                        "areaName": "米脂县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610828",
                        "areaName": "佳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610829",
                        "areaName": "吴堡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610830",
                        "areaName": "清涧县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610831",
                        "areaName": "子洲县"
                    }
                ],
                "areaId": "6108",
                "areaName": "榆林市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610902",
                        "areaName": "汉滨区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610921",
                        "areaName": "汉阴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610922",
                        "areaName": "石泉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610923",
                        "areaName": "宁陕县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610924",
                        "areaName": "紫阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610925",
                        "areaName": "岚皋县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610926",
                        "areaName": "平利县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610927",
                        "areaName": "镇坪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610928",
                        "areaName": "旬阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "610929",
                        "areaName": "白河县"
                    }
                ],
                "areaId": "6109",
                "areaName": "安康市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "611001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "611002",
                        "areaName": "商州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "611021",
                        "areaName": "洛南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "611022",
                        "areaName": "丹凤县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "611023",
                        "areaName": "商南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "611024",
                        "areaName": "山阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "611025",
                        "areaName": "镇安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "611026",
                        "areaName": "柞水县"
                    }
                ],
                "areaId": "6110",
                "areaName": "商洛市"
            }
        ],
        "areaId": "61",
        "areaName": "陕西省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620102",
                        "areaName": "城关区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620103",
                        "areaName": "七里河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620104",
                        "areaName": "兰州市西固区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620105",
                        "areaName": "安宁区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620111",
                        "areaName": "红古区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620121",
                        "areaName": "永登县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620122",
                        "areaName": "皋兰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620123",
                        "areaName": "榆中县"
                    }
                ],
                "areaId": "6201",
                "areaName": "兰州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620201",
                        "areaName": "市辖"
                    }
                ],
                "areaId": "6202",
                "areaName": "嘉峪关市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620302",
                        "areaName": "金川区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620321",
                        "areaName": "永昌县"
                    }
                ],
                "areaId": "6203",
                "areaName": "金昌市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620402",
                        "areaName": "白银区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620403",
                        "areaName": "平川区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620421",
                        "areaName": "靖远县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620422",
                        "areaName": "会宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620423",
                        "areaName": "景泰县"
                    }
                ],
                "areaId": "6204",
                "areaName": "白银市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620502",
                        "areaName": "秦州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620503",
                        "areaName": "麦积区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620521",
                        "areaName": "清水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620522",
                        "areaName": "秦安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620523",
                        "areaName": "甘谷县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620524",
                        "areaName": "武山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620525",
                        "areaName": "张家川县"
                    }
                ],
                "areaId": "6205",
                "areaName": "天水市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620602",
                        "areaName": "凉州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620621",
                        "areaName": "民勤县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620622",
                        "areaName": "古浪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620623",
                        "areaName": "天祝县"
                    }
                ],
                "areaId": "6206",
                "areaName": "武威市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620702",
                        "areaName": "甘州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620721",
                        "areaName": "肃南裕固族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620722",
                        "areaName": "民乐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620723",
                        "areaName": "临泽县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620724",
                        "areaName": "高台县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620725",
                        "areaName": "山丹县"
                    }
                ],
                "areaId": "6207",
                "areaName": "张掖市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620802",
                        "areaName": "崆峒区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620821",
                        "areaName": "泾川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620822",
                        "areaName": "灵台县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620823",
                        "areaName": "崇信县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620824",
                        "areaName": "华亭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620825",
                        "areaName": "庄浪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620826",
                        "areaName": "静宁县"
                    }
                ],
                "areaId": "6208",
                "areaName": "平凉市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620902",
                        "areaName": "肃州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620921",
                        "areaName": "金塔县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620922",
                        "areaName": "瓜州县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620923",
                        "areaName": "肃北蒙古族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620924",
                        "areaName": "阿克塞县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620981",
                        "areaName": "玉门市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "620982",
                        "areaName": "敦煌市"
                    }
                ],
                "areaId": "6209",
                "areaName": "酒泉市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621002",
                        "areaName": "西峰区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621021",
                        "areaName": "庆城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621022",
                        "areaName": "环县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621023",
                        "areaName": "华池县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621024",
                        "areaName": "合水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621025",
                        "areaName": "正宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621026",
                        "areaName": "宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621027",
                        "areaName": "镇原县"
                    }
                ],
                "areaId": "6210",
                "areaName": "庆阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621102",
                        "areaName": "安定区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621121",
                        "areaName": "通渭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621122",
                        "areaName": "陇西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621123",
                        "areaName": "渭源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621124",
                        "areaName": "临洮县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621125",
                        "areaName": "漳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621126",
                        "areaName": "岷县"
                    }
                ],
                "areaId": "6211",
                "areaName": "定西市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621202",
                        "areaName": "武都区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621221",
                        "areaName": "成县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621222",
                        "areaName": "文县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621223",
                        "areaName": "宕昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621224",
                        "areaName": "康县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621225",
                        "areaName": "西和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621226",
                        "areaName": "礼县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621227",
                        "areaName": "徽县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "621228",
                        "areaName": "两当县"
                    }
                ],
                "areaId": "6212",
                "areaName": "陇南市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "622901",
                        "areaName": "临夏市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "622921",
                        "areaName": "临夏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "622922",
                        "areaName": "康乐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "622923",
                        "areaName": "永靖县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "622924",
                        "areaName": "广河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "622925",
                        "areaName": "和政县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "622926",
                        "areaName": "东乡族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "622927",
                        "areaName": "积石山县"
                    }
                ],
                "areaId": "6229",
                "areaName": "临夏州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "623001",
                        "areaName": "合作市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "623021",
                        "areaName": "临潭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "623022",
                        "areaName": "卓尼县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "623023",
                        "areaName": "舟曲县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "623024",
                        "areaName": "迭部县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "623025",
                        "areaName": "玛曲县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "623026",
                        "areaName": "碌曲县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "623027",
                        "areaName": "夏河县"
                    }
                ],
                "areaId": "6230",
                "areaName": "甘南州"
            }
        ],
        "areaId": "62",
        "areaName": "甘肃省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "630101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "630102",
                        "areaName": "城东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "630103",
                        "areaName": "城中区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "630104",
                        "areaName": "城西区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "630105",
                        "areaName": "城北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "630121",
                        "areaName": "大通回族土族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "630122",
                        "areaName": "湟中县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "630123",
                        "areaName": "湟源县"
                    }
                ],
                "areaId": "6301",
                "areaName": "西宁市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632121",
                        "areaName": "平安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632122",
                        "areaName": "民和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632123",
                        "areaName": "乐都县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632126",
                        "areaName": "互助县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632127",
                        "areaName": "化隆回族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632128",
                        "areaName": "循化县"
                    }
                ],
                "areaId": "6321",
                "areaName": "海东地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632221",
                        "areaName": "门源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632222",
                        "areaName": "祁连县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632223",
                        "areaName": "海晏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632224",
                        "areaName": "刚察县"
                    }
                ],
                "areaId": "6322",
                "areaName": "海北州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632321",
                        "areaName": "同仁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632322",
                        "areaName": "尖扎县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632323",
                        "areaName": "泽库县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632324",
                        "areaName": "河南县"
                    }
                ],
                "areaId": "6323",
                "areaName": "黄南州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632521",
                        "areaName": "共和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632522",
                        "areaName": "同德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632523",
                        "areaName": "贵德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632524",
                        "areaName": "兴海县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632525",
                        "areaName": "贵南县"
                    }
                ],
                "areaId": "6325",
                "areaName": "海南州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632621",
                        "areaName": "玛沁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632622",
                        "areaName": "班玛县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632623",
                        "areaName": "甘德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632624",
                        "areaName": "达日县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632625",
                        "areaName": "久治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632626",
                        "areaName": "玛多县"
                    }
                ],
                "areaId": "6326",
                "areaName": "果洛州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632721",
                        "areaName": "玉树县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632722",
                        "areaName": "杂多县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632723",
                        "areaName": "称多县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632724",
                        "areaName": "治多县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632725",
                        "areaName": "囊谦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632726",
                        "areaName": "曲麻莱县"
                    }
                ],
                "areaId": "6327",
                "areaName": "玉树州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632801",
                        "areaName": "格尔木市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632802",
                        "areaName": "德令哈市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632821",
                        "areaName": "乌兰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632822",
                        "areaName": "都兰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "632823",
                        "areaName": "天峻县"
                    }
                ],
                "areaId": "6328",
                "areaName": "海西州"
            }
        ],
        "areaId": "63",
        "areaName": "青海省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640104",
                        "areaName": "兴庆区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640105",
                        "areaName": "西夏区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640106",
                        "areaName": "金凤区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640121",
                        "areaName": "永宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640122",
                        "areaName": "贺兰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640181",
                        "areaName": "灵武市"
                    }
                ],
                "areaId": "6401",
                "areaName": "银川市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640202",
                        "areaName": "大武口区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640205",
                        "areaName": "惠农区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640221",
                        "areaName": "平罗县"
                    }
                ],
                "areaId": "6402",
                "areaName": "石嘴山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640302",
                        "areaName": "利通区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640323",
                        "areaName": "盐池县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640324",
                        "areaName": "同心县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640381",
                        "areaName": "青铜峡市"
                    }
                ],
                "areaId": "6403",
                "areaName": "吴忠市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640402",
                        "areaName": "原州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640422",
                        "areaName": "西吉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640423",
                        "areaName": "隆德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640424",
                        "areaName": "泾源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640425",
                        "areaName": "彭阳县"
                    }
                ],
                "areaId": "6404",
                "areaName": "固原市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640502",
                        "areaName": "沙坡头区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640521",
                        "areaName": "中宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "640522",
                        "areaName": "海原县"
                    }
                ],
                "areaId": "6405",
                "areaName": "中卫市"
            }
        ],
        "areaId": "64",
        "areaName": "宁夏回族自治区"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650102",
                        "areaName": "天山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650103",
                        "areaName": "沙依巴克区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650104",
                        "areaName": "新市区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650105",
                        "areaName": "水磨沟区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650106",
                        "areaName": "头屯河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650107",
                        "areaName": "达坂城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650108",
                        "areaName": "东山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650121",
                        "areaName": "乌鲁木齐县"
                    }
                ],
                "areaId": "6501",
                "areaName": "乌鲁木齐市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650201",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650202",
                        "areaName": "独山子区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650203",
                        "areaName": "克拉玛依区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650204",
                        "areaName": "白碱滩区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "650205",
                        "areaName": "乌尔禾区"
                    }
                ],
                "areaId": "6502",
                "areaName": "克拉玛依市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652101",
                        "areaName": "吐鲁番市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652122",
                        "areaName": "鄯善县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652123",
                        "areaName": "托克逊县"
                    }
                ],
                "areaId": "6521",
                "areaName": "吐鲁番地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652201",
                        "areaName": "哈密市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652222",
                        "areaName": "巴里坤县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652223",
                        "areaName": "伊吾县"
                    }
                ],
                "areaId": "6522",
                "areaName": "哈密地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652301",
                        "areaName": "昌吉市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652302",
                        "areaName": "阜康市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652303",
                        "areaName": "米泉市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652323",
                        "areaName": "呼图壁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652324",
                        "areaName": "玛纳斯"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652325",
                        "areaName": "奇台县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652327",
                        "areaName": "吉木萨尔县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652328",
                        "areaName": "木垒县"
                    }
                ],
                "areaId": "6523",
                "areaName": "昌吉州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652701",
                        "areaName": "博乐市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652722",
                        "areaName": "精河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652723",
                        "areaName": "温泉县"
                    }
                ],
                "areaId": "6527",
                "areaName": "博尔塔拉蒙古自治州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652801",
                        "areaName": "库尔勒市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652822",
                        "areaName": "轮台县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652823",
                        "areaName": "尉犁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652824",
                        "areaName": "若羌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652825",
                        "areaName": "且末县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652826",
                        "areaName": "焉耆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652827",
                        "areaName": "和静县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652828",
                        "areaName": "和硕县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652829",
                        "areaName": "博湖县"
                    }
                ],
                "areaId": "6528",
                "areaName": "巴音郭楞蒙古自治州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652901",
                        "areaName": "阿克苏市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652922",
                        "areaName": "温宿县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652923",
                        "areaName": "库车县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652924",
                        "areaName": "沙雅县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652925",
                        "areaName": "新和县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652926",
                        "areaName": "拜城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652927",
                        "areaName": "乌什县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652928",
                        "areaName": "阿瓦提县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "652929",
                        "areaName": "柯坪县"
                    }
                ],
                "areaId": "6529",
                "areaName": "阿克苏地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653001",
                        "areaName": "阿图什市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653022",
                        "areaName": "阿克陶县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653023",
                        "areaName": "阿合奇县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653024",
                        "areaName": "乌恰县"
                    }
                ],
                "areaId": "6530",
                "areaName": "克州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653101",
                        "areaName": "喀什市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653121",
                        "areaName": "疏附县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653122",
                        "areaName": "疏勒县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653123",
                        "areaName": "英吉沙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653124",
                        "areaName": "泽普县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653125",
                        "areaName": "莎车县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653126",
                        "areaName": "叶城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653127",
                        "areaName": "麦盖提县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653128",
                        "areaName": "岳普湖县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653129",
                        "areaName": "伽师县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653130",
                        "areaName": "巴楚县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653131",
                        "areaName": "塔什库尔干县"
                    }
                ],
                "areaId": "6531",
                "areaName": "喀什地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653201",
                        "areaName": "和田市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653221",
                        "areaName": "和田县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653222",
                        "areaName": "墨玉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653223",
                        "areaName": "皮山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653224",
                        "areaName": "洛浦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653225",
                        "areaName": "策勒县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653226",
                        "areaName": "于田县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "653227",
                        "areaName": "民丰县"
                    }
                ],
                "areaId": "6532",
                "areaName": "和田地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654002",
                        "areaName": "伊宁市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654003",
                        "areaName": "奎屯市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654021",
                        "areaName": "伊宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654022",
                        "areaName": "察布查尔县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654023",
                        "areaName": "霍城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654024",
                        "areaName": "巩留县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654025",
                        "areaName": "新源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654026",
                        "areaName": "昭苏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654027",
                        "areaName": "特克斯县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654028",
                        "areaName": "尼勒克县"
                    }
                ],
                "areaId": "6540",
                "areaName": "伊犁州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654201",
                        "areaName": "塔城市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654202",
                        "areaName": "乌苏市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654221",
                        "areaName": "额敏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654223",
                        "areaName": "沙湾县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654224",
                        "areaName": "托里县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654225",
                        "areaName": "裕民县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654226",
                        "areaName": "和布克赛尔蒙古自治县"
                    }
                ],
                "areaId": "6542",
                "areaName": "塔城地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654301",
                        "areaName": "阿勒泰市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654321",
                        "areaName": "布尔津县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654322",
                        "areaName": "富蕴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654323",
                        "areaName": "福海县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654324",
                        "areaName": "哈巴河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654325",
                        "areaName": "青河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "654326",
                        "areaName": "吉木乃县"
                    }
                ],
                "areaId": "6543",
                "areaName": "阿勒泰地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "659001",
                        "areaName": "石河子市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "659002",
                        "areaName": "阿拉尔市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "659003",
                        "areaName": "图木舒克市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "659004",
                        "areaName": "五家渠市"
                    }
                ],
                "areaId": "6590",
                "areaName": "省直辖行政单位"
            }
        ],
        "areaId": "65",
        "areaName": "新疆维吾尔自治区"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500101",
                        "areaName": "万州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500102",
                        "areaName": "涪陵区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500103",
                        "areaName": "渝中区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500104",
                        "areaName": "大渡口区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500105",
                        "areaName": "江北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500106",
                        "areaName": "沙坪坝区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500107",
                        "areaName": "九龙坡区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500108",
                        "areaName": "南岸区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500109",
                        "areaName": "北碚区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500110",
                        "areaName": "万盛区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500111",
                        "areaName": "双桥区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500112",
                        "areaName": "渝北区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500113",
                        "areaName": "巴南区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500114",
                        "areaName": "黔江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500115",
                        "areaName": "长寿区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500116",
                        "areaName": "江津区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500117",
                        "areaName": "合川区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500118",
                        "areaName": "永川区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500119",
                        "areaName": "南川区"
                    }
                ],
                "areaId": "5001",
                "areaName": "市辖区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500222",
                        "areaName": "綦江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500223",
                        "areaName": "潼南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500224",
                        "areaName": "铜梁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500225",
                        "areaName": "大足县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500226",
                        "areaName": "荣昌县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500227",
                        "areaName": "璧山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500228",
                        "areaName": "梁平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500229",
                        "areaName": "城口县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500230",
                        "areaName": "丰都县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500231",
                        "areaName": "垫江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500232",
                        "areaName": "武隆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500233",
                        "areaName": "忠县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500234",
                        "areaName": "开县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500235",
                        "areaName": "云阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500236",
                        "areaName": "奉节县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500237",
                        "areaName": "巫山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500238",
                        "areaName": "巫溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500240",
                        "areaName": "石柱县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500241",
                        "areaName": "秀山土家族苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500242",
                        "areaName": "酉阳土家族苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "500243",
                        "areaName": "彭水苗族土家族自治县"
                    }
                ],
                "areaId": "5002",
                "areaName": "县"
            }
        ],
        "areaId": "50",
        "areaName": "重庆市"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510104",
                        "areaName": "锦江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510105",
                        "areaName": "青羊区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510106",
                        "areaName": "金牛区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510107",
                        "areaName": "武侯区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510108",
                        "areaName": "成华区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510112",
                        "areaName": "龙泉驿区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510113",
                        "areaName": "青白江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510114",
                        "areaName": "新都区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510115",
                        "areaName": "温江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510121",
                        "areaName": "金堂县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510122",
                        "areaName": "双流县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510124",
                        "areaName": "郫县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510129",
                        "areaName": "大邑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510131",
                        "areaName": "蒲江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510132",
                        "areaName": "新津县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510181",
                        "areaName": "都江堰市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510182",
                        "areaName": "彭州市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510183",
                        "areaName": "邛崃市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510184",
                        "areaName": "崇州市"
                    }
                ],
                "areaId": "5101",
                "areaName": "成都市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510302",
                        "areaName": "自流井区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510303",
                        "areaName": "贡井区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510304",
                        "areaName": "大安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510311",
                        "areaName": "沿滩区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510321",
                        "areaName": "荣县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510322",
                        "areaName": "富顺县"
                    }
                ],
                "areaId": "5103",
                "areaName": "自贡市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510402",
                        "areaName": "攀枝花东区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510403",
                        "areaName": "西区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510411",
                        "areaName": "仁和区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510421",
                        "areaName": "米易县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510422",
                        "areaName": "盐边县"
                    }
                ],
                "areaId": "5104",
                "areaName": "攀枝花市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510502",
                        "areaName": "江阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510503",
                        "areaName": "纳溪区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510504",
                        "areaName": "龙马潭区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510521",
                        "areaName": "泸县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510522",
                        "areaName": "合江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510524",
                        "areaName": "叙永县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510525",
                        "areaName": "古蔺县"
                    }
                ],
                "areaId": "5105",
                "areaName": "泸州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510603",
                        "areaName": "旌阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510623",
                        "areaName": "中江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510626",
                        "areaName": "罗江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510681",
                        "areaName": "广汉市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510682",
                        "areaName": "什邡市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510683",
                        "areaName": "绵竹市"
                    }
                ],
                "areaId": "5106",
                "areaName": "德阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510703",
                        "areaName": "涪城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510704",
                        "areaName": "游仙区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510722",
                        "areaName": "三台县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510723",
                        "areaName": "盐亭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510724",
                        "areaName": "安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510725",
                        "areaName": "梓潼县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510726",
                        "areaName": "北川羌族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510727",
                        "areaName": "平武县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510781",
                        "areaName": "江油市"
                    }
                ],
                "areaId": "5107",
                "areaName": "绵阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510802",
                        "areaName": "市中区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510811",
                        "areaName": "元坝区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510812",
                        "areaName": "朝天区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510821",
                        "areaName": "旺苍县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510822",
                        "areaName": "青川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510823",
                        "areaName": "剑阁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510824",
                        "areaName": "苍溪县"
                    }
                ],
                "areaId": "5108",
                "areaName": "广元市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510903",
                        "areaName": "船山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510904",
                        "areaName": "安居区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510921",
                        "areaName": "蓬溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510922",
                        "areaName": "射洪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "510923",
                        "areaName": "大英县"
                    }
                ],
                "areaId": "5109",
                "areaName": "遂宁市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511002",
                        "areaName": "市中区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511011",
                        "areaName": "东兴区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511024",
                        "areaName": "威远县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511025",
                        "areaName": "资中县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511028",
                        "areaName": "隆昌县"
                    }
                ],
                "areaId": "5110",
                "areaName": "内江市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511102",
                        "areaName": "市中区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511111",
                        "areaName": "沙湾区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511112",
                        "areaName": "五通桥区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511113",
                        "areaName": "金口河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511123",
                        "areaName": "犍为县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511124",
                        "areaName": "井研县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511126",
                        "areaName": "夹江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511129",
                        "areaName": "沐川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511132",
                        "areaName": "峨边彝族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511133",
                        "areaName": "马边彝族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511181",
                        "areaName": "峨眉山市"
                    }
                ],
                "areaId": "5111",
                "areaName": "乐山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511302",
                        "areaName": "顺庆区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511303",
                        "areaName": "高坪区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511304",
                        "areaName": "嘉陵区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511321",
                        "areaName": "南部县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511322",
                        "areaName": "营山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511323",
                        "areaName": "蓬安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511324",
                        "areaName": "仪陇县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511325",
                        "areaName": "西充县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511381",
                        "areaName": "阆中市"
                    }
                ],
                "areaId": "5113",
                "areaName": "南充市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511402",
                        "areaName": "东坡区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511421",
                        "areaName": "仁寿县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511422",
                        "areaName": "彭山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511423",
                        "areaName": "洪雅县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511424",
                        "areaName": "丹棱县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511425",
                        "areaName": "青神县"
                    }
                ],
                "areaId": "5114",
                "areaName": "眉山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511502",
                        "areaName": "翠屏区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511521",
                        "areaName": "宜宾县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511522",
                        "areaName": "南溪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511523",
                        "areaName": "江安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511524",
                        "areaName": "长宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511525",
                        "areaName": "高县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511526",
                        "areaName": "珙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511527",
                        "areaName": "筠连县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511528",
                        "areaName": "兴文县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511529",
                        "areaName": "屏山县"
                    }
                ],
                "areaId": "5115",
                "areaName": "宜宾市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511602",
                        "areaName": "广安区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511621",
                        "areaName": "岳池县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511622",
                        "areaName": "武胜县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511623",
                        "areaName": "邻水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511681",
                        "areaName": "华蓥市"
                    }
                ],
                "areaId": "5116",
                "areaName": "广安市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511702",
                        "areaName": "通川区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511721",
                        "areaName": "达县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511722",
                        "areaName": "宣汉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511723",
                        "areaName": "开江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511724",
                        "areaName": "大竹县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511725",
                        "areaName": "渠县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511781",
                        "areaName": "万源市"
                    }
                ],
                "areaId": "5117",
                "areaName": "达州市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511802",
                        "areaName": "雨城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511821",
                        "areaName": "名山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511822",
                        "areaName": "荥经县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511823",
                        "areaName": "汉源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511824",
                        "areaName": "石棉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511825",
                        "areaName": "天全县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511826",
                        "areaName": "芦山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511827",
                        "areaName": "宝兴县"
                    }
                ],
                "areaId": "5118",
                "areaName": "雅安市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511902",
                        "areaName": "巴州区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511921",
                        "areaName": "通江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511922",
                        "areaName": "南江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "511923",
                        "areaName": "平昌县"
                    }
                ],
                "areaId": "5119",
                "areaName": "巴中市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "512001",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "512002",
                        "areaName": "雁江区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "512021",
                        "areaName": "安岳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "512022",
                        "areaName": "乐至县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "512081",
                        "areaName": "简阳市"
                    }
                ],
                "areaId": "5120",
                "areaName": "资阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513221",
                        "areaName": "汶川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513222",
                        "areaName": "理县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513223",
                        "areaName": "茂县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513224",
                        "areaName": "松潘县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513225",
                        "areaName": "九寨沟县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513226",
                        "areaName": "金川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513227",
                        "areaName": "小金县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513228",
                        "areaName": "黑水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513229",
                        "areaName": "马尔康县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513230",
                        "areaName": "壤塘县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513231",
                        "areaName": "阿坝县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513232",
                        "areaName": "若尔盖县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513233",
                        "areaName": "红原县"
                    }
                ],
                "areaId": "5132",
                "areaName": "阿坝州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513321",
                        "areaName": "康定县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513322",
                        "areaName": "泸定县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513323",
                        "areaName": "丹巴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513324",
                        "areaName": "九龙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513325",
                        "areaName": "雅江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513326",
                        "areaName": "道孚县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513327",
                        "areaName": "炉霍县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513328",
                        "areaName": "甘孜县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513329",
                        "areaName": "新龙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513330",
                        "areaName": "德格县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513331",
                        "areaName": "白玉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513332",
                        "areaName": "石渠县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513333",
                        "areaName": "色达县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513334",
                        "areaName": "理塘县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513335",
                        "areaName": "巴塘县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513336",
                        "areaName": "乡城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513337",
                        "areaName": "稻城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513338",
                        "areaName": "得荣县"
                    }
                ],
                "areaId": "5133",
                "areaName": "甘孜藏族自治州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513401",
                        "areaName": "西昌市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513422",
                        "areaName": "木里藏族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513423",
                        "areaName": "盐源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513424",
                        "areaName": "德昌"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513425",
                        "areaName": "会理县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513426",
                        "areaName": "会东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513427",
                        "areaName": "宁南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513428",
                        "areaName": "普格县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513429",
                        "areaName": "布拖县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513430",
                        "areaName": "金阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513431",
                        "areaName": "昭觉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513432",
                        "areaName": "喜德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513433",
                        "areaName": "冕宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513434",
                        "areaName": "越西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513435",
                        "areaName": "甘洛县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513436",
                        "areaName": "美姑县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "513437",
                        "areaName": "雷波县"
                    }
                ],
                "areaId": "5134",
                "areaName": "凉山州"
            }
        ],
        "areaId": "51",
        "areaName": "四川省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520102",
                        "areaName": "南明区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520103",
                        "areaName": "云岩区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520111",
                        "areaName": "花溪区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520112",
                        "areaName": "乌当区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520113",
                        "areaName": "白云区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520114",
                        "areaName": "小河区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520121",
                        "areaName": "开阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520122",
                        "areaName": "息烽县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520123",
                        "areaName": "修文县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520181",
                        "areaName": "清镇市"
                    }
                ],
                "areaId": "5201",
                "areaName": "贵阳市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520201",
                        "areaName": "钟山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520203",
                        "areaName": "六枝特区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520221",
                        "areaName": "水城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520222",
                        "areaName": "盘县"
                    }
                ],
                "areaId": "5202",
                "areaName": "六盘水市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520302",
                        "areaName": "红花岗区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520303",
                        "areaName": "汇川区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520321",
                        "areaName": "遵义县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520322",
                        "areaName": "桐梓县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520323",
                        "areaName": "绥阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520324",
                        "areaName": "正安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520325",
                        "areaName": "道真仡佬族苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520326",
                        "areaName": "务川仡佬族苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520327",
                        "areaName": "凤冈县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520328",
                        "areaName": "湄潭县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520329",
                        "areaName": "余庆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520330",
                        "areaName": "习水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520381",
                        "areaName": "赤水市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520382",
                        "areaName": "仁怀市"
                    }
                ],
                "areaId": "5203",
                "areaName": "遵义市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520402",
                        "areaName": "西秀区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520421",
                        "areaName": "平坝县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520422",
                        "areaName": "普定县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520423",
                        "areaName": "镇宁布依族苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520424",
                        "areaName": "关岭自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "520425",
                        "areaName": "紫云苗族布依族自治县"
                    }
                ],
                "areaId": "5204",
                "areaName": "安顺市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522201",
                        "areaName": "铜仁市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522222",
                        "areaName": "江口县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522223",
                        "areaName": "玉屏侗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522224",
                        "areaName": "石阡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522225",
                        "areaName": "思南县　"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522226",
                        "areaName": "印江土家族苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522227",
                        "areaName": "德江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522228",
                        "areaName": "沿河土家族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522229",
                        "areaName": "松桃苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522230",
                        "areaName": "万山特区"
                    }
                ],
                "areaId": "5222",
                "areaName": "铜仁地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522301",
                        "areaName": "兴义市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522322",
                        "areaName": "兴仁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522323",
                        "areaName": "普安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522324",
                        "areaName": "晴隆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522325",
                        "areaName": "贞丰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522326",
                        "areaName": "望谟县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522327",
                        "areaName": "册亨县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522328",
                        "areaName": "安龙县"
                    }
                ],
                "areaId": "5223",
                "areaName": "黔西南州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522401",
                        "areaName": "毕节市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522422",
                        "areaName": "大方县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522423",
                        "areaName": "黔西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522424",
                        "areaName": "金沙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522425",
                        "areaName": "织金县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522426",
                        "areaName": "纳雍县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522427",
                        "areaName": "威宁彝族回族苗族自治县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522428",
                        "areaName": "赫章县"
                    }
                ],
                "areaId": "5224",
                "areaName": "毕节地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522601",
                        "areaName": "凯里市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522622",
                        "areaName": "黄平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522623",
                        "areaName": "施秉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522624",
                        "areaName": "三穗县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522625",
                        "areaName": "镇远县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522626",
                        "areaName": "岑巩县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522627",
                        "areaName": "天柱县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522628",
                        "areaName": "锦屏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522629",
                        "areaName": "剑河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522630",
                        "areaName": "台江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522631",
                        "areaName": "黎平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522632",
                        "areaName": "榕江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522633",
                        "areaName": "从江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522634",
                        "areaName": "雷山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522635",
                        "areaName": "麻江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522636",
                        "areaName": "丹寨县"
                    }
                ],
                "areaId": "5226",
                "areaName": "黔东南苗族侗族自治州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522701",
                        "areaName": "都匀市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522702",
                        "areaName": "福泉市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522722",
                        "areaName": "荔波县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522723",
                        "areaName": "贵定县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522725",
                        "areaName": "瓮安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522726",
                        "areaName": "独山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522727",
                        "areaName": "平塘县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522728",
                        "areaName": "罗甸县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522729",
                        "areaName": "长顺县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522730",
                        "areaName": "龙里县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522731",
                        "areaName": "惠水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "522732",
                        "areaName": "三都水族自治县"
                    }
                ],
                "areaId": "5227",
                "areaName": "黔南布依族苗族自治州"
            }
        ],
        "areaId": "52",
        "areaName": "贵州省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530102",
                        "areaName": "五华区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530103",
                        "areaName": "盘龙区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530111",
                        "areaName": "官渡区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530112",
                        "areaName": "西山区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530113",
                        "areaName": "东川区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530121",
                        "areaName": "呈贡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530122",
                        "areaName": "晋宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530124",
                        "areaName": "富民县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530125",
                        "areaName": "宜良县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530126",
                        "areaName": "石林县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530127",
                        "areaName": "嵩明县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530128",
                        "areaName": "禄劝县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530129",
                        "areaName": "寻甸县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530181",
                        "areaName": "安宁市"
                    }
                ],
                "areaId": "5301",
                "areaName": "昆明市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530301",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530302",
                        "areaName": "麒麟区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530321",
                        "areaName": "马龙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530322",
                        "areaName": "陆良县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530323",
                        "areaName": "师宗县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530324",
                        "areaName": "罗平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530325",
                        "areaName": "富源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530326",
                        "areaName": "会泽县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530328",
                        "areaName": "沾益县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530381",
                        "areaName": "宣威市"
                    }
                ],
                "areaId": "5303",
                "areaName": "曲靖市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530401",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530402",
                        "areaName": "红塔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530421",
                        "areaName": "江川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530422",
                        "areaName": "澄江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530423",
                        "areaName": "通海县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530424",
                        "areaName": "华宁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530425",
                        "areaName": "易门县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530426",
                        "areaName": "峨山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530427",
                        "areaName": "新平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530428",
                        "areaName": "元江县"
                    }
                ],
                "areaId": "5304",
                "areaName": "玉溪市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530501",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530502",
                        "areaName": "隆阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530521",
                        "areaName": "施甸县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530522",
                        "areaName": "腾冲县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530523",
                        "areaName": "龙陵县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530524",
                        "areaName": "昌宁县"
                    }
                ],
                "areaId": "5305",
                "areaName": "保山市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530601",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530602",
                        "areaName": "昭阳区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530621",
                        "areaName": "鲁甸县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530622",
                        "areaName": "巧家县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530623",
                        "areaName": "盐津县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530624",
                        "areaName": "大关县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530625",
                        "areaName": "永善县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530626",
                        "areaName": "绥江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530627",
                        "areaName": "镇雄县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530628",
                        "areaName": "彝良县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530629",
                        "areaName": "威信县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530630",
                        "areaName": "水富县"
                    }
                ],
                "areaId": "5306",
                "areaName": "昭通市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530701",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530702",
                        "areaName": "古城区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530721",
                        "areaName": "玉龙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530722",
                        "areaName": "永胜县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530723",
                        "areaName": "华坪县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530724",
                        "areaName": "宁蒗县"
                    }
                ],
                "areaId": "5307",
                "areaName": "丽江市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530801",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530802",
                        "areaName": "翠云区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530821",
                        "areaName": "普洱县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530822",
                        "areaName": "墨江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530823",
                        "areaName": "景东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530824",
                        "areaName": "景谷县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530825",
                        "areaName": "镇沅县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530826",
                        "areaName": "江城县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530827",
                        "areaName": "孟连县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530828",
                        "areaName": "澜沧县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530829",
                        "areaName": "西盟县"
                    }
                ],
                "areaId": "5308",
                "areaName": "思茅市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530901",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530902",
                        "areaName": "临翔区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530921",
                        "areaName": "凤庆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530922",
                        "areaName": "云县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530923",
                        "areaName": "永德县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530924",
                        "areaName": "镇康县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530925",
                        "areaName": "双江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530926",
                        "areaName": "耿马县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "530927",
                        "areaName": "沧源县"
                    }
                ],
                "areaId": "5309",
                "areaName": "临沧市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532301",
                        "areaName": "楚雄市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532322",
                        "areaName": "双柏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532323",
                        "areaName": "牟定县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532324",
                        "areaName": "南华县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532325",
                        "areaName": "姚安县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532326",
                        "areaName": "大姚县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532327",
                        "areaName": "永仁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532328",
                        "areaName": "元谋县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532329",
                        "areaName": "武定县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532331",
                        "areaName": "禄丰县"
                    }
                ],
                "areaId": "5323",
                "areaName": "楚雄州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532501",
                        "areaName": "个旧市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532502",
                        "areaName": "开远市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532522",
                        "areaName": "蒙自县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532523",
                        "areaName": "屏边县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532524",
                        "areaName": "建水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532525",
                        "areaName": "石屏县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532526",
                        "areaName": "弥勒县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532527",
                        "areaName": "泸西县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532528",
                        "areaName": "元阳县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532529",
                        "areaName": "红河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532530",
                        "areaName": "金平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532531",
                        "areaName": "绿春县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532532",
                        "areaName": "河口县"
                    }
                ],
                "areaId": "5325",
                "areaName": "红河州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532621",
                        "areaName": "文山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532622",
                        "areaName": "砚山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532623",
                        "areaName": "西畴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532624",
                        "areaName": "麻栗坡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532625",
                        "areaName": "马关县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532626",
                        "areaName": "丘北县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532627",
                        "areaName": "广南县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532628",
                        "areaName": "富宁县"
                    }
                ],
                "areaId": "5326",
                "areaName": "文山州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532801",
                        "areaName": "景洪市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532822",
                        "areaName": "勐海县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532823",
                        "areaName": "勐腊县"
                    }
                ],
                "areaId": "5328",
                "areaName": "西双版纳州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532901",
                        "areaName": "大理市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532922",
                        "areaName": "漾濞县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532923",
                        "areaName": "祥云县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532924",
                        "areaName": "宾川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532925",
                        "areaName": "弥渡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532926",
                        "areaName": "南涧县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532927",
                        "areaName": "巍山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532928",
                        "areaName": "永平县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532929",
                        "areaName": "云龙县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532930",
                        "areaName": "洱源县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532931",
                        "areaName": "剑川县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "532932",
                        "areaName": "鹤庆县"
                    }
                ],
                "areaId": "5329",
                "areaName": "大理州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533102",
                        "areaName": "瑞丽市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533103",
                        "areaName": "潞西市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533122",
                        "areaName": "梁河县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533123",
                        "areaName": "盈江县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533124",
                        "areaName": "陇川县"
                    }
                ],
                "areaId": "5331",
                "areaName": "德宏州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533321",
                        "areaName": "泸水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533323",
                        "areaName": "福贡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533324",
                        "areaName": "贡山县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533325",
                        "areaName": "兰坪县"
                    }
                ],
                "areaId": "5333",
                "areaName": "怒江州"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533421",
                        "areaName": "香格里拉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533422",
                        "areaName": "德钦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "533423",
                        "areaName": "维西县"
                    }
                ],
                "areaId": "5334",
                "areaName": "迪庆州"
            }
        ],
        "areaId": "53",
        "areaName": "云南省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542621",
                        "areaName": "林芝县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542622",
                        "areaName": "工布江达县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542623",
                        "areaName": "米林县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542624",
                        "areaName": "墨脱县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542625",
                        "areaName": "波密县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542626",
                        "areaName": "察隅县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542627",
                        "areaName": "朗县"
                    }
                ],
                "areaId": "5426",
                "areaName": "林芝地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "540101",
                        "areaName": "市辖区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "540102",
                        "areaName": "城关区"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "540121",
                        "areaName": "林周县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "540122",
                        "areaName": "当雄县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "540123",
                        "areaName": "尼木县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "540124",
                        "areaName": "曲水县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "540125",
                        "areaName": "堆龙德庆"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "540126",
                        "areaName": "达孜县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "540127",
                        "areaName": "墨竹工卡县"
                    }
                ],
                "areaId": "5401",
                "areaName": "拉萨市"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542121",
                        "areaName": "昌都县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542122",
                        "areaName": "江达县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542123",
                        "areaName": "贡觉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542124",
                        "areaName": "类乌齐县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542125",
                        "areaName": "丁青县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542126",
                        "areaName": "察亚县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542127",
                        "areaName": "八宿县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542128",
                        "areaName": "左贡县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542129",
                        "areaName": "芒康县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542132",
                        "areaName": "洛隆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542133",
                        "areaName": "边坝县"
                    }
                ],
                "areaId": "5421",
                "areaName": "昌都地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542221",
                        "areaName": "乃东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542222",
                        "areaName": "扎囊县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542223",
                        "areaName": "贡嘎县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542224",
                        "areaName": "桑日县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542225",
                        "areaName": "琼结县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542226",
                        "areaName": "曲松县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542227",
                        "areaName": "措美县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542228",
                        "areaName": "洛扎县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542229",
                        "areaName": "加查县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542231",
                        "areaName": "隆子县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542232",
                        "areaName": "错那县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542233",
                        "areaName": "浪卡子县"
                    }
                ],
                "areaId": "5422",
                "areaName": "山南地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542301",
                        "areaName": "日喀则市"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542322",
                        "areaName": "南木林县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542323",
                        "areaName": "江孜县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542324",
                        "areaName": "定日县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542325",
                        "areaName": "萨迦县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542326",
                        "areaName": "拉孜县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542327",
                        "areaName": "昂仁县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542328",
                        "areaName": "谢通门县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542329",
                        "areaName": "白朗县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542330",
                        "areaName": "仁布县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542331",
                        "areaName": "康马县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542332",
                        "areaName": "定结县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542333",
                        "areaName": "仲巴县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542334",
                        "areaName": "亚东县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542335",
                        "areaName": "吉隆县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542336",
                        "areaName": "聂拉木县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542337",
                        "areaName": "萨嘎县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542338",
                        "areaName": "岗巴县"
                    }
                ],
                "areaId": "5423",
                "areaName": "日喀则地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542421",
                        "areaName": "那曲县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542422",
                        "areaName": "嘉黎县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542423",
                        "areaName": "比如县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542424",
                        "areaName": "聂荣县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542425",
                        "areaName": "安多县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542426",
                        "areaName": "申扎县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542427",
                        "areaName": "索县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542428",
                        "areaName": "班戈县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542429",
                        "areaName": "巴青县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542430",
                        "areaName": "尼玛县"
                    }
                ],
                "areaId": "5424",
                "areaName": "那曲地区"
            },
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542527",
                        "areaName": "措勤县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542521",
                        "areaName": "普兰县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542522",
                        "areaName": "札达县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542523",
                        "areaName": "噶尔县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542524",
                        "areaName": "日土县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542525",
                        "areaName": "革吉县"
                    },
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "542526",
                        "areaName": "改则县"
                    }
                ],
                "areaId": "5425",
                "areaName": "阿里地区"
            }
        ],
        "areaId": "54",
        "areaName": "西藏自治区"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "710101",
                        "areaName": "台湾"
                    }
                ],
                "areaId": "7101",
                "areaName": "台湾"
            }
        ],
        "areaId": "71",
        "areaName": "台湾省"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "810101",
                        "areaName": "香港"
                    }
                ],
                "areaId": "8101",
                "areaName": "香港"
            }
        ],
        "areaId": "81",
        "areaName": "香港特别行政区"
    },
    {
        "countChildren": 0,
        "childern": [
            {
                "countChildren": 0,
                "childern": [
                    {
                        "countChildren": 0,
                        "childern": [],
                        "areaId": "820101",
                        "areaName": "澳门"
                    }
                ],
                "areaId": "8201",
                "areaName": "澳门"
            }
        ],
        "areaId": "82",
        "areaName": "澳门特别行政区"
    }
],
    arrayCity:[],
    arrayDistrict:[],
    index: 0,
    indexC: 0,
    indexD: 0,
    name:'',
    phoneNum:'',
    zipCode:'',
    detailAddress:'',
    pId:'',
    cId:'',
    dId:'',
    pName:'',
    cName:'',
    dName:'',
    addressInfo:{},
  },
  //省
  bindProvinceChange: function(e) {
    this.setData({
      index: e.detail.value,
      arrayCity: this.data.array[e.detail.value].childern,
      pId: this.data.array[e.detail.value].areaId,
      pName: this.data.array[e.detail.value].areaName,
    })
  },
  //市
  bindCityChange: function(e) {
    this.setData({
      indexC: e.detail.value,
      arrayDistrict: this.data.arrayCity[e.detail.value].childern,
      cId: this.data.arrayCity[e.detail.value].areaId,
      cName: this.data.arrayCity[e.detail.value].areaName,
    })
  },
  //区
  bindDistrictChange: function(e) {
    this.setData({
      indexD: e.detail.value,
      dId: this.data.arrayDistrict[e.detail.value].areaId,
      dName: this.data.arrayDistrict[e.detail.value].areaName,
    })
  },
  //收货人赋值
  bindNameInput: function(e) {
    this.setData({
      name: e.detail.value
    })
  },
  //手机号赋值
  bindPhoneInput: function(e) {
    this.setData({
      phoneNum: e.detail.value
    })
  },
  //邮政编码赋值
  bindZipCodeInput: function(e) {
    this.setData({
      zipCode: e.detail.value
    })
  },
  //详细地址赋值
  bindAddressInput: function(e) {
    this.setData({
      detailAddress: e.detail.value
    })
  },
  //保存
  addAddress:function(){
    var that = this;
    if(that.data.name.length == 0){
        wx.showToast({
            title: '收货人不能为空',
            icon: 'loading',
            mask: true
        })
    }else if(that.data.phoneNum.length == 0){
        wx.showToast({
            title: '手机号不能为空',
            icon: 'loading',
            mask: true
        })
    }else if(that.data.zipCode.length == 0){
        wx.showToast({
            title: '邮编不能为空',
            icon: 'loading',
            mask: true
        })
    }else if(that.data.pId.length == 0){
        wx.showToast({
            title: '请选所在省份',
            icon: 'loading',
            mask: true
        })
    }else if(that.data.cId.length == 0){
        wx.showToast({
            title: '请选择所在市',
            icon: 'loading',
            mask: true
        })
    }else if(that.data.dId.length == 0){
        wx.showToast({
            title: '请选择所在区县',
            icon: 'loading',
            mask: true
        })
    }else if(that.data.detailAddress.length == 0){
        wx.showToast({
            title: '详细地址不能为空为空',
            icon: 'loading',
            mask: true
        })
    }else{
        request.req(uri_save_address, {
            trueName: that.data.name,
            mobPhone: that.data.phoneNum,
            zipCode: that.data.zipCode,
            provinceId: that.data.pId,
            cityId: that.data.cId,
            areaId: that.data.dId,
            areaInfo:that.data.pName+','+ that.data.cName+','+ that.data.dName,
            address: that.data.detailAddress,
        }, (err, res) => {
            var result = res.data;
            if (result.result == 1) { //地址保存成功
            wx.navigateBack({
                delta: 1, // 回退前 delta(默认为1) 页面
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
            } else {
                wx.showToast({
                    title: '保存失败',
                    icon: 'loading',
                    duration: 1500
                })
            }
        })
    }
  },
  onLoad:function(options){
    // 生命周期函数--监听页面加载
    var that = this;
    this.setData({
        addressInfo : options,
        name: options.trueName,
        phoneNum: options.mobPhone,
        zipCode:options.zipCode,
        detailAddress:options.address,
    });
    // console.log('wwwwwwwwwwwwwwwwwwww');
    // console.log(that.data.addressInfo);
  },
})