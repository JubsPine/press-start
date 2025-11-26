<?php
session_start();
require_once '../backend/db.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'] ?? '';
    $pass = $_POST['pass'] ?? '';

    if ($user === 'admin' && $pass === 'admin123') {
        $_SESSION['admin_logged'] = true;
        $_SESSION['admin_user'] = 'admin';
        header('Location: dashboard.php');
        exit;
    } else {
        $err = 'Credenciais inválidas';
    }
}
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Login Admin</title>
<link rel="stylesheet" href="../public/css/style.css">
</head><body>
<div style="max-width:420px;margin:40px auto;color:#fff">
<h2>Login Administrador</h2>
<?php if ($err): ?><div style="color:#f88"><?=htmlspecialchars($err)?></div><?php endif;?>
<form method="post">
  <label>Usuário<br><input name="user" required></label><br><br>
  <label>Senha<br><input name="pass" type="password" required></label><br><br>
  <button type="submit" class="botao-retro">Entrar</button>
</form>
<p style="color:#ccc">Usuário demo: <b>admin</b> / senha: <b>admin123</b></p>
</div>
</body></html>
