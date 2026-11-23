<?php
// LISTA de visitantes (Read)
require 'conexao.php';
require 'servicos/visitante_servico.php';

$lista = listarVisitantes($pdo);
$titulo = "Visitantes";
include 'includes/topo.php';
?>
<div class="cabeca">
  <h1>Visitantes</h1>
  <a class="btn" href="visitante_form.php">Novo visitante</a>
</div>

<?php if (!$lista): ?>
  <p class="vazio">Nenhum visitante ainda. Cadastre o primeiro.</p>
<?php else: ?>
<div class="rolagem">
  <table>
    <tr><th>Nome</th><th>E-mail</th><th>Instituição</th><th>Medições</th><th></th></tr>
    <?php foreach ($lista as $v): ?>
    <tr>
      <td><?= e($v['nome']) ?></td>
      <td><?= e($v['email']) ?></td>
      <td><?= e($v['instituicao'] ?: '-') ?></td>
      <td><?= $v['total'] ?></td>
      <td class="fim">
        <a href="visitante_form.php?id=<?= $v['id'] ?>">Editar</a>
        <form method="post" action="visitante_excluir.php" onsubmit="return confirm('Excluir este visitante?')">
          <input type="hidden" name="id" value="<?= $v['id'] ?>">
          <button class="link perigo">Excluir</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php endif; ?>
<?php include 'includes/rodape.php'; ?>
