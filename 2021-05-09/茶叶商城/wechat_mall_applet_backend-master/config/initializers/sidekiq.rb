require 'sidekiq'
require Rails.root.join("config", 'initializers', 'redis.rb')

Sidekiq.configure_server do |config|
  #config.redis = ConnectionPool.new(size: 5, &redis_conn)
  config.redis = $redis_config

  config.error_handlers << Proc.new { |ex, ctx_hash| SidekiqErrorMailer.send_email(ex, ctx_hash).deliver_now }
end

Sidekiq.configure_client do |config|
  #config.redis = ConnectionPool.new(size: 1, &redis_conn)
  config.redis = $redis_config
end

