class Verification < ApplicationRecord
  validates :mobile, presence: true, format: { with: /\A1[3-9][0-9]\d{8}\z/ }
  validates :expire_at, presence: true
  validates :code, presence: true

  def self.send_message!(mobile)
    verification = Verification.new(mobile: mobile, code: generate_code, expire_at: Time.current + 10.minutes)
    verification.save

    VerifySmsSendWorker.perform_async(verification.id)
  end

  def self.can_send?(mobile)
    #一个小时之内发送的频率为8
    created_at_lq = Time.current - 1.hour
    count = Verification.where(mobile: mobile).where('created_at > ? and created_at < ?', created_at_lq, Time.current).count
    return false if count > 8
    true
  end

  def self.validate?(mobile, code)
    return Verification.where('expire_at > :now', now: Time.current).where(mobile: mobile, code: code).exists?
  end

  private

  def self.generate_code
    rand(9).to_s + rand(9).to_s + rand(9).to_s + rand(9).to_s
  end
end
