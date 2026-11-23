<?php
// FORMULÁRIO de medição: serve para registrar (Create) e editar (Update).
require 'conexao.php';
require 'servicos/medicao_servico.php';
require 'servicos/visitante_servico.php';

$erros = [];
$medicao = ['id' => '', 'visitante_id' => '', 'd1_cm' => '', 'd2_cm' => '', 'angulo_graus' => '', 'eixo' => 'inclinacao', 'observacao' => ''];
$visitantes = listarVisitantes($pdo); // para preencher a lista de escolha

// Se veio ?id= na URL, é edição
if (isset($_GET['id'])) {
    $medicao = buscarMedicao($pdo, (int)$_GET['id']);
    if (!$medicao) { header('Location: medicoes.php'); exit; }
}

// Se o formulário foi enviado, valida e salva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medicao = [
        'id'           => $_POST['id'],
        'visitante_id' => $_POST['visitante_id'] === '' ? null : (int)$_POST['visitante_id'],
        'd1_cm'        => trim($_POST['d1_cm']),
        'd2_cm'        => trim($_POST['d2_cm']),
        'angulo_graus' => trim($_POST['angulo_graus']),
        'eixo'         => $_POST['eixo'],
        'observacao'   => trim($_POST['observacao']),
    ];
    $erros = validarMedicao($medicao['d1_cm'], $medicao['d2_cm'], $medicao['angulo_graus'], $medicao['eixo']);

    if (!$erros) {
        // campo de texto vazio vira NULL no banco
        $d2     = $medicao['d2_cm'] === '' ? null : $medicao['d2_cm'];
        $angulo = $medicao['angulo_graus'] === '' ? null : $medicao['angulo_graus'];
        $obs    = $medicao['observacao'] === '' ? null : $medicao['observacao'];

        if ($medicao['id']) {
            atualizarMedicao($pdo, $medicao['id'], $medicao['visitante_id'], $medicao['d1_cm'], $d2, $angulo, $medicao['eixo'], $obs);
        } else {
            criarMedicao($pdo, $medicao['visitante_id'], $medicao['d1_cm'], $d2, $angulo, $medicao['eixo'], $obs);
        }
        header('Location: medicoes.php?msg=' . urlencode('Medição salva!'));
        exit;
    }
}

$titulo = $medicao['id'] ? "Editar medição" : "Nova medição";
include 'includes/topo.php';
?>
<div class="cabeca"><h1><?= $titulo ?></h1></div>

<?php foreach ($erros as $erro): ?><p class="aviso erro"><?= e($erro) ?></p><?php endforeach; ?>

<form class="form" method="post">
  <input type="hidden" name="id" value="<?= e($medicao['id']) ?>">
  <label><span>Visitante (opcional)</span>
    <select name="visitante_id">
      <option value="">Sem identificação</option>
      <?php foreach ($visitantes as $v): ?>
        <option value="<?= $v['id'] ?>" <?= $medicao['visitante_id'] == $v['id'] ? 'selected' : '' ?>><?= e($v['nome']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label><span>Distância 1 (d1) em cm, de 2 a 400</span><input name="d1_cm" type="number" step="0.1" value="<?= e($medicao['d1_cm']) ?>"></label>
  <label><span>Distância 2 (d2) em cm (opcional)</span><input name="d2_cm" type="number" step="0.1" value="<?= e($medicao['d2_cm']) ?>"></label>
  <label><span>Ângulo em graus, de 0 a 90 (opcional)</span><input name="angulo_graus" type="number" step="0.1" value="<?= e($medicao['angulo_graus']) ?>"></label>
  <label><span>Tipo do ângulo</span>
    <select name="eixo">
      <option value="inclinacao" <?= $medicao['eixo'] == 'inclinacao' ? 'selected' : '' ?>>Inclinação</option>
      <option value="rotacao" <?= $medicao['eixo'] == 'rotacao' ? 'selected' : '' ?>>Rotação</option>
    </select>
  </label>
  <label><span>O que foi medido?</span><input name="observacao" value="<?= e($medicao['observacao']) ?>"></label>
  <p class="acoes"><button class="btn">Salvar</button> <a class="btn sec" href="medicoes.php">Cancelar</a></p>
</form>
<?php include 'includes/rodape.php'; ?>
