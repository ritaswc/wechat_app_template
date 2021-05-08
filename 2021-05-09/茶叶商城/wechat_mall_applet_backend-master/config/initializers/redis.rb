require 'redis'

#redis
redis_config_in_file = YAML.load(File.open(Rails.root.join("config/redis.yml"))).symbolize_keys
redis_default_config = redis_config_in_file[:default].symbolize_keys
$redis_config = redis_default_config.merge(redis_config_in_file[Rails.env.to_sym].symbolize_keys) if redis_config_in_file[Rails.env.to_sym]

$redis = Redis.new($redis_config)

# To clear out the db before each test
$redis.flushdb if Rails.env.test?
