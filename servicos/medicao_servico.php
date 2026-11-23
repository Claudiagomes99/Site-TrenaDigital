<?php
// SERVIÇO DE MEDIÇÕES: todas as operações do banco da tabela "medicoes".
// d1 e d2 são as duas distâncias que a trena guarda; o ângulo vem do MPU6050.

// READ - lista as medições junto com o nome do visitante (JOIN)
function listarMedicoes($pdo) {
    $sql = "SELECT m.*, v.nome AS visitante
            FROM medicoes m LEFT JOIN visitantes v ON v.id = m.visitante_id
            ORDER BY m.id DESC";
    return $pdo->query($sql)->fetchAll();
}

// READ - busca uma medição pelo id
function buscarMedicao($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM medicoes WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// CREATE - registra uma nova medição (pelo formulário = origem "manual")
function criarMedicao($pdo, $visitante_id, $d1, $d2, $angulo, $eixo, $observacao) {
    $stmt = $pdo->prepare("INSERT INTO medicoes (visitante_id, d1_cm, d2_cm, angulo_graus, eixo, observacao) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$visitante_id, $d1, $d2, $angulo, $eixo, $observacao]);
}

// UPDATE - altera uma medição
function atualizarMedicao($pdo, $id, $visitante_id, $d1, $d2, $angulo, $eixo, $observacao) {
    $stmt = $pdo->prepare("UPDATE medicoes SET visitante_id = ?, d1_cm = ?, d2_cm = ?, angulo_graus = ?, eixo = ?, observacao = ? WHERE id = ?");
    $stmt->execute([$visitante_id, $d1, $d2, $angulo, $eixo, $observacao, $id]);
}

// DELETE - apaga uma medição
function excluirMedicao($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM medicoes WHERE id = ?");
    $stmt->execute([$id]);
}

// Usado pela api.php: salva a leitura que veio do Arduino (origem "dispositivo")
function criarMedicaoDoDispositivo($pdo, $d1, $d2, $angulo, $eixo) {
    $stmt = $pdo->prepare("INSERT INTO medicoes (d1_cm, d2_cm, angulo_graus, eixo, origem) VALUES (?, ?, ?, ?, 'dispositivo')");
    $stmt->execute([$d1, $d2, $angulo, $eixo]);
}

// Confere se os dados são válidos. Os limites seguem o aparelho:
// HC-SR04 mede de 2 a 400 cm e o código mostra o ângulo em valor absoluto (0 a 90).
function validarMedicao($d1, $d2, $angulo, $eixo) {
    $erros = [];
    if (!is_numeric($d1) || $d1 < 2 || $d1 > 400) $erros[] = "A distância 1 (d1) deve ficar entre 2 e 400 cm.";
    if ($d2 !== '' && $d2 !== null && (!is_numeric($d2) || $d2 < 2 || $d2 > 400)) $erros[] = "A distância 2 (d2) deve ficar entre 2 e 400 cm.";
    if ($angulo !== '' && $angulo !== null && (!is_numeric($angulo) || $angulo < 0 || $angulo > 90)) $erros[] = "O ângulo deve ficar entre 0 e 90 graus.";
    if (!in_array($eixo, ['inclinacao', 'rotacao'])) $erros[] = "Escolha inclinação ou rotação.";
    return $erros;
}
