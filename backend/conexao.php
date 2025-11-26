<?php
$host = getenv("MYSQLHOST");
$user = getenv("MYSQLUSER");
$pass = getenv("MYSQLPASSWORD");
$db = getenv("MYSQLDATABASE");
$port = getenv("MYSQLPORT");

$conn = new mysqli($host, $user, $pass, $db, $port);

//validação
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
