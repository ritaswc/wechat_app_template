package com.leadingsoft.liuw.enums;

/**
 * 普通消息类型
 *
 * @author user
 */
public enum MsgType {
    unknown("unknown")/* 未知无效 */,
    text("text")/* 文本消息 */,
    image("image")/* 图片消息 */,
    voice("voice")/* 语音消息 */,
    video("video")/* 视频消息 */,
    shortvideo("shortvideo")/* 小视频消息 */,
    location("location")/* 地理位置消息 */,
    link("link")/* 链接消息 */,
    event("event")/* 接收事件推送消息 */,
    transfer_customer_service("transfer_customer_service")/* 消息转发到多客服 */;

    private String value;

    MsgType(final String value) {
        this.value = value;
    }

    public static MsgType getValue(final String value) {
        for (final MsgType type : MsgType.values()) {
            if (type.value == value) {
                return type;
            }
        }
        return unknown;
    }
}
