<?php
/**
 * Project:  Simple postcode lookup service
 * Author:   Philidor
 * Version:  1.001
 * Framework: SlimFramework
 *
 * Modification History: 
 *               - 6/17/2013 
 
 #Sample Output
//http://127.0.0.1/_SlimFramework/sfapp/postcode/near/13.2947/123.7933
//http://127.0.0.1/_SlimFramework/sfapp/postcode/0902

 */
require 'Slim/Slim.php';
$app = new Slim();


$app->get('/postcode', 'queryAll');
$app->get('/postcode/:code',  'queryPostCode');
$app->get('/postcode/near/:lat/:long',  'queryPostCodeNear');

$app->run();

//Get Query By all data
function queryAll() {
	$sql = "select postcode,place_name,latitude,longitude FROM geo ORDER BY postcode";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$geoList = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"geo": ' . json_encode($geoList) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}


//Get Query By postcode
function queryPostCode($code) {
	
	$sql = "SELECT postcode,place_name,latitude,longitude FROM geo WHERE postcode='$code'";
	
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$geoList = $stmt->fetchObject();  
		$db = null;
		echo json_encode($geoList); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
	
	
	
	
}
//Get Query By latitude and longitude
function queryPostCodeNear($lat,$long) {
	
	$sql = "SELECT postcode,place_name,latitude,longitude FROM geo WHERE latitude='$lat' and longitude='$long'";

	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$geoList = $stmt->fetchObject();  
		$db = null;
		echo json_encode($geoList); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}


//Connection
function getConnection() {
	$dbhost="127.0.0.1";
	$dbuser="root";
	$dbpass="";
	$dbname="sf_myappdb";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}


?>