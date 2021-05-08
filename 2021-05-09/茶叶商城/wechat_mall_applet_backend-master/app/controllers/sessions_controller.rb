# frozen_string_literal: true
require Rails.root.join('lib', 'wxbiz_data_crypt')

class SessionsController < BaseController
  # 打开应用时就获取用户敏感信息
  # 这里不返回 @token, @customer 的 id 更新也是 nil
  def wechat_user_type
    update_wechat_user_token unless Rails.env.staging?
    return render json: { wechat_user_type: 'normal' }

  rescue DevDomainError, NoAppIdError => e
    Rails.logger.info "appdev=======#{e.message}  #{request.remote_ip}"
    return render json: { wechat_user_type: 'normal' }
  end

  def login
    @mobile   = params[:mobile]
    @token    = request.headers['Authorization']
    @customer = nil

    if token_valid?
      token_login
      return render status: 403, json: { code: 7, msg: 'token登录出错' } if @customer.nil?
    else
      return render status: 403, json: { code: 4, msg: '需要填写手机号码' } if @mobile.blank?

      case login_type
      when :password
        password_login
        return render status: 403, json: { code: 6, msg: '密码不正确或该账号没有绑定' } if @customer.nil?
      when :mobile_code
        mobilecode_login
        return render status: 403, json: { code: 5, msg: '手机验证码不正确！' } if @customer.nil?
      end
      begin
        update_wechat_user_token
      rescue DevDomainError, NoAppIdError => e
        return render status: 403, json: { code: 9, msg: e.message }
      end
    end
    render json: { token: @token, customer: @customer.slice(:name, :mobile, :baye_rank, :id, :account_type) }
  end

  def logout
    # TODO: code not used
    # 但 code 每次 token 登录也会变
    param_code = params[:code]
    return render status: 500 unless current_wechat_user

    current_wechat_user.expire!
    render status: 200
  end

  private

  def login_type
    return :password if params[:password].present?
    return :mobile_code if params[:mobile_code].present?
  end

  def password_login
    c = Customer.find_by mobile: @mobile
    @customer = c if c&.valid_password?(params[:password])
  end

  def mobilecode_login
    return nil unless Verification.validate?(@mobile, params[:mobile_code])
    @customer = Customer.find_by(mobile: @mobile)
    @customer = Customer.new.register!(mobile: @mobile, name: params[:name]) unless @customer
  end

  def token_login
    return nil unless current_wechat_user
    current_wechat_user.update!(wx_code: params[:code])

    @customer = Customer.find(current_wechat_user.customer_id)
  end

  # params[:code], @customer, @token
  def update_wechat_user_token
    @token = 'wx_' + SecureRandom.hex(20)
    body = cached_wx_session_key(params[:code])
    sensitive_data = decrypt(body['session_key'])

    wechat_user = find_exist_wechat_user(sensitive_data, body) || WechatUser.new
    wechat_user.update_token(body, @customer, @token, params[:code])
    wechat_user.update_info(sensitive_data)
    wechat_user
  end

  def find_exist_wechat_user(data, body)
    if WechatUser.where(union_open_id: data['unionId']).count > 1
      WechatUser.find_by(union_open_id: data['unionId'], open_id: body['openid'])
    else
      WechatUser.find_by(union_open_id: data['unionId'])
    end
  end

  def token_valid?
    return false if @token.blank?
    current_wechat_user
  end

  def cached_wx_session_key(code)
    raise NoAppIdError if code == 'the code is a mock one'
    key = "wxcode_#{code}"
    sessions = $redis.get(key)
    if sessions.blank?
      raise DevDomainError if Rails.env.development?
      sessions = wx_get_session_key(code)

      $redis.set(key, sessions)
      $redis.expire(key, 3600 * 6)
    else
      Rails.logger.debug "=== session key read from redis: #{sessions}"
    end
    JSON.parse(sessions)
  end

  def wx_get_session_key(code)
    uri = URI('https://api.weixin.qq.com/sns/jscode2session')
    params = { appid: ENV['weapplet_app_id'], secret: ENV['weapplet_secret'], js_code: code, grant_type: 'authorization_code' }
    uri.query = URI.encode_www_form(params)
    resp = Net::HTTP.get_response(uri)
    if resp.is_a?(Net::HTTPSuccess) && !resp.body['errcode']
      return resp.body
    else
      raise("wx 请求没有 Success #{resp&.body}")
    end
  end

  def decrypt(session_key)
    app_id         = ENV['weapplet_app_id']
    encrypted_data = params[:encrypted][:encryptedData]
    iv             = params[:encrypted][:iv]

    pc = WXBizDataCrypt.new(app_id, session_key)
    pc.decrypt(encrypted_data, iv)
  end
end

class NoAppIdError < StandardError
  attr_reader :message
  def initialize(message = nil)
    @message = message || '由于没有添加AppId，微信的登录无法实现，所以不能登录。'
  end
end

class DevDomainError < StandardError
  attr_reader :message
  def initialize(message = nil)
    @message = message || '本地的 RAPI 没有域名，无法获取向微信获取 session key'
  end
end
