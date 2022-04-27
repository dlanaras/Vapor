<!DOCTYPE html>
<html lang="en">
<?php
require_once("../../classes/AccountRepository.php");
require_once("../../classes/SessionManager.php");
$accountRepo = new AccountRepository();
require_once("../../templates/mustBeLogedIn.php");
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit yourself</title>
    <link rel="stylesheet" href="../../styles/style.css" />
</head>

<body>
    <div class="content">
        <?php require_once("../../templates/header.php") ?>
        <div class="contentBody">
            <?php
            $accountId = htmlspecialchars($_POST['accountId']);
            $username = htmlspecialchars($_POST['username']);
            $firstName = htmlspecialchars($_POST['firstName']);
            $lastName = htmlspecialchars($_POST['lastName']);
            $email = htmlspecialchars($_POST['email']);
            $biography = htmlspecialchars($_POST['biography']);
            $password = htmlspecialchars($_POST['password']);
            $currentPassword = htmlspecialchars($_POST['currentPassword']);

            try {
                if ($accountId === null) throw new Exception("Something went wrong, please try again");
                if ($currentPassword === null) throw new Exception("Something went wrong, please try again");
                if (str_replace(" ", "", $username) === "") throw new Exception("Name cannot be empty");
                if (str_replace(" ", "", $email) === "") throw new Exception("Email cannot be empty");
                if (str_replace(" ", "", $password) === "") throw new Exception("Password cannot be empty");

                $updatedAccount = new Account($password, $accountId, $username, $firstName, $lastName, false, $biography, $email, false);
                $currentPassword === $password ? $accountRepo->update($updatedAccount) : $accountRepo->updateWithPassword($updatedAccount);
                if($_SESSION["username"] !== $username) {
                    $_SESSION["username"] = $username;
                }
                SessionManager::redir("/");
            } catch (Exception $e) {
                echo $e->getMessage();
            }

            $currentAccount = $accountRepo->getByUserName($_SESSION['username']);
            ?>
            <form action="./accountSettings.php" method="post" class="flex-column">
                <input type="number" value="<?= $currentAccount->accountId ?>" name="accountId" hidden>
                <label for="User Name">
                    <p>User Name</p>
                    <input type="text" name="username" value="<?= $currentAccount->userName ?>" required>
                </label>
                <label for="First Name">
                    <p>First Name</p>
                    <input type="text" name="firstName" value="<?= $currentAccount->firstName ?>">
                </label>
                <label for="Last Name">
                    <p>Last Name</p>
                    <input type="text" name="lastName" value="<?= $currentAccount->lastName ?>">
                </label>
                <label for="E-Mail">
                    <p>E-Mail</p>
                    <input type="text" name="email" value="<?= $currentAccount->email ?>" required>
                </label>
                <label for="Biography">
                    <p>Biography</p>
                    <input type="text" name="biography" value="<?= $currentAccount->biography ?>">
                </label>
                <label for="Is Admin">
                    <p>Is a God?</p>
                    <input type="text" value="<?= $currentAccount->isAdmin == 0 ? 'No' : 'Yes' ?>" disabled>
                </label>
                <label for="Password">
                    <p>Password</p>
                    <input type="password" name="password" value="<?= $currentAccount->password ?>">
                    <input type="hidden" name="currentPassword" value="<?= $currentAccount->password ?>">
                </label>
                <label for="Is Banned">
                    <p>Is banned?</p>
                    <input type="text" value="<?= $currentAccount->isBanned == 0 ? 'No' : 'Yes' ?>" disabled>
                </label>
                <div>
                    <button type="button" onclick="window.location = '/'">Cancel</button>
                    <input type="submit" value="Save Changes">
                </div>
            </form>
        </div>
        <?php require_once("../../templates/footer.php") ?>
    </div>
</body>
</html>