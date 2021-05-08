module Swagger
  module Docs
    class Config
      def self.base_api_controller
        ActionController::API
      end
    end
    Config.register_apis('1.0' =>
      {
        # the extension used for the API
        :api_extension_type => :json,
        # the output location where your .json files are written to
        :api_file_path => 'public/swagger_doc',
        # Ability to setup base controller for each api version. Api::V1::SomeController for example.
        :parent_controller => ActionController::API,
        # add custom attributes to api-docs
        :attributes => {
          :info => {
            'title' => 'Bayetech Rails API App',
            'description' => 'A grape replacement backend for iOS, Android and Wechat applet.',
            'termsOfServiceUrl' => 'http://www.bayekeji.com/contact',
            'contact' => 'admin@bayekeji.com'
          }
        }
      })
  end
end
