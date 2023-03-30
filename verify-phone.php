<?php 
require_once 'php_action/core.php';
require_once '../subdomain_storename.php';

$sqlusers ="SELECT * FROM users WHERE user_id = $userId";
$userResult=$connect->query($sqlusers);
$storeResult=$userResult->fetch_assoc();
$phone=$storeResult['phone'];
$storename=$storeResult['storename'];
$firstname=$storeResult['firstname'];
$lastname=$storeResult['lastname'];
$email=$storeResult['email'];


$errors = array();


if($_POST) {		

	$code = $_POST['code'];
	$code = $connect->real_escape_string($code);


	if(empty($code) ) {

		if($code == "") {
			$errors[] = "Please enter code sent to your phone number";
		} 

	} else {
			// exists

			$mainSql = "SELECT * FROM phone_verification_codes WHERE code = '$code' AND store_id = '$userId'";

			$mainResult = $connect->query($mainSql);

			if($mainResult->num_rows == 1) {

			$userSql = "UPDATE users SET phone_verification_status=1 WHERE user_id = '$userId' ";

			$mainResult = $connect->query($userSql);

							// Be sure to include the file you've just downloaded
				require_once('AfricasTalkingGateway.php');
				// Specify your authentication credentials
				$username   = "Javisotieno";
				$apikey     = "fc8597cbed40cda6a2e7651458aa02b44b5a0a2c148557b39d371e1efe28d6af";
				// Specify the numbers that you want to send to in a comma-separated list
				// Please ensure you include the country code (+254 for Kenya in this case)
				$firstdigit=substr($phone, 0, 1);

				if($firstdigit=='0'){
					$recipients = "+254".substr($phone,1);
				}elseif($firstdigit=='7'){
					$recipients = "+254".$phone;
				}elseif($firstdigit=='2'){
					$recipients = "+".$phone;
				}elseif($firstdigit=="+"){
					$recipients = $phone;
				}

				//$recipients = "+254707641174,+254733YYYZZZ";

				// And of course we want our recipients to know what we really do
				$message_promoter   = "JAVY : $firstname, welcome to Javy. www.".$storeName.".av.ke is ready. Login to http://promote.javy.co.ke to manage your account. Helpline:0716 545459";


				$gateway    = new AfricasTalkingGateway($username, $apikey);

				$from = "JAVY";

				try 
				{ 
				  // Thats it, hit send and we'll take care of the rest. 
				  $results = $gateway->sendMessage($recipients, $message_promoter, $from);
				            
				  foreach($results as $result) {
				    // status is either "Success" or "error message"
				    //echo " Number: " .$result->number;
				    //echo " Status: " .$result->status;
				    //echo " MessageId: " .$result->messageId;
				    //echo " Cost: "   .$result->cost."\n";
				  }
				}
				catch ( AfricasTalkingGatewayException $e )
				{
				  //echo "Encountered an error while sending: ".$e->getMessage();
				}



				$to      = 'javisotieno@gmail.com';
				$subject = 'NEW STORE REGISTERED & VERIFIED : '.$storename;
				$message = 'First Name: '.$firstname.'
				'.'Last Name: '.$lastname.'
				'.'Storename: '.$storename.'
				'.'Email: '.$email.'
				'.'Phone Number: '.$phonenumber;
				$headers = 'From: info@javytech.co.ke' .'
				'.
				'Reply-To: info@javytech.co.ke' .'
				'.
				'X-Mailer: PHP/' . phpversion();

				//mail($to, $subject, $message, $headers);


				require("email-sendgrid/sendgrid-php.php"); 
				// If not using Composer, uncomment the above line

				$email_sendgrid = new \SendGrid\Mail\Mail(); 
				$email_sendgrid->setFrom("info@javytech.co.ke", "Javy Technologies");
				$email_sendgrid->setSubject($subject);
				$email_sendgrid->addTo($to, 'Javis');
				$email_sendgrid->addContent("text/plain", $message );
				//$email_sendgrid->addContent( "text", "<strong>and easy to do anywhere, even with PHP</strong>");
				$sendgrid = new \SendGrid('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');
				try {
				    $response = $sendgrid->send($email_sendgrid);
				     //print $response->statusCode() . "\n";
				     //print_r($response->headers());
				     //print $response->body() . "\n";
				} catch (Exception $e) {
				    //echo 'Caught exception: ',  $e->getMessage(), "\n";
				}


				
				header('location: dashboard.php');	
					
			} else{
				
				$errors[] = "Incorrect Verification Code";
			} // /else
		


		
	} // /else not empty username // password
	
} // /if $_POST

