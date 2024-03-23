<?php
session_start();
unset($_SESSION['museum_userid']);
header("Location: index.php");
exit;
?>