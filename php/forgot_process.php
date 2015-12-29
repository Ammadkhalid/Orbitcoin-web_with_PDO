<?php
include "config.php";
try {
    if(!empty($_POST["Email"]) && !empty($_POST["Username"])) {
        $email = $_POST["Email"];
        $username = $_POST["Username"];
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $chk = $con->prepare("SELECT Email FROM users WHERE Email=:email");
        $chk->execute(array(":email" => $email));
            if($chk->rowCount() == 1) {
                $chk_user = $con->prepare("SELECT Username FROM users WHERE Email=:email and Username=:user");
                $chk_user->execute(array(":email" => $email,
                    ":user" => $username));
                if($chk_user->rowCount() == 1) {
                    $yes = 'Yes';
                    $chk_email_confirm = $con->prepare("SELECT ID FROM users WHERE Email=:email and Username=:user AND Email_confirm=:yes");
                    $chk_email_confirm->execute(array(":email" => $email ,":yes" => $yes, ":user" => $username));
                    if($chk_email_confirm->rowCount() == 1) {
                        $code = sha1(rand(0, 999999)).md5(rand(0, 997554));
                        $code = str_shuffle($code);
                        $update_code = $con->prepare("UPDATE users SET Code=:code WHERE Username=:username");
                        $update_code->execute(array(":username" => $username,
                            ":code" => $code));
                        //Important!
                        $subject = '$username Your Password Recovery Email!';
                        $message = 'http://127.0.0.1/reset.php?code='.$code;
                        $mail = mail($email, $subject, $message);
                        if($mail) {
                            Echo "Email Send Please Check!";
                        }
                    } else {
                        echo "Please Confirm Your Email";
                    }
                } else {
                    echo "Username With Email is not found!";
                }
            } else {
                echo "Email Not Found!";
            }
        } else {
            echo "Invalid Email Address";
        }
    } elseif(empty($_POST["Email"])) {
        echo "Please Enter Email";
    } elseif(empty($_POST["Username"])) {
        echo "Please Enter Username";
    }
}

catch(PDOException $message) {
    echo $message->getMessage();
}
?>