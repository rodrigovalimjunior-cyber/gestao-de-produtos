// Função para mostrar alertas
function showAlert(message, type = 'success') {
    const alert = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('#alerts').html(alert);
}

// Carregar fornecedores via AJAX
function loadFornecedores() {
    $.ajax({
        url: '/gestao-produtos/pages/ajax/fornecedores.php',
        method: 'GET',
        success: function(data) {
            const fornecedores = JSON.parse(data);
            let html = '';
            fornecedores.forEach(f => {
                html += `
                    <tr>
                        <td>${f.id}</td>
                        <td>${f.nome}</td>
                        <td>${f.email}</td>
                        <td>${f.telefone}</td>
                        <td>${f.endereco}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editFornecedor(${f.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteFornecedor(${f.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#fornecedores-tbody').html(html);
        }
    });
}

// Carregar produtos via AJAX
function loadProdutos() {
    $.ajax({
        url: '/gestao-produtos/pages/ajax/produtos.php',
        method: 'GET',
        success: function(data) {
            const produtos = JSON.parse(data);
            let html = '';
            produtos.forEach(p => {
                html += `
                    <tr>
                        <td>${p.id}</td>
                        <td>${p.nome}</td>
                        <td>${p.descricao}</td>
                        <td>R$ ${parseFloat(p.preco).toFixed(2)}</td>
                        <td>${p.estoque}</td>
                        <td>${p.fornecedor_nome || 'N/A'}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editProduto(${p.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="deleteProduto(${p.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
            });
            $('#produtos-tbody').html(html);
        }
    });
}

// Deletar fornecedor
function deleteFornecedor(id) {
    if(confirm('Deseja realmente excluir este fornecedor?')) {
        $.ajax({
            url: '/gestao-produtos/pages/ajax/fornecedores.php',
            method: 'DELETE',
            data: JSON.stringify({ id: id }),
            contentType: 'application/json',
            success: function(data) {
                const response = JSON.parse(data);
                if(response.success) {
                    showAlert('Fornecedor excluído com sucesso!');
                    loadFornecedores();
                }
            }
        });
    }
}

// Deletar produto
function deleteProduto(id) {
    if(confirm('Deseja realmente excluir este produto?')) {
        $.ajax({
            url: '/gestao-produtos/pages/ajax/produtos.php',
            method: 'DELETE',
            data: JSON.stringify({ id: id }),
            contentType: 'application/json',
            success: function(data) {
                const response = JSON.parse(data);
                if(response.success) {
                    showAlert('Produto excluído com sucesso!');
                    loadProdutos();
                }
            }
        });
    }
}