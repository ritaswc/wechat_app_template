class ProductSerializer < ActiveModel::Serializer
  attributes :id, :name, :sku, :image_url, :uid, :unit, :promotion_url, :category_id

  attribute :flag do
    case object.flag
    when 1
      '火爆'
    when 3
      '新品'
    when 4
      '最热'
    when 5
      '促销'
    end
  end

  attribute :desc do
    html = object.description_of_detail.content
    html.scan(/<img .+?>/).map do |img|
      next unless img =~ /alt="Image"/
      width  = img.match(/width="(\d+)"/)[1]
      height = img.match(/height="(\d+)"/)[1]
      src    = img.match(/src="(.+?)"/)[1]
      [src, width, height]
    end
  end

  attribute :baye_price do
    object.baye_price
  end

  attribute :member_price do
    object.member_price
  end
end
