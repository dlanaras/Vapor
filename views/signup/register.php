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

<body style="padding-top: 260px; padding-left: 37%">
    <?php
    if (SessionManager::isLoggedIn()) {
        SessionManager::redir("../main/index.php");
    }
    ?>

    <form action="./register.php" method="post">
        <p>Firstname</p>
        <input type="text" name="firstName" required><br>
        <p>Lastname</p>
        <input type="text" name="lastName" required><br>
        <p>E-Mail</p>
        <input type="email" name="email" required><br>
        <p>Username</p>
        <input type="text" name="dbuser" minlength="3" required><br>
        <p>Password</p>
        <input type="password" name="password" required><br>
        <input type="submit" class="notrealinput">
    </form>

    <button onclick="window.location = './login.php'">
        Break Free of the cycle
    </button>

    <?php
    $dbUser = htmlspecialchars($_POST['dbuser']);
    $dbPassword = htmlspecialchars($_POST['password']);
    $dbFirstName = htmlspecialchars($_POST['firstName']);
    $dbLastName = htmlspecialchars($_POST['lastName']);
    $dbEmail = htmlspecialchars($_POST['email']);

    if (!empty($dbUser) && !empty($dbPassword) && strlen($dbPassword) > 2 && !empty($dbFirstName) && !empty($dbLastName) && !empty($dbEmail)) {
        SessionManager::register($dbUser, $dbPassword, $dbFirstName, $dbLastName, $dbEmail);
        SessionManager::redir("../main/index.php");
    }
    ?>
</>