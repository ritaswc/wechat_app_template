package com.leadingsoft.liuw.enums;

/**
 * 消息事件类型
 *
 * @author user
 */
public enum EventType {
    unknown("unknown")/* 未知无效 */,
    subscribe("subscribe") /* 订阅 */,
    unsubscribe("unsubscribe") /* 取消订阅 */,
    scan("scan") /* 用户已关注时的事件推送 */,
    location("location") /* 上报地理位置事件 */,
    click("click")/* 点击菜单拉取消息时的事件推送 */,
    view("view")/* 点击菜单跳转链接时的事件推送 */,
    templatesendjobfinish("templatesendjobfinish")/* 模板消息发送完成 */;

    private String value;

    EventType(final String value) {
        this.value = value;
    }

    public static EventType getValue(final String value) {
        for (final EventType type : EventType.values()) {
            if (type.value == value) {
                return type;
            }
        }
        return unknown;
    }
}
