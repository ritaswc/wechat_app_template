<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%express}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $code
 * @property integer $sort
 * @property integer $is_delete
 */
class Express extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%express}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort', 'is_delete'], 'integer'],
            [['name', 'code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'sort' => 'Sort',
            'is_delete' => 'Is Delete',
        ];
    }

    public static function getExpressList()
    {
        return [

            [
                'id' => 1,
                'name' => '顺丰速运',
                'code' => 'SF',
            ],

            [
                'id' => 6,
                'name' => '百世快递',
                'code' => 'HTKY',
            ],

            [
                'id' => 5,
                'name' => '中通快递',
                'code' => 'ZTO',
            ],

            [
                'id' => 2,
                'name' => '申通快递',
                'code' => 'STO',
            ],

            [
                'id' => 4,
                'name' => '圆通速递',
                'code' => 'YTO',
            ],

            [
                'id' => 3,
                'name' => '韵达速递',
                'code' => 'YD',
            ],

            [
                'id' => 55,
                'name' => '龙邦快递',
                'code' => 'LB',
            ],

            [
                'id' => 56,
                'name' => '联昊通速递',
                'code' => 'LHT',
            ],

            [
                'id' => 57,
                'name' => '民航快递',
                'code' => 'MHKD',
            ],

            [
                'id' => 9,
                'name' => '邮政快递包裹',
                'code' => 'YZPY',
            ],

            [
                'id' => 7,
                'name' => 'EMS',
                'code' => 'EMS',
            ],

            [
                'id' => 8,
                'name' => '天天快递',
                'code' => 'HHTT',
            ],

            [
                'id' => 103,
                'name' => '京东物流',
                'code' => 'JD',
            ],

            [
                'id' => 58,
                'name' => '明亮物流',
                'code' => 'MLWL',
            ],

            [
                'id' => 12,
                'name' => '全峰快递',
                'code' => 'QFKD',
            ],

            [
                'id' => 11,
                'name' => '国通快递',
                'code' => 'GTO',
            ],

            [
                'id' => 13,
                'name' => '优速快递',
                'code' => 'UC',
            ],

            [
                'id' => 18,
                'name' => '德邦',
                'code' => 'DBL',
            ],

            [
                'id' => 33,
                'name' => '快捷快递',
                'code' => 'FAST',
            ],

            [
                'id' => 10,
                'name' => '宅急送',
                'code' => 'ZJS',
            ],

            [
                'id' => 21,
                'name' => '安捷快递',
                'code' => 'AJ',
            ],

            [
                'id' => 59,
                'name' => '能达速递',
                'code' => 'NEDA',
            ],

            [
                'id' => 60,
                'name' => '平安达腾飞快递',
                'code' => 'PADTF',
            ],

            [
                'id' => 100,
                'name' => '泛捷快递',
                'code' => 'PANEX',
            ],

            [
                'id' => 101,
                'name' => 'PCA Express',
                'code' => 'PCA',
            ],

            [
                'id' => 61,
                'name' => '全晨快递',
                'code' => 'QCKD',
            ],

            [
                'id' => 62,
                'name' => '全日通快递',
                'code' => 'QRT',
            ],

            [
                'id' => 95,
                'name' => '快客快递',
                'code' => 'QUICK',
            ],

            [
                'id' => 83,
                'name' => '义达国际物流',
                'code' => 'YDH',
            ],

            [
                'id' => 84,
                'name' => '越丰物流',
                'code' => 'YFEX',
            ],

            [
                'id' => 85,
                'name' => '原飞航物流',
                'code' => 'YFHEX',
            ],

            [
                'id' => 86,
                'name' => '亚风快递',
                'code' => 'YFSD',
            ],

            [
                'id' => 87,
                'name' => '运通快递',
                'code' => 'YTKD',
            ],

            [
                'id' => 88,
                'name' => '亿翔快递',
                'code' => 'YXKD',
            ],

            [
                'id' => 89,
                'name' => '增益快递',
                'code' => 'ZENY',
            ],

            [
                'id' => 90,
                'name' => '汇强快递',
                'code' => 'ZHQKD',
            ],

            [
                'id' => 91,
                'name' => '众通快递',
                'code' => 'ZTE',
            ],

            [
                'id' => 14,
                'name' => '中铁快运',
                'code' => 'ZTKY',
            ],

            [
                'id' => 15,
                'name' => '中铁物流',
                'code' => 'ZTWL',
            ],

            [
                'id' => 92,
                'name' => '中邮物流',
                'code' => 'ZYWL',
            ],

            [
                'id' => 16,
                'name' => '亚马逊物流',
                'code' => 'AMAZON',
            ],

            [
                'id' => 22,
                'name' => '安能物流',
                'code' => 'ANE',
            ],

            [
                'id' => 23,
                'name' => '安信达快递',
                'code' => 'AXD',
            ],

            [
                'id' => 99,
                'name' => '澳邮专线',
                'code' => 'AYCA',
            ],

            [
                'id' => 25,
                'name' => '百福东方',
                'code' => 'BFDF',
            ],

            [
                'id' => 24,
                'name' => '北青小红帽',
                'code' => 'BQXHM',
            ],

            [
                'id' => 20,
                'name' => '百世快运',
                'code' => 'BTWL',
            ],

            [
                'id' => 26,
                'name' => 'CCES快递',
                'code' => 'CCES',
            ],

            [
                'id' => 27,
                'name' => '城市100',
                'code' => 'CITY100',
            ],

            [
                'id' => 17,
                'name' => '城际快递',
                'code' => 'CJKD',
            ],

            [
                'id' => 96,
                'name' => 'CNPEX中邮快递',
                'code' => 'CNPEX',
            ],

            [
                'id' => 28,
                'name' => 'COE东方快递',
                'code' => 'COE',
            ],

            [
                'id' => 29,
                'name' => '长沙创一',
                'code' => 'CSCY',
            ],

            [
                'id' => 30,
                'name' => '成都善途速运',
                'code' => 'CDSTKY',
            ],

            [
                'id' => 31,
                'name' => 'D速物流',
                'code' => 'DSWL',
            ],

            [
                'id' => 32,
                'name' => '大田物流',
                'code' => 'DTWL',
            ],

            [
                'id' => 34,
                'name' => 'FEDEX联邦(国内件）',
                'code' => 'FEDEX',
            ],

            [
                'id' => 35,
                'name' => 'FEDEX联邦(国际件）',
                'code' => 'FEDEX_GJ',
            ],

            [
                'id' => 36,
                'name' => '飞康达',
                'code' => 'FKD',
            ],

            [
                'id' => 37,
                'name' => '广东邮政',
                'code' => 'GDEMS',
            ],

            [
                'id' => 38,
                'name' => '共速达',
                'code' => 'GSD',
            ],

            [
                'id' => 39,
                'name' => '高铁速递',
                'code' => 'GTSD',
            ],

            [
                'id' => 19,
                'name' => '汇丰物流',
                'code' => 'HFWL',
            ],

            [
                'id' => 40,
                'name' => '恒路物流',
                'code' => 'HLWL',
            ],

            [
                'id' => 41,
                'name' => '天地华宇',
                'code' => 'HOAU',
            ],

            [
                'id' => 97,
                'name' => '鸿桥供应链',
                'code' => 'HOTSCM',
            ],

            [
                'id' => 98,
                'name' => '海派通物流公司',
                'code' => 'HPTEX',
            ],

            [
                'id' => 42,
                'name' => '华强物流',
                'code' => 'hq568',
            ],

            [
                'id' => 43,
                'name' => '华夏龙物流',
                'code' => 'HXLWL',
            ],

            [
                'id' => 44,
                'name' => '好来运快递',
                'code' => 'HYLSD',
            ],

            [
                'id' => 45,
                'name' => '京广速递',
                'code' => 'JGSD',
            ],

            [
                'id' => 46,
                'name' => '九曳供应链',
                'code' => 'JIUYE',
            ],

            [
                'id' => 47,
                'name' => '佳吉快运',
                'code' => 'JJKY',
            ],

            [
                'id' => 48,
                'name' => '嘉里物流',
                'code' => 'JLDT',
            ],

            [
                'id' => 49,
                'name' => '捷特快递',
                'code' => 'JTKD',
            ],

            [
                'id' => 50,
                'name' => '急先达',
                'code' => 'JXD',
            ],

            [
                'id' => 51,
                'name' => '晋越快递',
                'code' => 'JYKD',
            ],

            [
                'id' => 52,
                'name' => '加运美',
                'code' => 'JYM',
            ],

            [
                'id' => 53,
                'name' => '佳怡物流',
                'code' => 'JYWL',
            ],

            [
                'id' => 54,
                'name' => '跨越物流',
                'code' => 'KYWL',
            ],

            [
                'id' => 63,
                'name' => '如风达',
                'code' => 'RFD',
            ],

            [
                'id' => 94,
                'name' => '瑞丰速递',
                'code' => 'RFEX',
            ],

            [
                'id' => 64,
                'name' => '赛澳递',
                'code' => 'SAD',
            ],

            [
                'id' => 65,
                'name' => '圣安物流',
                'code' => 'SAWL',
            ],

            [
                'id' => 66,
                'name' => '盛邦物流',
                'code' => 'SBWL',
            ],

            [
                'id' => 67,
                'name' => '上大物流',
                'code' => 'SDWL',
            ],

            [
                'id' => 68,
                'name' => '盛丰物流',
                'code' => 'SFWL',
            ],

            [
                'id' => 69,
                'name' => '盛辉物流',
                'code' => 'SHWL',
            ],

            [
                'id' => 70,
                'name' => '速通物流',
                'code' => 'ST',
            ],

            [
                'id' => 71,
                'name' => '速腾快递',
                'code' => 'STWL',
            ],

            [
                'id' => 93,
                'name' => '速必达物流',
                'code' => 'SUBIDA',
            ],

            [
                'id' => 72,
                'name' => '速尔快递',
                'code' => 'SURE',
            ],

            [
                'id' => 73,
                'name' => '唐山申通',
                'code' => 'TSSTO',
            ],

            [
                'id' => 74,
                'name' => '全一快递',
                'code' => 'UAPEX',
            ],

            [
                'id' => 102,
                'name' => 'UEQ Express',
                'code' => 'UEQ',
            ],

            [
                'id' => 75,
                'name' => '万家物流',
                'code' => 'WJWL',
            ],

            [
                'id' => 76,
                'name' => '万象物流',
                'code' => 'WXWL',
            ],

            [
                'id' => 77,
                'name' => '新邦物流',
                'code' => 'XBWL',
            ],

            [
                'id' => 78,
                'name' => '信丰物流',
                'code' => 'XFEX',
            ],

            [
                'id' => 79,
                'name' => '希优特',
                'code' => 'XYT',
            ],

            [
                'id' => 80,
                'name' => '新杰物流',
                'code' => 'XJ',
            ],

            [
                'id' => 81,
                'name' => '源安达快递',
                'code' => 'YADEX',
            ],

            [
                'id' => 82,
                'name' => '远成物流',
                'code' => 'YCWL',
            ],
            [
                'id' => 104,
                'name' => '丰恒物流',
                'code' => 'FHWL',
            ],
            [
                'id' => 105,
                'name' => '佳润达物流',
                'code' => 'JRDWL',
            ],
            [
                'id' => 1001,
                'name' => '邮政包裹信件',
                'code' => 'YZPY',
            ],
            [
                'id' => 1002,
                'name' => '安能快递',
                'code' => 'ANE',
            ],
            [
                'id' => 1003,
                'name' => '程光',
                'code' => 'CG',
            ],
            [
                'id' => 1004,
                'name' => '富腾达',
                'code' => 'FTD',
            ],
            [
                'id' => 1005,
                'name' => '中通快运',
                'code' => 'ZTOKY',
            ],
            [
                'id' => 1006,
                'name' => '品骏快递',
                'code' => 'PJ',
            ],
            [
                'id' => 1007,
                'name' => 'EWE',
                'code' => 'EWE',
            ],
            [
                'id' => 1008,
                'name' => '特急送',
                'code' => 'TJS',
            ],
            [
                'id' => 1009,
                'name' => '承诺达',
                'code' => 'CND',
            ],
        ];
    }

    // 根据快递公司名称获取数据
    public static function getOne($param)
    {
        $return = false;
        if (!$param) {
            return $return;
        }
        $expressList = self::getExpressList();
        foreach ($expressList as $item) {
            if ($item['name'] == $param) {
                $return = $item;
                break;
            }
        }
        return $return;
    }

    /**
     * 获取数组中最后一个Id的值，用于追加数据
     * @return mixed
     */
    public static function highestId()
    {
        $express = self::getExpressList();
        $ids = [];
        foreach ($express as $item) {
            $ids[] = $item['id'];
        }
        rsort($ids);

        return reset($ids);
    }

    /**
     * @return array
     * 快递鸟提供的电子面单模板规格
     */
    public static function getTemplateSize()
    {
        return [
            'DBL' => [
                [
                    'name' => '二联177',
                    'value' => ''
                ],
                [
                    'name' => '二联177新',
                    'value' => '18001'
                ],
                [
                    'name' => '三联177新',
                    'value' => '18002'
                ],
            ],
            'EMS' => [
                [
                    'name' => '二联150',
                    'value' => ''
                ],
                [
                    'name' => '隐私二联150',
                    'value' => '180_YS'
                ],
            ],
            'KYSY' => [
                [
                    'name' => '二联137',
                    'value' => ''
                ],
                [
                    'name' => '三联210',
                    'value' => '210'
                ],
            ],
            'SF' => [
                [
                    'name' => '二联150',
                    'value' => ''
                ],
                [
                    'name' => '三联210',
                    'value' => '210'
                ],
                [
                    'name' => '二联150新',
                    'value' => '15001'
                ],
                [
                    'name' => '二联180新',
                    'value' => '180'
                ],
                [
                    'name' => '三联210新',
                    'value' => '21001'
                ],
            ],
            'STO' => [
                [
                    'name' => '二联180',
                    'value' => ''
                ],
                [
                    'name' => '二联150',
                    'value' => '150'
                ],
                [
                    'name' => '二联180新',
                    'value' => '180'
                ],
                [
                    'name' => '三联180新',
                    'value' => '18003'
                ],
                [
                    'name' => '隐私二联180新',
                    'value' => '180_YS'
                ],
            ],
            'SURE' => [
                [
                    'name' => '二联150',
                    'value' => ''
                ],
                [
                    'name' => '二联150新',
                    'value' => '150'
                ],
                [
                    'name' => '二联180新',
                    'value' => '180'
                ],
            ],
            'YD' => [
                [
                    'name' => '二联203',
                    'value' => ''
                ],
                [
                    'name' => '二联180',
                    'value' => '180'
                ],
                [
                    'name' => '隐私二联180',
                    'value' => '180_YS'
                ],
            ],
            'YTO' => [
                [
                    'name' => '二联180',
                    'value' => ''
                ],
                [
                    'name' => '三联180',
                    'value' => '180'
                ],
                [
                    'name' => '二联180新',
                    'value' => '18001'
                ],
                [
                    'name' => '隐私二联180新',
                    'value' => '18001_YS'
                ],
            ],
            'ZJS' => [
                [
                    'name' => '二联120',
                    'value' => ''
                ],
                [
                    'name' => '二联180',
                    'value' => '180'
                ],
            ],
            'ZTO' => [
                [
                    'name' => '二联180',
                    'value' => ''
                ],
                [
                    'name' => '二联180新',
                    'value' => '180'
                ],
                [
                    'name' => '隐私二联180新',
                    'value' => '180_YS'
                ],
            ],
        ];
    }
}
