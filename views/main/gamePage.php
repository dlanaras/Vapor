<?php
require_once("../../classes/SessionManager.php");
require_once("../../templates/mustBeLogedIn.php");
require_once("../../classes/GameRepository.php");
require_once("../../models/Game.class.php");
require_once("../../classes/AccountRepository.php");
$accountRepository = new AccountRepository();
$gameRepository = new GameRepository();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vapor</title>
    <link rel="stylesheet" type="text/css" href="../../styles/terminal.css">
</head>
<?php
$gameId = htmlspecialchars($_GET['gameId']);

if (empty($gameId)) {
    SessionManager::redir("/");
}

$selectedGame = $gameRepository->getById($gameId);
?>

<body>
    <div class="overlay"></div>
    <div class="scanline"></div>
    <div class="wrapper">
        <div class="content clearfix">
            <header class="site clearfix">
                <nav class="site clear">
                    <?php require_once("../../templates/header.php") ?>
                </nav>

                <div class="col one" style="margin-block-start: 2.2em; margin-block-end: 2em;">
                    <img id="logo-v" src="../../resources/<?= $selectedGame->thumbnail ?>" alt="Single Page Game Thumbnail">
                </div>
                <div class="col two">
                    <h4><br /><?= $selectedGame->gameName ?></h4>
                    <p>----------------------------------------</p>
                    <p>Vapor v 1.0.0</p>
                    <p>(c)2022 Vapor Inc.</p>
                    <p>- Server 420 -</p>
                </div>
            </header>
            <p>Price >> <?= $selectedGame->price ?></p>
            <p class="clear"><br></p>
            <p>
                Description >> <?= $selectedGame->description ?>
            </p>
            <p class="clear"><br></p>
            <?php if (!$accountRepository->isGameAddedToAccount($selectedGame->gameId, $_SESSION["Id"])) : ?>
                <form action="./library.php?chosenGame=<?= $selectedGame->gameId ?>" method="post">
                    <button type="submit" name="addToLibrary" value="<?= $selectedGame->gameId ?>">Add to Library/ Buy</button>
                </form>
            <?php else : ?>
                <p>Game Already Owned</p>
            <?php endif ?>
        </div>
    </div>
    <script type="text/javascript" src="../animation_terminal/terminal.js"></script>
</body>

</html>