<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Reset Password</h1>
    <?php
    require_once("../../classes/SessionManager.php");

    $resEmail = htmlspecialchars($_POST["resEmail"]);

    if(!empty($resEmail)) SessionManager::resetPassword($resEmail);
    ?>

    <form action="./reset.php" method="post">
        <input type="email" name="resEmail">
        <input type="submit">
    </form>
</body>
</html>