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
    <link rel="stylesheet" href="../../styles/style.css" />
</head>
<?php
$gameId = htmlspecialchars($_GET['gameId']);

if (empty($gameId)) {
    SessionManager::redir("/");
}

$selectedGame = $gameRepository->getById($gameId);
?>

<body>
    <div class="content">
        <?php require_once("../../templates/header.php") ?>
        <div class="content-body">
            <div class="single-game-page">
                <img width="96%" height="45%" src="../../resources/<?= $selectedGame->thumbnail ?>" alt="Single Page Game Thumbnail">
                <h1 class="imageTopText"><?= $selectedGame->gameName ?></h1>
                <div class="single-game-page-rest">
                    <?php if(!$accountRepository->isGameAddedToAccount($selectedGame->gameId, $_SESSION["Id"])):?>
                    <form action="./library.php?chosenGame=<?= $selectedGame->gameId?>" method="post">
                        <button type="submit" name="addToLibrary" value="<?= $selectedGame->gameId?>">Add to Library/ Buy</button>
                    </form>
                    <?php else:?>
                        <h1>Game Already Owned</h1>
                    <?php endif?>
                    <h1>$ -<?= $selectedGame->price ?></h1>
                    <h1>
                        Description:
                        <br>
                        <?= $selectedGame->description ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</body>

</html>