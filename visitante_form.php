<?php
// FORMULÁRIO de visitante: serve para cadastrar (Create) e editar (Update).
require 'conexao.php';
require 'servicos/visitante_servico.php';

$erros = [];
$visitante = ['id' => '', 'nome' => '', 'email' => '', 'instituicao' => ''];

// Se veio ?id= na URL, é edição: carrega os dados do banco
if (isset($_GET['id'])) {
    $visitante = buscarVisitante($pdo, (int)$_GET['id']);
    if (!$visitante) { header('Location: visitantes.php'); exit; }
}

// Se o formulário foi enviado, valida e salva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $visitante = [
        'id'          => $_POST['id'],
        'nome'        => trim($_POST['nome']),
        'email'       => trim($_POST['email']),
        'instituicao' => trim($_POST['instituicao']),
    ];
    $erros = validarVisitante($visitante['nome'], $visitante['email']);

    if (!$erros) {
        try {
            if ($visitante['id']) {
                atualizarVisitante($pdo, $visitante['id'], $visitante['nome'], $visitante['email'], $visitante['instituicao']);
            } else {
                criarVisitante($pdo, $visitante['nome'], $visitante['email'], $visitante['instituicao']);
            }
            header('Location: visitantes.php?msg=' . urlencode('Visitante salvo!'));
            exit;
        } catch (PDOException $ex) {
            $erros[] = "Esse e-mail já está cadastrado.";
        }
    }
}

$titulo = $visitante['id'] ? "Editar visitante" : "Novo visitante";
include 'includes/topo.php';
?>
<div class="cabeca"><h1><?= $titulo ?></h1></div>

<?php foreach ($erros as $erro): ?><p class="aviso erro"><?= e($erro) ?></p><?php endforeach; ?>

<form class="form" method="post">
  <input type="hidden" name="id" value="<?= e($visitante['id']) ?>">
  <label><span>Nome completo</span><input name="nome" value="<?= e($visitante['nome']) ?>"></label>
  <label><span>E-mail</span><input name="email" type="email" value="<?= e($visitante['email']) ?>"></label>
  <label><span>Instituição (opcional)</span><input name="instituicao" value="<?= e($visitante['instituicao']) ?>"></label>
  <p class="acoes"><button class="btn">Salvar</button> <a class="btn sec" href="visitantes.php">Cancelar</a></p>
</form>
<?php include 'includes/rodape.php'; ?>
