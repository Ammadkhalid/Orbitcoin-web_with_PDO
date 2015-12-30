<?php
require"jsonRPCClient.php";
// edit your host db name and wallet etc
$host = "localhost";
$user = "root";
$db = "orbitcoin";
$pass = "";
try {
	$con = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
	$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$coin = new jsonRPCClient("http://Orb:Orbitcoin_password@127.0.0.1:15299");
}

catch(PDOException $e) {
	echo $e->getMessage();
}

?>
