<?php

use function PHPUnit\Framework\containsEqual;
use function PHPUnit\Framework\containsIdentical;
use function PHPUnit\Framework\containsOnly;

require_once("../../classes/GameRepository.php");
require_once("../../classes/AchievementRepository.php");
require_once("../../templates/mustBeLogedIn.php");
require_once("../../templates/mustBeAdmin.php");
require_once("../../models/Achievement.class.php");
require_once("../../templates/uploadFile.php");
$achievementRepository = new AchievementRepository();
$gameRepository = new GameRepository();
$gameNamesToIds = $gameRepository->getMapOfGameNamesAndIds();
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
$chosenGame = htmlspecialchars($_GET['chosenGame']);

$rowCounter = ((int) htmlspecialchars($_POST['rowCounter'])) - 1;

if (isset($rowCounter)) {
    while ($rowCounter >= 0) {
        $name = htmlspecialchars($_POST["name$rowCounter"]);
        $description = htmlspecialchars($_POST["description$rowCounter"]);
        $thumbnail = htmlspecialchars($_POST["thumbnail$rowCounter"]);
        $isDisabled = (int) isset($_POST["isDisabled$rowCounter"]);
        $gameId = (int) htmlspecialchars($_POST["gameId$rowCounter"]);
        $achievementId = (int) htmlspecialchars($_POST["achievementId$rowCounter"]);

        $newAchievement = new Achievement($achievementId, $name, $description, $isDisabled, $thumbnail, $gameId);

        $achievementRepository->update($newAchievement);

        $rowCounter--;
    }
}

$addName = htmlspecialchars($_POST['addName']);
$addDesc = htmlspecialchars($_POST['addDesc']);
$addGame = (int) htmlspecialchars($_POST['addGame']);

if (isset($_POST['addAchievement']) && isset($_FILES['addThumb']) && !empty($addName) && !empty($addDesc) && !empty($addGame)) {
    uploadFileAndReturnName("addThumb");
    $achievementToAdd = new Achievement(0,$addName,$addDesc,0,$_FILES['addThumb']["name"],$addGame);
    $achievementRepository->add($achievementToAdd);
}

?>

