<?php 

require_once 'php_action/core.php';

// remove all session variables
session_unset(); 

setcookie('storename',$storename,time() - 1);
setcookie('password',$password,time() - 1);
// destroy the session 
session_destroy(); 

include 'closeconnection.php';
//header('location: http://localhost/websites/stock-2/login.php');
header('location: login.php');

?>