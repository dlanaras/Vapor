<?php

use phpDocumentor\Reflection\Types\Boolean;

require_once("../../classes/SessionManager.php");
require_once("../../classes/AccountRepository.php");
require_once("../../classes/DbConnHandler.php");
require_once("../../templates/mustBeLogedIn.php");
require_once("../../templates/mustBeAdmin.php");

$accountRepository = new AccountRepository(DbConnHandler::getConnection());
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../../styles/style.css" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gods accounts</title>
    <link rel="stylesheet" href="../../styles/style.css" />
</head>

<body>
    <div class="content">
        <?php require_once("../../templates/header.php") ?>
        <div class="content-body">
            <h1>Gods accounts</h1>
            <div class="formSeperateMiddle">
                <form action="./accountsCRUD.php" method="get">
                    <?php
                    $banId = htmlspecialchars($_POST['banId']);

                    if (!empty($banId)) {
                        $accountRepository->disable($banId);
                    }

                    $ascendId = htmlspecialchars($_POST['ascendId']);

                    if (!empty($ascendId)) {
                        $accountRepository->setAdmin($ascendId);
                    }

                    $dbUser = htmlspecialchars($_POST['dbuser']);
                    $dbPassword = htmlspecialchars($_POST['password']);
                    $dbFirstName = htmlspecialchars($_POST['firstName']);
                    $dbLastName = htmlspecialchars($_POST['lastName']);
                    $dbEmail = htmlspecialchars($_POST['email']);
                    $dbIsAdmin = (Boolean) htmlspecialchars($_POST['isAdmin']);

                    if (!empty($dbUser) && !empty($dbPassword) && strlen($dbPassword) > 2 && !empty($dbFirstName) && !empty($dbLastName) && !empty($dbEmail)) {
                        $newAcc = new Account($dbPassword, 0, $dbUser, $dbFirstName, $dbLastName, $dbIsAdmin, "", $dbEmail, false);
                        $accountRepository->add($newAcc);
                    }

                    $accounts = $accountRepository->getAll();

                    $searchTerm = htmlspecialchars($_GET['searchTerm']);
                    if (!empty($searchTerm)) {
                        foreach ($accounts as $account) {
                            if(strtolower($account->userName) === strtolower($searchTerm)) {
                                $accounts = array();
                                $accounts = [$account];
                                break;
                            }
                        }

                        if(count($accounts) > 1) {
                            echo "No Results found with search term $searchterm";
                            echo "<button onclick='window.location=./accountsCRUD.php'>Reset</button>";
                            die();
                        }
                    }

                    $pageSize = htmlspecialchars($_GET['pageSize']);
                    if (empty($pageSize)) {
                        $pageSize = 10;
                    }
                    ?>
                    <input type="number" min="1" max="100" name="pageSize" value="<?= $pageSize ?>">
                    <input type="submit" value="Change Page Size">
                    <?php
                    $pageSize = (int)$pageSize;
                    $pageAmount = count($accounts) % $pageSize == 0 ? count($accounts) / $pageSize : floor(count($accounts) / $pageSize) + 1;
                    $currentPage = empty(htmlspecialchars($_GET['currentPage'])) ? 0 : (int)htmlspecialchars($_GET['currentPage']);
                    for ($i = 0; $i < $pageAmount; $i++) :
                    ?>
                        <button type="submit" name="currentPage" value="<?= $i ?>" class="<?= $currentPage == $i ? 'pageSelected' : '' ?>"><?= $i + 1 ?></button>
                    <?php endfor ?>
                </form>
                <form action="./accountsCRUD.php" method="get">
                    <input type="text" name="searchTerm" value="<?= is_null($searchTerm) ? "" : $searchTerm ?>">
                    <input type="submit" value="Search By Name">
                </form>
            </div>
            <br>
            <table class="godsTable">
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>FirstName</th>
                    <th>LastName</th>
                    <th>biography</th>
                    <th>VACBan?</th>
                    <th>IsGod?</th>
                    <th></th>
                </tr>
                <?php
                $accountsOnPage = array_slice($accounts, $currentPage == 0 ? 0 : $pageSize * $currentPage, $pageSize);

                foreach ($accountsOnPage as $account) {
                ?>
                    <tr>
                        <td><?= $account->userName ?></td>
                        <td><?= $account->email ?></td>
                        <td><?= $account->firstName ?></td>
                        <td><?= $account->lastName ?></td>
                        <td><?= $account->biography ?></td>
                        <td>
                            <?php if (!$account->isBanned) {
                            ?>
                                <form action="./accountsCRUD.php" method="post">
                                    <input type="hidden" name="banId" value="<?= $account->accountId ?>">
                                    <button type="submit">Bring Wrath upon this damned soul</button>
                                </form>
                            <?php
                            } else {
                                echo "Already VACBanned";
                            }
                            ?>
                        </td>
                        <?php if ($account->isAdmin == 1) : ?>
                            <td>true</td>
                        <?php else : ?>
                            <td>false</td>
                            <td>
                                <form action="./accountsCRUD.php" method="post">
                                    <input type="hidden" name="ascendId" value="<?= $account->accountId ?>">
                                    <button type="submit">ASCEND</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php
                }
                ?>
            </table>
            <br>
            <br>
            <form action="./accountsCRUD.php" method="post">
                <table class="godsTable">
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>IsGod?</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" name="firstName" required>
                        </td>
                        <td>
                            <input type="text" name="lastName" required>
                        </td>
                        <td>
                            <input type="email" name="email" required>
                        </td>
                        <td>
                            <input type="text" name="dbuser" minlength="3" required>
                        </td>
                        <td>
                            <input type="password" name="password" required>
                        </td>
                        <td>
                            <input type="checkbox" name="isAdmin">
                        </td>
                    </tr>
                </table>
                <input type="submit" name="addAccount" value="Create New Account" class="centerAddAccount">
            </form>
        </div>
        <?php require_once("../../templates/footer.php") ?>
    </div>
</body>

</html>