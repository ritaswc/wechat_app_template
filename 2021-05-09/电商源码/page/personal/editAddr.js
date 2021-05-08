var app     = getApp();
var common  = require('../../util/util.js');
var address_info = {
      "code": "200",
      "count": 0,
      "data": [
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10500",
                  "name": "东城区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10501",
                  "name": "西城区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10502",
                  "name": "海淀区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10503",
                  "name": "朝阳区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10504",
                  "name": "崇文区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10505",
                  "name": "宣武区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10506",
                  "name": "丰台区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10507",
                  "name": "石景山区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10508",
                  "name": "房山区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10509",
                  "name": "门头沟区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10510",
                  "name": "通州区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10511",
                  "name": "顺义区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10512",
                  "name": "昌平区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10513",
                  "name": "怀柔区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10514",
                  "name": "平谷区",
                  "pCode": "10052"
                },
                {
                  "childrenList": null,
                  "code": "10515",
                  "name": "大兴区",
                  "pCode": "10052"
                }
              ],
              "code": "10052",
              "name": "北京",
              "pCode": "10002"
            }
          ],
          "code": "10002",
          "name": "北京市",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10398",
                  "name": "迎江区",
                  "pCode": "10036"
                },
                {
                  "childrenList": null,
                  "code": "10399",
                  "name": "大观区",
                  "pCode": "10036"
                },
                {
                  "childrenList": null,
                  "code": "10400",
                  "name": "宜秀区",
                  "pCode": "10036"
                },
                {
                  "childrenList": null,
                  "code": "10401",
                  "name": "桐城市",
                  "pCode": "10036"
                },
                {
                  "childrenList": null,
                  "code": "10402",
                  "name": "怀宁县",
                  "pCode": "10036"
                },
                {
                  "childrenList": null,
                  "code": "10403",
                  "name": "枞阳县",
                  "pCode": "10036"
                },
                {
                  "childrenList": null,
                  "code": "10404",
                  "name": "潜山县",
                  "pCode": "10036"
                },
                {
                  "childrenList": null,
                  "code": "10405",
                  "name": "太湖县",
                  "pCode": "10036"
                },
                {
                  "childrenList": null,
                  "code": "10406",
                  "name": "宿松县",
                  "pCode": "10036"
                },
                {
                  "childrenList": null,
                  "code": "10407",
                  "name": "望江县",
                  "pCode": "10036"
                },
                {
                  "childrenList": null,
                  "code": "10408",
                  "name": "岳西县",
                  "pCode": "10036"
                }
              ],
              "code": "10036",
              "name": "安庆",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10409",
                  "name": "中市区",
                  "pCode": "10037"
                },
                {
                  "childrenList": null,
                  "code": "10410",
                  "name": "东市区",
                  "pCode": "10037"
                },
                {
                  "childrenList": null,
                  "code": "10411",
                  "name": "西市区",
                  "pCode": "10037"
                },
                {
                  "childrenList": null,
                  "code": "10412",
                  "name": "郊区",
                  "pCode": "10037"
                },
                {
                  "childrenList": null,
                  "code": "10413",
                  "name": "怀远县",
                  "pCode": "10037"
                },
                {
                  "childrenList": null,
                  "code": "10414",
                  "name": "五河县",
                  "pCode": "10037"
                },
                {
                  "childrenList": null,
                  "code": "10415",
                  "name": "固镇县",
                  "pCode": "10037"
                }
              ],
              "code": "10037",
              "name": "蚌埠",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10416",
                  "name": "居巢区",
                  "pCode": "10038"
                },
                {
                  "childrenList": null,
                  "code": "10417",
                  "name": "庐江县",
                  "pCode": "10038"
                },
                {
                  "childrenList": null,
                  "code": "10418",
                  "name": "无为县",
                  "pCode": "10038"
                },
                {
                  "childrenList": null,
                  "code": "10419",
                  "name": "含山县",
                  "pCode": "10038"
                },
                {
                  "childrenList": null,
                  "code": "10420",
                  "name": "和县",
                  "pCode": "10038"
                }
              ],
              "code": "10038",
              "name": "巢湖",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10421",
                  "name": "贵池区",
                  "pCode": "10039"
                },
                {
                  "childrenList": null,
                  "code": "10422",
                  "name": "东至县",
                  "pCode": "10039"
                },
                {
                  "childrenList": null,
                  "code": "10423",
                  "name": "石台县",
                  "pCode": "10039"
                },
                {
                  "childrenList": null,
                  "code": "10424",
                  "name": "青阳县",
                  "pCode": "10039"
                }
              ],
              "code": "10039",
              "name": "池州",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10425",
                  "name": "琅琊区",
                  "pCode": "10040"
                },
                {
                  "childrenList": null,
                  "code": "10426",
                  "name": "南谯区",
                  "pCode": "10040"
                },
                {
                  "childrenList": null,
                  "code": "10427",
                  "name": "天长市",
                  "pCode": "10040"
                },
                {
                  "childrenList": null,
                  "code": "10428",
                  "name": "明光市",
                  "pCode": "10040"
                },
                {
                  "childrenList": null,
                  "code": "10429",
                  "name": "来安县",
                  "pCode": "10040"
                },
                {
                  "childrenList": null,
                  "code": "10430",
                  "name": "全椒县",
                  "pCode": "10040"
                },
                {
                  "childrenList": null,
                  "code": "10431",
                  "name": "定远县",
                  "pCode": "10040"
                },
                {
                  "childrenList": null,
                  "code": "10432",
                  "name": "凤阳县",
                  "pCode": "10040"
                }
              ],
              "code": "10040",
              "name": "滁州",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10433",
                  "name": "蚌山区",
                  "pCode": "10041"
                },
                {
                  "childrenList": null,
                  "code": "10434",
                  "name": "龙子湖区",
                  "pCode": "10041"
                },
                {
                  "childrenList": null,
                  "code": "10435",
                  "name": "禹会区",
                  "pCode": "10041"
                },
                {
                  "childrenList": null,
                  "code": "10436",
                  "name": "淮上区",
                  "pCode": "10041"
                },
                {
                  "childrenList": null,
                  "code": "10437",
                  "name": "颍州区",
                  "pCode": "10041"
                },
                {
                  "childrenList": null,
                  "code": "10438",
                  "name": "颍东区",
                  "pCode": "10041"
                },
                {
                  "childrenList": null,
                  "code": "10439",
                  "name": "颍泉区",
                  "pCode": "10041"
                },
                {
                  "childrenList": null,
                  "code": "10440",
                  "name": "界首市",
                  "pCode": "10041"
                },
                {
                  "childrenList": null,
                  "code": "10441",
                  "name": "临泉县",
                  "pCode": "10041"
                },
                {
                  "childrenList": null,
                  "code": "10442",
                  "name": "太和县",
                  "pCode": "10041"
                },
                {
                  "childrenList": null,
                  "code": "10443",
                  "name": "阜南县",
                  "pCode": "10041"
                },
                {
                  "childrenList": null,
                  "code": "10444",
                  "name": "颖上县",
                  "pCode": "10041"
                }
              ],
              "code": "10041",
              "name": "阜阳",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10445",
                  "name": "相山区",
                  "pCode": "10042"
                },
                {
                  "childrenList": null,
                  "code": "10446",
                  "name": "杜集区",
                  "pCode": "10042"
                },
                {
                  "childrenList": null,
                  "code": "10447",
                  "name": "烈山区",
                  "pCode": "10042"
                },
                {
                  "childrenList": null,
                  "code": "10448",
                  "name": "濉溪县",
                  "pCode": "10042"
                }
              ],
              "code": "10042",
              "name": "淮北",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10449",
                  "name": "田家庵区",
                  "pCode": "10043"
                },
                {
                  "childrenList": null,
                  "code": "10450",
                  "name": "大通区",
                  "pCode": "10043"
                },
                {
                  "childrenList": null,
                  "code": "10451",
                  "name": "谢家集区",
                  "pCode": "10043"
                },
                {
                  "childrenList": null,
                  "code": "10452",
                  "name": "八公山区",
                  "pCode": "10043"
                },
                {
                  "childrenList": null,
                  "code": "10453",
                  "name": "潘集区",
                  "pCode": "10043"
                },
                {
                  "childrenList": null,
                  "code": "10454",
                  "name": "凤台县",
                  "pCode": "10043"
                }
              ],
              "code": "10043",
              "name": "淮南",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10455",
                  "name": "屯溪区",
                  "pCode": "10044"
                },
                {
                  "childrenList": null,
                  "code": "10456",
                  "name": "黄山区",
                  "pCode": "10044"
                },
                {
                  "childrenList": null,
                  "code": "10457",
                  "name": "徽州区",
                  "pCode": "10044"
                },
                {
                  "childrenList": null,
                  "code": "10458",
                  "name": "歙县",
                  "pCode": "10044"
                },
                {
                  "childrenList": null,
                  "code": "10459",
                  "name": "休宁县",
                  "pCode": "10044"
                },
                {
                  "childrenList": null,
                  "code": "10460",
                  "name": "黟县",
                  "pCode": "10044"
                },
                {
                  "childrenList": null,
                  "code": "10461",
                  "name": "祁门县",
                  "pCode": "10044"
                }
              ],
              "code": "10044",
              "name": "黄山",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10462",
                  "name": "金安区",
                  "pCode": "10045"
                },
                {
                  "childrenList": null,
                  "code": "10463",
                  "name": "裕安区",
                  "pCode": "10045"
                },
                {
                  "childrenList": null,
                  "code": "10464",
                  "name": "寿县",
                  "pCode": "10045"
                },
                {
                  "childrenList": null,
                  "code": "10465",
                  "name": "霍邱县",
                  "pCode": "10045"
                },
                {
                  "childrenList": null,
                  "code": "10466",
                  "name": "舒城县",
                  "pCode": "10045"
                },
                {
                  "childrenList": null,
                  "code": "10467",
                  "name": "金寨县",
                  "pCode": "10045"
                },
                {
                  "childrenList": null,
                  "code": "10468",
                  "name": "霍山县",
                  "pCode": "10045"
                }
              ],
              "code": "10045",
              "name": "六安",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10469",
                  "name": "雨山区",
                  "pCode": "10046"
                },
                {
                  "childrenList": null,
                  "code": "10470",
                  "name": "花山区",
                  "pCode": "10046"
                },
                {
                  "childrenList": null,
                  "code": "10471",
                  "name": "金家庄区",
                  "pCode": "10046"
                },
                {
                  "childrenList": null,
                  "code": "10472",
                  "name": "当涂县",
                  "pCode": "10046"
                }
              ],
              "code": "10046",
              "name": "马鞍山",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10473",
                  "name": "埇桥区",
                  "pCode": "10047"
                },
                {
                  "childrenList": null,
                  "code": "10474",
                  "name": "砀山县",
                  "pCode": "10047"
                },
                {
                  "childrenList": null,
                  "code": "10475",
                  "name": "萧县",
                  "pCode": "10047"
                },
                {
                  "childrenList": null,
                  "code": "10476",
                  "name": "灵璧县",
                  "pCode": "10047"
                },
                {
                  "childrenList": null,
                  "code": "10477",
                  "name": "泗县",
                  "pCode": "10047"
                }
              ],
              "code": "10047",
              "name": "宿州",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10478",
                  "name": "铜官山区",
                  "pCode": "10048"
                },
                {
                  "childrenList": null,
                  "code": "10479",
                  "name": "狮子山区",
                  "pCode": "10048"
                },
                {
                  "childrenList": null,
                  "code": "10480",
                  "name": "郊区",
                  "pCode": "10048"
                },
                {
                  "childrenList": null,
                  "code": "10481",
                  "name": "铜陵县",
                  "pCode": "10048"
                }
              ],
              "code": "10048",
              "name": "铜陵",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10482",
                  "name": "镜湖区",
                  "pCode": "10049"
                },
                {
                  "childrenList": null,
                  "code": "10483",
                  "name": "弋江区",
                  "pCode": "10049"
                },
                {
                  "childrenList": null,
                  "code": "10484",
                  "name": "鸠江区",
                  "pCode": "10049"
                },
                {
                  "childrenList": null,
                  "code": "10485",
                  "name": "三山区",
                  "pCode": "10049"
                },
                {
                  "childrenList": null,
                  "code": "10486",
                  "name": "芜湖县",
                  "pCode": "10049"
                },
                {
                  "childrenList": null,
                  "code": "10487",
                  "name": "繁昌县",
                  "pCode": "10049"
                },
                {
                  "childrenList": null,
                  "code": "10488",
                  "name": "南陵县",
                  "pCode": "10049"
                }
              ],
              "code": "10049",
              "name": "芜湖",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10489",
                  "name": "宣州区",
                  "pCode": "10050"
                },
                {
                  "childrenList": null,
                  "code": "10490",
                  "name": "宁国市",
                  "pCode": "10050"
                },
                {
                  "childrenList": null,
                  "code": "10491",
                  "name": "郎溪县",
                  "pCode": "10050"
                },
                {
                  "childrenList": null,
                  "code": "10492",
                  "name": "广德县",
                  "pCode": "10050"
                },
                {
                  "childrenList": null,
                  "code": "10493",
                  "name": "泾县",
                  "pCode": "10050"
                },
                {
                  "childrenList": null,
                  "code": "10494",
                  "name": "绩溪县",
                  "pCode": "10050"
                },
                {
                  "childrenList": null,
                  "code": "10495",
                  "name": "旌德县",
                  "pCode": "10050"
                }
              ],
              "code": "10050",
              "name": "宣城",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10496",
                  "name": "涡阳县",
                  "pCode": "10051"
                },
                {
                  "childrenList": null,
                  "code": "10497",
                  "name": "蒙城县",
                  "pCode": "10051"
                },
                {
                  "childrenList": null,
                  "code": "10498",
                  "name": "利辛县",
                  "pCode": "10051"
                },
                {
                  "childrenList": null,
                  "code": "10499",
                  "name": "谯城区",
                  "pCode": "10051"
                }
              ],
              "code": "10051",
              "name": "亳州",
              "pCode": "10003"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13402",
                  "name": "庐阳区",
                  "pCode": "13401"
                },
                {
                  "childrenList": null,
                  "code": "13403",
                  "name": "瑶海区",
                  "pCode": "13401"
                },
                {
                  "childrenList": null,
                  "code": "13404",
                  "name": "蜀山区",
                  "pCode": "13401"
                },
                {
                  "childrenList": null,
                  "code": "13405",
                  "name": "包河区",
                  "pCode": "13401"
                },
                {
                  "childrenList": null,
                  "code": "13406",
                  "name": "长丰县",
                  "pCode": "13401"
                },
                {
                  "childrenList": null,
                  "code": "13407",
                  "name": "肥东县",
                  "pCode": "13401"
                },
                {
                  "childrenList": null,
                  "code": "13408",
                  "name": "肥西县",
                  "pCode": "13401"
                }
              ],
              "code": "13401",
              "name": "合肥",
              "pCode": "10003"
            }
          ],
          "code": "10003",
          "name": "安徽省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10518",
                  "name": "鼓楼区",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10519",
                  "name": "台江区",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10520",
                  "name": "仓山区",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10521",
                  "name": "马尾区",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10522",
                  "name": "晋安区",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10523",
                  "name": "福清市",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10524",
                  "name": "长乐市",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10525",
                  "name": "闽侯县",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10526",
                  "name": "连江县",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10527",
                  "name": "罗源县",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10528",
                  "name": "闽清县",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10529",
                  "name": "永泰县",
                  "pCode": "10053"
                },
                {
                  "childrenList": null,
                  "code": "10530",
                  "name": "平潭县",
                  "pCode": "10053"
                }
              ],
              "code": "10053",
              "name": "福州",
              "pCode": "10004"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10531",
                  "name": "新罗区",
                  "pCode": "10054"
                },
                {
                  "childrenList": null,
                  "code": "10532",
                  "name": "漳平市",
                  "pCode": "10054"
                },
                {
                  "childrenList": null,
                  "code": "10533",
                  "name": "长汀县",
                  "pCode": "10054"
                },
                {
                  "childrenList": null,
                  "code": "10534",
                  "name": "永定县",
                  "pCode": "10054"
                },
                {
                  "childrenList": null,
                  "code": "10535",
                  "name": "上杭县",
                  "pCode": "10054"
                },
                {
                  "childrenList": null,
                  "code": "10536",
                  "name": "武平县",
                  "pCode": "10054"
                },
                {
                  "childrenList": null,
                  "code": "10537",
                  "name": "连城县",
                  "pCode": "10054"
                }
              ],
              "code": "10054",
              "name": "龙岩",
              "pCode": "10004"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10538",
                  "name": "延平区",
                  "pCode": "10055"
                },
                {
                  "childrenList": null,
                  "code": "10539",
                  "name": "邵武市",
                  "pCode": "10055"
                },
                {
                  "childrenList": null,
                  "code": "10540",
                  "name": "武夷山市",
                  "pCode": "10055"
                },
                {
                  "childrenList": null,
                  "code": "10541",
                  "name": "建瓯市",
                  "pCode": "10055"
                },
                {
                  "childrenList": null,
                  "code": "10542",
                  "name": "建阳市",
                  "pCode": "10055"
                },
                {
                  "childrenList": null,
                  "code": "10543",
                  "name": "顺昌县",
                  "pCode": "10055"
                },
                {
                  "childrenList": null,
                  "code": "10544",
                  "name": "浦城县",
                  "pCode": "10055"
                },
                {
                  "childrenList": null,
                  "code": "10545",
                  "name": "光泽县",
                  "pCode": "10055"
                },
                {
                  "childrenList": null,
                  "code": "10546",
                  "name": "松溪县",
                  "pCode": "10055"
                },
                {
                  "childrenList": null,
                  "code": "10547",
                  "name": "政和县",
                  "pCode": "10055"
                }
              ],
              "code": "10055",
              "name": "南平",
              "pCode": "10004"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10548",
                  "name": "蕉城区",
                  "pCode": "10056"
                },
                {
                  "childrenList": null,
                  "code": "10549",
                  "name": "福安市",
                  "pCode": "10056"
                },
                {
                  "childrenList": null,
                  "code": "10550",
                  "name": "福鼎市",
                  "pCode": "10056"
                },
                {
                  "childrenList": null,
                  "code": "10551",
                  "name": "霞浦县",
                  "pCode": "10056"
                },
                {
                  "childrenList": null,
                  "code": "10552",
                  "name": "古田县",
                  "pCode": "10056"
                },
                {
                  "childrenList": null,
                  "code": "10553",
                  "name": "屏南县",
                  "pCode": "10056"
                },
                {
                  "childrenList": null,
                  "code": "10554",
                  "name": "寿宁县",
                  "pCode": "10056"
                },
                {
                  "childrenList": null,
                  "code": "10555",
                  "name": "周宁县",
                  "pCode": "10056"
                },
                {
                  "childrenList": null,
                  "code": "10556",
                  "name": "柘荣县",
                  "pCode": "10056"
                }
              ],
              "code": "10056",
              "name": "宁德",
              "pCode": "10004"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10557",
                  "name": "城厢区",
                  "pCode": "10057"
                },
                {
                  "childrenList": null,
                  "code": "10558",
                  "name": "涵江区",
                  "pCode": "10057"
                },
                {
                  "childrenList": null,
                  "code": "10559",
                  "name": "荔城区",
                  "pCode": "10057"
                },
                {
                  "childrenList": null,
                  "code": "10560",
                  "name": "秀屿区",
                  "pCode": "10057"
                },
                {
                  "childrenList": null,
                  "code": "10561",
                  "name": "仙游县",
                  "pCode": "10057"
                }
              ],
              "code": "10057",
              "name": "莆田",
              "pCode": "10004"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10562",
                  "name": "鲤城区",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10563",
                  "name": "丰泽区",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10564",
                  "name": "洛江区",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10565",
                  "name": "清濛开发区",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10566",
                  "name": "泉港区",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10567",
                  "name": "石狮市",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10568",
                  "name": "晋江市",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10569",
                  "name": "南安市",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10570",
                  "name": "惠安县",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10571",
                  "name": "安溪县",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10572",
                  "name": "永春县",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10573",
                  "name": "德化县",
                  "pCode": "10058"
                },
                {
                  "childrenList": null,
                  "code": "10574",
                  "name": "金门县",
                  "pCode": "10058"
                }
              ],
              "code": "10058",
              "name": "泉州",
              "pCode": "10004"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10575",
                  "name": "梅列区",
                  "pCode": "10059"
                },
                {
                  "childrenList": null,
                  "code": "10576",
                  "name": "三元区",
                  "pCode": "10059"
                },
                {
                  "childrenList": null,
                  "code": "10577",
                  "name": "永安市",
                  "pCode": "10059"
                },
                {
                  "childrenList": null,
                  "code": "10578",
                  "name": "明溪县",
                  "pCode": "10059"
                },
                {
                  "childrenList": null,
                  "code": "10579",
                  "name": "清流县",
                  "pCode": "10059"
                },
                {
                  "childrenList": null,
                  "code": "10580",
                  "name": "宁化县",
                  "pCode": "10059"
                },
                {
                  "childrenList": null,
                  "code": "10581",
                  "name": "大田县",
                  "pCode": "10059"
                },
                {
                  "childrenList": null,
                  "code": "10582",
                  "name": "尤溪县",
                  "pCode": "10059"
                },
                {
                  "childrenList": null,
                  "code": "10583",
                  "name": "沙县",
                  "pCode": "10059"
                },
                {
                  "childrenList": null,
                  "code": "10584",
                  "name": "将乐县",
                  "pCode": "10059"
                },
                {
                  "childrenList": null,
                  "code": "10585",
                  "name": "泰宁县",
                  "pCode": "10059"
                },
                {
                  "childrenList": null,
                  "code": "10586",
                  "name": "建宁县",
                  "pCode": "10059"
                }
              ],
              "code": "10059",
              "name": "三明",
              "pCode": "10004"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10587",
                  "name": "思明区",
                  "pCode": "10060"
                },
                {
                  "childrenList": null,
                  "code": "10588",
                  "name": "海沧区",
                  "pCode": "10060"
                },
                {
                  "childrenList": null,
                  "code": "10589",
                  "name": "湖里区",
                  "pCode": "10060"
                },
                {
                  "childrenList": null,
                  "code": "10590",
                  "name": "集美区",
                  "pCode": "10060"
                },
                {
                  "childrenList": null,
                  "code": "10591",
                  "name": "同安区",
                  "pCode": "10060"
                },
                {
                  "childrenList": null,
                  "code": "10592",
                  "name": "翔安区",
                  "pCode": "10060"
                }
              ],
              "code": "10060",
              "name": "厦门",
              "pCode": "10004"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10593",
                  "name": "芗城区",
                  "pCode": "10061"
                },
                {
                  "childrenList": null,
                  "code": "10594",
                  "name": "龙文区",
                  "pCode": "10061"
                },
                {
                  "childrenList": null,
                  "code": "10595",
                  "name": "龙海市",
                  "pCode": "10061"
                },
                {
                  "childrenList": null,
                  "code": "10596",
                  "name": "云霄县",
                  "pCode": "10061"
                },
                {
                  "childrenList": null,
                  "code": "10597",
                  "name": "漳浦县",
                  "pCode": "10061"
                },
                {
                  "childrenList": null,
                  "code": "10598",
                  "name": "诏安县",
                  "pCode": "10061"
                },
                {
                  "childrenList": null,
                  "code": "10599",
                  "name": "长泰县",
                  "pCode": "10061"
                },
                {
                  "childrenList": null,
                  "code": "10600",
                  "name": "东山县",
                  "pCode": "10061"
                },
                {
                  "childrenList": null,
                  "code": "10601",
                  "name": "南靖县",
                  "pCode": "10061"
                },
                {
                  "childrenList": null,
                  "code": "10602",
                  "name": "平和县",
                  "pCode": "10061"
                },
                {
                  "childrenList": null,
                  "code": "10603",
                  "name": "华安县",
                  "pCode": "10061"
                }
              ],
              "code": "10061",
              "name": "漳州",
              "pCode": "10004"
            }
          ],
          "code": "10004",
          "name": "福建省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10604",
                  "name": "皋兰县",
                  "pCode": "10062"
                },
                {
                  "childrenList": null,
                  "code": "10605",
                  "name": "城关区",
                  "pCode": "10062"
                },
                {
                  "childrenList": null,
                  "code": "10606",
                  "name": "七里河区",
                  "pCode": "10062"
                },
                {
                  "childrenList": null,
                  "code": "10607",
                  "name": "西固区",
                  "pCode": "10062"
                },
                {
                  "childrenList": null,
                  "code": "10608",
                  "name": "安宁区",
                  "pCode": "10062"
                },
                {
                  "childrenList": null,
                  "code": "10609",
                  "name": "红古区",
                  "pCode": "10062"
                },
                {
                  "childrenList": null,
                  "code": "10610",
                  "name": "永登县",
                  "pCode": "10062"
                },
                {
                  "childrenList": null,
                  "code": "10611",
                  "name": "榆中县",
                  "pCode": "10062"
                }
              ],
              "code": "10062",
              "name": "兰州",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10612",
                  "name": "白银区",
                  "pCode": "10063"
                },
                {
                  "childrenList": null,
                  "code": "10613",
                  "name": "平川区",
                  "pCode": "10063"
                },
                {
                  "childrenList": null,
                  "code": "10614",
                  "name": "会宁县",
                  "pCode": "10063"
                },
                {
                  "childrenList": null,
                  "code": "10615",
                  "name": "景泰县",
                  "pCode": "10063"
                },
                {
                  "childrenList": null,
                  "code": "10616",
                  "name": "靖远县",
                  "pCode": "10063"
                }
              ],
              "code": "10063",
              "name": "白银",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10617",
                  "name": "临洮县",
                  "pCode": "10064"
                },
                {
                  "childrenList": null,
                  "code": "10618",
                  "name": "陇西县",
                  "pCode": "10064"
                },
                {
                  "childrenList": null,
                  "code": "10619",
                  "name": "通渭县",
                  "pCode": "10064"
                },
                {
                  "childrenList": null,
                  "code": "10620",
                  "name": "渭源县",
                  "pCode": "10064"
                },
                {
                  "childrenList": null,
                  "code": "10621",
                  "name": "漳县",
                  "pCode": "10064"
                },
                {
                  "childrenList": null,
                  "code": "10622",
                  "name": "岷县",
                  "pCode": "10064"
                },
                {
                  "childrenList": null,
                  "code": "10623",
                  "name": "安定区",
                  "pCode": "10064"
                },
                {
                  "childrenList": null,
                  "code": "10624",
                  "name": "安定区",
                  "pCode": "10064"
                }
              ],
              "code": "10064",
              "name": "定西",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10625",
                  "name": "合作市",
                  "pCode": "10065"
                },
                {
                  "childrenList": null,
                  "code": "10626",
                  "name": "临潭县",
                  "pCode": "10065"
                },
                {
                  "childrenList": null,
                  "code": "10627",
                  "name": "卓尼县",
                  "pCode": "10065"
                },
                {
                  "childrenList": null,
                  "code": "10628",
                  "name": "舟曲县",
                  "pCode": "10065"
                },
                {
                  "childrenList": null,
                  "code": "10629",
                  "name": "迭部县",
                  "pCode": "10065"
                },
                {
                  "childrenList": null,
                  "code": "10630",
                  "name": "玛曲县",
                  "pCode": "10065"
                },
                {
                  "childrenList": null,
                  "code": "10631",
                  "name": "碌曲县",
                  "pCode": "10065"
                },
                {
                  "childrenList": null,
                  "code": "10632",
                  "name": "夏河县",
                  "pCode": "10065"
                }
              ],
              "code": "10065",
              "name": "甘南",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10633",
                  "name": "嘉峪关市",
                  "pCode": "10066"
                }
              ],
              "code": "10066",
              "name": "嘉峪关",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10634",
                  "name": "金川区",
                  "pCode": "10067"
                },
                {
                  "childrenList": null,
                  "code": "10635",
                  "name": "永昌县",
                  "pCode": "10067"
                }
              ],
              "code": "10067",
              "name": "金昌",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10636",
                  "name": "肃州区",
                  "pCode": "10068"
                },
                {
                  "childrenList": null,
                  "code": "10637",
                  "name": "玉门市",
                  "pCode": "10068"
                },
                {
                  "childrenList": null,
                  "code": "10638",
                  "name": "敦煌市",
                  "pCode": "10068"
                },
                {
                  "childrenList": null,
                  "code": "10639",
                  "name": "金塔县",
                  "pCode": "10068"
                },
                {
                  "childrenList": null,
                  "code": "10640",
                  "name": "瓜州县",
                  "pCode": "10068"
                },
                {
                  "childrenList": null,
                  "code": "10641",
                  "name": "肃北",
                  "pCode": "10068"
                },
                {
                  "childrenList": null,
                  "code": "10642",
                  "name": "阿克塞",
                  "pCode": "10068"
                }
              ],
              "code": "10068",
              "name": "酒泉",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10643",
                  "name": "临夏市",
                  "pCode": "10069"
                },
                {
                  "childrenList": null,
                  "code": "10644",
                  "name": "临夏县",
                  "pCode": "10069"
                },
                {
                  "childrenList": null,
                  "code": "10645",
                  "name": "康乐县",
                  "pCode": "10069"
                },
                {
                  "childrenList": null,
                  "code": "10646",
                  "name": "永靖县",
                  "pCode": "10069"
                },
                {
                  "childrenList": null,
                  "code": "10647",
                  "name": "广河县",
                  "pCode": "10069"
                },
                {
                  "childrenList": null,
                  "code": "10648",
                  "name": "和政县",
                  "pCode": "10069"
                },
                {
                  "childrenList": null,
                  "code": "10649",
                  "name": "东乡族自治县",
                  "pCode": "10069"
                },
                {
                  "childrenList": null,
                  "code": "10650",
                  "name": "积石山",
                  "pCode": "10069"
                }
              ],
              "code": "10069",
              "name": "临夏",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10651",
                  "name": "成县",
                  "pCode": "10070"
                },
                {
                  "childrenList": null,
                  "code": "10652",
                  "name": "徽县",
                  "pCode": "10070"
                },
                {
                  "childrenList": null,
                  "code": "10653",
                  "name": "康县",
                  "pCode": "10070"
                },
                {
                  "childrenList": null,
                  "code": "10654",
                  "name": "礼县",
                  "pCode": "10070"
                },
                {
                  "childrenList": null,
                  "code": "10655",
                  "name": "两当县",
                  "pCode": "10070"
                },
                {
                  "childrenList": null,
                  "code": "10656",
                  "name": "文县",
                  "pCode": "10070"
                },
                {
                  "childrenList": null,
                  "code": "10657",
                  "name": "西和县",
                  "pCode": "10070"
                },
                {
                  "childrenList": null,
                  "code": "10658",
                  "name": "宕昌县",
                  "pCode": "10070"
                },
                {
                  "childrenList": null,
                  "code": "10659",
                  "name": "武都区",
                  "pCode": "10070"
                }
              ],
              "code": "10070",
              "name": "陇南",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10660",
                  "name": "崇信县",
                  "pCode": "10071"
                },
                {
                  "childrenList": null,
                  "code": "10661",
                  "name": "华亭县",
                  "pCode": "10071"
                },
                {
                  "childrenList": null,
                  "code": "10662",
                  "name": "静宁县",
                  "pCode": "10071"
                },
                {
                  "childrenList": null,
                  "code": "10663",
                  "name": "灵台县",
                  "pCode": "10071"
                },
                {
                  "childrenList": null,
                  "code": "10664",
                  "name": "崆峒区",
                  "pCode": "10071"
                },
                {
                  "childrenList": null,
                  "code": "10665",
                  "name": "庄浪县",
                  "pCode": "10071"
                },
                {
                  "childrenList": null,
                  "code": "10666",
                  "name": "泾川县",
                  "pCode": "10071"
                }
              ],
              "code": "10071",
              "name": "平凉",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10667",
                  "name": "合水县",
                  "pCode": "10072"
                },
                {
                  "childrenList": null,
                  "code": "10668",
                  "name": "华池县",
                  "pCode": "10072"
                },
                {
                  "childrenList": null,
                  "code": "10669",
                  "name": "环县",
                  "pCode": "10072"
                },
                {
                  "childrenList": null,
                  "code": "10670",
                  "name": "宁县",
                  "pCode": "10072"
                },
                {
                  "childrenList": null,
                  "code": "10671",
                  "name": "庆城县",
                  "pCode": "10072"
                },
                {
                  "childrenList": null,
                  "code": "10672",
                  "name": "西峰区",
                  "pCode": "10072"
                },
                {
                  "childrenList": null,
                  "code": "10673",
                  "name": "镇原县",
                  "pCode": "10072"
                },
                {
                  "childrenList": null,
                  "code": "10674",
                  "name": "正宁县",
                  "pCode": "10072"
                }
              ],
              "code": "10072",
              "name": "庆阳",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10675",
                  "name": "甘谷县",
                  "pCode": "10073"
                },
                {
                  "childrenList": null,
                  "code": "10676",
                  "name": "秦安县",
                  "pCode": "10073"
                },
                {
                  "childrenList": null,
                  "code": "10677",
                  "name": "清水县",
                  "pCode": "10073"
                },
                {
                  "childrenList": null,
                  "code": "10678",
                  "name": "秦州区",
                  "pCode": "10073"
                },
                {
                  "childrenList": null,
                  "code": "10679",
                  "name": "麦积区",
                  "pCode": "10073"
                },
                {
                  "childrenList": null,
                  "code": "10680",
                  "name": "武山县",
                  "pCode": "10073"
                },
                {
                  "childrenList": null,
                  "code": "10681",
                  "name": "张家川",
                  "pCode": "10073"
                }
              ],
              "code": "10073",
              "name": "天水",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10682",
                  "name": "古浪县",
                  "pCode": "10074"
                },
                {
                  "childrenList": null,
                  "code": "10683",
                  "name": "民勤县",
                  "pCode": "10074"
                },
                {
                  "childrenList": null,
                  "code": "10684",
                  "name": "天祝",
                  "pCode": "10074"
                },
                {
                  "childrenList": null,
                  "code": "10685",
                  "name": "凉州区",
                  "pCode": "10074"
                }
              ],
              "code": "10074",
              "name": "武威",
              "pCode": "10005"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10686",
                  "name": "高台县",
                  "pCode": "10075"
                },
                {
                  "childrenList": null,
                  "code": "10687",
                  "name": "临泽县",
                  "pCode": "10075"
                },
                {
                  "childrenList": null,
                  "code": "10688",
                  "name": "民乐县",
                  "pCode": "10075"
                },
                {
                  "childrenList": null,
                  "code": "10689",
                  "name": "山丹县",
                  "pCode": "10075"
                },
                {
                  "childrenList": null,
                  "code": "10690",
                  "name": "肃南",
                  "pCode": "10075"
                },
                {
                  "childrenList": null,
                  "code": "10691",
                  "name": "甘州区",
                  "pCode": "10075"
                }
              ],
              "code": "10075",
              "name": "张掖",
              "pCode": "10005"
            }
          ],
          "code": "10005",
          "name": "甘肃省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10692",
                  "name": "从化市",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10693",
                  "name": "天河区",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10694",
                  "name": "东山区",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10695",
                  "name": "白云区",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10696",
                  "name": "海珠区",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10697",
                  "name": "荔湾区",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10698",
                  "name": "越秀区",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10699",
                  "name": "黄埔区",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10700",
                  "name": "番禺区",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10701",
                  "name": "花都区",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10702",
                  "name": "增城区",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10703",
                  "name": "从化区",
                  "pCode": "10076"
                },
                {
                  "childrenList": null,
                  "code": "10704",
                  "name": "市郊",
                  "pCode": "10076"
                }
              ],
              "code": "10076",
              "name": "广州",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10705",
                  "name": "福田区",
                  "pCode": "10077"
                },
                {
                  "childrenList": null,
                  "code": "10706",
                  "name": "罗湖区",
                  "pCode": "10077"
                },
                {
                  "childrenList": null,
                  "code": "10707",
                  "name": "南山区",
                  "pCode": "10077"
                },
                {
                  "childrenList": null,
                  "code": "10708",
                  "name": "宝安区",
                  "pCode": "10077"
                },
                {
                  "childrenList": null,
                  "code": "10709",
                  "name": "龙岗区",
                  "pCode": "10077"
                },
                {
                  "childrenList": null,
                  "code": "10710",
                  "name": "盐田区",
                  "pCode": "10077"
                }
              ],
              "code": "10077",
              "name": "深圳",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10711",
                  "name": "湘桥区",
                  "pCode": "10078"
                },
                {
                  "childrenList": null,
                  "code": "10712",
                  "name": "潮安县",
                  "pCode": "10078"
                },
                {
                  "childrenList": null,
                  "code": "10713",
                  "name": "饶平县",
                  "pCode": "10078"
                }
              ],
              "code": "10078",
              "name": "潮州",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10714",
                  "name": "南城区",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10715",
                  "name": "东城区",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10716",
                  "name": "万江区",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10717",
                  "name": "莞城区",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10718",
                  "name": "石龙镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10719",
                  "name": "虎门镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10720",
                  "name": "麻涌镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10721",
                  "name": "道滘镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10722",
                  "name": "石碣镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10723",
                  "name": "沙田镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10724",
                  "name": "望牛墩镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10725",
                  "name": "洪梅镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10726",
                  "name": "茶山镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10727",
                  "name": "寮步镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10728",
                  "name": "大岭山镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10729",
                  "name": "大朗镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10730",
                  "name": "黄江镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10731",
                  "name": "樟木头",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10732",
                  "name": "凤岗镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10733",
                  "name": "塘厦镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10734",
                  "name": "谢岗镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10735",
                  "name": "厚街镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10736",
                  "name": "清溪镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10737",
                  "name": "常平镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10738",
                  "name": "桥头镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10739",
                  "name": "横沥镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10740",
                  "name": "东坑镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10741",
                  "name": "企石镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10742",
                  "name": "石排镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10743",
                  "name": "长安镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10744",
                  "name": "中堂镇",
                  "pCode": "10079"
                },
                {
                  "childrenList": null,
                  "code": "10745",
                  "name": "高埗镇",
                  "pCode": "10079"
                }
              ],
              "code": "10079",
              "name": "东莞",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10746",
                  "name": "禅城区",
                  "pCode": "10080"
                },
                {
                  "childrenList": null,
                  "code": "10747",
                  "name": "南海区",
                  "pCode": "10080"
                },
                {
                  "childrenList": null,
                  "code": "10748",
                  "name": "顺德区",
                  "pCode": "10080"
                },
                {
                  "childrenList": null,
                  "code": "10749",
                  "name": "三水区",
                  "pCode": "10080"
                },
                {
                  "childrenList": null,
                  "code": "10750",
                  "name": "高明区",
                  "pCode": "10080"
                }
              ],
              "code": "10080",
              "name": "佛山",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10751",
                  "name": "东源县",
                  "pCode": "10081"
                },
                {
                  "childrenList": null,
                  "code": "10752",
                  "name": "和平县",
                  "pCode": "10081"
                },
                {
                  "childrenList": null,
                  "code": "10753",
                  "name": "源城区",
                  "pCode": "10081"
                },
                {
                  "childrenList": null,
                  "code": "10754",
                  "name": "连平县",
                  "pCode": "10081"
                },
                {
                  "childrenList": null,
                  "code": "10755",
                  "name": "龙川县",
                  "pCode": "10081"
                },
                {
                  "childrenList": null,
                  "code": "10756",
                  "name": "紫金县",
                  "pCode": "10081"
                }
              ],
              "code": "10081",
              "name": "河源",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10757",
                  "name": "惠阳区",
                  "pCode": "10082"
                },
                {
                  "childrenList": null,
                  "code": "10758",
                  "name": "惠城区",
                  "pCode": "10082"
                },
                {
                  "childrenList": null,
                  "code": "10759",
                  "name": "大亚湾",
                  "pCode": "10082"
                },
                {
                  "childrenList": null,
                  "code": "10760",
                  "name": "博罗县",
                  "pCode": "10082"
                },
                {
                  "childrenList": null,
                  "code": "10761",
                  "name": "惠东县",
                  "pCode": "10082"
                },
                {
                  "childrenList": null,
                  "code": "10762",
                  "name": "龙门县",
                  "pCode": "10082"
                }
              ],
              "code": "10082",
              "name": "惠州",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10763",
                  "name": "江海区",
                  "pCode": "10083"
                },
                {
                  "childrenList": null,
                  "code": "10764",
                  "name": "蓬江区",
                  "pCode": "10083"
                },
                {
                  "childrenList": null,
                  "code": "10765",
                  "name": "新会区",
                  "pCode": "10083"
                },
                {
                  "childrenList": null,
                  "code": "10766",
                  "name": "台山市",
                  "pCode": "10083"
                },
                {
                  "childrenList": null,
                  "code": "10767",
                  "name": "开平市",
                  "pCode": "10083"
                },
                {
                  "childrenList": null,
                  "code": "10768",
                  "name": "鹤山市",
                  "pCode": "10083"
                },
                {
                  "childrenList": null,
                  "code": "10769",
                  "name": "恩平市",
                  "pCode": "10083"
                }
              ],
              "code": "10083",
              "name": "江门",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10770",
                  "name": "榕城区",
                  "pCode": "10084"
                },
                {
                  "childrenList": null,
                  "code": "10771",
                  "name": "普宁市",
                  "pCode": "10084"
                },
                {
                  "childrenList": null,
                  "code": "10772",
                  "name": "揭东县",
                  "pCode": "10084"
                },
                {
                  "childrenList": null,
                  "code": "10773",
                  "name": "揭西县",
                  "pCode": "10084"
                },
                {
                  "childrenList": null,
                  "code": "10774",
                  "name": "惠来县",
                  "pCode": "10084"
                }
              ],
              "code": "10084",
              "name": "揭阳",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10775",
                  "name": "茂南区",
                  "pCode": "10085"
                },
                {
                  "childrenList": null,
                  "code": "10776",
                  "name": "茂港区",
                  "pCode": "10085"
                },
                {
                  "childrenList": null,
                  "code": "10777",
                  "name": "高州市",
                  "pCode": "10085"
                },
                {
                  "childrenList": null,
                  "code": "10778",
                  "name": "化州市",
                  "pCode": "10085"
                },
                {
                  "childrenList": null,
                  "code": "10779",
                  "name": "信宜市",
                  "pCode": "10085"
                },
                {
                  "childrenList": null,
                  "code": "10780",
                  "name": "电白县",
                  "pCode": "10085"
                }
              ],
              "code": "10085",
              "name": "茂名",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10781",
                  "name": "梅县",
                  "pCode": "10086"
                },
                {
                  "childrenList": null,
                  "code": "10782",
                  "name": "梅江区",
                  "pCode": "10086"
                },
                {
                  "childrenList": null,
                  "code": "10783",
                  "name": "兴宁市",
                  "pCode": "10086"
                },
                {
                  "childrenList": null,
                  "code": "10784",
                  "name": "大埔县",
                  "pCode": "10086"
                },
                {
                  "childrenList": null,
                  "code": "10785",
                  "name": "丰顺县",
                  "pCode": "10086"
                },
                {
                  "childrenList": null,
                  "code": "10786",
                  "name": "五华县",
                  "pCode": "10086"
                },
                {
                  "childrenList": null,
                  "code": "10787",
                  "name": "平远县",
                  "pCode": "10086"
                },
                {
                  "childrenList": null,
                  "code": "10788",
                  "name": "蕉岭县",
                  "pCode": "10086"
                }
              ],
              "code": "10086",
              "name": "梅州",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10789",
                  "name": "清城区",
                  "pCode": "10087"
                },
                {
                  "childrenList": null,
                  "code": "10790",
                  "name": "英德市",
                  "pCode": "10087"
                },
                {
                  "childrenList": null,
                  "code": "10791",
                  "name": "连州市",
                  "pCode": "10087"
                },
                {
                  "childrenList": null,
                  "code": "10792",
                  "name": "佛冈县",
                  "pCode": "10087"
                },
                {
                  "childrenList": null,
                  "code": "10793",
                  "name": "阳山县",
                  "pCode": "10087"
                },
                {
                  "childrenList": null,
                  "code": "10794",
                  "name": "清新县",
                  "pCode": "10087"
                },
                {
                  "childrenList": null,
                  "code": "10795",
                  "name": "连山",
                  "pCode": "10087"
                },
                {
                  "childrenList": null,
                  "code": "10796",
                  "name": "连南",
                  "pCode": "10087"
                }
              ],
              "code": "10087",
              "name": "清远",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10797",
                  "name": "南澳县",
                  "pCode": "10088"
                },
                {
                  "childrenList": null,
                  "code": "10798",
                  "name": "潮阳区",
                  "pCode": "10088"
                },
                {
                  "childrenList": null,
                  "code": "10799",
                  "name": "澄海区",
                  "pCode": "10088"
                },
                {
                  "childrenList": null,
                  "code": "10800",
                  "name": "龙湖区",
                  "pCode": "10088"
                },
                {
                  "childrenList": null,
                  "code": "10801",
                  "name": "金平区",
                  "pCode": "10088"
                },
                {
                  "childrenList": null,
                  "code": "10802",
                  "name": "濠江区",
                  "pCode": "10088"
                },
                {
                  "childrenList": null,
                  "code": "10803",
                  "name": "潮南区",
                  "pCode": "10088"
                }
              ],
              "code": "10088",
              "name": "汕头",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10804",
                  "name": "城区",
                  "pCode": "10089"
                },
                {
                  "childrenList": null,
                  "code": "10805",
                  "name": "陆丰市",
                  "pCode": "10089"
                },
                {
                  "childrenList": null,
                  "code": "10806",
                  "name": "海丰县",
                  "pCode": "10089"
                },
                {
                  "childrenList": null,
                  "code": "10807",
                  "name": "陆河县",
                  "pCode": "10089"
                }
              ],
              "code": "10089",
              "name": "汕尾",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10808",
                  "name": "曲江县",
                  "pCode": "10090"
                },
                {
                  "childrenList": null,
                  "code": "10809",
                  "name": "浈江区",
                  "pCode": "10090"
                },
                {
                  "childrenList": null,
                  "code": "10810",
                  "name": "武江区",
                  "pCode": "10090"
                },
                {
                  "childrenList": null,
                  "code": "10811",
                  "name": "曲江区",
                  "pCode": "10090"
                },
                {
                  "childrenList": null,
                  "code": "10812",
                  "name": "乐昌市",
                  "pCode": "10090"
                },
                {
                  "childrenList": null,
                  "code": "10813",
                  "name": "南雄市",
                  "pCode": "10090"
                },
                {
                  "childrenList": null,
                  "code": "10814",
                  "name": "始兴县",
                  "pCode": "10090"
                },
                {
                  "childrenList": null,
                  "code": "10815",
                  "name": "仁化县",
                  "pCode": "10090"
                },
                {
                  "childrenList": null,
                  "code": "10816",
                  "name": "翁源县",
                  "pCode": "10090"
                },
                {
                  "childrenList": null,
                  "code": "10817",
                  "name": "新丰县",
                  "pCode": "10090"
                },
                {
                  "childrenList": null,
                  "code": "10818",
                  "name": "乳源",
                  "pCode": "10090"
                }
              ],
              "code": "10090",
              "name": "韶关",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10819",
                  "name": "江城区",
                  "pCode": "10091"
                },
                {
                  "childrenList": null,
                  "code": "10820",
                  "name": "阳春市",
                  "pCode": "10091"
                },
                {
                  "childrenList": null,
                  "code": "10821",
                  "name": "阳西县",
                  "pCode": "10091"
                },
                {
                  "childrenList": null,
                  "code": "10822",
                  "name": "阳东县",
                  "pCode": "10091"
                }
              ],
              "code": "10091",
              "name": "阳江",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10823",
                  "name": "云城区",
                  "pCode": "10092"
                },
                {
                  "childrenList": null,
                  "code": "10824",
                  "name": "罗定市",
                  "pCode": "10092"
                },
                {
                  "childrenList": null,
                  "code": "10825",
                  "name": "新兴县",
                  "pCode": "10092"
                },
                {
                  "childrenList": null,
                  "code": "10826",
                  "name": "郁南县",
                  "pCode": "10092"
                },
                {
                  "childrenList": null,
                  "code": "10827",
                  "name": "云安县",
                  "pCode": "10092"
                }
              ],
              "code": "10092",
              "name": "云浮",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10828",
                  "name": "赤坎区",
                  "pCode": "10093"
                },
                {
                  "childrenList": null,
                  "code": "10829",
                  "name": "霞山区",
                  "pCode": "10093"
                },
                {
                  "childrenList": null,
                  "code": "10830",
                  "name": "坡头区",
                  "pCode": "10093"
                },
                {
                  "childrenList": null,
                  "code": "10831",
                  "name": "麻章区",
                  "pCode": "10093"
                },
                {
                  "childrenList": null,
                  "code": "10832",
                  "name": "廉江市",
                  "pCode": "10093"
                },
                {
                  "childrenList": null,
                  "code": "10833",
                  "name": "雷州市",
                  "pCode": "10093"
                },
                {
                  "childrenList": null,
                  "code": "10834",
                  "name": "吴川市",
                  "pCode": "10093"
                },
                {
                  "childrenList": null,
                  "code": "10835",
                  "name": "遂溪县",
                  "pCode": "10093"
                },
                {
                  "childrenList": null,
                  "code": "10836",
                  "name": "徐闻县",
                  "pCode": "10093"
                }
              ],
              "code": "10093",
              "name": "湛江",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10837",
                  "name": "肇庆市",
                  "pCode": "10094"
                },
                {
                  "childrenList": null,
                  "code": "10838",
                  "name": "高要市",
                  "pCode": "10094"
                },
                {
                  "childrenList": null,
                  "code": "10839",
                  "name": "四会市",
                  "pCode": "10094"
                },
                {
                  "childrenList": null,
                  "code": "10840",
                  "name": "广宁县",
                  "pCode": "10094"
                },
                {
                  "childrenList": null,
                  "code": "10841",
                  "name": "怀集县",
                  "pCode": "10094"
                },
                {
                  "childrenList": null,
                  "code": "10842",
                  "name": "封开县",
                  "pCode": "10094"
                },
                {
                  "childrenList": null,
                  "code": "10843",
                  "name": "德庆县",
                  "pCode": "10094"
                }
              ],
              "code": "10094",
              "name": "肇庆",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10844",
                  "name": "石岐街道",
                  "pCode": "10095"
                },
                {
                  "childrenList": null,
                  "code": "10845",
                  "name": "东区街道",
                  "pCode": "10095"
                },
                {
                  "childrenList": null,
                  "code": "10846",
                  "name": "西区街道",
                  "pCode": "10095"
                },
                {
                  "childrenList": null,
                  "code": "10847",
                  "name": "环城街道",
                  "pCode": "10095"
                },
                {
                  "childrenList": null,
                  "code": "10848",
                  "name": "中山港街道",
                  "pCode": "10095"
                },
                {
                  "childrenList": null,
                  "code": "10849",
                  "name": "五桂山街道",
                  "pCode": "10095"
                }
              ],
              "code": "10095",
              "name": "中山",
              "pCode": "10006"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10850",
                  "name": "香洲区",
                  "pCode": "10096"
                },
                {
                  "childrenList": null,
                  "code": "10851",
                  "name": "斗门区",
                  "pCode": "10096"
                },
                {
                  "childrenList": null,
                  "code": "10852",
                  "name": "金湾区",
                  "pCode": "10096"
                }
              ],
              "code": "10096",
              "name": "珠海",
              "pCode": "10006"
            }
          ],
          "code": "10006",
          "name": "广东省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10853",
                  "name": "邕宁区",
                  "pCode": "10097"
                },
                {
                  "childrenList": null,
                  "code": "10854",
                  "name": "青秀区",
                  "pCode": "10097"
                },
                {
                  "childrenList": null,
                  "code": "10855",
                  "name": "兴宁区",
                  "pCode": "10097"
                },
                {
                  "childrenList": null,
                  "code": "10856",
                  "name": "良庆区",
                  "pCode": "10097"
                },
                {
                  "childrenList": null,
                  "code": "10857",
                  "name": "西乡塘区",
                  "pCode": "10097"
                },
                {
                  "childrenList": null,
                  "code": "10858",
                  "name": "江南区",
                  "pCode": "10097"
                },
                {
                  "childrenList": null,
                  "code": "10859",
                  "name": "武鸣县",
                  "pCode": "10097"
                },
                {
                  "childrenList": null,
                  "code": "10860",
                  "name": "隆安县",
                  "pCode": "10097"
                },
                {
                  "childrenList": null,
                  "code": "10861",
                  "name": "马山县",
                  "pCode": "10097"
                },
                {
                  "childrenList": null,
                  "code": "10862",
                  "name": "上林县",
                  "pCode": "10097"
                },
                {
                  "childrenList": null,
                  "code": "10863",
                  "name": "宾阳县",
                  "pCode": "10097"
                },
                {
                  "childrenList": null,
                  "code": "10864",
                  "name": "横县",
                  "pCode": "10097"
                }
              ],
              "code": "10097",
              "name": "南宁",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10865",
                  "name": "秀峰区",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10866",
                  "name": "叠彩区",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10867",
                  "name": "象山区",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10868",
                  "name": "七星区",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10869",
                  "name": "雁山区",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10870",
                  "name": "阳朔县",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10871",
                  "name": "临桂县",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10872",
                  "name": "灵川县",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10873",
                  "name": "全州县",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10874",
                  "name": "平乐县",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10875",
                  "name": "兴安县",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10876",
                  "name": "灌阳县",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10877",
                  "name": "荔浦县",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10878",
                  "name": "资源县",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10879",
                  "name": "永福县",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10880",
                  "name": "龙胜",
                  "pCode": "10098"
                },
                {
                  "childrenList": null,
                  "code": "10881",
                  "name": "恭城",
                  "pCode": "10098"
                }
              ],
              "code": "10098",
              "name": "桂林",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10882",
                  "name": "右江区",
                  "pCode": "10099"
                },
                {
                  "childrenList": null,
                  "code": "10883",
                  "name": "凌云县",
                  "pCode": "10099"
                },
                {
                  "childrenList": null,
                  "code": "10884",
                  "name": "平果县",
                  "pCode": "10099"
                },
                {
                  "childrenList": null,
                  "code": "10885",
                  "name": "西林县",
                  "pCode": "10099"
                },
                {
                  "childrenList": null,
                  "code": "10886",
                  "name": "乐业县",
                  "pCode": "10099"
                },
                {
                  "childrenList": null,
                  "code": "10887",
                  "name": "德保县",
                  "pCode": "10099"
                },
                {
                  "childrenList": null,
                  "code": "10888",
                  "name": "田林县",
                  "pCode": "10099"
                },
                {
                  "childrenList": null,
                  "code": "10889",
                  "name": "田阳县",
                  "pCode": "10099"
                },
                {
                  "childrenList": null,
                  "code": "10890",
                  "name": "靖西县",
                  "pCode": "10099"
                },
                {
                  "childrenList": null,
                  "code": "10891",
                  "name": "田东县",
                  "pCode": "10099"
                },
                {
                  "childrenList": null,
                  "code": "10892",
                  "name": "那坡县",
                  "pCode": "10099"
                },
                {
                  "childrenList": null,
                  "code": "10893",
                  "name": "隆林",
                  "pCode": "10099"
                }
              ],
              "code": "10099",
              "name": "百色",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10894",
                  "name": "海城区",
                  "pCode": "10100"
                },
                {
                  "childrenList": null,
                  "code": "10895",
                  "name": "银海区",
                  "pCode": "10100"
                },
                {
                  "childrenList": null,
                  "code": "10896",
                  "name": "铁山港区",
                  "pCode": "10100"
                },
                {
                  "childrenList": null,
                  "code": "10897",
                  "name": "合浦县",
                  "pCode": "10100"
                }
              ],
              "code": "10100",
              "name": "北海",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10898",
                  "name": "江州区",
                  "pCode": "10101"
                },
                {
                  "childrenList": null,
                  "code": "10899",
                  "name": "凭祥市",
                  "pCode": "10101"
                },
                {
                  "childrenList": null,
                  "code": "10900",
                  "name": "宁明县",
                  "pCode": "10101"
                },
                {
                  "childrenList": null,
                  "code": "10901",
                  "name": "扶绥县",
                  "pCode": "10101"
                },
                {
                  "childrenList": null,
                  "code": "10902",
                  "name": "龙州县",
                  "pCode": "10101"
                },
                {
                  "childrenList": null,
                  "code": "10903",
                  "name": "大新县",
                  "pCode": "10101"
                },
                {
                  "childrenList": null,
                  "code": "10904",
                  "name": "天等县",
                  "pCode": "10101"
                }
              ],
              "code": "10101",
              "name": "崇左",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10905",
                  "name": "港口区",
                  "pCode": "10102"
                },
                {
                  "childrenList": null,
                  "code": "10906",
                  "name": "防城区",
                  "pCode": "10102"
                },
                {
                  "childrenList": null,
                  "code": "10907",
                  "name": "东兴市",
                  "pCode": "10102"
                },
                {
                  "childrenList": null,
                  "code": "10908",
                  "name": "上思县",
                  "pCode": "10102"
                }
              ],
              "code": "10102",
              "name": "防城港",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10909",
                  "name": "港北区",
                  "pCode": "10103"
                },
                {
                  "childrenList": null,
                  "code": "10910",
                  "name": "港南区",
                  "pCode": "10103"
                },
                {
                  "childrenList": null,
                  "code": "10911",
                  "name": "覃塘区",
                  "pCode": "10103"
                },
                {
                  "childrenList": null,
                  "code": "10912",
                  "name": "桂平市",
                  "pCode": "10103"
                },
                {
                  "childrenList": null,
                  "code": "10913",
                  "name": "平南县",
                  "pCode": "10103"
                }
              ],
              "code": "10103",
              "name": "贵港",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10914",
                  "name": "金城江区",
                  "pCode": "10104"
                },
                {
                  "childrenList": null,
                  "code": "10915",
                  "name": "宜州市",
                  "pCode": "10104"
                },
                {
                  "childrenList": null,
                  "code": "10916",
                  "name": "天峨县",
                  "pCode": "10104"
                },
                {
                  "childrenList": null,
                  "code": "10917",
                  "name": "凤山县",
                  "pCode": "10104"
                },
                {
                  "childrenList": null,
                  "code": "10918",
                  "name": "南丹县",
                  "pCode": "10104"
                },
                {
                  "childrenList": null,
                  "code": "10919",
                  "name": "东兰县",
                  "pCode": "10104"
                },
                {
                  "childrenList": null,
                  "code": "10920",
                  "name": "都安",
                  "pCode": "10104"
                },
                {
                  "childrenList": null,
                  "code": "10921",
                  "name": "罗城",
                  "pCode": "10104"
                },
                {
                  "childrenList": null,
                  "code": "10922",
                  "name": "巴马",
                  "pCode": "10104"
                },
                {
                  "childrenList": null,
                  "code": "10923",
                  "name": "环江",
                  "pCode": "10104"
                },
                {
                  "childrenList": null,
                  "code": "10924",
                  "name": "大化",
                  "pCode": "10104"
                }
              ],
              "code": "10104",
              "name": "河池",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10925",
                  "name": "八步区",
                  "pCode": "10105"
                },
                {
                  "childrenList": null,
                  "code": "10926",
                  "name": "钟山县",
                  "pCode": "10105"
                },
                {
                  "childrenList": null,
                  "code": "10927",
                  "name": "昭平县",
                  "pCode": "10105"
                },
                {
                  "childrenList": null,
                  "code": "10928",
                  "name": "富川",
                  "pCode": "10105"
                }
              ],
              "code": "10105",
              "name": "贺州",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10929",
                  "name": "兴宾区",
                  "pCode": "10106"
                },
                {
                  "childrenList": null,
                  "code": "10930",
                  "name": "合山市",
                  "pCode": "10106"
                },
                {
                  "childrenList": null,
                  "code": "10931",
                  "name": "象州县",
                  "pCode": "10106"
                },
                {
                  "childrenList": null,
                  "code": "10932",
                  "name": "武宣县",
                  "pCode": "10106"
                },
                {
                  "childrenList": null,
                  "code": "10933",
                  "name": "忻城县",
                  "pCode": "10106"
                },
                {
                  "childrenList": null,
                  "code": "10934",
                  "name": "金秀",
                  "pCode": "10106"
                }
              ],
              "code": "10106",
              "name": "来宾",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10935",
                  "name": "城中区",
                  "pCode": "10107"
                },
                {
                  "childrenList": null,
                  "code": "10936",
                  "name": "鱼峰区",
                  "pCode": "10107"
                },
                {
                  "childrenList": null,
                  "code": "10937",
                  "name": "柳北区",
                  "pCode": "10107"
                },
                {
                  "childrenList": null,
                  "code": "10938",
                  "name": "柳南区",
                  "pCode": "10107"
                },
                {
                  "childrenList": null,
                  "code": "10939",
                  "name": "柳江县",
                  "pCode": "10107"
                },
                {
                  "childrenList": null,
                  "code": "10940",
                  "name": "柳城县",
                  "pCode": "10107"
                },
                {
                  "childrenList": null,
                  "code": "10941",
                  "name": "鹿寨县",
                  "pCode": "10107"
                },
                {
                  "childrenList": null,
                  "code": "10942",
                  "name": "融安县",
                  "pCode": "10107"
                },
                {
                  "childrenList": null,
                  "code": "10943",
                  "name": "融水",
                  "pCode": "10107"
                },
                {
                  "childrenList": null,
                  "code": "10944",
                  "name": "三江",
                  "pCode": "10107"
                }
              ],
              "code": "10107",
              "name": "柳州",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10945",
                  "name": "钦南区",
                  "pCode": "10108"
                },
                {
                  "childrenList": null,
                  "code": "10946",
                  "name": "钦北区",
                  "pCode": "10108"
                },
                {
                  "childrenList": null,
                  "code": "10947",
                  "name": "灵山县",
                  "pCode": "10108"
                },
                {
                  "childrenList": null,
                  "code": "10948",
                  "name": "浦北县",
                  "pCode": "10108"
                }
              ],
              "code": "10108",
              "name": "钦州",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10949",
                  "name": "万秀区",
                  "pCode": "10109"
                },
                {
                  "childrenList": null,
                  "code": "10950",
                  "name": "蝶山区",
                  "pCode": "10109"
                },
                {
                  "childrenList": null,
                  "code": "10951",
                  "name": "长洲区",
                  "pCode": "10109"
                },
                {
                  "childrenList": null,
                  "code": "10952",
                  "name": "岑溪市",
                  "pCode": "10109"
                },
                {
                  "childrenList": null,
                  "code": "10953",
                  "name": "苍梧县",
                  "pCode": "10109"
                },
                {
                  "childrenList": null,
                  "code": "10954",
                  "name": "藤县",
                  "pCode": "10109"
                },
                {
                  "childrenList": null,
                  "code": "10955",
                  "name": "蒙山县",
                  "pCode": "10109"
                }
              ],
              "code": "10109",
              "name": "梧州",
              "pCode": "10007"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10956",
                  "name": "玉州区",
                  "pCode": "10110"
                },
                {
                  "childrenList": null,
                  "code": "10957",
                  "name": "北流市",
                  "pCode": "10110"
                },
                {
                  "childrenList": null,
                  "code": "10958",
                  "name": "容县",
                  "pCode": "10110"
                },
                {
                  "childrenList": null,
                  "code": "10959",
                  "name": "陆川县",
                  "pCode": "10110"
                },
                {
                  "childrenList": null,
                  "code": "10960",
                  "name": "博白县",
                  "pCode": "10110"
                },
                {
                  "childrenList": null,
                  "code": "10961",
                  "name": "兴业县",
                  "pCode": "10110"
                }
              ],
              "code": "10110",
              "name": "玉林",
              "pCode": "10007"
            }
          ],
          "code": "10007",
          "name": "广西壮族自治区",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10962",
                  "name": "南明区",
                  "pCode": "10111"
                },
                {
                  "childrenList": null,
                  "code": "10963",
                  "name": "云岩区",
                  "pCode": "10111"
                },
                {
                  "childrenList": null,
                  "code": "10964",
                  "name": "花溪区",
                  "pCode": "10111"
                },
                {
                  "childrenList": null,
                  "code": "10965",
                  "name": "乌当区",
                  "pCode": "10111"
                },
                {
                  "childrenList": null,
                  "code": "10966",
                  "name": "白云区",
                  "pCode": "10111"
                },
                {
                  "childrenList": null,
                  "code": "10967",
                  "name": "小河区",
                  "pCode": "10111"
                },
                {
                  "childrenList": null,
                  "code": "10968",
                  "name": "金阳新区",
                  "pCode": "10111"
                },
                {
                  "childrenList": null,
                  "code": "10969",
                  "name": "新天园区",
                  "pCode": "10111"
                },
                {
                  "childrenList": null,
                  "code": "10970",
                  "name": "清镇市",
                  "pCode": "10111"
                },
                {
                  "childrenList": null,
                  "code": "10971",
                  "name": "开阳县",
                  "pCode": "10111"
                },
                {
                  "childrenList": null,
                  "code": "10972",
                  "name": "修文县",
                  "pCode": "10111"
                },
                {
                  "childrenList": null,
                  "code": "10973",
                  "name": "息烽县",
                  "pCode": "10111"
                }
              ],
              "code": "10111",
              "name": "贵阳",
              "pCode": "10008"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10974",
                  "name": "西秀区",
                  "pCode": "10112"
                },
                {
                  "childrenList": null,
                  "code": "10975",
                  "name": "关岭",
                  "pCode": "10112"
                },
                {
                  "childrenList": null,
                  "code": "10976",
                  "name": "镇宁",
                  "pCode": "10112"
                },
                {
                  "childrenList": null,
                  "code": "10977",
                  "name": "紫云",
                  "pCode": "10112"
                },
                {
                  "childrenList": null,
                  "code": "10978",
                  "name": "平坝县",
                  "pCode": "10112"
                },
                {
                  "childrenList": null,
                  "code": "10979",
                  "name": "普定县",
                  "pCode": "10112"
                }
              ],
              "code": "10112",
              "name": "安顺",
              "pCode": "10008"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10980",
                  "name": "毕节市",
                  "pCode": "10113"
                },
                {
                  "childrenList": null,
                  "code": "10981",
                  "name": "大方县",
                  "pCode": "10113"
                },
                {
                  "childrenList": null,
                  "code": "10982",
                  "name": "黔西县",
                  "pCode": "10113"
                },
                {
                  "childrenList": null,
                  "code": "10983",
                  "name": "金沙县",
                  "pCode": "10113"
                },
                {
                  "childrenList": null,
                  "code": "10984",
                  "name": "织金县",
                  "pCode": "10113"
                },
                {
                  "childrenList": null,
                  "code": "10985",
                  "name": "纳雍县",
                  "pCode": "10113"
                },
                {
                  "childrenList": null,
                  "code": "10986",
                  "name": "赫章县",
                  "pCode": "10113"
                },
                {
                  "childrenList": null,
                  "code": "10987",
                  "name": "威宁",
                  "pCode": "10113"
                }
              ],
              "code": "10113",
              "name": "毕节",
              "pCode": "10008"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10988",
                  "name": "钟山区",
                  "pCode": "10114"
                },
                {
                  "childrenList": null,
                  "code": "10989",
                  "name": "六枝特区",
                  "pCode": "10114"
                },
                {
                  "childrenList": null,
                  "code": "10990",
                  "name": "水城县",
                  "pCode": "10114"
                },
                {
                  "childrenList": null,
                  "code": "10991",
                  "name": "盘县",
                  "pCode": "10114"
                }
              ],
              "code": "10114",
              "name": "六盘水",
              "pCode": "10008"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "10992",
                  "name": "凯里市",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "10993",
                  "name": "黄平县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "10994",
                  "name": "施秉县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "10995",
                  "name": "三穗县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "10996",
                  "name": "镇远县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "10997",
                  "name": "岑巩县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "10998",
                  "name": "天柱县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "10999",
                  "name": "锦屏县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "11000",
                  "name": "剑河县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "11001",
                  "name": "台江县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "11002",
                  "name": "黎平县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "11003",
                  "name": "榕江县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "11004",
                  "name": "从江县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "11005",
                  "name": "雷山县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "11006",
                  "name": "麻江县",
                  "pCode": "10115"
                },
                {
                  "childrenList": null,
                  "code": "11007",
                  "name": "丹寨县",
                  "pCode": "10115"
                }
              ],
              "code": "10115",
              "name": "黔东南",
              "pCode": "10008"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11008",
                  "name": "都匀市",
                  "pCode": "10116"
                },
                {
                  "childrenList": null,
                  "code": "11009",
                  "name": "福泉市",
                  "pCode": "10116"
                },
                {
                  "childrenList": null,
                  "code": "11010",
                  "name": "荔波县",
                  "pCode": "10116"
                },
                {
                  "childrenList": null,
                  "code": "11011",
                  "name": "贵定县",
                  "pCode": "10116"
                },
                {
                  "childrenList": null,
                  "code": "11012",
                  "name": "瓮安县",
                  "pCode": "10116"
                },
                {
                  "childrenList": null,
                  "code": "11013",
                  "name": "独山县",
                  "pCode": "10116"
                },
                {
                  "childrenList": null,
                  "code": "11014",
                  "name": "平塘县",
                  "pCode": "10116"
                },
                {
                  "childrenList": null,
                  "code": "11015",
                  "name": "罗甸县",
                  "pCode": "10116"
                },
                {
                  "childrenList": null,
                  "code": "11016",
                  "name": "长顺县",
                  "pCode": "10116"
                },
                {
                  "childrenList": null,
                  "code": "11017",
                  "name": "龙里县",
                  "pCode": "10116"
                },
                {
                  "childrenList": null,
                  "code": "11018",
                  "name": "惠水县",
                  "pCode": "10116"
                },
                {
                  "childrenList": null,
                  "code": "11019",
                  "name": "三都",
                  "pCode": "10116"
                }
              ],
              "code": "10116",
              "name": "黔南",
              "pCode": "10008"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11020",
                  "name": "兴义市",
                  "pCode": "10117"
                },
                {
                  "childrenList": null,
                  "code": "11021",
                  "name": "兴仁县",
                  "pCode": "10117"
                },
                {
                  "childrenList": null,
                  "code": "11022",
                  "name": "普安县",
                  "pCode": "10117"
                },
                {
                  "childrenList": null,
                  "code": "11023",
                  "name": "晴隆县",
                  "pCode": "10117"
                },
                {
                  "childrenList": null,
                  "code": "11024",
                  "name": "贞丰县",
                  "pCode": "10117"
                },
                {
                  "childrenList": null,
                  "code": "11025",
                  "name": "望谟县",
                  "pCode": "10117"
                },
                {
                  "childrenList": null,
                  "code": "11026",
                  "name": "册亨县",
                  "pCode": "10117"
                },
                {
                  "childrenList": null,
                  "code": "11027",
                  "name": "安龙县",
                  "pCode": "10117"
                }
              ],
              "code": "10117",
              "name": "黔西南",
              "pCode": "10008"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11028",
                  "name": "铜仁市",
                  "pCode": "10118"
                },
                {
                  "childrenList": null,
                  "code": "11029",
                  "name": "江口县",
                  "pCode": "10118"
                },
                {
                  "childrenList": null,
                  "code": "11030",
                  "name": "石阡县",
                  "pCode": "10118"
                },
                {
                  "childrenList": null,
                  "code": "11031",
                  "name": "思南县",
                  "pCode": "10118"
                },
                {
                  "childrenList": null,
                  "code": "11032",
                  "name": "德江县",
                  "pCode": "10118"
                },
                {
                  "childrenList": null,
                  "code": "11033",
                  "name": "玉屏",
                  "pCode": "10118"
                },
                {
                  "childrenList": null,
                  "code": "11034",
                  "name": "印江",
                  "pCode": "10118"
                },
                {
                  "childrenList": null,
                  "code": "11035",
                  "name": "沿河",
                  "pCode": "10118"
                },
                {
                  "childrenList": null,
                  "code": "11036",
                  "name": "松桃",
                  "pCode": "10118"
                },
                {
                  "childrenList": null,
                  "code": "11037",
                  "name": "万山特区",
                  "pCode": "10118"
                }
              ],
              "code": "10118",
              "name": "铜仁",
              "pCode": "10008"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11038",
                  "name": "红花岗区",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11039",
                  "name": "务川县",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11040",
                  "name": "道真县",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11041",
                  "name": "汇川区",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11042",
                  "name": "赤水市",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11043",
                  "name": "仁怀市",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11044",
                  "name": "遵义县",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11045",
                  "name": "桐梓县",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11046",
                  "name": "绥阳县",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11047",
                  "name": "正安县",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11048",
                  "name": "凤冈县",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11049",
                  "name": "湄潭县",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11050",
                  "name": "余庆县",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11051",
                  "name": "习水县",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11052",
                  "name": "道真",
                  "pCode": "10119"
                },
                {
                  "childrenList": null,
                  "code": "11053",
                  "name": "务川",
                  "pCode": "10119"
                }
              ],
              "code": "10119",
              "name": "遵义",
              "pCode": "10008"
            }
          ],
          "code": "10008",
          "name": "贵州省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11054",
                  "name": "秀英区",
                  "pCode": "10120"
                },
                {
                  "childrenList": null,
                  "code": "11055",
                  "name": "龙华区",
                  "pCode": "10120"
                },
                {
                  "childrenList": null,
                  "code": "11056",
                  "name": "琼山区",
                  "pCode": "10120"
                },
                {
                  "childrenList": null,
                  "code": "11057",
                  "name": "美兰区",
                  "pCode": "10120"
                }
              ],
              "code": "10120",
              "name": "海口",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10121",
              "name": "三亚",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10122",
              "name": "白沙",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10123",
              "name": "保亭",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10124",
              "name": "昌江",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10125",
              "name": "澄迈县",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10126",
              "name": "定安县",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10127",
              "name": "东方",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10128",
              "name": "乐东",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10129",
              "name": "临高县",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10130",
              "name": "陵水",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10131",
              "name": "琼海",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10132",
              "name": "琼中",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10133",
              "name": "屯昌县",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10134",
              "name": "万宁",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10135",
              "name": "文昌",
              "pCode": "10009"
            },
            {
              "childrenList": [],
              "code": "10136",
              "name": "五指山",
              "pCode": "10009"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11058",
                  "name": "市区",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11059",
                  "name": "洋浦开发区",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11060",
                  "name": "那大镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11061",
                  "name": "王五镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11062",
                  "name": "雅星镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11063",
                  "name": "大成镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11064",
                  "name": "中和镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11065",
                  "name": "峨蔓镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11066",
                  "name": "南丰镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11067",
                  "name": "白马井镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11068",
                  "name": "兰洋镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11069",
                  "name": "和庆镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11070",
                  "name": "海头镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11071",
                  "name": "排浦镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11072",
                  "name": "东成镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11073",
                  "name": "光村镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11074",
                  "name": "木棠镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11075",
                  "name": "新州镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11076",
                  "name": "三都镇",
                  "pCode": "10137"
                },
                {
                  "childrenList": null,
                  "code": "11077",
                  "name": "其他",
                  "pCode": "10137"
                }
              ],
              "code": "10137",
              "name": "儋州",
              "pCode": "10009"
            }
          ],
          "code": "10009",
          "name": "海南省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11078",
                  "name": "长安区",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11079",
                  "name": "桥东区",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11080",
                  "name": "桥西区",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11081",
                  "name": "新华区",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11082",
                  "name": "裕华区",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11083",
                  "name": "井陉矿区",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11084",
                  "name": "高新区",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11085",
                  "name": "辛集市",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11086",
                  "name": "藁城市",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11087",
                  "name": "晋州市",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11088",
                  "name": "新乐市",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11089",
                  "name": "鹿泉市",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11090",
                  "name": "井陉县",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11091",
                  "name": "正定县",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11092",
                  "name": "栾城县",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11093",
                  "name": "行唐县",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11094",
                  "name": "灵寿县",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11095",
                  "name": "高邑县",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11096",
                  "name": "深泽县",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11097",
                  "name": "赞皇县",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11098",
                  "name": "无极县",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11099",
                  "name": "平山县",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11100",
                  "name": "元氏县",
                  "pCode": "10138"
                },
                {
                  "childrenList": null,
                  "code": "11101",
                  "name": "赵县",
                  "pCode": "10138"
                }
              ],
              "code": "10138",
              "name": "石家庄",
              "pCode": "10010"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11102",
                  "name": "新市区",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11103",
                  "name": "南市区",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11104",
                  "name": "北市区",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11105",
                  "name": "涿州市",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11106",
                  "name": "定州市",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11107",
                  "name": "安国市",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11108",
                  "name": "高碑店市",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11109",
                  "name": "满城县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11110",
                  "name": "清苑县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11111",
                  "name": "涞水县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11112",
                  "name": "阜平县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11113",
                  "name": "徐水县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11114",
                  "name": "定兴县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11115",
                  "name": "唐县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11116",
                  "name": "高阳县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11117",
                  "name": "容城县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11118",
                  "name": "涞源县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11119",
                  "name": "望都县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11120",
                  "name": "安新县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11121",
                  "name": "易县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11122",
                  "name": "曲阳县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11123",
                  "name": "蠡县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11124",
                  "name": "顺平县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11125",
                  "name": "博野县",
                  "pCode": "10139"
                },
                {
                  "childrenList": null,
                  "code": "11126",
                  "name": "雄县",
                  "pCode": "10139"
                }
              ],
              "code": "10139",
              "name": "保定",
              "pCode": "10010"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11127",
                  "name": "运河区",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11128",
                  "name": "新华区",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11129",
                  "name": "泊头市",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11130",
                  "name": "任丘市",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11131",
                  "name": "黄骅市",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11132",
                  "name": "河间市",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11133",
                  "name": "沧县",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11134",
                  "name": "青县",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11135",
                  "name": "东光县",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11136",
                  "name": "海兴县",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11137",
                  "name": "盐山县",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11138",
                  "name": "肃宁县",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11139",
                  "name": "南皮县",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11140",
                  "name": "吴桥县",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11141",
                  "name": "献县",
                  "pCode": "10140"
                },
                {
                  "childrenList": null,
                  "code": "11142",
                  "name": "孟村",
                  "pCode": "10140"
                }
              ],
              "code": "10140",
              "name": "沧州",
              "pCode": "10010"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11143",
                  "name": "双桥区",
                  "pCode": "10141"
                },
                {
                  "childrenList": null,
                  "code": "11144",
                  "name": "双滦区",
                  "pCode": "10141"
                },
                {
                  "childrenList": null,
                  "code": "11145",
                  "name": "鹰手营子矿区",
                  "pCode": "10141"
                },
                {
                  "childrenList": null,
                  "code": "11146",
                  "name": "承德县",
                  "pCode": "10141"
                },
                {
                  "childrenList": null,
                  "code": "11147",
                  "name": "兴隆县",
                  "pCode": "10141"
                },
                {
                  "childrenList": null,
                  "code": "11148",
                  "name": "平泉县",
                  "pCode": "10141"
                },
                {
                  "childrenList": null,
                  "code": "11149",
                  "name": "滦平县",
                  "pCode": "10141"
                },
                {
                  "childrenList": null,
                  "code": "11150",
                  "name": "隆化县",
                  "pCode": "10141"
                },
                {
                  "childrenList": null,
                  "code": "11151",
                  "name": "丰宁",
                  "pCode": "10141"
                },
                {
                  "childrenList": null,
                  "code": "11152",
                  "name": "宽城",
                  "pCode": "10141"
                },
                {
                  "childrenList": null,
                  "code": "11153",
                  "name": "围场",
                  "pCode": "10141"
                }
              ],
              "code": "10141",
              "name": "承德",
              "pCode": "10010"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11154",
                  "name": "从台区",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11155",
                  "name": "复兴区",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11156",
                  "name": "邯山区",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11157",
                  "name": "峰峰矿区",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11158",
                  "name": "武安市",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11159",
                  "name": "邯郸县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11160",
                  "name": "临漳县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11161",
                  "name": "成安县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11162",
                  "name": "大名县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11163",
                  "name": "涉县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11164",
                  "name": "磁县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11165",
                  "name": "肥乡县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11166",
                  "name": "永年县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11167",
                  "name": "邱县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11168",
                  "name": "鸡泽县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11169",
                  "name": "广平县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11170",
                  "name": "馆陶县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11171",
                  "name": "魏县",
                  "pCode": "10142"
                },
                {
                  "childrenList": null,
                  "code": "11172",
                  "name": "曲周县",
                  "pCode": "10142"
                }
              ],
              "code": "10142",
              "name": "邯郸",
              "pCode": "10010"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11173",
                  "name": "桃城区",
                  "pCode": "10143"
                },
                {
                  "childrenList": null,
                  "code": "11174",
                  "name": "冀州市",
                  "pCode": "10143"
                },
                {
                  "childrenList": null,
                  "code": "11175",
                  "name": "深州市",
                  "pCode": "10143"
                },
                {
                  "childrenList": null,
                  "code": "11176",
                  "name": "枣强县",
                  "pCode": "10143"
                },
                {
                  "childrenList": null,
                  "code": "11177",
                  "name": "武邑县",
                  "pCode": "10143"
                },
                {
                  "childrenList": null,
                  "code": "11178",
                  "name": "武强县",
                  "pCode": "10143"
                },
                {
                  "childrenList": null,
                  "code": "11179",
                  "name": "饶阳县",
                  "pCode": "10143"
                },
                {
                  "childrenList": null,
                  "code": "11180",
                  "name": "安平县",
                  "pCode": "10143"
                },
                {
                  "childrenList": null,
                  "code": "11181",
                  "name": "故城县",
                  "pCode": "10143"
                },
                {
                  "childrenList": null,
                  "code": "11182",
                  "name": "景县",
                  "pCode": "10143"
                },
                {
                  "childrenList": null,
                  "code": "11183",
                  "name": "阜城县",
                  "pCode": "10143"
                }
              ],
              "code": "10143",
              "name": "衡水",
              "pCode": "10010"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11184",
                  "name": "安次区",
                  "pCode": "10144"
                },
                {
                  "childrenList": null,
                  "code": "11185",
                  "name": "广阳区",
                  "pCode": "10144"
                },
                {
                  "childrenList": null,
                  "code": "11186",
                  "name": "霸州市",
                  "pCode": "10144"
                },
                {
                  "childrenList": null,
                  "code": "11187",
                  "name": "三河市",
                  "pCode": "10144"
                },
                {
                  "childrenList": null,
                  "code": "11188",
                  "name": "固安县",
                  "pCode": "10144"
                },
                {
                  "childrenList": null,
                  "code": "11189",
                  "name": "永清县",
                  "pCode": "10144"
                },
                {
                  "childrenList": null,
                  "code": "11190",
                  "name": "香河县",
                  "pCode": "10144"
                },
                {
                  "childrenList": null,
                  "code": "11191",
                  "name": "大城县",
                  "pCode": "10144"
                },
                {
                  "childrenList": null,
                  "code": "11192",
                  "name": "文安县",
                  "pCode": "10144"
                },
                {
                  "childrenList": null,
                  "code": "11193",
                  "name": "大厂",
                  "pCode": "10144"
                }
              ],
              "code": "10144",
              "name": "廊坊",
              "pCode": "10010"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11194",
                  "name": "海港区",
                  "pCode": "10145"
                },
                {
                  "childrenList": null,
                  "code": "11195",
                  "name": "山海关区",
                  "pCode": "10145"
                },
                {
                  "childrenList": null,
                  "code": "11196",
                  "name": "北戴河区",
                  "pCode": "10145"
                },
                {
                  "childrenList": null,
                  "code": "11197",
                  "name": "昌黎县",
                  "pCode": "10145"
                },
                {
                  "childrenList": null,
                  "code": "11198",
                  "name": "抚宁县",
                  "pCode": "10145"
                },
                {
                  "childrenList": null,
                  "code": "11199",
                  "name": "卢龙县",
                  "pCode": "10145"
                },
                {
                  "childrenList": null,
                  "code": "11200",
                  "name": "青龙",
                  "pCode": "10145"
                }
              ],
              "code": "10145",
              "name": "秦皇岛",
              "pCode": "10010"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11201",
                  "name": "路北区",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11202",
                  "name": "路南区",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11203",
                  "name": "古冶区",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11204",
                  "name": "开平区",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11205",
                  "name": "丰南区",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11206",
                  "name": "丰润区",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11207",
                  "name": "遵化市",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11208",
                  "name": "迁安市",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11209",
                  "name": "滦县",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11210",
                  "name": "滦南县",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11211",
                  "name": "乐亭县",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11212",
                  "name": "迁西县",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11213",
                  "name": "玉田县",
                  "pCode": "10146"
                },
                {
                  "childrenList": null,
                  "code": "11214",
                  "name": "唐海县",
                  "pCode": "10146"
                }
              ],
              "code": "10146",
              "name": "唐山",
              "pCode": "10010"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11215",
                  "name": "桥东区",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11216",
                  "name": "桥西区",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11217",
                  "name": "南宫市",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11218",
                  "name": "沙河市",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11219",
                  "name": "邢台县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11220",
                  "name": "临城县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11221",
                  "name": "内丘县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11222",
                  "name": "柏乡县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11223",
                  "name": "隆尧县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11224",
                  "name": "任县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11225",
                  "name": "南和县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11226",
                  "name": "宁晋县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11227",
                  "name": "巨鹿县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11228",
                  "name": "新河县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11229",
                  "name": "广宗县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11230",
                  "name": "平乡县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11231",
                  "name": "威县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11232",
                  "name": "清河县",
                  "pCode": "10147"
                },
                {
                  "childrenList": null,
                  "code": "11233",
                  "name": "临西县",
                  "pCode": "10147"
                }
              ],
              "code": "10147",
              "name": "邢台",
              "pCode": "10010"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11234",
                  "name": "桥西区",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11235",
                  "name": "桥东区",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11236",
                  "name": "宣化区",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11237",
                  "name": "下花园区",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11238",
                  "name": "宣化县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11239",
                  "name": "张北县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11240",
                  "name": "康保县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11241",
                  "name": "沽源县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11242",
                  "name": "尚义县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11243",
                  "name": "蔚县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11244",
                  "name": "阳原县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11245",
                  "name": "怀安县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11246",
                  "name": "万全县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11247",
                  "name": "怀来县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11248",
                  "name": "涿鹿县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11249",
                  "name": "赤城县",
                  "pCode": "10148"
                },
                {
                  "childrenList": null,
                  "code": "11250",
                  "name": "崇礼县",
                  "pCode": "10148"
                }
              ],
              "code": "10148",
              "name": "张家口",
              "pCode": "10010"
            }
          ],
          "code": "10010",
          "name": "河北省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11251",
                  "name": "金水区",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11252",
                  "name": "邙山区",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11253",
                  "name": "二七区",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11254",
                  "name": "管城区",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11255",
                  "name": "中原区",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11256",
                  "name": "上街区",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11257",
                  "name": "惠济区",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11258",
                  "name": "郑东新区",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11259",
                  "name": "经济技术开发区",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11260",
                  "name": "高新开发区",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11261",
                  "name": "出口加工区",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11262",
                  "name": "巩义市",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11263",
                  "name": "荥阳市",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11264",
                  "name": "新密市",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11265",
                  "name": "新郑市",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11266",
                  "name": "登封市",
                  "pCode": "10149"
                },
                {
                  "childrenList": null,
                  "code": "11267",
                  "name": "中牟县",
                  "pCode": "10149"
                }
              ],
              "code": "10149",
              "name": "郑州",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11268",
                  "name": "西工区",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11269",
                  "name": "老城区",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11270",
                  "name": "涧西区",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11271",
                  "name": "瀍河回族区",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11272",
                  "name": "洛龙区",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11273",
                  "name": "吉利区",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11274",
                  "name": "偃师市",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11275",
                  "name": "孟津县",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11276",
                  "name": "新安县",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11277",
                  "name": "栾川县",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11278",
                  "name": "嵩县",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11279",
                  "name": "汝阳县",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11280",
                  "name": "宜阳县",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11281",
                  "name": "洛宁县",
                  "pCode": "10150"
                },
                {
                  "childrenList": null,
                  "code": "11282",
                  "name": "伊川县",
                  "pCode": "10150"
                }
              ],
              "code": "10150",
              "name": "洛阳",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11283",
                  "name": "鼓楼区",
                  "pCode": "10151"
                },
                {
                  "childrenList": null,
                  "code": "11284",
                  "name": "龙亭区",
                  "pCode": "10151"
                },
                {
                  "childrenList": null,
                  "code": "11285",
                  "name": "顺河回族区",
                  "pCode": "10151"
                },
                {
                  "childrenList": null,
                  "code": "11286",
                  "name": "金明区",
                  "pCode": "10151"
                },
                {
                  "childrenList": null,
                  "code": "11287",
                  "name": "禹王台区",
                  "pCode": "10151"
                },
                {
                  "childrenList": null,
                  "code": "11288",
                  "name": "杞县",
                  "pCode": "10151"
                },
                {
                  "childrenList": null,
                  "code": "11289",
                  "name": "通许县",
                  "pCode": "10151"
                },
                {
                  "childrenList": null,
                  "code": "11290",
                  "name": "尉氏县",
                  "pCode": "10151"
                },
                {
                  "childrenList": null,
                  "code": "11291",
                  "name": "开封县",
                  "pCode": "10151"
                },
                {
                  "childrenList": null,
                  "code": "11292",
                  "name": "兰考县",
                  "pCode": "10151"
                }
              ],
              "code": "10151",
              "name": "开封",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11293",
                  "name": "北关区",
                  "pCode": "10152"
                },
                {
                  "childrenList": null,
                  "code": "11294",
                  "name": "文峰区",
                  "pCode": "10152"
                },
                {
                  "childrenList": null,
                  "code": "11295",
                  "name": "殷都区",
                  "pCode": "10152"
                },
                {
                  "childrenList": null,
                  "code": "11296",
                  "name": "龙安区",
                  "pCode": "10152"
                },
                {
                  "childrenList": null,
                  "code": "11297",
                  "name": "林州市",
                  "pCode": "10152"
                },
                {
                  "childrenList": null,
                  "code": "11298",
                  "name": "安阳县",
                  "pCode": "10152"
                },
                {
                  "childrenList": null,
                  "code": "11299",
                  "name": "汤阴县",
                  "pCode": "10152"
                },
                {
                  "childrenList": null,
                  "code": "11300",
                  "name": "滑县",
                  "pCode": "10152"
                },
                {
                  "childrenList": null,
                  "code": "11301",
                  "name": "内黄县",
                  "pCode": "10152"
                }
              ],
              "code": "10152",
              "name": "安阳",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11302",
                  "name": "淇滨区",
                  "pCode": "10153"
                },
                {
                  "childrenList": null,
                  "code": "11303",
                  "name": "山城区",
                  "pCode": "10153"
                },
                {
                  "childrenList": null,
                  "code": "11304",
                  "name": "鹤山区",
                  "pCode": "10153"
                },
                {
                  "childrenList": null,
                  "code": "11305",
                  "name": "浚县",
                  "pCode": "10153"
                },
                {
                  "childrenList": null,
                  "code": "11306",
                  "name": "淇县",
                  "pCode": "10153"
                }
              ],
              "code": "10153",
              "name": "鹤壁",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11307",
                  "name": "济源市",
                  "pCode": "10154"
                }
              ],
              "code": "10154",
              "name": "济源",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11308",
                  "name": "解放区",
                  "pCode": "10155"
                },
                {
                  "childrenList": null,
                  "code": "11309",
                  "name": "中站区",
                  "pCode": "10155"
                },
                {
                  "childrenList": null,
                  "code": "11310",
                  "name": "马村区",
                  "pCode": "10155"
                },
                {
                  "childrenList": null,
                  "code": "11311",
                  "name": "山阳区",
                  "pCode": "10155"
                },
                {
                  "childrenList": null,
                  "code": "11312",
                  "name": "沁阳市",
                  "pCode": "10155"
                },
                {
                  "childrenList": null,
                  "code": "11313",
                  "name": "孟州市",
                  "pCode": "10155"
                },
                {
                  "childrenList": null,
                  "code": "11314",
                  "name": "修武县",
                  "pCode": "10155"
                },
                {
                  "childrenList": null,
                  "code": "11315",
                  "name": "博爱县",
                  "pCode": "10155"
                },
                {
                  "childrenList": null,
                  "code": "11316",
                  "name": "武陟县",
                  "pCode": "10155"
                },
                {
                  "childrenList": null,
                  "code": "11317",
                  "name": "温县",
                  "pCode": "10155"
                }
              ],
              "code": "10155",
              "name": "焦作",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11318",
                  "name": "卧龙区",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11319",
                  "name": "宛城区",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11320",
                  "name": "邓州市",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11321",
                  "name": "南召县",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11322",
                  "name": "方城县",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11323",
                  "name": "西峡县",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11324",
                  "name": "镇平县",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11325",
                  "name": "内乡县",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11326",
                  "name": "淅川县",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11327",
                  "name": "社旗县",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11328",
                  "name": "唐河县",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11329",
                  "name": "新野县",
                  "pCode": "10156"
                },
                {
                  "childrenList": null,
                  "code": "11330",
                  "name": "桐柏县",
                  "pCode": "10156"
                }
              ],
              "code": "10156",
              "name": "南阳",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11331",
                  "name": "新华区",
                  "pCode": "10157"
                },
                {
                  "childrenList": null,
                  "code": "11332",
                  "name": "卫东区",
                  "pCode": "10157"
                },
                {
                  "childrenList": null,
                  "code": "11333",
                  "name": "湛河区",
                  "pCode": "10157"
                },
                {
                  "childrenList": null,
                  "code": "11334",
                  "name": "石龙区",
                  "pCode": "10157"
                },
                {
                  "childrenList": null,
                  "code": "11335",
                  "name": "舞钢市",
                  "pCode": "10157"
                },
                {
                  "childrenList": null,
                  "code": "11336",
                  "name": "汝州市",
                  "pCode": "10157"
                },
                {
                  "childrenList": null,
                  "code": "11337",
                  "name": "宝丰县",
                  "pCode": "10157"
                },
                {
                  "childrenList": null,
                  "code": "11338",
                  "name": "叶县",
                  "pCode": "10157"
                },
                {
                  "childrenList": null,
                  "code": "11339",
                  "name": "鲁山县",
                  "pCode": "10157"
                },
                {
                  "childrenList": null,
                  "code": "11340",
                  "name": "郏县",
                  "pCode": "10157"
                }
              ],
              "code": "10157",
              "name": "平顶山",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11341",
                  "name": "湖滨区",
                  "pCode": "10158"
                },
                {
                  "childrenList": null,
                  "code": "11342",
                  "name": "义马市",
                  "pCode": "10158"
                },
                {
                  "childrenList": null,
                  "code": "11343",
                  "name": "灵宝市",
                  "pCode": "10158"
                },
                {
                  "childrenList": null,
                  "code": "11344",
                  "name": "渑池县",
                  "pCode": "10158"
                },
                {
                  "childrenList": null,
                  "code": "11345",
                  "name": "陕县",
                  "pCode": "10158"
                },
                {
                  "childrenList": null,
                  "code": "11346",
                  "name": "卢氏县",
                  "pCode": "10158"
                }
              ],
              "code": "10158",
              "name": "三门峡",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11347",
                  "name": "梁园区",
                  "pCode": "10159"
                },
                {
                  "childrenList": null,
                  "code": "11348",
                  "name": "睢阳区",
                  "pCode": "10159"
                },
                {
                  "childrenList": null,
                  "code": "11349",
                  "name": "永城市",
                  "pCode": "10159"
                },
                {
                  "childrenList": null,
                  "code": "11350",
                  "name": "民权县",
                  "pCode": "10159"
                },
                {
                  "childrenList": null,
                  "code": "11351",
                  "name": "睢县",
                  "pCode": "10159"
                },
                {
                  "childrenList": null,
                  "code": "11352",
                  "name": "宁陵县",
                  "pCode": "10159"
                },
                {
                  "childrenList": null,
                  "code": "11353",
                  "name": "虞城县",
                  "pCode": "10159"
                },
                {
                  "childrenList": null,
                  "code": "11354",
                  "name": "柘城县",
                  "pCode": "10159"
                },
                {
                  "childrenList": null,
                  "code": "11355",
                  "name": "夏邑县",
                  "pCode": "10159"
                }
              ],
              "code": "10159",
              "name": "商丘",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11356",
                  "name": "卫滨区",
                  "pCode": "10160"
                },
                {
                  "childrenList": null,
                  "code": "11357",
                  "name": "红旗区",
                  "pCode": "10160"
                },
                {
                  "childrenList": null,
                  "code": "11358",
                  "name": "凤泉区",
                  "pCode": "10160"
                },
                {
                  "childrenList": null,
                  "code": "11359",
                  "name": "牧野区",
                  "pCode": "10160"
                },
                {
                  "childrenList": null,
                  "code": "11360",
                  "name": "卫辉市",
                  "pCode": "10160"
                },
                {
                  "childrenList": null,
                  "code": "11361",
                  "name": "辉县市",
                  "pCode": "10160"
                },
                {
                  "childrenList": null,
                  "code": "11362",
                  "name": "新乡县",
                  "pCode": "10160"
                },
                {
                  "childrenList": null,
                  "code": "11363",
                  "name": "获嘉县",
                  "pCode": "10160"
                },
                {
                  "childrenList": null,
                  "code": "11364",
                  "name": "原阳县",
                  "pCode": "10160"
                },
                {
                  "childrenList": null,
                  "code": "11365",
                  "name": "延津县",
                  "pCode": "10160"
                },
                {
                  "childrenList": null,
                  "code": "11366",
                  "name": "封丘县",
                  "pCode": "10160"
                },
                {
                  "childrenList": null,
                  "code": "11367",
                  "name": "长垣县",
                  "pCode": "10160"
                }
              ],
              "code": "10160",
              "name": "新乡",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11368",
                  "name": "浉河区",
                  "pCode": "10161"
                },
                {
                  "childrenList": null,
                  "code": "11369",
                  "name": "平桥区",
                  "pCode": "10161"
                },
                {
                  "childrenList": null,
                  "code": "11370",
                  "name": "罗山县",
                  "pCode": "10161"
                },
                {
                  "childrenList": null,
                  "code": "11371",
                  "name": "光山县",
                  "pCode": "10161"
                },
                {
                  "childrenList": null,
                  "code": "11372",
                  "name": "新县",
                  "pCode": "10161"
                },
                {
                  "childrenList": null,
                  "code": "11373",
                  "name": "商城县",
                  "pCode": "10161"
                },
                {
                  "childrenList": null,
                  "code": "11374",
                  "name": "固始县",
                  "pCode": "10161"
                },
                {
                  "childrenList": null,
                  "code": "11375",
                  "name": "潢川县",
                  "pCode": "10161"
                },
                {
                  "childrenList": null,
                  "code": "11376",
                  "name": "淮滨县",
                  "pCode": "10161"
                },
                {
                  "childrenList": null,
                  "code": "11377",
                  "name": "息县",
                  "pCode": "10161"
                }
              ],
              "code": "10161",
              "name": "信阳",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11378",
                  "name": "魏都区",
                  "pCode": "10162"
                },
                {
                  "childrenList": null,
                  "code": "11379",
                  "name": "禹州市",
                  "pCode": "10162"
                },
                {
                  "childrenList": null,
                  "code": "11380",
                  "name": "长葛市",
                  "pCode": "10162"
                },
                {
                  "childrenList": null,
                  "code": "11381",
                  "name": "许昌县",
                  "pCode": "10162"
                },
                {
                  "childrenList": null,
                  "code": "11382",
                  "name": "鄢陵县",
                  "pCode": "10162"
                },
                {
                  "childrenList": null,
                  "code": "11383",
                  "name": "襄城县",
                  "pCode": "10162"
                }
              ],
              "code": "10162",
              "name": "许昌",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11384",
                  "name": "川汇区",
                  "pCode": "10163"
                },
                {
                  "childrenList": null,
                  "code": "11385",
                  "name": "项城市",
                  "pCode": "10163"
                },
                {
                  "childrenList": null,
                  "code": "11386",
                  "name": "扶沟县",
                  "pCode": "10163"
                },
                {
                  "childrenList": null,
                  "code": "11387",
                  "name": "西华县",
                  "pCode": "10163"
                },
                {
                  "childrenList": null,
                  "code": "11388",
                  "name": "商水县",
                  "pCode": "10163"
                },
                {
                  "childrenList": null,
                  "code": "11389",
                  "name": "沈丘县",
                  "pCode": "10163"
                },
                {
                  "childrenList": null,
                  "code": "11390",
                  "name": "郸城县",
                  "pCode": "10163"
                },
                {
                  "childrenList": null,
                  "code": "11391",
                  "name": "淮阳县",
                  "pCode": "10163"
                },
                {
                  "childrenList": null,
                  "code": "11392",
                  "name": "太康县",
                  "pCode": "10163"
                },
                {
                  "childrenList": null,
                  "code": "11393",
                  "name": "鹿邑县",
                  "pCode": "10163"
                }
              ],
              "code": "10163",
              "name": "周口",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11394",
                  "name": "驿城区",
                  "pCode": "10164"
                },
                {
                  "childrenList": null,
                  "code": "11395",
                  "name": "西平县",
                  "pCode": "10164"
                },
                {
                  "childrenList": null,
                  "code": "11396",
                  "name": "上蔡县",
                  "pCode": "10164"
                },
                {
                  "childrenList": null,
                  "code": "11397",
                  "name": "平舆县",
                  "pCode": "10164"
                },
                {
                  "childrenList": null,
                  "code": "11398",
                  "name": "正阳县",
                  "pCode": "10164"
                },
                {
                  "childrenList": null,
                  "code": "11399",
                  "name": "确山县",
                  "pCode": "10164"
                },
                {
                  "childrenList": null,
                  "code": "11400",
                  "name": "泌阳县",
                  "pCode": "10164"
                },
                {
                  "childrenList": null,
                  "code": "11401",
                  "name": "汝南县",
                  "pCode": "10164"
                },
                {
                  "childrenList": null,
                  "code": "11402",
                  "name": "遂平县",
                  "pCode": "10164"
                },
                {
                  "childrenList": null,
                  "code": "11403",
                  "name": "新蔡县",
                  "pCode": "10164"
                }
              ],
              "code": "10164",
              "name": "驻马店",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11404",
                  "name": "郾城区",
                  "pCode": "10165"
                },
                {
                  "childrenList": null,
                  "code": "11405",
                  "name": "源汇区",
                  "pCode": "10165"
                },
                {
                  "childrenList": null,
                  "code": "11406",
                  "name": "召陵区",
                  "pCode": "10165"
                },
                {
                  "childrenList": null,
                  "code": "11407",
                  "name": "舞阳县",
                  "pCode": "10165"
                },
                {
                  "childrenList": null,
                  "code": "11408",
                  "name": "临颍县",
                  "pCode": "10165"
                }
              ],
              "code": "10165",
              "name": "漯河",
              "pCode": "10011"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11409",
                  "name": "华龙区",
                  "pCode": "10166"
                },
                {
                  "childrenList": null,
                  "code": "11410",
                  "name": "清丰县",
                  "pCode": "10166"
                },
                {
                  "childrenList": null,
                  "code": "11411",
                  "name": "南乐县",
                  "pCode": "10166"
                },
                {
                  "childrenList": null,
                  "code": "11412",
                  "name": "范县",
                  "pCode": "10166"
                },
                {
                  "childrenList": null,
                  "code": "11413",
                  "name": "台前县",
                  "pCode": "10166"
                },
                {
                  "childrenList": null,
                  "code": "11414",
                  "name": "濮阳县",
                  "pCode": "10166"
                }
              ],
              "code": "10166",
              "name": "濮阳",
              "pCode": "10011"
            }
          ],
          "code": "10011",
          "name": "河南省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11415",
                  "name": "道里区",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11416",
                  "name": "南岗区",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11417",
                  "name": "动力区",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11418",
                  "name": "平房区",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11419",
                  "name": "香坊区",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11420",
                  "name": "太平区",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11421",
                  "name": "道外区",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11422",
                  "name": "阿城区",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11423",
                  "name": "呼兰区",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11424",
                  "name": "松北区",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11425",
                  "name": "尚志市",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11426",
                  "name": "双城市",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11427",
                  "name": "五常市",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11428",
                  "name": "方正县",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11429",
                  "name": "宾县",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11430",
                  "name": "依兰县",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11431",
                  "name": "巴彦县",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11432",
                  "name": "通河县",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11433",
                  "name": "木兰县",
                  "pCode": "10167"
                },
                {
                  "childrenList": null,
                  "code": "11434",
                  "name": "延寿县",
                  "pCode": "10167"
                }
              ],
              "code": "10167",
              "name": "哈尔滨",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11435",
                  "name": "萨尔图区",
                  "pCode": "10168"
                },
                {
                  "childrenList": null,
                  "code": "11436",
                  "name": "红岗区",
                  "pCode": "10168"
                },
                {
                  "childrenList": null,
                  "code": "11437",
                  "name": "龙凤区",
                  "pCode": "10168"
                },
                {
                  "childrenList": null,
                  "code": "11438",
                  "name": "让胡路区",
                  "pCode": "10168"
                },
                {
                  "childrenList": null,
                  "code": "11439",
                  "name": "大同区",
                  "pCode": "10168"
                },
                {
                  "childrenList": null,
                  "code": "11440",
                  "name": "肇州县",
                  "pCode": "10168"
                },
                {
                  "childrenList": null,
                  "code": "11441",
                  "name": "肇源县",
                  "pCode": "10168"
                },
                {
                  "childrenList": null,
                  "code": "11442",
                  "name": "林甸县",
                  "pCode": "10168"
                },
                {
                  "childrenList": null,
                  "code": "11443",
                  "name": "杜尔伯特",
                  "pCode": "10168"
                }
              ],
              "code": "10168",
              "name": "大庆",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11444",
                  "name": "呼玛县",
                  "pCode": "10169"
                },
                {
                  "childrenList": null,
                  "code": "11445",
                  "name": "漠河县",
                  "pCode": "10169"
                },
                {
                  "childrenList": null,
                  "code": "11446",
                  "name": "塔河县",
                  "pCode": "10169"
                }
              ],
              "code": "10169",
              "name": "大兴安岭",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11447",
                  "name": "兴山区",
                  "pCode": "10170"
                },
                {
                  "childrenList": null,
                  "code": "11448",
                  "name": "工农区",
                  "pCode": "10170"
                },
                {
                  "childrenList": null,
                  "code": "11449",
                  "name": "南山区",
                  "pCode": "10170"
                },
                {
                  "childrenList": null,
                  "code": "11450",
                  "name": "兴安区",
                  "pCode": "10170"
                },
                {
                  "childrenList": null,
                  "code": "11451",
                  "name": "向阳区",
                  "pCode": "10170"
                },
                {
                  "childrenList": null,
                  "code": "11452",
                  "name": "东山区",
                  "pCode": "10170"
                },
                {
                  "childrenList": null,
                  "code": "11453",
                  "name": "萝北县",
                  "pCode": "10170"
                },
                {
                  "childrenList": null,
                  "code": "11454",
                  "name": "绥滨县",
                  "pCode": "10170"
                }
              ],
              "code": "10170",
              "name": "鹤岗",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11455",
                  "name": "爱辉区",
                  "pCode": "10171"
                },
                {
                  "childrenList": null,
                  "code": "11456",
                  "name": "五大连池市",
                  "pCode": "10171"
                },
                {
                  "childrenList": null,
                  "code": "11457",
                  "name": "北安市",
                  "pCode": "10171"
                },
                {
                  "childrenList": null,
                  "code": "11458",
                  "name": "嫩江县",
                  "pCode": "10171"
                },
                {
                  "childrenList": null,
                  "code": "11459",
                  "name": "逊克县",
                  "pCode": "10171"
                },
                {
                  "childrenList": null,
                  "code": "11460",
                  "name": "孙吴县",
                  "pCode": "10171"
                }
              ],
              "code": "10171",
              "name": "黑河",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11461",
                  "name": "鸡冠区",
                  "pCode": "10172"
                },
                {
                  "childrenList": null,
                  "code": "11462",
                  "name": "恒山区",
                  "pCode": "10172"
                },
                {
                  "childrenList": null,
                  "code": "11463",
                  "name": "城子河区",
                  "pCode": "10172"
                },
                {
                  "childrenList": null,
                  "code": "11464",
                  "name": "滴道区",
                  "pCode": "10172"
                },
                {
                  "childrenList": null,
                  "code": "11465",
                  "name": "梨树区",
                  "pCode": "10172"
                },
                {
                  "childrenList": null,
                  "code": "11466",
                  "name": "虎林市",
                  "pCode": "10172"
                },
                {
                  "childrenList": null,
                  "code": "11467",
                  "name": "密山市",
                  "pCode": "10172"
                },
                {
                  "childrenList": null,
                  "code": "11468",
                  "name": "鸡东县",
                  "pCode": "10172"
                }
              ],
              "code": "10172",
              "name": "鸡西",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11469",
                  "name": "前进区",
                  "pCode": "10173"
                },
                {
                  "childrenList": null,
                  "code": "11470",
                  "name": "郊区",
                  "pCode": "10173"
                },
                {
                  "childrenList": null,
                  "code": "11471",
                  "name": "向阳区",
                  "pCode": "10173"
                },
                {
                  "childrenList": null,
                  "code": "11472",
                  "name": "东风区",
                  "pCode": "10173"
                },
                {
                  "childrenList": null,
                  "code": "11473",
                  "name": "同江市",
                  "pCode": "10173"
                },
                {
                  "childrenList": null,
                  "code": "11474",
                  "name": "富锦市",
                  "pCode": "10173"
                },
                {
                  "childrenList": null,
                  "code": "11475",
                  "name": "桦南县",
                  "pCode": "10173"
                },
                {
                  "childrenList": null,
                  "code": "11476",
                  "name": "桦川县",
                  "pCode": "10173"
                },
                {
                  "childrenList": null,
                  "code": "11477",
                  "name": "汤原县",
                  "pCode": "10173"
                },
                {
                  "childrenList": null,
                  "code": "11478",
                  "name": "抚远县",
                  "pCode": "10173"
                }
              ],
              "code": "10173",
              "name": "佳木斯",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11479",
                  "name": "爱民区",
                  "pCode": "10174"
                },
                {
                  "childrenList": null,
                  "code": "11480",
                  "name": "东安区",
                  "pCode": "10174"
                },
                {
                  "childrenList": null,
                  "code": "11481",
                  "name": "阳明区",
                  "pCode": "10174"
                },
                {
                  "childrenList": null,
                  "code": "11482",
                  "name": "西安区",
                  "pCode": "10174"
                },
                {
                  "childrenList": null,
                  "code": "11483",
                  "name": "绥芬河市",
                  "pCode": "10174"
                },
                {
                  "childrenList": null,
                  "code": "11484",
                  "name": "海林市",
                  "pCode": "10174"
                },
                {
                  "childrenList": null,
                  "code": "11485",
                  "name": "宁安市",
                  "pCode": "10174"
                },
                {
                  "childrenList": null,
                  "code": "11486",
                  "name": "穆棱市",
                  "pCode": "10174"
                },
                {
                  "childrenList": null,
                  "code": "11487",
                  "name": "东宁县",
                  "pCode": "10174"
                },
                {
                  "childrenList": null,
                  "code": "11488",
                  "name": "林口县",
                  "pCode": "10174"
                }
              ],
              "code": "10174",
              "name": "牡丹江",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11489",
                  "name": "桃山区",
                  "pCode": "10175"
                },
                {
                  "childrenList": null,
                  "code": "11490",
                  "name": "新兴区",
                  "pCode": "10175"
                },
                {
                  "childrenList": null,
                  "code": "11491",
                  "name": "茄子河区",
                  "pCode": "10175"
                },
                {
                  "childrenList": null,
                  "code": "11492",
                  "name": "勃利县",
                  "pCode": "10175"
                }
              ],
              "code": "10175",
              "name": "七台河",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11493",
                  "name": "龙沙区",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11494",
                  "name": "昂昂溪区",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11495",
                  "name": "铁峰区",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11496",
                  "name": "建华区",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11497",
                  "name": "富拉尔基区",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11498",
                  "name": "碾子山区",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11499",
                  "name": "梅里斯达斡尔区",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11500",
                  "name": "讷河市",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11501",
                  "name": "龙江县",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11502",
                  "name": "依安县",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11503",
                  "name": "泰来县",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11504",
                  "name": "甘南县",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11505",
                  "name": "富裕县",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11506",
                  "name": "克山县",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11507",
                  "name": "克东县",
                  "pCode": "10176"
                },
                {
                  "childrenList": null,
                  "code": "11508",
                  "name": "拜泉县",
                  "pCode": "10176"
                }
              ],
              "code": "10176",
              "name": "齐齐哈尔",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11509",
                  "name": "尖山区",
                  "pCode": "10177"
                },
                {
                  "childrenList": null,
                  "code": "11510",
                  "name": "岭东区",
                  "pCode": "10177"
                },
                {
                  "childrenList": null,
                  "code": "11511",
                  "name": "四方台区",
                  "pCode": "10177"
                },
                {
                  "childrenList": null,
                  "code": "11512",
                  "name": "宝山区",
                  "pCode": "10177"
                },
                {
                  "childrenList": null,
                  "code": "11513",
                  "name": "集贤县",
                  "pCode": "10177"
                },
                {
                  "childrenList": null,
                  "code": "11514",
                  "name": "友谊县",
                  "pCode": "10177"
                },
                {
                  "childrenList": null,
                  "code": "11515",
                  "name": "宝清县",
                  "pCode": "10177"
                },
                {
                  "childrenList": null,
                  "code": "11516",
                  "name": "饶河县",
                  "pCode": "10177"
                }
              ],
              "code": "10177",
              "name": "双鸭山",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11517",
                  "name": "北林区",
                  "pCode": "10178"
                },
                {
                  "childrenList": null,
                  "code": "11518",
                  "name": "安达市",
                  "pCode": "10178"
                },
                {
                  "childrenList": null,
                  "code": "11519",
                  "name": "肇东市",
                  "pCode": "10178"
                },
                {
                  "childrenList": null,
                  "code": "11520",
                  "name": "海伦市",
                  "pCode": "10178"
                },
                {
                  "childrenList": null,
                  "code": "11521",
                  "name": "望奎县",
                  "pCode": "10178"
                },
                {
                  "childrenList": null,
                  "code": "11522",
                  "name": "兰西县",
                  "pCode": "10178"
                },
                {
                  "childrenList": null,
                  "code": "11523",
                  "name": "青冈县",
                  "pCode": "10178"
                },
                {
                  "childrenList": null,
                  "code": "11524",
                  "name": "庆安县",
                  "pCode": "10178"
                },
                {
                  "childrenList": null,
                  "code": "11525",
                  "name": "明水县",
                  "pCode": "10178"
                },
                {
                  "childrenList": null,
                  "code": "11526",
                  "name": "绥棱县",
                  "pCode": "10178"
                }
              ],
              "code": "10178",
              "name": "绥化",
              "pCode": "10012"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11527",
                  "name": "伊春区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11528",
                  "name": "带岭区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11529",
                  "name": "南岔区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11530",
                  "name": "金山屯区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11531",
                  "name": "西林区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11532",
                  "name": "美溪区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11533",
                  "name": "乌马河区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11534",
                  "name": "翠峦区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11535",
                  "name": "友好区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11536",
                  "name": "上甘岭区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11537",
                  "name": "五营区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11538",
                  "name": "红星区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11539",
                  "name": "新青区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11540",
                  "name": "汤旺河区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11541",
                  "name": "乌伊岭区",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11542",
                  "name": "铁力市",
                  "pCode": "10179"
                },
                {
                  "childrenList": null,
                  "code": "11543",
                  "name": "嘉荫县",
                  "pCode": "10179"
                }
              ],
              "code": "10179",
              "name": "伊春",
              "pCode": "10012"
            }
          ],
          "code": "10012",
          "name": "黑龙江省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11544",
                  "name": "江岸区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11545",
                  "name": "武昌区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11546",
                  "name": "江汉区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11547",
                  "name": "硚口区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11548",
                  "name": "汉阳区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11549",
                  "name": "青山区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11550",
                  "name": "洪山区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11551",
                  "name": "东西湖区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11552",
                  "name": "汉南区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11553",
                  "name": "蔡甸区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11554",
                  "name": "江夏区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11555",
                  "name": "黄陂区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11556",
                  "name": "新洲区",
                  "pCode": "10180"
                },
                {
                  "childrenList": null,
                  "code": "11557",
                  "name": "经济开发区",
                  "pCode": "10180"
                }
              ],
              "code": "10180",
              "name": "武汉",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11558",
                  "name": "仙桃市",
                  "pCode": "10181"
                }
              ],
              "code": "10181",
              "name": "仙桃",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11559",
                  "name": "鄂城区",
                  "pCode": "10182"
                },
                {
                  "childrenList": null,
                  "code": "11560",
                  "name": "华容区",
                  "pCode": "10182"
                },
                {
                  "childrenList": null,
                  "code": "11561",
                  "name": "梁子湖区",
                  "pCode": "10182"
                }
              ],
              "code": "10182",
              "name": "鄂州",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11562",
                  "name": "黄州区",
                  "pCode": "10183"
                },
                {
                  "childrenList": null,
                  "code": "11563",
                  "name": "麻城市",
                  "pCode": "10183"
                },
                {
                  "childrenList": null,
                  "code": "11564",
                  "name": "武穴市",
                  "pCode": "10183"
                },
                {
                  "childrenList": null,
                  "code": "11565",
                  "name": "团风县",
                  "pCode": "10183"
                },
                {
                  "childrenList": null,
                  "code": "11566",
                  "name": "红安县",
                  "pCode": "10183"
                },
                {
                  "childrenList": null,
                  "code": "11567",
                  "name": "罗田县",
                  "pCode": "10183"
                },
                {
                  "childrenList": null,
                  "code": "11568",
                  "name": "英山县",
                  "pCode": "10183"
                },
                {
                  "childrenList": null,
                  "code": "11569",
                  "name": "浠水县",
                  "pCode": "10183"
                },
                {
                  "childrenList": null,
                  "code": "11570",
                  "name": "蕲春县",
                  "pCode": "10183"
                },
                {
                  "childrenList": null,
                  "code": "11571",
                  "name": "黄梅县",
                  "pCode": "10183"
                }
              ],
              "code": "10183",
              "name": "黄冈",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11572",
                  "name": "黄石港区",
                  "pCode": "10184"
                },
                {
                  "childrenList": null,
                  "code": "11573",
                  "name": "西塞山区",
                  "pCode": "10184"
                },
                {
                  "childrenList": null,
                  "code": "11574",
                  "name": "下陆区",
                  "pCode": "10184"
                },
                {
                  "childrenList": null,
                  "code": "11575",
                  "name": "铁山区",
                  "pCode": "10184"
                },
                {
                  "childrenList": null,
                  "code": "11576",
                  "name": "大冶市",
                  "pCode": "10184"
                },
                {
                  "childrenList": null,
                  "code": "11577",
                  "name": "阳新县",
                  "pCode": "10184"
                }
              ],
              "code": "10184",
              "name": "黄石",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11578",
                  "name": "东宝区",
                  "pCode": "10185"
                },
                {
                  "childrenList": null,
                  "code": "11579",
                  "name": "掇刀区",
                  "pCode": "10185"
                },
                {
                  "childrenList": null,
                  "code": "11580",
                  "name": "钟祥市",
                  "pCode": "10185"
                },
                {
                  "childrenList": null,
                  "code": "11581",
                  "name": "京山县",
                  "pCode": "10185"
                },
                {
                  "childrenList": null,
                  "code": "11582",
                  "name": "沙洋县",
                  "pCode": "10185"
                }
              ],
              "code": "10185",
              "name": "荆门",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11583",
                  "name": "沙市区",
                  "pCode": "10186"
                },
                {
                  "childrenList": null,
                  "code": "11584",
                  "name": "荆州区",
                  "pCode": "10186"
                },
                {
                  "childrenList": null,
                  "code": "11585",
                  "name": "石首市",
                  "pCode": "10186"
                },
                {
                  "childrenList": null,
                  "code": "11586",
                  "name": "洪湖市",
                  "pCode": "10186"
                },
                {
                  "childrenList": null,
                  "code": "11587",
                  "name": "松滋市",
                  "pCode": "10186"
                },
                {
                  "childrenList": null,
                  "code": "11588",
                  "name": "公安县",
                  "pCode": "10186"
                },
                {
                  "childrenList": null,
                  "code": "11589",
                  "name": "监利县",
                  "pCode": "10186"
                },
                {
                  "childrenList": null,
                  "code": "11590",
                  "name": "江陵县",
                  "pCode": "10186"
                }
              ],
              "code": "10186",
              "name": "荆州",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11591",
                  "name": "潜江市",
                  "pCode": "10187"
                }
              ],
              "code": "10187",
              "name": "潜江",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11592",
                  "name": "神农架林区",
                  "pCode": "10188"
                }
              ],
              "code": "10188",
              "name": "神农架林区",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11593",
                  "name": "张湾区",
                  "pCode": "10189"
                },
                {
                  "childrenList": null,
                  "code": "11594",
                  "name": "茅箭区",
                  "pCode": "10189"
                },
                {
                  "childrenList": null,
                  "code": "11595",
                  "name": "丹江口市",
                  "pCode": "10189"
                },
                {
                  "childrenList": null,
                  "code": "11596",
                  "name": "郧县",
                  "pCode": "10189"
                },
                {
                  "childrenList": null,
                  "code": "11597",
                  "name": "郧西县",
                  "pCode": "10189"
                },
                {
                  "childrenList": null,
                  "code": "11598",
                  "name": "竹山县",
                  "pCode": "10189"
                },
                {
                  "childrenList": null,
                  "code": "11599",
                  "name": "竹溪县",
                  "pCode": "10189"
                },
                {
                  "childrenList": null,
                  "code": "11600",
                  "name": "房县",
                  "pCode": "10189"
                }
              ],
              "code": "10189",
              "name": "十堰",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11601",
                  "name": "曾都区",
                  "pCode": "10190"
                },
                {
                  "childrenList": null,
                  "code": "11602",
                  "name": "广水市",
                  "pCode": "10190"
                }
              ],
              "code": "10190",
              "name": "随州",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11603",
                  "name": "天门市",
                  "pCode": "10191"
                }
              ],
              "code": "10191",
              "name": "天门",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11604",
                  "name": "咸安区",
                  "pCode": "10192"
                },
                {
                  "childrenList": null,
                  "code": "11605",
                  "name": "赤壁市",
                  "pCode": "10192"
                },
                {
                  "childrenList": null,
                  "code": "11606",
                  "name": "嘉鱼县",
                  "pCode": "10192"
                },
                {
                  "childrenList": null,
                  "code": "11607",
                  "name": "通城县",
                  "pCode": "10192"
                },
                {
                  "childrenList": null,
                  "code": "11608",
                  "name": "崇阳县",
                  "pCode": "10192"
                },
                {
                  "childrenList": null,
                  "code": "11609",
                  "name": "通山县",
                  "pCode": "10192"
                }
              ],
              "code": "10192",
              "name": "咸宁",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11610",
                  "name": "襄城区",
                  "pCode": "10193"
                },
                {
                  "childrenList": null,
                  "code": "11611",
                  "name": "樊城区",
                  "pCode": "10193"
                },
                {
                  "childrenList": null,
                  "code": "11612",
                  "name": "襄阳区",
                  "pCode": "10193"
                },
                {
                  "childrenList": null,
                  "code": "11613",
                  "name": "老河口市",
                  "pCode": "10193"
                },
                {
                  "childrenList": null,
                  "code": "11614",
                  "name": "枣阳市",
                  "pCode": "10193"
                },
                {
                  "childrenList": null,
                  "code": "11615",
                  "name": "宜城市",
                  "pCode": "10193"
                },
                {
                  "childrenList": null,
                  "code": "11616",
                  "name": "南漳县",
                  "pCode": "10193"
                },
                {
                  "childrenList": null,
                  "code": "11617",
                  "name": "谷城县",
                  "pCode": "10193"
                },
                {
                  "childrenList": null,
                  "code": "11618",
                  "name": "保康县",
                  "pCode": "10193"
                }
              ],
              "code": "10193",
              "name": "襄樊",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11619",
                  "name": "孝南区",
                  "pCode": "10194"
                },
                {
                  "childrenList": null,
                  "code": "11620",
                  "name": "应城市",
                  "pCode": "10194"
                },
                {
                  "childrenList": null,
                  "code": "11621",
                  "name": "安陆市",
                  "pCode": "10194"
                },
                {
                  "childrenList": null,
                  "code": "11622",
                  "name": "汉川市",
                  "pCode": "10194"
                },
                {
                  "childrenList": null,
                  "code": "11623",
                  "name": "孝昌县",
                  "pCode": "10194"
                },
                {
                  "childrenList": null,
                  "code": "11624",
                  "name": "大悟县",
                  "pCode": "10194"
                },
                {
                  "childrenList": null,
                  "code": "11625",
                  "name": "云梦县",
                  "pCode": "10194"
                }
              ],
              "code": "10194",
              "name": "孝感",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11626",
                  "name": "长阳",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11627",
                  "name": "五峰",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11628",
                  "name": "西陵区",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11629",
                  "name": "伍家岗区",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11630",
                  "name": "点军区",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11631",
                  "name": "猇亭区",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11632",
                  "name": "夷陵区",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11633",
                  "name": "宜都市",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11634",
                  "name": "当阳市",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11635",
                  "name": "枝江市",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11636",
                  "name": "远安县",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11637",
                  "name": "兴山县",
                  "pCode": "10195"
                },
                {
                  "childrenList": null,
                  "code": "11638",
                  "name": "秭归县",
                  "pCode": "10195"
                }
              ],
              "code": "10195",
              "name": "宜昌",
              "pCode": "10013"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11639",
                  "name": "恩施市",
                  "pCode": "10196"
                },
                {
                  "childrenList": null,
                  "code": "11640",
                  "name": "利川市",
                  "pCode": "10196"
                },
                {
                  "childrenList": null,
                  "code": "11641",
                  "name": "建始县",
                  "pCode": "10196"
                },
                {
                  "childrenList": null,
                  "code": "11642",
                  "name": "巴东县",
                  "pCode": "10196"
                },
                {
                  "childrenList": null,
                  "code": "11643",
                  "name": "宣恩县",
                  "pCode": "10196"
                },
                {
                  "childrenList": null,
                  "code": "11644",
                  "name": "咸丰县",
                  "pCode": "10196"
                },
                {
                  "childrenList": null,
                  "code": "11645",
                  "name": "来凤县",
                  "pCode": "10196"
                },
                {
                  "childrenList": null,
                  "code": "11646",
                  "name": "鹤峰县",
                  "pCode": "10196"
                }
              ],
              "code": "10196",
              "name": "恩施",
              "pCode": "10013"
            }
          ],
          "code": "10013",
          "name": "湖北省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11647",
                  "name": "岳麓区",
                  "pCode": "10197"
                },
                {
                  "childrenList": null,
                  "code": "11648",
                  "name": "芙蓉区",
                  "pCode": "10197"
                },
                {
                  "childrenList": null,
                  "code": "11649",
                  "name": "天心区",
                  "pCode": "10197"
                },
                {
                  "childrenList": null,
                  "code": "11650",
                  "name": "开福区",
                  "pCode": "10197"
                },
                {
                  "childrenList": null,
                  "code": "11651",
                  "name": "雨花区",
                  "pCode": "10197"
                },
                {
                  "childrenList": null,
                  "code": "11652",
                  "name": "开发区",
                  "pCode": "10197"
                },
                {
                  "childrenList": null,
                  "code": "11653",
                  "name": "浏阳市",
                  "pCode": "10197"
                },
                {
                  "childrenList": null,
                  "code": "11654",
                  "name": "长沙县",
                  "pCode": "10197"
                },
                {
                  "childrenList": null,
                  "code": "11655",
                  "name": "望城县",
                  "pCode": "10197"
                },
                {
                  "childrenList": null,
                  "code": "11656",
                  "name": "宁乡县",
                  "pCode": "10197"
                }
              ],
              "code": "10197",
              "name": "长沙",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11657",
                  "name": "永定区",
                  "pCode": "10198"
                },
                {
                  "childrenList": null,
                  "code": "11658",
                  "name": "武陵源区",
                  "pCode": "10198"
                },
                {
                  "childrenList": null,
                  "code": "11659",
                  "name": "慈利县",
                  "pCode": "10198"
                },
                {
                  "childrenList": null,
                  "code": "11660",
                  "name": "桑植县",
                  "pCode": "10198"
                }
              ],
              "code": "10198",
              "name": "张家界",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11661",
                  "name": "武陵区",
                  "pCode": "10199"
                },
                {
                  "childrenList": null,
                  "code": "11662",
                  "name": "鼎城区",
                  "pCode": "10199"
                },
                {
                  "childrenList": null,
                  "code": "11663",
                  "name": "津市市",
                  "pCode": "10199"
                },
                {
                  "childrenList": null,
                  "code": "11664",
                  "name": "安乡县",
                  "pCode": "10199"
                },
                {
                  "childrenList": null,
                  "code": "11665",
                  "name": "汉寿县",
                  "pCode": "10199"
                },
                {
                  "childrenList": null,
                  "code": "11666",
                  "name": "澧县",
                  "pCode": "10199"
                },
                {
                  "childrenList": null,
                  "code": "11667",
                  "name": "临澧县",
                  "pCode": "10199"
                },
                {
                  "childrenList": null,
                  "code": "11668",
                  "name": "桃源县",
                  "pCode": "10199"
                },
                {
                  "childrenList": null,
                  "code": "11669",
                  "name": "石门县",
                  "pCode": "10199"
                }
              ],
              "code": "10199",
              "name": "常德",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11670",
                  "name": "北湖区",
                  "pCode": "10200"
                },
                {
                  "childrenList": null,
                  "code": "11671",
                  "name": "苏仙区",
                  "pCode": "10200"
                },
                {
                  "childrenList": null,
                  "code": "11672",
                  "name": "资兴市",
                  "pCode": "10200"
                },
                {
                  "childrenList": null,
                  "code": "11673",
                  "name": "桂阳县",
                  "pCode": "10200"
                },
                {
                  "childrenList": null,
                  "code": "11674",
                  "name": "宜章县",
                  "pCode": "10200"
                },
                {
                  "childrenList": null,
                  "code": "11675",
                  "name": "永兴县",
                  "pCode": "10200"
                },
                {
                  "childrenList": null,
                  "code": "11676",
                  "name": "嘉禾县",
                  "pCode": "10200"
                },
                {
                  "childrenList": null,
                  "code": "11677",
                  "name": "临武县",
                  "pCode": "10200"
                },
                {
                  "childrenList": null,
                  "code": "11678",
                  "name": "汝城县",
                  "pCode": "10200"
                },
                {
                  "childrenList": null,
                  "code": "11679",
                  "name": "桂东县",
                  "pCode": "10200"
                },
                {
                  "childrenList": null,
                  "code": "11680",
                  "name": "安仁县",
                  "pCode": "10200"
                }
              ],
              "code": "10200",
              "name": "郴州",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11681",
                  "name": "雁峰区",
                  "pCode": "10201"
                },
                {
                  "childrenList": null,
                  "code": "11682",
                  "name": "珠晖区",
                  "pCode": "10201"
                },
                {
                  "childrenList": null,
                  "code": "11683",
                  "name": "石鼓区",
                  "pCode": "10201"
                },
                {
                  "childrenList": null,
                  "code": "11684",
                  "name": "蒸湘区",
                  "pCode": "10201"
                },
                {
                  "childrenList": null,
                  "code": "11685",
                  "name": "南岳区",
                  "pCode": "10201"
                },
                {
                  "childrenList": null,
                  "code": "11686",
                  "name": "耒阳市",
                  "pCode": "10201"
                },
                {
                  "childrenList": null,
                  "code": "11687",
                  "name": "常宁市",
                  "pCode": "10201"
                },
                {
                  "childrenList": null,
                  "code": "11688",
                  "name": "衡阳县",
                  "pCode": "10201"
                },
                {
                  "childrenList": null,
                  "code": "11689",
                  "name": "衡南县",
                  "pCode": "10201"
                },
                {
                  "childrenList": null,
                  "code": "11690",
                  "name": "衡山县",
                  "pCode": "10201"
                },
                {
                  "childrenList": null,
                  "code": "11691",
                  "name": "衡东县",
                  "pCode": "10201"
                },
                {
                  "childrenList": null,
                  "code": "11692",
                  "name": "祁东县",
                  "pCode": "10201"
                }
              ],
              "code": "10201",
              "name": "衡阳",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11693",
                  "name": "鹤城区",
                  "pCode": "10202"
                },
                {
                  "childrenList": null,
                  "code": "11694",
                  "name": "靖州",
                  "pCode": "10202"
                },
                {
                  "childrenList": null,
                  "code": "11695",
                  "name": "麻阳",
                  "pCode": "10202"
                },
                {
                  "childrenList": null,
                  "code": "11696",
                  "name": "通道",
                  "pCode": "10202"
                },
                {
                  "childrenList": null,
                  "code": "11697",
                  "name": "新晃",
                  "pCode": "10202"
                },
                {
                  "childrenList": null,
                  "code": "11698",
                  "name": "芷江",
                  "pCode": "10202"
                },
                {
                  "childrenList": null,
                  "code": "11699",
                  "name": "沅陵县",
                  "pCode": "10202"
                },
                {
                  "childrenList": null,
                  "code": "11700",
                  "name": "辰溪县",
                  "pCode": "10202"
                },
                {
                  "childrenList": null,
                  "code": "11701",
                  "name": "溆浦县",
                  "pCode": "10202"
                },
                {
                  "childrenList": null,
                  "code": "11702",
                  "name": "中方县",
                  "pCode": "10202"
                },
                {
                  "childrenList": null,
                  "code": "11703",
                  "name": "会同县",
                  "pCode": "10202"
                },
                {
                  "childrenList": null,
                  "code": "11704",
                  "name": "洪江市",
                  "pCode": "10202"
                }
              ],
              "code": "10202",
              "name": "怀化",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11705",
                  "name": "娄星区",
                  "pCode": "10203"
                },
                {
                  "childrenList": null,
                  "code": "11706",
                  "name": "冷水江市",
                  "pCode": "10203"
                },
                {
                  "childrenList": null,
                  "code": "11707",
                  "name": "涟源市",
                  "pCode": "10203"
                },
                {
                  "childrenList": null,
                  "code": "11708",
                  "name": "双峰县",
                  "pCode": "10203"
                },
                {
                  "childrenList": null,
                  "code": "11709",
                  "name": "新化县",
                  "pCode": "10203"
                }
              ],
              "code": "10203",
              "name": "娄底",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11710",
                  "name": "城步",
                  "pCode": "10204"
                },
                {
                  "childrenList": null,
                  "code": "11711",
                  "name": "双清区",
                  "pCode": "10204"
                },
                {
                  "childrenList": null,
                  "code": "11712",
                  "name": "大祥区",
                  "pCode": "10204"
                },
                {
                  "childrenList": null,
                  "code": "11713",
                  "name": "北塔区",
                  "pCode": "10204"
                },
                {
                  "childrenList": null,
                  "code": "11714",
                  "name": "武冈市",
                  "pCode": "10204"
                },
                {
                  "childrenList": null,
                  "code": "11715",
                  "name": "邵东县",
                  "pCode": "10204"
                },
                {
                  "childrenList": null,
                  "code": "11716",
                  "name": "新邵县",
                  "pCode": "10204"
                },
                {
                  "childrenList": null,
                  "code": "11717",
                  "name": "邵阳县",
                  "pCode": "10204"
                },
                {
                  "childrenList": null,
                  "code": "11718",
                  "name": "隆回县",
                  "pCode": "10204"
                },
                {
                  "childrenList": null,
                  "code": "11719",
                  "name": "洞口县",
                  "pCode": "10204"
                },
                {
                  "childrenList": null,
                  "code": "11720",
                  "name": "绥宁县",
                  "pCode": "10204"
                },
                {
                  "childrenList": null,
                  "code": "11721",
                  "name": "新宁县",
                  "pCode": "10204"
                }
              ],
              "code": "10204",
              "name": "邵阳",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11722",
                  "name": "岳塘区",
                  "pCode": "10205"
                },
                {
                  "childrenList": null,
                  "code": "11723",
                  "name": "雨湖区",
                  "pCode": "10205"
                },
                {
                  "childrenList": null,
                  "code": "11724",
                  "name": "湘乡市",
                  "pCode": "10205"
                },
                {
                  "childrenList": null,
                  "code": "11725",
                  "name": "韶山市",
                  "pCode": "10205"
                },
                {
                  "childrenList": null,
                  "code": "11726",
                  "name": "湘潭县",
                  "pCode": "10205"
                }
              ],
              "code": "10205",
              "name": "湘潭",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11727",
                  "name": "吉首市",
                  "pCode": "10206"
                },
                {
                  "childrenList": null,
                  "code": "11728",
                  "name": "泸溪县",
                  "pCode": "10206"
                },
                {
                  "childrenList": null,
                  "code": "11729",
                  "name": "凤凰县",
                  "pCode": "10206"
                },
                {
                  "childrenList": null,
                  "code": "11730",
                  "name": "花垣县",
                  "pCode": "10206"
                },
                {
                  "childrenList": null,
                  "code": "11731",
                  "name": "保靖县",
                  "pCode": "10206"
                },
                {
                  "childrenList": null,
                  "code": "11732",
                  "name": "古丈县",
                  "pCode": "10206"
                },
                {
                  "childrenList": null,
                  "code": "11733",
                  "name": "永顺县",
                  "pCode": "10206"
                },
                {
                  "childrenList": null,
                  "code": "11734",
                  "name": "龙山县",
                  "pCode": "10206"
                }
              ],
              "code": "10206",
              "name": "湘西",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11735",
                  "name": "赫山区",
                  "pCode": "10207"
                },
                {
                  "childrenList": null,
                  "code": "11736",
                  "name": "资阳区",
                  "pCode": "10207"
                },
                {
                  "childrenList": null,
                  "code": "11737",
                  "name": "沅江市",
                  "pCode": "10207"
                },
                {
                  "childrenList": null,
                  "code": "11738",
                  "name": "南县",
                  "pCode": "10207"
                },
                {
                  "childrenList": null,
                  "code": "11739",
                  "name": "桃江县",
                  "pCode": "10207"
                },
                {
                  "childrenList": null,
                  "code": "11740",
                  "name": "安化县",
                  "pCode": "10207"
                }
              ],
              "code": "10207",
              "name": "益阳",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11741",
                  "name": "江华",
                  "pCode": "10208"
                },
                {
                  "childrenList": null,
                  "code": "11742",
                  "name": "冷水滩区",
                  "pCode": "10208"
                },
                {
                  "childrenList": null,
                  "code": "11743",
                  "name": "零陵区",
                  "pCode": "10208"
                },
                {
                  "childrenList": null,
                  "code": "11744",
                  "name": "祁阳县",
                  "pCode": "10208"
                },
                {
                  "childrenList": null,
                  "code": "11745",
                  "name": "东安县",
                  "pCode": "10208"
                },
                {
                  "childrenList": null,
                  "code": "11746",
                  "name": "双牌县",
                  "pCode": "10208"
                },
                {
                  "childrenList": null,
                  "code": "11747",
                  "name": "道县",
                  "pCode": "10208"
                },
                {
                  "childrenList": null,
                  "code": "11748",
                  "name": "江永县",
                  "pCode": "10208"
                },
                {
                  "childrenList": null,
                  "code": "11749",
                  "name": "宁远县",
                  "pCode": "10208"
                },
                {
                  "childrenList": null,
                  "code": "11750",
                  "name": "蓝山县",
                  "pCode": "10208"
                },
                {
                  "childrenList": null,
                  "code": "11751",
                  "name": "新田县",
                  "pCode": "10208"
                }
              ],
              "code": "10208",
              "name": "永州",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11752",
                  "name": "岳阳楼区",
                  "pCode": "10209"
                },
                {
                  "childrenList": null,
                  "code": "11753",
                  "name": "君山区",
                  "pCode": "10209"
                },
                {
                  "childrenList": null,
                  "code": "11754",
                  "name": "云溪区",
                  "pCode": "10209"
                },
                {
                  "childrenList": null,
                  "code": "11755",
                  "name": "汨罗市",
                  "pCode": "10209"
                },
                {
                  "childrenList": null,
                  "code": "11756",
                  "name": "临湘市",
                  "pCode": "10209"
                },
                {
                  "childrenList": null,
                  "code": "11757",
                  "name": "岳阳县",
                  "pCode": "10209"
                },
                {
                  "childrenList": null,
                  "code": "11758",
                  "name": "华容县",
                  "pCode": "10209"
                },
                {
                  "childrenList": null,
                  "code": "11759",
                  "name": "湘阴县",
                  "pCode": "10209"
                },
                {
                  "childrenList": null,
                  "code": "11760",
                  "name": "平江县",
                  "pCode": "10209"
                }
              ],
              "code": "10209",
              "name": "岳阳",
              "pCode": "10014"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11761",
                  "name": "天元区",
                  "pCode": "10210"
                },
                {
                  "childrenList": null,
                  "code": "11762",
                  "name": "荷塘区",
                  "pCode": "10210"
                },
                {
                  "childrenList": null,
                  "code": "11763",
                  "name": "芦淞区",
                  "pCode": "10210"
                },
                {
                  "childrenList": null,
                  "code": "11764",
                  "name": "石峰区",
                  "pCode": "10210"
                },
                {
                  "childrenList": null,
                  "code": "11765",
                  "name": "醴陵市",
                  "pCode": "10210"
                },
                {
                  "childrenList": null,
                  "code": "11766",
                  "name": "株洲县",
                  "pCode": "10210"
                },
                {
                  "childrenList": null,
                  "code": "11767",
                  "name": "攸县",
                  "pCode": "10210"
                },
                {
                  "childrenList": null,
                  "code": "11768",
                  "name": "茶陵县",
                  "pCode": "10210"
                },
                {
                  "childrenList": null,
                  "code": "11769",
                  "name": "炎陵县",
                  "pCode": "10210"
                }
              ],
              "code": "10210",
              "name": "株洲",
              "pCode": "10014"
            }
          ],
          "code": "10014",
          "name": "湖南省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11770",
                  "name": "朝阳区",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11771",
                  "name": "宽城区",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11772",
                  "name": "二道区",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11773",
                  "name": "南关区",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11774",
                  "name": "绿园区",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11775",
                  "name": "双阳区",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11776",
                  "name": "净月潭开发区",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11777",
                  "name": "高新技术开发区",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11778",
                  "name": "经济技术开发区",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11779",
                  "name": "汽车产业开发区",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11780",
                  "name": "德惠市",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11781",
                  "name": "九台市",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11782",
                  "name": "榆树市",
                  "pCode": "10211"
                },
                {
                  "childrenList": null,
                  "code": "11783",
                  "name": "农安县",
                  "pCode": "10211"
                }
              ],
              "code": "10211",
              "name": "长春",
              "pCode": "10015"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11784",
                  "name": "船营区",
                  "pCode": "10212"
                },
                {
                  "childrenList": null,
                  "code": "11785",
                  "name": "昌邑区",
                  "pCode": "10212"
                },
                {
                  "childrenList": null,
                  "code": "11786",
                  "name": "龙潭区",
                  "pCode": "10212"
                },
                {
                  "childrenList": null,
                  "code": "11787",
                  "name": "丰满区",
                  "pCode": "10212"
                },
                {
                  "childrenList": null,
                  "code": "11788",
                  "name": "蛟河市",
                  "pCode": "10212"
                },
                {
                  "childrenList": null,
                  "code": "11789",
                  "name": "桦甸市",
                  "pCode": "10212"
                },
                {
                  "childrenList": null,
                  "code": "11790",
                  "name": "舒兰市",
                  "pCode": "10212"
                },
                {
                  "childrenList": null,
                  "code": "11791",
                  "name": "磐石市",
                  "pCode": "10212"
                },
                {
                  "childrenList": null,
                  "code": "11792",
                  "name": "永吉县",
                  "pCode": "10212"
                }
              ],
              "code": "10212",
              "name": "吉林",
              "pCode": "10015"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11793",
                  "name": "洮北区",
                  "pCode": "10213"
                },
                {
                  "childrenList": null,
                  "code": "11794",
                  "name": "洮南市",
                  "pCode": "10213"
                },
                {
                  "childrenList": null,
                  "code": "11795",
                  "name": "大安市",
                  "pCode": "10213"
                },
                {
                  "childrenList": null,
                  "code": "11796",
                  "name": "镇赉县",
                  "pCode": "10213"
                },
                {
                  "childrenList": null,
                  "code": "11797",
                  "name": "通榆县",
                  "pCode": "10213"
                }
              ],
              "code": "10213",
              "name": "白城",
              "pCode": "10015"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11798",
                  "name": "江源区",
                  "pCode": "10214"
                },
                {
                  "childrenList": null,
                  "code": "11799",
                  "name": "八道江区",
                  "pCode": "10214"
                },
                {
                  "childrenList": null,
                  "code": "11800",
                  "name": "长白",
                  "pCode": "10214"
                },
                {
                  "childrenList": null,
                  "code": "11801",
                  "name": "临江市",
                  "pCode": "10214"
                },
                {
                  "childrenList": null,
                  "code": "11802",
                  "name": "抚松县",
                  "pCode": "10214"
                },
                {
                  "childrenList": null,
                  "code": "11803",
                  "name": "靖宇县",
                  "pCode": "10214"
                }
              ],
              "code": "10214",
              "name": "白山",
              "pCode": "10015"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11804",
                  "name": "龙山区",
                  "pCode": "10215"
                },
                {
                  "childrenList": null,
                  "code": "11805",
                  "name": "西安区",
                  "pCode": "10215"
                },
                {
                  "childrenList": null,
                  "code": "11806",
                  "name": "东丰县",
                  "pCode": "10215"
                },
                {
                  "childrenList": null,
                  "code": "11807",
                  "name": "东辽县",
                  "pCode": "10215"
                }
              ],
              "code": "10215",
              "name": "辽源",
              "pCode": "10015"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11808",
                  "name": "铁西区",
                  "pCode": "10216"
                },
                {
                  "childrenList": null,
                  "code": "11809",
                  "name": "铁东区",
                  "pCode": "10216"
                },
                {
                  "childrenList": null,
                  "code": "11810",
                  "name": "伊通",
                  "pCode": "10216"
                },
                {
                  "childrenList": null,
                  "code": "11811",
                  "name": "公主岭市",
                  "pCode": "10216"
                },
                {
                  "childrenList": null,
                  "code": "11812",
                  "name": "双辽市",
                  "pCode": "10216"
                },
                {
                  "childrenList": null,
                  "code": "11813",
                  "name": "梨树县",
                  "pCode": "10216"
                }
              ],
              "code": "10216",
              "name": "四平",
              "pCode": "10015"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11814",
                  "name": "前郭尔罗斯",
                  "pCode": "10217"
                },
                {
                  "childrenList": null,
                  "code": "11815",
                  "name": "宁江区",
                  "pCode": "10217"
                },
                {
                  "childrenList": null,
                  "code": "11816",
                  "name": "长岭县",
                  "pCode": "10217"
                },
                {
                  "childrenList": null,
                  "code": "11817",
                  "name": "乾安县",
                  "pCode": "10217"
                },
                {
                  "childrenList": null,
                  "code": "11818",
                  "name": "扶余县",
                  "pCode": "10217"
                }
              ],
              "code": "10217",
              "name": "松原",
              "pCode": "10015"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11819",
                  "name": "东昌区",
                  "pCode": "10218"
                },
                {
                  "childrenList": null,
                  "code": "11820",
                  "name": "二道江区",
                  "pCode": "10218"
                },
                {
                  "childrenList": null,
                  "code": "11821",
                  "name": "梅河口市",
                  "pCode": "10218"
                },
                {
                  "childrenList": null,
                  "code": "11822",
                  "name": "集安市",
                  "pCode": "10218"
                },
                {
                  "childrenList": null,
                  "code": "11823",
                  "name": "通化县",
                  "pCode": "10218"
                },
                {
                  "childrenList": null,
                  "code": "11824",
                  "name": "辉南县",
                  "pCode": "10218"
                },
                {
                  "childrenList": null,
                  "code": "11825",
                  "name": "柳河县",
                  "pCode": "10218"
                }
              ],
              "code": "10218",
              "name": "通化",
              "pCode": "10015"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11826",
                  "name": "延吉市",
                  "pCode": "10219"
                },
                {
                  "childrenList": null,
                  "code": "11827",
                  "name": "图们市",
                  "pCode": "10219"
                },
                {
                  "childrenList": null,
                  "code": "11828",
                  "name": "敦化市",
                  "pCode": "10219"
                },
                {
                  "childrenList": null,
                  "code": "11829",
                  "name": "珲春市",
                  "pCode": "10219"
                },
                {
                  "childrenList": null,
                  "code": "11830",
                  "name": "龙井市",
                  "pCode": "10219"
                },
                {
                  "childrenList": null,
                  "code": "11831",
                  "name": "和龙市",
                  "pCode": "10219"
                },
                {
                  "childrenList": null,
                  "code": "11832",
                  "name": "安图县",
                  "pCode": "10219"
                },
                {
                  "childrenList": null,
                  "code": "11833",
                  "name": "汪清县",
                  "pCode": "10219"
                }
              ],
              "code": "10219",
              "name": "延边",
              "pCode": "10015"
            }
          ],
          "code": "10015",
          "name": "吉林省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11834",
                  "name": "玄武区",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11835",
                  "name": "鼓楼区",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11836",
                  "name": "白下区",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11837",
                  "name": "建邺区",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11838",
                  "name": "秦淮区",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11839",
                  "name": "雨花台区",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11840",
                  "name": "下关区",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11841",
                  "name": "栖霞区",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11842",
                  "name": "浦口区",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11843",
                  "name": "江宁区",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11844",
                  "name": "六合区",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11845",
                  "name": "溧水县",
                  "pCode": "10220"
                },
                {
                  "childrenList": null,
                  "code": "11846",
                  "name": "高淳县",
                  "pCode": "10220"
                }
              ],
              "code": "10220",
              "name": "南京",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11847",
                  "name": "沧浪区",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11848",
                  "name": "金阊区",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11849",
                  "name": "平江区",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11850",
                  "name": "虎丘区",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11851",
                  "name": "吴中区",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11852",
                  "name": "相城区",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11853",
                  "name": "园区",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11854",
                  "name": "新区",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11855",
                  "name": "常熟市",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11856",
                  "name": "张家港市",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11857",
                  "name": "玉山镇",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11858",
                  "name": "巴城镇",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11859",
                  "name": "周市镇",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11860",
                  "name": "陆家镇",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11861",
                  "name": "花桥镇",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11862",
                  "name": "淀山湖镇",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11863",
                  "name": "张浦镇",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11864",
                  "name": "周庄镇",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11865",
                  "name": "千灯镇",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11866",
                  "name": "锦溪镇",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11867",
                  "name": "开发区",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11868",
                  "name": "吴江市",
                  "pCode": "10221"
                },
                {
                  "childrenList": null,
                  "code": "11869",
                  "name": "太仓市",
                  "pCode": "10221"
                }
              ],
              "code": "10221",
              "name": "苏州",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11870",
                  "name": "崇安区",
                  "pCode": "10222"
                },
                {
                  "childrenList": null,
                  "code": "11871",
                  "name": "北塘区",
                  "pCode": "10222"
                },
                {
                  "childrenList": null,
                  "code": "11872",
                  "name": "南长区",
                  "pCode": "10222"
                },
                {
                  "childrenList": null,
                  "code": "11873",
                  "name": "锡山区",
                  "pCode": "10222"
                },
                {
                  "childrenList": null,
                  "code": "11874",
                  "name": "惠山区",
                  "pCode": "10222"
                },
                {
                  "childrenList": null,
                  "code": "11875",
                  "name": "滨湖区",
                  "pCode": "10222"
                },
                {
                  "childrenList": null,
                  "code": "11876",
                  "name": "新区",
                  "pCode": "10222"
                },
                {
                  "childrenList": null,
                  "code": "11877",
                  "name": "江阴市",
                  "pCode": "10222"
                },
                {
                  "childrenList": null,
                  "code": "11878",
                  "name": "宜兴市",
                  "pCode": "10222"
                }
              ],
              "code": "10222",
              "name": "无锡",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11879",
                  "name": "天宁区",
                  "pCode": "10223"
                },
                {
                  "childrenList": null,
                  "code": "11880",
                  "name": "钟楼区",
                  "pCode": "10223"
                },
                {
                  "childrenList": null,
                  "code": "11881",
                  "name": "戚墅堰区",
                  "pCode": "10223"
                },
                {
                  "childrenList": null,
                  "code": "11882",
                  "name": "郊区",
                  "pCode": "10223"
                },
                {
                  "childrenList": null,
                  "code": "11883",
                  "name": "新北区",
                  "pCode": "10223"
                },
                {
                  "childrenList": null,
                  "code": "11884",
                  "name": "武进区",
                  "pCode": "10223"
                },
                {
                  "childrenList": null,
                  "code": "11885",
                  "name": "溧阳市",
                  "pCode": "10223"
                },
                {
                  "childrenList": null,
                  "code": "11886",
                  "name": "金坛市",
                  "pCode": "10223"
                }
              ],
              "code": "10223",
              "name": "常州",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11887",
                  "name": "清河区",
                  "pCode": "10224"
                },
                {
                  "childrenList": null,
                  "code": "11888",
                  "name": "清浦区",
                  "pCode": "10224"
                },
                {
                  "childrenList": null,
                  "code": "11889",
                  "name": "楚州区",
                  "pCode": "10224"
                },
                {
                  "childrenList": null,
                  "code": "11890",
                  "name": "淮阴区",
                  "pCode": "10224"
                },
                {
                  "childrenList": null,
                  "code": "11891",
                  "name": "涟水县",
                  "pCode": "10224"
                },
                {
                  "childrenList": null,
                  "code": "11892",
                  "name": "洪泽县",
                  "pCode": "10224"
                },
                {
                  "childrenList": null,
                  "code": "11893",
                  "name": "盱眙县",
                  "pCode": "10224"
                },
                {
                  "childrenList": null,
                  "code": "11894",
                  "name": "金湖县",
                  "pCode": "10224"
                }
              ],
              "code": "10224",
              "name": "淮安",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11895",
                  "name": "新浦区",
                  "pCode": "10225"
                },
                {
                  "childrenList": null,
                  "code": "11896",
                  "name": "连云区",
                  "pCode": "10225"
                },
                {
                  "childrenList": null,
                  "code": "11897",
                  "name": "海州区",
                  "pCode": "10225"
                },
                {
                  "childrenList": null,
                  "code": "11898",
                  "name": "赣榆县",
                  "pCode": "10225"
                },
                {
                  "childrenList": null,
                  "code": "11899",
                  "name": "东海县",
                  "pCode": "10225"
                },
                {
                  "childrenList": null,
                  "code": "11900",
                  "name": "灌云县",
                  "pCode": "10225"
                },
                {
                  "childrenList": null,
                  "code": "11901",
                  "name": "灌南县",
                  "pCode": "10225"
                }
              ],
              "code": "10225",
              "name": "连云港",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11902",
                  "name": "崇川区",
                  "pCode": "10226"
                },
                {
                  "childrenList": null,
                  "code": "11903",
                  "name": "港闸区",
                  "pCode": "10226"
                },
                {
                  "childrenList": null,
                  "code": "11904",
                  "name": "经济开发区",
                  "pCode": "10226"
                },
                {
                  "childrenList": null,
                  "code": "11905",
                  "name": "启东市",
                  "pCode": "10226"
                },
                {
                  "childrenList": null,
                  "code": "11906",
                  "name": "如皋市",
                  "pCode": "10226"
                },
                {
                  "childrenList": null,
                  "code": "11907",
                  "name": "通州市",
                  "pCode": "10226"
                },
                {
                  "childrenList": null,
                  "code": "11908",
                  "name": "海门市",
                  "pCode": "10226"
                },
                {
                  "childrenList": null,
                  "code": "11909",
                  "name": "海安县",
                  "pCode": "10226"
                },
                {
                  "childrenList": null,
                  "code": "11910",
                  "name": "如东县",
                  "pCode": "10226"
                }
              ],
              "code": "10226",
              "name": "南通",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11911",
                  "name": "宿城区",
                  "pCode": "10227"
                },
                {
                  "childrenList": null,
                  "code": "11912",
                  "name": "宿豫区",
                  "pCode": "10227"
                },
                {
                  "childrenList": null,
                  "code": "11913",
                  "name": "宿豫县",
                  "pCode": "10227"
                },
                {
                  "childrenList": null,
                  "code": "11914",
                  "name": "沭阳县",
                  "pCode": "10227"
                },
                {
                  "childrenList": null,
                  "code": "11915",
                  "name": "泗阳县",
                  "pCode": "10227"
                },
                {
                  "childrenList": null,
                  "code": "11916",
                  "name": "泗洪县",
                  "pCode": "10227"
                }
              ],
              "code": "10227",
              "name": "宿迁",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11917",
                  "name": "海陵区",
                  "pCode": "10228"
                },
                {
                  "childrenList": null,
                  "code": "11918",
                  "name": "高港区",
                  "pCode": "10228"
                },
                {
                  "childrenList": null,
                  "code": "11919",
                  "name": "兴化市",
                  "pCode": "10228"
                },
                {
                  "childrenList": null,
                  "code": "11920",
                  "name": "靖江市",
                  "pCode": "10228"
                },
                {
                  "childrenList": null,
                  "code": "11921",
                  "name": "泰兴市",
                  "pCode": "10228"
                },
                {
                  "childrenList": null,
                  "code": "11922",
                  "name": "姜堰市",
                  "pCode": "10228"
                }
              ],
              "code": "10228",
              "name": "泰州",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11923",
                  "name": "云龙区",
                  "pCode": "10229"
                },
                {
                  "childrenList": null,
                  "code": "11924",
                  "name": "鼓楼区",
                  "pCode": "10229"
                },
                {
                  "childrenList": null,
                  "code": "11925",
                  "name": "九里区",
                  "pCode": "10229"
                },
                {
                  "childrenList": null,
                  "code": "11926",
                  "name": "贾汪区",
                  "pCode": "10229"
                },
                {
                  "childrenList": null,
                  "code": "11927",
                  "name": "泉山区",
                  "pCode": "10229"
                },
                {
                  "childrenList": null,
                  "code": "11928",
                  "name": "新沂市",
                  "pCode": "10229"
                },
                {
                  "childrenList": null,
                  "code": "11929",
                  "name": "邳州市",
                  "pCode": "10229"
                },
                {
                  "childrenList": null,
                  "code": "11930",
                  "name": "丰县",
                  "pCode": "10229"
                },
                {
                  "childrenList": null,
                  "code": "11931",
                  "name": "沛县",
                  "pCode": "10229"
                },
                {
                  "childrenList": null,
                  "code": "11932",
                  "name": "铜山县",
                  "pCode": "10229"
                },
                {
                  "childrenList": null,
                  "code": "11933",
                  "name": "睢宁县",
                  "pCode": "10229"
                }
              ],
              "code": "10229",
              "name": "徐州",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11934",
                  "name": "城区",
                  "pCode": "10230"
                },
                {
                  "childrenList": null,
                  "code": "11935",
                  "name": "亭湖区",
                  "pCode": "10230"
                },
                {
                  "childrenList": null,
                  "code": "11936",
                  "name": "盐都区",
                  "pCode": "10230"
                },
                {
                  "childrenList": null,
                  "code": "11937",
                  "name": "盐都县",
                  "pCode": "10230"
                },
                {
                  "childrenList": null,
                  "code": "11938",
                  "name": "东台市",
                  "pCode": "10230"
                },
                {
                  "childrenList": null,
                  "code": "11939",
                  "name": "大丰市",
                  "pCode": "10230"
                },
                {
                  "childrenList": null,
                  "code": "11940",
                  "name": "响水县",
                  "pCode": "10230"
                },
                {
                  "childrenList": null,
                  "code": "11941",
                  "name": "滨海县",
                  "pCode": "10230"
                },
                {
                  "childrenList": null,
                  "code": "11942",
                  "name": "阜宁县",
                  "pCode": "10230"
                },
                {
                  "childrenList": null,
                  "code": "11943",
                  "name": "射阳县",
                  "pCode": "10230"
                },
                {
                  "childrenList": null,
                  "code": "11944",
                  "name": "建湖县",
                  "pCode": "10230"
                }
              ],
              "code": "10230",
              "name": "盐城",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11945",
                  "name": "广陵区",
                  "pCode": "10231"
                },
                {
                  "childrenList": null,
                  "code": "11946",
                  "name": "维扬区",
                  "pCode": "10231"
                },
                {
                  "childrenList": null,
                  "code": "11947",
                  "name": "邗江区",
                  "pCode": "10231"
                },
                {
                  "childrenList": null,
                  "code": "11948",
                  "name": "仪征市",
                  "pCode": "10231"
                },
                {
                  "childrenList": null,
                  "code": "11949",
                  "name": "高邮市",
                  "pCode": "10231"
                },
                {
                  "childrenList": null,
                  "code": "11950",
                  "name": "江都市",
                  "pCode": "10231"
                },
                {
                  "childrenList": null,
                  "code": "11951",
                  "name": "宝应县",
                  "pCode": "10231"
                }
              ],
              "code": "10231",
              "name": "扬州",
              "pCode": "10016"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11952",
                  "name": "京口区",
                  "pCode": "10232"
                },
                {
                  "childrenList": null,
                  "code": "11953",
                  "name": "润州区",
                  "pCode": "10232"
                },
                {
                  "childrenList": null,
                  "code": "11954",
                  "name": "丹徒区",
                  "pCode": "10232"
                },
                {
                  "childrenList": null,
                  "code": "11955",
                  "name": "丹阳市",
                  "pCode": "10232"
                },
                {
                  "childrenList": null,
                  "code": "11956",
                  "name": "扬中市",
                  "pCode": "10232"
                },
                {
                  "childrenList": null,
                  "code": "11957",
                  "name": "句容市",
                  "pCode": "10232"
                }
              ],
              "code": "10232",
              "name": "镇江",
              "pCode": "10016"
            }
          ],
          "code": "10016",
          "name": "江苏省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11958",
                  "name": "东湖区",
                  "pCode": "10233"
                },
                {
                  "childrenList": null,
                  "code": "11959",
                  "name": "西湖区",
                  "pCode": "10233"
                },
                {
                  "childrenList": null,
                  "code": "11960",
                  "name": "青云谱区",
                  "pCode": "10233"
                },
                {
                  "childrenList": null,
                  "code": "11961",
                  "name": "湾里区",
                  "pCode": "10233"
                },
                {
                  "childrenList": null,
                  "code": "11962",
                  "name": "青山湖区",
                  "pCode": "10233"
                },
                {
                  "childrenList": null,
                  "code": "11963",
                  "name": "红谷滩新区",
                  "pCode": "10233"
                },
                {
                  "childrenList": null,
                  "code": "11964",
                  "name": "昌北区",
                  "pCode": "10233"
                },
                {
                  "childrenList": null,
                  "code": "11965",
                  "name": "高新区",
                  "pCode": "10233"
                },
                {
                  "childrenList": null,
                  "code": "11966",
                  "name": "南昌县",
                  "pCode": "10233"
                },
                {
                  "childrenList": null,
                  "code": "11967",
                  "name": "新建县",
                  "pCode": "10233"
                },
                {
                  "childrenList": null,
                  "code": "11968",
                  "name": "安义县",
                  "pCode": "10233"
                },
                {
                  "childrenList": null,
                  "code": "11969",
                  "name": "进贤县",
                  "pCode": "10233"
                }
              ],
              "code": "10233",
              "name": "南昌",
              "pCode": "10017"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11970",
                  "name": "临川区",
                  "pCode": "10234"
                },
                {
                  "childrenList": null,
                  "code": "11971",
                  "name": "南城县",
                  "pCode": "10234"
                },
                {
                  "childrenList": null,
                  "code": "11972",
                  "name": "黎川县",
                  "pCode": "10234"
                },
                {
                  "childrenList": null,
                  "code": "11973",
                  "name": "南丰县",
                  "pCode": "10234"
                },
                {
                  "childrenList": null,
                  "code": "11974",
                  "name": "崇仁县",
                  "pCode": "10234"
                },
                {
                  "childrenList": null,
                  "code": "11975",
                  "name": "乐安县",
                  "pCode": "10234"
                },
                {
                  "childrenList": null,
                  "code": "11976",
                  "name": "宜黄县",
                  "pCode": "10234"
                },
                {
                  "childrenList": null,
                  "code": "11977",
                  "name": "金溪县",
                  "pCode": "10234"
                },
                {
                  "childrenList": null,
                  "code": "11978",
                  "name": "资溪县",
                  "pCode": "10234"
                },
                {
                  "childrenList": null,
                  "code": "11979",
                  "name": "东乡县",
                  "pCode": "10234"
                },
                {
                  "childrenList": null,
                  "code": "11980",
                  "name": "广昌县",
                  "pCode": "10234"
                }
              ],
              "code": "10234",
              "name": "抚州",
              "pCode": "10017"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11981",
                  "name": "章贡区",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11982",
                  "name": "于都县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11983",
                  "name": "瑞金市",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11984",
                  "name": "南康市",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11985",
                  "name": "赣县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11986",
                  "name": "信丰县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11987",
                  "name": "大余县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11988",
                  "name": "上犹县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11989",
                  "name": "崇义县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11990",
                  "name": "安远县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11991",
                  "name": "龙南县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11992",
                  "name": "定南县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11993",
                  "name": "全南县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11994",
                  "name": "宁都县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11995",
                  "name": "兴国县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11996",
                  "name": "会昌县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11997",
                  "name": "寻乌县",
                  "pCode": "10235"
                },
                {
                  "childrenList": null,
                  "code": "11998",
                  "name": "石城县",
                  "pCode": "10235"
                }
              ],
              "code": "10235",
              "name": "赣州",
              "pCode": "10017"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "11999",
                  "name": "安福县",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12000",
                  "name": "吉州区",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12001",
                  "name": "青原区",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12002",
                  "name": "井冈山市",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12003",
                  "name": "吉安县",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12004",
                  "name": "吉水县",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12005",
                  "name": "峡江县",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12006",
                  "name": "新干县",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12007",
                  "name": "永丰县",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12008",
                  "name": "泰和县",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12009",
                  "name": "遂川县",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12010",
                  "name": "万安县",
                  "pCode": "10236"
                },
                {
                  "childrenList": null,
                  "code": "12011",
                  "name": "永新县",
                  "pCode": "10236"
                }
              ],
              "code": "10236",
              "name": "吉安",
              "pCode": "10017"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12012",
                  "name": "珠山区",
                  "pCode": "10237"
                },
                {
                  "childrenList": null,
                  "code": "12013",
                  "name": "昌江区",
                  "pCode": "10237"
                },
                {
                  "childrenList": null,
                  "code": "12014",
                  "name": "乐平市",
                  "pCode": "10237"
                },
                {
                  "childrenList": null,
                  "code": "12015",
                  "name": "浮梁县",
                  "pCode": "10237"
                }
              ],
              "code": "10237",
              "name": "景德镇",
              "pCode": "10017"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12016",
                  "name": "浔阳区",
                  "pCode": "10238"
                },
                {
                  "childrenList": null,
                  "code": "12017",
                  "name": "庐山区",
                  "pCode": "10238"
                },
                {
                  "childrenList": null,
                  "code": "12018",
                  "name": "瑞昌市",
                  "pCode": "10238"
                },
                {
                  "childrenList": null,
                  "code": "12019",
                  "name": "九江县",
                  "pCode": "10238"
                },
                {
                  "childrenList": null,
                  "code": "12020",
                  "name": "武宁县",
                  "pCode": "10238"
                },
                {
                  "childrenList": null,
                  "code": "12021",
                  "name": "修水县",
                  "pCode": "10238"
                },
                {
                  "childrenList": null,
                  "code": "12022",
                  "name": "永修县",
                  "pCode": "10238"
                },
                {
                  "childrenList": null,
                  "code": "12023",
                  "name": "德安县",
                  "pCode": "10238"
                },
                {
                  "childrenList": null,
                  "code": "12024",
                  "name": "星子县",
                  "pCode": "10238"
                },
                {
                  "childrenList": null,
                  "code": "12025",
                  "name": "都昌县",
                  "pCode": "10238"
                },
                {
                  "childrenList": null,
                  "code": "12026",
                  "name": "湖口县",
                  "pCode": "10238"
                },
                {
                  "childrenList": null,
                  "code": "12027",
                  "name": "彭泽县",
                  "pCode": "10238"
                }
              ],
              "code": "10238",
              "name": "九江",
              "pCode": "10017"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12028",
                  "name": "安源区",
                  "pCode": "10239"
                },
                {
                  "childrenList": null,
                  "code": "12029",
                  "name": "湘东区",
                  "pCode": "10239"
                },
                {
                  "childrenList": null,
                  "code": "12030",
                  "name": "莲花县",
                  "pCode": "10239"
                },
                {
                  "childrenList": null,
                  "code": "12031",
                  "name": "芦溪县",
                  "pCode": "10239"
                },
                {
                  "childrenList": null,
                  "code": "12032",
                  "name": "上栗县",
                  "pCode": "10239"
                }
              ],
              "code": "10239",
              "name": "萍乡",
              "pCode": "10017"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12033",
                  "name": "信州区",
                  "pCode": "10240"
                },
                {
                  "childrenList": null,
                  "code": "12034",
                  "name": "德兴市",
                  "pCode": "10240"
                },
                {
                  "childrenList": null,
                  "code": "12035",
                  "name": "上饶县",
                  "pCode": "10240"
                },
                {
                  "childrenList": null,
                  "code": "12036",
                  "name": "广丰县",
                  "pCode": "10240"
                },
                {
                  "childrenList": null,
                  "code": "12037",
                  "name": "玉山县",
                  "pCode": "10240"
                },
                {
                  "childrenList": null,
                  "code": "12038",
                  "name": "铅山县",
                  "pCode": "10240"
                },
                {
                  "childrenList": null,
                  "code": "12039",
                  "name": "横峰县",
                  "pCode": "10240"
                },
                {
                  "childrenList": null,
                  "code": "12040",
                  "name": "弋阳县",
                  "pCode": "10240"
                },
                {
                  "childrenList": null,
                  "code": "12041",
                  "name": "余干县",
                  "pCode": "10240"
                },
                {
                  "childrenList": null,
                  "code": "12042",
                  "name": "波阳县",
                  "pCode": "10240"
                },
                {
                  "childrenList": null,
                  "code": "12043",
                  "name": "万年县",
                  "pCode": "10240"
                },
                {
                  "childrenList": null,
                  "code": "12044",
                  "name": "婺源县",
                  "pCode": "10240"
                }
              ],
              "code": "10240",
              "name": "上饶",
              "pCode": "10017"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12045",
                  "name": "渝水区",
                  "pCode": "10241"
                },
                {
                  "childrenList": null,
                  "code": "12046",
                  "name": "分宜县",
                  "pCode": "10241"
                }
              ],
              "code": "10241",
              "name": "新余",
              "pCode": "10017"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12047",
                  "name": "袁州区",
                  "pCode": "10242"
                },
                {
                  "childrenList": null,
                  "code": "12048",
                  "name": "丰城市",
                  "pCode": "10242"
                },
                {
                  "childrenList": null,
                  "code": "12049",
                  "name": "樟树市",
                  "pCode": "10242"
                },
                {
                  "childrenList": null,
                  "code": "12050",
                  "name": "高安市",
                  "pCode": "10242"
                },
                {
                  "childrenList": null,
                  "code": "12051",
                  "name": "奉新县",
                  "pCode": "10242"
                },
                {
                  "childrenList": null,
                  "code": "12052",
                  "name": "万载县",
                  "pCode": "10242"
                },
                {
                  "childrenList": null,
                  "code": "12053",
                  "name": "上高县",
                  "pCode": "10242"
                },
                {
                  "childrenList": null,
                  "code": "12054",
                  "name": "宜丰县",
                  "pCode": "10242"
                },
                {
                  "childrenList": null,
                  "code": "12055",
                  "name": "靖安县",
                  "pCode": "10242"
                },
                {
                  "childrenList": null,
                  "code": "12056",
                  "name": "铜鼓县",
                  "pCode": "10242"
                }
              ],
              "code": "10242",
              "name": "宜春",
              "pCode": "10017"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12057",
                  "name": "月湖区",
                  "pCode": "10243"
                },
                {
                  "childrenList": null,
                  "code": "12058",
                  "name": "贵溪市",
                  "pCode": "10243"
                },
                {
                  "childrenList": null,
                  "code": "12059",
                  "name": "余江县",
                  "pCode": "10243"
                }
              ],
              "code": "10243",
              "name": "鹰潭",
              "pCode": "10017"
            }
          ],
          "code": "10017",
          "name": "江西省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12060",
                  "name": "沈河区",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12061",
                  "name": "皇姑区",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12062",
                  "name": "和平区",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12063",
                  "name": "大东区",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12064",
                  "name": "铁西区",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12065",
                  "name": "苏家屯区",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12066",
                  "name": "东陵区",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12067",
                  "name": "沈北新区",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12068",
                  "name": "于洪区",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12069",
                  "name": "浑南新区",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12070",
                  "name": "新民市",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12071",
                  "name": "辽中县",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12072",
                  "name": "康平县",
                  "pCode": "10244"
                },
                {
                  "childrenList": null,
                  "code": "12073",
                  "name": "法库县",
                  "pCode": "10244"
                }
              ],
              "code": "10244",
              "name": "沈阳",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12074",
                  "name": "西岗区",
                  "pCode": "10245"
                },
                {
                  "childrenList": null,
                  "code": "12075",
                  "name": "中山区",
                  "pCode": "10245"
                },
                {
                  "childrenList": null,
                  "code": "12076",
                  "name": "沙河口区",
                  "pCode": "10245"
                },
                {
                  "childrenList": null,
                  "code": "12077",
                  "name": "甘井子区",
                  "pCode": "10245"
                },
                {
                  "childrenList": null,
                  "code": "12078",
                  "name": "旅顺口区",
                  "pCode": "10245"
                },
                {
                  "childrenList": null,
                  "code": "12079",
                  "name": "金州区",
                  "pCode": "10245"
                },
                {
                  "childrenList": null,
                  "code": "12080",
                  "name": "开发区",
                  "pCode": "10245"
                },
                {
                  "childrenList": null,
                  "code": "12081",
                  "name": "瓦房店市",
                  "pCode": "10245"
                },
                {
                  "childrenList": null,
                  "code": "12082",
                  "name": "普兰店市",
                  "pCode": "10245"
                },
                {
                  "childrenList": null,
                  "code": "12083",
                  "name": "庄河市",
                  "pCode": "10245"
                },
                {
                  "childrenList": null,
                  "code": "12084",
                  "name": "长海县",
                  "pCode": "10245"
                }
              ],
              "code": "10245",
              "name": "大连",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12085",
                  "name": "铁东区",
                  "pCode": "10246"
                },
                {
                  "childrenList": null,
                  "code": "12086",
                  "name": "铁西区",
                  "pCode": "10246"
                },
                {
                  "childrenList": null,
                  "code": "12087",
                  "name": "立山区",
                  "pCode": "10246"
                },
                {
                  "childrenList": null,
                  "code": "12088",
                  "name": "千山区",
                  "pCode": "10246"
                },
                {
                  "childrenList": null,
                  "code": "12089",
                  "name": "岫岩",
                  "pCode": "10246"
                },
                {
                  "childrenList": null,
                  "code": "12090",
                  "name": "海城市",
                  "pCode": "10246"
                },
                {
                  "childrenList": null,
                  "code": "12091",
                  "name": "台安县",
                  "pCode": "10246"
                }
              ],
              "code": "10246",
              "name": "鞍山",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12092",
                  "name": "本溪",
                  "pCode": "10247"
                },
                {
                  "childrenList": null,
                  "code": "12093",
                  "name": "平山区",
                  "pCode": "10247"
                },
                {
                  "childrenList": null,
                  "code": "12094",
                  "name": "明山区",
                  "pCode": "10247"
                },
                {
                  "childrenList": null,
                  "code": "12095",
                  "name": "溪湖区",
                  "pCode": "10247"
                },
                {
                  "childrenList": null,
                  "code": "12096",
                  "name": "南芬区",
                  "pCode": "10247"
                },
                {
                  "childrenList": null,
                  "code": "12097",
                  "name": "桓仁",
                  "pCode": "10247"
                }
              ],
              "code": "10247",
              "name": "本溪",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12098",
                  "name": "双塔区",
                  "pCode": "10248"
                },
                {
                  "childrenList": null,
                  "code": "12099",
                  "name": "龙城区",
                  "pCode": "10248"
                },
                {
                  "childrenList": null,
                  "code": "12100",
                  "name": "喀喇沁左翼蒙古族自治县",
                  "pCode": "10248"
                },
                {
                  "childrenList": null,
                  "code": "12101",
                  "name": "北票市",
                  "pCode": "10248"
                },
                {
                  "childrenList": null,
                  "code": "12102",
                  "name": "凌源市",
                  "pCode": "10248"
                },
                {
                  "childrenList": null,
                  "code": "12103",
                  "name": "朝阳县",
                  "pCode": "10248"
                },
                {
                  "childrenList": null,
                  "code": "12104",
                  "name": "建平县",
                  "pCode": "10248"
                }
              ],
              "code": "10248",
              "name": "朝阳",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12105",
                  "name": "振兴区",
                  "pCode": "10249"
                },
                {
                  "childrenList": null,
                  "code": "12106",
                  "name": "元宝区",
                  "pCode": "10249"
                },
                {
                  "childrenList": null,
                  "code": "12107",
                  "name": "振安区",
                  "pCode": "10249"
                },
                {
                  "childrenList": null,
                  "code": "12108",
                  "name": "宽甸",
                  "pCode": "10249"
                },
                {
                  "childrenList": null,
                  "code": "12109",
                  "name": "东港市",
                  "pCode": "10249"
                },
                {
                  "childrenList": null,
                  "code": "12110",
                  "name": "凤城市",
                  "pCode": "10249"
                }
              ],
              "code": "10249",
              "name": "丹东",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12111",
                  "name": "顺城区",
                  "pCode": "10250"
                },
                {
                  "childrenList": null,
                  "code": "12112",
                  "name": "新抚区",
                  "pCode": "10250"
                },
                {
                  "childrenList": null,
                  "code": "12113",
                  "name": "东洲区",
                  "pCode": "10250"
                },
                {
                  "childrenList": null,
                  "code": "12114",
                  "name": "望花区",
                  "pCode": "10250"
                },
                {
                  "childrenList": null,
                  "code": "12115",
                  "name": "清原",
                  "pCode": "10250"
                },
                {
                  "childrenList": null,
                  "code": "12116",
                  "name": "新宾",
                  "pCode": "10250"
                },
                {
                  "childrenList": null,
                  "code": "12117",
                  "name": "抚顺县",
                  "pCode": "10250"
                }
              ],
              "code": "10250",
              "name": "抚顺",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12118",
                  "name": "阜新",
                  "pCode": "10251"
                },
                {
                  "childrenList": null,
                  "code": "12119",
                  "name": "海州区",
                  "pCode": "10251"
                },
                {
                  "childrenList": null,
                  "code": "12120",
                  "name": "新邱区",
                  "pCode": "10251"
                },
                {
                  "childrenList": null,
                  "code": "12121",
                  "name": "太平区",
                  "pCode": "10251"
                },
                {
                  "childrenList": null,
                  "code": "12122",
                  "name": "清河门区",
                  "pCode": "10251"
                },
                {
                  "childrenList": null,
                  "code": "12123",
                  "name": "细河区",
                  "pCode": "10251"
                },
                {
                  "childrenList": null,
                  "code": "12124",
                  "name": "彰武县",
                  "pCode": "10251"
                }
              ],
              "code": "10251",
              "name": "阜新",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12125",
                  "name": "龙港区",
                  "pCode": "10252"
                },
                {
                  "childrenList": null,
                  "code": "12126",
                  "name": "南票区",
                  "pCode": "10252"
                },
                {
                  "childrenList": null,
                  "code": "12127",
                  "name": "连山区",
                  "pCode": "10252"
                },
                {
                  "childrenList": null,
                  "code": "12128",
                  "name": "兴城市",
                  "pCode": "10252"
                },
                {
                  "childrenList": null,
                  "code": "12129",
                  "name": "绥中县",
                  "pCode": "10252"
                },
                {
                  "childrenList": null,
                  "code": "12130",
                  "name": "建昌县",
                  "pCode": "10252"
                }
              ],
              "code": "10252",
              "name": "葫芦岛",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12131",
                  "name": "太和区",
                  "pCode": "10253"
                },
                {
                  "childrenList": null,
                  "code": "12132",
                  "name": "古塔区",
                  "pCode": "10253"
                },
                {
                  "childrenList": null,
                  "code": "12133",
                  "name": "凌河区",
                  "pCode": "10253"
                },
                {
                  "childrenList": null,
                  "code": "12134",
                  "name": "凌海市",
                  "pCode": "10253"
                },
                {
                  "childrenList": null,
                  "code": "12135",
                  "name": "北镇市",
                  "pCode": "10253"
                },
                {
                  "childrenList": null,
                  "code": "12136",
                  "name": "黑山县",
                  "pCode": "10253"
                },
                {
                  "childrenList": null,
                  "code": "12137",
                  "name": "义县",
                  "pCode": "10253"
                }
              ],
              "code": "10253",
              "name": "锦州",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12138",
                  "name": "白塔区",
                  "pCode": "10254"
                },
                {
                  "childrenList": null,
                  "code": "12139",
                  "name": "文圣区",
                  "pCode": "10254"
                },
                {
                  "childrenList": null,
                  "code": "12140",
                  "name": "宏伟区",
                  "pCode": "10254"
                },
                {
                  "childrenList": null,
                  "code": "12141",
                  "name": "太子河区",
                  "pCode": "10254"
                },
                {
                  "childrenList": null,
                  "code": "12142",
                  "name": "弓长岭区",
                  "pCode": "10254"
                },
                {
                  "childrenList": null,
                  "code": "12143",
                  "name": "灯塔市",
                  "pCode": "10254"
                },
                {
                  "childrenList": null,
                  "code": "12144",
                  "name": "辽阳县",
                  "pCode": "10254"
                }
              ],
              "code": "10254",
              "name": "辽阳",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12145",
                  "name": "双台子区",
                  "pCode": "10255"
                },
                {
                  "childrenList": null,
                  "code": "12146",
                  "name": "兴隆台区",
                  "pCode": "10255"
                },
                {
                  "childrenList": null,
                  "code": "12147",
                  "name": "大洼县",
                  "pCode": "10255"
                },
                {
                  "childrenList": null,
                  "code": "12148",
                  "name": "盘山县",
                  "pCode": "10255"
                }
              ],
              "code": "10255",
              "name": "盘锦",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12149",
                  "name": "银州区",
                  "pCode": "10256"
                },
                {
                  "childrenList": null,
                  "code": "12150",
                  "name": "清河区",
                  "pCode": "10256"
                },
                {
                  "childrenList": null,
                  "code": "12151",
                  "name": "调兵山市",
                  "pCode": "10256"
                },
                {
                  "childrenList": null,
                  "code": "12152",
                  "name": "开原市",
                  "pCode": "10256"
                },
                {
                  "childrenList": null,
                  "code": "12153",
                  "name": "铁岭县",
                  "pCode": "10256"
                },
                {
                  "childrenList": null,
                  "code": "12154",
                  "name": "西丰县",
                  "pCode": "10256"
                },
                {
                  "childrenList": null,
                  "code": "12155",
                  "name": "昌图县",
                  "pCode": "10256"
                }
              ],
              "code": "10256",
              "name": "铁岭",
              "pCode": "10018"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12156",
                  "name": "站前区",
                  "pCode": "10257"
                },
                {
                  "childrenList": null,
                  "code": "12157",
                  "name": "西市区",
                  "pCode": "10257"
                },
                {
                  "childrenList": null,
                  "code": "12158",
                  "name": "鲅鱼圈区",
                  "pCode": "10257"
                },
                {
                  "childrenList": null,
                  "code": "12159",
                  "name": "老边区",
                  "pCode": "10257"
                },
                {
                  "childrenList": null,
                  "code": "12160",
                  "name": "盖州市",
                  "pCode": "10257"
                },
                {
                  "childrenList": null,
                  "code": "12161",
                  "name": "大石桥市",
                  "pCode": "10257"
                }
              ],
              "code": "10257",
              "name": "营口",
              "pCode": "10018"
            }
          ],
          "code": "10018",
          "name": "辽宁省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12162",
                  "name": "回民区",
                  "pCode": "10258"
                },
                {
                  "childrenList": null,
                  "code": "12163",
                  "name": "玉泉区",
                  "pCode": "10258"
                },
                {
                  "childrenList": null,
                  "code": "12164",
                  "name": "新城区",
                  "pCode": "10258"
                },
                {
                  "childrenList": null,
                  "code": "12165",
                  "name": "赛罕区",
                  "pCode": "10258"
                },
                {
                  "childrenList": null,
                  "code": "12166",
                  "name": "清水河县",
                  "pCode": "10258"
                },
                {
                  "childrenList": null,
                  "code": "12167",
                  "name": "土默特左旗",
                  "pCode": "10258"
                },
                {
                  "childrenList": null,
                  "code": "12168",
                  "name": "托克托县",
                  "pCode": "10258"
                },
                {
                  "childrenList": null,
                  "code": "12169",
                  "name": "和林格尔县",
                  "pCode": "10258"
                },
                {
                  "childrenList": null,
                  "code": "12170",
                  "name": "武川县",
                  "pCode": "10258"
                }
              ],
              "code": "10258",
              "name": "呼和浩特",
              "pCode": "10019"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12171",
                  "name": "阿拉善左旗",
                  "pCode": "10259"
                },
                {
                  "childrenList": null,
                  "code": "12172",
                  "name": "阿拉善右旗",
                  "pCode": "10259"
                },
                {
                  "childrenList": null,
                  "code": "12173",
                  "name": "额济纳旗",
                  "pCode": "10259"
                }
              ],
              "code": "10259",
              "name": "阿拉善盟",
              "pCode": "10019"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12174",
                  "name": "临河区",
                  "pCode": "10260"
                },
                {
                  "childrenList": null,
                  "code": "12175",
                  "name": "五原县",
                  "pCode": "10260"
                },
                {
                  "childrenList": null,
                  "code": "12176",
                  "name": "磴口县",
                  "pCode": "10260"
                },
                {
                  "childrenList": null,
                  "code": "12177",
                  "name": "乌拉特前旗",
                  "pCode": "10260"
                },
                {
                  "childrenList": null,
                  "code": "12178",
                  "name": "乌拉特中旗",
                  "pCode": "10260"
                },
                {
                  "childrenList": null,
                  "code": "12179",
                  "name": "乌拉特后旗",
                  "pCode": "10260"
                },
                {
                  "childrenList": null,
                  "code": "12180",
                  "name": "杭锦后旗",
                  "pCode": "10260"
                }
              ],
              "code": "10260",
              "name": "巴彦淖尔盟",
              "pCode": "10019"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12181",
                  "name": "昆都仑区",
                  "pCode": "10261"
                },
                {
                  "childrenList": null,
                  "code": "12182",
                  "name": "青山区",
                  "pCode": "10261"
                },
                {
                  "childrenList": null,
                  "code": "12183",
                  "name": "东河区",
                  "pCode": "10261"
                },
                {
                  "childrenList": null,
                  "code": "12184",
                  "name": "九原区",
                  "pCode": "10261"
                },
                {
                  "childrenList": null,
                  "code": "12185",
                  "name": "石拐区",
                  "pCode": "10261"
                },
                {
                  "childrenList": null,
                  "code": "12186",
                  "name": "白云矿区",
                  "pCode": "10261"
                },
                {
                  "childrenList": null,
                  "code": "12187",
                  "name": "土默特右旗",
                  "pCode": "10261"
                },
                {
                  "childrenList": null,
                  "code": "12188",
                  "name": "固阳县",
                  "pCode": "10261"
                },
                {
                  "childrenList": null,
                  "code": "12189",
                  "name": "达尔罕茂明安联合旗",
                  "pCode": "10261"
                }
              ],
              "code": "10261",
              "name": "包头",
              "pCode": "10019"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12190",
                  "name": "红山区",
                  "pCode": "10262"
                },
                {
                  "childrenList": null,
                  "code": "12191",
                  "name": "元宝山区",
                  "pCode": "10262"
                },
                {
                  "childrenList": null,
                  "code": "12192",
                  "name": "松山区",
                  "pCode": "10262"
                },
                {
                  "childrenList": null,
                  "code": "12193",
                  "name": "阿鲁科尔沁旗",
                  "pCode": "10262"
                },
                {
                  "childrenList": null,
                  "code": "12194",
                  "name": "巴林左旗",
                  "pCode": "10262"
                },
                {
                  "childrenList": null,
                  "code": "12195",
                  "name": "巴林右旗",
                  "pCode": "10262"
                },
                {
                  "childrenList": null,
                  "code": "12196",
                  "name": "林西县",
                  "pCode": "10262"
                },
                {
                  "childrenList": null,
                  "code": "12197",
                  "name": "克什克腾旗",
                  "pCode": "10262"
                },
                {
                  "childrenList": null,
                  "code": "12198",
                  "name": "翁牛特旗",
                  "pCode": "10262"
                },
                {
                  "childrenList": null,
                  "code": "12199",
                  "name": "喀喇沁旗",
                  "pCode": "10262"
                },
                {
                  "childrenList": null,
                  "code": "12200",
                  "name": "宁城县",
                  "pCode": "10262"
                },
                {
                  "childrenList": null,
                  "code": "12201",
                  "name": "敖汉旗",
                  "pCode": "10262"
                }
              ],
              "code": "10262",
              "name": "赤峰",
              "pCode": "10019"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12202",
                  "name": "东胜区",
                  "pCode": "10263"
                },
                {
                  "childrenList": null,
                  "code": "12203",
                  "name": "达拉特旗",
                  "pCode": "10263"
                },
                {
                  "childrenList": null,
                  "code": "12204",
                  "name": "准格尔旗",
                  "pCode": "10263"
                },
                {
                  "childrenList": null,
                  "code": "12205",
                  "name": "鄂托克前旗",
                  "pCode": "10263"
                },
                {
                  "childrenList": null,
                  "code": "12206",
                  "name": "鄂托克旗",
                  "pCode": "10263"
                },
                {
                  "childrenList": null,
                  "code": "12207",
                  "name": "杭锦旗",
                  "pCode": "10263"
                },
                {
                  "childrenList": null,
                  "code": "12208",
                  "name": "乌审旗",
                  "pCode": "10263"
                },
                {
                  "childrenList": null,
                  "code": "12209",
                  "name": "伊金霍洛旗",
                  "pCode": "10263"
                }
              ],
              "code": "10263",
              "name": "鄂尔多斯",
              "pCode": "10019"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12210",
                  "name": "海拉尔区",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12211",
                  "name": "莫力达瓦",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12212",
                  "name": "满洲里市",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12213",
                  "name": "牙克石市",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12214",
                  "name": "扎兰屯市",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12215",
                  "name": "额尔古纳市",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12216",
                  "name": "根河市",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12217",
                  "name": "阿荣旗",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12218",
                  "name": "鄂伦春自治旗",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12219",
                  "name": "鄂温克族自治旗",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12220",
                  "name": "陈巴尔虎旗",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12221",
                  "name": "新巴尔虎左旗",
                  "pCode": "10264"
                },
                {
                  "childrenList": null,
                  "code": "12222",
                  "name": "新巴尔虎右旗",
                  "pCode": "10264"
                }
              ],
              "code": "10264",
              "name": "呼伦贝尔",
              "pCode": "10019"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12223",
                  "name": "科尔沁区",
                  "pCode": "10265"
                },
                {
                  "childrenList": null,
                  "code": "12224",
                  "name": "霍林郭勒市",
                  "pCode": "10265"
                },
                {
                  "childrenList": null,
                  "code": "12225",
                  "name": "科尔沁左翼中旗",
                  "pCode": "10265"
                },
                {
                  "childrenList": null,
                  "code": "12226",
                  "name": "科尔沁左翼后旗",
                  "pCode": "10265"
                },
                {
                  "childrenList": null,
                  "code": "12227",
                  "name": "开鲁县",
                  "pCode": "10265"
                },
                {
                  "childrenList": null,
                  "code": "12228",
                  "name": "库伦旗",
                  "pCode": "10265"
                },
                {
                  "childrenList": null,
                  "code": "12229",
                  "name": "奈曼旗",
                  "pCode": "10265"
                },
                {
                  "childrenList": null,
                  "code": "12230",
                  "name": "扎鲁特旗",
                  "pCode": "10265"
                }
              ],
              "code": "10265",
              "name": "通辽",
              "pCode": "10019"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12231",
                  "name": "海勃湾区",
                  "pCode": "10266"
                },
                {
                  "childrenList": null,
                  "code": "12232",
                  "name": "乌达区",
                  "pCode": "10266"
                },
                {
                  "childrenList": null,
                  "code": "12233",
                  "name": "海南区",
                  "pCode": "10266"
                }
              ],
              "code": "10266",
              "name": "乌海",
              "pCode": "10019"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12234",
                  "name": "化德县",
                  "pCode": "10267"
                },
                {
                  "childrenList": null,
                  "code": "12235",
                  "name": "集宁区",
                  "pCode": "10267"
                },
                {
                  "childrenList": null,
                  "code": "12236",
                  "name": "丰镇市",
                  "pCode": "10267"
                },
                {
                  "childrenList": null,
                  "code": "12237",
                  "name": "卓资县",
                  "pCode": "10267"
                },
                {
                  "childrenList": null,
                  "code": "12238",
                  "name": "商都县",
                  "pCode": "10267"
                },
                {
                  "childrenList": null,
                  "code": "12239",
                  "name": "兴和县",
                  "pCode": "10267"
                },
                {
                  "childrenList": null,
                  "code": "12240",
                  "name": "凉城县",
                  "pCode": "10267"
                },
                {
                  "childrenList": null,
                  "code": "12241",
                  "name": "察哈尔右翼前旗",
                  "pCode": "10267"
                },
                {
                  "childrenList": null,
                  "code": "12242",
                  "name": "察哈尔右翼中旗",
                  "pCode": "10267"
                },
                {
                  "childrenList": null,
                  "code": "12243",
                  "name": "察哈尔右翼后旗",
                  "pCode": "10267"
                },
                {
                  "childrenList": null,
                  "code": "12244",
                  "name": "四子王旗",
                  "pCode": "10267"
                }
              ],
              "code": "10267",
              "name": "乌兰察布市",
              "pCode": "10019"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12245",
                  "name": "二连浩特市",
                  "pCode": "10268"
                },
                {
                  "childrenList": null,
                  "code": "12246",
                  "name": "锡林浩特市",
                  "pCode": "10268"
                },
                {
                  "childrenList": null,
                  "code": "12247",
                  "name": "阿巴嘎旗",
                  "pCode": "10268"
                },
                {
                  "childrenList": null,
                  "code": "12248",
                  "name": "苏尼特左旗",
                  "pCode": "10268"
                },
                {
                  "childrenList": null,
                  "code": "12249",
                  "name": "苏尼特右旗",
                  "pCode": "10268"
                },
                {
                  "childrenList": null,
                  "code": "12250",
                  "name": "东乌珠穆沁旗",
                  "pCode": "10268"
                },
                {
                  "childrenList": null,
                  "code": "12251",
                  "name": "西乌珠穆沁旗",
                  "pCode": "10268"
                },
                {
                  "childrenList": null,
                  "code": "12252",
                  "name": "太仆寺旗",
                  "pCode": "10268"
                },
                {
                  "childrenList": null,
                  "code": "12253",
                  "name": "镶黄旗",
                  "pCode": "10268"
                },
                {
                  "childrenList": null,
                  "code": "12254",
                  "name": "正镶白旗",
                  "pCode": "10268"
                },
                {
                  "childrenList": null,
                  "code": "12255",
                  "name": "正蓝旗",
                  "pCode": "10268"
                },
                {
                  "childrenList": null,
                  "code": "12256",
                  "name": "多伦县",
                  "pCode": "10268"
                }
              ],
              "code": "10268",
              "name": "锡林郭勒盟",
              "pCode": "10019"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12257",
                  "name": "乌兰浩特市",
                  "pCode": "10269"
                },
                {
                  "childrenList": null,
                  "code": "12258",
                  "name": "阿尔山市",
                  "pCode": "10269"
                },
                {
                  "childrenList": null,
                  "code": "12259",
                  "name": "科尔沁右翼前旗",
                  "pCode": "10269"
                },
                {
                  "childrenList": null,
                  "code": "12260",
                  "name": "科尔沁右翼中旗",
                  "pCode": "10269"
                },
                {
                  "childrenList": null,
                  "code": "12261",
                  "name": "扎赉特旗",
                  "pCode": "10269"
                },
                {
                  "childrenList": null,
                  "code": "12262",
                  "name": "突泉县",
                  "pCode": "10269"
                }
              ],
              "code": "10269",
              "name": "兴安盟",
              "pCode": "10019"
            }
          ],
          "code": "10019",
          "name": "内蒙古自治区",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12263",
                  "name": "西夏区",
                  "pCode": "10270"
                },
                {
                  "childrenList": null,
                  "code": "12264",
                  "name": "金凤区",
                  "pCode": "10270"
                },
                {
                  "childrenList": null,
                  "code": "12265",
                  "name": "兴庆区",
                  "pCode": "10270"
                },
                {
                  "childrenList": null,
                  "code": "12266",
                  "name": "灵武市",
                  "pCode": "10270"
                },
                {
                  "childrenList": null,
                  "code": "12267",
                  "name": "永宁县",
                  "pCode": "10270"
                },
                {
                  "childrenList": null,
                  "code": "12268",
                  "name": "贺兰县",
                  "pCode": "10270"
                }
              ],
              "code": "10270",
              "name": "银川",
              "pCode": "10020"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12269",
                  "name": "原州区",
                  "pCode": "10271"
                },
                {
                  "childrenList": null,
                  "code": "12270",
                  "name": "海原县",
                  "pCode": "10271"
                },
                {
                  "childrenList": null,
                  "code": "12271",
                  "name": "西吉县",
                  "pCode": "10271"
                },
                {
                  "childrenList": null,
                  "code": "12272",
                  "name": "隆德县",
                  "pCode": "10271"
                },
                {
                  "childrenList": null,
                  "code": "12273",
                  "name": "泾源县",
                  "pCode": "10271"
                },
                {
                  "childrenList": null,
                  "code": "12274",
                  "name": "彭阳县",
                  "pCode": "10271"
                }
              ],
              "code": "10271",
              "name": "固原",
              "pCode": "10020"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12275",
                  "name": "惠农县",
                  "pCode": "10272"
                },
                {
                  "childrenList": null,
                  "code": "12276",
                  "name": "大武口区",
                  "pCode": "10272"
                },
                {
                  "childrenList": null,
                  "code": "12277",
                  "name": "惠农区",
                  "pCode": "10272"
                },
                {
                  "childrenList": null,
                  "code": "12278",
                  "name": "陶乐县",
                  "pCode": "10272"
                },
                {
                  "childrenList": null,
                  "code": "12279",
                  "name": "平罗县",
                  "pCode": "10272"
                }
              ],
              "code": "10272",
              "name": "石嘴山",
              "pCode": "10020"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12280",
                  "name": "利通区",
                  "pCode": "10273"
                },
                {
                  "childrenList": null,
                  "code": "12281",
                  "name": "中卫县",
                  "pCode": "10273"
                },
                {
                  "childrenList": null,
                  "code": "12282",
                  "name": "青铜峡市",
                  "pCode": "10273"
                },
                {
                  "childrenList": null,
                  "code": "12283",
                  "name": "中宁县",
                  "pCode": "10273"
                },
                {
                  "childrenList": null,
                  "code": "12284",
                  "name": "盐池县",
                  "pCode": "10273"
                },
                {
                  "childrenList": null,
                  "code": "12285",
                  "name": "同心县",
                  "pCode": "10273"
                }
              ],
              "code": "10273",
              "name": "吴忠",
              "pCode": "10020"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12286",
                  "name": "沙坡头区",
                  "pCode": "10274"
                },
                {
                  "childrenList": null,
                  "code": "12287",
                  "name": "海原县",
                  "pCode": "10274"
                },
                {
                  "childrenList": null,
                  "code": "12288",
                  "name": "中宁县",
                  "pCode": "10274"
                }
              ],
              "code": "10274",
              "name": "中卫",
              "pCode": "10020"
            }
          ],
          "code": "10020",
          "name": "宁夏回族自治区",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12289",
                  "name": "城中区",
                  "pCode": "10275"
                },
                {
                  "childrenList": null,
                  "code": "12290",
                  "name": "城东区",
                  "pCode": "10275"
                },
                {
                  "childrenList": null,
                  "code": "12291",
                  "name": "城西区",
                  "pCode": "10275"
                },
                {
                  "childrenList": null,
                  "code": "12292",
                  "name": "城北区",
                  "pCode": "10275"
                },
                {
                  "childrenList": null,
                  "code": "12293",
                  "name": "湟中县",
                  "pCode": "10275"
                },
                {
                  "childrenList": null,
                  "code": "12294",
                  "name": "湟源县",
                  "pCode": "10275"
                },
                {
                  "childrenList": null,
                  "code": "12295",
                  "name": "大通",
                  "pCode": "10275"
                }
              ],
              "code": "10275",
              "name": "西宁",
              "pCode": "10021"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12296",
                  "name": "玛沁县",
                  "pCode": "10276"
                },
                {
                  "childrenList": null,
                  "code": "12297",
                  "name": "班玛县",
                  "pCode": "10276"
                },
                {
                  "childrenList": null,
                  "code": "12298",
                  "name": "甘德县",
                  "pCode": "10276"
                },
                {
                  "childrenList": null,
                  "code": "12299",
                  "name": "达日县",
                  "pCode": "10276"
                },
                {
                  "childrenList": null,
                  "code": "12300",
                  "name": "久治县",
                  "pCode": "10276"
                },
                {
                  "childrenList": null,
                  "code": "12301",
                  "name": "玛多县",
                  "pCode": "10276"
                }
              ],
              "code": "10276",
              "name": "果洛",
              "pCode": "10021"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12302",
                  "name": "海晏县",
                  "pCode": "10277"
                },
                {
                  "childrenList": null,
                  "code": "12303",
                  "name": "祁连县",
                  "pCode": "10277"
                },
                {
                  "childrenList": null,
                  "code": "12304",
                  "name": "刚察县",
                  "pCode": "10277"
                },
                {
                  "childrenList": null,
                  "code": "12305",
                  "name": "门源",
                  "pCode": "10277"
                }
              ],
              "code": "10277",
              "name": "海北",
              "pCode": "10021"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12306",
                  "name": "平安县",
                  "pCode": "10278"
                },
                {
                  "childrenList": null,
                  "code": "12307",
                  "name": "乐都县",
                  "pCode": "10278"
                },
                {
                  "childrenList": null,
                  "code": "12308",
                  "name": "民和",
                  "pCode": "10278"
                },
                {
                  "childrenList": null,
                  "code": "12309",
                  "name": "互助",
                  "pCode": "10278"
                },
                {
                  "childrenList": null,
                  "code": "12310",
                  "name": "化隆",
                  "pCode": "10278"
                },
                {
                  "childrenList": null,
                  "code": "12311",
                  "name": "循化",
                  "pCode": "10278"
                }
              ],
              "code": "10278",
              "name": "海东",
              "pCode": "10021"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12312",
                  "name": "共和县",
                  "pCode": "10279"
                },
                {
                  "childrenList": null,
                  "code": "12313",
                  "name": "同德县",
                  "pCode": "10279"
                },
                {
                  "childrenList": null,
                  "code": "12314",
                  "name": "贵德县",
                  "pCode": "10279"
                },
                {
                  "childrenList": null,
                  "code": "12315",
                  "name": "兴海县",
                  "pCode": "10279"
                },
                {
                  "childrenList": null,
                  "code": "12316",
                  "name": "贵南县",
                  "pCode": "10279"
                }
              ],
              "code": "10279",
              "name": "海南",
              "pCode": "10021"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12317",
                  "name": "德令哈市",
                  "pCode": "10280"
                },
                {
                  "childrenList": null,
                  "code": "12318",
                  "name": "格尔木市",
                  "pCode": "10280"
                },
                {
                  "childrenList": null,
                  "code": "12319",
                  "name": "乌兰县",
                  "pCode": "10280"
                },
                {
                  "childrenList": null,
                  "code": "12320",
                  "name": "都兰县",
                  "pCode": "10280"
                },
                {
                  "childrenList": null,
                  "code": "12321",
                  "name": "天峻县",
                  "pCode": "10280"
                }
              ],
              "code": "10280",
              "name": "海西",
              "pCode": "10021"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12322",
                  "name": "同仁县",
                  "pCode": "10281"
                },
                {
                  "childrenList": null,
                  "code": "12323",
                  "name": "尖扎县",
                  "pCode": "10281"
                },
                {
                  "childrenList": null,
                  "code": "12324",
                  "name": "泽库县",
                  "pCode": "10281"
                },
                {
                  "childrenList": null,
                  "code": "12325",
                  "name": "河南蒙古族自治县",
                  "pCode": "10281"
                }
              ],
              "code": "10281",
              "name": "黄南",
              "pCode": "10021"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12326",
                  "name": "玉树县",
                  "pCode": "10282"
                },
                {
                  "childrenList": null,
                  "code": "12327",
                  "name": "杂多县",
                  "pCode": "10282"
                },
                {
                  "childrenList": null,
                  "code": "12328",
                  "name": "称多县",
                  "pCode": "10282"
                },
                {
                  "childrenList": null,
                  "code": "12329",
                  "name": "治多县",
                  "pCode": "10282"
                },
                {
                  "childrenList": null,
                  "code": "12330",
                  "name": "囊谦县",
                  "pCode": "10282"
                },
                {
                  "childrenList": null,
                  "code": "12331",
                  "name": "曲麻莱县",
                  "pCode": "10282"
                }
              ],
              "code": "10282",
              "name": "玉树",
              "pCode": "10021"
            }
          ],
          "code": "10021",
          "name": "青海省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12332",
                  "name": "市中区",
                  "pCode": "10283"
                },
                {
                  "childrenList": null,
                  "code": "12333",
                  "name": "历下区",
                  "pCode": "10283"
                },
                {
                  "childrenList": null,
                  "code": "12334",
                  "name": "天桥区",
                  "pCode": "10283"
                },
                {
                  "childrenList": null,
                  "code": "12335",
                  "name": "槐荫区",
                  "pCode": "10283"
                },
                {
                  "childrenList": null,
                  "code": "12336",
                  "name": "历城区",
                  "pCode": "10283"
                },
                {
                  "childrenList": null,
                  "code": "12337",
                  "name": "长清区",
                  "pCode": "10283"
                },
                {
                  "childrenList": null,
                  "code": "12338",
                  "name": "章丘市",
                  "pCode": "10283"
                },
                {
                  "childrenList": null,
                  "code": "12339",
                  "name": "平阴县",
                  "pCode": "10283"
                },
                {
                  "childrenList": null,
                  "code": "12340",
                  "name": "济阳县",
                  "pCode": "10283"
                },
                {
                  "childrenList": null,
                  "code": "12341",
                  "name": "商河县",
                  "pCode": "10283"
                }
              ],
              "code": "10283",
              "name": "济南",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12342",
                  "name": "市南区",
                  "pCode": "10284"
                },
                {
                  "childrenList": null,
                  "code": "12343",
                  "name": "市北区",
                  "pCode": "10284"
                },
                {
                  "childrenList": null,
                  "code": "12344",
                  "name": "城阳区",
                  "pCode": "10284"
                },
                {
                  "childrenList": null,
                  "code": "12345",
                  "name": "四方区",
                  "pCode": "10284"
                },
                {
                  "childrenList": null,
                  "code": "12346",
                  "name": "李沧区",
                  "pCode": "10284"
                },
                {
                  "childrenList": null,
                  "code": "12347",
                  "name": "黄岛区",
                  "pCode": "10284"
                },
                {
                  "childrenList": null,
                  "code": "12348",
                  "name": "崂山区",
                  "pCode": "10284"
                },
                {
                  "childrenList": null,
                  "code": "12349",
                  "name": "胶州市",
                  "pCode": "10284"
                },
                {
                  "childrenList": null,
                  "code": "12350",
                  "name": "即墨市",
                  "pCode": "10284"
                },
                {
                  "childrenList": null,
                  "code": "12351",
                  "name": "平度市",
                  "pCode": "10284"
                },
                {
                  "childrenList": null,
                  "code": "12352",
                  "name": "胶南市",
                  "pCode": "10284"
                },
                {
                  "childrenList": null,
                  "code": "12353",
                  "name": "莱西市",
                  "pCode": "10284"
                }
              ],
              "code": "10284",
              "name": "青岛",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12354",
                  "name": "滨城区",
                  "pCode": "10285"
                },
                {
                  "childrenList": null,
                  "code": "12355",
                  "name": "惠民县",
                  "pCode": "10285"
                },
                {
                  "childrenList": null,
                  "code": "12356",
                  "name": "阳信县",
                  "pCode": "10285"
                },
                {
                  "childrenList": null,
                  "code": "12357",
                  "name": "无棣县",
                  "pCode": "10285"
                },
                {
                  "childrenList": null,
                  "code": "12358",
                  "name": "沾化县",
                  "pCode": "10285"
                },
                {
                  "childrenList": null,
                  "code": "12359",
                  "name": "博兴县",
                  "pCode": "10285"
                },
                {
                  "childrenList": null,
                  "code": "12360",
                  "name": "邹平县",
                  "pCode": "10285"
                }
              ],
              "code": "10285",
              "name": "滨州",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12361",
                  "name": "德城区",
                  "pCode": "10286"
                },
                {
                  "childrenList": null,
                  "code": "12362",
                  "name": "陵县",
                  "pCode": "10286"
                },
                {
                  "childrenList": null,
                  "code": "12363",
                  "name": "乐陵市",
                  "pCode": "10286"
                },
                {
                  "childrenList": null,
                  "code": "12364",
                  "name": "禹城市",
                  "pCode": "10286"
                },
                {
                  "childrenList": null,
                  "code": "12365",
                  "name": "宁津县",
                  "pCode": "10286"
                },
                {
                  "childrenList": null,
                  "code": "12366",
                  "name": "庆云县",
                  "pCode": "10286"
                },
                {
                  "childrenList": null,
                  "code": "12367",
                  "name": "临邑县",
                  "pCode": "10286"
                },
                {
                  "childrenList": null,
                  "code": "12368",
                  "name": "齐河县",
                  "pCode": "10286"
                },
                {
                  "childrenList": null,
                  "code": "12369",
                  "name": "平原县",
                  "pCode": "10286"
                },
                {
                  "childrenList": null,
                  "code": "12370",
                  "name": "夏津县",
                  "pCode": "10286"
                },
                {
                  "childrenList": null,
                  "code": "12371",
                  "name": "武城县",
                  "pCode": "10286"
                }
              ],
              "code": "10286",
              "name": "德州",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12372",
                  "name": "东营区",
                  "pCode": "10287"
                },
                {
                  "childrenList": null,
                  "code": "12373",
                  "name": "河口区",
                  "pCode": "10287"
                },
                {
                  "childrenList": null,
                  "code": "12374",
                  "name": "垦利县",
                  "pCode": "10287"
                },
                {
                  "childrenList": null,
                  "code": "12375",
                  "name": "利津县",
                  "pCode": "10287"
                },
                {
                  "childrenList": null,
                  "code": "12376",
                  "name": "广饶县",
                  "pCode": "10287"
                }
              ],
              "code": "10287",
              "name": "东营",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12377",
                  "name": "牡丹区",
                  "pCode": "10288"
                },
                {
                  "childrenList": null,
                  "code": "12378",
                  "name": "曹县",
                  "pCode": "10288"
                },
                {
                  "childrenList": null,
                  "code": "12379",
                  "name": "单县",
                  "pCode": "10288"
                },
                {
                  "childrenList": null,
                  "code": "12380",
                  "name": "成武县",
                  "pCode": "10288"
                },
                {
                  "childrenList": null,
                  "code": "12381",
                  "name": "巨野县",
                  "pCode": "10288"
                },
                {
                  "childrenList": null,
                  "code": "12382",
                  "name": "郓城县",
                  "pCode": "10288"
                },
                {
                  "childrenList": null,
                  "code": "12383",
                  "name": "鄄城县",
                  "pCode": "10288"
                },
                {
                  "childrenList": null,
                  "code": "12384",
                  "name": "定陶县",
                  "pCode": "10288"
                },
                {
                  "childrenList": null,
                  "code": "12385",
                  "name": "东明县",
                  "pCode": "10288"
                }
              ],
              "code": "10288",
              "name": "菏泽",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12386",
                  "name": "市中区",
                  "pCode": "10289"
                },
                {
                  "childrenList": null,
                  "code": "12387",
                  "name": "任城区",
                  "pCode": "10289"
                },
                {
                  "childrenList": null,
                  "code": "12388",
                  "name": "曲阜市",
                  "pCode": "10289"
                },
                {
                  "childrenList": null,
                  "code": "12389",
                  "name": "兖州市",
                  "pCode": "10289"
                },
                {
                  "childrenList": null,
                  "code": "12390",
                  "name": "邹城市",
                  "pCode": "10289"
                },
                {
                  "childrenList": null,
                  "code": "12391",
                  "name": "微山县",
                  "pCode": "10289"
                },
                {
                  "childrenList": null,
                  "code": "12392",
                  "name": "鱼台县",
                  "pCode": "10289"
                },
                {
                  "childrenList": null,
                  "code": "12393",
                  "name": "金乡县",
                  "pCode": "10289"
                },
                {
                  "childrenList": null,
                  "code": "12394",
                  "name": "嘉祥县",
                  "pCode": "10289"
                },
                {
                  "childrenList": null,
                  "code": "12395",
                  "name": "汶上县",
                  "pCode": "10289"
                },
                {
                  "childrenList": null,
                  "code": "12396",
                  "name": "泗水县",
                  "pCode": "10289"
                },
                {
                  "childrenList": null,
                  "code": "12397",
                  "name": "梁山县",
                  "pCode": "10289"
                }
              ],
              "code": "10289",
              "name": "济宁",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12398",
                  "name": "莱城区",
                  "pCode": "10290"
                },
                {
                  "childrenList": null,
                  "code": "12399",
                  "name": "钢城区",
                  "pCode": "10290"
                }
              ],
              "code": "10290",
              "name": "莱芜",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12400",
                  "name": "东昌府区",
                  "pCode": "10291"
                },
                {
                  "childrenList": null,
                  "code": "12401",
                  "name": "临清市",
                  "pCode": "10291"
                },
                {
                  "childrenList": null,
                  "code": "12402",
                  "name": "阳谷县",
                  "pCode": "10291"
                },
                {
                  "childrenList": null,
                  "code": "12403",
                  "name": "莘县",
                  "pCode": "10291"
                },
                {
                  "childrenList": null,
                  "code": "12404",
                  "name": "茌平县",
                  "pCode": "10291"
                },
                {
                  "childrenList": null,
                  "code": "12405",
                  "name": "东阿县",
                  "pCode": "10291"
                },
                {
                  "childrenList": null,
                  "code": "12406",
                  "name": "冠县",
                  "pCode": "10291"
                },
                {
                  "childrenList": null,
                  "code": "12407",
                  "name": "高唐县",
                  "pCode": "10291"
                }
              ],
              "code": "10291",
              "name": "聊城",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12408",
                  "name": "兰山区",
                  "pCode": "10292"
                },
                {
                  "childrenList": null,
                  "code": "12409",
                  "name": "罗庄区",
                  "pCode": "10292"
                },
                {
                  "childrenList": null,
                  "code": "12410",
                  "name": "河东区",
                  "pCode": "10292"
                },
                {
                  "childrenList": null,
                  "code": "12411",
                  "name": "沂南县",
                  "pCode": "10292"
                },
                {
                  "childrenList": null,
                  "code": "12412",
                  "name": "郯城县",
                  "pCode": "10292"
                },
                {
                  "childrenList": null,
                  "code": "12413",
                  "name": "沂水县",
                  "pCode": "10292"
                },
                {
                  "childrenList": null,
                  "code": "12414",
                  "name": "苍山县",
                  "pCode": "10292"
                },
                {
                  "childrenList": null,
                  "code": "12415",
                  "name": "费县",
                  "pCode": "10292"
                },
                {
                  "childrenList": null,
                  "code": "12416",
                  "name": "平邑县",
                  "pCode": "10292"
                },
                {
                  "childrenList": null,
                  "code": "12417",
                  "name": "莒南县",
                  "pCode": "10292"
                },
                {
                  "childrenList": null,
                  "code": "12418",
                  "name": "蒙阴县",
                  "pCode": "10292"
                },
                {
                  "childrenList": null,
                  "code": "12419",
                  "name": "临沭县",
                  "pCode": "10292"
                }
              ],
              "code": "10292",
              "name": "临沂",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12420",
                  "name": "东港区",
                  "pCode": "10293"
                },
                {
                  "childrenList": null,
                  "code": "12421",
                  "name": "岚山区",
                  "pCode": "10293"
                },
                {
                  "childrenList": null,
                  "code": "12422",
                  "name": "五莲县",
                  "pCode": "10293"
                },
                {
                  "childrenList": null,
                  "code": "12423",
                  "name": "莒县",
                  "pCode": "10293"
                }
              ],
              "code": "10293",
              "name": "日照",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12424",
                  "name": "泰山区",
                  "pCode": "10294"
                },
                {
                  "childrenList": null,
                  "code": "12425",
                  "name": "岱岳区",
                  "pCode": "10294"
                },
                {
                  "childrenList": null,
                  "code": "12426",
                  "name": "新泰市",
                  "pCode": "10294"
                },
                {
                  "childrenList": null,
                  "code": "12427",
                  "name": "肥城市",
                  "pCode": "10294"
                },
                {
                  "childrenList": null,
                  "code": "12428",
                  "name": "宁阳县",
                  "pCode": "10294"
                },
                {
                  "childrenList": null,
                  "code": "12429",
                  "name": "东平县",
                  "pCode": "10294"
                }
              ],
              "code": "10294",
              "name": "泰安",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12430",
                  "name": "荣成市",
                  "pCode": "10295"
                },
                {
                  "childrenList": null,
                  "code": "12431",
                  "name": "乳山市",
                  "pCode": "10295"
                },
                {
                  "childrenList": null,
                  "code": "12432",
                  "name": "环翠区",
                  "pCode": "10295"
                },
                {
                  "childrenList": null,
                  "code": "12433",
                  "name": "文登市",
                  "pCode": "10295"
                }
              ],
              "code": "10295",
              "name": "威海",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12434",
                  "name": "潍城区",
                  "pCode": "10296"
                },
                {
                  "childrenList": null,
                  "code": "12435",
                  "name": "寒亭区",
                  "pCode": "10296"
                },
                {
                  "childrenList": null,
                  "code": "12436",
                  "name": "坊子区",
                  "pCode": "10296"
                },
                {
                  "childrenList": null,
                  "code": "12437",
                  "name": "奎文区",
                  "pCode": "10296"
                },
                {
                  "childrenList": null,
                  "code": "12438",
                  "name": "青州市",
                  "pCode": "10296"
                },
                {
                  "childrenList": null,
                  "code": "12439",
                  "name": "诸城市",
                  "pCode": "10296"
                },
                {
                  "childrenList": null,
                  "code": "12440",
                  "name": "寿光市",
                  "pCode": "10296"
                },
                {
                  "childrenList": null,
                  "code": "12441",
                  "name": "安丘市",
                  "pCode": "10296"
                },
                {
                  "childrenList": null,
                  "code": "12442",
                  "name": "高密市",
                  "pCode": "10296"
                },
                {
                  "childrenList": null,
                  "code": "12443",
                  "name": "昌邑市",
                  "pCode": "10296"
                },
                {
                  "childrenList": null,
                  "code": "12444",
                  "name": "临朐县",
                  "pCode": "10296"
                },
                {
                  "childrenList": null,
                  "code": "12445",
                  "name": "昌乐县",
                  "pCode": "10296"
                }
              ],
              "code": "10296",
              "name": "潍坊",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12446",
                  "name": "芝罘区",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12447",
                  "name": "福山区",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12448",
                  "name": "牟平区",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12449",
                  "name": "莱山区",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12450",
                  "name": "开发区",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12451",
                  "name": "龙口市",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12452",
                  "name": "莱阳市",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12453",
                  "name": "莱州市",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12454",
                  "name": "蓬莱市",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12455",
                  "name": "招远市",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12456",
                  "name": "栖霞市",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12457",
                  "name": "海阳市",
                  "pCode": "10297"
                },
                {
                  "childrenList": null,
                  "code": "12458",
                  "name": "长岛县",
                  "pCode": "10297"
                }
              ],
              "code": "10297",
              "name": "烟台",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12459",
                  "name": "市中区",
                  "pCode": "10298"
                },
                {
                  "childrenList": null,
                  "code": "12460",
                  "name": "山亭区",
                  "pCode": "10298"
                },
                {
                  "childrenList": null,
                  "code": "12461",
                  "name": "峄城区",
                  "pCode": "10298"
                },
                {
                  "childrenList": null,
                  "code": "12462",
                  "name": "台儿庄区",
                  "pCode": "10298"
                },
                {
                  "childrenList": null,
                  "code": "12463",
                  "name": "薛城区",
                  "pCode": "10298"
                },
                {
                  "childrenList": null,
                  "code": "12464",
                  "name": "滕州市",
                  "pCode": "10298"
                }
              ],
              "code": "10298",
              "name": "枣庄",
              "pCode": "10022"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12465",
                  "name": "张店区",
                  "pCode": "10299"
                },
                {
                  "childrenList": null,
                  "code": "12466",
                  "name": "临淄区",
                  "pCode": "10299"
                },
                {
                  "childrenList": null,
                  "code": "12467",
                  "name": "淄川区",
                  "pCode": "10299"
                },
                {
                  "childrenList": null,
                  "code": "12468",
                  "name": "博山区",
                  "pCode": "10299"
                },
                {
                  "childrenList": null,
                  "code": "12469",
                  "name": "周村区",
                  "pCode": "10299"
                },
                {
                  "childrenList": null,
                  "code": "12470",
                  "name": "桓台县",
                  "pCode": "10299"
                },
                {
                  "childrenList": null,
                  "code": "12471",
                  "name": "高青县",
                  "pCode": "10299"
                },
                {
                  "childrenList": null,
                  "code": "12472",
                  "name": "沂源县",
                  "pCode": "10299"
                }
              ],
              "code": "10299",
              "name": "淄博",
              "pCode": "10022"
            }
          ],
          "code": "10022",
          "name": "山东省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12473",
                  "name": "杏花岭区",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12474",
                  "name": "小店区",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12475",
                  "name": "迎泽区",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12476",
                  "name": "尖草坪区",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12477",
                  "name": "万柏林区",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12478",
                  "name": "晋源区",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12479",
                  "name": "高新开发区",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12480",
                  "name": "民营经济开发区",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12481",
                  "name": "经济技术开发区",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12482",
                  "name": "清徐县",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12483",
                  "name": "阳曲县",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12484",
                  "name": "娄烦县",
                  "pCode": "10300"
                },
                {
                  "childrenList": null,
                  "code": "12485",
                  "name": "古交市",
                  "pCode": "10300"
                }
              ],
              "code": "10300",
              "name": "太原",
              "pCode": "10023"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12486",
                  "name": "城区",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12487",
                  "name": "郊区",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12488",
                  "name": "沁县",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12489",
                  "name": "潞城市",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12490",
                  "name": "长治县",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12491",
                  "name": "襄垣县",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12492",
                  "name": "屯留县",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12493",
                  "name": "平顺县",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12494",
                  "name": "黎城县",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12495",
                  "name": "壶关县",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12496",
                  "name": "长子县",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12497",
                  "name": "武乡县",
                  "pCode": "10301"
                },
                {
                  "childrenList": null,
                  "code": "12498",
                  "name": "沁源县",
                  "pCode": "10301"
                }
              ],
              "code": "10301",
              "name": "长治",
              "pCode": "10023"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12499",
                  "name": "城区",
                  "pCode": "10302"
                },
                {
                  "childrenList": null,
                  "code": "12500",
                  "name": "矿区",
                  "pCode": "10302"
                },
                {
                  "childrenList": null,
                  "code": "12501",
                  "name": "南郊区",
                  "pCode": "10302"
                },
                {
                  "childrenList": null,
                  "code": "12502",
                  "name": "新荣区",
                  "pCode": "10302"
                },
                {
                  "childrenList": null,
                  "code": "12503",
                  "name": "阳高县",
                  "pCode": "10302"
                },
                {
                  "childrenList": null,
                  "code": "12504",
                  "name": "天镇县",
                  "pCode": "10302"
                },
                {
                  "childrenList": null,
                  "code": "12505",
                  "name": "广灵县",
                  "pCode": "10302"
                },
                {
                  "childrenList": null,
                  "code": "12506",
                  "name": "灵丘县",
                  "pCode": "10302"
                },
                {
                  "childrenList": null,
                  "code": "12507",
                  "name": "浑源县",
                  "pCode": "10302"
                },
                {
                  "childrenList": null,
                  "code": "12509",
                  "name": "大同县",
                  "pCode": "10302"
                }
              ],
              "code": "10302",
              "name": "大同",
              "pCode": "10023"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12510",
                  "name": "城区",
                  "pCode": "10303"
                },
                {
                  "childrenList": null,
                  "code": "12511",
                  "name": "高平市",
                  "pCode": "10303"
                },
                {
                  "childrenList": null,
                  "code": "12512",
                  "name": "沁水县",
                  "pCode": "10303"
                },
                {
                  "childrenList": null,
                  "code": "12513",
                  "name": "阳城县",
                  "pCode": "10303"
                },
                {
                  "childrenList": null,
                  "code": "12514",
                  "name": "陵川县",
                  "pCode": "10303"
                },
                {
                  "childrenList": null,
                  "code": "12515",
                  "name": "泽州县",
                  "pCode": "10303"
                }
              ],
              "code": "10303",
              "name": "晋城",
              "pCode": "10023"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12516",
                  "name": "榆次区",
                  "pCode": "10304"
                },
                {
                  "childrenList": null,
                  "code": "12517",
                  "name": "介休市",
                  "pCode": "10304"
                },
                {
                  "childrenList": null,
                  "code": "12518",
                  "name": "榆社县",
                  "pCode": "10304"
                },
                {
                  "childrenList": null,
                  "code": "12519",
                  "name": "左权县",
                  "pCode": "10304"
                },
                {
                  "childrenList": null,
                  "code": "12520",
                  "name": "和顺县",
                  "pCode": "10304"
                },
                {
                  "childrenList": null,
                  "code": "12521",
                  "name": "昔阳县",
                  "pCode": "10304"
                },
                {
                  "childrenList": null,
                  "code": "12522",
                  "name": "寿阳县",
                  "pCode": "10304"
                },
                {
                  "childrenList": null,
                  "code": "12523",
                  "name": "太谷县",
                  "pCode": "10304"
                },
                {
                  "childrenList": null,
                  "code": "12524",
                  "name": "祁县",
                  "pCode": "10304"
                },
                {
                  "childrenList": null,
                  "code": "12525",
                  "name": "平遥县",
                  "pCode": "10304"
                },
                {
                  "childrenList": null,
                  "code": "12526",
                  "name": "灵石县",
                  "pCode": "10304"
                }
              ],
              "code": "10304",
              "name": "晋中",
              "pCode": "10023"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12527",
                  "name": "尧都区",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12528",
                  "name": "侯马市",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12529",
                  "name": "霍州市",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12530",
                  "name": "曲沃县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12531",
                  "name": "翼城县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12532",
                  "name": "襄汾县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12533",
                  "name": "洪洞县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12534",
                  "name": "吉县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12535",
                  "name": "安泽县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12536",
                  "name": "浮山县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12537",
                  "name": "古县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12538",
                  "name": "乡宁县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12539",
                  "name": "大宁县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12540",
                  "name": "隰县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12541",
                  "name": "永和县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12542",
                  "name": "蒲县",
                  "pCode": "10305"
                },
                {
                  "childrenList": null,
                  "code": "12543",
                  "name": "汾西县",
                  "pCode": "10305"
                }
              ],
              "code": "10305",
              "name": "临汾",
              "pCode": "10023"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12544",
                  "name": "离石市",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12545",
                  "name": "离石区",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12546",
                  "name": "孝义市",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12547",
                  "name": "汾阳市",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12548",
                  "name": "文水县",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12549",
                  "name": "交城县",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12550",
                  "name": "兴县",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12551",
                  "name": "临县",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12552",
                  "name": "柳林县",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12553",
                  "name": "石楼县",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12554",
                  "name": "岚县",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12555",
                  "name": "方山县",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12556",
                  "name": "中阳县",
                  "pCode": "10306"
                },
                {
                  "childrenList": null,
                  "code": "12557",
                  "name": "交口县",
                  "pCode": "10306"
                }
              ],
              "code": "10306",
              "name": "吕梁",
              "pCode": "10023"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12558",
                  "name": "朔城区",
                  "pCode": "10307"
                },
                {
                  "childrenList": null,
                  "code": "12559",
                  "name": "平鲁区",
                  "pCode": "10307"
                },
                {
                  "childrenList": null,
                  "code": "12560",
                  "name": "山阴县",
                  "pCode": "10307"
                },
                {
                  "childrenList": null,
                  "code": "12561",
                  "name": "应县",
                  "pCode": "10307"
                },
                {
                  "childrenList": null,
                  "code": "12562",
                  "name": "右玉县",
                  "pCode": "10307"
                },
                {
                  "childrenList": null,
                  "code": "12563",
                  "name": "怀仁县",
                  "pCode": "10307"
                }
              ],
              "code": "10307",
              "name": "朔州",
              "pCode": "10023"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12564",
                  "name": "忻府区",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12565",
                  "name": "原平市",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12566",
                  "name": "定襄县",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12567",
                  "name": "五台县",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12568",
                  "name": "代县",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12569",
                  "name": "繁峙县",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12570",
                  "name": "宁武县",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12571",
                  "name": "静乐县",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12572",
                  "name": "神池县",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12573",
                  "name": "五寨县",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12574",
                  "name": "岢岚县",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12575",
                  "name": "河曲县",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12576",
                  "name": "保德县",
                  "pCode": "10308"
                },
                {
                  "childrenList": null,
                  "code": "12577",
                  "name": "偏关县",
                  "pCode": "10308"
                }
              ],
              "code": "10308",
              "name": "忻州",
              "pCode": "10023"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12578",
                  "name": "城区",
                  "pCode": "10309"
                },
                {
                  "childrenList": null,
                  "code": "12579",
                  "name": "矿区",
                  "pCode": "10309"
                },
                {
                  "childrenList": null,
                  "code": "12580",
                  "name": "郊区",
                  "pCode": "10309"
                },
                {
                  "childrenList": null,
                  "code": "12581",
                  "name": "平定县",
                  "pCode": "10309"
                },
                {
                  "childrenList": null,
                  "code": "12582",
                  "name": "盂县",
                  "pCode": "10309"
                }
              ],
              "code": "10309",
              "name": "阳泉",
              "pCode": "10023"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12583",
                  "name": "盐湖区",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12584",
                  "name": "永济市",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12585",
                  "name": "河津市",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12586",
                  "name": "临猗县",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12587",
                  "name": "万荣县",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12588",
                  "name": "闻喜县",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12589",
                  "name": "稷山县",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12590",
                  "name": "新绛县",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12591",
                  "name": "绛县",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12592",
                  "name": "垣曲县",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12593",
                  "name": "夏县",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12594",
                  "name": "平陆县",
                  "pCode": "10310"
                },
                {
                  "childrenList": null,
                  "code": "12595",
                  "name": "芮城县",
                  "pCode": "10310"
                }
              ],
              "code": "10310",
              "name": "运城",
              "pCode": "10023"
            }
          ],
          "code": "10023",
          "name": "山西省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12596",
                  "name": "莲湖区",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12597",
                  "name": "新城区",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12598",
                  "name": "碑林区",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12599",
                  "name": "雁塔区",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12600",
                  "name": "灞桥区",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12601",
                  "name": "未央区",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12602",
                  "name": "阎良区",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12603",
                  "name": "临潼区",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12604",
                  "name": "长安区",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12605",
                  "name": "蓝田县",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12606",
                  "name": "周至县",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12607",
                  "name": "户县",
                  "pCode": "10311"
                },
                {
                  "childrenList": null,
                  "code": "12608",
                  "name": "高陵县",
                  "pCode": "10311"
                }
              ],
              "code": "10311",
              "name": "西安",
              "pCode": "10024"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12609",
                  "name": "汉滨区",
                  "pCode": "10312"
                },
                {
                  "childrenList": null,
                  "code": "12610",
                  "name": "汉阴县",
                  "pCode": "10312"
                },
                {
                  "childrenList": null,
                  "code": "12611",
                  "name": "石泉县",
                  "pCode": "10312"
                },
                {
                  "childrenList": null,
                  "code": "12612",
                  "name": "宁陕县",
                  "pCode": "10312"
                },
                {
                  "childrenList": null,
                  "code": "12613",
                  "name": "紫阳县",
                  "pCode": "10312"
                },
                {
                  "childrenList": null,
                  "code": "12614",
                  "name": "岚皋县",
                  "pCode": "10312"
                },
                {
                  "childrenList": null,
                  "code": "12615",
                  "name": "平利县",
                  "pCode": "10312"
                },
                {
                  "childrenList": null,
                  "code": "12616",
                  "name": "镇坪县",
                  "pCode": "10312"
                },
                {
                  "childrenList": null,
                  "code": "12617",
                  "name": "旬阳县",
                  "pCode": "10312"
                },
                {
                  "childrenList": null,
                  "code": "12618",
                  "name": "白河县",
                  "pCode": "10312"
                }
              ],
              "code": "10312",
              "name": "安康",
              "pCode": "10024"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12619",
                  "name": "陈仓区",
                  "pCode": "10313"
                },
                {
                  "childrenList": null,
                  "code": "12620",
                  "name": "渭滨区",
                  "pCode": "10313"
                },
                {
                  "childrenList": null,
                  "code": "12621",
                  "name": "金台区",
                  "pCode": "10313"
                },
                {
                  "childrenList": null,
                  "code": "12622",
                  "name": "凤翔县",
                  "pCode": "10313"
                },
                {
                  "childrenList": null,
                  "code": "12623",
                  "name": "岐山县",
                  "pCode": "10313"
                },
                {
                  "childrenList": null,
                  "code": "12624",
                  "name": "扶风县",
                  "pCode": "10313"
                },
                {
                  "childrenList": null,
                  "code": "12625",
                  "name": "眉县",
                  "pCode": "10313"
                },
                {
                  "childrenList": null,
                  "code": "12626",
                  "name": "陇县",
                  "pCode": "10313"
                },
                {
                  "childrenList": null,
                  "code": "12627",
                  "name": "千阳县",
                  "pCode": "10313"
                },
                {
                  "childrenList": null,
                  "code": "12628",
                  "name": "麟游县",
                  "pCode": "10313"
                },
                {
                  "childrenList": null,
                  "code": "12629",
                  "name": "凤县",
                  "pCode": "10313"
                },
                {
                  "childrenList": null,
                  "code": "12630",
                  "name": "太白县",
                  "pCode": "10313"
                }
              ],
              "code": "10313",
              "name": "宝鸡",
              "pCode": "10024"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12631",
                  "name": "汉台区",
                  "pCode": "10314"
                },
                {
                  "childrenList": null,
                  "code": "12632",
                  "name": "南郑县",
                  "pCode": "10314"
                },
                {
                  "childrenList": null,
                  "code": "12633",
                  "name": "城固县",
                  "pCode": "10314"
                },
                {
                  "childrenList": null,
                  "code": "12634",
                  "name": "洋县",
                  "pCode": "10314"
                },
                {
                  "childrenList": null,
                  "code": "12635",
                  "name": "西乡县",
                  "pCode": "10314"
                },
                {
                  "childrenList": null,
                  "code": "12636",
                  "name": "勉县",
                  "pCode": "10314"
                },
                {
                  "childrenList": null,
                  "code": "12637",
                  "name": "宁强县",
                  "pCode": "10314"
                },
                {
                  "childrenList": null,
                  "code": "12638",
                  "name": "略阳县",
                  "pCode": "10314"
                },
                {
                  "childrenList": null,
                  "code": "12639",
                  "name": "镇巴县",
                  "pCode": "10314"
                },
                {
                  "childrenList": null,
                  "code": "12640",
                  "name": "留坝县",
                  "pCode": "10314"
                },
                {
                  "childrenList": null,
                  "code": "12641",
                  "name": "佛坪县",
                  "pCode": "10314"
                }
              ],
              "code": "10314",
              "name": "汉中",
              "pCode": "10024"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12642",
                  "name": "商州区",
                  "pCode": "10315"
                },
                {
                  "childrenList": null,
                  "code": "12643",
                  "name": "洛南县",
                  "pCode": "10315"
                },
                {
                  "childrenList": null,
                  "code": "12644",
                  "name": "丹凤县",
                  "pCode": "10315"
                },
                {
                  "childrenList": null,
                  "code": "12645",
                  "name": "商南县",
                  "pCode": "10315"
                },
                {
                  "childrenList": null,
                  "code": "12646",
                  "name": "山阳县",
                  "pCode": "10315"
                },
                {
                  "childrenList": null,
                  "code": "12647",
                  "name": "镇安县",
                  "pCode": "10315"
                },
                {
                  "childrenList": null,
                  "code": "12648",
                  "name": "柞水县",
                  "pCode": "10315"
                }
              ],
              "code": "10315",
              "name": "商洛",
              "pCode": "10024"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12649",
                  "name": "耀州区",
                  "pCode": "10316"
                },
                {
                  "childrenList": null,
                  "code": "12650",
                  "name": "王益区",
                  "pCode": "10316"
                },
                {
                  "childrenList": null,
                  "code": "12651",
                  "name": "印台区",
                  "pCode": "10316"
                },
                {
                  "childrenList": null,
                  "code": "12652",
                  "name": "宜君县",
                  "pCode": "10316"
                }
              ],
              "code": "10316",
              "name": "铜川",
              "pCode": "10024"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12653",
                  "name": "临渭区",
                  "pCode": "10317"
                },
                {
                  "childrenList": null,
                  "code": "12654",
                  "name": "韩城市",
                  "pCode": "10317"
                },
                {
                  "childrenList": null,
                  "code": "12655",
                  "name": "华阴市",
                  "pCode": "10317"
                },
                {
                  "childrenList": null,
                  "code": "12656",
                  "name": "华县",
                  "pCode": "10317"
                },
                {
                  "childrenList": null,
                  "code": "12657",
                  "name": "潼关县",
                  "pCode": "10317"
                },
                {
                  "childrenList": null,
                  "code": "12658",
                  "name": "大荔县",
                  "pCode": "10317"
                },
                {
                  "childrenList": null,
                  "code": "12659",
                  "name": "合阳县",
                  "pCode": "10317"
                },
                {
                  "childrenList": null,
                  "code": "12660",
                  "name": "澄城县",
                  "pCode": "10317"
                },
                {
                  "childrenList": null,
                  "code": "12661",
                  "name": "蒲城县",
                  "pCode": "10317"
                },
                {
                  "childrenList": null,
                  "code": "12662",
                  "name": "白水县",
                  "pCode": "10317"
                },
                {
                  "childrenList": null,
                  "code": "12663",
                  "name": "富平县",
                  "pCode": "10317"
                }
              ],
              "code": "10317",
              "name": "渭南",
              "pCode": "10024"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12664",
                  "name": "秦都区",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12665",
                  "name": "渭城区",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12666",
                  "name": "杨陵区",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12667",
                  "name": "兴平市",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12668",
                  "name": "三原县",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12669",
                  "name": "泾阳县",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12670",
                  "name": "乾县",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12671",
                  "name": "礼泉县",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12672",
                  "name": "永寿县",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12673",
                  "name": "彬县",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12674",
                  "name": "长武县",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12675",
                  "name": "旬邑县",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12676",
                  "name": "淳化县",
                  "pCode": "10318"
                },
                {
                  "childrenList": null,
                  "code": "12677",
                  "name": "武功县",
                  "pCode": "10318"
                }
              ],
              "code": "10318",
              "name": "咸阳",
              "pCode": "10024"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12678",
                  "name": "吴起县",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12679",
                  "name": "宝塔区",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12680",
                  "name": "延长县",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12681",
                  "name": "延川县",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12682",
                  "name": "子长县",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12683",
                  "name": "安塞县",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12684",
                  "name": "志丹县",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12685",
                  "name": "甘泉县",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12686",
                  "name": "富县",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12687",
                  "name": "洛川县",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12688",
                  "name": "宜川县",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12689",
                  "name": "黄龙县",
                  "pCode": "10319"
                },
                {
                  "childrenList": null,
                  "code": "12690",
                  "name": "黄陵县",
                  "pCode": "10319"
                }
              ],
              "code": "10319",
              "name": "延安",
              "pCode": "10024"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12691",
                  "name": "榆阳区",
                  "pCode": "10320"
                },
                {
                  "childrenList": null,
                  "code": "12692",
                  "name": "神木县",
                  "pCode": "10320"
                },
                {
                  "childrenList": null,
                  "code": "12693",
                  "name": "府谷县",
                  "pCode": "10320"
                },
                {
                  "childrenList": null,
                  "code": "12694",
                  "name": "横山县",
                  "pCode": "10320"
                },
                {
                  "childrenList": null,
                  "code": "12695",
                  "name": "靖边县",
                  "pCode": "10320"
                },
                {
                  "childrenList": null,
                  "code": "12696",
                  "name": "定边县",
                  "pCode": "10320"
                },
                {
                  "childrenList": null,
                  "code": "12697",
                  "name": "绥德县",
                  "pCode": "10320"
                },
                {
                  "childrenList": null,
                  "code": "12698",
                  "name": "米脂县",
                  "pCode": "10320"
                },
                {
                  "childrenList": null,
                  "code": "12699",
                  "name": "佳县",
                  "pCode": "10320"
                },
                {
                  "childrenList": null,
                  "code": "12700",
                  "name": "吴堡县",
                  "pCode": "10320"
                },
                {
                  "childrenList": null,
                  "code": "12701",
                  "name": "清涧县",
                  "pCode": "10320"
                },
                {
                  "childrenList": null,
                  "code": "12702",
                  "name": "子洲县",
                  "pCode": "10320"
                }
              ],
              "code": "10320",
              "name": "榆林",
              "pCode": "10024"
            }
          ],
          "code": "10024",
          "name": "陕西省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12703",
                  "name": "长宁区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12704",
                  "name": "闸北区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12705",
                  "name": "闵行区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12706",
                  "name": "徐汇区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12707",
                  "name": "浦东新区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12708",
                  "name": "杨浦区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12709",
                  "name": "普陀区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12710",
                  "name": "静安区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12711",
                  "name": "卢湾区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12712",
                  "name": "虹口区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12713",
                  "name": "黄浦区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12714",
                  "name": "南汇区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12715",
                  "name": "松江区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12716",
                  "name": "嘉定区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12717",
                  "name": "宝山区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12718",
                  "name": "青浦区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12719",
                  "name": "金山区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12720",
                  "name": "奉贤区",
                  "pCode": "10321"
                },
                {
                  "childrenList": null,
                  "code": "12721",
                  "name": "崇明县",
                  "pCode": "10321"
                }
              ],
              "code": "10321",
              "name": "上海",
              "pCode": "10025"
            }
          ],
          "code": "10025",
          "name": "上海市",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12722",
                  "name": "青羊区",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12723",
                  "name": "锦江区",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12724",
                  "name": "金牛区",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12725",
                  "name": "武侯区",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12726",
                  "name": "成华区",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12727",
                  "name": "龙泉驿区",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12728",
                  "name": "青白江区",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12729",
                  "name": "新都区",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12730",
                  "name": "温江区",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12731",
                  "name": "高新区",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12732",
                  "name": "高新西区",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12733",
                  "name": "都江堰市",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12734",
                  "name": "彭州市",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12735",
                  "name": "邛崃市",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12736",
                  "name": "崇州市",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12737",
                  "name": "金堂县",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12738",
                  "name": "双流县",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12739",
                  "name": "郫县",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12740",
                  "name": "大邑县",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12741",
                  "name": "蒲江县",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12742",
                  "name": "新津县",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12743",
                  "name": "都江堰市",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12744",
                  "name": "彭州市",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12745",
                  "name": "邛崃市",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12746",
                  "name": "崇州市",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12747",
                  "name": "金堂县",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12748",
                  "name": "双流县",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12749",
                  "name": "郫县",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12750",
                  "name": "大邑县",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12751",
                  "name": "蒲江县",
                  "pCode": "10322"
                },
                {
                  "childrenList": null,
                  "code": "12752",
                  "name": "新津县",
                  "pCode": "10322"
                }
              ],
              "code": "10322",
              "name": "成都",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12753",
                  "name": "涪城区",
                  "pCode": "10323"
                },
                {
                  "childrenList": null,
                  "code": "12754",
                  "name": "游仙区",
                  "pCode": "10323"
                },
                {
                  "childrenList": null,
                  "code": "12755",
                  "name": "江油市",
                  "pCode": "10323"
                },
                {
                  "childrenList": null,
                  "code": "12756",
                  "name": "盐亭县",
                  "pCode": "10323"
                },
                {
                  "childrenList": null,
                  "code": "12757",
                  "name": "三台县",
                  "pCode": "10323"
                },
                {
                  "childrenList": null,
                  "code": "12758",
                  "name": "平武县",
                  "pCode": "10323"
                },
                {
                  "childrenList": null,
                  "code": "12759",
                  "name": "安县",
                  "pCode": "10323"
                },
                {
                  "childrenList": null,
                  "code": "12760",
                  "name": "梓潼县",
                  "pCode": "10323"
                },
                {
                  "childrenList": null,
                  "code": "12761",
                  "name": "北川县",
                  "pCode": "10323"
                }
              ],
              "code": "10323",
              "name": "绵阳",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12762",
                  "name": "马尔康县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12763",
                  "name": "汶川县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12764",
                  "name": "理县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12765",
                  "name": "茂县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12766",
                  "name": "松潘县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12767",
                  "name": "九寨沟县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12768",
                  "name": "金川县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12769",
                  "name": "小金县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12770",
                  "name": "黑水县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12771",
                  "name": "壤塘县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12772",
                  "name": "阿坝县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12773",
                  "name": "若尔盖县",
                  "pCode": "10324"
                },
                {
                  "childrenList": null,
                  "code": "12774",
                  "name": "红原县",
                  "pCode": "10324"
                }
              ],
              "code": "10324",
              "name": "阿坝",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12775",
                  "name": "巴州区",
                  "pCode": "10325"
                },
                {
                  "childrenList": null,
                  "code": "12776",
                  "name": "通江县",
                  "pCode": "10325"
                },
                {
                  "childrenList": null,
                  "code": "12777",
                  "name": "南江县",
                  "pCode": "10325"
                },
                {
                  "childrenList": null,
                  "code": "12778",
                  "name": "平昌县",
                  "pCode": "10325"
                }
              ],
              "code": "10325",
              "name": "巴中",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12779",
                  "name": "通川区",
                  "pCode": "10326"
                },
                {
                  "childrenList": null,
                  "code": "12780",
                  "name": "万源市",
                  "pCode": "10326"
                },
                {
                  "childrenList": null,
                  "code": "12781",
                  "name": "达县",
                  "pCode": "10326"
                },
                {
                  "childrenList": null,
                  "code": "12782",
                  "name": "宣汉县",
                  "pCode": "10326"
                },
                {
                  "childrenList": null,
                  "code": "12783",
                  "name": "开江县",
                  "pCode": "10326"
                },
                {
                  "childrenList": null,
                  "code": "12784",
                  "name": "大竹县",
                  "pCode": "10326"
                },
                {
                  "childrenList": null,
                  "code": "12785",
                  "name": "渠县",
                  "pCode": "10326"
                }
              ],
              "code": "10326",
              "name": "达州",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12786",
                  "name": "旌阳区",
                  "pCode": "10327"
                },
                {
                  "childrenList": null,
                  "code": "12787",
                  "name": "广汉市",
                  "pCode": "10327"
                },
                {
                  "childrenList": null,
                  "code": "12788",
                  "name": "什邡市",
                  "pCode": "10327"
                },
                {
                  "childrenList": null,
                  "code": "12789",
                  "name": "绵竹市",
                  "pCode": "10327"
                },
                {
                  "childrenList": null,
                  "code": "12790",
                  "name": "罗江县",
                  "pCode": "10327"
                },
                {
                  "childrenList": null,
                  "code": "12791",
                  "name": "中江县",
                  "pCode": "10327"
                }
              ],
              "code": "10327",
              "name": "德阳",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12792",
                  "name": "康定县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12793",
                  "name": "丹巴县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12794",
                  "name": "泸定县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12795",
                  "name": "炉霍县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12796",
                  "name": "九龙县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12797",
                  "name": "甘孜县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12798",
                  "name": "雅江县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12799",
                  "name": "新龙县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12800",
                  "name": "道孚县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12801",
                  "name": "白玉县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12802",
                  "name": "理塘县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12803",
                  "name": "德格县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12804",
                  "name": "乡城县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12805",
                  "name": "石渠县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12806",
                  "name": "稻城县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12807",
                  "name": "色达县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12808",
                  "name": "巴塘县",
                  "pCode": "10328"
                },
                {
                  "childrenList": null,
                  "code": "12809",
                  "name": "得荣县",
                  "pCode": "10328"
                }
              ],
              "code": "10328",
              "name": "甘孜",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12810",
                  "name": "广安区",
                  "pCode": "10329"
                },
                {
                  "childrenList": null,
                  "code": "12811",
                  "name": "华蓥市",
                  "pCode": "10329"
                },
                {
                  "childrenList": null,
                  "code": "12812",
                  "name": "岳池县",
                  "pCode": "10329"
                },
                {
                  "childrenList": null,
                  "code": "12813",
                  "name": "武胜县",
                  "pCode": "10329"
                },
                {
                  "childrenList": null,
                  "code": "12814",
                  "name": "邻水县",
                  "pCode": "10329"
                }
              ],
              "code": "10329",
              "name": "广安",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12815",
                  "name": "利州区",
                  "pCode": "10330"
                },
                {
                  "childrenList": null,
                  "code": "12816",
                  "name": "元坝区",
                  "pCode": "10330"
                },
                {
                  "childrenList": null,
                  "code": "12817",
                  "name": "朝天区",
                  "pCode": "10330"
                },
                {
                  "childrenList": null,
                  "code": "12818",
                  "name": "旺苍县",
                  "pCode": "10330"
                },
                {
                  "childrenList": null,
                  "code": "12819",
                  "name": "青川县",
                  "pCode": "10330"
                },
                {
                  "childrenList": null,
                  "code": "12820",
                  "name": "剑阁县",
                  "pCode": "10330"
                },
                {
                  "childrenList": null,
                  "code": "12821",
                  "name": "苍溪县",
                  "pCode": "10330"
                }
              ],
              "code": "10330",
              "name": "广元",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12822",
                  "name": "峨眉山市",
                  "pCode": "10331"
                },
                {
                  "childrenList": null,
                  "code": "12823",
                  "name": "乐山市",
                  "pCode": "10331"
                },
                {
                  "childrenList": null,
                  "code": "12824",
                  "name": "犍为县",
                  "pCode": "10331"
                },
                {
                  "childrenList": null,
                  "code": "12825",
                  "name": "井研县",
                  "pCode": "10331"
                },
                {
                  "childrenList": null,
                  "code": "12826",
                  "name": "夹江县",
                  "pCode": "10331"
                },
                {
                  "childrenList": null,
                  "code": "12827",
                  "name": "沐川县",
                  "pCode": "10331"
                },
                {
                  "childrenList": null,
                  "code": "12828",
                  "name": "峨边",
                  "pCode": "10331"
                },
                {
                  "childrenList": null,
                  "code": "12829",
                  "name": "马边",
                  "pCode": "10331"
                }
              ],
              "code": "10331",
              "name": "乐山",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12830",
                  "name": "西昌市",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12831",
                  "name": "盐源县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12832",
                  "name": "德昌县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12833",
                  "name": "会理县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12834",
                  "name": "会东县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12835",
                  "name": "宁南县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12836",
                  "name": "普格县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12837",
                  "name": "布拖县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12838",
                  "name": "金阳县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12839",
                  "name": "昭觉县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12840",
                  "name": "喜德县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12841",
                  "name": "冕宁县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12842",
                  "name": "越西县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12843",
                  "name": "甘洛县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12844",
                  "name": "美姑县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12845",
                  "name": "雷波县",
                  "pCode": "10332"
                },
                {
                  "childrenList": null,
                  "code": "12846",
                  "name": "木里",
                  "pCode": "10332"
                }
              ],
              "code": "10332",
              "name": "凉山",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12847",
                  "name": "东坡区",
                  "pCode": "10333"
                },
                {
                  "childrenList": null,
                  "code": "12848",
                  "name": "仁寿县",
                  "pCode": "10333"
                },
                {
                  "childrenList": null,
                  "code": "12849",
                  "name": "彭山县",
                  "pCode": "10333"
                },
                {
                  "childrenList": null,
                  "code": "12850",
                  "name": "洪雅县",
                  "pCode": "10333"
                },
                {
                  "childrenList": null,
                  "code": "12851",
                  "name": "丹棱县",
                  "pCode": "10333"
                },
                {
                  "childrenList": null,
                  "code": "12852",
                  "name": "青神县",
                  "pCode": "10333"
                }
              ],
              "code": "10333",
              "name": "眉山",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12853",
                  "name": "阆中市",
                  "pCode": "10334"
                },
                {
                  "childrenList": null,
                  "code": "12854",
                  "name": "南部县",
                  "pCode": "10334"
                },
                {
                  "childrenList": null,
                  "code": "12855",
                  "name": "营山县",
                  "pCode": "10334"
                },
                {
                  "childrenList": null,
                  "code": "12856",
                  "name": "蓬安县",
                  "pCode": "10334"
                },
                {
                  "childrenList": null,
                  "code": "12857",
                  "name": "仪陇县",
                  "pCode": "10334"
                },
                {
                  "childrenList": null,
                  "code": "12858",
                  "name": "顺庆区",
                  "pCode": "10334"
                },
                {
                  "childrenList": null,
                  "code": "12859",
                  "name": "高坪区",
                  "pCode": "10334"
                },
                {
                  "childrenList": null,
                  "code": "12860",
                  "name": "嘉陵区",
                  "pCode": "10334"
                },
                {
                  "childrenList": null,
                  "code": "12861",
                  "name": "西充县",
                  "pCode": "10334"
                }
              ],
              "code": "10334",
              "name": "南充",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12862",
                  "name": "市中区",
                  "pCode": "10335"
                },
                {
                  "childrenList": null,
                  "code": "12863",
                  "name": "东兴区",
                  "pCode": "10335"
                },
                {
                  "childrenList": null,
                  "code": "12864",
                  "name": "威远县",
                  "pCode": "10335"
                },
                {
                  "childrenList": null,
                  "code": "12865",
                  "name": "资中县",
                  "pCode": "10335"
                },
                {
                  "childrenList": null,
                  "code": "12866",
                  "name": "隆昌县",
                  "pCode": "10335"
                }
              ],
              "code": "10335",
              "name": "内江",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12867",
                  "name": "东  区",
                  "pCode": "10336"
                },
                {
                  "childrenList": null,
                  "code": "12868",
                  "name": "西  区",
                  "pCode": "10336"
                },
                {
                  "childrenList": null,
                  "code": "12869",
                  "name": "仁和区",
                  "pCode": "10336"
                },
                {
                  "childrenList": null,
                  "code": "12870",
                  "name": "米易县",
                  "pCode": "10336"
                },
                {
                  "childrenList": null,
                  "code": "12871",
                  "name": "盐边县",
                  "pCode": "10336"
                }
              ],
              "code": "10336",
              "name": "攀枝花",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12872",
                  "name": "船山区",
                  "pCode": "10337"
                },
                {
                  "childrenList": null,
                  "code": "12873",
                  "name": "安居区",
                  "pCode": "10337"
                },
                {
                  "childrenList": null,
                  "code": "12874",
                  "name": "蓬溪县",
                  "pCode": "10337"
                },
                {
                  "childrenList": null,
                  "code": "12875",
                  "name": "射洪县",
                  "pCode": "10337"
                },
                {
                  "childrenList": null,
                  "code": "12876",
                  "name": "大英县",
                  "pCode": "10337"
                }
              ],
              "code": "10337",
              "name": "遂宁",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12877",
                  "name": "雨城区",
                  "pCode": "10338"
                },
                {
                  "childrenList": null,
                  "code": "12878",
                  "name": "名山县",
                  "pCode": "10338"
                },
                {
                  "childrenList": null,
                  "code": "12879",
                  "name": "荥经县",
                  "pCode": "10338"
                },
                {
                  "childrenList": null,
                  "code": "12880",
                  "name": "汉源县",
                  "pCode": "10338"
                },
                {
                  "childrenList": null,
                  "code": "12881",
                  "name": "石棉县",
                  "pCode": "10338"
                },
                {
                  "childrenList": null,
                  "code": "12882",
                  "name": "天全县",
                  "pCode": "10338"
                },
                {
                  "childrenList": null,
                  "code": "12883",
                  "name": "芦山县",
                  "pCode": "10338"
                },
                {
                  "childrenList": null,
                  "code": "12884",
                  "name": "宝兴县",
                  "pCode": "10338"
                }
              ],
              "code": "10338",
              "name": "雅安",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12885",
                  "name": "翠屏区",
                  "pCode": "10339"
                },
                {
                  "childrenList": null,
                  "code": "12886",
                  "name": "宜宾县",
                  "pCode": "10339"
                },
                {
                  "childrenList": null,
                  "code": "12887",
                  "name": "南溪县",
                  "pCode": "10339"
                },
                {
                  "childrenList": null,
                  "code": "12888",
                  "name": "江安县",
                  "pCode": "10339"
                },
                {
                  "childrenList": null,
                  "code": "12889",
                  "name": "长宁县",
                  "pCode": "10339"
                },
                {
                  "childrenList": null,
                  "code": "12890",
                  "name": "高县",
                  "pCode": "10339"
                },
                {
                  "childrenList": null,
                  "code": "12891",
                  "name": "珙县",
                  "pCode": "10339"
                },
                {
                  "childrenList": null,
                  "code": "12892",
                  "name": "筠连县",
                  "pCode": "10339"
                },
                {
                  "childrenList": null,
                  "code": "12893",
                  "name": "兴文县",
                  "pCode": "10339"
                },
                {
                  "childrenList": null,
                  "code": "12894",
                  "name": "屏山县",
                  "pCode": "10339"
                }
              ],
              "code": "10339",
              "name": "宜宾",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12895",
                  "name": "雁江区",
                  "pCode": "10340"
                },
                {
                  "childrenList": null,
                  "code": "12896",
                  "name": "简阳市",
                  "pCode": "10340"
                },
                {
                  "childrenList": null,
                  "code": "12897",
                  "name": "安岳县",
                  "pCode": "10340"
                },
                {
                  "childrenList": null,
                  "code": "12898",
                  "name": "乐至县",
                  "pCode": "10340"
                }
              ],
              "code": "10340",
              "name": "资阳",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12899",
                  "name": "大安区",
                  "pCode": "10341"
                },
                {
                  "childrenList": null,
                  "code": "12900",
                  "name": "自流井区",
                  "pCode": "10341"
                },
                {
                  "childrenList": null,
                  "code": "12901",
                  "name": "贡井区",
                  "pCode": "10341"
                },
                {
                  "childrenList": null,
                  "code": "12902",
                  "name": "沿滩区",
                  "pCode": "10341"
                },
                {
                  "childrenList": null,
                  "code": "12903",
                  "name": "荣县",
                  "pCode": "10341"
                },
                {
                  "childrenList": null,
                  "code": "12904",
                  "name": "富顺县",
                  "pCode": "10341"
                }
              ],
              "code": "10341",
              "name": "自贡",
              "pCode": "10026"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12905",
                  "name": "江阳区",
                  "pCode": "10342"
                },
                {
                  "childrenList": null,
                  "code": "12906",
                  "name": "纳溪区",
                  "pCode": "10342"
                },
                {
                  "childrenList": null,
                  "code": "12907",
                  "name": "龙马潭区",
                  "pCode": "10342"
                },
                {
                  "childrenList": null,
                  "code": "12908",
                  "name": "泸县",
                  "pCode": "10342"
                },
                {
                  "childrenList": null,
                  "code": "12909",
                  "name": "合江县",
                  "pCode": "10342"
                },
                {
                  "childrenList": null,
                  "code": "12910",
                  "name": "叙永县",
                  "pCode": "10342"
                },
                {
                  "childrenList": null,
                  "code": "12911",
                  "name": "古蔺县",
                  "pCode": "10342"
                }
              ],
              "code": "10342",
              "name": "泸州",
              "pCode": "10026"
            }
          ],
          "code": "10026",
          "name": "四川省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12912",
                  "name": "和平区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12913",
                  "name": "河西区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12914",
                  "name": "南开区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12915",
                  "name": "河北区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12916",
                  "name": "河东区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12917",
                  "name": "红桥区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12918",
                  "name": "东丽区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12919",
                  "name": "津南区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12920",
                  "name": "西青区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12921",
                  "name": "北辰区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12922",
                  "name": "塘沽区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12923",
                  "name": "汉沽区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12924",
                  "name": "大港区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12925",
                  "name": "武清区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12926",
                  "name": "宝坻区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12927",
                  "name": "经济开发区",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12928",
                  "name": "宁河县",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12929",
                  "name": "静海县",
                  "pCode": "10343"
                },
                {
                  "childrenList": null,
                  "code": "12930",
                  "name": "蓟县",
                  "pCode": "10343"
                }
              ],
              "code": "10343",
              "name": "天津",
              "pCode": "10027"
            }
          ],
          "code": "10027",
          "name": "天津市",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12931",
                  "name": "城关区",
                  "pCode": "10344"
                },
                {
                  "childrenList": null,
                  "code": "12932",
                  "name": "林周县",
                  "pCode": "10344"
                },
                {
                  "childrenList": null,
                  "code": "12933",
                  "name": "当雄县",
                  "pCode": "10344"
                },
                {
                  "childrenList": null,
                  "code": "12934",
                  "name": "尼木县",
                  "pCode": "10344"
                },
                {
                  "childrenList": null,
                  "code": "12935",
                  "name": "曲水县",
                  "pCode": "10344"
                },
                {
                  "childrenList": null,
                  "code": "12936",
                  "name": "堆龙德庆县",
                  "pCode": "10344"
                },
                {
                  "childrenList": null,
                  "code": "12937",
                  "name": "达孜县",
                  "pCode": "10344"
                },
                {
                  "childrenList": null,
                  "code": "12938",
                  "name": "墨竹工卡县",
                  "pCode": "10344"
                }
              ],
              "code": "10344",
              "name": "拉萨",
              "pCode": "10028"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12939",
                  "name": "噶尔县",
                  "pCode": "10345"
                },
                {
                  "childrenList": null,
                  "code": "12940",
                  "name": "普兰县",
                  "pCode": "10345"
                },
                {
                  "childrenList": null,
                  "code": "12941",
                  "name": "札达县",
                  "pCode": "10345"
                },
                {
                  "childrenList": null,
                  "code": "12942",
                  "name": "日土县",
                  "pCode": "10345"
                },
                {
                  "childrenList": null,
                  "code": "12943",
                  "name": "革吉县",
                  "pCode": "10345"
                },
                {
                  "childrenList": null,
                  "code": "12944",
                  "name": "改则县",
                  "pCode": "10345"
                },
                {
                  "childrenList": null,
                  "code": "12945",
                  "name": "措勤县",
                  "pCode": "10345"
                }
              ],
              "code": "10345",
              "name": "阿里",
              "pCode": "10028"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12946",
                  "name": "昌都县",
                  "pCode": "10346"
                },
                {
                  "childrenList": null,
                  "code": "12947",
                  "name": "江达县",
                  "pCode": "10346"
                },
                {
                  "childrenList": null,
                  "code": "12948",
                  "name": "贡觉县",
                  "pCode": "10346"
                },
                {
                  "childrenList": null,
                  "code": "12949",
                  "name": "类乌齐县",
                  "pCode": "10346"
                },
                {
                  "childrenList": null,
                  "code": "12950",
                  "name": "丁青县",
                  "pCode": "10346"
                },
                {
                  "childrenList": null,
                  "code": "12951",
                  "name": "察雅县",
                  "pCode": "10346"
                },
                {
                  "childrenList": null,
                  "code": "12952",
                  "name": "八宿县",
                  "pCode": "10346"
                },
                {
                  "childrenList": null,
                  "code": "12953",
                  "name": "左贡县",
                  "pCode": "10346"
                },
                {
                  "childrenList": null,
                  "code": "12954",
                  "name": "芒康县",
                  "pCode": "10346"
                },
                {
                  "childrenList": null,
                  "code": "12955",
                  "name": "洛隆县",
                  "pCode": "10346"
                },
                {
                  "childrenList": null,
                  "code": "12956",
                  "name": "边坝县",
                  "pCode": "10346"
                }
              ],
              "code": "10346",
              "name": "昌都",
              "pCode": "10028"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12957",
                  "name": "林芝县",
                  "pCode": "10347"
                },
                {
                  "childrenList": null,
                  "code": "12958",
                  "name": "工布江达县",
                  "pCode": "10347"
                },
                {
                  "childrenList": null,
                  "code": "12959",
                  "name": "米林县",
                  "pCode": "10347"
                },
                {
                  "childrenList": null,
                  "code": "12960",
                  "name": "墨脱县",
                  "pCode": "10347"
                },
                {
                  "childrenList": null,
                  "code": "12961",
                  "name": "波密县",
                  "pCode": "10347"
                },
                {
                  "childrenList": null,
                  "code": "12962",
                  "name": "察隅县",
                  "pCode": "10347"
                },
                {
                  "childrenList": null,
                  "code": "12963",
                  "name": "朗县",
                  "pCode": "10347"
                }
              ],
              "code": "10347",
              "name": "林芝",
              "pCode": "10028"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12964",
                  "name": "那曲县",
                  "pCode": "10348"
                },
                {
                  "childrenList": null,
                  "code": "12965",
                  "name": "嘉黎县",
                  "pCode": "10348"
                },
                {
                  "childrenList": null,
                  "code": "12966",
                  "name": "比如县",
                  "pCode": "10348"
                },
                {
                  "childrenList": null,
                  "code": "12967",
                  "name": "聂荣县",
                  "pCode": "10348"
                },
                {
                  "childrenList": null,
                  "code": "12968",
                  "name": "安多县",
                  "pCode": "10348"
                },
                {
                  "childrenList": null,
                  "code": "12969",
                  "name": "申扎县",
                  "pCode": "10348"
                },
                {
                  "childrenList": null,
                  "code": "12970",
                  "name": "索县",
                  "pCode": "10348"
                },
                {
                  "childrenList": null,
                  "code": "12971",
                  "name": "班戈县",
                  "pCode": "10348"
                },
                {
                  "childrenList": null,
                  "code": "12972",
                  "name": "巴青县",
                  "pCode": "10348"
                },
                {
                  "childrenList": null,
                  "code": "12973",
                  "name": "尼玛县",
                  "pCode": "10348"
                }
              ],
              "code": "10348",
              "name": "那曲",
              "pCode": "10028"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12974",
                  "name": "日喀则市",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12975",
                  "name": "南木林县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12976",
                  "name": "江孜县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12977",
                  "name": "定日县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12978",
                  "name": "萨迦县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12979",
                  "name": "拉孜县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12980",
                  "name": "昂仁县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12981",
                  "name": "谢通门县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12982",
                  "name": "白朗县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12983",
                  "name": "仁布县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12984",
                  "name": "康马县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12985",
                  "name": "定结县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12986",
                  "name": "仲巴县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12987",
                  "name": "亚东县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12988",
                  "name": "吉隆县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12989",
                  "name": "聂拉木县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12990",
                  "name": "萨嘎县",
                  "pCode": "10349"
                },
                {
                  "childrenList": null,
                  "code": "12991",
                  "name": "岗巴县",
                  "pCode": "10349"
                }
              ],
              "code": "10349",
              "name": "日喀则",
              "pCode": "10028"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "12992",
                  "name": "乃东县",
                  "pCode": "10350"
                },
                {
                  "childrenList": null,
                  "code": "12993",
                  "name": "扎囊县",
                  "pCode": "10350"
                },
                {
                  "childrenList": null,
                  "code": "12994",
                  "name": "贡嘎县",
                  "pCode": "10350"
                },
                {
                  "childrenList": null,
                  "code": "12995",
                  "name": "桑日县",
                  "pCode": "10350"
                },
                {
                  "childrenList": null,
                  "code": "12996",
                  "name": "琼结县",
                  "pCode": "10350"
                },
                {
                  "childrenList": null,
                  "code": "12997",
                  "name": "曲松县",
                  "pCode": "10350"
                },
                {
                  "childrenList": null,
                  "code": "12998",
                  "name": "措美县",
                  "pCode": "10350"
                },
                {
                  "childrenList": null,
                  "code": "12999",
                  "name": "洛扎县",
                  "pCode": "10350"
                },
                {
                  "childrenList": null,
                  "code": "13000",
                  "name": "加查县",
                  "pCode": "10350"
                },
                {
                  "childrenList": null,
                  "code": "13001",
                  "name": "隆子县",
                  "pCode": "10350"
                },
                {
                  "childrenList": null,
                  "code": "13002",
                  "name": "错那县",
                  "pCode": "10350"
                },
                {
                  "childrenList": null,
                  "code": "13003",
                  "name": "浪卡子县",
                  "pCode": "10350"
                }
              ],
              "code": "10350",
              "name": "山南",
              "pCode": "10028"
            }
          ],
          "code": "10028",
          "name": "西藏自治区",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13004",
                  "name": "天山区",
                  "pCode": "10351"
                },
                {
                  "childrenList": null,
                  "code": "13005",
                  "name": "沙依巴克区",
                  "pCode": "10351"
                },
                {
                  "childrenList": null,
                  "code": "13006",
                  "name": "新市区",
                  "pCode": "10351"
                },
                {
                  "childrenList": null,
                  "code": "13007",
                  "name": "水磨沟区",
                  "pCode": "10351"
                },
                {
                  "childrenList": null,
                  "code": "13008",
                  "name": "头屯河区",
                  "pCode": "10351"
                },
                {
                  "childrenList": null,
                  "code": "13009",
                  "name": "达坂城区",
                  "pCode": "10351"
                },
                {
                  "childrenList": null,
                  "code": "13010",
                  "name": "米东区",
                  "pCode": "10351"
                },
                {
                  "childrenList": null,
                  "code": "13011",
                  "name": "乌鲁木齐县",
                  "pCode": "10351"
                }
              ],
              "code": "10351",
              "name": "乌鲁木齐",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13012",
                  "name": "阿克苏市",
                  "pCode": "10352"
                },
                {
                  "childrenList": null,
                  "code": "13013",
                  "name": "温宿县",
                  "pCode": "10352"
                },
                {
                  "childrenList": null,
                  "code": "13014",
                  "name": "库车县",
                  "pCode": "10352"
                },
                {
                  "childrenList": null,
                  "code": "13015",
                  "name": "沙雅县",
                  "pCode": "10352"
                },
                {
                  "childrenList": null,
                  "code": "13016",
                  "name": "新和县",
                  "pCode": "10352"
                },
                {
                  "childrenList": null,
                  "code": "13017",
                  "name": "拜城县",
                  "pCode": "10352"
                },
                {
                  "childrenList": null,
                  "code": "13018",
                  "name": "乌什县",
                  "pCode": "10352"
                },
                {
                  "childrenList": null,
                  "code": "13019",
                  "name": "阿瓦提县",
                  "pCode": "10352"
                },
                {
                  "childrenList": null,
                  "code": "13020",
                  "name": "柯坪县",
                  "pCode": "10352"
                }
              ],
              "code": "10352",
              "name": "阿克苏",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13021",
                  "name": "阿拉尔市",
                  "pCode": "10353"
                }
              ],
              "code": "10353",
              "name": "阿拉尔",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13022",
                  "name": "库尔勒市",
                  "pCode": "10354"
                },
                {
                  "childrenList": null,
                  "code": "13023",
                  "name": "轮台县",
                  "pCode": "10354"
                },
                {
                  "childrenList": null,
                  "code": "13024",
                  "name": "尉犁县",
                  "pCode": "10354"
                },
                {
                  "childrenList": null,
                  "code": "13025",
                  "name": "若羌县",
                  "pCode": "10354"
                },
                {
                  "childrenList": null,
                  "code": "13026",
                  "name": "且末县",
                  "pCode": "10354"
                },
                {
                  "childrenList": null,
                  "code": "13027",
                  "name": "焉耆",
                  "pCode": "10354"
                },
                {
                  "childrenList": null,
                  "code": "13028",
                  "name": "和静县",
                  "pCode": "10354"
                },
                {
                  "childrenList": null,
                  "code": "13029",
                  "name": "和硕县",
                  "pCode": "10354"
                },
                {
                  "childrenList": null,
                  "code": "13030",
                  "name": "博湖县",
                  "pCode": "10354"
                }
              ],
              "code": "10354",
              "name": "巴音郭楞",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13031",
                  "name": "博乐市",
                  "pCode": "10355"
                },
                {
                  "childrenList": null,
                  "code": "13032",
                  "name": "精河县",
                  "pCode": "10355"
                },
                {
                  "childrenList": null,
                  "code": "13033",
                  "name": "温泉县",
                  "pCode": "10355"
                }
              ],
              "code": "10355",
              "name": "博尔塔拉",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13034",
                  "name": "呼图壁县",
                  "pCode": "10356"
                },
                {
                  "childrenList": null,
                  "code": "13035",
                  "name": "米泉市",
                  "pCode": "10356"
                },
                {
                  "childrenList": null,
                  "code": "13036",
                  "name": "昌吉市",
                  "pCode": "10356"
                },
                {
                  "childrenList": null,
                  "code": "13037",
                  "name": "阜康市",
                  "pCode": "10356"
                },
                {
                  "childrenList": null,
                  "code": "13038",
                  "name": "玛纳斯县",
                  "pCode": "10356"
                },
                {
                  "childrenList": null,
                  "code": "13039",
                  "name": "奇台县",
                  "pCode": "10356"
                },
                {
                  "childrenList": null,
                  "code": "13040",
                  "name": "吉木萨尔县",
                  "pCode": "10356"
                },
                {
                  "childrenList": null,
                  "code": "13041",
                  "name": "木垒",
                  "pCode": "10356"
                }
              ],
              "code": "10356",
              "name": "昌吉",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13042",
                  "name": "哈密市",
                  "pCode": "10357"
                },
                {
                  "childrenList": null,
                  "code": "13043",
                  "name": "伊吾县",
                  "pCode": "10357"
                },
                {
                  "childrenList": null,
                  "code": "13044",
                  "name": "巴里坤",
                  "pCode": "10357"
                }
              ],
              "code": "10357",
              "name": "哈密",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13045",
                  "name": "和田市",
                  "pCode": "10358"
                },
                {
                  "childrenList": null,
                  "code": "13046",
                  "name": "和田县",
                  "pCode": "10358"
                },
                {
                  "childrenList": null,
                  "code": "13047",
                  "name": "墨玉县",
                  "pCode": "10358"
                },
                {
                  "childrenList": null,
                  "code": "13048",
                  "name": "皮山县",
                  "pCode": "10358"
                },
                {
                  "childrenList": null,
                  "code": "13049",
                  "name": "洛浦县",
                  "pCode": "10358"
                },
                {
                  "childrenList": null,
                  "code": "13050",
                  "name": "策勒县",
                  "pCode": "10358"
                },
                {
                  "childrenList": null,
                  "code": "13051",
                  "name": "于田县",
                  "pCode": "10358"
                },
                {
                  "childrenList": null,
                  "code": "13052",
                  "name": "民丰县",
                  "pCode": "10358"
                }
              ],
              "code": "10358",
              "name": "和田",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13053",
                  "name": "喀什市",
                  "pCode": "10359"
                },
                {
                  "childrenList": null,
                  "code": "13054",
                  "name": "疏附县",
                  "pCode": "10359"
                },
                {
                  "childrenList": null,
                  "code": "13055",
                  "name": "疏勒县",
                  "pCode": "10359"
                },
                {
                  "childrenList": null,
                  "code": "13056",
                  "name": "英吉沙县",
                  "pCode": "10359"
                },
                {
                  "childrenList": null,
                  "code": "13057",
                  "name": "泽普县",
                  "pCode": "10359"
                },
                {
                  "childrenList": null,
                  "code": "13058",
                  "name": "莎车县",
                  "pCode": "10359"
                },
                {
                  "childrenList": null,
                  "code": "13059",
                  "name": "叶城县",
                  "pCode": "10359"
                },
                {
                  "childrenList": null,
                  "code": "13060",
                  "name": "麦盖提县",
                  "pCode": "10359"
                },
                {
                  "childrenList": null,
                  "code": "13061",
                  "name": "岳普湖县",
                  "pCode": "10359"
                },
                {
                  "childrenList": null,
                  "code": "13062",
                  "name": "伽师县",
                  "pCode": "10359"
                },
                {
                  "childrenList": null,
                  "code": "13063",
                  "name": "巴楚县",
                  "pCode": "10359"
                },
                {
                  "childrenList": null,
                  "code": "13064",
                  "name": "塔什库尔干",
                  "pCode": "10359"
                }
              ],
              "code": "10359",
              "name": "喀什",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13065",
                  "name": "克拉玛依市",
                  "pCode": "10360"
                }
              ],
              "code": "10360",
              "name": "克拉玛依",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13066",
                  "name": "阿图什市",
                  "pCode": "10361"
                },
                {
                  "childrenList": null,
                  "code": "13067",
                  "name": "阿克陶县",
                  "pCode": "10361"
                },
                {
                  "childrenList": null,
                  "code": "13068",
                  "name": "阿合奇县",
                  "pCode": "10361"
                },
                {
                  "childrenList": null,
                  "code": "13069",
                  "name": "乌恰县",
                  "pCode": "10361"
                }
              ],
              "code": "10361",
              "name": "克孜勒苏",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13070",
                  "name": "石河子市",
                  "pCode": "10362"
                }
              ],
              "code": "10362",
              "name": "石河子",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13071",
                  "name": "图木舒克市",
                  "pCode": "10363"
                }
              ],
              "code": "10363",
              "name": "图木舒克",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13072",
                  "name": "吐鲁番市",
                  "pCode": "10364"
                },
                {
                  "childrenList": null,
                  "code": "13073",
                  "name": "鄯善县",
                  "pCode": "10364"
                },
                {
                  "childrenList": null,
                  "code": "13074",
                  "name": "托克逊县",
                  "pCode": "10364"
                }
              ],
              "code": "10364",
              "name": "吐鲁番",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13075",
                  "name": "五家渠市",
                  "pCode": "10365"
                }
              ],
              "code": "10365",
              "name": "五家渠",
              "pCode": "10029"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13076",
                  "name": "阿勒泰市",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13077",
                  "name": "布克赛尔",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13078",
                  "name": "伊宁市",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13079",
                  "name": "布尔津县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13080",
                  "name": "奎屯市",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13081",
                  "name": "乌苏市",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13082",
                  "name": "额敏县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13083",
                  "name": "富蕴县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13084",
                  "name": "伊宁县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13085",
                  "name": "福海县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13086",
                  "name": "霍城县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13087",
                  "name": "沙湾县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13088",
                  "name": "巩留县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13089",
                  "name": "哈巴河县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13090",
                  "name": "托里县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13091",
                  "name": "青河县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13092",
                  "name": "新源县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13093",
                  "name": "裕民县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13094",
                  "name": "和布克赛尔",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13095",
                  "name": "吉木乃县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13096",
                  "name": "昭苏县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13097",
                  "name": "特克斯县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13098",
                  "name": "尼勒克县",
                  "pCode": "10366"
                },
                {
                  "childrenList": null,
                  "code": "13099",
                  "name": "察布查尔",
                  "pCode": "10366"
                }
              ],
              "code": "10366",
              "name": "伊犁",
              "pCode": "10029"
            }
          ],
          "code": "10029",
          "name": "新疆维吾尔自治区",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13100",
                  "name": "盘龙区",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13101",
                  "name": "五华区",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13102",
                  "name": "官渡区",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13103",
                  "name": "西山区",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13104",
                  "name": "东川区",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13105",
                  "name": "安宁市",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13106",
                  "name": "呈贡县",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13107",
                  "name": "晋宁县",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13108",
                  "name": "富民县",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13109",
                  "name": "宜良县",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13110",
                  "name": "嵩明县",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13111",
                  "name": "石林县",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13112",
                  "name": "禄劝",
                  "pCode": "10367"
                },
                {
                  "childrenList": null,
                  "code": "13113",
                  "name": "寻甸",
                  "pCode": "10367"
                }
              ],
              "code": "10367",
              "name": "昆明",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13114",
                  "name": "兰坪",
                  "pCode": "10368"
                },
                {
                  "childrenList": null,
                  "code": "13115",
                  "name": "泸水县",
                  "pCode": "10368"
                },
                {
                  "childrenList": null,
                  "code": "13116",
                  "name": "福贡县",
                  "pCode": "10368"
                },
                {
                  "childrenList": null,
                  "code": "13117",
                  "name": "贡山",
                  "pCode": "10368"
                }
              ],
              "code": "10368",
              "name": "怒江",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13118",
                  "name": "宁洱",
                  "pCode": "10369"
                },
                {
                  "childrenList": null,
                  "code": "13119",
                  "name": "思茅区",
                  "pCode": "10369"
                },
                {
                  "childrenList": null,
                  "code": "13120",
                  "name": "墨江",
                  "pCode": "10369"
                },
                {
                  "childrenList": null,
                  "code": "13121",
                  "name": "景东",
                  "pCode": "10369"
                },
                {
                  "childrenList": null,
                  "code": "13122",
                  "name": "景谷",
                  "pCode": "10369"
                },
                {
                  "childrenList": null,
                  "code": "13123",
                  "name": "镇沅",
                  "pCode": "10369"
                },
                {
                  "childrenList": null,
                  "code": "13124",
                  "name": "江城",
                  "pCode": "10369"
                },
                {
                  "childrenList": null,
                  "code": "13125",
                  "name": "孟连",
                  "pCode": "10369"
                },
                {
                  "childrenList": null,
                  "code": "13126",
                  "name": "澜沧",
                  "pCode": "10369"
                },
                {
                  "childrenList": null,
                  "code": "13127",
                  "name": "西盟",
                  "pCode": "10369"
                }
              ],
              "code": "10369",
              "name": "普洱",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13128",
                  "name": "古城区",
                  "pCode": "10370"
                },
                {
                  "childrenList": null,
                  "code": "13129",
                  "name": "宁蒗",
                  "pCode": "10370"
                },
                {
                  "childrenList": null,
                  "code": "13130",
                  "name": "玉龙",
                  "pCode": "10370"
                },
                {
                  "childrenList": null,
                  "code": "13131",
                  "name": "永胜县",
                  "pCode": "10370"
                },
                {
                  "childrenList": null,
                  "code": "13132",
                  "name": "华坪县",
                  "pCode": "10370"
                }
              ],
              "code": "10370",
              "name": "丽江",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13133",
                  "name": "隆阳区",
                  "pCode": "10371"
                },
                {
                  "childrenList": null,
                  "code": "13134",
                  "name": "施甸县",
                  "pCode": "10371"
                },
                {
                  "childrenList": null,
                  "code": "13135",
                  "name": "腾冲县",
                  "pCode": "10371"
                },
                {
                  "childrenList": null,
                  "code": "13136",
                  "name": "龙陵县",
                  "pCode": "10371"
                },
                {
                  "childrenList": null,
                  "code": "13137",
                  "name": "昌宁县",
                  "pCode": "10371"
                }
              ],
              "code": "10371",
              "name": "保山",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13138",
                  "name": "楚雄市",
                  "pCode": "10372"
                },
                {
                  "childrenList": null,
                  "code": "13139",
                  "name": "双柏县",
                  "pCode": "10372"
                },
                {
                  "childrenList": null,
                  "code": "13140",
                  "name": "牟定县",
                  "pCode": "10372"
                },
                {
                  "childrenList": null,
                  "code": "13141",
                  "name": "南华县",
                  "pCode": "10372"
                },
                {
                  "childrenList": null,
                  "code": "13142",
                  "name": "姚安县",
                  "pCode": "10372"
                },
                {
                  "childrenList": null,
                  "code": "13143",
                  "name": "大姚县",
                  "pCode": "10372"
                },
                {
                  "childrenList": null,
                  "code": "13144",
                  "name": "永仁县",
                  "pCode": "10372"
                },
                {
                  "childrenList": null,
                  "code": "13145",
                  "name": "元谋县",
                  "pCode": "10372"
                },
                {
                  "childrenList": null,
                  "code": "13146",
                  "name": "武定县",
                  "pCode": "10372"
                },
                {
                  "childrenList": null,
                  "code": "13147",
                  "name": "禄丰县",
                  "pCode": "10372"
                }
              ],
              "code": "10372",
              "name": "楚雄",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13148",
                  "name": "大理市",
                  "pCode": "10373"
                },
                {
                  "childrenList": null,
                  "code": "13149",
                  "name": "祥云县",
                  "pCode": "10373"
                },
                {
                  "childrenList": null,
                  "code": "13150",
                  "name": "宾川县",
                  "pCode": "10373"
                },
                {
                  "childrenList": null,
                  "code": "13151",
                  "name": "弥渡县",
                  "pCode": "10373"
                },
                {
                  "childrenList": null,
                  "code": "13152",
                  "name": "永平县",
                  "pCode": "10373"
                },
                {
                  "childrenList": null,
                  "code": "13153",
                  "name": "云龙县",
                  "pCode": "10373"
                },
                {
                  "childrenList": null,
                  "code": "13154",
                  "name": "洱源县",
                  "pCode": "10373"
                },
                {
                  "childrenList": null,
                  "code": "13155",
                  "name": "剑川县",
                  "pCode": "10373"
                },
                {
                  "childrenList": null,
                  "code": "13156",
                  "name": "鹤庆县",
                  "pCode": "10373"
                },
                {
                  "childrenList": null,
                  "code": "13157",
                  "name": "漾濞",
                  "pCode": "10373"
                },
                {
                  "childrenList": null,
                  "code": "13158",
                  "name": "南涧",
                  "pCode": "10373"
                },
                {
                  "childrenList": null,
                  "code": "13159",
                  "name": "巍山",
                  "pCode": "10373"
                }
              ],
              "code": "10373",
              "name": "大理",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13160",
                  "name": "潞西市",
                  "pCode": "10374"
                },
                {
                  "childrenList": null,
                  "code": "13161",
                  "name": "瑞丽市",
                  "pCode": "10374"
                },
                {
                  "childrenList": null,
                  "code": "13162",
                  "name": "梁河县",
                  "pCode": "10374"
                },
                {
                  "childrenList": null,
                  "code": "13163",
                  "name": "盈江县",
                  "pCode": "10374"
                },
                {
                  "childrenList": null,
                  "code": "13164",
                  "name": "陇川县",
                  "pCode": "10374"
                }
              ],
              "code": "10374",
              "name": "德宏",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13165",
                  "name": "香格里拉县",
                  "pCode": "10375"
                },
                {
                  "childrenList": null,
                  "code": "13166",
                  "name": "德钦县",
                  "pCode": "10375"
                },
                {
                  "childrenList": null,
                  "code": "13167",
                  "name": "维西",
                  "pCode": "10375"
                }
              ],
              "code": "10375",
              "name": "迪庆",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13168",
                  "name": "泸西县",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13169",
                  "name": "蒙自县",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13170",
                  "name": "个旧市",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13171",
                  "name": "开远市",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13172",
                  "name": "绿春县",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13173",
                  "name": "建水县",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13174",
                  "name": "石屏县",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13175",
                  "name": "弥勒县",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13176",
                  "name": "元阳县",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13177",
                  "name": "红河县",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13178",
                  "name": "金平",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13179",
                  "name": "河口",
                  "pCode": "10376"
                },
                {
                  "childrenList": null,
                  "code": "13180",
                  "name": "屏边",
                  "pCode": "10376"
                }
              ],
              "code": "10376",
              "name": "红河",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13181",
                  "name": "临翔区",
                  "pCode": "10377"
                },
                {
                  "childrenList": null,
                  "code": "13182",
                  "name": "凤庆县",
                  "pCode": "10377"
                },
                {
                  "childrenList": null,
                  "code": "13183",
                  "name": "云县",
                  "pCode": "10377"
                },
                {
                  "childrenList": null,
                  "code": "13184",
                  "name": "永德县",
                  "pCode": "10377"
                },
                {
                  "childrenList": null,
                  "code": "13185",
                  "name": "镇康县",
                  "pCode": "10377"
                },
                {
                  "childrenList": null,
                  "code": "13186",
                  "name": "双江",
                  "pCode": "10377"
                },
                {
                  "childrenList": null,
                  "code": "13187",
                  "name": "耿马",
                  "pCode": "10377"
                },
                {
                  "childrenList": null,
                  "code": "13188",
                  "name": "沧源",
                  "pCode": "10377"
                }
              ],
              "code": "10377",
              "name": "临沧",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13189",
                  "name": "麒麟区",
                  "pCode": "10378"
                },
                {
                  "childrenList": null,
                  "code": "13190",
                  "name": "宣威市",
                  "pCode": "10378"
                },
                {
                  "childrenList": null,
                  "code": "13191",
                  "name": "马龙县",
                  "pCode": "10378"
                },
                {
                  "childrenList": null,
                  "code": "13192",
                  "name": "陆良县",
                  "pCode": "10378"
                },
                {
                  "childrenList": null,
                  "code": "13193",
                  "name": "师宗县",
                  "pCode": "10378"
                },
                {
                  "childrenList": null,
                  "code": "13194",
                  "name": "罗平县",
                  "pCode": "10378"
                },
                {
                  "childrenList": null,
                  "code": "13195",
                  "name": "富源县",
                  "pCode": "10378"
                },
                {
                  "childrenList": null,
                  "code": "13196",
                  "name": "会泽县",
                  "pCode": "10378"
                },
                {
                  "childrenList": null,
                  "code": "13197",
                  "name": "沾益县",
                  "pCode": "10378"
                }
              ],
              "code": "10378",
              "name": "曲靖",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13198",
                  "name": "文山县",
                  "pCode": "10379"
                },
                {
                  "childrenList": null,
                  "code": "13199",
                  "name": "砚山县",
                  "pCode": "10379"
                },
                {
                  "childrenList": null,
                  "code": "13200",
                  "name": "西畴县",
                  "pCode": "10379"
                },
                {
                  "childrenList": null,
                  "code": "13201",
                  "name": "麻栗坡县",
                  "pCode": "10379"
                },
                {
                  "childrenList": null,
                  "code": "13202",
                  "name": "马关县",
                  "pCode": "10379"
                },
                {
                  "childrenList": null,
                  "code": "13203",
                  "name": "丘北县",
                  "pCode": "10379"
                },
                {
                  "childrenList": null,
                  "code": "13204",
                  "name": "广南县",
                  "pCode": "10379"
                },
                {
                  "childrenList": null,
                  "code": "13205",
                  "name": "富宁县",
                  "pCode": "10379"
                }
              ],
              "code": "10379",
              "name": "文山",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13206",
                  "name": "景洪市",
                  "pCode": "10380"
                },
                {
                  "childrenList": null,
                  "code": "13207",
                  "name": "勐海县",
                  "pCode": "10380"
                },
                {
                  "childrenList": null,
                  "code": "13208",
                  "name": "勐腊县",
                  "pCode": "10380"
                }
              ],
              "code": "10380",
              "name": "西双版纳",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13209",
                  "name": "红塔区",
                  "pCode": "10381"
                },
                {
                  "childrenList": null,
                  "code": "13210",
                  "name": "江川县",
                  "pCode": "10381"
                },
                {
                  "childrenList": null,
                  "code": "13211",
                  "name": "澄江县",
                  "pCode": "10381"
                },
                {
                  "childrenList": null,
                  "code": "13212",
                  "name": "通海县",
                  "pCode": "10381"
                },
                {
                  "childrenList": null,
                  "code": "13213",
                  "name": "华宁县",
                  "pCode": "10381"
                },
                {
                  "childrenList": null,
                  "code": "13214",
                  "name": "易门县",
                  "pCode": "10381"
                },
                {
                  "childrenList": null,
                  "code": "13215",
                  "name": "峨山",
                  "pCode": "10381"
                },
                {
                  "childrenList": null,
                  "code": "13216",
                  "name": "新平",
                  "pCode": "10381"
                },
                {
                  "childrenList": null,
                  "code": "13217",
                  "name": "元江",
                  "pCode": "10381"
                }
              ],
              "code": "10381",
              "name": "玉溪",
              "pCode": "10030"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13218",
                  "name": "昭阳区",
                  "pCode": "10382"
                },
                {
                  "childrenList": null,
                  "code": "13219",
                  "name": "鲁甸县",
                  "pCode": "10382"
                },
                {
                  "childrenList": null,
                  "code": "13220",
                  "name": "巧家县",
                  "pCode": "10382"
                },
                {
                  "childrenList": null,
                  "code": "13221",
                  "name": "盐津县",
                  "pCode": "10382"
                },
                {
                  "childrenList": null,
                  "code": "13222",
                  "name": "大关县",
                  "pCode": "10382"
                },
                {
                  "childrenList": null,
                  "code": "13223",
                  "name": "永善县",
                  "pCode": "10382"
                },
                {
                  "childrenList": null,
                  "code": "13224",
                  "name": "绥江县",
                  "pCode": "10382"
                },
                {
                  "childrenList": null,
                  "code": "13225",
                  "name": "镇雄县",
                  "pCode": "10382"
                },
                {
                  "childrenList": null,
                  "code": "13226",
                  "name": "彝良县",
                  "pCode": "10382"
                },
                {
                  "childrenList": null,
                  "code": "13227",
                  "name": "威信县",
                  "pCode": "10382"
                },
                {
                  "childrenList": null,
                  "code": "13228",
                  "name": "水富县",
                  "pCode": "10382"
                }
              ],
              "code": "10382",
              "name": "昭通",
              "pCode": "10030"
            }
          ],
          "code": "10030",
          "name": "云南省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13229",
                  "name": "西湖区",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13230",
                  "name": "上城区",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13231",
                  "name": "下城区",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13232",
                  "name": "拱墅区",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13233",
                  "name": "滨江区",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13234",
                  "name": "江干区",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13235",
                  "name": "萧山区",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13236",
                  "name": "余杭区",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13237",
                  "name": "市郊",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13238",
                  "name": "建德市",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13239",
                  "name": "富阳市",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13240",
                  "name": "临安市",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13241",
                  "name": "桐庐县",
                  "pCode": "10383"
                },
                {
                  "childrenList": null,
                  "code": "13242",
                  "name": "淳安县",
                  "pCode": "10383"
                }
              ],
              "code": "10383",
              "name": "杭州",
              "pCode": "10031"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13243",
                  "name": "吴兴区",
                  "pCode": "10384"
                },
                {
                  "childrenList": null,
                  "code": "13244",
                  "name": "南浔区",
                  "pCode": "10384"
                },
                {
                  "childrenList": null,
                  "code": "13245",
                  "name": "德清县",
                  "pCode": "10384"
                },
                {
                  "childrenList": null,
                  "code": "13246",
                  "name": "长兴县",
                  "pCode": "10384"
                },
                {
                  "childrenList": null,
                  "code": "13247",
                  "name": "安吉县",
                  "pCode": "10384"
                }
              ],
              "code": "10384",
              "name": "湖州",
              "pCode": "10031"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13248",
                  "name": "南湖区",
                  "pCode": "10385"
                },
                {
                  "childrenList": null,
                  "code": "13249",
                  "name": "秀洲区",
                  "pCode": "10385"
                },
                {
                  "childrenList": null,
                  "code": "13250",
                  "name": "海宁市",
                  "pCode": "10385"
                },
                {
                  "childrenList": null,
                  "code": "13251",
                  "name": "嘉善县",
                  "pCode": "10385"
                },
                {
                  "childrenList": null,
                  "code": "13252",
                  "name": "平湖市",
                  "pCode": "10385"
                },
                {
                  "childrenList": null,
                  "code": "13253",
                  "name": "桐乡市",
                  "pCode": "10385"
                },
                {
                  "childrenList": null,
                  "code": "13254",
                  "name": "海盐县",
                  "pCode": "10385"
                }
              ],
              "code": "10385",
              "name": "嘉兴",
              "pCode": "10031"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13255",
                  "name": "婺城区",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13256",
                  "name": "金东区",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13257",
                  "name": "兰溪市",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13258",
                  "name": "市区",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13259",
                  "name": "佛堂镇",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13260",
                  "name": "上溪镇",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13261",
                  "name": "义亭镇",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13262",
                  "name": "大陈镇",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13263",
                  "name": "苏溪镇",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13264",
                  "name": "赤岸镇",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13265",
                  "name": "东阳市",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13266",
                  "name": "永康市",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13267",
                  "name": "武义县",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13268",
                  "name": "浦江县",
                  "pCode": "10386"
                },
                {
                  "childrenList": null,
                  "code": "13269",
                  "name": "磐安县",
                  "pCode": "10386"
                }
              ],
              "code": "10386",
              "name": "金华",
              "pCode": "10031"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13270",
                  "name": "莲都区",
                  "pCode": "10387"
                },
                {
                  "childrenList": null,
                  "code": "13271",
                  "name": "龙泉市",
                  "pCode": "10387"
                },
                {
                  "childrenList": null,
                  "code": "13272",
                  "name": "青田县",
                  "pCode": "10387"
                },
                {
                  "childrenList": null,
                  "code": "13273",
                  "name": "缙云县",
                  "pCode": "10387"
                },
                {
                  "childrenList": null,
                  "code": "13274",
                  "name": "遂昌县",
                  "pCode": "10387"
                },
                {
                  "childrenList": null,
                  "code": "13275",
                  "name": "松阳县",
                  "pCode": "10387"
                },
                {
                  "childrenList": null,
                  "code": "13276",
                  "name": "云和县",
                  "pCode": "10387"
                },
                {
                  "childrenList": null,
                  "code": "13277",
                  "name": "庆元县",
                  "pCode": "10387"
                },
                {
                  "childrenList": null,
                  "code": "13278",
                  "name": "景宁",
                  "pCode": "10387"
                }
              ],
              "code": "10387",
              "name": "丽水",
              "pCode": "10031"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13279",
                  "name": "海曙区",
                  "pCode": "10388"
                },
                {
                  "childrenList": null,
                  "code": "13280",
                  "name": "江东区",
                  "pCode": "10388"
                },
                {
                  "childrenList": null,
                  "code": "13281",
                  "name": "江北区",
                  "pCode": "10388"
                },
                {
                  "childrenList": null,
                  "code": "13282",
                  "name": "镇海区",
                  "pCode": "10388"
                },
                {
                  "childrenList": null,
                  "code": "13283",
                  "name": "北仑区",
                  "pCode": "10388"
                },
                {
                  "childrenList": null,
                  "code": "13284",
                  "name": "鄞州区",
                  "pCode": "10388"
                },
                {
                  "childrenList": null,
                  "code": "13285",
                  "name": "余姚市",
                  "pCode": "10388"
                },
                {
                  "childrenList": null,
                  "code": "13286",
                  "name": "慈溪市",
                  "pCode": "10388"
                },
                {
                  "childrenList": null,
                  "code": "13287",
                  "name": "奉化市",
                  "pCode": "10388"
                },
                {
                  "childrenList": null,
                  "code": "13288",
                  "name": "象山县",
                  "pCode": "10388"
                },
                {
                  "childrenList": null,
                  "code": "13289",
                  "name": "宁海县",
                  "pCode": "10388"
                }
              ],
              "code": "10388",
              "name": "宁波",
              "pCode": "10031"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13290",
                  "name": "越城区",
                  "pCode": "10389"
                },
                {
                  "childrenList": null,
                  "code": "13291",
                  "name": "上虞市",
                  "pCode": "10389"
                },
                {
                  "childrenList": null,
                  "code": "13292",
                  "name": "嵊州市",
                  "pCode": "10389"
                },
                {
                  "childrenList": null,
                  "code": "13293",
                  "name": "绍兴县",
                  "pCode": "10389"
                },
                {
                  "childrenList": null,
                  "code": "13294",
                  "name": "新昌县",
                  "pCode": "10389"
                },
                {
                  "childrenList": null,
                  "code": "13295",
                  "name": "诸暨市",
                  "pCode": "10389"
                }
              ],
              "code": "10389",
              "name": "绍兴",
              "pCode": "10031"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13296",
                  "name": "椒江区",
                  "pCode": "10390"
                },
                {
                  "childrenList": null,
                  "code": "13297",
                  "name": "黄岩区",
                  "pCode": "10390"
                },
                {
                  "childrenList": null,
                  "code": "13298",
                  "name": "路桥区",
                  "pCode": "10390"
                },
                {
                  "childrenList": null,
                  "code": "13299",
                  "name": "温岭市",
                  "pCode": "10390"
                },
                {
                  "childrenList": null,
                  "code": "13300",
                  "name": "临海市",
                  "pCode": "10390"
                },
                {
                  "childrenList": null,
                  "code": "13301",
                  "name": "玉环县",
                  "pCode": "10390"
                },
                {
                  "childrenList": null,
                  "code": "13302",
                  "name": "三门县",
                  "pCode": "10390"
                },
                {
                  "childrenList": null,
                  "code": "13303",
                  "name": "天台县",
                  "pCode": "10390"
                },
                {
                  "childrenList": null,
                  "code": "13304",
                  "name": "仙居县",
                  "pCode": "10390"
                }
              ],
              "code": "10390",
              "name": "台州",
              "pCode": "10031"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13305",
                  "name": "鹿城区",
                  "pCode": "10391"
                },
                {
                  "childrenList": null,
                  "code": "13306",
                  "name": "龙湾区",
                  "pCode": "10391"
                },
                {
                  "childrenList": null,
                  "code": "13307",
                  "name": "瓯海区",
                  "pCode": "10391"
                },
                {
                  "childrenList": null,
                  "code": "13308",
                  "name": "瑞安市",
                  "pCode": "10391"
                },
                {
                  "childrenList": null,
                  "code": "13309",
                  "name": "乐清市",
                  "pCode": "10391"
                },
                {
                  "childrenList": null,
                  "code": "13310",
                  "name": "洞头县",
                  "pCode": "10391"
                },
                {
                  "childrenList": null,
                  "code": "13311",
                  "name": "永嘉县",
                  "pCode": "10391"
                },
                {
                  "childrenList": null,
                  "code": "13312",
                  "name": "平阳县",
                  "pCode": "10391"
                },
                {
                  "childrenList": null,
                  "code": "13313",
                  "name": "苍南县",
                  "pCode": "10391"
                },
                {
                  "childrenList": null,
                  "code": "13314",
                  "name": "文成县",
                  "pCode": "10391"
                },
                {
                  "childrenList": null,
                  "code": "13315",
                  "name": "泰顺县",
                  "pCode": "10391"
                }
              ],
              "code": "10391",
              "name": "温州",
              "pCode": "10031"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13316",
                  "name": "定海区",
                  "pCode": "10392"
                },
                {
                  "childrenList": null,
                  "code": "13317",
                  "name": "普陀区",
                  "pCode": "10392"
                },
                {
                  "childrenList": null,
                  "code": "13318",
                  "name": "岱山县",
                  "pCode": "10392"
                },
                {
                  "childrenList": null,
                  "code": "13319",
                  "name": "嵊泗县",
                  "pCode": "10392"
                }
              ],
              "code": "10392",
              "name": "舟山",
              "pCode": "10031"
            },
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13320",
                  "name": "衢州市",
                  "pCode": "10393"
                },
                {
                  "childrenList": null,
                  "code": "13321",
                  "name": "江山市",
                  "pCode": "10393"
                },
                {
                  "childrenList": null,
                  "code": "13322",
                  "name": "常山县",
                  "pCode": "10393"
                },
                {
                  "childrenList": null,
                  "code": "13323",
                  "name": "开化县",
                  "pCode": "10393"
                }
              ],
              "code": "10393",
              "name": "衢州",
              "pCode": "10031"
            }
          ],
          "code": "10031",
          "name": "浙江省",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13325",
                  "name": "合川区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13326",
                  "name": "江津区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13327",
                  "name": "南川区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13328",
                  "name": "永川区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13329",
                  "name": "南岸区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13330",
                  "name": "渝北区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13331",
                  "name": "万盛区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13332",
                  "name": "大渡口区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13333",
                  "name": "万州区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13334",
                  "name": "北碚区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13335",
                  "name": "沙坪坝区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13336",
                  "name": "巴南区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13337",
                  "name": "涪陵区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13338",
                  "name": "江北区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13339",
                  "name": "九龙坡区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13340",
                  "name": "渝中区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13341",
                  "name": "黔江开发区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13342",
                  "name": "长寿区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13343",
                  "name": "双桥区",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13344",
                  "name": "綦江县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13345",
                  "name": "潼南县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13346",
                  "name": "铜梁县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13347",
                  "name": "大足县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13348",
                  "name": "荣昌县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13349",
                  "name": "璧山县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13350",
                  "name": "垫江县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13351",
                  "name": "武隆县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13352",
                  "name": "丰都县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13353",
                  "name": "城口县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13354",
                  "name": "梁平县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13355",
                  "name": "开县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13356",
                  "name": "巫溪县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13357",
                  "name": "巫山县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13358",
                  "name": "奉节县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13359",
                  "name": "云阳县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13360",
                  "name": "忠县",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13361",
                  "name": "石柱",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13362",
                  "name": "彭水",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13363",
                  "name": "酉阳",
                  "pCode": "10394"
                },
                {
                  "childrenList": null,
                  "code": "13364",
                  "name": "秀山",
                  "pCode": "10394"
                }
              ],
              "code": "10394",
              "name": "重庆",
              "pCode": "10032"
            }
          ],
          "code": "10032",
          "name": "重庆市",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13365",
                  "name": "沙田区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13366",
                  "name": "东区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13367",
                  "name": "观塘区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13368",
                  "name": "黄大仙区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13369",
                  "name": "九龙城区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13370",
                  "name": "屯门区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13371",
                  "name": "葵青区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13372",
                  "name": "元朗区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13373",
                  "name": "深水埗区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13374",
                  "name": "西贡区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13375",
                  "name": "大埔区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13376",
                  "name": "湾仔区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13377",
                  "name": "油尖旺区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13378",
                  "name": "北区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13379",
                  "name": "南区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13380",
                  "name": "荃湾区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13381",
                  "name": "中西区",
                  "pCode": "10395"
                },
                {
                  "childrenList": null,
                  "code": "13382",
                  "name": "离岛区",
                  "pCode": "10395"
                }
              ],
              "code": "10395",
              "name": "香港",
              "pCode": "10033"
            }
          ],
          "code": "10033",
          "name": "香港特别行政区",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13383",
                  "name": "澳门",
                  "pCode": "10396"
                }
              ],
              "code": "10396",
              "name": "澳门",
              "pCode": "10034"
            }
          ],
          "code": "10034",
          "name": "澳门特别行政区",
          "pCode": null
        },
        {
          "childrenList": [
            {
              "childrenList": [
                {
                  "childrenList": null,
                  "code": "13384",
                  "name": "台北",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13385",
                  "name": "高雄",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13386",
                  "name": "基隆",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13387",
                  "name": "台中",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13388",
                  "name": "台南",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13389",
                  "name": "新竹",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13390",
                  "name": "嘉义",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13391",
                  "name": "宜兰县",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13392",
                  "name": "桃园县",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13393",
                  "name": "苗栗县",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13394",
                  "name": "彰化县",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13395",
                  "name": "南投县",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13396",
                  "name": "云林县",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13397",
                  "name": "屏东县",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13398",
                  "name": "台东县",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13399",
                  "name": "花莲县",
                  "pCode": "10397"
                },
                {
                  "childrenList": null,
                  "code": "13400",
                  "name": "澎湖县",
                  "pCode": "10397"
                }
              ],
              "code": "10397",
              "name": "台湾",
              "pCode": "10035"
            }
          ],
          "code": "10035",
          "name": "台湾省",
          "pCode": null
        }
      ],
      "message": "success",
      "requestId": null,
      "success": true
    },
    detail_info  = {
      "code": 200,
      "requestId": "xxx",
      "data": {
        recipient: "小李",
        telephone: 12233445566,
        province: "北京市",
        city: "北京",
        area: "东城区",
        address: "区域",
        postCode: 10000,
        period: "2016-01-01",
        isDefault: 1,
      }
      ,
      "success": true,
      "message": "success"
    }
