module PaperTrail
  class Version < ApplicationRecord
    include PaperTrail::VersionConcern
    establish_connection :baye_trail_versions
  end
end
