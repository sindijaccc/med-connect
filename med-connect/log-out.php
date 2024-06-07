<?php
include "db.php";
session_start();
unset($_SESSION['id']);
session_destroy();
header("Location: ../med-connect/index.php");
exit();
?>
