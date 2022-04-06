<?php
if($_SERVER['REQUEST_URI'] === "/") {
    header("Location: ./src/index.php");
}

?>