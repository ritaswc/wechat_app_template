package com.leadingsoft.liuw.filter;

import com.leadingsoft.liuw.auth.WebAuthenticationToken;
import com.leadingsoft.liuw.bean.WxSessionBean;
import com.leadingsoft.liuw.exception.CustomRuntimeException;
import com.leadingsoft.liuw.handler.DefaultAuthenticationSuccessHandler;
import com.leadingsoft.liuw.utils.JsonUtils;
import lombok.extern.slf4j.Slf4j;
import org.apache.commons.lang3.StringUtils;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.core.ParameterizedTypeReference;
import org.springframework.http.HttpMethod;
import org.springframework.http.ResponseEntity;
import org.springframework.security.authentication.AbstractAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.AuthenticationException;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.web.authentication.AbstractAuthenticationProcessingFilter;
import org.springframework.web.client.RestClientException;
import org.springframework.web.client.RestTemplate;

import javax.servlet.FilterChain;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.util.HashMap;
import java.util.Map;
import java.util.UUID;

@Slf4j
public class WechatAuthenticationFilter extends AbstractAuthenticationProcessingFilter {

    @Value("${weixin.config.appId}")
    private String appId;

    @Value("${weixin.config.secret}")
    private String secret;

    @Value("${weixin.code2session.url}")
    private String code2sessionUrl;

    @Autowired
    private RestTemplate restTemplate;

    // 返回类型ResultDTO<String>
    private final ParameterizedTypeReference<String> responseType =
            new ParameterizedTypeReference<String>() {
            };

    public WechatAuthenticationFilter() {
        super("/wechat/login");
        super.setAuthenticationSuccessHandler(new DefaultAuthenticationSuccessHandler());
    }

    @Override
    public void doFilter(final ServletRequest req, final ServletResponse res, final FilterChain chain)
            throws IOException, ServletException {
        final HttpServletRequest request = (HttpServletRequest) req;
        String sessionKey = (String) request.getParameter("wxSessionKey");
        if(StringUtils.isEmpty(sessionKey)){
            return;
        }
        String sessionVal = (String) request.getSession().getAttribute(sessionKey);

        if (StringUtils.isEmpty(sessionVal)) {
            return;
        }

        super.doFilter(req, res, chain);
    }

    /*
     * 认证过程
     */
    @Override
    public Authentication attemptAuthentication(final HttpServletRequest request, final HttpServletResponse response)
            throws AuthenticationException {

        String code = request.getParameter("code");
        ResponseEntity<String> resultEntity;
        try {
            resultEntity = this.restTemplate.exchange(
                    this.code2sessionUrl,
                    HttpMethod.GET,
                    null,
                    this.responseType,
                    appId, secret, code);
        } catch (final RestClientException e) {
            WechatAuthenticationFilter.log.error("请求微信服务器错误", e);
            return null;
        }

        WxSessionBean sessionBean = JsonUtils.jsonToPojo(resultEntity.getBody(), WxSessionBean.class);

        if(org.springframework.util.StringUtils.isEmpty(sessionBean.getErrcode())){
            WechatAuthenticationFilter.log.error("微信服务器返回错误", sessionBean.getErrcode());
            return null;
        }


        String sessionKey = UUID.randomUUID().toString();


        final WebAuthenticationToken auth = new WebAuthenticationToken();
        auth.setPrincipal(sessionKey);
        auth.setAuthenticated(true);
        final Map map = new HashMap();
        map.put("openid", sessionBean.getOpenid());
        map.put("session_key", sessionBean.getSession_key());
        auth.setDetails(map);

        return null;
    }

}
