class ManageFeaturesController < BaseController
  # 用于开关某些 feature，因为审核较慢，而前端功能通常没有那么复杂

  def index
    h = {
      enableGuanDao: false, #启用管到产品的购买
      enableNewFlag: false  #将首页调整为正式的产品数量，而不是为了审核
    }
    render json: h
  end
end
