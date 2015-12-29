<?php
include "config.php";
Class Wallet {
    private $con;
    public function __construct($con) {
        $this->con = $con;
    }

    public function Wallet_login($username, $password) {
        try {
            $chk_user = $this->con->prepare("SELECT Password,Email_confirm FROM users WHERE Username=:username");
            $usrn = array(":username" => $username);
            $chk_user->execute($usrn);
            if($chk_user->rowCount() == 1) {
                $chk_login = $chk_user->fetch();
                if(password_verify($password, $chk_login["Password"]) == true) {
                    if($chk_login["Email_confirm"] == "Yes") {
                        $session_id = md5(rand(0, 129724)).sha1(rand(0, 444545));
                        $login_with_unique_session = $this->con->prepare("UPDATE users SET Session_id=:session WHERE Username=:username");
                        $session_data = array(":username" => $username,
                            ":session" => $session_id);
                        $chk = $login_with_unique_session->execute($session_data);
                        if($chk) {
                            session_start();
                            $session = $_SESSION["Usr_d"] = $session_id;

                            if($session) {
                                echo "Login Successful!";
                            }

                            // Important !!!
                            session_save_path("/");

                            if($_SERVER["PORT"] == "443") {
                                $http = true;
                            } else {
                                $http = false;
                            }
                            // Important !!!
                            session_set_cookie_params(0, "/", $_SERVER["SERVER_NAME"], $http, TRUE);
                        }
                    } else {
                        echo "Please Confirm Your Email!";
                    }
                } else {
                    echo "Incorrect Password!";
                }
            } else {
                echo "Username Not Found!";
            }
        }

        catch(PDOException $e) {
        echo $e->getMessage();
        }

    }

}
if(!empty($_POST["Username"]) && !empty($_POST["Password"])) {
    $wallet = new Wallet($con);
    $username = $_POST["Username"];
    $password = $_POST["Password"];
    echo $wallet->Wallet_login($username, $password);
} elseif(empty($_POST["Username"])) {
    echo "Please Enter Username!";
} elseif(empty($_POST["Password"])) {
    echo "Please Enter Password!";
}