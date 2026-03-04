-- 1. Criação do Banco de Dados
DROP DATABASE IF EXISTS `bd_apoi_me`;

CREATE DATABASE IF NOT EXISTS `bd_apoi_me` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_0900_ai_ci;

USE `bd_apoi_me`; 

-- 2. Tabela: condominio
CREATE TABLE `condominio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `foto` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabela: categorias
CREATE TABLE `categorias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` enum('manutenção','domésticos','cuidados','educação') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Tabela: dias_semanas
CREATE TABLE `dias_semanas` (
  `id` int NOT NULL,
  `nome` enum('domingo','segunda-feira','terça-feira','quarta-feira','quinta-feira','sexta-feira','sábado') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Tabela: usuario
CREATE TABLE `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `imagem` varchar(300) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_usuario_condominio` (`codigo`),
  CONSTRAINT `fk_usuario_condominio` FOREIGN KEY (`codigo`) REFERENCES `condominio` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    
-- 6. Tabela: servicos
CREATE TABLE `servicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(70) NOT NULL,
  `imagem` varchar(300) NOT NULL,
  `descricao` varchar(500) NOT NULL,
  `horario_inicio` time DEFAULT NULL,
  `horario_fim` time DEFAULT NULL,
  `data_limite` date NOT NULL,
  `id_categoria` int NOT NULL,
  `id_prestador` int NOT NULL,
  `id_condominio` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_servicos_categoria` (`id_categoria`),
  KEY `fk_servicos_prestador` (`id_prestador`),
  KEY `fk_servicos_condominio` (`id_condominio`),
  CONSTRAINT `fk_servicos_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`),
  CONSTRAINT `fk_servicos_condominio` FOREIGN KEY (`id_condominio`) REFERENCES `condominio` (`id`),
  CONSTRAINT `fk_servicos_prestador` FOREIGN KEY (`id_prestador`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Tabela: disponibilidade (Relacionamento N:N entre dias e serviços)
CREATE TABLE `disponibilidade` (
  `id_dia` int NOT NULL,
  `id_servicos` int NOT NULL,
  PRIMARY KEY (`id_dia`,`id_servicos`),
  KEY `fk_disponibilidade_servicos` (`id_servicos`),
  CONSTRAINT `fk_dia` FOREIGN KEY (`id_dia`) REFERENCES `dias_semanas` (`id`),
  CONSTRAINT `fk_disponibilidade_servicos` FOREIGN KEY (`id_servicos`) REFERENCES `servicos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Tabela: contratados (Agendamentos)
CREATE TABLE `contratados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dia` date NOT NULL,
  `horario` time NOT NULL,
  `id_condominio` int NOT NULL,
  `id_cliente` int NOT NULL,
  `id_servico` int NOT NULL,
  `confirmado` enum('pendente','confirmado','concluido') DEFAULT 'pendente',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_agenda` (`dia`,`horario`,`id_servico`),
  KEY `fk_contratados_condominio` (`id_condominio`),
  KEY `fk_contratados_usuario` (`id_cliente`),
  KEY `fk_contratados_servico` (`id_servico`),
  CONSTRAINT `fk_contratados_condominio` FOREIGN KEY (`id_condominio`) REFERENCES `condominio` (`id`),
  CONSTRAINT `fk_contratados_servico` FOREIGN KEY (`id_servico`) REFERENCES `servicos` (`id`),
  CONSTRAINT `fk_contratados_usuario` FOREIGN KEY (`id_cliente`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserção de Teste
INSERT INTO `condominio` (`codigo`, `nome`, `foto`) VALUES
('1234spd', 'Edifício Teste', 'url_da_foto.jpg'),
('5678abc', 'Residencial Primavera', 'img/condominio.png'),
('9101xyz', 'Condomínio Bela Vista', 'img/condominio.png');

-- Categorias --
INSERT INTO categorias(nome) VALUES (1);
INSERT INTO categorias(nome) VALUES (2);
INSERT INTO categorias(nome) VALUES (3);
INSERT INTO categorias(nome) VALUES (4);

-- Dias da Semana --
INSERT INTO dias_semanas (id, nome) VALUES
(1, 'domingo'),
(2, 'segunda-feira'),
(3, 'terça-feira'),
(4, 'quarta-feira'),
(5, 'quinta-feira'),
(6, 'sexta-feira'),
(7, 'sábado');

-- Usuários --
INSERT INTO usuario (nome, email, senha, imagem, codigo) VALUES
('João Silva', 'joao@gmail.com', '123456', '../icon/user.png', '1234spd'),
('Maria Souza', 'maria@gmail.com', '123456', '../icon/user.png', '5678abc'),
('Carlos Lima', 'carlos@gmail.com', '123456', '../icon/user.png', '9101xyz');

-- Serviços --
INSERT INTO servicos (nome, imagem, descricao, horario_inicio, horario_fim, data_limite, id_categoria, id_prestador, id_condominio) VALUES 
('Serviço de Limpeza', 
 'img/faxineira.jpg', 
 'Limpeza completa de apartamentos.', 
 '08:00:00', 
 '16:00:00', 
 '2026-04-10', 
 2, 
 2, 
 2), ('Cuidador de Idosos', 
 'img/cuidador-de-cachorro.jpg', 
 'Cuidados diários para idosos.', 
 '09:00:00', 
 '18:00:00', 
 '2026-04-15', 
 3, 
 3, 
 3), ('Aula Particular de Matemática', 
 'img/a-mostra.jpg', 
 'Reforço escolar para ensino fundamental.', 
 '14:00:00', 
 '17:00:00', 
 '2026-05-01', 
 4, 
 1, 
 1);
 
 -- Contratos --
 INSERT INTO contratados (dia, horario, id_condominio, id_cliente, id_servico, confirmado) VALUES
('2026-03-10', '09:00:00', 1, 1, 1, 'confirmado'),
('2026-03-12', '10:00:00', 2, 2, 2, 'pendente'),
('2026-03-15', '15:00:00', 3, 3, 3, 'concluido');
 
SELECT * FROM usuario;

SELECT * FROM condominio;