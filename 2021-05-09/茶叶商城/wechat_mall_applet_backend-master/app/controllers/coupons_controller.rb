class CouponsController < ApplicationController
  def list
    product_ids = params[:product_ids]&.split(',')
    products_order_quantities = params[:products_order_quantities]&.split(',')

    if product_ids.present? && products_order_quantities.present?
      @coupons = current_customer.get_available_coupons_from_order_items(product_ids, products_order_quantities)
    else
      @coupons = current_customer.coupons.valid_unused_coupons
    end
    @coupons = @coupons.joins(:coupon_defination).where('coupon_definations.coupon_type = ?', 5) if params[:coupon_type] == 'pick_up_good'
    @coupons = @coupons.joins(:coupon_defination).where('coupon_definations.coupon_type = ?', 6) if params[:coupon_type] == 'gift'
    render json: @coupons
  end
end
