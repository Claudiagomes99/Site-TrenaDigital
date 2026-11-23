<?php
// EXCLUI um visitante (Delete) e volta para a lista.
require 'conexao.php';
require 'servicos/visitante_servico.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    excluirVisitante($pdo, (int)$_POST['id']);
}
header('Location: visitantes.php?msg=' . urlencode('Visitante excluído.'));
