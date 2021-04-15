require_relative 'config'

class Cli
  def run
    call_api
  end

  private

  def call_api
    api = Hubspot::Crm::Objects::BasicApi.new
    api.get_by_id('contact', 'test_contact', auth_names: 'hapikey')
  rescue Hubspot::Crm::Objects::ApiError => e
    p e
  end
end

p Cli.new.run
