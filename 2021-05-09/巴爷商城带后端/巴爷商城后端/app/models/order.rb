class Order < ApplicationRecord
  attr_accessor :invoice_title, :inner_info, :outer_info
  include Baye::Uid
  include Baye::No
  include Baye::OrderConcern

  validates :customer, presence: true
  validates :customer_name, presence: true, if: :require_address?
  validates :customer_mobile, presence: true, format: { with: /\A1[3-9][0-9]\d{8}\z/ }, if: :require_address?
  validates :province, presence: true, if: :require_address?
  validates :city, presence: true, if: :require_address?
  validates :county, presence: true, if: :require_address?
  validates :detail_address, presence: true, if: :require_address?
  validates :type, presence: true
  validate :check_order_items
  validate :check_inventory, if: -> { category.in?(%w(礼品卡 电子礼品卡)) }
  validates :inner_info, length: { maximum: 4 }
  validates :outer_info, length: { maximum: 20 }

  visitable :visit, class_name: Baye::Visit

  belongs_to :customer
  has_many :order_items, -> { order(:id) }, class_name: Baye::OrderItem
  belongs_to :payment, class_name: Baye::Payment, autosave: true

  accepts_nested_attributes_for :order_items, allow_destroy: true

  def final_amount
    couponed_amount.present? ? couponed_amount : amount
  end

  def distribute_coin_pay_from_order_to_order_item(coin_pay_rate, free_coin_pay_rate)
    # distribute coin pay to all order items
    order_items.each do |order_item|
      order_item.distributed_free_coin_pay_amount = order_item.final_amount * free_coin_pay_rate

      if order_items.last.id == order_item.id
        order_item.distributed_coin_pay_amount = distributed_coin_pay_amount - order_items.limit(order_items.count - 1).sum(:distributed_coin_pay_amount)
      else
        order_item.distributed_coin_pay_amount = order_item.final_amount * coin_pay_rate
      end

      order_item.save
    end
  end

  def free_coin_pay_amount
    return 0 if order_items.size.zero?
    order_items.inject(0) { |acc, elem| acc + (elem.distributed_free_coin_pay_amount.present? ? elem.distributed_free_coin_pay_amount.to_f : 0) }
  end

  private

  def require_address?
    return false if category == '定金'
    return true if category == '商品'
    false
  end

  def check_order_items
    if self.order_items.blank? && !self.order_id.nil?
      self.errors.add(:items_null_error, "请添加商品")
      return false
    end
  end

  def check_inventory
    order_items.each do |item|
      product = Product.find_by id: item.linked_product_id
      next unless item.use_inventory
      inventory_product = product.linked_inventory(item.product_id)
      total_inventory_quantity = customer.inventories.find_by(product_id: inventory_product.id).quantity
      using_inventory_quantity = product.linked_package(item.product_id).capacity * item.quantity
      self.errors.add(:not_enough_inventory, "#{inventory_product.name}库存不足") if using_inventory_quantity > total_inventory_quantity
      return false
    end
  end
end
