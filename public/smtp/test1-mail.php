<?php
// if(isset($_POST['username']) && isset($_POST['password'])){
// Report all PHP errors (see changelog)
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
// Report all PHP errors
error_reporting(-1);

// require_once('PHPMailer/class-phpmailer.php');
require 'PHPMailer/PHPMailerAutoload.php';

	 $mail = new PHPMailer();
	 $mail->IsHTML(true);
	 $mail->IsSMTP();
	 $mail->SMTPAuth = true; // enable SMTP authentication
	 $mail->SMTPSecure = "ssl"; // sets the prefix to the serviers
	 $mail->Host = "smtp.iitm.ac.in"; // sets GMAIL as the SMTP server
	 $mail->Port = 465; // set the SMTP port for the GMAIL server
	 $mail->Username = "libsmtp"; // EMAIL username
	 $mail->Password = "Chennai36"; // EMAIL password
	 $mail->From = "eservices@iitm.ac.in"; // "name@yourdomain.com";
	 $mail->FromName = "Eservices, IIT Madras";  // set from Name
	 $mail->Subject ="Testing mail delivery";
	 $mail->Body = "Dear "."name".",<br><br>
	        This is for testing.<br>
	        Name of the applicant : <b>"."appname"."</b><br><br> Click the link below
	         to submit your form.<br>
	<a href=\"http://"."link"."\">"."link"."</a>
	        <br>
	        The form would take less than 5 minutes to complete.<br>
	        <b>If clicking the link above does not take you to the referee form,
	        you can copy and paste the link in a web browser to access the form.<br>
	The deadline for receiving your inputs is 18 March 2016.</b>
	        <br><br> Thanks in anticipation,<br>Admission office,<br>MBA 2016-18 Admission,<br>Department of Management Studies,<br>IIT Madras.";
	$email = "librarian@iitm.ac.in";
	$mail->AddAddress($email, ""); // to Address
	$mail->set('X-Priority', '1'); //Priority 1 = High, 3 = Normal, 5 = low
	if($mail->Send())
	{
	echo " Sent to  <br>".$email;
	}
	else
	{
	echo " not Sent <br>";
	}


?>
