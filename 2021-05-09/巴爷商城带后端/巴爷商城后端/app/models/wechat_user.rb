class WechatUser < ApplicationRecord
  belongs_to :customer
  validates :open_id, presence: true, uniqueness: true

  def self.find_by_token_valid(token)
    WechatUser.where(client_token: token).where("expired_at > ?", Time.now).first
  end

  def subscribe
    self.subscribed_at = Time.current
    self.unsubscribed_at = nil
    save
  end

  def unsubscribe
    self.unsubscribed_at = Time.current
    save
  end

  def subscribed?
    if unsubscribed_at.nil? && subscribed_at.present?
      true
    else
      false
    end
  end

  def bind_customer!(customer)
    self.customer = customer
    save
  end

  def expire!
    self.expired_at = Time.now - 1
    save
  end

  def update_token(body, customer, token, wx_code)
    self.session_key  = body['session_key']
    self.customer_id  = customer&.id
    self.expired_at   = 7.days.from_now
    self.client_token = token
    self.wx_code      = wx_code
    self.app_id       = ENV['weapplet_app_id']
    self.open_id      = body['openid']
    save!
  end

  def update_info(user_info)
    self.nickname  = user_info['nickName']
    self.gender    = user_info['gender']
    self.country   = user_info['country']
    self.city      = user_info['city']
    self.province  = user_info['province']
    self.avatar    = user_info['avatarUrl']
    self.union_open_id = user_info['unionId']
    save
  end
end
