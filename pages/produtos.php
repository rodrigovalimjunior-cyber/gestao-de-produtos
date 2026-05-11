<?php
require_once '../includes/header.php';

if(!isset($_SESSION['usuario_id'])) {
    header('Location: /gestao-produtos/pages/login.php');
    exit;
}

$pdo = require_once '../config/database.php';
?>

<div id="alerts"></div>

<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <i class="bi bi-box"></i> Cadastrar Produto
    </div>
    <div class="card-body">
        <form id="formProduto">
            <input type="hidden" id="produto_id" value="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" id="produto_nome" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Fornecedor</label>
                    <select id="produto_fornecedor" class="form-select">
                        <option value="">Selecione um fornecedor</option>
                        <?php
                        $pdo = getConnection();
                        $stmt = $pdo->query("SELECT * FROM fornecedores ORDER BY nome");
                        $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        foreach($fornecedores as $f): ?>
                            <option value="<?php echo $f['id']; ?>"><?php echo $f['nome']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Preço</label>
                    <input type="number" id="produto_preco" class="form-control" step="0.01" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Estoque</label>
                    <input type="number" id="produto_estoque" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Descrição</label>
                    <input type="text" id="produto_descricao" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-dark">
                <i class="bi bi-save"></i> Salvar
            </button>
            <button type="button" class="btn btn-secondary" id="btnCancelarProduto" style="display:none" onclick="cancelarEdicaoProduto()">
                Cancelar
            </button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        <i class="bi bi-list"></i> Produtos Cadastrados
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Preço</th>
                        <th>Estoque</th>
                        <th>Fornecedor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="produtos-tbody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadProdutos();

    $('#formProduto').submit(function(e) {
        e.preventDefault();
        const id = $('#produto_id').val();
        const data = {
            nome: $('#produto_nome').val(),
            descricao: $('#produto_descricao').val(),
            preco: $('#produto_preco').val(),
            estoque: $('#produto_estoque').val(),
            fornecedor_id: $('#produto_fornecedor').val()
        };

        if(id) data.id = id;

        $.ajax({
            url: '/gestao-produtos/pages/ajax/produtos.php',
            method: id ? 'PUT' : 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                const res = JSON.parse(response);
                if(res.success) {
                    showAlert(id ? 'Produto atualizado!' : 'Produto cadastrado!');
                    $('#formProduto')[0].reset();
                    $('#produto_id').val('');
                    $('#btnCancelarProduto').hide();
                    loadProdutos();
                }
            }
        });
    });
});

function editProduto(id) {
    $.ajax({
        url: '/gestao-produtos/pages/ajax/produtos.php?id=' + id,
        method: 'GET',
        success: function(data) {
            const p = JSON.parse(data);
            $('#produto_id').val(p.id);
            $('#produto_nome').val(p.nome);
            $('#produto_descricao').val(p.descricao);
            $('#produto_preco').val(p.preco);
            $('#produto_estoque').val(p.estoque);
            $('#produto_fornecedor').val(p.fornecedor_id);
            $('#btnCancelarProduto').show();
            window.scrollTo(0, 0);
        }
    });
}

function cancelarEdicaoProduto() {
    $('#formProduto')[0].reset();
    $('#produto_id').val('');
    $('#btnCancelarProduto').hide();
}
</script>

<?php require_once '../includes/footer.php'; ?>