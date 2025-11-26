<?php
require_once 'auth.php';
require_once '../backend/db.php';
$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? '';
    $desc = $_POST['descricao'] ?? '';
    $ativo = isset($_POST['ativo']) ? 1 : 0;
    if ($id) {
        $stmt = $conn->prepare("UPDATE QUESTIONARIOS SET NOME=?, DESCRICAO=?, ATIVO=? WHERE ID_QUESTIONARIO=?");
        $stmt->bind_param('ssii', $nome, $desc, $ativo, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO QUESTIONARIOS (NOME, DESCRICAO, ATIVO) VALUES (?,?,?)");
        $stmt->bind_param('ssi', $nome, $desc, $ativo);
        $stmt->execute();
    }
    header('Location: questionarios.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM QUESTIONARIOS WHERE ID_QUESTIONARIO = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: questionarios.php');
    exit;
}

$rows = $conn->query('SELECT * FROM QUESTIONARIOS ORDER BY CRIACAO DESC')->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><title>Questionários</title>
<link rel="stylesheet" href="../public/css/style.css">
</head><body style="color:#fff;padding:20px">
  <h2>Questionários</h2>
  <a href="dashboard.php">← Voltar</a>
  <h3>Criar / Editar</h3>
  <form method="post">
    <input type="hidden" name="id" id="qid">
    <label>Nome<br><input name="nome" id="nome" required></label><br><br>
    <label>Descrição<br><textarea name="descricao" id="descricao"></textarea></label><br><br>
    <label><input type="checkbox" name="ativo" id="ativo" checked> Ativo</label><br><br>
    <button class="botao-retro">Salvar</button>
  </form>

  <h3>Lista</h3>
  <table border="0" cellpadding="6">
    <tr><th>Nome</th><th>Ativo</th><th>Ações</th></tr>
    <?php foreach($rows as $r): ?>
      <tr style="vertical-align:top">
        <td><?=htmlspecialchars($r['NOME'])?></td>
        <td><?= $r['ATIVO'] ? 'Sim' : 'Não'?></td>
        <td>
          <a href="questoes.php?id_questionario=<?= $r['ID_QUESTIONARIO']?>">Questões</a> |
          <a href="?delete=<?= $r['ID_QUESTIONARIO']?>" onclick="return confirm('Excluir?')">Excluir</a>
        </td>
      </tr>
    <?php endforeach;?>
  </table>

<script>
function edit(q){ 
  document.getElementById('qid').value = q.ID_QUESTIONARIO;
  document.getElementById('nome').value = q.NOME;
  document.getElementById('descricao').value = q.DESCRICAO;
  document.getElementById('ativo').checked = q.ATIVO==1;
}
</script>

</body></html>
