<?php
/**
 * @copyright ©2018 浙江禾匠信息科技
 * @author Lu Wei
 * @link http://www.zjhejiang.com/
 * Created by IntelliJ IDEA
 * Date Time: 2018/10/24 10:22
 */


namespace app\hejiang\cloud;


class CloudTask
{
    /**
     * @param string $url 回调访问的url，注意不要加域名，授权系统自动加上
     * @param int $delay_seconds 任务执行延时，秒
     * @return mixed
     * @throws CloudException
     */
    public static function create($url, $delay_seconds)
    {
        return [
            'code' => 0,
            'msg' => 'Create task cancel.',
            'data' => [],
        ];
    }
}
