app_root = '/var/www/landlord_rapi/current'
pidfile "#{app_root}/tmp/pids/puma.pid"
state_path "#{app_root}/tmp/pids/puma.state"
stdout_redirect "#{app_root}/log/puma.stdout.log", "#{app_root}/log/puma.stderr.log", true
daemonize true
port 8000
workers 4
threads 4, 10
preload_app!

on_worker_boot do
  ActiveSupport.on_load(:active_record) do
    ActiveRecord::Base.establish_connection
  end
end

before_fork do
  ActiveRecord::Base.connection_pool.disconnect!
end
