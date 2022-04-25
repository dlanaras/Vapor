<?php
require_once("../../classes/GameRepository.php");
require_once("../../templates/mustBeLogedIn.php");
require_once("../../templates/mustBeAdmin.php");
require_once("../../models/Achievement.class.php")
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
    <?php require_once("../../templates/header.php") ?>
    <div class="content-body">
        <?php
            //TODO: only allow adding achievements here since a gameId is required to create an achievement
        ?>
    </div>
    <?php require_once("../../templates/footer.php") ?>
</body>