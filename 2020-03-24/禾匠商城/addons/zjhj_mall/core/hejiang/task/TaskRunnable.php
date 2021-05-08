<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/24 10:07
 */


namespace app\hejiang\task;


use yii\base\BaseObject;

abstract class TaskRunnable extends BaseObject
{
    /**
     * @param array $params
     * @return boolean
     */
    public abstract function run($params = []);
}