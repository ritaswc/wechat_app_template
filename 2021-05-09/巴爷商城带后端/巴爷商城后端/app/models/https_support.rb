require 'net/http'
require 'net/https'
require 'uri'

module HttpsSupport
  def get_https_client(uri)
    http = Net::HTTP.new(uri.host, uri.port)
    http.use_ssl = true
    if Rails.env.development?
      http.verify_mode = OpenSSL::SSL::VERIFY_NONE
    else
      http.verify_mode = OpenSSL::SSL::VERIFY_PEER
    end
    http.cert_store = OpenSSL::X509::Store.new
    http.cert_store.set_default_paths
    http
  end
end
