require_relative 'boot'

require "rails"
# Pick the frameworks you want:
require "active_model/railtie"
require "active_job/railtie"
require "active_record/railtie"
require "action_controller/railtie"
require "action_mailer/railtie"
require "action_view/railtie"
require "action_cable/engine"
# require "sprockets/railtie"
require "rails/test_unit/railtie"

# Require the gems listed in Gemfile, including any gems
# you've limited to :test, :development, or :production.
Bundler.require(*Rails.groups)

module LandlordRapi
  class Application < Rails::Application
    # Rails 5
    config.middleware.insert_before 0, Rack::Cors do
      allow do
        origins ['http://localhost:3000', 'https://swagger-ui.bayekeji.com']
        resource '*', headers: :any, methods: [:get, :post, :options]
      end
    end

    # Settings in config/environments/* take precedence over those specified here.
    # Application configuration should go into files in config/initializers
    # -- all .rb files in that directory are automatically loaded.
    config.time_zone = 'Beijing'
    config.active_record.default_timezone = :local

    # Only loads a smaller set of middleware suitable for API only apps.
    # Middleware like session, flash, cookies can be added back manually.
    # Skip views, helpers and assets when generating a new resource.
    config.api_only = true
    config.i18n.default_locale = 'zh-CN'
    config.i18n.available_locales = ['zh-CN', 'en']
    config.i18n.fallbacks = true
  end
end

I18n.config.enforce_available_locales = false
I18n.locale = 'zh-CN'
