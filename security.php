<?php

session_start();
if (empty($_SESSION['role'])) {
    header("location:index.php");
}
?>