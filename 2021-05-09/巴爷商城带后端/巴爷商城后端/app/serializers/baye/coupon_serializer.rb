module Baye
  class CouponSerializer < ActiveModel::Serializer
    attributes :code, :state, :uid, :coupon_value
    attributes :title, :sub_title, :product_category_title, :end_time, :left_image

    def title
      object.coupon_defination.title
    end

    def sub_title
      object.coupon_defination.sub_title
    end

    def product_category_title
      object.coupon_defination.product_category_title
    end

    def end_time
      object.coupon_defination.end_time.strftime('%y-%m-%d')
    end

    def left_image
      if object.overdue?
        nil
      else
        object.coupon_defination.left_side_image.url
      end
    end
  end
end