<?php
require_once "../../classes/SessionManager.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    if (SessionManager::isLoggedIn()) {
        SessionManager::redir("../main/index.php");
    }
    ?>

    <form action="./register.php" method="post">
        Firstname
        <input type="text" name="firstName" required>
        Lastname
        <input type="text" name="lastName" required>
        E-Mail
        <input type="email" name="email" required>
        Username
        <input type="text" name="dbuser" minlength="3" required>
        Password
        <input type="password" name="password" required>
        <input type="submit">
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

    if (!empty($dbUser) && !empty($dbPassword) && strlen($dbPassword) > 3 && !empty($dbFirstName) && !empty($dbLastName) && !empty($dbEmail)) {
        SessionManager::register($dbUser, $dbPassword, $dbFirstName, $dbLastName, $dbEmail);
        SessionManager::redir("../main/index.php");
    }
    ?>
</body>