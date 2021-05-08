<?php
/**
 * @link:http://www.zjhejiang.com/
 * @copyright: Copyright (c) 2018 浙江禾匠信息科技有限公司
 *
 * Created by PhpStorm.
 * User: 风哀伤
 * Date: 2018/11/19
 * Time: 11:44
 */

namespace app\models\task\order;


use app\hejiang\task\TaskRunnable;
use app\models\ActionLog;
use app\models\IntegralOrder;
use app\models\Mch;
use app\models\MchAccountLog;
use app\models\MchPlugin;
use app\models\MchSetting;
use app\models\MsOrder;
use app\models\Order;
use app\models\OrderDetail;
use app\models\OrderShare;
use app\models\PtOrder;
use app\models\Register;
use app\models\Setting;
use app\models\Store;
use app\models\User;
use app\models\UserShareMoney;

/**
 * @property Store $store
 */
class OrderAuthSale extends TaskRunnable
{
    const STORE = 'STORE';
    const MS = 'MIAOSHA';
    const PT = 'PINTUAN';
    const INTEGRAL = 'INTEGRAL';

    public $store;
    public $actionType;
    public $params = [];
    /* @var $order Order|PtOrder|MsOrder|IntegralOrder */
    public $order;

    public function run($param = [])
    {

    }
}