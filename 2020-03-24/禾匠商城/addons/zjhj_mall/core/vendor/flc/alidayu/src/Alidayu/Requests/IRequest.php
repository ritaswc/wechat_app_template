<?php
namespace Flc\Alidayu\Requests;

/**
 * 阿里大于 - 请求接口类
 *
 * @author Flc <2016-09-18 19:43:18>
 * @link   http://flc.ren
 */
Interface IRequest
{
    /**
     * 获取接口名称
     * @return string
     */
    public function getMethod();

    /**
     * 获取请求参数
     * @return array 
     */
    public function getParams();
}
