<?php
// Página inicial: mostra números do sistema e as últimas medições.
require 'conexao.php';

$totalVisitantes = $pdo->query("SELECT COUNT(*) FROM visitantes")->fetchColumn();
$totalMedicoes   = $pdo->query("SELECT COUNT(*) FROM medicoes")->fetchColumn();
$media           = $pdo->query("SELECT COALESCE(AVG(d1_cm),0) FROM medicoes")->fetchColumn();
$ultimas         = $pdo->query("SELECT d1_cm, angulo_graus FROM medicoes ORDER BY id DESC LIMIT 8")->fetchAll();

$titulo = "Início";
include 'includes/topo.php';
?>
<section class="hero">
  <div>
    <h1>Meça qualquer distância com um clique.</h1>
    <p>Escolha centímetros ou metros, aponte o laser e guarde duas distâncias com o botão. Depois veja a inclinação ou a rotação no display. Cada leitura fica salva aqui.</p>
    <p class="acoes">
      <a class="btn" href="medicao_form.php">Registrar medição</a>
      <a class="btn sec" href="visitante_form.php">Cadastrar visitante</a>
    </p>
  </div>
  <div class="lcd">
    <small>ULTIMA D1</small>
    <strong><?= $ultimas ? number_format($ultimas[0]['d1_cm'], 1, ',', '') : '--' ?> cm</strong>
    <small>ANG: <?= $ultimas ? number_format((float)$ultimas[0]['angulo_graus'], 1, ',', '') : '--' ?> graus</small>
  </div>
</section>

<section class="numeros">
  <div><b><?= $totalVisitantes ?></b> visitantes cadastrados</div>
  <div><b><?= $totalMedicoes ?></b> medições registradas</div>
  <div><b><?= number_format($media, 1, ',', '') ?></b> cm de média</div>
</section>

<section class="painel">
  <h2>Últimas medições</h2>
  <?php if (!$ultimas): ?><p class="vazio">Nenhuma medição ainda. Registre a primeira.</p><?php endif; ?>
  <?php foreach ($ultimas as $m): ?>
    <div class="barra"><span style="width:<?= min(100, $m['d1_cm'] / 4) ?>%"></span><em><?= number_format($m['d1_cm'], 1, ',', '') ?> cm</em></div>
  <?php endforeach; ?>
</section>
<?php include 'includes/rodape.php'; ?>
