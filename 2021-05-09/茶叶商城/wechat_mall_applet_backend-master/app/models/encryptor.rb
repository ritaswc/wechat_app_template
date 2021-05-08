require 'bcrypt'

module Encryptor
  def self.digest(klass, password)
    if klass.pepper.present?
      password = "#{password}#{klass.pepper}"
    end
    ::BCrypt::Password.create(password, cost: klass.stretches).to_s
  end

  def self.compare(klass, encrypted_password, password)
    return false if encrypted_password.blank?
    bcrypt   = ::BCrypt::Password.new(encrypted_password)
    if klass.pepper.present?
      password = "#{password}#{klass.pepper}"
    end
    password = ::BCrypt::Engine.hash_secret(password, bcrypt.salt)
    secure_compare(password, encrypted_password)
  end

  def self.secure_compare(a, b)
    return false if a.blank? || b.blank? || a.bytesize != b.bytesize
    l = a.unpack "C#{a.bytesize}"

    res = 0
    b.each_byte { |byte| res |= byte ^ l.shift }
    res == 0
  end
end
