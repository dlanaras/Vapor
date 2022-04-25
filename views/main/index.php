<?php
require_once("../../classes/SessionManager.php");
require_once("../../templates/mustBeLogedIn.php");
require_once("../../classes/GameRepository.php");
require_once("../../models/Game.class.php");

$gameRepository = new GameRepository();
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
$games = $gameRepository->getAll();

$searchTerm = htmlspecialchars($_GET['searchTerm']);
if (!empty($searchTerm)) {
    $foundGames = array();
    foreach ($games as $game) {
        if (str_contains(strtolower($game->gameName), strtolower($searchTerm))) {
            $foundGames[] = $game;
        }
    }
    $games = $foundGames;

    if (count($games) < 1) {
        echo "No Results found with search term $searchterm";
    }
}
?>

<body>
    <div class="content">
        <?php require_once("../../templates/header.php") ?>
        <div class="content-body">
            <form action="./index.php" action="get">
                <input type="text" name="searchTerm" value="<?= is_null($searchTerm) ? "" : $searchTerm ?>">
                <input type="submit" value="Search By Name">
            </form>
            <br>
            <div class="flex-games">
                <?php
                foreach ($games as $game) :
                    if (!$game->isDisabled) :
                ?>
                        <div class="flex-games-element">
                            <img src="../../resources/<?= $game->thumbnail ?>" alt="Game Thumbnail" height="150" width="300">
                            <div class="imageBotText"><?= $game->gameName ?></div>
                            <button onclick="window.location = './gamePage.php?gameId=<?= $game->gameId ?>'" class="buy-button">Buy</button>
                            <h3>- $ <?= $game->price ?> </h3>
                        </div>
                <?php endif;
                endforeach ?>
            </div>
            <br>
            <button onclick="window.location='./index.php'" class="reload-button">Reload</button>
        </div>
        <?php require_once("../../templates/footer.php") ?>
    </div>
</body>

</html>