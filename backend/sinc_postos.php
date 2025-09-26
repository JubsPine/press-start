<?php

set_time_limit(0);
include 'conexao.php';

$codigo_tipo_unidade = 5;   // ajuste conforme necessidade (5 = hospital no seu exemplo)
$codigo_uf = null;          // se quiser filtrar por estado, coloque o código (ex: 35)
$limit = 100;               // quantos por página (API suporta limit)
$offset = 0;
$totalInserted = 0;
$totalUpdated = 0;

function fetch_page($url) {
    // Buscar via file_get_contents (pode trocar por cURL se preferir)
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: PressStartSync/1.0\r\n" // alguns servers exigem UA
        ]
    ];
    $context = stream_context_create($opts);
    $resp = @file_get_contents($url, false, $context);
    if ($resp === FALSE) return false;
    return json_decode($resp, true);
}

while (true) {
    $params = "codigo_tipo_unidade={$codigo_tipo_unidade}&limit={$limit}&offset={$offset}";
    if (!empty($codigo_uf)) $params .= "&codigo_uf={$codigo_uf}";

    $url = "https://apidadosabertos.saude.gov.br/cnes/estabelecimentos?{$params}";
    echo "Buscando: $url\n";

    $json = fetch_page($url);
    if (!$json || !isset($json['estabelecimentos'])) {
        echo "Erro ao buscar ou resposta vazia. Parando.\n";
        break;
    }

    $estabelecimentos = $json['estabelecimentos'];
    if (count($estabelecimentos) === 0) {
        echo "Nenhum estabelecimento retornado — fim da paginação.\n";
        break;
    }

    foreach ($estabelecimentos as $posto) {
        $cnes = intval($posto['codigo_cnes'] ?? 0);
        if ($cnes === 0) continue;

        $nome = $conn->real_escape_string($posto['nome_fantasia'] ?? $posto['nome_razao_social'] ?? '');
        $endereco = $conn->real_escape_string(
            trim(($posto['endereco_estabelecimento'] ?? '') . " " . ($posto['numero_estabelecimento'] ?? '')) .
            (isset($posto['bairro_estabelecimento']) ? ", " . $posto['bairro_estabelecimento'] : '')
        );
        $cep = $conn->real_escape_string($posto['codigo_cep_estabelecimento'] ?? '');
        $telefone = $conn->real_escape_string($posto['numero_telefone_estabelecimento'] ?? '');
        $lat = is_numeric($posto['latitude_estabelecimento_decimo_grau']) ? $posto['latitude_estabelecimento_decimo_grau'] : "NULL";
        $lng = is_numeric($posto['longitude_estabelecimento_decimo_grau']) ? $posto['longitude_estabelecimento_decimo_grau'] : "NULL";
        $hora = $conn->real_escape_string($posto['descricao_turno_atendimento'] ?? '');
        $tipo = $conn->real_escape_string($posto['codigo_tipo_unidade'] ?? '');

        // Convert numeric lat/lng to SQL literals or NULL
        $lat_sql = ($lat === "NULL") ? "NULL" : $lat;
        $lng_sql = ($lng === "NULL") ? "NULL" : $lng;

        $sql = "INSERT INTO POSTOS_SAUDE (CODIGO_CNES, NOME, ENDERECO, CEP, TELEFONE, TIPO, LATITUDE, LONGITUDE, HORA_FUNC)
                VALUES ({$cnes}, '{$nome}', '{$endereco}', '{$cep}', '{$telefone}', '{$tipo}', {$lat_sql}, {$lng_sql}, '{$hora}')
                ON DUPLICATE KEY UPDATE
                  NOME=VALUES(NOME), ENDERECO=VALUES(ENDERECO), CEP=VALUES(CEP), TELEFONE=VALUES(TELEFONE),
                  LATITUDE=VALUES(LATITUDE), LONGITUDE=VALUES(LONGITUDE), HORA_FUNC=VALUES(HORA_FUNC), TIPO=VALUES(TIPO)";

        if ($conn->query($sql) === TRUE) {
            if ($conn->affected_rows === 1) $totalInserted++;
            else $totalUpdated++;
        } else {
            echo "Erro SQL (CNES {$cnes}): " . $conn->error . "\n";
        }
    }

    echo "Página offset {$offset} processada. Inseridos: {$totalInserted}, Atualizados: {$totalUpdated}\n";

    // Avança pagina
    $offset += $limit;
    // pequena pausa para não sobrecarregar
    usleep(200000); // 0.2s
}

echo "Sincronização finalizada. Total inseridos: {$totalInserted}, atualizados: {$totalUpdated}\n";
?>
