<?php
    header('Content-Type: application/json; charset=utf-8');
    require_once 'db.php';

    $conn = db_connect();

    // Aqui buscamos um questionário ativo. Por simplicidade pegamos o primeiro.
    $qsql = "SELECT ID_QUESTIONARIO, NOME FROM QUESTIONARIOS WHERE ATIVO = 1 ORDER BY CRIACAO LIMIT 1";
    $qres = $conn->query($qsql);
    if (!$qres) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao consultar questionarios']);
        exit;
    }
    $quiz = $qres->fetch_assoc();
    if (!$quiz) {
        echo json_encode([]);
        exit;
    }

    $idQuiz = (int)$quiz['ID_QUESTIONARIO'];

    $sql = "SELECT ID_QUESTAO, QUESTAO, TIPO FROM QUESTOES WHERE ID_QUESTIONARIO = $idQuiz ORDER BY ORDEM";
    $res = $conn->query($sql);
    $out = [];
    while ($row = $res->fetch_assoc()) {
        $idq = (int)$row['ID_QUESTAO'];
        $rsql = "SELECT ID_RESPOSTA, RESPOSTA, VALOR FROM RESPOSTAS WHERE ID_QUESTAO = $idq ORDER BY ORDEM";
        $rres = $conn->query($rsql);
        $respostas = [];
        while ($r = $rres->fetch_assoc()) {
            $respostas[] = $r;
        }
        $row['respostas'] = $respostas;
        $out[] = $row;
    }

    echo json_encode($out, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
    $conn->close();
    ?>