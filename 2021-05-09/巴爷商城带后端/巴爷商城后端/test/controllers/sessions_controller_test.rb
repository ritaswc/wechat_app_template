require 'test_helper'

class SessionsControllerTest < ActionDispatch::IntegrationTest
  setup do
    @wechat_user = wechat_users(:gehao)
    @customer = customers(:gehao)
    @client_token = @wechat_user.client_token

    @header = { 'Content-Type' => 'application/json' }
    @head_with_token = @header.merge('Authorization' => @client_token)

    SessionsController.class_eval do
      def update_wechat_user_token
        @token = 'wx_' + SecureRandom.hex(20)
        true
      end
    end
  end

  test 'token login' do
    post login_sessions_url, params: {}, headers: @head_with_token
    resp = JSON.parse @response.body

    assert_equal(@client_token, resp['token'])
    assert_equal(@customer.name, resp['customer']['name'])
    assert_equal(@customer.baye_rank, resp['customer']['baye_rank'])
  end

  test 'password login success' do
    post login_sessions_url(mobile: @customer.mobile, password: '1234567'), headers: @header

    assert_response :success
    resp = JSON.parse(@response.body)

    # Not equal!
    assert_not_equal(@client_token, resp['token'])

    assert_equal(@customer.name, resp['customer']['name'])
    assert_equal(@customer.baye_rank, resp['customer']['baye_rank'])
  end

  test 'mobile code login success' do
    Verification.send_message!(@customer.mobile)
    mobile_code = Verification.where(mobile: @customer.mobile).last.code

    post login_sessions_url(mobile: @customer.mobile, mobile_code: mobile_code), headers: @header

    assert_response :success
    resp = JSON.parse(@response.body)

    # Not equal!
    assert_not_equal(@client_token, resp['token'])

    assert_equal(@customer.name, resp['customer']['name'])
    assert_equal(@customer.baye_rank, resp['customer']['baye_rank'])
  end

  test 'mobile code login (New mobile) success' do
    new_mobile = '13055556666'

    Verification.send_message!(new_mobile)
    mobile_code = Verification.where(mobile: new_mobile).last.code

    post login_sessions_url(mobile: new_mobile, mobile_code: mobile_code), headers: @header

    assert_response :success
    resp = JSON.parse(@response.body)

    # Not equal!
    assert_not_equal(@client_token, resp['token'])
    assert_not_nil(Customer.find_by(mobile: resp['customer']['mobile']))
  end
end
