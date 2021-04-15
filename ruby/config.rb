# Load the gem
require 'hubspot-api-client'
require 'dotenv'

Dotenv.load

# Setup authorization
Hubspot.configure do |config|
  # Configure API key authorization: hapikey
  config.api_key['hapikey'] = ENV['HUBSPOT_API_KEY']
  config.error_handler = {
    404 => {
      max_retries: 3,
      seconds_delay: 5,
      retry_block: -> () { puts 'API returned 404 Status Code. Sleeping...' }
    }
  }
end
