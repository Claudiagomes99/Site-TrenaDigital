<?php
// Página "Como usar": explica o funcionamento da trena para os visitantes.
// Os passos seguem exatamente o código do dispositivo (Wokwi).
require 'conexao.php';
$titulo = "Como usar";
include 'includes/topo.php';
?>
<div class="cabeca"><h1>Como usar a trena</h1></div>

<h2 class="sub">O que o botão faz</h2>
<p>O dispositivo tem <b>um único botão</b>. A função dele muda conforme a tela em que você está.</p>
<div class="botoes">
  <div class="linha cab"><span>Tela</span><span>Toque rápido</span><span>Segurar (mais de 0,5 s)</span></div>
  <div class="linha"><b>Unidade</b><span>Troca entre cm e m.</span><span>Avança para a tela de distância.</span></div>
  <div class="linha"><b>Distância</b><span>Guarda d1; no toque seguinte guarda d2, e assim por diante.</span><span>Guarda a medição atual e avança para o ângulo.</span></div>
  <div class="linha"><b>Ângulo</b><span>Alterna entre Inclinação e Rotação.</span><span>Volta para a tela de unidade.</span></div>
</div>
<p class="dica">Laser: fica ligado só na tela de distância. No menu e no ângulo ele está desligado.</p>

<h2 class="sub">Passo a passo</h2>
<div class="passos">
  <section>
    <h2>1. Escolha a unidade</h2>
    <p>O visor mostra <b>Selecione a unidade</b>. Um toque rápido no botão troca entre <b>cm</b> e <b>m</b>.
    Para avançar, segure o botão por mais de meio segundo.</p>
    <p class="dica">Dica: ao segurar, a unidade troca mais uma vez. Se quiser trabalhar em metros, deixe o visor em cm e segure.</p>
  </section>
  <section>
    <h2>2. Meça a distância</h2>
    <p>O laser liga. Aponte para a parede e veja a distância ao vivo na linha <b>Dist</b>.
    Cada toque guarda uma medição: primeiro <b>d1</b>, depois <b>d2</b>, e volta a d1.
    Para avançar, segure o botão: ele guarda a medição atual (d1 ou d2) e depois passa para o ângulo.</p>
    <p class="dica">O sensor ultrassônico mede de 2 a 400 cm.</p>
  </section>
  <section>
    <h2>3. Meça o ângulo</h2>
    <p>O laser desliga e o sensor MPU6050 mostra o ângulo em graus (de 0 a 90, sem sinal).
    Um toque alterna entre <b>Inclinação</b> e <b>Rotação</b>. Segurar o botão volta ao menu.</p>
  </section>
  <section>
    <h2>4. Registre no site</h2>
    <p>Anote d1, d2 e o ângulo e registre em <a href="medicao_form.php">Nova medição</a>.
    O site guarda tudo em centímetros: se o visor estava em metros, multiplique por 100.</p>
  </section>
</div>
<?php include 'includes/rodape.php'; ?>
