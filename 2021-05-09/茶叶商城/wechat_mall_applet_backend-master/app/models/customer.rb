class Customer < ApplicationRecord
  include Baye::Uid
  include Baye::No
  include Baye::CustomerConcern
  extend Encryptor

  attr_accessor :stretches
  @stretches = 10
  attr_accessor :pepper
  @pepper = nil

  mount_uploader :avatar, CustomerAvatarUploader

  validates :name, presence: true, length: { in: 1..45 }
  validates :mobile, uniqueness: true, presence: true, format: { with: /\A1[3-9][0-9]\d{8}\z|BYCE-\w+/}
  validates :coin_balance, numericality: { only_integer: true }, allow_blank: true
  validates :free_coin_balance, numericality: { only_integer: true }, allow_blank: true

  has_many :inventory_changes, class_name: Baye::CustomerInventoryChange
  has_many :inventories, class_name: Baye::CustomerInventory
  has_many :orders
  has_many :payments, class_name: Baye::Payment
  has_many :wechat_users
  has_many :coupons, class_name: Baye::Coupon

  before_create :set_customer_account_type, :set_badge_value, :set_nickname, :set_reference_code, :set_agent_value

  def get_available_coupons_from_order_items(product_ids, products_order_quantities)
    products = Product.where(id: product_ids)
    product_category_ids = products.collect(&:category_id).uniq
    product_store_ids = products.collect(&:store_id).uniq
    shippment_ids = products.collect(&:shippment_id).uniq
    free_express_ids = Baye::Shippment.where(is_pinkage: true).ids
    allow_use_express_save_coupon = (product_store_ids.count == 1 && (shippment_ids - free_express_ids).count == 1)

    total_consume = 0
    products.zip(products_order_quantities) { |p, qty| total_consume += p.product_price(self) * qty.to_i }

    ac = coupons.joins(:coupon_defination)
    ac = ac.joins('LEFT JOIN coupon_defination_categories ON coupon_defination_categories.coupon_defination_id = coupon_definations.id')
           .joins('LEFT JOIN coupon_defination_products ON coupon_defination_products.coupon_defination_id = coupon_definations.id')
    ac = ac.where('coupon_defination_categories.product_category_id IN (?) AND coupon_definations.filteration_type IN (?)', product_category_ids,
                  %w(2,4))
           .or(ac.where('coupon_defination_products.product_id IN (?) AND coupon_definations.filteration_type IN (?)', product_ids, %w(1,3)))

    ac = ac.order(id: :desc).where(state: 'unused')
           .where('coupon_definations.begin_time < ?', Time.current)
           .where('coupon_definations.end_time > ?', Time.current)
           .where('coupon_definations.least_cost <= ?', total_consume)
    ac.where.not('coupon_definations.coupon_type = ?', Baye::CouponDefination.coupon_types['express_save']) unless allow_use_express_save_coupon
    ac.uniq
  end

  def valid_password?(password)
    klass = Struct.new(:pepper, :stretches).new(pepper, stretches)
    Encryptor.compare(klass, self.encrypted_password, password)
  end

  def password_digest(password)
    klass = Struct.new(:pepper, :stretches).new(pepper, stretches)
    Encryptor.digest(klass, password)
  end

  def register!(opt = {})
    self.coin_balance = 0
    self.mobile = opt[:mobile]
    self.name = opt[:name] || ("BY-" + Time.current.strftime('%y%m%d%H%M%S%L'))
    self.password = opt[:password] || SecureRandom.hex
    save
    self
  end

  def password=(password)
    self.encrypted_password = password_digest(password)
  end

  def need_pay_coin_balance(coin_quantity)
    total_coin_balance.zero? ? 0 : ((coin_balance.to_i / total_coin_balance) * coin_quantity).to_i
  end

  def need_pay_free_coin_balance(coin_quantity)
    coin_quantity - need_pay_coin_balance(coin_quantity)
  end

  private

  def set_customer_account_type
    self.account_type = '潜在巴爷'
  end

  def set_badge_value
    if self.wine_badge.nil?
      self.wine_badge = false
    end
    if self.tea_badge.nil?
      self.tea_badge = false
    end
    if self.rice_badge.nil?
      self.rice_badge = false
    end
    true
  end

  def set_agent_value
    if self.agent.nil?
      self.agent = false
    end
    true
  end

  def set_nickname
    if !self.name.nil?
      self.nickname = self.name
    end
    true
  end

  def set_reference_code
    if self.reference_code.nil?
      o = %w(0 1 2 3 4 5 6 7 8 9 A B C D E F G H J K M N P Q R S T U V W X Y Z)
      begin
        self.reference_code = (0...4).map { o[rand(o.length)] }.join
      end while self.class.exists?(reference_code: reference_code)
    end
    true
  end
end
