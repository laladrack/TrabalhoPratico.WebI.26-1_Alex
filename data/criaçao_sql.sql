CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha_hash VARCHAR(255) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE Ligas (
    id_liga INT AUTO_INCREMENT PRIMARY KEY,
    nome_liga VARCHAR(50) NOT NULL,
    palavra_chave VARCHAR(20) NOT NULL,
    id_criador INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_criador) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Membros_Ligas (
    id_liga INT,
    id_usuario INT,
    ingressou_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_liga, id_usuario),
    FOREIGN KEY (id_liga) REFERENCES Ligas(id_liga) ON DELETE CASCADE,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Partidas (
    id_partida INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    palavra_jogada CHAR(5) NOT NULL,
    tentativas INT NOT NULL,
    ganhou BOOLEAN NOT NULL,
    data_partida TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario) ON DELETE CASCADE,
    CONSTRAINT chk_tentativas CHECK (tentativas BETWEEN 1 AND 6)
) ENGINE=InnoDB;

CREATE TABLE Dicionario_Palavras (
    id_palavra INT AUTO_INCREMENT PRIMARY KEY,
    palavra CHAR(5) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE INDEX idx_partidas_data ON Partidas(data_partida);
CREATE INDEX idx_partidas_usuario ON Partidas(id_usuario);

