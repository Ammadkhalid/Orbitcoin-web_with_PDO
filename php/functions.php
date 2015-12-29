<?php
include "config.php";
require_once "jsonRPCClient.php";
session_start();
if(isset($_SESSION["Usr_d"])) {
	$session = $_SESSION["Usr_d"];
	$chk_session = $con->prepare("SELECT Session_id FROM users WHERE Session_id=:session");
	$session_id = array(":session" => $session);
	$chk_session->execute($session_id);
	if($chk_session->rowCount() == 1) {
	class Wallet {
		private $con;
		private $coin;

		function __construct($con, $coin) {
			$this->coin = $coin;
			$this->con = $con;
		}

		public function Username($session) {
			$get_usr = $this->con->prepare("SELECT Username FROM users WHERE Session_id=:id");
			$id = array(":id" => $session);
			$get_usr->execute($id);
			$user = $get_usr->fetch(PDO::FETCH_ASSOC);
			echo $user["Username"];
		}

		public function Is_validate_address($orb_address_for_vali) {
			$chk = $this->coin->validateaddress($orb_address_for_vali);
			if($chk["isvalid"] == 1) {
				echo "Ok!";
			}
		}

		public function chk_user_pass_during_send_payment($user_input_pass_in_send_payment, $session) {
			$si_chk_pass = $this->con->prepare("SELECT Password FROM users WHERE Session_id=:id");
			$data = array(":id" => $session);
			$si_chk_pass->execute($data);
			if($si_chk_pass->rowCount() == 1) {
				$password = $si_chk_pass->fetch(PDO::FETCH_ASSOC);
				$password = $password["Password"];
				$verify = password_verify($user_input_pass_in_send_payment ,$password);
				if($verify == true) {
					echo "Ok!";
				}
			}
		}

		public function chk_amount($chk_amount, $session) {
			try {
			if (is_numeric($chk_amount)) {
				$payment = doubleval($chk_amount);
				$get_user = $this->con->prepare("SELECT Username FROM users WHERE Session_id=:id");
				$id = array(":id" => $session);
				$get_user->execute($id);
				$username = $get_user->fetch(PDO::FETCH_ASSOC);
				$username = $username["Username"];
				$address = $this->coin->getaddressesbyaccount($username);
				$balance = 0;
				$list_accounts = $this->coin->listaddressgroupings();
				$count = $list_accounts;
				for ($i = count($count) - 1; $i >= 0; $i--) {
					$llops = $count[$i];
					for ($o = count($llops) - 1; $o >= 0; $o--) {
						for ($adr = count($address) - 1; $adr >= 0; $adr--) {
							if ($count[$i][$o][0] == $address[$adr]) {
								$balance += $count[$i][$o][1];
							}
						}
					}
				}
				if ($balance == $payment) {
					echo "No fee to spend";
				} elseif ($balance - 0.001 >= $payment) {
					echo "Ok!";
				}
			}
			}

			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}

		public function send_payment($orbitcoin_address, $amnount, $password_orb, $session) {
			try {
				$chk_address = $this->coin->validateaddress($orbitcoin_address);
				if($chk_address["isvalid"] == true) {
					if(is_numeric($amnount)) {
						$payment = floatval($amnount);
						$pdo_sel_data = $this->con->prepare("SELECT Username,Password FROM users WHERE Session_id=:id");
						$sel_data = array(":id" => $session);
						$pdo_sel_data->execute($sel_data);
						$user = $pdo_sel_data->fetch(PDO::FETCH_ASSOC);
						$address = $this->coin->getaddressesbyaccount($user["Username"]);
						$balance = 0;
						$list_accounts = $this->coin->listaddressgroupings();
						$count = $list_accounts;
						for($i=count($count)-1;$i>=0;$i--) {
							$llops = $count[$i];
							for($o=count($llops)-1;$o>=0;$o--) {
								for($adr=count($address)-1;$adr>=0;$adr--) {
									if($count[$i][$o][0] == $address[$adr]) {
										$balance += $count[$i][$o][1];
									}
								}
							}
						}
						if($balance == $payment) {
							echo "No fee to spend";
						} elseif($balance >= $payment + 0.001 ) {
							$settxfee = $this->coin->settxfee(0.001);
							$verify_password = password_verify($password_orb, $user["Password"]);
							if($verify_password == true) {
								$send = $this->coin->sendtoaddress($orbitcoin_address, doubleval($payment));
								if($send) {
									echo "Send! Tx id:"."<br />";
									echo "<a style='color:red;' href='atlas.phoenixcoin.org:1080/tx/".$send."' target=__blank>".$send.'</a>';
									$time = time();
									$insert = $this->con->prepare("INSERT INTO send_transations (Account,To_address,Amount_send,Date_send,Tx_id) VALUES (:user,:pay,:amout,:time,:tx)");
									$values = array(":user" =>$user["Username"],
										":pay" => $orbitcoin_address,
										":amout" => $payment,
										":time" => $time,
										":tx" => $send);
									$insert->execute($values);
								}
							} else {
								echo "Incorrect Password!";
							}
						} else {
							echo "Insufficient Balance!";
						}
					} else {
						echo "Invalid Amount";
					}
				} else {
					echo "Invalid Orbitcoin Address";
				}
			}

			catch(PDOException $e) {
				echo $e->getMessage();
			}

		}

		public function generate_new($session) {
			$sel_usr = $this->con->prepare("SELECT Username FROM users WHERE Session_id=:id");
			$ids = array(":id" => $session);
			$sel_usr->execute($ids);
			$get_usr = $sel_usr->fetch(PDO::FETCH_ASSOC);
			$new_adr = $this->coin->getnewaddress($get_usr["Username"]);
			if($new_adr) {
				?>
				<tr>
					<td>
						<?php echo $new_adr; ?>
					</td>
					<td>
					</td>
					<td>
						<img width="30px" height="30px" src="http://api.qrserver.com/v1/create-qr-code/?color=FFFFFF&amp;bgcolor=497BE8&amp;data=<?php echo $new_adr; ?>&amp;qzone=1&amp;margin=0&amp;size=150x150&amp;ecc=L" alt="qr code" />
					</td>
				</tr>
				<?php
			}
		}

		public function Change_Account_setting($session, $email, $old_pass, $password) {
			try {
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					echo "Incorrect Email!";
				} else {
					if($password == $old_pass) {
						echo "Please Enter Different New Password!";
					} else {
						$sel_old_pass = $this->con->prepare("SELECT Password FROM users WHERE Session_id=:id");
						$id = array(":id" => $session);
						$sel_old_pass->execute($id);
						$get = $sel_old_pass->fetch(PDO::FETCH_ASSOC);
						$verify = password_verify($old_pass, $get["Password"]);
						if($verify == true) {
							$update_pass = $this->con->prepare("UPDATE users SET Password=:pass WHERE Session_id=:id");
							$up_pass = password_hash($password, PASSWORD_DEFAULT);
							$insert_prepare = array(":pass" => $up_pass,
								":id" => $session);
							$update_pass->execute($insert_prepare);
							if($update_pass) {
								$update_email = $this->con->prepare("UPDATE users SET Email=:mail WHERE Session_id=:id");
								$up_mails = array(":mail" => $email,
									":id" => $session);
								$update_email->execute($up_mails);
								if($update_email) {
									echo "Email And Password Change Successfully!";
									echo '<meta http-equiv="refresh" content="3" >';
								}
							}
						} else {
							echo "Incorrect Account Password!";
						}
					}
				}
			}

			catch(PDOException $e) {
				echo $e->getMessage();
			}
		}

		public function Change_Account_Password($session, $old_pass, $password) {
			try {
				$sel_old = $this->con->prepare("SELECT Password FROM users WHERE Session_id=:id");
				$id = array(":id" => $session);
				$sel_old->execute($id);
				$pass = $sel_old->fetch(PDO::FETCH_ASSOC);
				$verify = password_verify($old_pass, $pass["Password"]);
				if($verify == true) {
					if($old_pass != $password) {
					$password = password_hash($password, PASSWORD_DEFAULT);
					$update_pass = $this->con->prepare("UPDATE users SET Password=:pass WHERE Session_id=:id");
					$up_pass = array(":pass" => $password,
					":id" => $session);
					$chk_update = $update_pass->execute($up_pass);
					if($chk_update) {
						echo "Password Successfully Changed!";
					}

					} else {
						echo "Please Enter Different New Password!";
					}
				} else {
					echo "Incorrect Password!";
				}
			}

			catch(PDOEXception $e) {
				echo $e->getMessage();
			}
		}

		public function Change_Account_Email($session, $email, $pass, $session) {
			$sel_pass = $this->con->prepare("SELECT Password FROM users WHERE Session_id=:id");
			$id = array(":id" => $session);
			$sel_pass->execute($id);
			$fetch_usr = $sel_pass->fetch(PDO::FETCH_ASSOC);
			$chk = password_verify($pass, $fetch_usr["Password"]);
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				echo "Incorrect Email!";
			} else {
				$chk_email = $this->con->prepare("SELECT Email FROM users WHERE Email=:email");
				$bind_email = array(":email" => $email);
				$chk_email->execute($bind_email);
				if($chk_email->rowCount() != 1) {
					if($chk == true) {
						$update_email = $this->con->prepare("UPDATE users SET Email=:mail WHERE Session_id=:id");
						$email_bind = array(":mail" => $email,
							":id" => $session);
						$chk_email = $update_email->execute($email_bind);
						if($chk_email) {
							echo "Email has been Change Successfully!";
						}
					} else {
						echo "Incorrect Account Password!";
					}
				} else {
					echo "Email Already Taken!";
				}
			}
		}

		public function Dump_address($session, $dump_adr, $dump_pass) {
			$sel_session = $this->con->prepare("SELECT Username,Password FROM users WHERE Session_id=:id");
			$ses = array(":id" => $session);
			$sel_session->execute($ses);
			$get_user = $sel_session->fetch(PDO::FETCH_ASSOC);
			$verify_pass = password_verify($dump_pass, $get_user["Password"]);
			if($verify_pass == true) {
				$is_vid = $this->coin->validateaddress($dump_adr);
				if($is_vid["isvalid"] == 1) {
					$list_adrs = $this->coin->getaddressesbyaccount($get_user["Username"]);
					function chk($list_adrs, $dump_adr) {
						foreach($list_adrs as $index => $key) {
							if($list_adrs[$index] == $dump_adr) {
								return "Ok!";
							}
						}
					}

					if(chk($list_adrs, $dump_adr) == "Ok!") {
						$dmp = $this->coin->dumpprivkey($dump_adr);
						echo $dmp;
					} else {
						echo "ERROR! Reload page";
					}

				} else {
					echo "ERROR! Invalid Address!";
				}
			} else {
				echo "Incorrect Account Password!";
			}
		}

		public function Balance($session) {
			$find_username = $this->con->prepare("SELECT Username FROM users WHERE Session_id=:id");
			$usr = array(":id" => $session);
			$find_username->execute($usr);
			$get_user = $find_username->fetch(PDO::FETCH_ASSOC);
			$address = $this->coin->getaddressesbyaccount($get_user["Username"]);
			$balance = 0;
			$list_accounts = $this->coin->listaddressgroupings();
			$count = $list_accounts;
			for($i=count($count)-1;$i>=0;$i--) {
				$llops = $count[$i];
				for($o=count($llops)-1;$o>=0;$o--) {
					for($adr=count($address)-1;$adr>=0;$adr--) {
						if($count[$i][$o][0] == $address[$adr]) {
							$balance += $count[$i][$o][1];
						}
					}
				}
			}
			echo $balance;
		}

		public function total_receive($session) {
			$find_username = $this->con->prepare("SELECT Username FROM users WHERE Session_id=:id");
			$usr = array(":id" => $session);
			$find_username->execute($usr);
			$get_user = $find_username->fetch(PDO::FETCH_ASSOC);
			$total = $this->coin->getreceivedbyaccount($get_user["Username"]);
			echo $total;
		}

		public function receive_txs($session) {
			$find_username = $this->con->prepare("SELECT Username FROM users WHERE Session_id=:id");
			$usr = array(":id" => $session);
			$find_username->execute($usr);
			$get_user = $find_username->fetch(PDO::FETCH_ASSOC);
			$username = $get_user["Username"];
			$r_tx = $this->con->prepare("SELECT * FROM user_receive_transations WHERE Tx_username=:username ORDER BY Date DESC");
			$rx_data = array(":username" => $username);
			$r_tx->execute($rx_data);
if($r_tx->rowCount() > 0) {
?>
<table class="table" id="r_txs">
	<tr>
		<td style="color:white">
			From
		</td>
		<td style="color:white">
			Estimate Time / Date
		</td>
		<td style="color:white">
			Amount
		</td>
	</tr>
	<?php
	while($tx=$r_tx->fetch(PDO::FETCH_ASSOC)) {
		$shorting_adrs = $this->con->prepare("SELECT * FROM sender_address WHERE Username=:username AND next_tx_id=:next_tx_id LIMIT 1");
		$data = array(":username" => $username,
			":next_tx_id" => $tx["Tx_id"]);
		$shorting_adrs->execute($data);
		while($ars=$shorting_adrs->fetch(PDO::FETCH_ASSOC)) {
			?>
			<tr>
				<td >
					<?php
					if($ars["next_tx_id"] == $tx["Tx_id"]) {
						echo "<a style='text-decoration:none;color:black;' href='http://atlas.phoenixcoin.org:1080/tx/".$ars["next_tx_id"]."' target=__blank>".$ars["Sender"]."</a>"."<br />";
					}
					?>
				</td>
				<td >
					<?php
					$estimate_like = time() - $tx["Date"];
					$mins = round($estimate_like / 60);
					$hours = round($estimate_like / 3600);
					$days = round($estimate_like / 86400);
					$weeks = round($estimate_like / 604800);
					$months = round($estimate_like / 2600640);
					$years = round($estimate_like / 31207680);

					if($estimate_like <= 60) {
						echo "$estimate_like Secs ago";
					} elseif($mins <= 60) {
						echo "$mins mins ago";
					} elseif($hours <= 23) {
						echo "$hours hours ago ";
					} elseif($days == 1) {
						echo "$days Day ago";
					} elseif($days <= 6) {
						echo "$days Days";
					} elseif($weeks == 1) {
						echo "$weeks Week ago";
					} elseif($weeks <= 4.3) {
						echo "$weeks weeks ago";
					} elseif($months) {
						echo "$months month ago";
					} elseif($months) {
						echo "$months months ago";
					} elseif($years == 1) {
						echo "$years year ago";
					} else {
						echo "$years years ago";
					}

					?>
				</td>
				<td >
					<?php
					echo "+".$tx["Amount"];

					?>
				</td>
			</tr>
			<?php
		}
	}
	} else {
		?>
		<div id="r_txs" style="text-align:center;">
			No Receive transation found!
		</div>
		<?php
	}
	?>
</table> <?php
		}

		public function send_txs($session) {
			$find_username = $this->con->prepare("SELECT Username FROM users WHERE Session_id=:id");
			$usr = array(":id" => $session);
			$find_username->execute($usr);
			$get_user = $find_username->fetch(PDO::FETCH_ASSOC);
			$username = $get_user["Username"];
			$display_send = $this->con->prepare("SELECT * FROM send_transations WHERE Account=:user ORDER BY Date_send DESC");
			$s_tx_data = array(":user" => $username);
			$display_send->execute($s_tx_data);
if($display_send->rowCount($username) > 0) {
?>
<table class="table" id="s_tx">
	<tr>
		<td style="color:white">
			To
		</td>
		<td style="color:white">
			Estimate Time / Date
		</td>
		<td style="color:white">
			Amount
		</td>
	</tr>
	<?php
	while($tx=$display_send->fetch(PDO::FETCH_ASSOC)) {
		?>
		<tr>
			<td >
				<?php
				echo "<a style='text-decoration:none;color:black;' href='http://atlas.phoenixcoin.org:1080/tx/".$tx["Tx_id"]."' target=__blank>".$tx["To_address"]."</a>"."<br />";
				?>
			</td>
			<td >
				<?php
				$estimate_like = time() - $tx["Date_send"];
				$mins = round($estimate_like / 60);
				$hours = round($estimate_like / 3600);
				$days = round($estimate_like / 86400);
				$weeks = round($estimate_like / 604800);
				$months = round($estimate_like / 2600640);
				$years = round($estimate_like / 31207680);

				if($estimate_like <= 60) {
					echo "$estimate_like Secs ago";
				} elseif($mins <= 60) {
					echo "$mins mins ago";
				} elseif($hours <= 23) {
					echo "$hours hours ago ";
				} elseif($days == 1) {
					echo "$days Day ago";
				} elseif($days <= 6) {
					echo "$days Days";
				} elseif($weeks == 1) {
					echo "$weeks Week ago";
				} elseif($weeks <= 4.3) {
					echo "$weeks weeks ago";
				} elseif($months) {
					echo "$months month ago";
				} elseif($months) {
					echo "$months months ago";
				} elseif($years == 1) {
					echo "$years year ago";
				} else {
					echo "$years years ago";
				}

				?>
			</td>
			<td >
				<?php
				echo "-".$tx["Amount_send"];

				?>
			</td>
		</tr>
		<?php
	}
	} else {
		?>
		<div class="table" id="s_tx" style="text-align:center;">
			No Send transation found!
		</div>
		<?php
	}
	?>
</table>
<?php
		}
	}

		$wallet = new Wallet($con, $coin);
