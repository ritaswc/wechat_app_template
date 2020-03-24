<?php

namespace Hejiang\Express\Trackers;

use Hejiang\Express\Exceptions\TrackingException;
use Hejiang\Express\Status;
use Hejiang\Express\Waybill;

class Kuaidi100 extends BaseTracker implements TrackerInterface
{
    use TrackerTrait;

    public static function getSupportedExpresses()
    {
        return [
            '澳大利亚邮政' => 'auspost',
            'AAE' => 'aae',
            '安信达' => 'anxindakuaixi',
            '百世' => 'huitongkuaidi',
            '百福东方' => 'baifudongfang',
            'BHT' => 'bht',
            '邦送' => 'bangsongwuliu',
            '希伊艾斯（CCES）' => 'cces',
            '中国东方（COE）' => 'coe',
            '传喜' => 'chuanxiwuliu',
            '加拿大邮政' => 'canpost',
            '大田' => 'datianwuliu',
            '德邦' => 'debangwuliu',
            'DPEX' => 'dpex',
            'DHL' => 'dhl',
            'DHL国际' => 'dhlen',
            'DHL德国' => 'dhlde',
            'D速' => 'dsukuaidi',
            '递四方' => 'disifang',
            'EMS' => 'ems',
            'EMS英文' => 'emsen',
            'EMS国际' => 'emsguoji',
            'EMS国际英文' => 'emsinten',
            'Fedex英文' => 'fedex',
            'Fedex' => 'fedexcn',
            'Fedex美国' => 'fedexus',
            '飞康达' => 'feikangda',
            '飞快达' => 'feikuaida',
            '凡客如风达' => 'rufengda',
            '风行天下' => 'fengxingtianxia',
            '飞豹' => 'feibaokuaidi',
            '港中能达' => 'ganzhongnengda',
            '国通' => 'guotongkuaidi',
            '广东邮政' => 'guangdongyouzhengwuliu',
            'GLS' => 'gls',
            '共速达' => 'gongsuda',
            '汇通' => 'huitongkuaidi',
            '汇强' => 'huiqiangkuaidi',
            '华宇' => 'tiandihuayu',
            '恒路' => 'hengluwuliu',
            '华夏龙' => 'huaxialongwuliu',
            '海航天天' => 'tiantian',
            '海外环球' => 'haiwaihuanqiu',
            '河北建华' => 'hebeijianhua',
            '海盟' => 'haimengsudi',
            '华企' => 'huaqikuaiyun',
            '山东海红' => 'haihongwangsong',
            '佳吉' => 'jiajiwuliu',
            '佳怡' => 'jiayiwuliu',
            '加运美' => 'jiayunmeiwuliu',
            '京广' => 'jinguangsudikuaijian',
            '急先达' => 'jixianda',
            '晋越' => 'jinyuekuaidi',
            '捷特' => 'jietekuaidi',
            '金大' => 'jindawuliu',
            '嘉里大通' => 'jialidatong',
            '快捷' => 'kuaijiesudi',
            '康力' => 'kangliwuliu',
            '跨越' => 'kuayue',
            '联昊通' => 'lianhaowuliu',
            '龙邦' => 'longbanwuliu',
            '蓝镖' => 'lanbiaokuaidi',
            '乐捷递' => 'lejiedi',
            '联邦' => 'lianbangkuaidi',
            '联邦英文' => 'lianbangkuaidien',
            '立即送' => 'lijisong',
            '隆浪' => 'longlangkuaidi',
            '门对门' => 'menduimen',
            '美国' => 'meiguokuaidi',
            '明亮' => 'mingliangwuliu',
            'OCS' => 'ocs',
            'OnTrac' => 'ontrac',
            '全晨' => 'quanchenkuaidi',
            '全际通' => 'quanjitong',
            '全日通' => 'quanritongkuaidi',
            '全一' => 'quanyikuaidi',
            '全峰' => 'quanfengkuaidi',
            '七天连锁' => 'sevendays',
            '如风达' => 'rufengda',
            '国内邮件' => 'youzhengguonei',
            '国际邮件' => 'youzhengguoji',
            '申通' => 'shentong',
            '顺丰' => 'shunfeng',
            '顺丰（英文结果）' => 'shunfengen',
            '三态' => 'santaisudi',
            '盛辉' => 'shenghuiwuliu',
            '速尔' => 'suer',
            '盛丰' => 'shengfengwuliu',
            '上大' => 'shangda',
            '三态' => 'santaisudi',
            '山东海红' => 'haihongwangsong',
            '赛澳递' => 'saiaodi',
            '山西红马甲' => 'sxhongmajia',
            '圣安' => 'shenganwuliu',
            '穗佳' => 'suijiawuliu',
            '天地华宇' => 'tiandihuayu',
            '天天' => 'tiantian',
            'TNT（中文结果）' => 'tnt',
            'TNT（英文结果）' => 'tnten',
            '通和天下' => 'tonghetianxia',
            'UPS（中文结果）' => 'ups',
            'UPS（英文结果）' => 'upsen',
            '优速' => 'youshuwuliu',
            'USPS（中英文）' => 'usps',
            '万家' => 'wanjiawuliu',
            '万象' => 'wanxiangwuliu',
            '微特派' => 'weitepai',
            '新邦' => 'xinbangwuliu',
            '信丰' => 'xinfengwuliu',
            '新邦' => 'xinbangwuliu',
            '新蛋奥硕' => 'neweggozzo',
            '香港邮政' => 'hkpost',
            '圆通' => 'yuantong',
            '韵达' => 'yunda',
            '运通' => 'yuntongkuaidi',
            '远成' => 'yuanchengwuliu',
            '亚风' => 'yafengsudi',
            '一邦' => 'yibangwuliu',
            '优速' => 'youshuwuliu',
            '源伟丰' => 'yuanweifeng',
            '元智捷诚' => 'yuanzhijiecheng',
            '越丰' => 'yuefengwuliu',
            '源安达' => 'yuananda',
            '原飞航' => 'yuanfeihangwuliu',
            '忠信达' => 'zhongxinda',
            '芝麻开门' => 'zhimakaimen',
            '银捷' => 'yinjiesudi',
            '一统飞鸿' => 'yitongfeihong',
            '中通' => 'zhongtong',
            '宅急送' => 'zhaijisong',
            '中邮' => 'zhongyouwuliu',
            '忠信达' => 'zhongxinda',
            '中速快件' => 'zhongsukuaidi',
            '芝麻开门' => 'zhimakaimen',
            '郑州建华' => 'zhengzhoujianhua',
            '中天万运' => 'zhongtianwanyun',
            '邮政' => 'youzhengguonei',
            '邮政国际' => 'youzhengguoji',
        ];
    }

    public function track(Waybill $waybill)
    {
        $apiUrl = 'http://www.kuaidi100.com/query?type='
            . urlencode(static::getExpressCode($waybill->express))
            . '&postid='
            . urlencode($waybill->id);
        $curl = static::httpGet($apiUrl);
        $response = static::getJsonResponse($curl);

        if ($response->status != 200) {
            throw new TrackingException($response->message, $response);
        }
        $statusMap = [
            0 => Status::STATUS_TRANSPORTING,
            1 => Status::STATUS_PICKEDUP,
            2 => Status::STATUS_REJECTED,
            3 => Status::STATUS_DELIVERED,
            4 => Status::STATUS_RETURNED,
            5 => Status::STATUS_DELIVERING,
            6 => Status::STATUS_RETURNING,
        ];
        $waybill->status = $statusMap[intval($response->state)];
        foreach ($response->data as $trace) {
            $waybill->traces->append($trace->time, $trace->context, $trace->location);
        }
    }
}
