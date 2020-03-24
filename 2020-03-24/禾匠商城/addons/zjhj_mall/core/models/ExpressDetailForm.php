<?php
/**
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2017/8/21
 * Time: 9:58
 */

namespace app\models;

use Curl\Curl;
use Hejiang\Express\Exceptions\TrackingException;
use Hejiang\Express\Trackers\TrackerInterface;
use Hejiang\Express\Waybill;
use yii\helpers\VarDumper;

class ExpressDetailForm extends Model
{
    public $express_no;
    public $express;
    public $store_id;

    public $status_text = [
        1 => '?',
        2 => '运输中',
        3 => '已签收',
        4 => '问题件',
    ];

    public function rules()
    {
        return [
            [['express', 'express_no'], 'trim'],
            [['express_no', 'express', 'store_id'], 'required'],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        return $this->getData();
    }

    private function transExpressName($name)
    {
        if (!$name) {
            return false;
        }
        $append_list = [
            '快递',
            '快运',
            '物流',
            '速运',
            '速递',
        ];
        foreach ($append_list as $append) {
            $name = str_replace($append, '', $name);
        }

        $name_map_list = [
            '邮政快递包裹' => '邮政',
            '邮政包裹信件' => '邮政',
        ];
        if (isset($name_map_list[$name])) {
            $name = $name_map_list[$name];
        }
        return $name;
    }

    private function getData()
    {
        /**@var array $status_map 定义在 Hejiang\Express\Status */
        $status_map = [
            -1 => '已揽件',
            0 => '已揽件',
            1 => '已发出',
            2 => '在途中',
            3 => '派件中',
            4 => '已签收',
            5 => '已自取',
            6 => '问题件',
            7 => '已退回',
            8 => '已退签',
        ];

        /** @var Waybill $wb */
        $wb = \Yii::createObject([
            'class' => 'Hejiang\Express\Waybill',
            'id' => $this->express_no,
            'express' => $this->transExpressName($this->express),
        ]);

        $tracker_class_list = [
            // 'Hejiang\Express\Trackers\Kuaidi100',
            'Hejiang\Express\Trackers\Kuaidiniao',
            'Hejiang\Express\Trackers\Kuaidiwang',
        ];

        foreach ($tracker_class_list as $tracker_class) {
            $class_args = [
                'class' => $tracker_class,
            ];

            if ($tracker_class == 'Hejiang\Express\Trackers\Kuaidiniao') {
                list($EBusinessID, $AppKey) = $this->getKuaidiniaoConfig();
                $class_args['EBusinessID'] = $EBusinessID;
                $class_args['AppKey'] = $AppKey;
            }

            /** @var TrackerInterface $tracker */
            $tracker = \Yii::createObject($class_args);
            try {
                $list = $wb->getTraces($tracker)->toArray();
                if (!is_array($list)) {
                    return [
                        'code' => 1,
                        'msg' => '物流信息查询失败。',
                    ];
                }
                foreach ($list as &$item) {
                    $item['datetime'] = $item['time'];
                    $item['detail'] = $item['desc'];
                    unset($item['time']);
                    unset($item['desc']);
                }
            } catch (TrackingException $ex) {
                continue;
            }
            if (isset($status_map[$wb->status])) {
                $status_text = $status_map[$wb->status];
            } else {
                $status_text = '状态未知';
            }
            return [
                'code' => 0,
                'data' => [
                    'list' => $list,
                    'status' => $wb->status,
                    'status_text' => $status_text,
                ],
            ];
        }
        return [
            'code' => 1,
            'msg' => '未查询到物流信息。',
        ];
    }

    private function getKuaidiniaoConfig()
    {
        $store = Store::findOne($this->store_id);
        if (!$store || !$store->kdniao_mch_id || !$store->kdniao_api_key) {
            return ['', ''];
        }
        $mch_id = $store->kdniao_mch_id;
        $api_key = $store->kdniao_api_key;
        return [$mch_id, $api_key];
    }
}
