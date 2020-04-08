<?php
include('./includes/config.inc.php');

include('./includes/users.class.php');
$user = new Users();
$userID = $user->LoginCheck();
?>
<!--
    All right reseved to Dark7oveRR
    do not removed this copy right because Dark7oveRR code everything it's will to be risky to remove it
    i hope to enjoy on my code :)
 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dark7Over Secure Login</title>
</head>
<body>
<center>
    <h1>You have successefully login!</h1>
    <h2> YOU CAN DESIGN THIS PAGE! </h2>
    <h3>ALL RIGHT RESERVED TO Dark7Over</h3>
</center>
</body>
</html>
