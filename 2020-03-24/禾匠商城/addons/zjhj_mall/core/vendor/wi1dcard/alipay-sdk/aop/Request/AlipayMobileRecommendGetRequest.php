<?php
/**
 * ALIPAY API: alipay.mobile.recommend.get request
 *
 * @author auto create
 *
 * @since  1.0, 2015-03-11 15:19:54
 */

namespace Alipay\Request;

class AlipayMobileRecommendGetRequest extends AbstractAlipayRequest
{
    /**
     * 请求上下文扩展信息，需要与接口负责人约定。格式为json对象。
     **/
    private $extInfo;
    /**
     * 期望获取的最多推荐数量, 默认获取一个推荐内容, 0表示获取所有推荐内容
     **/
    private $limit;
    /**
     * 所使用的场景id，请向接口负责人申请
     **/
    private $sceneId;
    /**
     * 获取推荐信息的开始位置, 默认从0开始
     **/
    private $startIdx;
    /**
     * 用户openid
     **/
    private $userId;

    public function setExtInfo($extInfo)
    {
        $this->extInfo = $extInfo;
        $this->apiParams['ext_info'] = $extInfo;
    }

    public function getExtInfo()
    {
        return $this->extInfo;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
        $this->apiParams['limit'] = $limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setSceneId($sceneId)
    {
        $this->sceneId = $sceneId;
        $this->apiParams['scene_id'] = $sceneId;
    }

    public function getSceneId()
    {
        return $this->sceneId;
    }

    public function setStartIdx($startIdx)
    {
        $this->startIdx = $startIdx;
        $this->apiParams['start_idx'] = $startIdx;
    }

    public function getStartIdx()
    {
        return $this->startIdx;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        $this->apiParams['user_id'] = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
