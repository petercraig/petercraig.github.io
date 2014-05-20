<?php $name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];
$formcontent="The contact form on streamboosters.com has recieved a submission, you can view the details below! \n \n Name: $name \n Email: $email \n Message: $message";
$recipient = "upitpromo@gmail.com";
$subject = "New Contact Form Submission (streamboosters.com)";
$mailheader = "From: $email \r\n";
mail($recipient, $subject, $formcontent, $mailheader) or die("Error!");
$url = 'success.html';
echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL='.$url.'">';  
?>
 