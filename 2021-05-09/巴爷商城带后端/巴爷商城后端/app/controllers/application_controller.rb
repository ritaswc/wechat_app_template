class ApplicationController < BaseController
  before_action :authenticate_customer!

  private

  def authenticate_customer!
    return render json: { return_status: 401, retrun_message: 'Unauthorized' }, status: 401 if current_customer.blank?
  end
end
