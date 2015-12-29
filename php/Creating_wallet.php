<?php
include "config.php";
class Wallet{
	private $con;
	public function __construct($con) {
		$this->con = $con;
	}
	public function Create_wallet($username, $password, $cpassword, $email) {
		try {
			if(!ctype_alnum($username)) {
				echo "Username Must be in alphabet and numbers";
			} else {
				$chk = $this->con->prepare("SELECT Username FROM users WHERE Username=:username");
				$chk->bindParam(":username", $username);
				$chk->execute();
				if($chk->rowCount() == 0) {
					if(strlen($password) >= 6) {
						$password = password_hash($password, PASSWORD_DEFAULT);
						$cpassword = password_verify($cpassword, $password);
						if($cpassword == true) {
							if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
								$chk_email = $this->con->prepare("SELECT Email FROM users WHERE Email=:email");
								$chk_email->bindParam(":email", $email);
								$chk_email->execute();
							 if($chk_email->rowCount() == 0) {
							// for captcha
							//if (!class_exists('KeyCAPTCHA_CLASS')) {
							//include('../php/keycaptcha.php');
							//}
							//$kc_o = new KeyCAPTCHA_CLASS();
							//if ($kc_o->check_result($_POST['capcode'])) {
	
							//} else {
							
							//}
							$code = md5(rand(0, 4687));
							$insert_data = $this->con->prepare("INSERT INTO users (Username,Password,Email,Code) VALUES (:Username,:Password,:Email,:Code)");
							$submit_data = array(':Username' => $username,':Password' => $password, ':Email'=> $email,':Code' => $code);
							$insert_data->execute($submit_data);
							// Important!
							if($insert_data) {
								$id = $this->con->lastInsertId();
								$subject = "$username Orbitcoin Wallet Email Confirmation";
								$message = "
								this Email has been sent by Orbitcoin web-wallet for email confirmation.Click on the following link to confirm email.
								http://localhost/index.php?hash=$code&id=$id
								";
								$mail = mail($email, $subject, $message);
								if($mail) {
									echo "Email Confirmation has been send! Please check";
								}
							}
							
							} else {
								echo "Email Already taken!";
							}
							} else {
								echo "Invalid Email Address!";
							}
						} else {
							echo "Password is not match!";
						}
					} else {
						echo "Enter Minimum 6 Characters In Password!";
					}
				} else {
					echo "Username already taken!";
				}
			}
		}
		
		catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
}
if(!empty($_POST["Username"]) && !empty($_POST["Email"]) && !empty($_POST["Password"]) && !empty($_POST["Cpassword"])) {
	$username = $_POST["Username"];
	$email = $_POST["Email"];
	$password = $_POST["Password"];
	$cpassword = $_POST["Cpassword"];
	$wallet = new Wallet($con, $coin);
	echo $wallet->Create_wallet($username, $password, $cpassword, $email);
	
} elseif(empty($_POST["Username"])) {
	echo "Please Enter Username!";
} elseif(empty($_POST["Password"])) {
	echo "Please Enter Password!";
} elseif(empty($_POST["Cpassword"])) {
	echo "Please Enter Confirm Password!";
} elseif(empty($_POST["Email"])) {
	echo "Please Enter Email!";
}