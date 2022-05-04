<?php
require_once("../../classes/SessionManager.php");
if (!SessionManager::isLoggedIn()) SessionManager::redir("/");
?>