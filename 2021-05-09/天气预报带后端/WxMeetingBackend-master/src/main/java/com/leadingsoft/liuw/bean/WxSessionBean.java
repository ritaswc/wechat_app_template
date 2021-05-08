package com.leadingsoft.liuw.bean;

import lombok.Getter;
import lombok.Setter;

/**
 * Created by liuw on 2017/4/21.
 */
@Getter
@Setter
public class WxSessionBean {

    private String openid;

    private String session_key;

    private String errcode;

    private String errmsg;
}
