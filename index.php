<?php
include('./includes/config.inc.php');

include('./includes/users.class.php');
$user = new Users();
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
        if(isset($_POST['doLogin']))
        {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user->Login($username, $password);
        }
    ?>
    <form action="index.php" class="login-form" method="POST">
        <h1>LOGIN</h1>
    <div class="txtb">
        <input type="text" placeholder="username" id="username" name="username" value="<?php echo $username; ?>"/>
    </div>
    <div class="txtb">
    <input type="password" placeholder="password" id="password" name="password"/>
    </div>
    <button class="logbtn" type="submit" id="doLogin" name="doLogin">LOGIN</button>
    <div class="bottom-text">
        Don't have account? <a href="register.php">REGISTER</a>
    </div>
    </form>
    </body>
</html>