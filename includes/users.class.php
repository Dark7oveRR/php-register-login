<?php

class Users {

    public function Login($username, $password) {
        global $conn;
        global $salt;
        global $peper;

        // Check if entered username & password not blank and min 4 chars.
        if(empty($username) || strlen($username) <= 3)
        {
            ?><script>toastr["success"]("msg", "title")</script><?php
            print "<font color='red'> Please enter a valid username or email.";
        }
        elseif(empty($password) || strlen($password) <= 3)
        {
            print "<font color='red'> Please enter a valid password.";
        } else {

            $safe_username = mysqli_real_escape_string($conn, $username);  // Securing of MysqlI Injection
            $safe_password = mysqli_real_escape_string($conn, $password);  // Securing of MysqlI Injection
            $safe_password = md5($salt . $safe_password . $peper);         // Hashing password with the salt and peper.

            // Check if username existing
            $query_check_username = $conn->query("SELECT user_id, user_password, user_banned, user_active FROM account_users WHERE user_username = '".$safe_username."' OR user_email = '".$safe_username."' LIMIT 1");
            if(!$query_check_username || $query_check_username->num_rows == 0)
            {
                print "<font color='red'> Sorry this username or email is not found.";
            } else {
                $userdata = $query_check_username->fetch_assoc();

                // Check password
                if($safe_password != $userdata['user_password'])
                {
                    print "<font color='red'> Sorry this password is incorrect.";
                }
                // Check account ban
                elseif(0 != $userdata['user_banned'])
                {
                    print "<font color='red'> Sorry this account is banned.";
                }
                // Check IP ban
                elseif($this->isIPBanned())
                {
                    print "<font color='red'> Sorry this IP address is banned.";
                }
                // Check account active
                elseif(1 != $userdata['user_active'])
                {
                    print "<font color='red'> Sorry this account is not activated.";
                }
                else {
                    // Ready to login!
                    $auth = $this->RandomString(50);
                    $token = $this->RandomString(50);
                    $UIP = $this->UserIP();
                    $UID = $userdata['user_id'];

                    // Set session in database
                    $query_set_session = $conn->query("UPDATE account_sessions SET session_auth = '".$auth."', session_token = '".$token."', session_ip = '".$UIP."' WHERE session_user_id = '".$UID."' ");
                    if($query_set_session)
                    {
                        // Set sessions in userbrowser
                        $_SESSION['U_AUTH'] = $auth;
                        $_SESSION['U_TOKEN'] = $token;

                        // Redirect to dashboard.
                        header("Location: dashboard.php");
                        die();

                    } else {
                        print "<font color='red'> Sorry the session could not be created, Try again.";
                    }
                }
            }
        }
    }

    public function Register($username, $password1, $password2, $email) {
        global $conn;
        global $salt;
        global $peper;
        global $regrank;
        global $regactive;

        // Check if entered username & password not blank and min 4 chars.
        if(empty($username) || strlen($username) <= 3)
        {
            print "<font color='red'> Please enter a valid username.";
        }
        elseif(empty($password1) || strlen($password1) <= 3)
        {
            print "<font color='red'> Please enter a valid password.";
        }
        elseif(preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $password1) === 0)
        {
            print "<font color='red'> Password must be at least 8 characters and must contain at least one lower case letter, one upper case letter and one digit";
        }
        elseif($password1 != $password2)
        {
            print "<font color='red'> Please make sure the entered passwords are the same.";
        }
        else if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            print "<font color='red'> Please enter a valid email address.";
        }
        else {
            $safe_username = mysqli_real_escape_string($conn, $username);  // Securing of MysqlI Injection
            $safe_password = mysqli_real_escape_string($conn, $password1);  // Securing of MysqlI Injection
            $safe_password = md5($salt . $password1 . $peper);
            $safe_email = mysqli_real_escape_string($conn, $email);  // Securing of MysqlI Injection

            // Check if username existing.
            $query_check_username = $conn->query("SELECT user_id FROM account_users WHERE user_username = '".$safe_username."' LIMIT 1");
            if(!$query_check_username || $query_check_username->num_rows > 0)
            {
                print "<font color='red'> Please choose another username, This one is already taken.";
            } else {
                // Check if email existing..
                $query_check_email = $conn->query("SELECT user_id FROM account_users WHERE user_email = '".$safe_email."' LIMIT 1");
                if(!$query_check_email || $query_check_email->num_rows > 0)
                {
                    print "<font color='red'> Please choose another email address, This one is already taken.";
                }
                // Check IP ban
                elseif($this->isIPBanned())
                {
                    print "<font color='red'> Sorry this IP address is banned.";
                }
                else
                {
                    // Insert account
                    $registeringIP = $this->UserIP();
                    $query_insert_account = $conn->query("INSERT INTO account_users (user_username, user_password, user_email, user_rank, user_active, register_ip) VALUES ('".$safe_username."', '".$safe_password."', '".$safe_email."', '".$regrank."', '".$regactive."', '".$registeringIP."')");
                    if($query_insert_account)
                    {
                        $registeringUserID = $conn->insert_id;

                        // insert session
                        $query_insert_session = $conn->query("INSERT INTO account_sessions (session_user_id) VALUES ('".$registeringUserID."')");

                        print "<font color='green'> Account created succesfully.";
                    } else {
                        print "<font color='red'> Sorry, Something went wrong please try again.";
                    }
                }
            }
        }


    }

    public function LoginCheck()
    {
        global $conn;
        if(isset($_SESSION['U_AUTH']) && isset($_SESSION['U_TOKEN']))
        {
            $auth = mysqli_real_escape_String($conn, $_SESSION['U_AUTH']);
            $token = mysqli_real_escape_String($conn, $_SESSION['U_TOKEN']);
            $UIP = $this->UserIP();

            if($this->isIPBanned())
            {
              header("Location: index.php?logout=ipbanned");
              die();
            }
            elseif(strlen($auth) == 50 && strlen($token) == 50)
            {
                $query_check_session = $conn->query("SELECT session_user_id FROM account_sessions WHERE session_auth = '".$auth."' AND session_token = '".$token."' AND session_ip = '".$UIP."' LIMIT 1 ");
                if($query_check_session && $query_check_session->num_rows == 1)
                {
                    $sessiondata = $query_check_session->fetch_assoc();
                    return $sessiondata['session_user_id'];
                } else {
                    header("Location: index.php?logout=sessioninvalid");
                    die();
                }
            } else {
                header("Location: index.php?logout=sessioninvalid");
                die();
            }
        } else {
            header("Location: index.php?logout=sessionexpired");
            die();
        }
    }


    public function isIPBanned()
    {
        global $conn;
        $query_find_ipban = $conn->query("SELECT ban_id FROM system_ban WHERE ban_ip = '".$this->UserIP()."' LIMIT 1");
        if($query_find_ipban && $query_find_ipban->num_rows > 0)
        {
            return true;
        } else {
            return false;
        }
    }

    public function UserIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function RandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

?>
