<?php

namespace Hejiang\Express;

abstract class Status
{
    /**
     * 揽件
     */
    const STATUS_UNKNOWN = -1;

    /**
     * 揽件
     */
    const STATUS_PICKEDUP = 0;

    /**
     * 发出
     */
    const STATUS_DEPART = 1;

    /**
     * 在途
     */
    const STATUS_TRANSPORTING = 2;

    /**
     * 派件
     */
    const STATUS_DELIVERING = 3;

    /**
     * 签收
     */
    const STATUS_DELIVERED = 4;

    /**
     * 自取
     */
    const STATUS_SELFPICKUP = 5;

    /**
     * 疑难
     */
    const STATUS_REJECTED = 6;

    /**
     * 退回
     */
    const STATUS_RETURNING = 7;
    
    /**
     * 退签
     */
    const STATUS_RETURNED = 8;
}
