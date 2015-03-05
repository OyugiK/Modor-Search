var express 	= require("express");
var mongoose 	= require("mongoose");
var bodyParser 	= require("body-parser");
var graphAPI 	= require("./people.js");
var cookieParser = require('cookie-parser');
var expressHbs = require('express3-handlebars');


var app = express();

app.engine('hbs', expressHbs({extname:'hbs', defaultLayout:'main.hbs'}));

app.set('view engine', 'hbs');
app.use(bodyParser());

// instantiate the people graph api
var api = new graphAPI();

// List all People

app.get('/api/people', function(req, res){
	//call the api
	data =  api.listAllPeople(req,res);
	res.render('findAll', data);
	console.log(data);

});


// Search by Name
app.get('/api/people/name/:name', function(req, res){
	// call the api
	data = api.findByName(req,res);

});


// Search by Phone Number
app.get('/api/people/phone/:phone', function(req, res){
	// call the api
	data =  api.findByPhoneNumber(req,res);
	res.render('findByPhone', data);

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

// About
app.get('/about', function(req,res){
	//res.sendFile('about.html');
	res.render('index');
});

app.get('/complex', function(req, res){
  var data = {
    name: 'Gorilla',
    address: {
      streetName: 'Broadway',
      streetNumber: '721',
      floor: 4,
      addressType: {
        typeName: 'residential'
      }
    }
  };
  res.render('complex', data);
});

app.get('/loop', function(req, res){
  var basketballPlayers = [
    {name: 'Lebron James', team: 'the Heat'},
    {name: 'Kevin Durant', team: 'the Thunder'},
    {name: 'Kobe Jordan',  team: 'the Lakers'}
  ];
  
  var days = [
    'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
  ];
  
  var data = {
    basketballPlayers: basketballPlayers,
    days: days
  };
  
  res.render('loop', data);
});


app.listen(3000);
console.log("Running on port 3000");