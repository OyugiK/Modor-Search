var express = require("express");
var mongoose = require("mongoose");
var mongoosemask = require("mongoosemask");
var logger = require("./logger.js");

var app		= express();


logger.info("starting the People GraphAPI");

// connect oto 
mongoose.connect('mongodb://localhost/people');
logger.info("connected to database");

//the person schema
// make some things unique
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
	registered:String,
	about:{ type: String, select: true },
	tags:{
		type:String
	},
	friends:{
		id:Number,
		name:String
	},
	date:Date
}


// the model
var Person = mongoose.model('Person', personSchema, 'people')


/**

People Graph API

**/

module.exports = exports = GraphAPI = function(){


	/**
	List all People in the Graph Network

	**/

	this.listAllPeople = function(req, res){
		// query

		return Person.find().lean().exec(function (err,doc){
			if (!err) {
				if (doc.length == 0) {				
						res.send({"status" : "404 Person Doesnt Exist"});
						//console.log({"status" : "404 Person Doesnt Exist"});
						logger.debug({"status" : "404 Person Doesnt Exist"});
					}
					else{
						res.send(JSON.parse(JSON.stringify(doc)));
						//console.log({"status" : "200 OK"});
						// Populate a new field views
						logger.info({"status" : "200 OK"});
						// future logger message
						/*{
							"status": 401,
        					"message": "Invalid credentials"
      					});*/
						
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
		return Person.find({name: req.params.name}).lean().exec(function(err,doc){
			if (!err) {
				if (doc.length == 0) {				
					res.send({"status" : "404 Person Doesnt Exist"});
					// console.log({"status" : "404 Person Doesnt Exist"});
					logger.debug({"status" : "404 Person Doesnt Exist"});
				}
				else{
					res.send(JSON.parse(JSON.stringify(doc)));
					// console.log({"status" : "200 OK"});
					// Populate a new field views
					logger.info({"status" : "200 OK"});
					logger.info("params %s", req.params.name);
					logger.debug("Found %s", doc.length);
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
		Person.find({phone: req.params.phone}).lean().exec(function(err,doc){
			if(!err){
				if (doc.length ==0) {
					res.send({"status" : "404 Person Doesnt Exist"});
					// console.log({"status" : "404 Person Doesnt Exist"});
					logger.debug({"status" : "404 Person Doesnt Exist"});

				}
				else{					
					res.send(JSON.parse(JSON.stringify(doc)));
					// console.log({"status" : "200 OK"});
					logger.info({"status" : "200 OK"});
					logger.info("params %s", req.params.phone);
					logger.debug("Found %s", doc.length);
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
		Person.find({company: req.params.company}).lean().exec(function(err,doc){
			if(!err){
				if (doc.length ==0) {
					res.send({"status" : "404 Person Doesnt Exist"});
					// console.log({"status" : "404 Person Doesnt Exist"});
					logger.debug({"status" : "404 Person Doesnt Exist"});
				}
				else{
					res.send(JSON.parse(JSON.stringify(doc)));
					// console.log({"status" : "200 OK"});
					logger.info({"status" : "200 OK"});
					logger.info("params %s", req.params.company);
					logger.debug("Found %s", doc.length);
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
		Person.find({'friends.name': req.params.name}).lean().exec(function(err,doc){
			if(!err){
				if (doc.length ==0) {
					res.send({"status" : "404 Person Doesnt Exist"});
					// console.log({"status" : "404 Person Doesnt Exist"});
					logger.debug({"status" : "404 Person Doesnt Exist"});
				}
				else{
					res.send(JSON.parse(JSON.stringify(doc)));
					// console.log({"status" : "200 OK"});
					logger.info({"status" : "200 OK"});
					logger.info("params %s", req.params.name);
					logger.debug("Found %s", doc.length);
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
		Person.find({address: req.params.address}).lean().exec(function(err,doc){
			if(!err){				
				if (doc.length ==0) {
					res.send({"status" : "404 Person Doesnt Exist"});
					// console.log({"status" : "404 Person Doesnt Exist"});
					logger.debug({"status" : "404 Person Doesnt Exist"});
				}
				else{
					res.send(JSON.parse(JSON.stringify(doc)));
					// console.log({"status" : "200 OK"});
					logger.info({"status" : "200 OK"});
					logger.info("params %s", req.params.address);
					logger.debug("Found %s", doc.length);
				}
			}
			else{
				console.log(err);
			}
		});
	}

	// Finf by tags,email,about

};


