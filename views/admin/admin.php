<?php
require_once("../../templates/mustBeLogedIn.php");
require_once("../../templates/mustBeAdmin.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vapor</title>
    <link rel="stylesheet" href="../../styles/style.css" />
</head>

<body>
    <div class="content">
        <?php require_once("../../templates/header.php") ?>
        <div class="content-body">
            <h1>Welcome Gaben, change whatever your righteousness desires</h1>
            <a href="./gamesCRUD.php">Games O' Games</a>
            <br>
            <a href="./accountsCRUD.php">Oh The Humanity</a>
        </div>
        <?php require_once("../../templates/footer.php") ?>
    </div>

</body>

</html>