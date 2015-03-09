
People Graph API
----------------

GET /api/people/ 							- list all the people


GET /api/people/name/Chloe%20Hamphrey	 	- Search by Name


GET /api/people/phone/889-590-3598 			- Search by Phone


GET /api/people/company/Celgra				- Search by Company


GET /api/people/pals/Alexandra%20Youmans	- Search by Friend


GET /api/people/address/27735%2C%20Columbia%2C%20Stanton%20Streets?username=87bf83fc&password=f4a1112a - Search by Address


### CURL Samples


#### Search All People

curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET 'http://127.0.0.1:3000/api/people/?username=87bf83fc&password=f4a1112a'



#### Search By Name

curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET 'http://127.0.0.1:3000/api/people/name/Chloe%20Hamphrey?username=87bf83fc&password=f4a1112a'


#### Search By Phone Number

curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET 'http://127.0.0.1:3000/api/people/phone/889-590-3598?username=87bf83fc&password=f4a1112a'


#### Search By Company

curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET 'http://127.0.0.1:3000/api/people/company/Celgra?username=87bf83fc&password=f4a1112a'


#### Search By Friends

curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET 'http://127.0.0.1:3000/api/people/pals/Alexandra%20Youmans?username=87bf83fc&password=f4a1112a'


#### Search By Address

curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET 'http://127.0.0.1:3000/api/people/address/27735%2C%20Columbia%2C%20Stanton%20Streets?username=87bf83fc&password=f4a1112a'

### Installation

Run the install.sh script after cloning or downloading the sources.

$ ./install.sh

### Usage

Load the URL [http://[your host]/modorwebapp/webApp/index.php] login and see how it works.


Test search using the following :


a) name : Chloe Hamphrey 


b) phone : 889-590-3598


c) address : 27735, Columbia, Stanton Streets


d) company : Celgra 




