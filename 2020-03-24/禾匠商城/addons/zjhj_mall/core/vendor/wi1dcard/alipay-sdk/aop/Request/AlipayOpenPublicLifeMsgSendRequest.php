<?php
/**
 * ALIPAY API: alipay.open.public.life.msg.send request
 *
 * @author auto create
 *
 * @since  1.0, 2018-04-17 14:24:09
 */

namespace Alipay\Request;

class AlipayOpenPublicLifeMsgSendRequest extends AbstractAlipayRequest
{
    /**
     * 消息分类，请传入对应分类编码值
     **/
    private $category;
    /**
     * 消息正文，html原文或纯文本
     **/
    private $content;
    /**
     * 消息背景图片（目前支持格式jpg, jpeg, bmp），需上传图片原始二进制流，图片最大1MB
     **/
    private $cover;
    /**
     * 消息概述
     **/
    private $desc;
    /**
     * 媒体资讯类生活号消息类型
     **/
    private $msgType;
    /**
     * 消息来源方附属信息，供搜索、推荐使用
     * publish_time（int）：消息发布时间，单位秒
     * keyword_list（String）: 文章的标签列表，英文逗号分隔
     * comment（int）：消息来源处评论次数
     * reward（int）：消息来源处打赏次数
     * is_recommended（boolean）：消息在来源处是否被推荐
     * is_news（boolean）：消息是否实时性内容
     * read（int）：消息在来源处被阅读次数
     * like（int）：消息在来源处被点赞次数
     * is_hot（boolean）：消息在来源平台是否是热门内容
     * share（int）：文章在来源平台的分享次数
     * deadline（int）：文章的失效时间，单位秒
     **/
    private $sourceExtInfo;
    /**
     * 消息标题
     **/
    private $title;
    /**
     * 来源方消息唯一标识；若不为空，根据此id和生活号id对消息去重；若为空，不去重
     **/
    private $uniqueMsgId;
    /**
     * 生活号消息视频时长，单位：秒（视频类消息必填）
     **/
    private $videoLength;
    /**
     * 视频类型消息中视频抽样关键帧截图，视频类消息选填
     **/
    private $videoSamples;
    /**
     * 视频大小，单位：KB（视频类消息必填）
     **/
    private $videoSize;
    /**
     * 视频资源来源id（视频类消息必填），取值限定youku, miaopai, taobao, sina中的一个
     **/
    private $videoSource;
    /**
     * 视频的临时链接（优酷来源的视频消息，该字段不能为空）
     **/
    private $videoTemporaryUrl;
    /**
     * 生活号视频类消息视频id或url（视频类消息必填，根据来源区分）
     **/
    private $videoUrl;

    public function setCategory($category)
    {
        $this->category = $category;
        $this->apiParams['category'] = $category;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setContent($content)
    {
        $this->content = $content;
        $this->apiParams['content'] = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setCover($cover)
    {
        $this->cover = $cover;
        $this->apiParams['cover'] = $cover;
    }

    public function getCover()
    {
        return $this->cover;
    }

    public function setDesc($desc)
    {
        $this->desc = $desc;
        $this->apiParams['desc'] = $desc;
    }

    public function getDesc()
    {
        return $this->desc;
    }

    public function setMsgType($msgType)
    {
        $this->msgType = $msgType;
        $this->apiParams['msg_type'] = $msgType;
    }

    public function getMsgType()
    {
        return $this->msgType;
    }

    public function setSourceExtInfo($sourceExtInfo)
    {
        $this->sourceExtInfo = $sourceExtInfo;
        $this->apiParams['source_ext_info'] = $sourceExtInfo;
    }

    public function getSourceExtInfo()
    {
        return $this->sourceExtInfo;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        $this->apiParams['title'] = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setUniqueMsgId($uniqueMsgId)
    {
        $this->uniqueMsgId = $uniqueMsgId;
        $this->apiParams['unique_msg_id'] = $uniqueMsgId;
    }

    public function getUniqueMsgId()
    {
        return $this->uniqueMsgId;
    }

    public function setVideoLength($videoLength)
    {
        $this->videoLength = $videoLength;
        $this->apiParams['video_length'] = $videoLength;
    }

    public function getVideoLength()
    {
        return $this->videoLength;
    }

    public function setVideoSamples($videoSamples)
    {
        $this->videoSamples = $videoSamples;
        $this->apiParams['video_samples'] = $videoSamples;
    }

    public function getVideoSamples()
    {
        return $this->videoSamples;
    }

    public function setVideoSize($videoSize)
    {
        $this->videoSize = $videoSize;
        $this->apiParams['video_size'] = $videoSize;
    }

    public function getVideoSize()
    {
        return $this->videoSize;
    }

    public function setVideoSource($videoSource)
    {
        $this->videoSource = $videoSource;
        $this->apiParams['video_source'] = $videoSource;
    }

    public function getVideoSource()
    {
        return $this->videoSource;
    }

    public function setVideoTemporaryUrl($videoTemporaryUrl)
    {
        $this->videoTemporaryUrl = $videoTemporaryUrl;
        $this->apiParams['video_temporary_url'] = $videoTemporaryUrl;
    }

    public function getVideoTemporaryUrl()
    {
        return $this->videoTemporaryUrl;
    }

    public function setVideoUrl($videoUrl)
    {
        $this->videoUrl = $videoUrl;
        $this->apiParams['video_url'] = $videoUrl;
    }

    public function getVideoUrl()
    {
        return $this->videoUrl;
    }
}
