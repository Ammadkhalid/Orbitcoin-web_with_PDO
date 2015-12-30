<?php
include "php/header.php";
require "php/config.php";
require "php/wallet.class.php";
if(isset($_SESSION["Usr_d"])) {
	if($wallet->chk_session($session) != false) {
?>
<!DOCTYPE html>
<html >
		<?php $title = $wallet->get_username($session);
		echo $header->Get_wallet($title);
		?>
  <div class="topbar">
  <ul>
	<li> <a href="logout.php">Logout</a></li>
  </ul>
  <div id="con"><span id="refresher"><i class="fa fa-refresh "></i></span></div>
  <span id="top_balance"><p><?php echo $wallet->get_balance($session); ?> ORB</p></span>
  </div>
	<header>
	<ul>
	<li id="home" class="home_active">Wallet</li>
	<li id="tx">Transactions</li>
	<li id="send">Send Payment</li>
	<li id="rx">Receive</li>
	<li id="account">Account Setting</li>
	</ul>
	</header>
  <body>
	<div class="container" id="home_content">
	<span id="total_b">Total Receive: <?php 
	echo $wallet->total_receive($session);
	?> ORB</span>
	<p id="balance">Balance: <?php echo $wallet->get_balance($session);
	?> ORB</p><br />
	<div id="qt_code">
	<img width="100px" height="100px" src="http://api.qrserver.com/v1/create-qr-code/?color=FFFFFF&amp;bgcolor=497BE8&amp;data=<?php echo $wallet->Wallet_address($session); ?>&amp;qzone=1&amp;margin=0&amp;size=150x150&amp;ecc=L" alt="qr code" />
	</div><br />

	<span id="wallet_address">
	<span><?php
		echo $wallet->Wallet_address($session);
	?></span>
	</span>
	</div>

	<div class="container_tx" id="tx_content">
	<span >Toggle Transactions</span>
	<div id="receive_txs">
<?php
$username = $wallet->get_username($session);
if($wallet->Chk_receive_txs($username) == true) {
$r_tx = $con->prepare("SELECT * FROM user_receive_transations WHERE Tx_username=:username ORDER BY Date DESC");
$rx_data = array(":username" => $username);
$r_tx->execute($rx_data);
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
	$shorting_adrs = $con->prepare("SELECT * FROM sender_address WHERE Username=:username AND next_tx_id=:next_tx_id LIMIT 1");
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
                </table>
				</div>
				<div id="send_txs">
<?php
if($wallet->Chk_send_txs($username) == true) {
$display_send = $con->prepare("SELECT * FROM send_transations WHERE Account=:user ORDER BY Date_send DESC");
$s_tx_data = array(":user" => $username);
$display_send->execute($s_tx_data);
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
		
	</div>
	</div>
	
	<div class="container_send" id="send_content">
	<div id="send_menu">
	<h2>Menu</h2>
	<ul>
	<li id="active_menu">Quick Send</li>
	</ul>
	</div>
	
	<div id="simple_send">
	<h1>Quick Send</h1>
	<p>Use the form below to send a payment to a Orbitcoin Address</p>
	<div class="send_form">
	<input type="text" placeHolder="Orbitcoin Address" id="simple_address"><br />
	<input type="text" placeHolder="Amount" id="simple_amount"><br />
	<input type="Password" placeHolder="Password"><br />
	<button type="submit" id="button_simple">Send</button><br />
	
	<div id="error">
	
	
	</div>
	
	</div>
	
	</div>
	
	
	</div>
	<div id="receive_content" class="container">
	<table class="table">
                    <tr>
                        <td style="color:white">
                            Orbitcoin Address
                        </td>
                        <td style="color:white">
                            Balance
                        </td>
                        <td style="color:white">
                            qr Code
                        </td>
                    </tr>
	<?php 
	$count = $coin->getaddressesbyaccount($username);
	for($i=count($count)-1;$i>=0;$i--) {
	?>
                    <tr>
                        <td >
                         <?php echo $count[$i]; ?>
                        </td>
						<td >
						<?php
						$cg = $coin->listaddressgroupings();
						
						for($l=count($cg)-1;$l>=0;$l--) {
							for($k=count($cg[$l])-1;$k>=0;$k--) {
								if($cg[$l][$k]["0"] ==  $count[$i]) {
									print_r($cg[$l][$k]["1"]." ORB");
								}
							}
						}
						?>
						</td>
						<td >
						<a href="http://api.qrserver.com/v1/create-qr-code/?color=FFFFFF&amp;bgcolor=497BE8&amp;data=<?php echo $count[$i]; ?>&amp;qzone=1&amp;margin=0&amp;size=150x150&amp;ecc=L" target="__blank"><img width="30px" height="30px" src="http://api.qrserver.com/v1/create-qr-code/?color=FFFFFF&amp;bgcolor=497BE8&amp;data=<?php echo $count[$i]; ?>&amp;qzone=1&amp;margin=0&amp;size=150x150&amp;ecc=L" alt="qr code" /></a>
						</td>
                    </tr>
					<?php }?>
                </table>
	<button id="c_adr">Create New Address</button><p>Note: The address with empty balance field means 0 balance!</p>
	</div>
	
	<div class="container" id="account_content">
	<div id="account_menu"> 
	<h2>Menu</h2>
	<ul>
	<li id="active_menu">Account Setting</li>
	<li>Dump Address</li>
	</ul>
	</div>
	
	<div id="simple_send">
	<h1>Account Setting</h1>
	<p>Using this form to change email or Password And both</p>
	<div class="form">
	<input type="password" placeHolder="Change Password" id="password"><br />
	<input type="text" placeHolder="Change Email" id="email"><br />
	<input type="Password" placeHolder="Password" id="apass"><br />
	<button type="submit" id="change_simple">Change</button><br />
	
	<div id="error">
	
	
	</div>
	
	</div>
	
	</div>
	
	<div id="import_send">
	<h1>Dump address</h1>
	<p>Using this form to dump your Orbitcoin addresses!</p>
	<input type="password" Placeholder="Password" id="password"><br />
	<select id="adr_option">
	<option value="Choose Orb Address">Choose Orb Address</option>
	<?php
	$option = $coin->getaddressesbyaccount($username);
	for($i=count($option)-1;$i>=0;$i--) {
	?>
	<option value="<?php echo $option[$i]; ?>"><?php echo $option[$i]; ?></option>
	<?php }
	?>
	</select><br />
	<button type="submit" id="dump_adr">Dump Address</button><br />
	<textarea id="dump_keys">
	
	
	</textarea>
	</div>
	
	</div>
	</div>
	<script src="js/wallet.js"></script>
	<link type="text/css" rel="stylesheet" href="css/tooltipster-shadow.css" />
	<link type="text/css" rel="stylesheet" href="css/tooltipster.css" />
	<script type="text/javascript" src="js/jquery.tooltipster.min.js"></script>
  </body>
  <?php include "php/footer.php"; ?>
</html>
<?php
	} else {
		session_start();
		session_destroy();
		header("location:login.php");
	}
} else {
	header("location:login.php");
}