<body>
    <?php require_once("../../templates/header.php") ?>
    <div class="content-body">
        <?php if (empty($chosenGame)) : ?>
            <form action="./achievementCRUD.php" method="get">
                <select name="chosenGame" class="notrealinput">
                    <?php foreach ($gameNamesToIds as $id => $gameName) : ?>
                        <option value="<?= $id ?>"><?= $gameName ?></option>
                    <?php endforeach ?>
                </select>
                <input type="submit" value="Select Game" class="notrealinput">
            </form>
        <?php else : ?>
            <form action="./achievementCRUD.php" method="get">
                <?php                
                $achievements = $achievementRepository->getAllAchievementsOfGame($chosenGame);

                $searchTerm = htmlspecialchars($_GET['searchTerm']);

                if (!empty($searchTerm)) {
                    $newAchievementArray = array();
                    foreach ($achievements as $achievement) {
                        if(str_contains(strtolower($achievement->achievementName), strtolower($searchTerm))) {
                            $newAchievementArray[] = $achievement;
                        }
                    }
                    $achievements = $newAchievementArray;

                    if(count($achievements) < 1) {
                        echo "No Results found with search term $searchTerm";
                        echo "<br><button onclick='window.location=./achievementsCRUd.php'>Reset</button>";
                        die();
                    }
                }

                $pageSize = htmlspecialchars($_GET['pageSize']);
                if (empty($pageSize)) {
                    $pageSize = 10;
                }
                ?>
                <input type="hidden" name="chosenGame" value="<?= $chosenGame ?>">
                <input type="number" min="1" max="100" name="pageSize" value="<?= $pageSize ?>" class="notrealinput">
                <input type="submit" value="Change Page Size" class="notrealinput">
                <?php

                $pageSize = (int)$pageSize;
                $pageAmount = count($achievements) % $pageSize == 0 ? count($achievements) / $pageSize : floor(count($achievements) / $pageSize) + 1;
                $currentPage = empty(htmlspecialchars($_GET['currentPage'])) ? 0 : (int)htmlspecialchars($_GET['currentPage']);
                for ($i = 0; $i < $pageAmount; $i++) :
                ?>
                    <button type="submit" name="currentPage" value="<?= $i ?>" class="<?= $currentPage == $i ? 'pageSelected' : '' ?>"><?= $i + 1 ?></button>
                <?php endfor ?>
            </form>
            <form action="./achievementCRUD.php" method="get">
                    <input type="text" name="searchTerm" value="<?= is_null($searchTerm) ? "" : $searchTerm ?>" class="notrealinput">
                    <input type="hidden" value="<?= $chosenGame ?>" name="chosenGame">
                    <input type="submit" value="Search By Name" class="notrealinput">
                </form>
            <form action="./achievementCRUD.php" method="post">
                <br>
                <table class="godsTable">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Thumbnail</th>
                        <th>Is Disabled?</th>
                        <th>Game</th>
                    </tr>
                    <?php
                    $achievementsonPage = array_slice($achievements, $currentPage == 0 ? 0 : $pageSize * $currentPage, $pageSize);
                    for ($i = count($achievementsonPage) - 1; $i >= 0; $i--) :
                    ?>
                        <tr>
                            <td>
                                <input type="text" value="<?= $achievementsonPage[$i]->achievementName ?>" name="name<?= $i ?>">
                            </td>
                            <td>
                                <input type="text" value="<?= $achievementsonPage[$i]->description ?>" name="description<?= $i ?>">
                            </td>
                            <td>
                                <input type="hidden" value="<?= $achievementsonPage[$i]->thumbnail ?>" name="thumbnail<?= $i ?>">
                                <img src="../../resources/<?= $achievementsonPage[$i]->thumbnail ?>" alt="Achievement thumbnail" height="32px" width="32px">
                            </td>
                            <td>
                                <input type="checkbox" value="<?= $achievementsonPage[$i]->isDisabled ?>" name="isDisabled<?= $i ?>" <?= $achievementsonPage[$i]->isDisabled == 1 ? "checked" : "" ?>>
                            </td>
                            <td>
                                <input type="hidden" value="<?= $achievementsonPage[$i]->gameId ?>" name="gameId<?= $i ?>">
                                <?= $gameNamesToIds[$achievementsonPage[$i]->gameId] ?>
                            </td>
                        </tr>
                        <input type="hidden" name="achievementId<?= $i ?>" value="<?= $achievementsonPage[$i]->achievementId ?>">
                    <?php endfor ?>
                </table>
                <input type="hidden" name="rowCounter" value="<?= count($achievementsonPage) ?>">
                <br>
                <input type="submit" value="Update Achievements" class="notrealinput">
            </form>
            <br>
            <br>
            <form action="./achievementCRUD.php" method="post" enctype="multipart/form-data">
                <table class="godsTable">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Thumbnail</th>
                        <th>Game</th>
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
                            <select name="addGame" class="notrealinput">
                                <?php foreach ($gameNamesToIds as $id => $gameName) : ?>
                                    <option value="<?= $id ?>"><?= $gameName ?></option>
                                <?php endforeach ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="submit" name="addAchievement" value="Create New Achievement" class="centerAddAccount">
            </form>
            <br>
            <form action="./achievementCRUD.php" action="get">
                <input type="hidden" value="<?= $chosenGame ?>" name="chosenGame">
                <input type="submit" value="Reset"  class="notrealinput">
            </form>
            <form action="./achievementCRUD.php" action="get">
                <input type="submit" value="Go Back" class="notrealinput">
            </form>
        <?php endif ?>
    </div>
    <?php require_once("../../templates/footer.php") ?>
</body>

</html>