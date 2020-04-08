<?php
include('./includes/config.inc.php');
include('./includes/users.class.php');
?>
<!--
    All right reseved to Dark7oveRR
    do not removed this copy right because Dark7oveRR code everything it's will to be risky to remove it
    i hope to enjoy on my code :)
 -->


<!DOCTYPE html>
<html>
    <head>
        <title>Coder: Dark7oveRR</title>
        <link rel="stylesheet" href="/assets/css/main.css">
    </head>
    <body>
    <?php
$username = "";
if(isset($_POST['doRegister']))
{
    $username = $_POST['username'];
    $password1 = $_POST['password1'];
    $password2= $_POST['password2'];
    $email = $_POST['email'];

    $user = new Users();
    $user->Register($username, $password1, $password2, $email);
}
?>
    <form action="register.php" class="register-form" method="POST">
        <h1>REGISTER</h1>
    <div class="txtb">
    <input type="text" placeholder="Username" name="username" id="username" value="<?php echo $username; ?>">
    </div>
    <div class="txtb">
    <input type="email" placeholder="E-Mail" name="email" id="email">
    </div>
    <div class="txtb">
    <input type="password" placeholder="Password" name="password1" id="password1">
    </div>
    <div class="txtb">
    <input type="password" placeholder="Confirm Password" name="password2" id="password2">
    </div>
    <center>
    <button class="register" type="submit" id="doRegister" name="doRegister">REGISTER</button>
</center>
    <div class="bottom-text">
        Already have account? <a href="index.php">LOGIN</a>
    </div>
    </form>
    </body>
</html>