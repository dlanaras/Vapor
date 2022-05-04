<?php
require_once("../../classes/SessionManager.php");
if (!SessionManager::isAdmin()) SessionManager::redir("/");
?>