Page({
  data: {
    address: {},
    isShow: 0
  },
  address: {},
  origin: {},
  maybeInfo: {},
  addrInfo: {},
  bindChange: function(e) {
    var self = this,
        val  = e.detail.value,
        obj  = new common.getAddress(self.address),
        pid  = val[0],
        cid  = val[1],
        aid  = val[2];
    obj.initObj();
    var addr = obj.setCity(pid, cid);
    this.setData({
      province: addr.province,
      city: addr.city,
      area: addr.area
    });
    self.maybeInfo = {
      province: addr.province[pid],
      city: addr.city[cid],
      area: addr.area[aid]
    }
  },
  onLoad: function(e) {
    var self     = this,
        edit     = e.id ? true : false;
    self.address = address_info.data;    //区域数据
    var a      = new common.getAddress(self.address);
    var detail = a.initObj();
    self.setData({
      province: detail.province,
      city: detail.city,
      area: detail.area
    });
    if(edit) {
      wx.setNavigationBarTitle({
        title: "编辑地址"
      });
      self.origin = {
        isEdit: true,
        id: e.id
      }
      self.setData({
        address: detail_info.data,
      })
    } else {
      var address = {
        isDefault: 0
      }
      self.origin = {
        isEdit: false,
        id: e.id
      }
      self.setData({
        address: address
      })
      wx.setNavigationBarTitle({
        title: "新建收货地址"
      });
    }
  },
  onShow: function() {

  },
  confirm: function() {
    var self    = this;
    var address = this.data.address;
    if(common.objLength(self.maybeInfo)) {
      self.addrInfo = self.maybeInfo;
    } else {
      self.addrInfo = {
        province: self.address[0].name,
        city: self.address[0].childrenList[0].name,
        area: self.address[0].childrenList[0].childrenList[0].name,
      }
    }
    address.province = self.addrInfo.province;
    address.city     = self.addrInfo.city;
    address.area     = self.addrInfo.area;
    self.setData({
      isShow: 0,
      address: address
    });
    self.maybeInfo = {};
  },
  hideMask: function() {
    var self = this;
    self.setData({
      isShow: 0
    })
  },
  setArea: function() {
    var self   = this;
    var a      = new common.getAddress(self.address);
    var detail = a.initObj();
    self.setData({
      province: detail.province,
      city: detail.city,
      area: detail.area,
      isShow: 1
    });
  },
  setDefault: function() {
    var self    = this,
        address = self.data.address;
    if(address.isDefault == 0) {
      address.isDefault = 1;
    } else {
      address.isDefault = 0;
    }
    self.setData({
      address: address
    });
  },
  setName: function(e) {
    var address       = this.data.address;
    address.recipient = e.detail.value;
    this.setData({
      address: address
    })
  },
  setPhone: function(e) {
    var address       = this.data.address;
    address.telephone = e.detail.value;
    this.setData({
      address: address
    })
  },
  setDetail: function(e) {
    var address     = this.data.address;
    address.address = e.detail.value;
    this.setData({
      address: address
    })
  },
  saveAddr: function() {
    wx.navigateBack({
      delta: 1
    })
  }
})