<?php

namespace Hejiang\Express\Trackers;

use Curl\Curl;
use Hejiang\Express\Exceptions\TrackingException;
use Hejiang\Express\Waybill;
use Hejiang\Express\Status;

class Kuaidiniao extends BaseTracker implements TrackerInterface
{
    use TrackerTrait;

    public $EBusinessID;

    public $AppKey;

    public static function getSupportedExpresses()
    {
        return [
            '京东' => 'JD',
            '顺丰' => 'SF',
            '申通' => 'STO',
            '韵达' => 'YD',
            '圆通' => 'YTO',
            '中通' => 'ZTO',
            '百世' => 'HTKY',
            'EMS' => 'EMS',
            '天天' => 'HHTT',
            '邮政' => 'YZPY',
            '宅急送' => 'ZJS',
            '国通' => 'GTO',
            '全峰' => 'QFKD',
            '优速' => 'UC',
            '中铁' => 'ZTKY',
            '中铁' => 'ZTWL',
            '亚马逊' => 'AMAZON',
            '城际' => 'CJKD',
            '德邦' => 'DBL',
            '汇丰' => 'HFWL',
            // '百世' => 'BTWL',
            '安捷' => 'AJ',
            '安能' => 'ANE',
            '安信达' => 'AXD',
            '北青小红帽' => 'BQXHM',
            '百福东方' => 'BFDF',
            'CCES' => 'CCES',
            '城市100' => 'CITY100',
            'COE东方' => 'COE',
            '长沙创一' => 'CSCY',
            '成都善途' => 'CDSTKY',
            'D速' => 'DSWL',
            '大田' => 'DTWL',
            '快捷' => 'FAST',
            '联邦' => 'FEDEX',
            'FEDEX' => 'FEDEX_GJ',
            '飞康达' => 'FKD',
            '广东邮政' => 'GDEMS',
            '共速达' => 'GSD',
            '高铁' => 'GTSD',
            '恒路' => 'HLWL',
            '天地华宇' => 'HOAU',
            '华强' => 'hq568',
            '华夏龙' => 'HXLWL',
            '好来运' => 'HYLSD',
            '京广' => 'JGSD',
            '九曳供应链' => 'JIUYE',
            '佳吉' => 'JJKY',
            '嘉里' => 'JLDT',
            '捷特' => 'JTKD',
            '急先达' => 'JXD',
            '晋越' => 'JYKD',
            '加运美' => 'JYM',
            '佳怡' => 'JYWL',
            '跨越' => 'KYWL',
            '龙邦' => 'LB',
            '联昊通' => 'LHT',
            '民航' => 'MHKD',
            '明亮' => 'MLWL',
            '能达' => 'NEDA',
            '平安达腾飞' => 'PADTF',
            '全晨' => 'QCKD',
            '全日通' => 'QRT',
            '如风达' => 'RFD',
            '赛澳递' => 'SAD',
            '圣安' => 'SAWL',
            '盛邦' => 'SBWL',
            '上大' => 'SDWL',
            '盛丰' => 'SFWL',
            '盛辉' => 'SHWL',
            '速通' => 'ST',
            '速腾' => 'STWL',
            '速尔' => 'SURE',
            '唐山申通' => 'TSSTO',
            '全一' => 'UAPEX',
            '万家' => 'WJWL',
            '万象' => 'WXWL',
            '新邦' => 'XBWL',
            '信丰' => 'XFEX',
            '希优特' => 'XYT',
            '新杰' => 'XJ',
            '源安达' => 'YADEX',
            '远成' => 'YCWL',
            '义达' => 'YDH',
            '越丰' => 'YFEX',
            '原飞航' => 'YFHEX',
            '亚风' => 'YFSD',
            '运通' => 'YTKD',
            '亿翔' => 'YXKD',
            '增益' => 'ZENY',
            '汇强' => 'ZHQKD',
            '众通' => 'ZTE',
            '中邮' => 'ZYWL',
            '速必达' => 'SUBIDA',
            '瑞丰' => 'RFEX',
            '快客' => 'QUICK',
            'CNPEX中邮' => 'CNPEX',
            '鸿桥供应链' => 'HOTSCM',
            '海派通' => 'HPTEX',
            '澳邮专线' => 'AYCA',
            '泛捷' => 'PANEX',
            'PCA Express' => 'PCA',
            'UEQ Express' => 'UEQ',
            '程光' => 'CG',
            '富腾达' => 'FTD',
            '中通快运' => 'ZTOKY',
            '品骏' => 'PJ',
            'EWE' => 'EWE',
            '特急送' => 'TJS',
            '承诺达' => 'CND',
        ];
    }

    public function track(Waybill $waybill)
    {
        $curl = new Curl();
        $requestData = json_encode([
            'ShipperCode' => static::getExpressCode($waybill->express),
            'LogisticCode' => $waybill->id,
            'OrderCode' => $waybill->orderId,
        ]);
        $postContent1 = [
            'RequestData' => urlencode($requestData),
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1008',
            'DataSign' => base64_encode(md5($requestData . $this->AppKey)),
            'DataType' => '2',
        ];
        $curl->post(
            'http://api.kdniao.com/api/dist',
            $postContent1
        );
        $response = static::getJsonResponse($curl);
        $postContent2 = [
            'RequestData' => urlencode($requestData),
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1002',
            'DataSign' => base64_encode(md5($requestData . $this->AppKey)),
            'DataType' => '2',
        ];
        $curl->post(
            'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx',
            $postContent2
        );
        $response = static::getJsonResponse($curl);
        if ($response->Success == false) {
            $postContent3 = [
                'RequestData' => urlencode($requestData),
                'EBusinessID' => $this->EBusinessID,
                'RequestType' => '8001',
                'DataSign' => base64_encode(md5($requestData . $this->AppKey)),
                'DataType' => '2',
            ];
            $curl->post(
                'http://api.kdniao.com/Ebusiness/EbusinessOrderHandle.aspx',
                $postContent3
            );
            $response = static::getJsonResponse($curl);
        }

        if ($response->Success == false) {
            throw new TrackingException($response->Reason, $response);
        }
        $statusMap = [
            2 => Status::STATUS_TRANSPORTING,
            3 => Status::STATUS_DELIVERED,
            4 => Status::STATUS_REJECTED,
        ];
        $waybill->status = $statusMap[intval($response->State)];
        foreach ($response->Traces as $trace) {
            $waybill->traces->append($trace->AcceptTime, $trace->AcceptStation, $trace->Remark);
        }
    }
}
