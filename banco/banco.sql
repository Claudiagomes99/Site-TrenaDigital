-- =====================================================================
-- TRENA LASER INTELIGENTE - Banco de dados (MySQL / MariaDB)
-- Baseado no código do dispositivo (Wokwi): Arduino Uno + HC-SR04 +
-- MPU6050 + laser + botão + LCD 16x2 I2C.
--
-- O dispositivo guarda DUAS distâncias (d1 e d2, botão no modo distância)
-- e um ângulo em dois modos: "Inclinacao" (eixo X) e "Rotacao" (eixo Y).
-- O site grava tudo em CENTÍMETROS (se o aparelho estiver em "m", converter).
--
-- MODELO LÓGICO
--   visitantes (1) ------< (N) medicoes
--   Um visitante faz várias medições; uma medição pertence a no máximo
--   um visitante (NULL = medição sem identificação).
--
--   VISITANTES                      MEDICOES
--   +--------------------+          +--------------------------------+
--   | PK id              |<---------| FK visitante_id (anulável)     |
--   | nome  (obrig.)     |  1 : N   | PK id                          |
--   | email (único)      |          | d1_cm  (2 a 400, obrigatório)  |
--   | instituicao        |          | d2_cm  (2 a 400, opcional)     |
--   | criado_em          |          | angulo_graus (0 a 90)          |
--   +--------------------+          | eixo (inclinacao / rotacao)    |
--                                   | origem (dispositivo / manual)  |
--                                   | observacao, criado_em          |
--                                   +--------------------------------+
-- =====================================================================
CREATE DATABASE IF NOT EXISTS trena_iot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE trena_iot;

-- Apaga as tabelas antigas para poder importar este arquivo quantas vezes precisar
-- (medicoes primeiro, porque ela depende de visitantes)
DROP TABLE IF EXISTS medicoes;
DROP TABLE IF EXISTS visitantes;

-- Entidade 1: quem visita o estande e usa a trena
CREATE TABLE visitantes (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nome        VARCHAR(100) NOT NULL,
  email       VARCHAR(120) NOT NULL UNIQUE,
  instituicao VARCHAR(100) NULL,
  criado_em   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Entidade 2: leituras da trena (HC-SR04 = d1/d2, MPU6050 = ângulo)
CREATE TABLE medicoes (
  id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  visitante_id  INT UNSIGNED NULL,
  d1_cm         DECIMAL(6,1) NOT NULL CHECK (d1_cm BETWEEN 2 AND 400),
  d2_cm         DECIMAL(6,1) NULL CHECK (d2_cm BETWEEN 2 AND 400),
  angulo_graus  DECIMAL(4,1) NULL CHECK (angulo_graus BETWEEN 0 AND 90),
  eixo          ENUM('inclinacao','rotacao') NOT NULL DEFAULT 'inclinacao',
  origem        ENUM('dispositivo','manual') NOT NULL DEFAULT 'manual',
  observacao    VARCHAR(200) NULL,
  criado_em     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_medicao_visitante FOREIGN KEY (visitante_id)
    REFERENCES visitantes(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Dados de exemplo
INSERT INTO visitantes (nome, email, instituicao) VALUES
 ('Ana Souza','ana@exemplo.com','Escola Tecnica Central'),
 ('Bruno Lima','bruno@exemplo.com','Colegio Horizonte');
INSERT INTO medicoes (visitante_id, d1_cm, d2_cm, angulo_graus, eixo, origem, observacao) VALUES
 (1,142.5,98.0,3.0,'inclinacao','dispositivo','Largura da mesa'),
 (2,87.0,NULL,0.0,'inclinacao','dispositivo','Altura do estande'),
 (1,215.4,180.2,12.0,'rotacao','manual','Porta da sala');
