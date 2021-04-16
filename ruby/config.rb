# Load the gem
require 'hubspot-api-client'
require 'dotenv'

Dotenv.load

# Setup authorization
Hubspot.configure do |config|
  # Configure API key authorization: hapikey
  config.api_key['hapikey'] = ENV['HUBSPOT_API_KEY']
  config.error_handler = {
    429 => {
      max_retries: 2,
      seconds_delay: 10,
      retry_block: -> () { puts 'API returned 429 Status Code. Sleeping...' }
    }
  }
end
