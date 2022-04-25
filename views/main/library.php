<?php
require_once("../../templates/mustBeLogedIn.php");
require_once("../../classes/SessionManager.php");
require_once("../../models/Account.class.php");
require_once("../../models/Game.class.php");
require_once("../../classes/AccountRepository.php");
require_once("../../classes/GameRepository.php");
require_once("../../models/Achievement.class.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../styles/style.css" />
    <title>Vapor</title>
</head>
<?php
$accountRepository = new AccountRepository();
$gameRepository = new GameRepository();

$addToLibrary = htmlspecialchars($_POST['addToLibrary']);

if (!empty($addToLibrary)) {
    $accountRepository->addGameToAccountLibrary($addToLibrary, $_SESSION["Id"]);
}

$addAchievement = htmlspecialchars($_POST["addAchievement"]);

if (!empty($addAchievement)) {
    $accountRepository->addAchievementToAccount($addAchievement, $_SESSION["Id"]);
}

$currentAccount = $accountRepository->getGamesWithAccountId($_SESSION["Id"]);

$chosenGame = htmlspecialchars($_GET['chosenGame']);
$chosenGameInstance = new Game(0, "", 0, "noGame.png", "", "", 0, "");

if (!empty($chosenGame)) {
    $chosenGameInstance = $gameRepository->getById($chosenGame);
    $chosenGameInstance->achievements = $gameRepository->getAchievements($chosenGame);
}
?>

<body>
    <div class="content">
        <?php require_once("../../templates/header.php") ?>
        <div class="content-body">
            <div class="library">
                <form action="./library.php" method="get">
                    <?php foreach ($currentAccount->games as $game) : ?>
                        <button class="<?= $chosenGameInstance->gameId == $game->gameId ? "selectedGame" : "" ?>" name="chosenGame" value="<?= $game->gameId ?>"><?= $game->gameName ?></button>
                    <?php endforeach ?>
                </form>
                <div>
                    <img src="../../resources/<?= $chosenGameInstance->thumbnail ?>" alt="Game Thumbnail" height="350px" width="100%">
                    <div class="imageBotText"><?= $chosenGameInstance->gameName ?></div>
                    <button onclick="window.open('<?= $chosenGameInstance->isDisabled ? 'https://en.wikipedia.org/wiki/List_of_banned_video_games' : $chosenGameInstance->downloadLink ?>', '_blank')" class="download-button">Download</button>
                    <h3>Details</h3>
                    <ul>
                        <li><?= $chosenGameInstance->description ?> </li>
                        <li><?= $chosenGameInstance->releaseDate ?> </li>
                        <li>- $ <?= $chosenGameInstance->price ?> </li>
                    </ul>
                    <h3>Achievements</h3>
                    <div class="library-achievements">
                        <?php foreach ($chosenGameInstance->achievements as $achievement) : ?>
                            <img width="64" height="64" src="../../resources/<?= $achievement->thumbnail ?>" alt="Achievement Thumbnail">
                            <h4><?= $achievement->achievementName ?></h4>
                            <h5><?= $achievement->description ?></h5>
                            <h4><?= $achievement->isDisabled ? "This achievement isn't available anymore" : "" ?></h4>
                            <?php if(!$accountRepository->isAchievementAddedToAccount($achievement->achievementId, $_SESSION["Id"])) :?>
                                <form action="./library.php?chosenGame=<?=$chosenGame?>" method="post">
                                    <button type="submit" value="<?= $achievement->achievementId ?>" name="addAchievement">Add Achievement to Account</button>
                                </form>
                            <?php endif?>
                        <?php endforeach ?>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>