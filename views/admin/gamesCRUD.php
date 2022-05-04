<?php

use PhpParser\Node\Expr\Cast\Double;

require_once("../../classes/GameRepository.php");
require_once("../../templates/mustBeLogedIn.php");
require_once("../../templates/mustBeAdmin.php");
require_once("../../templates/uploadFile.php");
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
$rowCounter = ((int) htmlspecialchars($_POST['rowCounter'])) - 1;

if (isset($rowCounter)) {
    while ($rowCounter >= 0) {
        $name = htmlspecialchars($_POST["name$rowCounter"]);
        $description = htmlspecialchars($_POST["description$rowCounter"]);
        $thumbnail = htmlspecialchars($_POST["thumbnail$rowCounter"]);
        $isDisabled = (int) isset($_POST["isDisabled$rowCounter"]);
        $gameId = (int) htmlspecialchars($_POST["gameId$rowCounter"]);
        $price = (float) htmlspecialchars($_POST["price$rowCounter"]);
        $releaseDate = htmlspecialchars($_POST["releaseDate$rowCounter"]);
        $downloadLink = htmlspecialchars($_POST["downloadLink$rowCounter"]);

        $newGame = new Game($gameId, $name, $price, $thumbnail, $description, $releaseDate, $isDisabled, $downloadLink);

        $gameRepository->update($newGame);

        $rowCounter--;
    }
}

$addName = htmlspecialchars($_POST['addName']);
$addDesc = htmlspecialchars($_POST['addDesc']);
$addDownLink = htmlspecialchars($_POST['addDownLink']);
$addDate = htmlspecialchars($_POST['addDate']);
$addPrice = htmlspecialchars($_POST['addPrice']);


if (isset($_POST['addGame']) && isset($_FILES['addThumb']) && !empty($addName) && !empty($addDesc) && !empty($addDownLink) && !empty($addPrice) && !empty($addDate)) {
    uploadFileAndReturnName("addThumb");
    $gameToAdd = new Game(0, $addName, $addPrice, $_FILES['addThumb']["name"], $addDesc, $addDate, 0, $addDownLink);
    $gameRepository->add($gameToAdd);
}

?>

<body>
    <?php require_once("../../templates/header.php") ?>
    <div class="content-body">
        <form action="./gamesCRUD.php" method="get">
            <?php
            $games = $gameRepository->getAll();

            $searchTerm = htmlspecialchars($_GET['searchTerm']);

            if (!empty($searchTerm)) {
                $tempGamesArray = array();
                foreach ($games as $game) {
                    if (str_contains(strtolower($game->gameName), strtolower($searchTerm))) {
                        $tempGamesArray[] = $game;
                    }
                }
                $games = $tempGamesArray;

                if (count($games) < 1) {
                    echo "No Results found with search term $searchTerm";
                    echo "<br><button onclick='window.location=./gamesCRUD.php'>Reset</button>";
                    die();
                }
            }
            $pageSize = htmlspecialchars($_GET['pageSize']);
            if (empty($pageSize)) {
                $pageSize = 10;
            }
            ?>
            <input type="number" min="1" max="100" name="pageSize" value="<?= $pageSize ?>" class="notrealinput">
            <input type="submit" value="Change Page Size" class="notrealinput">
            <?php

            $pageSize = (int)$pageSize;
            $pageAmount = count($games) % $pageSize == 0 ? count($games) / $pageSize : floor(count($games) / $pageSize) + 1;
            $currentPage = empty(htmlspecialchars($_GET['currentPage'])) ? 0 : (int)htmlspecialchars($_GET['currentPage']);
            for ($i = 0; $i < $pageAmount; $i++) :
            ?>
                <button type="submit" name="currentPage" value="<?= $i ?>" class="<?= $currentPage == $i ? 'pageSelected' : '' ?>"><?= $i + 1 ?></button>
            <?php endfor ?>
        </form>
        <form action="./gamesCRUD.php" method="get">
            <input type="text" name="searchTerm" value="<?= is_null($searchTerm) ? "" : $searchTerm ?>">
            <input type="submit" value="Search By Name" class="notrealinput">
        </form>
        <br>
        <form action="./gamesCRUD.php" method="post">
            <table class="godsTable">
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Thumbnail</th>
                    <th>Description</th>
                    <th>Released on</th>
                    <th>Is Disabled?</th>
                    <th>Link</th>
                </tr>
                <?php
                $gamesOnPage = array_slice($games, $currentPage == 0 ? 0 : $pageSize * $currentPage, $pageSize);
                for ($i = count($gamesOnPage) - 1; $i >= 0; $i--) :
                ?>
                    <tr>
                        <td>
                            <input type="text" value="<?= $gamesOnPage[$i]->gameName ?>" name="name<?= $i ?>">
                        </td>
                        <td>
                            <input type="number" value="<?= $gamesOnPage[$i]->price ?>" name="price<?= $i ?>">
                        </td>
                        <td>
                            <input type="hidden" value="<?= $gamesOnPage[$i]->thumbnail ?>" name="thumbnail<?= $i ?>">
                            <img src="../../resources/<?= $gamesOnPage[$i]->thumbnail ?>" alt="Game thumbnail" height="64px" width="86px">
                        </td>
                        <td>
                            <input type="text" value="<?= $gamesOnPage[$i]->description ?>" name="description<?= $i ?>">
                        </td>
                        <td>
                            <input type="date" value="<?= $gamesOnPage[$i]->releaseDate ?>" name="releaseDate<?= $i ?>">
                        </td>
                        <td>
                            <input type="checkbox" value="<?= $gamesOnPage[$i]->isDisabled ?>" name="isDisabled<?= $i ?>" <?= $gamesOnPage[$i]->isDisabled == 1 ? "checked" : "" ?>>
                        </td>
                        <td>
                            <input type="text" value="<?= $gamesOnPage[$i]->downloadLink ?>" name="downloadLink<?= $i ?>">
                        </td>
                    </tr>
                    <input type="hidden" value="<?= $gamesOnPage[$i]->gameId ?>" name="gameId<?= $i ?>">
                    <input type="hidden" name="gameId<?= $i ?>" value="<?= $gamesOnPage[$i]->gameId ?>">
                <?php endfor ?>
            </table>
            <input type="hidden" name="rowCounter" value="<?= count($gamesOnPage) ?>">
            <br>
            <input type="submit" value="Update Games">
        </form>
        <br>
        <br>
        <form action="./gamesCRUD.php" method="post" enctype="multipart/form-data">
            <table class="godsTable">
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Thumbnail</th>
                    <th>Price</th>
                    <th>Release date</th>
                    <th>Download Link</th>
                </tr>
                <tr>
                    <td>
                        <input type="text" name="addName" required>
                    </td>
                    <td>
                        <input type="text" name="addDesc" required>
                    </td>
                    <td>
                        <input type="file" name="addThumb" required>
                    </td>
                    <td>
                        <input type="number" name="addPrice" required>
                    </td>
                    <td>
                        <input type="date" name="addDate" required>
                    </td>
                    <td>
                        <input type="text" name="addDownLink" required>
                    </td>
                </tr>
            </table>
            <input type="submit" name="addGame" value="Create New Game" class="centerAddAccount">
        </form>
    </div>
    <?php require_once("../../templates/footer.php") ?>
</body>