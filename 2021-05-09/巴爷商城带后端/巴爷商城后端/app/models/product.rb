class Product < ApplicationRecord
  mount_uploader :promotion_image, Baye::ProductPromotionImageUploader
  include Baye::Enable

  validates :sku, presence: true, uniqueness: { scope: :store_id }, length: { minimum: 1, maximum: 191 }
  validates :name, presence: true, length: { minimum: 1, maximum: 191 }, if: :is_product_id_blank?
  validates :category, presence: true, if: :is_product_id_blank?
  # validates :description, presence: true, length: { maximum: 65534 }
  validates :price, presence: true, numericality: { greater_than_or_equal_to: 0.00 }, if: :is_product_id_blank?
  validates :member_price, presence: true, numericality: { greater_than_or_equal_to: 0.00 }, if: :is_product_id_blank?
  validates :baye_price, presence: true, numericality: { greater_than_or_equal_to: 0.00 }, if: :is_product_id_blank?
  validates :quantity, presence: true, numericality: { only_integer: true, greater_than_or_equal_to: 1 }, if: :is_product_id_blank?
  # validates :coin_limited, presence: true, numericality: { only_integer: true, greater_than_or_equal_to: 0 }

  validates :unit, presence: true, if: :is_product_id_blank?
  validates :weight, presence: true, numericality: { only_integer: true, greater_than_or_equal_to: 0 }, if: :is_product_id_blank?
  validates :stock, presence: true, numericality: { only_integer: true, greater_than_or_equal_to: 0 }, if: :is_product_id_blank?

  validates :store_id, :shippment_id, presence: true, if: -> { product_id.blank? }


  has_many :images, class_name: Baye::ProductImage, dependent: :destroy
  has_many :relation_products, foreign_key: :relation_product_id, class_name: Product
  has_one :description_of_detail, -> { where key: 'detail' }, class_name: Baye::ProductDescription
  belongs_to :category, class_name: Baye::ProductCategory
  belongs_to :shippment, class_name: Baye::Shippment, optional: false

  scope :on_sale, -> { where('(? between sale_time and removed_time) or (sale_time is null and removed_time> ?) or (removed_time is null and sale_time< ?) or (sale_time is null and removed_time is null)', Time.current, Time.current, Time.current) }
  scope :common, -> { where('type is null') }
  scope :is_search, -> { where(is_search: true) }
  scope :asset, -> { where(asset: true) }
  scope :subscription_goods, -> { where(unit: '订金') }

  scope :popularity_products, -> { where(flag: 1) } # 火爆
  scope :new_products,        -> { where(flag: 3) } # 新品
  scope :hot_products,        -> { where(flag: 4) } # 最热
  scope :promotions,          -> { where(flag: 5) } # 促销

  enum commission_type: { cash: 0, rate: 1 }

  def image_url
    images.first.file.url if images.present?
  end

  def is_gift_card?
    category.try(:name) == '礼品卡'
  end

  # product_price is different in baye-core
  def product_price(customer)
    return self.member_price if customer.nil? || customer.account_type == '游客' || customer.account_type == '潜在巴爷'
    return self.baye_price if customer.account_type == '巴爷'
  end

  def promotion_url
    promotion_image.try(:file).try(:url) || ''
  end

  def is_product_id_blank?
    self.product_id.blank?
  end
end
