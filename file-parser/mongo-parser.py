# Author : Kevin Oyugi

# Simple JSON to Mongo Parser
# Take contents of file and upload the data to MongoDB


import json
import pymongo
import datetime

json_data=open('people.json').read()
data = json.loads(json_data)


total = data['total']

conn = pymongo.Connection('mongodb://localhost/gwaji')
db = conn['gwaji']

count = 0
while count < total:
    #print count
    count += 1  # This is the same as count = count + 1


    ID	= data['result'][count]['id']
    guid = data['result'][count]['guid']
    picture = data['result'][count]['picture']
    age = data['result'][count]['age']
    name = data['result'][count]['name']
    gender = data['result'][count]['gender']
    company = data['result'][count]['company']
    phone = data['result'][count]['phone']
    email = data['result'][count]['email']
    address = data['result'][count]['address']
    about = data['result'][count]['about']
    registered = data['result'][count]['registered']
    tags = data['result'][count]['tags']
    friends = data['result'][count]['friends']

       

    new_posts = {'id': ID,
                  'guid': guid,
                  'picture': picture,
                  'age': age,
                  'name': name,
                  'gender': gender,
                  'company': company,
                  'phone': phone,
                  'email': email,
                  'address': address,
                  'about': about,
                  'registered': registered,
                  'friends': friends,
                  'date': datetime.datetime(2009, 11, 12, 11, 14)}


    db.gwaji.insert(new_posts)
