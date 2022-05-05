<?php
require_once "../../classes/SessionManager.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/style.css">
    <title>Vapor</title>
</head>

<body class="makeFlex">


    <?php
    if (SessionManager::isLoggedIn()) {
        SessionManager::redir("../main/home.php");
    }
    ?>

    <form action="./login.php" method="post">
        <p>Username</p>
        <input type="text" name="dbuser">
        <p>Password</p>
        <input type="password" name="password">
        <br>
        <input type="submit" class="notrealinput" value="Login">
    </form>
    <br>
    <br>
    <button onclick="window.location = './register.php'">
        Escape
    </button>
    <button onclick="window.location = './reset.php'">
        Forgor?
    </button>

    <?php
    $dbUser = htmlspecialchars($_POST['dbuser']);
    $dbPassword = htmlspecialchars($_POST['password']);

    if (!empty($dbUser) && !empty($dbPassword)) {
        SessionManager::login($dbUser, $dbPassword);
        SessionManager::redir("../main/home.php");
    }
    ?>
</body>