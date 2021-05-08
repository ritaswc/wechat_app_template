module BaseHelper
  def current_customer
    applet_customer
  end

  def current_wechat_user
    applet_user(request.headers['Authorization'])
  end

  def applet_customer
    @applet_customer ||= current_wechat_user&.customer
  end

  def applet_user(token)
    @applet_user ||= WechatUser.find_by_token_valid(token)
  end
end
