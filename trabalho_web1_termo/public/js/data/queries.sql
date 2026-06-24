-- Criar Liga
INSERT INTO Ligas (nome_liga, palavra_chave, id_criador) 
VALUES ('Devs do Termo', 'cafe123', 1);

INSERT INTO Membros_Ligas (id_liga, id_usuario) 
VALUES (LAST_INSERT_ID(), 1);

-- Buscar todas as ligas
SELECT id_liga, nome_liga, id_criador, criado_em 
FROM Ligas;

-- Buscar ligas por aproximação
SELECT id_liga, nome_liga 
FROM Ligas 
WHERE nome_liga LIKE '%Devs%';

-- Ranking Geral
SELECT 
    u.id_usuario,
    u.nome,
    COUNT(p.id_partida) AS total_partidas,
    SUM(IF(p.ganhou = 1, (7 - p.tentativas), 0)) AS pontuacao_total,
    ROUND((COUNT(IF(p.ganhou = 1, 1, NULL)) * 100.0 / COUNT(p.id_partida)), 2) AS taxa_vitoria_percentual
FROM Usuarios u
LEFT JOIN Partidas p ON u.id_usuario = p.id_usuario
GROUP BY u.id_usuario, u.nome
ORDER BY pontuacao_total DESC, taxa_vitoria_percentual DESC;

-- Ranking Semanal
SELECT 
    u.id_usuario,
    u.nome,
    SUM(IF(p.ganhou = 1, (7 - p.tentativas), 0)) AS pontuacao_semanal
FROM Usuarios u
INNER JOIN Partidas p ON u.id_usuario = p.id_usuario
WHERE p.data_partida >= (NOW() - INTERVAL 7 DAY)
GROUP BY u.id_usuario, u.nome
ORDER BY pontuacao_semanal DESC;

-- Ranking por Liga (Exemplo filtrando a Liga ID 3)
SELECT 
    u.id_usuario,
    u.nome,
    SUM(IF(p.ganhou = 1, (7 - p.tentativas), 0)) AS pontuacao_liga
FROM Usuarios u
INNER JOIN Membros_Ligas ml ON u.id_usuario = ml.id_usuario
LEFT JOIN Partidas p ON u.id_usuario = p.id_usuario
WHERE ml.id_liga = 3
GROUP BY u.id_usuario, u.nome
ORDER BY pontuacao_liga DESC;
