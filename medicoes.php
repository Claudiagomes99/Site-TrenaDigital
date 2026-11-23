<?php
// LISTA de medições (Read)
require 'conexao.php';
require 'servicos/medicao_servico.php';

$lista = listarMedicoes($pdo);
$titulo = "Medições";
include 'includes/topo.php';
?>
<div class="cabeca">
  <h1>Medições</h1>
  <a class="btn" href="medicao_form.php">Nova medição</a>
</div>

<?php if (!$lista): ?>
  <p class="vazio">Nenhuma medição ainda. Registre a primeira.</p>
<?php else: ?>
<div class="rolagem">
  <table>
    <tr><th>Visitante</th><th>d1 (cm)</th><th>d2 (cm)</th><th>Diferença (cm)</th><th>Ângulo</th><th>Origem</th><th>Observação</th><th>Data</th><th></th></tr>
    <?php foreach ($lista as $m): ?>
    <tr>
      <td><?= e($m['visitante'] ?: 'Sem identificação') ?></td>
      <td><?= e($m['d1_cm']) ?></td>
      <td><?= e($m['d2_cm'] ?? '-') ?></td>
      <!-- Diferença entre as duas leituras (extra do site; o aparelho não calcula isso) -->
      <td><?= $m['d2_cm'] !== null ? number_format(abs($m['d1_cm'] - $m['d2_cm']), 1) : '-' ?></td>
      <td><?= $m['angulo_graus'] !== null ? e($m['angulo_graus']) . '° (' . ($m['eixo'] == 'rotacao' ? 'rotação' : 'inclinação') . ')' : '-' ?></td>
      <td><span class="tag <?= e($m['origem']) ?>"><?= e($m['origem']) ?></span></td>
      <td><?= e($m['observacao'] ?: '-') ?></td>
      <td><?= date('d/m/Y H:i', strtotime($m['criado_em'])) ?></td>
      <td class="fim">
        <a href="medicao_form.php?id=<?= $m['id'] ?>">Editar</a>
        <form method="post" action="medicao_excluir.php" onsubmit="return confirm('Excluir esta medição?')">
          <input type="hidden" name="id" value="<?= $m['id'] ?>">
          <button class="link perigo">Excluir</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php endif; ?>
<?php include 'includes/rodape.php'; ?>
