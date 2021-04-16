import os
from dotenv import load_dotenv
from hubspot import HubSpot
from urllib3.util.retry import Retry
from hubspot.crm.contacts import ApiException
import threading
import time
import sys

def api_key():
  load_dotenv()
  return os.environ['HUBSPOT_API_KEY']

def create_client():
  retry = Retry(
    total=5,
    backoff_factor=10,
    status_forcelist=(429, 500, 502, 504),
  )

  return HubSpot(api_key=api_key(), retry=retry)

def call_api(number):
  hubspot = create_client()
  try:
    page = hubspot.crm.contacts.basic_api.get_page()
    print(number)
    sys.stdout.flush()
  except ApiException as e:
    print(e)

for i in range(300):
  thread = threading.Thread(target=call_api, args=(i,))
  thread.start()
