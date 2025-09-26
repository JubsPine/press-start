<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "PRESS_START_TO_HELP";

//conexão
$conn = new mysqli($host, $user, $pass, $dbname);

//validação
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
