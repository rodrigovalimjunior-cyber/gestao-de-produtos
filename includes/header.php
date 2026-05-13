<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/gestao-produtos/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/gestao-produtos/index.php">
            <i class="bi bi-box-seam"></i> Gestão de Produtos
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if(isset($_SESSION['usuario_id'])): ?>
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/gestao-produtos/pages/produtos.php">
                        <i class="bi bi-box"></i> Produtos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/gestao-produtos/pages/fornecedores.php">
                        <i class="bi bi-truck"></i> Fornecedores
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/gestao-produtos/pages/cesta.php">
                        <i class="bi bi-cart"></i> Cesta
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link text-light">
                        <i class="bi bi-person"></i> <?php echo $_SESSION['usuario_nome']; ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/gestao-produtos/pages/logout.php">
                        <i class="bi bi-box-arrow-right"></i> Sair
                    </a>
                </li>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>
<div class="container mt-4"></div>
// verificacao de sessao
