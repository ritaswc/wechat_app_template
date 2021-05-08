class Profile < ActiveModelSerializers::Model
  attr_accessor :id, :sku, :name, :image, :inventory, :asset_count
end
