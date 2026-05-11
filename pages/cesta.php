<?php
require_once '../includes/header.php';

if(!isset($_SESSION['usuario_id'])) {
    header('Location: /gestao-produtos/pages/login.php');
    exit;
}

$pdo = getConnection();
$usuario_id = $_SESSION['usuario_id'];

// Busca produtos na cesta do usuário
$stmt = $pdo->prepare("
    SELECT p.*, c.id as cesta_id 
    FROM cesta c 
    JOIN produtos p ON c.produto_id = p.id 
    WHERE c.usuario_id = ?
");
$stmt->execute([$usuario_id]);
$cesta = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca todos os produtos disponíveis
$stmt = $pdo->query("SELECT * FROM produtos ORDER BY nome");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcula total
$total = array_sum(array_column($cesta, 'preco'));
$quantidade = count($cesta);

// Adicionar produto na cesta
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if($_POST['action'] === 'adicionar') {
        $produtos_selecionados = $_POST['produtos'] ?? [];
        
        if(empty($produtos_selecionados)) {
            $erro = 'Selecione pelo menos um produto!';
        } else {
            foreach($produtos_selecionados as $produto_id) {
                // Verifica se já está na cesta
                $stmt = $pdo->prepare("SELECT id FROM cesta WHERE usuario_id = ? AND produto_id = ?");
                $stmt->execute([$usuario_id, $produto_id]);
                if(!$stmt->fetch()) {
                    $stmt = $pdo->prepare("INSERT INTO cesta (usuario_id, produto_id) VALUES (?, ?)");
                    $stmt->execute([$usuario_id, $produto_id]);
                }
            }
            header('Location: /gestao-produtos/pages/cesta.php');
            exit;
        }
    }

    if($_POST['action'] === 'remover') {
        $stmt = $pdo->prepare("DELETE FROM cesta WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$_POST['cesta_id'], $usuario_id]);
        header('Location: /gestao-produtos/pages/cesta.php');
        exit;
    }

    if($_POST['action'] === 'limpar') {
        $stmt = $pdo->prepare("DELETE FROM cesta WHERE usuario_id = ?");
        $stmt->execute([$usuario_id]);
        header('Location: /gestao-produtos/pages/cesta.php');
        exit;
    }
}
?>

<div id="alerts"></div>

<?php if(isset($erro)): ?>
    <div class="alert alert-danger"><?php echo $erro; ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-box"></i> Selecionar Produtos
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="action" value="adicionar">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Selecionar</th>
                                    <th>Nome</th>
                                    <th>Descrição</th>
                                    <th>Preço</th>
                                    <th>Estoque</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($produtos as $p): ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="produtos[]" value="<?php echo $p['id']; ?>" class="form-check-input produto-check">
                                    </td>
                                    <td><?php echo $p['nome']; ?></td>
                                    <td><?php echo $p['descricao']; ?></td>
                                    <td>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></td>
                                    <td><?php echo $p['estoque']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-dark" id="btnAdicionar" disabled>
                        <i class="bi bi-cart-plus"></i> Adicionar à Cesta
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-cart"></i> Minha Cesta
            </div>
            <div class="card-body">
                <?php if(empty($cesta)): ?>
                    <p class="text-muted text-center">Cesta vazia</p>
                <?php else: ?>
                    <form method="POST">
                        <input type="hidden" name="action" value="limpar">
                        <ul class="list-group mb-3">
                            <?php foreach($cesta as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo $item['nome']; ?>
                                <div>
                                    <span class="badge bg-dark">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></span>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="action" value="remover">
                                        <input type="hidden" name="cesta_id" value="<?php echo $item['cesta_id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger ms-1">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Total de produtos:</strong>
                            <span><?php echo $quantidade; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Valor total:</strong>
                            <span class="text-success fw-bold">R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-trash"></i> Limpar Cesta
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.produto-check').change(function() {
        const checked = $('.produto-check:checked').length;
        $('#btnAdicionar').prop('disabled', checked === 0);
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>