class ProfileSerializer < ActiveModel::Serializer
  attributes :id, :sku, :name, :image, :inventory, :asset_count
end