<?php
require_once("../../classes/SessionManager.php");
require_once("../../classes/AccountRepository.php");
$accountRepository = new AccountRepository();
?>
<div class="content-header">
    <a href="../main/index.php" class="homeLink"><h1>Vapor</h1></a>
    <div class="flex-grow"></div>
    <div class="content-header-top-right-form">
        <?php if (SessionManager::isAwayFromHome()) : ?>
            <button type="button" onclick="window.location = '../main/index.php'">Back To Home</button>
        <?php endif ?>
        <?php if (SessionManager::isAdmin()) : ?>
            <button type="button" onclick="window.location = '../admin/admin.php'">Become Gaben</button>
        <?php endif ?>
        <button type="button" onclick="download()">Download</button>
        <button type="button" onclick="window.location = '../signup/logout.php';" class="header-top-right-logout-butt">Logout</button>
        <button type="button" onclick="window.location = '../main/accountSettings.php'">Edit Account</button>
        <button type="button" onclick="window.location = '../main/library.php'">Library</button>
    </div>
    <script>
        function download() {
            window.open('https://github.com/Sorry4Nothing/Vapor/releases', '_blank');
        }
    </script>
    <div class="bg-animation">    
    <ul class="bg-animation-box">
      <li class="circle-box"></li>
            <li class="corners-box-20"></li>
            <li class="circle-box"></li>
            <li class="corners-box-20"></li>
            <li></li>
            <li class="corners-box-35"></li>
            <li class="circle-box"></li>
      <li></li>   
        </ul>
    </div>
</div>
<br>