var express = require("express");
var mongoose = require("mongoose");

var app		= express();

mongoose.connect('mongodb://localhost/modoz');

var personSchema = {
	id:Number,
	guid:String,
	picture:String,
	age:Number,
	name:String,
	gender:String,
	company:String,
	phone:String,
	email:String,
	address:String,
	about:String,
	registered:String,
	tags:String,
	friends:{
		id:Number,
		name:String
	},
	date:Date
}

var Person = mongoose.model('Person', personSchema, 'modoz')


// app.use(express.static(__dirname + '/views'));
// //Store all HTML files in view folder.
// app.use(express.static(__dirname + '/Script'));
// //Store all JS and CSS in Scripts folder.

/**

People Graph API

**/

module.exports = exports = GraphAPI = function(){


	/**
	List all People in the Graph Network

	**/

	this.listAllPeople = function(req, res){
		// query
		return Person.find(function (err,doc){
			if (!err) {
				if (doc.length == 0) {				
						res.send({"status" : "404 Person Doesnt Exist"});
						console.log({"status" : "404 Person Doesnt Exist"});
					}
					else{
						res.send(doc);
						console.log({"status" : "200 OK"});
						// Populate a new field views
					}			
				}
				else{

					console.log(err);
				}						
			});
		},

	/**
	Find People by Name
	@param : Full Names
	**/

	this.findByName = function(req, res){
		//query
		return Person.find({name: req.params.name}, function(err,doc){
			if (!err) {
				if (doc.length == 0) {				
					res.send({"status" : "404 Person Doesnt Exist"});
					console.log({"status" : "404 Person Doesnt Exist"});
				}
				else{
					res.send(doc);
					console.log({"status" : "200 OK"});
					// Populate a new field views
				}			
			}
			else{
				//res.send({"status" : "400 Person Bad Request"});
				console.log(err);
			}		
		});

	},

	/**
	Find people by MSISDN
	@param : Phone Number
	**/

	this.findByPhoneNumber = function(req, res){
		Person.find({phone: req.params.phone}, function(err,doc){
			if(!err){
				if (doc.length ==0) {
					res.send({"status" : "404 Person Doesnt Exist"});
					console.log({"status" : "404 Person Doesnt Exist"});
				}
				else{
					res.send(doc);
					console.log({"status" : "200 OK"});
				}			
			}
			else{
				res.send({"status" : "400 Person Bad Request"});
				console.log(err);
			}
		});

	},



	/**
	Find people by company
	@param : company
	**/

	this.findByCompany = function(req, res){
		Person.find({company: req.params.company}, function(err,doc){
			if(!err){
				if (doc.length ==0) {
					res.send({"status" : "404 Person Doesnt Exist"});
					console.log({"status" : "404 Person Doesnt Exist"});
				}
				else{
					res.send(doc);
					console.log({"status" : "200 OK"});
				}			
			}
			else{
				res.send({"status" : "400 Person Bad Request"});
				console.log(err)
			}
		});
	},


	/** 
	Find People by Friends
	@param : friends name
	**/

	this.findByFriends = function(req, res){
		Person.find({'friends.name': req.params.name}, function(err,doc){
			if(!err){
				if (doc.length ==0) {
					res.send({"status" : "404 Person Doesnt Exist"});
					console.log({"status" : "404 Person Doesnt Exist"});
				}
				else{
					res.send(doc);
					console.log({"status" : "200 OK"});
				}
			}
			else{
				res.send({"status" : "400 Person Bad Request"});
				console.log(err)
			}
		});
	},
	

	/**
	Find People By Address
	@param : address
	**/

	this.findByAddress = function(req, res)	{
		Person.find({address: req.params.address}, function(err,doc){
			if(!err){				
				if (doc.length ==0) {
					res.send({"status" : "404 Person Doesnt Exist"});
					console.log({"status" : "404 Person Doesnt Exist"});
				}
				else{
					res.send(doc);
					console.log({"status" : "200 OK"});
				}
			}
			else{
				console.log(err);
			}
		});
	}

	// Finf by tags,email,about

};


app.get('/', function(req,res){
	res.sendFile('index.html');
});


app.get('/about', function(req,res){
	res.sendFile('about.html');
});
