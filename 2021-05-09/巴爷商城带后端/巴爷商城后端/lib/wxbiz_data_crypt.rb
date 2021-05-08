class WXBizDataCrypt
  def initialize(app_id, session_key)
    @app_id = app_id
    @session_key = Base64.decode64(session_key)
  end

  def decrypt(encrypted_data, iv)
    encrypted_data = Base64.decode64(encrypted_data)
    iv = Base64.decode64(iv)

    cipher = OpenSSL::Cipher::AES.new(128, :CBC)
    cipher.decrypt
    cipher.padding = 0
    cipher.key = @session_key
    cipher.iv  = iv
    data = cipher.update(encrypted_data) << cipher.final
    result = JSON.parse(data[0...-data.last.ord])

    raise '解密错误' if result['watermark']['appid'] != @app_id
    result
  end
end
