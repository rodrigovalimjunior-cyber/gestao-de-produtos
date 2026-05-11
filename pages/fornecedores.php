<?php
require_once '../includes/header.php';

if(!isset($_SESSION['usuario_id'])) {
    header('Location: /gestao-produtos/pages/login.php');
    exit;
}
?>

<div id="alerts"></div>

<div class="card mb-4">
    <div class="card-header bg-dark text-white">
        <i class="bi bi-truck"></i> Cadastrar Fornecedor
    </div>
    <div class="card-body">
        <form id="formFornecedor">
            <input type="hidden" id="fornecedor_id" value="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" id="fornecedor_nome" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" id="fornecedor_email" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telefone</label>
                    <input type="text" id="fornecedor_telefone" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Endereço</label>
                    <input type="text" id="fornecedor_endereco" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-dark">
                <i class="bi bi-save"></i> Salvar
            </button>
            <button type="button" class="btn btn-secondary" id="btnCancelar" style="display:none" onclick="cancelarEdicao()">
                Cancelar
            </button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        <i class="bi bi-list"></i> Fornecedores Cadastrados
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Endereço</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="fornecedores-tbody">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    loadFornecedores();

    $('#formFornecedor').submit(function(e) {
        e.preventDefault();
        const id = $('#fornecedor_id').val();
        const data = {
            nome: $('#fornecedor_nome').val(),
            email: $('#fornecedor_email').val(),
            telefone: $('#fornecedor_telefone').val(),
            endereco: $('#fornecedor_endereco').val()
        };

        if(id) data.id = id;

        $.ajax({
            url: '/gestao-produtos/pages/ajax/fornecedores.php',
            method: id ? 'PUT' : 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function(response) {
                const res = JSON.parse(response);
                if(res.success) {
                    showAlert(id ? 'Fornecedor atualizado!' : 'Fornecedor cadastrado!');
                    $('#formFornecedor')[0].reset();
                    $('#fornecedor_id').val('');
                    $('#btnCancelar').hide();
                    loadFornecedores();
                }
            }
        });
    });
});

function editFornecedor(id) {
    $.ajax({
        url: '/gestao-produtos/pages/ajax/fornecedores.php?id=' + id,
        method: 'GET',
        success: function(data) {
            const f = JSON.parse(data);
            $('#fornecedor_id').val(f.id);
            $('#fornecedor_nome').val(f.nome);
            $('#fornecedor_email').val(f.email);
            $('#fornecedor_telefone').val(f.telefone);
            $('#fornecedor_endereco').val(f.endereco);
            $('#btnCancelar').show();
            window.scrollTo(0, 0);
        }
    });
}

function cancelarEdicao() {
    $('#formFornecedor')[0].reset();
    $('#fornecedor_id').val('');
    $('#btnCancelar').hide();
}
</script>

<?php require_once '../includes/footer.php'; ?>