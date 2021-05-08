Rails.application.routes.draw do
  resources :manage_features, only: [:index]
  resources :products, only: [:index, :show]
  resources :home_slides, only: [:index]
  resources :coupons, only: [] do
    collection do
      get :list
    end
  end

  resources :orders, only: [] do
    collection do
      post :create_applet_order
    end
  end

  resources :sessions, only: [] do
    collection do
      post :wechat_user_type
      post :login
      post :logout
      get :get_mobile_passcode
    end
  end

  resources :my_assets, only: [:index, :show], param: :sku

  resource :send_validation_code, only: [] do
    get :send_message
  end

  namespace :wechat do
    resources :payments, only: [] do
      member do
        post :notify
      end
    end
  end

  resources :districts, only: [] do
    collection do
      get :provinces
      get :cities
      get :counties
      get :districts
    end
  end
  resources :product_qr_codes, only: [] do
    collection do
      get :image
    end
  end
end
