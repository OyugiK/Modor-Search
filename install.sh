
#!/bin/bash
# installation script for the webApp

echo "Starting the installation of the Modor-Search Application"


# check if the installatio folder exists
if [ -d "/tmp/modor" ] || [ -d "/var/www/html/modorwebapp" ] || [ -d "/usr/src/graph-api"]
then
    echo "Directory /tmp/modor/ exists."
    echo "Entering installation directory."
else
    echo "Creating the sources installation directory /tmp/modor"
    # create installation dir
	sudo mkdir /tmp/modor
	echo "Creating the web app directory in /var/www/html "
	# make the web app directory in /var/www/html
	sudo mkdir /var/www/html/modorwebapp
	echo "Creating the api installation directory /usr/src/graph-api"
	# create the api directory
	sudo mkdir /usr/src/graph-api
fi

cd /tmp/modor

# get the file contents from github
git clone https://github.com/OyugiK/Modor-Search.git

# enter the git repo
cd Modor-Search

# move web app to web directory
mv webApp /var/www/html/modorwebapp

# move api to run location
mv api /usr/src/graph-api

# insert the json data to mongodb
cd file-parser

# run the python script to insert json to mongodb
python mongo-parser.py

# startup the api
cd /usr/src/graph-api/api/

#install any modules that may be missing
npm install

# start the api
node app.js

