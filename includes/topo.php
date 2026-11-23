<!-- Início de todas as páginas: cabeçalho e menu. Antes de incluir, defina $titulo. -->
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titulo) ?> | IoT</title>
  <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&family=Figtree:wght@400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
<header class="topo">
  <a class="marca" href="index.php"><i class="laser"></i>IoT</a>
  <nav>
    <a href="index.php">Início</a>
    <a href="visitantes.php">Visitantes</a>
    <a href="medicoes.php">Medições</a>
    <a href="como_usar.php">Como usar</a>
  </nav>
</header>
<div class="regua"></div>
<main>
<?php if (isset($_GET['msg'])): ?>
  <p class="aviso ok"><?= e($_GET['msg']) ?></p>
<?php endif; ?>
