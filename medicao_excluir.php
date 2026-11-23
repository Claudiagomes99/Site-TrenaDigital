<?php
// EXCLUI uma medição (Delete) e volta para a lista.
require 'conexao.php';
require 'servicos/medicao_servico.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    excluirMedicao($pdo, (int)$_POST['id']);
}
header('Location: medicoes.php?msg=' . urlencode('Medição excluída.'));
