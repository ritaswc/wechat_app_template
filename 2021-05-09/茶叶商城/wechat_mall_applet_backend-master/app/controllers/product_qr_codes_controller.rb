class ProductQrCodesController < BaseController
  VERSION = 1


  # WARN: deprecate method!
  def image
    return render status: 403

    file_name = check_page_path
    return render status: 403, json: { errcode: 1, msg: '这个小程序地址不在rapi白名单中' } if file_name.nil?

    file_path = Rails.root.join('public', 'page_qr_codes', file_name)
    unless File.exist?(file_path)
      file = Wechat.api.wxa_create_qrcode(params[:path]).open
      IO.binwrite(file_path, file.read)
    end

    url = request.base_url + '/page_qr_codes/' + file_name
    render json: { url: url }
  end

  def render_pay_qr_image
    key = 'pay_' + SecureRandom.hex(8)
    # Cart 是 tabBar 可能不能带参数了
    qr_image_url = "pages/cart/cart?ticket=#{key}"
    temp_info = params.to_json

    $redis.set(key, temp_info)
    $redis.expire(key, 7200)

    file_base_name = "#{key}.jpg"
    file_path = Rails.root.join('public', 'page_qr_codes', file_base_name)
    file = Wechat.api.wxa_create_qrcode(qr_image_url).open
    IO.binwrite(file_path, file.read)

    url = request.base_url + '/page_qr_codes/' + file_base_name
    render json: { url: url }
  end

  private

  def check_page_path
    case params[:path]
    when %r{pages/show_product/show_product\?id=(\d+)&share=1\z}
      "show_product_#{$1}_share_v#{VERSION}.jpg"
    when %r{pages/index/index\z}
      "index_v#{VERSION}.jpg"
    end
  end
end
