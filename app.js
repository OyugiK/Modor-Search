/*

The People Graph API provide the nodes that other 3rd parties will call when requesting for data
The API is designed for scale. It is also designed to be ubiquituos
This API is built to take advantage NodeJS non-blocking features i.e
Provide availabilty at all times to users in a fast and efficient fashion

The REST web service end-points can be consumed from :
a) USSD
b) Web App
c) Mobile (Android, Iphone, Nokia)

@Author : Kevin Oyugi
@Year   : 2015

#TODO ::::
Add Tokenisation
Add Logging
Add Authentification



*/


var express 	= require("express");
var mongoose 	= require("mongoose");
var graphAPI 	= require("./people.js");
var cookieParser = require('cookie-parser');
var expressHbs = require('express3-handlebars');


var app = express();

app.engine('hbs', expressHbs({extname:'hbs', defaultLayout:'main.hbs'}));

app.set('view engine', 'hbs');


// instantiate the people graph api
var api = new graphAPI();

// List all People

app.get('/api/people', function(req, res){
	//call the api
	data =  api.listAllPeople(req,res);
	
	return data

});


// Search by Name
app.get('/api/people/name/:name', function(req, res){
	// call the api
	data = api.findByName(req,res);

  return data;

});


// Search by Phone Number
app.get('/api/people/phone/:phone', function(req, res){
	// call the api
	data =  api.findByPhoneNumber(req,res);

  return data;
  

});


// Search By Company
app.get('/api/people/company/:company', function(req, res){
	return api.findByCompany(req,res);

});

// Search by Friends
app.get('/api/people/pals/:name', function(req,res){
	// call the api
	data = api.findByFriends(req,res);
	return data;
});

// Search by Address
app.get('/api/people/address/:address', function(req,res){
	// call the api
	return api.findByAddress(req,res);

});

// Default
app.get('/', function(req,res){
	//res.sendFile('index.html');
	res.render('index');
});

app.get('/about', function(req, res){
  var data = {
    name: 'Graph API',
    info: {
      versionName: 'Broadway',
      Author: 'OyugiK',      
      Contributors: {
        name: 'Skylar White'
      }
    }
  };
  res.render('about', data);
});


app.listen(3000);
console.log("Running on port 3000");