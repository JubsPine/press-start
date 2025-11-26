-- Insere um questionário de exemplo 'Quiz Engasgo' com 3 perguntas e respostas
USE PRESS_START_TO_HELP;

INSERT INTO QUESTIONARIOS (NOME, DESCRICAO) VALUES
('Quiz Engasgo', 'Quiz para avaliar procedimentos em caso de engasgo');

SET @idQuiz = LAST_INSERT_ID();

INSERT INTO QUESTOES (ID_QUESTIONARIO, QUESTAO, TIPO, ORDEM) VALUES
(@idQuiz, 'A pessoa consegue tossir e falar normalmente?', 'única', 1),
(@idQuiz, 'Deve-se dar líquidos para ajudar a engolir?', 'única', 2),
(@idQuiz, 'A manobra de Heimlich pode ser usada em adultos?', 'única', 3);

-- Obtenha os ids das questões (ajuste se necessário)
-- Inserir opções/respostas
INSERT INTO RESPOSTAS (ID_QUESTAO, RESPOSTA, VALOR, ORDEM)
SELECT q.ID_QUESTAO, 'Sim', 0, 1 FROM QUESTOES q WHERE q.ID_QUESTIONARIO = @idQuiz AND q.ORDEM = 1;
INSERT INTO RESPOSTAS (ID_QUESTAO, RESPOSTA, VALOR, ORDEM)
SELECT q.ID_QUESTAO, 'Não', 1, 2 FROM QUESTOES q WHERE q.ID_QUESTIONARIO = @idQuiz AND q.ORDEM = 1;

INSERT INTO RESPOSTAS (ID_QUESTAO, RESPOSTA, VALOR, ORDEM)
SELECT q.ID_QUESTAO, 'Sim', 1, 1 FROM QUESTOES q WHERE q.ID_QUESTIONARIO = @idQuiz AND q.ORDEM = 2;
INSERT INTO RESPOSTAS (ID_QUESTAO, RESPOSTA, VALOR, ORDEM)
SELECT q.ID_QUESTAO, 'Não', 0, 2 FROM QUESTOES q WHERE q.ID_QUESTIONARIO = @idQuiz AND q.ORDEM = 2;

INSERT INTO RESPOSTAS (ID_QUESTAO, RESPOSTA, VALOR, ORDEM)
SELECT q.ID_QUESTAO, 'Sim', 1, 1 FROM QUESTOES q WHERE q.ID_QUESTIONARIO = @idQuiz AND q.ORDEM = 3;
INSERT INTO RESPOSTAS (ID_QUESTAO, RESPOSTA, VALOR, ORDEM)
SELECT q.ID_QUESTAO, 'Não', 0, 2 FROM QUESTOES q WHERE q.ID_QUESTIONARIO = @idQuiz AND q.ORDEM = 3;

-- FIM
