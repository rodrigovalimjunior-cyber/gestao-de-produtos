<?php
session_start();
session_destroy();
header('Location: /gestao-produtos/pages/login.php');
exit;
?>