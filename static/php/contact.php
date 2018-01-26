<?php

//File 'access.ini' contains private information and is only accessible through server
$ini = parse_ini_file('/var/www/scripts/access.ini');
$to = $ini['emailContactFormEndpoint'];

//Get input from contact form
$subject = $_POST["name"];
$msgContent = strip_tags($_POST["message"], '<br>');
$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
$msg = "From: " . $email . "\r\nMessage: " . $msgContent;

//Check reCaptcha response
$privateKey = $ini['reCaptchaKey'];
$captchaResponse = $_POST['g-recaptcha-response'];
$captchaCheckResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$privateKey&response=$captchaResponse");
$decodeCaptchaResponse = json_decode($captchaCheckResponse);
$isValidCaptcha = $decodeCaptchaResponse -> success;

//Check input from contact form
if (!$isValidCaptcha) {
    echo "Please fill out reCaptcha correctly.";
    return;
} else if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    if (!preg_match("/^[a-zA-Z _]+$/", $subject)) {
        echo "Not a valid name. Please remove numbers and/or special characters.";
        return;
    }

  //Send email
  $mailStatus = mail($to, $subject, $msg);

  if(!$mailStatus) {
        echo "We apologize, there seems to be an issue.";
  } else {
        echo "Your message was successfully sent!";
    }

  } else {
        echo "Not a valid email address. Please try again.";
    }
?>