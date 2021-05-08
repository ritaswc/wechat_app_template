class DistrictsController < BaseController
  def cities
    data = Baye::District.cities(params[:province])
    render json: data
  end

  def counties
    data = Baye::District.counties(params[:province], params[:city])
    render json: data
  end

  def districts
    data = Baye::District.cities(params[:province]) if params[:province].present?
    data = Baye::District.counties(params[:province], params[:city]) if params[:city].present?
    data = Baye::District.provinces if params[:province].nil? && params[:city].nil?
    render json: data
  end
end
