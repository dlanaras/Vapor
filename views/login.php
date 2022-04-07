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
            <input type="text" name="dbuser" required>


            Password
            <input type="password" name="password" required>


            Login? 
            <input type="checkbox" name="wantLogin" value="1">

        <input type="submit">
</form>

<?php
            $dbUser = htmlspecialchars($_POST['dbuser']);
            $dbPassword = htmlspecialchars($_POST['password']);
            
            if (!empty($dbUser) && !empty($dbPassword)) {

                if(isset($_POST['wantLogin'])){

                    SessionManager::login($dbUser, $dbPassword);
                    SessionManager::redir("./index.php");
                }
                else{
                    SessionManager::register($dbUser, $dbPassword);
                    SessionManager::redir("./index.php");
                }
            }
?>
</body>
