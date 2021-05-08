class VerifySmsSendWorker
  include Sidekiq::Worker

  def perform(verification_id)
    Rails.logger.debug "开始发送 #{verification_id} 验证码 at #{Time.current}"
    item = Verification.find(verification_id)

    content = "您的验证码是【#{item.code}】。"
    SmsNotifyWorker.perform_async(item.mobile, content, 'UserRegistration')
  end
end
