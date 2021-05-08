package com.leadingsoft.liuw.handler;

import lombok.extern.slf4j.Slf4j;
import org.springframework.security.web.RedirectStrategy;
import org.springframework.util.StringUtils;

import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.IOException;

/**
 * Created by liuw on 2017/4/21.
 */
@Slf4j
public class DefaultUrlRedirectStrategy implements RedirectStrategy {

    @Override
    public void sendRedirect(final HttpServletRequest request, final HttpServletResponse response, String redirectUrl)
            throws IOException {
        if (!StringUtils.hasText(redirectUrl)) {
            redirectUrl = request.getParameter("redirect");
        }
        if (StringUtils.hasText(redirectUrl)) {
            redirectUrl = response.encodeRedirectURL(redirectUrl);
            if (DefaultUrlRedirectStrategy.log.isDebugEnabled()) {
                DefaultUrlRedirectStrategy.log.debug("Redirecting to '" + redirectUrl + "'");
            }
            response.sendRedirect(redirectUrl);
        }
    }
}
