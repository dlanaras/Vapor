<?php
if($_SERVER['REQUEST_URI'] === "/") {
    header("Location: ./views/index.php");
}

?>