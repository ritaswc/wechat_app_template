require Rails.root.join("config", 'initializers', 'redis.rb')

#cl2009
Cl2009.setup do |config|
  config.server  = 'http://222.73.117.156'
  config.account = ENV['CL_ACCOUNT']
  config.password = ENV['CL_PASSWORD']
  config.redis = $redis
end
