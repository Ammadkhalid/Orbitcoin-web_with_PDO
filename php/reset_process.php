<?php
require_once "config.php";
class Wallet {
    private $con;

    function __construct($con) {
        $this->con = $con;
    }

    public function Reset_password($code, $password, $confirm_password) {
        $chk_code = $this->con->prepare("SELECT Code FROM users WHERE Code=:code");
        $chk_code->execute(array(":code" => $code));
        if($chk_code->rowCount() == 1) {
            if(strlen($password) >= 6) {
                $sel_user = $this->con->prepare("SELECT Username FROM users WHERE Code=:code");
                $sel_user->execute(array(":code" => $code));
                $username = $sel_user->fetch(PDO::FETCH_ASSOC);
                $username = $username["Username"];
                if($confirm_password == $password) {
                    $update = $this->con->prepare("UPDATE users SET Password=:password WHERE Code=:code");
                    $password = password_hash($password, PASSWORD_DEFAULT);
                    $update_pass = $update->execute(array(":password" => $password, ":code" => $code));
                    $chk = $this->con->prepare("UPDATE users SET Code=:code WHERE Username=:User");
                    $chk_if = $chk->execute(array(":code" => Null,":User" => $username));
                    if($chk_if) {
                        echo "Thanks password has been reset please login now";
                    } else {
                        echo "ERROR! Reload Page!";
                    }
                } else {
                    echo "Password is not match!";
                }
            } else {
                echo "Enter Minimum 6 Characters In Password!";
            }
        } else {
            echo "Invalid Password Recovery link!";
        }
    }
}
$wallet = new Wallet($con);

if(!empty($_POST["Password"]) && !empty($_POST["Cpassword"]) && !empty($_POST["Code"])) {
    $code = $_POST["Code"];
    $password = $_POST["Password"];
    $confirm_password = $_POST["Cpassword"];
    echo $wallet->Reset_password($code, $password, $confirm_password);
} elseif(empty($_POST["Password"])) {
    echo "Please Enter Password";
} elseif(empty($_POST["Cpassword"])) {
    echo "Please Enter Confirm Password";
} elseif(empty($_POST["Code"])) {
    echo "ERROR! Reload Page!";
}