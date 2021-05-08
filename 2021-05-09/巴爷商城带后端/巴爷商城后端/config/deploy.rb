# config valid only for current version of Capistrano
lock '~> 3.8.0'

set :application, 'landlord_rapi'
set :repo_url, 'git@github.com:bayetech/wechat_mall_applet_backend.git'

# puma
set :puma_role, :app
set :puma_init_active_record, true
set :puma_config_file, 'config/puma-web.rb'
# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
# set :deploy_to, '/var/www/my_app_name'

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: 'log/capistrano.log', color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
append :linked_files, 'config/database.yml', 'config/secrets.yml', 'config/redis.yml',
                      'config/application.yml', 'config/sidekiq.yml', 'config/wechat.yml'

# Default value for linked_dirs is []
append :linked_dirs, 'log', 'tmp/pids', 'tmp/cache', 'tmp/sockets', 'public/system', 'public/swagger_doc', 'public/page_qr_codes'

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
# set :keep_releases, 5
