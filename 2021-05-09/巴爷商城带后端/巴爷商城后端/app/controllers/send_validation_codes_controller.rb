class SendValidationCodesController < BaseController

  def send_message
    mobile = params[:mobile]
    render json: { code: 20005, message: '手机号不存在' }.to_json and return if mobile.blank?
    render json: { code: 20004, message: '手机格式不对' }.to_json and return unless (mobile =~ /\A1[3-9][0-9]\d{8}\z/)
    render json: { code: 20003, message: '发送频率过多' }.to_json and return unless Verification.can_send?(mobile)
    render json: { code: 20001, message: '发送成功' }.to_json and return if Verification.send_message!(mobile)
  end
end
