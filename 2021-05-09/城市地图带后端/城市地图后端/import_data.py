# -*- coding: utf-8 -*-

from coffee.models import Spot
import csv

with open('coffee_spot.csv') as csvfile:
  reader = csv.DictReader(csvfile, delimiter=';')
  for row in reader:
    spot = Spot(id=row['id'], city=row['city'], name=row['name'], longitude=row['longitude'],
      latitude=row['latitude'], download_speed=row['download_speed'], speed_test_link=row['speed_test_link'],
      price_indication=row['price_indication'], bathroom=row['bathroom'], commit_user_name=row['commit_user_name'],
      commit_message=row['commit_message'], commit_date=row['commit_date'], commit_user_id=1)
    spot.save()

