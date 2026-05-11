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
        $stmt = $pdo->prepare("SELECT * FROM fornecedores WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        $stmt = $pdo->query("SELECT * FROM fornecedores ORDER BY nome");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}

if($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO fornecedores (nome, email, telefone, endereco) VALUES (?, ?, ?, ?)");
    $stmt->execute([$data['nome'], $data['email'], $data['telefone'], $data['endereco']]);
    echo json_encode(['success' => true]);
}

if($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("UPDATE fornecedores SET nome=?, email=?, telefone=?, endereco=? WHERE id=?");
    $stmt->execute([$data['nome'], $data['email'], $data['telefone'], $data['endereco'], $data['id']]);
    echo json_encode(['success' => true]);
}

if($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("DELETE FROM fornecedores WHERE id=?");
    $stmt->execute([$data['id']]);
    echo json_encode(['success' => true]);
}