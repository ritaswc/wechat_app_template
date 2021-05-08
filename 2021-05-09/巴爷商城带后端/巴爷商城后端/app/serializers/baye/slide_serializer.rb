module Baye
  class SlideSerializer < ActiveModel::Serializer
    attributes :img, :url

    def img
      object.image.url
    end

    def url
      object.product_id ? "/pages/show_product/show_product?id=#{object.product_id}" : ''
    end
  end
end
