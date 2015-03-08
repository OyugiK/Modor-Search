
#!/bin/bash
# installation script for the webApp

echo "Starting the installation of the Modor-Search Application"

echo "checking if installation folder exists ..."
# check if the installatio folder exists
if [ -d "/tmp/modor" ];then
	# statements	
    echo "Directory /tmp/modor/ exists!"
    echo "Emptying the previous directory"
    # empty/delete the directory
    sudo rm -rf /tmp/modor/
    echo "recreating the installation directory in /tmp/modor"
    # recreate the dir
    sudo mkdir /tmp/modor/
else
    echo "Creating the sources installation directory /tmp/modor"
    # create installation dir
	sudo mkdir /tmp/modor/
	
fi

echo "checking if webApp folder exists ..."
# check if webApp dir exists
if [ -d "/var/www/html/modorwebapp" ]; then
	#statements
	echo "Directory /var/www/html/modorwebapp exists!"
	echo "Emptying the previous directory"
	sudo rm -rf /var/www/html/modorwebapp/
	echo "recreating the webApp directory"
	# recrate the webApp dir
	sudo mkdir /var/www/html/modorwebapp/
else
	# create the webApp dir
	echo "Creating the webApp Directory"
	sudo mkdir /var/www/html/modorwebapp/
fi



echo "checking if graphAPI folder exists ..."
# check if graphAPI dir exists
if [ -d "/usr/src/graph-api" ]; then
	#statements
	echo "Directory /usr/src/graph-api exists!"
	echo "Emptying the previous directory"
	sudo rm -rf /usr/src/graph-api/
	echo "recreating the graphAPI directory"
	# recrate the graphAPI dir
	sudo mkdir /usr/src/graph-api/
else
	echo "Creating the api installation directory /usr/src/graph-api"
	# create the api directory
	sudo mkdir /usr/src/graph-api/
fi


echo "Entering the installation directory"
cd /tmp/modor/

echo "Starting the github repo clone"
# get the file contents from github
sudo git clone https://github.com/OyugiK/Modor-Search.git

echo "Entering the Modor-Search directory"
# enter the git repo
cd Modor-Search/

echo "Deploying the webApp to installation folder"
# move web app to web directory
sudo mv webApp /var/www/html/modorwebapp/

echo "Deploying the graphAPI to the installation folder"
# move api to run location
sudo mv graphAPI /usr/src/graph-api/

# insert the json data to mongodb
cd file-parser/

echo "Populating the Database"
# run the python script to insert json to mongodb
python mongo-parser.py

echo "Entering the graphAPI installation foler"
# startup the api
cd /usr/src/graph-api/graphAPI/

echo "Installing node modules incase any is missing"
#install any modules that may be missing
sudo npm install

echo "Starting the graphAPI"
# start the api
sudo node app.js

