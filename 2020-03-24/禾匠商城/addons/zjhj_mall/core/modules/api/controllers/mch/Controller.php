<?php
/**
 * @link http://www.zjhejiang.com/
 * @copyright Copyright (c) 2018 浙江禾匠信息科技有限公司
 * @author Lu Wei
 *
 * Created by IntelliJ IDEA.
 * User: luwei
 * Date: 2018/3/22
 * Time: 13:40
 */


namespace app\modules\api\controllers\mch;

use app\models\Mch;
use app\modules\api\behaviors\LoginBehavior;
use app\modules\api\behaviors\MchBehavior;
use app\modules\api\behaviors\VisitBehavior;

/**
 * Class Controller
 * @package app\modules\api\controllers\mch
 * @property Mch $mch
 */
class Controller extends \app\modules\api\controllers\Controller
{
    /** @var  Mch $mch */
    public $mch;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'login' => [
                'class' => LoginBehavior::className(),
            ],
            'mch' => [
                'class' => MchBehavior::className(),
            ],
            'visit' => [
                'class' => VisitBehavior::className(),
            ],
        ]);
    }
}
