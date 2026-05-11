<?php
require_once 'config/create_tables.php';
createDatabase();
require_once 'includes/header.php';

if(isset($_SESSION['usuario_id'])) {
    header('Location: /gestao-produtos/pages/produtos.php');
    exit;
} else {
    header('Location: /gestao-produtos/pages/login.php');
    exit;
}
?>