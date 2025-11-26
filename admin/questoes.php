<?php
require_once 'auth.php';
require_once '../backend/db.php';
$conn = db_connect();

$idq = isset($_GET['id_questionario']) ? (int)$_GET['id_questionario'] : 0;
if (!$idq) {
    echo 'Questionário não informado';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $questao = $_POST['questao'] ?? '';
    $tipo = $_POST['tipo'] ?? 'única';
    $ordem = (int)($_POST['ordem'] ?? 0);
    if ($id) {
        $stmt = $conn->prepare('UPDATE QUESTOES SET QUESTAO=?, TIPO=?, ORDEM=? WHERE ID_QUESTAO=?');
        $stmt->bind_param('siii', $questao, $tipo, $ordem, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare('INSERT INTO QUESTOES (ID_QUESTIONARIO, QUESTAO, TIPO, ORDEM) VALUES (?,?,?,?)');
        $stmt->bind_param('issi', $idq, $questao, $tipo, $ordem);
        $stmt->execute();
    }
    header('Location: questoes.php?id_questionario=' . $idq);
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare('DELETE FROM QUESTOES WHERE ID_QUESTAO = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: questoes.php?id_questionario=' . $idq);
    exit;
}

$quiz = $conn->query('SELECT * FROM QUESTIONARIOS WHERE ID_QUESTIONARIO = ' . $idq)->fetch_assoc();
$rows = $conn->query('SELECT * FROM QUESTOES WHERE ID_QUESTIONARIO = ' . $idq . ' ORDER BY ORDEM')->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Questões</title>
<link rel="stylesheet" href="../public/css/style.css"></head><body style="color:#fff;padding:20px">
  <h2>Questões do questionário: <?=htmlspecialchars($quiz['NOME'])?></h2>
  <a href="questionarios.php">← Voltar</a>
  <h3>Criar / Editar</h3>
  <form method="post">
    <input type="hidden" name="id" id="qid">
    <label>Pergunta<br><textarea name="questao" id="questao" required></textarea></label><br><br>
    <label>Tipo<br>
      <select name="tipo" id="tipo">
        <option value="única">única</option>
        <option value="múltipla">múltipla</option>
        <option value="texto">texto</option>
      </select>
    </label><br><br>
    <label>Ordem<br><input name="ordem" id="ordem" type="number" value="0"></label><br><br>
    <button class="botao-retro">Salvar</button>
  </form>

  <h3>Lista de Questões</h3>
  <table border="0" cellpadding="6">
    <tr><th>Pergunta</th><th>Tipo</th><th>Ações</th></tr>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?=nl2br(htmlspecialchars($r['QUESTAO']))?></td>
        <td><?=htmlspecialchars($r['TIPO'])?></td>
        <td>
          <a href="respostas.php?id_questao=<?= $r['ID_QUESTAO']?>">Respostas</a> |
          <a href="?delete=<?= $r['ID_QUESTAO']?>&id_questionario=<?= $idq?>" onclick="return confirm('Excluir?')">Excluir</a>
        </td>
      </tr>
    <?php endforeach;?>
  </table>

<script>
function edit(q){
  document.getElementById('qid').value = q.ID_QUESTAO;
  document.getElementById('questao').value = q.QUESTAO;
  document.getElementById('tipo').value = q.TIPO;
  document.getElementById('ordem').value = q.ORDEM;
}
</script>

</body></html>
