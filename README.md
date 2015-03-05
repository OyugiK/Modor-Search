
People Graph API

GET /api/people/ 							- Search all the people
GET /api/people/name/Chloe%20Hamphrey	 	- Search by Name
GET /api/people/phone/889-590-3598 			- Search by Phone
GET /api/people/company/Celgra				- Search by Company
GET /api/people/pals/Alexandra%20Youmans	- Search by Friend
GET /api/people/address/27735%2C%20Columbia%2C%20Stanton%20Streets - Search by Address



CURL Samples

Search All People

curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://127.0.0.1:3000/api/people/


Search By Name
curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://127.0.0.1:3000/api/people/name/Chloe%20Hamphrey


Search By Phone Number
curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://127.0.0.1:3000/api/people/phone/889-590-3598


Search By Company
curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://127.0.0.1:3000/api/people/company/Celgra


Search By Friends
curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://127.0.0.1:3000/api/people/pals/Alexandra%20Youmans



Search By Address
curl -i -H "Accept: application/json" -H "Content-Type: application/json" -X GET http://127.0.0.1:3000/api/people/address/27735%2C%20Columbia%2C%20Stanton%20Streets
