DROP DATABASE IF EXISTS bd_apoi_me;

CREATE DATABASE IF NOT EXISTS bd_apoi_me 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_0900_ai_ci;

USE bd_apoi_me;

-- Tabela: condominio
CREATE TABLE condominio (
  id INT NOT NULL AUTO_INCREMENT,
  codigo VARCHAR(10) NOT NULL,
  nome VARCHAR(70) NOT NULL,
  foto VARCHAR(300) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY codigo (codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: categorias
CREATE TABLE categorias (
  id INT NOT NULL AUTO_INCREMENT,
  nome ENUM('manutenção','domésticos','cuidados','educação') NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: dias_semanas
CREATE TABLE dias_semanas (
  id INT NOT NULL,
  nome ENUM('domingo','segunda-feira','terça-feira','quarta-feira','quinta-feira','sexta-feira','sábado') NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY nome (nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: usuario
CREATE TABLE usuario (
  id INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(50) NOT NULL,
  email VARCHAR(100) DEFAULT NULL,
  senha VARCHAR(255) NOT NULL,
  imagem VARCHAR(300) NOT NULL,
  codigo VARCHAR(10) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY email (email),
  KEY fk_usuario_condominio (codigo),
  CONSTRAINT fk_usuario_condominio FOREIGN KEY (codigo) REFERENCES condominio (codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: servicos
CREATE TABLE servicos (
  id INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(70) NOT NULL,
  imagem VARCHAR(300) NOT NULL,
  descricao VARCHAR(500) NOT NULL,
  horario_inicio TIME DEFAULT NULL,
  horario_fim TIME DEFAULT NULL,
  data_limite DATE NOT NULL,
  id_categoria INT NOT NULL,
  id_prestador INT NOT NULL,
  codigo VARCHAR(10) NOT NULL,
  PRIMARY KEY (id),
  KEY fk_servicos_categoria (id_categoria),
  KEY fk_servicos_prestador (id_prestador),
  KEY fk_servicos_condominio (codigo),
  CONSTRAINT fk_servicos_categoria FOREIGN KEY (id_categoria) REFERENCES categorias (id),
  CONSTRAINT fk_servicos_condominio FOREIGN KEY (codigo) REFERENCES condominio (codigo),
  CONSTRAINT fk_servicos_prestador FOREIGN KEY (id_prestador) REFERENCES usuario (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: disponibilidade
CREATE TABLE disponibilidade (
  id_dia INT NOT NULL,
  id_servicos INT NOT NULL,
  PRIMARY KEY (id_dia, id_servicos),
  KEY fk_disponibilidade_servicos (id_servicos),
  CONSTRAINT fk_dia FOREIGN KEY (id_dia) REFERENCES dias_semanas (id),
  CONSTRAINT fk_disponibilidade_servicos FOREIGN KEY (id_servicos) REFERENCES servicos (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: contratados
CREATE TABLE contratados (
  id INT NOT NULL AUTO_INCREMENT,
  dia DATE NOT NULL,
  horario TIME NOT NULL,
  id_condominio INT NOT NULL,
  id_cliente INT NOT NULL,
  id_servico INT NOT NULL,
  confirmado ENUM('pendente','confirmado','concluido') DEFAULT 'pendente',
  PRIMARY KEY (id),
  UNIQUE KEY unique_agenda (dia, horario, id_servico),
  KEY fk_contratados_condominio (id_condominio),
  KEY fk_contratados_usuario (id_cliente),
  KEY fk_contratados_servico (id_servico),
  CONSTRAINT fk_contratados_condominio FOREIGN KEY (id_condominio) REFERENCES condominio (id),
  CONSTRAINT fk_contratados_servico FOREIGN KEY (id_servico) REFERENCES servicos (id),
  CONSTRAINT fk_contratados_usuario FOREIGN KEY (id_cliente) REFERENCES usuario (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela: comentarios
CREATE TABLE comentarios (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nome VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  telefone VARCHAR(15) NOT NULL UNIQUE,
  mensagem VARCHAR(500) NOT NULL,
  CONSTRAINT fk_email_comentario FOREIGN KEY (email) REFERENCES usuario (email)
);

-- Condomínios
INSERT INTO condominio (codigo, nome, foto) VALUES
('1234spd', 'Edifício Teste', 'url_da_foto.jpg'),
('5678abc', 'Residencial Primavera', 'img/condominio.png'),
('9101xyz', 'Condomínio Bela Vista', 'img/condominio.png');

-- Categorias (Usando os nomes definidos no ENUM)
INSERT INTO categorias (nome) VALUES ('manutenção'), ('domésticos'), ('cuidados'), ('educação');

-- Dias da Semana
INSERT INTO dias_semanas (id, nome) VALUES
(1, 'domingo'), (2, 'segunda-feira'), (3, 'terça-feira'), (4, 'quarta-feira'), 
(5, 'quinta-feira'), (6, 'sexta-feira'), (7, 'sábado');

-- Usuários
INSERT INTO usuario (nome, email, senha, imagem, codigo) VALUES
('João Silva', 'joao@gmail.com', '$2y$10$wl7XMWQUP4nO7kMMcc1WxOjCuBiMI1.pP2r9M1pAELLUWa5a0B/fW', '../icon/user.png', '1234spd'),
('Maria Souza', 'maria@gmail.com', '$2y$10$hcjesuxWlNg9gxAg0zedOOrYqIoieDPlzZPxQuAg161VGJcJ753YO', '../icon/user.png', '5678abc'),
('Carlos Lima', 'carlos@gmail.com', '$2y$10$tSQzO6m5zqUIpTEPpsItNOICPx4hVelPVzxOB5zwTKYb.XsHjOmc6', '../icon/user.png', '9101xyz');

-- Serviços
INSERT INTO servicos (nome, imagem, codigo, descricao, horario_inicio, horario_fim, data_limite, id_categoria, id_prestador) VALUES 
('Serviço de Limpeza', 'img/faxineira.jpg', '1234spd', 'Limpeza completa de apartamentos.', '08:00:00', '16:00:00', '2026-04-10', 2, 2),
('Cuidador de Idosos', 'img/cuidador-de-cachorro.jpg', '5678abc', 'Cuidados diários para idosos.', '09:00:00', '18:00:00', '2026-04-15', 3, 3),
('Aula Particular de Matemática', 'img/a-mostra.jpg', '9101xyz', 'Reforço escolar para ensino fundamental.', '14:00:00', '17:00:00', '2026-05-01', 4, 1);

-- Contratos
INSERT INTO contratados (dia, horario, id_condominio, id_cliente, id_servico, confirmado) VALUES
('2026-03-10', '09:00:00', 1, 1, 1, 'confirmado'),
('2026-03-12', '10:00:00', 2, 2, 2, 'pendente'),
('2026-03-15', '15:00:00', 3, 3, 3, 'concluido');