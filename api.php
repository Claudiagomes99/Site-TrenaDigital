<?php
// API para o dispositivo enviar medições.
// POST com cabeçalho X-API-KEY e JSON, sempre em CENTÍMETROS:
// {"d1_cm":142.5,"d2_cm":98.0,"angulo_graus":3,"eixo":"inclinacao"}
require 'conexao.php';
require 'servicos/medicao_servico.php';

header('Content-Type: application/json; charset=utf-8');
$chave = "trena-feira-2026"; // senha combinada com o dispositivo

if (($_SERVER['HTTP_X_API_KEY'] ?? '') !== $chave) {
    http_response_code(401);
    echo json_encode(['erro' => 'Chave inválida']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados  = json_decode(file_get_contents('php://input'), true) ?? [];
    $d1     = $dados['d1_cm'] ?? '';
    $d2     = $dados['d2_cm'] ?? null;
    $angulo = $dados['angulo_graus'] ?? null;
    $eixo   = $dados['eixo'] ?? 'inclinacao';

    $erros = validarMedicao($d1, $d2, $angulo, $eixo);
    if ($erros) {
        http_response_code(422);
        echo json_encode(['erro' => $erros]);
        exit;
    }
    criarMedicaoDoDispositivo($pdo, $d1, $d2, $angulo, $eixo);
    http_response_code(201);
    echo json_encode(['ok' => true]);
    exit;
}

// Se não for POST, devolve a lista de medições em JSON
echo json_encode(listarMedicoes($pdo));
