<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/4/29
 * Time: 20:06
 */


namespace app\modules\mch\models\mch;

use app\models\Mch;
use app\models\MchCash;
use app\models\User;
use app\modules\mch\models\MchModel;
use yii\data\Pagination;

class CashListForm extends MchModel
{
    public $store_id;
    public $status;
    public $page;

    public function rules()
    {
        return [
            [['status', 'page'], 'integer'],
            [['page'], 'default', 'value' => 1],
        ];
    }

    public function search()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        $query = MchCash::find()->alias('mc')
            ->leftJoin(['m' => Mch::tableName()], 'mc.mch_id=m.id')
            ->leftJoin(['u' => User::tableName()], 'm.user_id=u.id')
            ->where([
                'mc.store_id' => $this->store_id,
            ]);
        if ($this->status != -1) {
            $query->andWhere(['mc.status' => $this->status]);
        }
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'page' => $this->page - 1,]);
        $list = $query->select('u.nickname,u.platform,m.name,mc.*')
            ->limit($pagination->limit)->offset($pagination->offset)
            ->orderBy('mc.addtime DESC')
            ->asArray()->all();
        foreach ($list as &$item) {
            $item['addtime'] = date('Y-m-d H:i:s', $item['addtime']);
            $type_data = $item['type_data'] ? \Yii::$app->serializer->decode($item['type_data']) : '';

            $content = "";
            switch ($item['type']) {
                case 0:
                    $content .= "转账方式：微信自动转账<br>";
                    break;
                case 1:
                    $content .= "转账方式：微信线下转账<br>";
                    $content .= "微信号：{$type_data['account']}<br>";
                    $content .= "微信昵称：{$type_data['nickname']}<br>";
                    break;
                case 2:
                    $content .= "转账方式：支付宝线下转账<br>";
                    $content .= "支付宝账号：{$type_data['account']}<br>";
                    $content .= "支付宝昵称：{$type_data['nickname']}<br>";
                    break;
                case 3:
                    $content .= "转账方式：转账到银行<br>";
                    $content .= "银行卡号：{$type_data['account']}<br>";
                    $content .= "开户人：{$type_data['nickname']}<br>";
                    $content .= "开户行：{$type_data['bank_name']}<br>";
                    break;
                case 4:
                    $content .= "转账方式：转账到余额<br>";
                    break;
                default:
                    break;
            }
            if ($item['status'] == 1) {
                switch ($item['virtual_type']) {
                    case 0:
                        $content .= "实际转账方式：微信自动转账<br>";
                        break;
                    case 1:
                        $content .= "实际转账方式：微信线下转账<br>";
                        break;
                    case 2:
                        $content .= "实际转账方式：支付宝线下转账<br>";
                        break;
                    case 3:
                        $content .= "实际转账方式：转账到银行<br>";
                        break;
                    case 4:
                        $content .= "实际转账方式：转账到余额<br>";
                        break;
                    default:
                        break;
                }
            }
            $item['account_content'] = $content;
        }
        return [
            'code' => 0,
            'data' => [
                'pagination' => $pagination,
                'list' => $list,
            ],
        ];
    }
}
