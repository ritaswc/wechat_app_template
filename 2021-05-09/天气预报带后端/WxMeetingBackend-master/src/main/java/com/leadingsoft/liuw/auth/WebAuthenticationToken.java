package com.leadingsoft.liuw.auth;

import org.springframework.security.core.Authentication;
import org.springframework.security.core.GrantedAuthority;
import org.springframework.security.core.authority.SimpleGrantedAuthority;

import java.util.Collection;
import java.util.Collections;
import java.util.Map;

/**
 * Created by liuw on 2017/4/24.
 */
public class WebAuthenticationToken  implements Authentication {
    private static final long serialVersionUID = 8059032696258599055L;

    private boolean authenticated;

    private String principal;

    private String appId;

    private Collection<SimpleGrantedAuthority> authorities;

    /**
     * 用户详细，此处限定为：只允许放字符串类型的内容
     */
    private Map<String, String> details;

    @Override
    public Object getPrincipal() {
        return this.principal;
    }

    public void setPrincipal(final String principal) {
        this.principal = principal;
    }

    @Override
    public String getName() {
        return this.principal;
    }

    @Override
    public Collection<? extends GrantedAuthority> getAuthorities() {
        if(this.authorities != null) {
            return this.authorities;
        }
        return Collections.emptyList();
    }

    public void setAuthorities(final Collection<SimpleGrantedAuthority> authorities) {
        this.authorities = authorities;
    }

    @Override
    public Object getCredentials() {
        return null;
    }

    @Override
    public Map<String, String> getDetails() {
        return this.details;
    }

    @Override
    public boolean isAuthenticated() {
        return this.authenticated;
    }

    @Override
    public void setAuthenticated(final boolean isAuthenticated) throws IllegalArgumentException {
        this.authenticated = isAuthenticated;
    }

    public void setDetails(final Map<String, String> details) {
        this.details = details;
    }

    public String getAppId() {
        return this.appId;
    }

    public void setAppId(final String appId) {
        this.appId = appId;
    }
}
