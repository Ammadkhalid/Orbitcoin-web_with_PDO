<?php
error_reporting(0);
require_once "config.php";
require_once "jsonRPCClient.php";
$sel_user = $con->prepare("SELECT Username FROM users");
$sel_user->execute();
foreach($sel_user->fetchAll() as $ysk) {
	$usrs = $ysk["Username"];
	$store = $coin->listtransactions($usrs);
	foreach($store as $li => $jh) {
	$uusr_tx = ($store[$li]["account"]);
	$tx = $store[$li]["txid"];
	$c = $coin->getrawtransaction($tx);
	$d = $coin->decoderawtransaction($c);
	$store_time = $d["time"];
	
	$est = array_splice($jh, 1);
	$amount = $est["amount"];
		$chk_txs = $con->prepare("SELECT ID FROM User_receive_transations WHERE Tx_id=:txs_id AND Tx_username=:tx_users");
		$chk_data_txs = array(":txs_id" => $tx,
			":tx_users" => $uusr_tx);
		$chk_txs->execute($chk_data_txs);
		if($chk_txs->rowCount() == 0) {
			$store = $con->prepare("INSERT INTO User_receive_transations (Tx_username,Tx_id,Date,Amount) VALUES (:tx_username,:tx_id,:date,:amount)");
			$data_insert = array(":tx_username" => $uusr_tx, ":tx_id" => $tx, ":date" => $store_time, ":amount" => $amount);
			$chk_txs_store = $store->execute($data_insert);
			if($chk_txs_store) {
				return "Ok!";
			}
		}
	}
}

$sqli_inert_usr = $con->prepare("SELECT Username FROM users");
$sqli_inert_usr->execute();
foreach($sqli_inert_usr->fetchAll() as  $user) {
	$pdo_sel = $con->prepare("SELECT Tx_username,Tx_id,Date FROM user_receive_transations Where Tx_username=:tx_username ORDER BY ID DESC");
	$tsr_data = array(":tx_username" => $user["Username"]);
	$pdo_sel->execute($tsr_data);
	foreach($pdo_sel->fetchAll() as  $tx) {
		$jj = $coin->getrawtransaction($tx["Tx_id"]);
		$test = $coin->decoderawtransaction($jj);
		echo "<pre>";
		$tx = $test["txid"];
		foreach($test as $test1 => $testk) {
			$time = $test["time"];
			foreach($testk as $kk) {
				$prev_txs = ($kk["txid"]);
				if(!empty($prev_txs) && !empty($tx) && !empty($user["Username"])) {
					$username = $user["Username"];
					$pdo_chk = $con->prepare("SELECT ID FROM user_previous_tx_id WHERE Username=:usrs AND Prev_tx_id=:prev_tx AND next_tx_id=:next_tx");
					$previous_txs = array(":usrs" => $username, ":prev_tx" =>$prev_txs, ":next_tx" => $tx);
					$pdo_chk->execute($previous_txs);
					if($pdo_chk->rowCount()  == 0) {
						$insert = $con->prepare("INSERT INTO user_previous_tx_id (Username,Prev_tx_id,next_tx_id) VALUES (:username,:prevs,:next)");
						$datas = array(":username" => $username,
							":prevs" => $prev_txs,
							":next" => $tx);
						$inserts =$insert->execute($datas);
						if($inserts) {
							echo true;
						}
					}
				}
			}
		}
	}
}

$prepare = $con->prepare("SELECT Username FROM users");
$prepare->execute();
while($user=$prepare->fetch(PDO::FETCH_ASSOC)) {
	$sqli_get_txs = $con->prepare("SELECT * FROM user_previous_tx_id WHERE Username=:username");
	$user = array(":username" => $user["Username"]);
	$sqli_get_txs->execute($user);
	foreach($sqli_get_txs->fetchAll() as $tx_data) {
		$raw = $coin->getrawtransaction($tx_data["Prev_tx_id"]);
		$decods = $coin->decoderawtransaction($raw);
		foreach($decods as $craps) {
			foreach($craps as $hits) {
				foreach($hits as $shit) {
					$yy = (array_splice($shit, 3));
					$adrs = ($yy["addresses"]["0"]);
					if(!empty($adrs) && !empty($tx_data["Prev_tx_id"]) && !empty($tx_data["next_tx_id"])) {
						$chk = $con->prepare("SELECT ID FROM sender_address WHERE Username=:username AND Prev_tx_id=:prev_id AND next_tx_id=:next_id AND Sender=:sender");
						$data_chk = array(":username" => $user[":username"], ":prev_id" => $tx_data["Prev_tx_id"], ":next_id" => $tx_data["next_tx_id"], ":sender" => $adrs);
						$chk->execute($data_chk);
						if($chk->rowCount() == 0) {
							$insert = $con->prepare("INSERT INTO sender_address (Username,Prev_tx_id,next_tx_id,Sender) VALUES (:username,:prev_id,:next_id,:sender)");
							$ins = $insert->execute($data_chk);
							if($ins) {
								return "Ok!";
							}
						}
					}
				}

			}
		}
	}
}

//richlist
$get_rich = $coin->listaddressgroupings();
foreach($get_rich as $keys) {
	foreach($keys as $indexs => $key) {
		$address = $key["0"];
		$balance = $key["1"];
		$pdo_sel = $con->prepare("SELECT ID FROM addresses_balance WHERE Address=:address");
		$chk = array(":address" => $address);
		$pdo_sel->execute($chk);
		if($pdo_sel->rowCount() == 0) {
			$sel_insert = $con->prepare("INSERT INTO addresses_balance (Address,Balance) VALUES (:address,:balance)");
			$data = array(":address" => $address,
				":balance" => $balance);
		} else {
			//	$sqli_update = mysqli_query($con, "UPDATE addresses_balance SET Balance='".$balance."' WHERE address='".$address."'");
			$update = $con->prepare("UPDATE addresses_balance SET Balance=:balance WHERE address=:address");
			$data = array(":address" => $address,
				":balance" => $balance);
		}
	}
}
?>


