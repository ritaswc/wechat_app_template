require 'net/http'
require 'json'
require 'openssl'
require 'digest/sha1'
require 'digest/md5'
require 'erb'

class WechatPaymentManager
  include HttpsSupport

  def register_for_app(payment)
    uri = URI("https://api.mch.weixin.qq.com/pay/unifiedorder")
    http = get_https_client(uri)
    request = Net::HTTP::Post.new(uri, initheader = { 'Content-Type' => 'application/xml' })
    request.body = build_data_for_app(payment)
    Rails.logger.debug request.body
    res = http.request(request)
    Rails.logger.debug res.body
    Nokogiri::Slop(res.body).root if res.is_a?(Net::HTTPSuccess)
  end

  def register_for_jsapi(payment, wechat_user)
    uri = URI("https://api.mch.weixin.qq.com/pay/unifiedorder")
    http = get_https_client(uri)
    request = Net::HTTP::Post.new(uri, initheader = { 'Content-Type' => 'application/xml' })
    request.body = build_data_for_jsapi(payment, wechat_user)
    Rails.logger.debug request.body
    res = http.request(request)
    Rails.logger.debug res.body
    Nokogiri::Slop(res.body).root if res.is_a?(Net::HTTPSuccess)
  end

  def build_data_for_app(payment)
    template =<<-EOF
    <xml>
       <appid>%{appid}</appid>
       <mch_id>%{mch_id}</mch_id>
       <nonce_str>%{nonce_str}</nonce_str>
       <sign>%{sign}</sign>
       <body>%{body}</body>
       <out_trade_no>%{out_trade_no}</out_trade_no>
       <total_fee>%{total_fee}</total_fee>
       <spbill_create_ip>%{spbill_create_ip}</spbill_create_ip>
       <notify_url>%{notify_url}</notify_url>
       <trade_type>%{trade_type}</trade_type>
       <time_start>%{time_start}</time_start>
       <time_expire>%{time_expire}</time_expire>
    </xml>
    EOF

    hash = {
      appid: ENV['weapplet_app_id'],
      mch_id: ENV['WECHAT_STORE_ID'],
      nonce_str: SecureRandom.hex(10),
      body: get_payment_body(payment),
      out_trade_no: payment.uid,
      total_fee: (payment.wx_pay_amount * 100).to_i,
      spbill_create_ip: payment.ip,
      notify_url: "#{ENV['server_url']}/wechat/payments/#{payment.uid}/notify",
      trade_type: 'APP',
      time_start: Time.current.strftime('%Y%m%d%H%M%S'),
      time_expire: (Time.current + 1.day).strftime('%Y%m%d%H%M%S'),
    }

    hash[:sign] = build_signature(hash)

    template.gsub(/\s|\n/, '') % hash
  end

  def build_data_for_jsapi(payment, wechat_user)
    template =<<-EOF
    <xml>
       <appid>%{appid}</appid>
       <mch_id>%{mch_id}</mch_id>
       <nonce_str>%{nonce_str}</nonce_str>
       <sign>%{sign}</sign>
       <body>%{body}</body>
       <out_trade_no>%{out_trade_no}</out_trade_no>
       <total_fee>%{total_fee}</total_fee>
       <spbill_create_ip>%{spbill_create_ip}</spbill_create_ip>
       <notify_url>%{notify_url}</notify_url>
       <trade_type>%{trade_type}</trade_type>
       <openid>%{openid}</openid>
       <time_start>%{time_start}</time_start>
       <time_expire>%{time_expire}</time_expire>
    </xml>
    EOF

    hash = {
      appid: ENV['weapplet_app_id'],
      mch_id: ENV['WECHAT_STORE_ID'],
      nonce_str: SecureRandom.hex(10),
      body: get_payment_body(payment),
      out_trade_no: payment.uid,
      total_fee: (payment.wx_pay_amount * 100).to_i,
      spbill_create_ip: payment.ip,
      notify_url: "#{ENV['server_url']}/wechat/payments/#{payment.uid}/notify",
      trade_type: 'JSAPI',
      time_start: Time.current.strftime('%Y%m%d%H%M%S'),
      time_expire: (Time.current + 1.day).strftime('%Y%m%d%H%M%S'),
      openid: wechat_user.open_id
    }

    hash[:sign] = build_signature(hash)

    template.gsub(/\s|\n/, '') % hash
  end

  def build_hash_for_jsapi_call(prepay_id)
    hash = {}
    hash[:timeStamp] = Time.current.to_i.to_s
    hash[:nonceStr] = SecureRandom.hex(10)
    hash[:package] = "prepay_id=#{prepay_id}"
    hash[:signType] = "MD5"
    hash[:appId] = ENV['weapplet_app_id']
    hash
  end

  def build_hash_for_app_call(prepay_id)
    hash = {}
    hash[:timestamp] = Time.current.to_i.to_s
    hash[:noncestr] = SecureRandom.hex(10)
    hash[:prepayid] = "#{prepay_id}"
    hash[:appid] = ENV['weapplet_app_id']
    hash[:partnerid] = ENV['WECHAT_STORE_ID']
    hash[:package] = 'Sign=WXPay'
    hash
  end

  def paid?(data)
    back_data = Nokogiri::Slop(data).root
    if back_data.result_code.content == 'SUCCESS'
      hash = {}
      back_data.elements.each do |element|
        hash[element.name] = element.content
      end
      hash.delete('sign')
      # Rails.logger.debug "计算出签名:#{build_signature(hash)},CALLBACK签名#{back_data.sign.content}"
      return true if build_signature(hash) == back_data.sign.content
    end
    return false
  end

  def build_signature(hash)
    origin = hash.sort.to_h.map { |k, v| "#{k}=#{v}" }.join('&')
    temp = "#{origin}&key=#{ENV['WECHAT_STORE_KEY']}"
    Rails.logger.debug temp
    Digest::MD5.hexdigest(temp).upcase
  end

  def get_payment_body(payment)
    return '巴爷科技商品订单' unless payment.orders.nil?
    return 'UNKNOWN'
  end
end
