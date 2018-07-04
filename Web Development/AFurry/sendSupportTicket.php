<?php
    $headers = "";
    $headers .= "MIME-Version: 1.0\r\n"; 
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .="From: webmaster@example.com" . "\r\n" ."CC: somebodyelse@example.com";

$to = "wabuilderman@gmail.com";
$subject = "BasicTest";
$message = "Hello. This is a test";
$executed = mail($to, $subject, $message, $headers);
echo "Mail has returned: ";
echo $executed ? 'true' : 'false';
?>