if(!empty($_POST["Username"])) {
	echo $wallet->Username($session);
}

if(!empty($_POST["Vali_orbitcoin_address"])) {
	$orb_address_for_vali = ($_POST["Vali_orbitcoin_address"]);
	echo $wallet->Is_validate_address($orb_address_for_vali);
}

if(!empty($_POST["orbitcoin_send_payment_password"])) {
	$user_input_pass_in_send_payment = ($_POST["orbitcoin_send_payment_password"]);
	echo $wallet->chk_user_pass_during_send_payment($user_input_pass_in_send_payment, $session);
}

if(!empty($_POST["Check_amount"])) {
	$chk_amount = $_POST["Check_amount"];
	echo $wallet->chk_amount($chk_amount, $session);
}

if(isset($_POST["orb_address"])) {
if(!empty($_POST["orb_address"])) {
	if(!empty($_POST["payment"])) {
		if(!empty($_POST["account_pass"])) {
	$orbitcoin_address = $_POST["orb_address"];
	$amnount = $_POST["payment"];
	$password_orb = $_POST["account_pass"];
	echo $wallet->send_payment($orbitcoin_address, $amnount, $password_orb, $session);
		} else {
			echo "Please Enter Account Password!";
		}
	} else {
		echo "Please Enter Amount!";
	}
} else {
	echo "Please Enter Orbitcoin To send payment!";
}
}

