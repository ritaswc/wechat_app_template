package com.leadingsoft.liuw.handler;

import com.leadingsoft.liuw.dto.ResultDTO;
import com.leadingsoft.liuw.utils.JsonUtils;
import org.springframework.security.core.Authentication;
import org.springframework.security.web.WebAttributes;
import org.springframework.security.web.authentication.AbstractAuthenticationTargetUrlRequestHandler;
import org.springframework.security.web.authentication.AuthenticationSuccessHandler;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import java.io.IOException;
import java.io.PrintWriter;

/**
 * Created by liuw on 2017/4/21.
 */
public class DefaultAuthenticationSuccessHandler  extends AbstractAuthenticationTargetUrlRequestHandler
        implements AuthenticationSuccessHandler {

    public DefaultAuthenticationSuccessHandler() {
        this.setRedirectStrategy(new DefaultUrlRedirectStrategy());
    }

    public DefaultAuthenticationSuccessHandler(final String defaultTargetUrl) {
        this();
        this.setDefaultTargetUrl(defaultTargetUrl);
    }

    @Override
    public void onAuthenticationSuccess(final HttpServletRequest request, final HttpServletResponse response,
                                        final Authentication authentication) throws IOException, ServletException {
        this.handle(request, response, authentication);
        this.clearAuthenticationAttributes(request);
    }

    /**
     * Removes temporary authentication-related data which may have been stored
     * in the session during the authentication process.
     */
    protected final void clearAuthenticationAttributes(final HttpServletRequest request) {
        final HttpSession session = request.getSession(false);

        if (session == null) {
            return;
        }
        session.removeAttribute(WebAttributes.AUTHENTICATION_EXCEPTION);
    }

    @Override
    protected void handle(final HttpServletRequest request, final HttpServletResponse response,
                          final Authentication authentication)
            throws IOException, ServletException {

        if ((request.getContentType() == null) || request.getContentType().contains("application/json")) {
            response.addHeader("Content-Type", "application/json;charset=UTF-8");
            final PrintWriter writer = response.getWriter();
            final ResultDTO<?> rs = ResultDTO.success(authentication);
            writer.write(JsonUtils.pojoToJson(rs));
        }
        final String targetUrl = request.getParameter("redirect");

        if ((targetUrl != null) && response.isCommitted()) {
            this.logger.debug("Response has already been committed. Unable to redirect to " + targetUrl);
            return;
        }

        super.getRedirectStrategy().sendRedirect(request, response, targetUrl);
    }
}
