class Wechat::PaymentsController < ActionController::Base
  protect_from_forgery with: :null_session
  skip_before_action :verify_authenticity_token

  def notify
    data = request.body.read
    result = Hash.from_xml(data)['xml']
    if WechatPaymentManager.new.paid?(data) && WxPay::Sign.verify?(result)
      back_data = Nokogiri::Slop(data).root
      payment = Baye::Payment.find_by(uid: back_data.out_trade_no.content)
      unless payment.paid?
        payment.wechat_payment.callback_data = data
        payment.wechat_payment.transaction_no = data.scan(/\<transaction_id\>(.*)\<\/transaction_id\>/).flatten.first
        payment.wechat_payment.succeeded_at = Time.current

        if payment.pay!
          return render :xml => {return_code: "SUCCESS"}.to_xml(root: 'xml', dasherize: false)
        else
          return render :xml => {return_code: "FAIL", return_msg: "签名失败"}.to_xml(root: 'xml', dasherize: false)
        end
        Rails.logger.debug "订单#{payment.id}支付完成."
      end
    end
    render nothing: true
  end
end
