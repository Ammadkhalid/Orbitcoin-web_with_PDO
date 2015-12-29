<?php
Class Load_header {
    public function Get_index() {
?>
        <head>
            <meta charset="UTF-8">
            <link rel="icon"  type="image/png" href="https://github.com/ghostlander/Orbitcoin/blob/master/src/qt/res/icons/orbitcoin.png?raw=true"/>
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <title>Home</title>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <script src="./js/index.js"></script>
            <link rel="stylesheet" href="css/index.css">
            <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

        </head>
<?php
    }

    public function Get_login() {
    ?>
        <head>
            <meta charset="UTF-8">
            <link rel="icon"  type="image/png" href="https://github.com/ghostlander/Orbitcoin/blob/master/src/qt/res/icons/orbitcoin.png?raw=true"/>
            <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
            <title>Wallet Login</title>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <link rel="stylesheet" href="css/login.css">
            <link rel="stylesheet" href="css/notifIt.css">
            <script src="js/notifIt.min.js"></script>
            <script src="js/notifIt.js"></script>
        </head>
<?php
    }

    public function Get_reg() {
?>
        <head>
            <meta charset="UTF-8">
            <link rel="icon"  type="image/png" href="https://github.com/ghostlander/Orbitcoin/blob/master/src/qt/res/icons/orbitcoin.png?raw=true"/>
            <title>Create Wallet For Orbitcoin</title>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
            <link rel="stylesheet" href="css/reg.css">
            <link rel="stylesheet" href="css/notifIt.css">
            <script src="js/notifIt.min.js"></script>
            <script src="js/notifIt.js"></script>
        </head>
<?php
    }

    public function Get_wallet($title) {
        ?>
<head>
    <meta charset="UTF-8">
    <link rel="icon"  type="image/png" href="https://github.com/ghostlander/Orbitcoin/blob/master/src/qt/res/icons/orbitcoin.png?raw=true"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Welcome <?php echo $title ?></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <link rel="stylesheet" href="css/wallet.css">
    <link rel="stylesheet" href="css/notifIt.css">
    <link rel="stylesheet" href="css/animation.css">
    <link rel="stylesheet" href="css/icons.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script src="js/notifIt.min.js"></script>
    <script src="js/notifIt.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

</head>
<?php
    }

    public function Get_forgot() {
    ?>
        <head>
            <meta charset="UTF-8">
            <link rel="icon"  type="image/png" href="https://github.com/ghostlander/Orbitcoin/blob/master/src/qt/res/icons/orbitcoin.png?raw=true"/>
            <title>Forgot Password</title>
            <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <link rel="stylesheet" href="css/forgot.css">
            <link rel="stylesheet" href="css/notifIt.css">
            <script src="js/notifIt.min.js"></script>
            <script src="js/notifIt.js"></script>
        </head>
<?php
    }

    public function Get_reset($title) {
    ?>
        <head>
            <meta charset="UTF-8">
            <link rel="icon"  type="image/png" href="https://github.com/ghostlander/Orbitcoin/blob/master/src/qt/res/icons/orbitcoin.png?raw=true"/>
            <link href='http://fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
            <title><?php echo $title; ?></title>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <link rel="stylesheet" href="css/reset.css">
            <link rel="stylesheet" href="css/notifIt.css">
            <script src="js/notifIt.min.js"></script>
            <script src="js/notifIt.js"></script>
            <script src="js/reset.js"></script>
        </head>
<?php
    }
}

$header = new Load_header();
