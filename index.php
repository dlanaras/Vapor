<?php
require_once("SessionManager.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./carsten.jpg">
    <link rel="stylesheet" href="./styles/style.css" />
    <title>Document</title>
</head>
<?php
if (!SessionManager::isLoggedIn()) {
    SessionManager::redir("./login.php");
}
?>

<body>
    <div class="content">
        <div class="content-header">
            <h1>Vapor</h1>
            <div class="flex-grow"></div>
            <form action="./index.php" method="post" class="content-header-top-right-form">
                <button type="button" onclick="window.location = 'https://github.com/Sorry4Nothing/Vapor'">Download</button>
                <input type="submit" name="logoutButt" value="Logout" class="header-top-right-logout-butt">
            </form>
        </div>
        <div class="content-body">

        </div>
        <div class="content-footer">
            <table>
                <tr>
                    <td>
                        <a href="./index.php">Enter Links Here</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <?php
    if (isset($_POST['logoutButt'])) {
        SessionManager::logout();
    }
    ?>
</body>

</html>