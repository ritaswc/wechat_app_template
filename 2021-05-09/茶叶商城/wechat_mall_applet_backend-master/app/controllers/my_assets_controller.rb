class MyAssetsController < ApplicationController
  def index
    products = Product.asset.where(product_id: nil).common

    # 这三张轮播图是 wechat 项目里的 assets
    image_hash = {}
    image_hash['TF-WUYI']      = "images/asset_TF-WUYI.jpg"
    image_hash['RF-WUCHANG']   = "images/asset_RF-WUCHANG.jpg"
    image_hash['100100300100'] = "images/asset_100100300100.jpg"

    arr = products.map do |product|
      image = image_hash[product.sku]
      next if image.blank?
      inventory = product.relation_products.map do |relation_product|
        inventory = current_customer.inventories.where(product_id: relation_product.id).first
        stock = inventory.present? ? inventory.quantity : 0
        relation_product.name + stock.to_s + relation_product.unit + " "
      end
      asset_count = current_customer.assets.where(product_id: product.id).count || 0

      Profile.new(id: product.id,
                  sku: product.sku,
                  inventory: "库存：#{inventory.join}",
                  name: product.name,
                  asset_count: "#{asset_count}亩",
                  image: image)
    end.compact

    render json: arr
  end

  def show
    product = Product.find_by(sku: params[:sku])

    my_asset = current_customer.assets.find_by(product_id: product.id)

    render json: my_asset, include: ['product']
  end
end
