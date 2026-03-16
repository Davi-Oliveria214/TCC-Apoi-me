CREATE TYPE tipo_categorias AS ENUM ('Domésticos', 'Cuidados', 'Educação', 'Manutenção');
CREATE TYPE tipo_dias AS ENUM ('domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado');
CREATE TYPE tipo_status AS ENUM ('pendente', 'confirmado', 'concluido');

create table condominios(
id serial primary key,
nome text not null,
codigo int not null unique
);

create table usuarios(
id serial primary key,
nome text not null,
email text not null unique,
senha varchar(300) not null,
telefone varchar(15) not null unique,
imagem text not null,
codigo int not null,
constraint fk_usuario_codigo
foreign key(codigo) references condominios(codigo)
);

create table categorias(
id serial primary key,
nome tipo_categorias not null unique
);

create table dias_semana(
id serial primary key,
nome tipo_dias not null
);

create table servicos(
id serial primary key,
nome text not null,
descricao text not null,
hora_inicio time,
hora_fim time,
dia date,
imagem text not null,
id_prestador int not null,
categoria int not null,
codigo int not null,
constraint fk_servico_prestador
foreign key(id_prestador) references usuarios(id),
constraint fk_servico_categoria
foreign key(categoria) references categorias(id),
constraint fk_servico_codigo
foreign key(codigo) references condominios(codigo)
);

create table contratados(
id serial primary key,
hora time not null,
dia date not null,
id_servico int not null,
id_prestador int not null,
id_cliente int not null,
confirmado tipo_status default 'pendente',
constraint fk_contrato_servico
foreign key(id_servico) references servicos(id),
constraint fk_contrato_prestador
foreign key(id_prestador) references usuarios(id),
constraint fk_contrato_cliente
foreign key(id_cliente) references usuarios(id),
check (id_cliente <> id_prestador)
);

create table disponibilidade(
id_dia int not null,
id_servico int not null,
primary key(id_dia, id_servico),
constraint fk_disponivel_dia
foreign key(id_dia) references dias_semana(id),
constraint fk_disponivel_servico
foreign key(id_servico) references servicos(id)
);

create table feedback(
id serial primary key,
nome text not null,
email text not null unique,
telefone varchar(12) not null unique,
mensagem varchar(500) not null,
nota int check (nota between 1 and 5),
respondido boolean default false,
data_envio timestamp default current_timestamp
);

INSERT INTO condominios (nome, codigo) VALUES 
('Edifício Bela Vista', 1010);

INSERT INTO categorias (nome) VALUES 
('Domésticos'), ('Cuidados'), ('Educação'), ('Manutenção');

INSERT INTO dias_semana (nome) VALUES 
('domingo'), ('segunda-feira'), ('terça-feira'), ('quarta-feira'), 
('quinta-feira'), ('sexta-feira'), ('sábado');

INSERT INTO usuarios (nome, email, senha, telefone, imagem, codigo) VALUES 
('Carlos', 'carlos@email.com', 'senha123', '11999998888', 'img/mostrar.jpg', 1010),
('Ana', 'ana@email.com', 'senha123', '11977776666', 'img/mostrar.jpg', 1010),
('Davi', 'davi@email.com', 'senha123', '11955554444', 'img/condomino.png', 1010);

INSERT INTO servicos (nome, descricao, hora_inicio, hora_fim, imagem, id_prestador, categoria, codigo) VALUES 
('Instalação Elétrica', 'Reparos em chuveiros e fiação geral.', '08:00', '18:00', 'img/eletricista.jpg', 1, 4, 1010),
('Limpeza Residencial', 'Faxina completa ou pesada para apartamentos.', '07:30', '16:00', 'img/faxineira.jpg', 2, 1, 1010),
('Cuidador de Pets', 'Passeios e cuidados durante o dia.', '09:00', '17:00', 'img/cuidador-de-cachorro.jpg', 1, 2, 1010);

INSERT INTO disponibilidade (id_dia, id_servico) VALUES (2, 1), (4, 1);

INSERT INTO disponibilidade (id_dia, id_servico) VALUES (6, 2);

INSERT INTO contratados (hora, dia, id_servico, id_prestador, id_cliente, confirmado) VALUES 
('10:00', '2026-03-23', 1, 1, 3, 'pendente');