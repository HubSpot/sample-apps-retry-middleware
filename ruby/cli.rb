require_relative 'config'
require 'parallel'

class Cli
  def run
    call_api
  end

  private

  def call_api
    batch_api = ::Hubspot::Crm::Contacts::BatchApi.new

    Parallel.map((1..10).to_a, in_processes: 10) do |process|
      10.times do
        contacts = [rand(100_000), rand(100_000), rand(100_000)].map do |i|
          ::Hubspot::Crm::Contacts::SimplePublicObjectInput.new(
            properties: { email: "retry_middleware_app#{i}@hubspot.com" }
          )
        end
        contacts_object = ::Hubspot::Crm::Contacts::BatchInputSimplePublicObjectInput.new(
          inputs: contacts
        )
        response = batch_api.create(contacts_object, auth_names: 'hapikey')
        ids_object = ::Hubspot::Crm::Contacts::BatchInputSimplePublicObjectId.new(
          inputs: response.results.map(&:id)
        )
        batch_api.archive(ids_object, auth_names: 'hapikey')
        puts "##{process} :: #{Time.now} :: Created and deleted batch of contacts."
      end
    end
  rescue Hubspot::Crm::Objects::ApiError => e
    p e
  end
end

p Cli.new.run
