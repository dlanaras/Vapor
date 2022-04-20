<?php
require_once("../classes/SessionManager.php");
require_once("../classes/AccountRepository.php");
require_once("../classes/DbConnHandler.php");

$accountRepository = new AccountRepository(DbConnHandler::getConnection());
//TODO: add banhammer button
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="../styles/style.css" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gods accounts</title>
</head>

<body>
    <div class="content">
        <?php require_once("../templates/header.php") ?>
        <div class="content-body">
            <h1>Gods accounts</h1>
            <form action="./accountsCRUD.php" method="get">

                <?php
                $banId = htmlspecialchars($_GET['banId']);

                if (!empty($banId)) {
                    $accountRepository->disable($banId);
                }
                $accounts = $accountRepository->getAll();
                $pageSize = htmlspecialchars($_GET['pageSize']);
                if (empty($pageSize)) {
                    $pageSize = 10;
                }
                ?>
                <input type="number" min="1" max="100" name="pageSize" value="<?= $pageSize ?>">
                <?php
                $pageSize = (int)$pageSize;
                $pageAmount = count($accounts) % $pageSize == 0 ? count($accounts) / $pageSize : count($accounts) / $pageSize + 1;
                $currentPage = empty(htmlspecialchars($_GET['currentPage'])) ? 0 : (int)htmlspecialchars($_GET['currentPage']);
                for ($i = 0; $i < $pageAmount; $i++) {
                ?>
                    <button type="submit" name="currentPage" value="<?= $i ?>" class="<?=empty($currentPage) && $currentPage == $i ? '' :  'pageSelected'?>"><?= $i + 1 ?></button>
                <?php
                }
                ?>
                <input type="submit">
            </form>
            <table>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>FirstName</th>
                    <th>LastName</th>
                    <th>biography</th>
                    <th>VACBan?</th>
                    <th>IsGod?</th>
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
                        <td><?= $account->isAdmin === 1 ? "true" : "false" ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </div>
        <?php require_once("../templates/footer.php") ?>
    </div>
</body>

</html>