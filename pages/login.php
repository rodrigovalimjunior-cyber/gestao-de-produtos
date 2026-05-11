<?php
require_once '../config/create_tables.php';
createDatabase();
require_once '../config/database.php';

session_start();

if(isset($_SESSION['usuario_id'])) {
    header('Location: /gestao-produtos/pages/produtos.php');
    exit;
}

$erro = '';
$sucesso = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST['action']) && $_POST['action'] === 'register') {
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $senha = hash('sha256', $_POST['senha']);

        try {
            $pdo = getConnection();
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
            $stmt->execute([$nome, $email, $senha]);
            $sucesso = 'Cadastro realizado com sucesso! Faça login.';
        } catch(PDOException $e) {
            $erro = 'Email já cadastrado!';
        }
    } else {
        $email = trim($_POST['email']);
        $senha = hash('sha256', $_POST['senha']);

        try {
            $pdo = getConnection();
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? AND senha = ?");
            $stmt->execute([$email, $senha]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if($usuario) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                header('Location: /gestao-produtos/pages/produtos.php');
                exit;
            } else {
                $erro = 'Email ou senha incorretos!';
            }
        } catch(PDOException $e) {
            $erro = 'Erro ao fazer login!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Produtos - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/gestao-produtos/assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="login-container">
    <div class="card">
        <div class="card-header bg-dark text-white text-center py-3">
            <h4><i class="bi bi-box-seam"></i> Gestão de Produtos</h4>
        </div>
        <div class="card-body p-4">
            <?php if($erro): ?>
                <div class="alert alert-danger"><?php echo $erro; ?></div>
            <?php endif; ?>
            <?php if($sucesso): ?>
                <div class="alert alert-success"><?php echo $sucesso; ?></div>
            <?php endif; ?>

            <ul class="nav nav-tabs mb-3" id="authTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#login">Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#register">Cadastrar</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="login">
                    <form method="POST">
                        <input type="hidden" name="action" value="login">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Entrar
                        </button>
                    </form>
                </div>
                <div class="tab-pane fade" id="register">
                    <form method="POST">
                        <input type="hidden" name="action" value="register">
                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100">
                            <i class="bi bi-person-plus"></i> Cadastrar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>