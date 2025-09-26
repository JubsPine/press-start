<?php
include 'conexao.php';

// Habilitar CORS simples (apenas para desenvolvimento)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Se for OPTIONS (preflight), responde OK
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

header("Content-Type: application/json; charset=UTF-8");

$acao = $_GET['acao'] ?? '';

if ($acao === 'listar_postos') {
    $cidade = $_GET['cidade'] ?? '';
    $estado = $_GET['estado'] ?? '';

    $sql = "SELECT CODIGO_CNES, NOME, ENDERECO, CEP, TELEFONE, TIPO, LATITUDE, LONGITUDE, HORA_FUNC 
            FROM POSTOS_SAUDE";

    $condicoes = [];
    if (!empty($cidade)) $condicoes[] = "ENDERECO LIKE '%$cidade%'";
    if (!empty($estado)) $condicoes[] = "ENDERECO LIKE '%$estado%'";
    if ($condicoes) {
        $sql .= " WHERE " . implode(" AND ", $condicoes);
    }

    $result = $conn->query($sql);

    $postos = [];
    while ($row = $result->fetch_assoc()) {
        $postos[] = $row;
    }

    echo json_encode($postos, JSON_UNESCAPED_UNICODE);
    exit;
}

echo json_encode(["erro" => "Ação inválida"]);

if ($acao === 'listar_postos' && isset($_GET['lat']) && isset($_GET['lng'])) {
    $lat = floatval($_GET['lat']);
    $lng = floatval($_GET['lng']);
    $raio = 10; // em km

    $sql = "SELECT *, 
            (6371 * acos(
                cos(radians($lat)) * cos(radians(LATITUDE)) *
                cos(radians(LONGITUDE) - radians($lng)) +
                sin(radians($lat)) * sin(radians(LATITUDE))
            )) AS distancia
            FROM POSTOS_SAUDE
            HAVING distancia < $raio
            ORDER BY distancia ASC
            LIMIT 20";

    $result = $conn->query($sql);
    $postos = [];
    while ($row = $result->fetch_assoc()) {
        $postos[] = $row;
    }
    echo json_encode($postos, JSON_UNESCAPED_UNICODE);
    exit;
}