?>

<!DOCTYPE html>
<html>
<head>
	<title>Javy Store| Verify Phone Number</title>

	<!--favicon-->
	<link rel="icon" href="images-front/icon.png" />

<!--make it responsive-->
   <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- bootstrap -->
	<link rel="stylesheet" href="assests/bootstrap/css/bootstrap.min.css">
	<!-- bootstrap theme-->
	<link rel="stylesheet" href="assests/bootstrap/css/bootstrap-theme.min.css">
	<!-- font awesome -->
	<link rel="stylesheet" href="assests/font-awesome/css/font-awesome.min.css">

  <!-- custom css -->
  <link rel="stylesheet" href="custom/css/custom.css">	

  <!-- jquery -->
	<script src="assests/jquery/jquery.min.js"></script>
  <!-- jquery ui -->  
  <link rel="stylesheet" href="assests/jquery-ui/jquery-ui.min.css">
  <script src="assests/jquery-ui/jquery-ui.min.js"></script>

  <!-- bootstrap js -->
	<script src="assests/bootstrap/js/bootstrap.min.js"></script>

	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-68172934-5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-68172934-5');
</script>

<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '703528689985924');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=703528689985924&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->


</head>
<body>
	<div class="container">
<div class="col-md-5 col-md-offset-4" style="margin-top: 40px">

<?php
			//display image or back to store link depending on whether supplier is accessing system from the promoter's website or from supply.javy.co.ke
			if ($host=='promote.javy.co.ke'){
				echo '<a href="http://www.javy.co.ke/index.php"><img src="assests/images/javy-promote-learn-more.jpg"></a>';
			}
			else{
				echo '<h1><a href=http://'.$host.' style="display: inline-block;color: #000;text-decoration: none;position: relative;font-weight: 700;" >Back to <span style="font-size: 1.7em;color: #F44336;vertical-align: sub;margin-right: 3px;">'.strtoupper(substr($storename, 0, 1)).'</span>'.substr($storename,1,mb_strlen($storename)-1).'</a></h1>';


			} 

			?>
</div>
		<div class="row vertical">
			<div class="col-md-5 col-md-offset-4">
				<div class="panel panel-info">
					<div class="panel-heading">
						<h3 class="panel-title">Promoter : Verify Phone Number</h3>
					</div>
					<div class="panel-body">

						<div class="messages">
							<?php if($errors) {
								foreach ($errors as $key => $value) {
									echo '<div class="alert alert-warning" role="alert">
									<i class="glyphicon glyphicon-exclamation-sign"></i>
									'.$value.'</div>';										
									}
								} ?>
						</div>
						<?php

						if (isset($_GET['page'])){
							$requested_page=$_GET['page'];
						}else{
							$requested_page='dashboard';
						}

												?>

						<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'].'?page='.$requested_page ?>" method="post" id="loginForm">
							<fieldset>
									<h5 style="text-align: center;">Verification code sent to <?php echo $phone ?></h5>
								
							  <div class="form-group">
									<label for="code" class="col-sm-5 control-label">Enter Code</label>
									<div class="col-sm-7">
									  <input type="text" class="form-control" id="code" name="code" placeholder="Code" autocomplete="off" value="<?php echo isset($_POST['']) ? $_POST['code'] : '' ?>" />
									</div>
								</div>
								
															
								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-8">
									  <button type="submit" class="btn btn-default"> <i class="glyphicon glyphicon-log-in"></i> Verify</button>
									</div>
								</div>

								<!--<a href="ajax-sendcode.php"><h5 style="margin-top: 30px;" class="col-sm-offset-4 col-sm-8">Resend</h5></a>-->
								<a href="logout.php"><h5 style="margin-top: 30px;" class="col-sm-offset-4 col-sm-8">Log out </h5></a>

								

							</fieldset>
						</form>
					</div>
					<!-- panel-body -->
				</div>
				<!-- /panel -->
			</div>
			<!-- /col-md-4 -->
		</div>
		<!-- /row -->
	</div>
	<!-- container -->	
</body>
</html>
	<?php
    include 'closeconnection.php';
    ?>








	