if(isset($_POST["generate_new"])) {
	if(!empty($_POST["generate_new"])) {
		echo $wallet->generate_new($session);
	}
}

if(isset($_POST["Email"]) OR isset($_POST["New_pass"]) OR isset($_POST["Password"])) {
if(!empty($_POST["Email"]) OR !empty($_POST["New_pass"])) {
	if(!empty($_POST["Password"])) {
			if(!empty($_POST["Email"]) AND !empty($_POST["New_pass"])) {
				$email =  $_POST["Email"];
				$old_pass = $_POST["Password"];
				$password = $_POST["New_pass"];
				if(strlen($_POST["New_pass"]) >= 6) {
				echo $wallet->Change_Account_setting($session, $email, $old_pass, $password);
				} else {
					echo "Please enter minimum 6 Characters In Password!";
				}
			} else {
				if(!empty($_POST["New_pass"])) {
					if(strlen($_POST["New_pass"]) >= 6) {
					$old_pass =$_POST["Password"];
					$password = $_POST["New_pass"];
					echo $wallet->Change_Account_Password($session, $old_pass, $password);
					} else {
						echo "Please enter minimum 6 Characters In Password!";
					}
				} else {
					if(!empty($_POST["Email"])) {
						$email = $_POST["Email"];
						if(!empty($_POST["Password"])) {
							$pass = $_POST["Password"];
							echo $wallet->Change_Account_Email($session, $email, $pass, $session);
						} else {
							echo "Please Enter Account Password to change Setting!";
						}
					}
				}
			}
	} else {
		echo "Please Enter Account Password to change Setting!";
	}
} else {
	echo "Please Enter Fields to change Account Setting!";
}
}

if(!empty($_POST["Dump_address"])) {
	if($_POST["Dump_address"] != "Choose Orb Address") {
		if(!empty($_POST["Dump_password"])) {
			$dump_adr = $_POST["Dump_address"];
			$dump_pass = $_POST["Dump_password"];
			echo $wallet->Dump_address($session, $dump_adr, $dump_pass);
		} else {
			echo "Please Enter Password To dump address!";
		}
	} else {
		echo "Please Select Orbitcoin For Dumpinging Address!";
	}
}

if(!empty($_POST["Balance"])) {
	echo $wallet->Balance($session);
}

if(!empty($_POST["total_receive"])) {
	echo $wallet->total_receive($session);
}

if(!empty($_POST["receive_txs"])) {
	echo $wallet->receive_txs($session);
}

if(!empty($_POST["Send_txs"])) {
	echo $wallet->send_txs($session);
}

	} else {
	echo "ERROR! Reload page";
}

} else {
	echo "ERROR! Reload page";
}
?>