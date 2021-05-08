module Baye
  class CustomerAssetSerializer < ActiveModel::Serializer
    attributes :no, :product_id, :product_icon, :sub_products, :inventory_changes
    belongs_to :product

    def no
      Baye::CustomerAsset.where(customer_id: object.customer_id, product_id: object.product_id).map{|asset| asset.no}.join(",")
    end

    def product_icon
      ENV['server_url'] + "/images/#{object.product_id}_asset_info.png"
    end

    def sub_products
      arr = []

      object.product.relation_products.each do |sub_product|
        arr << { name: sub_product.name, stock: object.customer.inventory_changes.find_by(product_id: sub_product).quantity / 1000.0 }
      end

      arr
    end

    def inventory_changes
      sub_product_ids = object.product.relation_products.map(&:id)
      result = object.customer.inventory_changes.where(product_id: sub_product_ids).order('created_at desc').group_by do |ic|
        ic.created_at.year
      end
      result
    end
  end
end
