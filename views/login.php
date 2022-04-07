<?php
include_once "../classes/SessionManager.php";
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
    //echo SessionManager::isLoggedIn();
    if(SessionManager::isLoggedIn()) {
        SessionManager::redir("./index.php");
    }
?>

<form action="./login.php" method="post">
            Username
            <input type="text" name="dbuser" >
            Password
            <input type="password" name="password" >
        <input type="submit">
</form>

<button onclick="window.location = './register.php'">
    Escape
</button>

<?php
    $dbUser = htmlspecialchars($_POST['dbuser']);
    $dbPassword = htmlspecialchars($_POST['password']);
    
    if (!empty($dbUser) && !empty($dbPassword)) {
        SessionManager::login($dbUser, $dbPassword);
        SessionManager::redir("./index.php");
    }
?>
</body>
