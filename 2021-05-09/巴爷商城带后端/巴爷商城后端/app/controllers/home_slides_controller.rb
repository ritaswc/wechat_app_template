class HomeSlidesController < BaseController
  swagger_controller :home_slides, 'Bayetech Store Home Slides'

  swagger_api :index do
    summary 'Fetches all store index home slide items'
    notes 'This lists all enabled Store position slide items'
  end

  def index
    @slides = Baye::Slide.enabled.where(position: 'Store').order('sort asc')
    render json: @slides
  end
end
