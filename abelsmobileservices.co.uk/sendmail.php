<?php 

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];
$number = $_POST['number'];

$formcontent="Name: $name \n Email: $email \n Number: $number \n Message: $message";
$recipient = "daveabel2004@yahoo.com";
$subject = "Contact Form Submission (abelsmobileservices.co.uk)";

$mailheader = "From: $email \r\n";

mail($recipient, $subject, $formcontent, $mailheader) or die("Error!");

$url = 'contactdone.html';

echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL='.$url.'">';  

?>
 