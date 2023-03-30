<?php 

require_once 'core.php';

if($_POST) {

	$valid['success'] = array('success' => false, 'messages' => array());

	$userId = $_POST['user_id'];
	$password = md5($_POST['password']);
	//$userId = $_POST['user_id'];

	$sql ="SELECT * FROM users WHERE user_id = {$userId}";
	$query = $connect->query($sql);
	$result = $query->fetch_assoc();

	if($password == $result['password']) {

			$updateSql = "DELETE FROM users WHERE user_id = {$userId} AND password= '$password'";
			if($connect->query($updateSql) === TRUE) {
				$valid['success'] = true;
				$valid['messages'] = "Successfully Deleted";
				
				session_unset(); 

				setcookie('storename',$storename,time() - 1);
				setcookie('password',$password,time() - 1);
				// destroy the session 
				session_destroy(); 

				//header('location: http://localhost/websites/stock-2/login.php');
				header('location: ../login.php');

			} else {
				$valid['success'] = false;
				$valid['messages'] = "Error while deleting store";	
			}

		} else {
		$valid['success'] = false;
		$valid['messages'] = "Incorrect Password. Please Try Again! ";
	}

	

	echo json_encode($valid);

}

include '../closeconnection.php';

?>