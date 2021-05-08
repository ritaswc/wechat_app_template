CarrierWave.configure do |config|
  config.storage           = (Rails.env.test? ? :file : :aliyun)
  config.enable_processing = false if Rails.env.test?
  config.aliyun_access_id  = ENV['ALIYUN_ACCESS_ID']
  config.aliyun_access_key = ENV['ALIYUN_ACCESS_KEY']
  # 你需要在 Aliyum OSS 上面提前创建一个 Bucket
  config.aliyun_bucket     = ENV['ALIYUN_BUCKET'] || 'baye-media'
  # 是否使用内部连接，true - 使用 Aliyun 局域网的方式访问  false - 外部网络访问
  config.aliyun_internal   = false
  # 配置存储的地区数据中心，默认: cn-hangzhou
  config.aliyun_area       = 'cn-shanghai'
end
