<?php 
require_once("../../classes/SessionManager.php");

SessionManager::logout();
SessionManager::redir("./login.php");
?>