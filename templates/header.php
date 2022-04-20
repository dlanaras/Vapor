<?php
require_once("../classes/AccountRepository.php");
$accountRepository = new AccountRepository();
?>
<div class="content-header">
    <h1>Vapor</h1>
    <div class="flex-grow"></div>
    <div class="content-header-top-right-form">
        <?php if($accountRepository->isAdmin()): ?>
             <button type="button" onclick="window.location = './admin.php'">Become Gaben</button>
        <?php endif ?>
        <button type="button" onclick="download()">Download</button>
        <button type="button" onclick="logout()" class="header-top-right-logout-butt">Logout</button>
    </div>
    <script>
        function download() {
            window.open('https://github.com/Sorry4Nothing/Vapor/releases', '_blank');
        }

        function logout() {
            window.location = './logout.php';
        }
    </script>
</div>