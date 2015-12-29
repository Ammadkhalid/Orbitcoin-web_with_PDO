<?php
require_once "config.php";
class Wallet {
    private $con;
    private $coin;

    public function __construct($con, $coin) {
        $this->con = $con;
        $this->coin = $coin;
    }

    public function chk_session($session) {
            try {
                $chk_session = $this->con->prepare("SELECT Username FROM users WHERE Session_id=:id");
                $session_data  = array(":id" => $session);
                $chk_session->execute($session_data);
                if($chk_session->rowCount() == 1) {
                    return true;
                }
            }


            catch(PDOException $e) {
                echo $e->getMessage();
            }

    }

    public function get_username($session) {
        try {
        $get_usr = $this->con->prepare("SELECT Username FROM users WHERE Session_id=:id");
        $data = array(":id" => $session);
        $get_usr->execute($data);
        foreach($get_usr->fetch(PDO::FETCH_ASSOC) as $username) {
            return $username;
        }
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function get_balance($session) {
        try {
            $get_usr = $this->con->prepare("SELECT Username FROM users WHERE Session_id=:id");
            $data = array(":id" => $session);
            $get_usr->execute($data);
            foreach($get_usr->fetch(PDO::FETCH_ASSOC) as $username) {
                $username = $username;
            }
            $address = $this->coin->getaddressesbyaccount($username);
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
            return $balance;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function total_receive($session) {
        try {
            $get_usr = $this->con->prepare("SELECT Username FROM users WHERE Session_id=:id");
            $data = array(":id" => $session);
            $get_usr->execute($data);
            foreach($get_usr->fetch(PDO::FETCH_ASSOC) as $username) {
                $username = $username;
            }
            $total_receive = $this->coin->getreceivedbyaccount($username);
            return $total_receive;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function Wallet_address($session) {
        try {
            $get_wallet_adr = $this->con->prepare("SELECT Username,Wallet_address FROM users WHERE Session_id=:session");
            $data = array(":session" => $session);
            $get_wallet_adr->execute($data);
            foreach($get_wallet_adr->fetchAll() as $indexs => $rows) {
                $username = $rows["Username"];
                $main_wallet_address = $rows["Wallet_address"];
            }
            echo $main_wallet_address;
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function Chk_receive_txs($username) {
    try {
        $chk_txs = $this->con->prepare("SELECT * FROM user_receive_transations WHERE Tx_username=:username ORDER BY Date DESC");
        $usr_data = array(":username" => $username);
        $chk_txs->execute($usr_data);
        if($chk_txs->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    catch(PDOException $e) {
        echo $e->getMessage();
    }
    }

    public function Chk_send_txs($username) {
        try {
            $chk_tx = $this->con->prepare("SELECT * FROM send_transations WHERE Account=:user ORDER BY Date_send DESC");
            $usr = array(":user" => $username);
            $chk_tx->execute($usr);
            if($chk_tx->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }

        catch(PDOException $fetch) {
        echo $fetch->getMessage();
        }
    }

}
session_start();
$session = $_SESSION["Usr_d"];
$wallet = new Wallet($con, $coin);