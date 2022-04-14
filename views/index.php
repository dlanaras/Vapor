<?php
require_once("../classes/SessionManager.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css" />
    <title>Document</title>
</head>
<?php
if (!SessionManager::isLoggedIn()) {
    SessionManager::redir("./login.php");
}
?>

<body>
    <div class="content">
        <?php require_once("../templates/header.php") ?>
        <div class="content-body">
        </div>
        <?php require_once("../templates/footer.php") ?>
    </div>

    <?php
    if (isset($_POST['logoutButt'])) {
        SessionManager::logout();
    }
    ?>
</body>

</html>