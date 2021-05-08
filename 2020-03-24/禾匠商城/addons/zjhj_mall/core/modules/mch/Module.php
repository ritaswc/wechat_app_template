<?php

namespace app\modules\mch;

/**
 * mch module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\mch\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // 挂载插件
        // 例如：XX::init($this);

        // 重定向插件控制器
//        $map = [
//            'test' => 'store',
//            'test2' => 'book/goods'
//        ];
//        array_walk($map, [$this, 'redirectController']);
        \app\plugins\bargain\init\Controller::init($this);
    }

    protected $controllerRedirectMap = [];

    /**
     * 重定向控制器
     *
     * @param string $existed 实际存在的控制器ID
     * @param string $id 虚拟控制器ID
     * @return void
     */
    public function redirectController($existed, $virtual)
    {
        $this->controllerRedirectMap[$virtual] = $existed;
    }

    public function createControllerByID($id)
    {
        if(isset($this->controllerRedirectMap[$id])) {
            $id = $this->controllerRedirectMap[$id];
        }
        return parent::createControllerByID($id);
    }
}
