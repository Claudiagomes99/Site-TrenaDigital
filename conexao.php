<?php
// Conexão com o banco de dados (MySQL). Troque usuário e senha se precisar.
$pdo = new PDO("mysql:host=localhost;dbname=trena_iot;charset=utf8mb4", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Função que protege o site contra código malicioso na tela (XSS)
function e($texto) {
    return htmlspecialchars((string)$texto, ENT_QUOTES, 'UTF-8');
}
