<?php 	

//localhost code
/*
$localhost = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "stock2";
*/

//web code

$localhost = "sql286.your-server.de";
$username = "javy2021";
$password = "@Ja20vy20";
$dbname = "javy2021";

$localhost = "172.105.101.31:3306";
$username = "konsoleh";
$password = "Billion@23";
$dbname = "javyte_db1";




// db connection
$connect = new mysqli($localhost, $username, $password, $dbname);
// check connection
if($connect->connect_error) {
  die("Connection Failed : " . $connect->connect_error);
} else {
  // echo "Successfully connected";
}

?>