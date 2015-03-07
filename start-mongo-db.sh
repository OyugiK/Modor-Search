# script to help with starting mongodb

# remove any lock files just incase there are any
sudo rm /data/db/mongod.lock
# repair the db
sudo mongod --dbpath /data/db --repair
# start up mongo
sudo mongod --dbpath /data/db



