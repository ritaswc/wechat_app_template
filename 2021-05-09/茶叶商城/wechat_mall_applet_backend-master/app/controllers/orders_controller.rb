class OrdersController < ApplicationController
  swagger_controller :orders, 'Bayetech Store Order'

  swagger_api :create_order do
    summary 'create order'
    notes 'create order'
  end

  def create_applet_order
    return nil if params[:order_items].blank?

    orders = []
    params[:order_items].group_by do |oi|
      product = Product.find_by(uid: oi[:product_uid])
      "#{product.store_id}_#{product.shippment_id}"
    end.each do |k, v|
      order = {}
      order[:store_id] = k.split('_').first
      order[:customer_id] = current_customer.id
      # order[:ship_address_id] = ship_address.id
      order[:type] = Order.name
      order[:state] = :waiting_for_pay
      order[:province] = params[:province]
      order[:city] = params[:city]
      order[:county] = params[:county]
      order[:detail_address] = params[:detail_address]
      order[:customer_name] = params[:customer_name]
      order[:customer_mobile] = params[:customer_mobile]
      order[:category] = '商品'
      order[:order_from] = params[:order_from]

      order[:order_items_attributes] = v.map do |oi|
        product = Product.find_by(uid: oi[:product_uid])
        order[:shippment_type] = oi[:shippment_type] unless product.shippment.try(:is_pinkage?)

        item = {
          'quantity' => oi[:quantity],
          'customer_id' => current_customer.id,
          'product_id' => product.id,
          'product_price' => product.product_price(current_customer),
          'product_name' => product.name,
          'product_unit' => product.unit,
          'product_weight' => product.weight,
          'sub_total' => oi[:quantity] * product.product_price(current_customer),
          'external_content' => oi[:external_content]
        }
        if (oi[:external_content].present? || oi[:inside_content].present?) && current_customer.account_type != '巴爷'
          item['customization_cost'] = 9 * oi[:quantity].to_i
        else
          item['customization_cost'] = 0
        end
        item
      end
      orders << order
    end
    orders = current_customer.orders.build orders
    return nil if orders.blank?

    orders.each do |order|
      order.product_amount = order.order_items.inject(0) { |sum, i| sum + i.sub_total }
      order.quantity       = order.order_items.inject(0) { |sum, i| sum + i.quantity }
      order.weight         = order.order_items.inject(0) { |sum, i| sum + i.product_weight }
      order.amount         = order.product_amount
      order.save!
    end

    @payment = current_customer.payments.build
    @payment.orders = orders
    @payment.pay_from = 'WECHAT'
    @payment.coin_quantity = 0
    @payment.ip = request.remote_ip
    @payment.expired_at = Time.current + 1.day
    @payment.amount = orders.map(&:amount).sum
    @payment.save

    # use coupon
    if params[:coupon_code].present?
      coupon = Baye::Coupon.find_by(code: params[:coupon_code])
      if coupon.present? && @payment.can_use_coupon?(coupon)
        @payment.add_coupon(coupon)
        @payment.use_coupon
        @payment.save
        coupon.update(customer_id: current_customer.id, binding_customer_at: Time.current)
      else
        return render status: 403, json: { code: 4001, msg: '该优惠券无法使用！' }
      end
    else
      @payment.save
    end

    # WARN: FOR TEST
    @payment.update(amount: 0.01) if Rails.env.staging?

    # 微信支付
    manager = WechatPaymentManager.new
    data = manager.register_for_jsapi(@payment, current_wechat_user)
    return render status: 403, json: { code: 4005, msg: 'data.prepay_id is blank' } unless data&.prepay_id&.content

    wechat_payment = Baye::WechatPayment.new
    wechat_payment.prepay_id = data.prepay_id.content
    wechat_payment.request_data = data.to_s
    @payment.wechat_payment = wechat_payment

    unless @payment.save
      Rails.logger.debug "无法更新支付:#{@payment.errors.full_messages}."
      return nil
    end

    @hash = manager.build_hash_for_jsapi_call(wechat_payment.prepay_id)
    @hash[:signature] = manager.build_signature(@hash)
    return render json: { hash: @hash }
  rescue ActiveRecord::RecordInvalid => e
    return render status: 403, json: { code: 4007, msg: e.message }
  end

  private

  def payment_params
    params.permit(:token, payment: [ :amount,
      orders_attributes: [ :detail_address, :province, :city, :county,
      order_items_attributes: [:product_id, :quantity, :external_content]]
    ])
  end
end
