import sys
from geopy.geocoders import Nominatim

def get_location(latitude,longitude):
#initialize the Nominatim object
   Nomi_locator = Nominatim(user_agent="My App")

#get the location
   location = Nomi_locator.reverse(f"{latitude}, {longitude}")

   print(location)

get_location(sys.argv[1],sys.argv[2])