<?php
require_once '../../config/database.php';
session_start();

if(!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

$pdo = getConnection();
$method = $_SERVER['REQUEST_METHOD'];

if($method === 'GET') {
    if(isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT p.*, f.nome as fornecedor_nome FROM produtos p LEFT JOIN fornecedores f ON p.fornecedor_id = f.id WHERE p.id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $stmt = $pdo->query("SELECT p.*, f.nome as fornecedor_nome FROM produtos p LEFT JOIN fornecedores f ON p.fornecedor_id = f.id ORDER BY p.nome");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

if($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, fornecedor_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$data['nome'], $data['descricao'], $data['preco'], $data['estoque'], $data['fornecedor_id'] ?: null]);
    echo json_encode(['success' => true]);
}

if($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, estoque=?, fornecedor_id=? WHERE id=?");
    $stmt->execute([$data['nome'], $data['descricao'], $data['preco'], $data['estoque'], $data['fornecedor_id'] ?: null, $data['id']]);
    echo json_encode(['success' => true]);
}

if($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("DELETE FROM produtos WHERE id=?");
    $stmt->execute([$data['id']]);
    echo json_encode(['success' => true]);
}