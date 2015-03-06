<?php

/*

This class is functional engine of the WebApp

@Author : Kevin Oyugi

*/
ob_start();
error_reporting(E_ALL);
define('__ROOT__', dirname(dirname(__FILE__)));

require_once("PostgresService.php");


class WebAppService{

	//Sanitise Responses
	private function _sanitize($string){
		$url1 = ltrim($string, '[');
		$url2 = rtrim($url1, ']');		
	}

	// REST WEB Service Consumer
	private function _consume($url){		
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($ch);
		curl_close($ch);
		//var_dump($data);
		return $data;
		
	}

	public function search($url){
		$people = $this->_consume($url);
		//var_dump($people);
		return $people;
	}

	# function to get userdetails
	public function getUser($username) {
		$log = new KLogger ( "/tmp/app.log" , KLogger::DEBUG );
		$log->LogInfo("in getUser $username");

		# define variables
		# @param username

		# db connection
		$db = new PostgresService();
		# query
		$query = "select userid, username,  usertype, account_flags, spassword,
		  salt, acl_flags, usertype_fk , password_tries, active_flags  
		  from tbl_users where username = $1";
		$queryParams = array($username);
		$log->LogDebug("Running Q($query) with 
			P(".print_r($queryParams,true).")");
		# keys, K
		$userInfoKeys = array("user-id","username","user-type","account-flags",
			"password", "salt", "acl-flags", "usertype-fk", "password-tries", 
			"active-flags");
		# values, V
		$userInfoArray = $db->select($query,$queryParams);
		$log->LogDebug("Running Q($query) 
			returns-> ".print_r($subInfoArray,true));

		# if failed, we leave
		if(!$userInfoArray || count($userInfoArray) == 0){
			$log->LogFatal("Could not retreive user info for 
				$customer see db class error log");
			return array();
		}

		# the first element in the array is what we need
		$userInfo = $userInfoArray[0];
		$userInfoStr = print_r($subInfo,true);
		$log->LogDebug("userInfoStr -> $userInfoStr");

		# now merge
		$userInfoKV = array();
		if(count($userInfo) == count($userInfoKeys)){
			$log->LogDebug("Converting to HashMap 
				counts be ".count($userInfo));
			$i = 0;
			foreach($userInfo as $val){
				$log->LogDebug("Run $i Setting ".$userInfoKeys[$i] 
					. " => " . $val);
				$userInfoKV[$userInfoKeys[$i]] = $val;
				$i++;
			}
			# return it
			return $userInfoKV;
		}
		else{
			$log->LogFatal("The size of the K != V has the db/select changed?");
			return false;
		}

		# any other
		return false;
	}



}





?>