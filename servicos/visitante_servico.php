<?php
// SERVIÇO DE VISITANTES: todas as operações do banco da tabela "visitantes".

// READ - lista todos os visitantes e conta quantas medições cada um fez
function listarVisitantes($pdo) {
    $sql = "SELECT v.id, v.nome, v.email, v.instituicao,
                   (SELECT COUNT(*) FROM medicoes m WHERE m.visitante_id = v.id) AS total
            FROM visitantes v ORDER BY v.id DESC";
    return $pdo->query($sql)->fetchAll();
}

// READ - busca um visitante pelo id
function buscarVisitante($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM visitantes WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// CREATE - cadastra um novo visitante
function criarVisitante($pdo, $nome, $email, $instituicao) {
    $stmt = $pdo->prepare("INSERT INTO visitantes (nome, email, instituicao) VALUES (?, ?, ?)");
    $stmt->execute([$nome, $email, $instituicao]);
}

// UPDATE - altera os dados de um visitante
function atualizarVisitante($pdo, $id, $nome, $email, $instituicao) {
    $stmt = $pdo->prepare("UPDATE visitantes SET nome = ?, email = ?, instituicao = ? WHERE id = ?");
    $stmt->execute([$nome, $email, $instituicao, $id]);
}

// DELETE - apaga um visitante (as medições dele ficam "sem identificação")
function excluirVisitante($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM visitantes WHERE id = ?");
    $stmt->execute([$id]);
}

// Confere se os dados são válidos. Devolve uma lista de erros (vazia = tudo certo)
function validarVisitante($nome, $email) {
    $erros = [];
    if (strlen($nome) < 3) $erros[] = "Escreva o nome com pelo menos 3 letras.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = "Escreva um e-mail válido.";
    return $erros;
}
