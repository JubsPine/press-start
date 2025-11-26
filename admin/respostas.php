<?php
require_once 'auth.php';
require_once '../backend/db.php';
$conn = db_connect();

$idq = isset($_GET['id_questao']) ? (int)$_GET['id_questao'] : 0;
if (!$idq) { echo 'Questão não informada'; exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $resposta = $_POST['resposta'] ?? '';
    $valor = (float)($_POST['valor'] ?? 0);
    $ordem = (int)($_POST['ordem'] ?? 0);
    if ($id) {
        $stmt = $conn->prepare('UPDATE RESPOSTAS SET RESPOSTA=?, VALOR=?, ORDEM=? WHERE ID_RESPOSTA=?');
        $stmt->bind_param('sdii', $resposta, $valor, $ordem, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare('INSERT INTO RESPOSTAS (ID_QUESTAO, RESPOSTA, VALOR, ORDEM) VALUES (?,?,?,?)');
        $stmt->bind_param('isdi', $idq, $resposta, $valor, $ordem);
        $stmt->execute();
    }
    header('Location: respostas.php?id_questao=' . $idq);
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare('DELETE FROM RESPOSTAS WHERE ID_RESPOSTA = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: respostas.php?id_questao=' . $idq);
    exit;
}

$questao = $conn->query('SELECT * FROM QUESTOES WHERE ID_QUESTAO = ' . $idq)->fetch_assoc();
$rows = $conn->query('SELECT * FROM RESPOSTAS WHERE ID_QUESTAO = ' . $idq . ' ORDER BY ORDEM')->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Respostas</title>
<link rel="stylesheet" href="../public/css/style.css"></head><body style="color:#fff;padding:20px">
  <h2>Respostas da pergunta: <?=htmlspecialchars($questao['QUESTAO'])?></h2>
  <a href="questoes.php?id_questionario=<?= $questao['ID_QUESTIONARIO']?>">← Voltar</a>
  <h3>Criar / Editar</h3>
  <form method="post">
    <input type="hidden" name="id" id="rid">
    <label>Resposta<br><input name="resposta" id="resposta" required></label><br><br>
    <label>Valor (pontuação)<br><input name="valor" id="valor" type="number" step="0.1" value="0"></label><br><br>
    <label>Ordem<br><input name="ordem" id="ordem" type="number" value="0"></label><br><br>
    <button class="botao-retro">Salvar</button>
  </form>

  <h3>Lista de Respostas</h3>
  <table border="0" cellpadding="6">
    <tr><th>Resposta</th><th>Valor</th><th>Ações</th></tr>
    <?php foreach($rows as $r): ?>
      <tr>
        <td><?=htmlspecialchars($r['RESPOSTA'])?></td>
        <td><?=htmlspecialchars($r['VALOR'])?></td>
        <td>
          <a href="?delete=<?= $r['ID_RESPOSTA']?>&id_questao=<?= $idq?>" onclick="return confirm('Excluir?')">Excluir</a>
        </td>
      </tr>
    <?php endforeach;?>
  </table>
</body></html>
