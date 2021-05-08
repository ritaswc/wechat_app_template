class ProductsController < BaseController
  swagger_controller :products, 'Bayetech Product'

  swagger_api :index do
    summary 'Fetches all Product items'
    notes 'This lists all on_sale common is_search and enabled products'
  end

  # {icon: "../../images/icon-new-list1.png", name: "资产", typeId: 0}
  # {icon: "../../images/icon-new-list2.png", name: "直销", typeId: 1}
  # {icon: "../../images/icon-new-list3.png", name: "甄选", typeId: 2}
  # {icon: "../../images/icon-new-list4.png", name: "管到", typeId: 3}
  def index
    @products = Product.includes(:description_of_detail).includes(:images).on_sale.common.is_search.enabled
    @products = case params[:type]
                when "0"
                  @products.subscription_goods
                when "1"
                  #  4 食品
                  #  6 大米
                  #  7 茶叶
                  # 15 白酒
                  @products.where(category_id: [4, 6, 7, 15])
                when "2"
                  @products.where(category_id: 17)
                when "3"
                  @products.where(category_id: 18, product_id: nil)
                else
                  @products.where(flag: [1, 3, 4, 5])
                end

    render json: @products
  end

  def show
    products = Product.includes(:description_of_detail).includes(:images).on_sale.common.is_search.enabled
    @product = products.find(params[:id])
    render json: @product
  end
end
