<?php
require "php/config.php";
include "php/header.php";
function Clean($string) {
	$string = htmlspecialchars($string);
	$string = addslashes($string);
	return $string;
}
if(isset($_GET["hash"]) && isset($_GET["id"])) {
	class Wallet {
		private $con;
        private $coin;
		public function __construct($con, $coin) {
			$this->con = $con;
            $this->coin = $coin;
		}

		public function confirm_email($hash, $id) {
			try {
				$no = 'No';
				$chk = $this->con->prepare("SELECT Email FROM users WHERE Code=:hash AND ID=:id AND Email_confirm=:email_confirm_no");
				$data = array(":hash" => $hash,
					":id" => $id,
					":email_confirm_no" => $no);
				$chk->execute($data);
				if($chk->rowCount() == 0) {
					?>
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
					<link rel="stylesheet" href="css/notifIt.css">
					<script src="js/notifIt.js"></script>
					<script>
						$(document).ready(function () {
							notif({
								type: "info",
								msg: "Invalid Link!",
								autohide: true,
								position: "bottom",
								opacity: 0.7,
								timeout: 5000,
								zindex: 0,
								offset: 0,
								fade: 100,
								bgcolor: "red"
							});
						});
					</script>
					<?php
				} else {
                    $yes = 'Yes';
                    $update_email = $this->con->prepare("UPDATE users SET Email_confirm=:email_confirm WHERE Code=:code AND ID=:id");
                    $up_email_data = array(":email_confirm" => $yes,
                        ":code" => $hash,
                        ":id" => $id);
                    $update_email->execute($up_email_data);
                    $update_code = $this->con->prepare("UPDATE users SET Code=:code WHERE ID=:id");
                    $code = Null;
                    $code_data = array(":code" => $code,
                        ":id" => $id);
                    $update_code->execute($code_data);
                    if($update_code) {
                        $get_username = $this->con->prepare("SELECT Username FROM users WHERE ID=:id");
                        $get_username->bindparam(":id", $id);
                        $get_username->execute();
                        $user = $get_username->fetch(PDO::FETCH_ASSOC);
                       foreach($user as $username) {
                           $username =  $username;
                       }
                        $address = $this->coin->getaccountaddress($username);
						if($address) {
							$insert_address = $this->con->prepare("UPDATE users SET Wallet_address=:address WHERE id=:id");
							$adr_insert = array(":id" => $id,
								":address" => $address);
							$insert_address->execute($adr_insert);
							if($insert_address) {
								?>
								<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
								<link rel="stylesheet" href="css/notifIt.css">
								<script src="js/notifIt.js"></script>
								<script>
									$(document).ready(function () {
										notif({
											type: "success",
											msg: "Thanks Email has been confirmed! Please Login now!",
											autohide: true,
											position: "bottom",
											opacity: 0.7,
											timeout: 5000,
											zindex: 0,
											offset: 0,
											fade: 100,
											bgcolor: "green"
										});
									});
								</script>
								<?php
							} else {
								?>
								<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
								<link rel="stylesheet" href="css/notifIt.css">
								<script src="js/notifIt.js"></script>
								<script>
									$(document).ready(function () {
										notif({
											type: "info",
											msg: "ERROR!",
											autohide: true,
											position: "bottom",
											opacity: 0.7,
											timeout: 5000,
											zindex: 0,
											offset: 0,
											fade: 100,
											bgcolor: "red"
										});
									});
								</script>
								<?php
							}
						}
                    }
				}
			}

			catch(PDOEXception $error) {
				echo $error->getMessage();
			}

		}
	}
	$hash = Clean($_GET["hash"]);
	$id = Clean($_GET["id"]);
	$wallet = new Wallet($con, $coin);
	echo $wallet->confirm_email($hash, $id);
}
?>
<!DOCTYPE html>
<html >
<?php echo $header->get_index(); ?>
	<header>
	<ul>
	<li><a href="index.php">Home</li></a>
	<li><a href="reg.php">Create Wallet</li></a>
	<li><a href="login.php">Wallet Login</li></a>
	</ul><br />
	</header><br />
  <body>
	<div class="body">
	<h1>Rich addresses</h1>
	<div id="richlists">
		<?php
		$get_list = $con->prepare("SELECT * FROM addresses_balance ORDER BY Balance DESC");
		$get_list->execute();
		if($get_list->rowCount() > 0) {
		?>
	<table>
  <thead>
    <tr>
      <th>Rank</th>
      <th>Address</th>
      <th>Amount</th>
    </tr>
	<?php
	$i = 0;
	while($list=$get_list->fetch(PDO::FETCH_ASSOC)) {
		$i++;
		if($list["Balance"] > 0) {
		?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><?php echo $list["Address"]; ?></td>
				<td><?php echo $list["Balance"]; ?></td>

			</tr>
	<?php
	}
	}
	?>
  </thead>
  <tbody>
  </tbody>
</table>
		<?php } else {
			echo "Addresses Found!";
		} ?>
	</div>
	</div>
  </body>
</html>