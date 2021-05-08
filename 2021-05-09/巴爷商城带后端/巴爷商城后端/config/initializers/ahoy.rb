module Ahoy
  self.protect_from_forgery = false
  class Store < Ahoy::Stores::ActiveRecordTokenStore
    def visit_model
      Baye::Visit
    end

    def event_model
      Baye::Event
    end

    def user
      controller.current_customer
    end

    def track_visit(options, &block)
      super do |visit|
        visit.platform = 'rapi'
      end
    end
  end
end
