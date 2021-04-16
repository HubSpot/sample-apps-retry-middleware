import os
from dotenv import load_dotenv
from hubspot import HubSpot
from urllib3.util.retry import Retry

def api_key():
  load_dotenv()
  return os.environ['HUBSPOT_API_KEY']

retry = Retry(
  total=3,
  backoff_factor=1,
  status_forcelist=(404, 500),
)

api_client = HubSpot(api_key=api_key(), retry=retry)
api_client.crm.objects.basic_api.get_by_id(object_type='contact', object_id='unknown_id')

