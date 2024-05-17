CREATE DATABASE reserva_sala;
USE reserva_sala;

CREATE TABLE sala (
	Id INT AUTO_INCREMENT PRIMARY KEY,
    Tipo VARCHAR(50),
    Capacidade INT
);

CREATE TABLE docente (
	Id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(100) NOT NULL
);

CREATE TABLE curso (
	Id INT AUTO_INCREMENT PRIMARY KEY,
	Nome VARCHAR(150),
    Sigla VARCHAR(30),
    SubArea VARCHAR(50)
);

CREATE TABLE subarea (
	Id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(150) NOT NULL,
    Cor VARCHAR(50) NOT NULL
);

CREATE TABLE turma (
	Id INT AUTO_INCREMENT PRIMARY KEY,
    Docente_ID INT,
    Curso_ID INT,
    Cod_Turma VARCHAR(50) NOT NULL,
    Periodo ENUM("Manhâ", "Tarde", "Noite"),    
    FOREIGN KEY (Curso_ID) REFERENCES curso(Id),
    FOREIGN KEY (Docente_ID) REFERENCES docente(Id)
);

CREATE TABLE reserva (
	Id INT AUTO_INCREMENT PRIMARY KEY,
    Sala_ID INT,
    Turma_ID INT,
    Status ENUM("Livre", "Reservado", "Manuteçâo"),
    Data_Inicio DATETIME,
    Data_FIM DATETIME,    
    FOREIGN KEY (Turma_ID) REFERENCES Turma(Id),
    FOREIGN KEY (Sala_ID) REFERENCES Sala(Id)
);

USE reserva_sala;

-- Inserir dados na tabela subarea
INSERT INTO subarea (Nome, Cor) VALUES 
('Computação', 'Azul'),
('Matemática', 'Verde');

-- Inserir dados na tabela curso
INSERT INTO curso (Nome, Sigla, SubArea) VALUES 
('Engenharia de Software', 'ES', 'Computação'),
('Matemática Aplicada', 'MA', 'Matemática');

-- Inserir dados na tabela docente
INSERT INTO docente (Nome) VALUES 
('Carlos Silva'),
('Maria Oliveira');

-- Inserir dados na tabela sala
INSERT INTO sala (Tipo, Capacidade) VALUES 
('Laboratório', 40),
('Sala de Aula', 30);

-- Inserir dados na tabela turma
INSERT INTO turma (Docente_ID, Curso_ID, Cod_Turma, Periodo) VALUES 
(1, 1, 'ES101', 'Manhã'),
(2, 2, 'MA202', 'Tarde');

-- Inserir dados na tabela reserva
INSERT INTO reserva (Sala_ID, Turma_ID, Status, Data_Inicio, Data_FIM) VALUES 
(1, 1, 'Reservado', '2024-05-16 08:00:00', '2024-05-16 10:00:00'),
(2, 2, 'Reservado', '2024-05-16 14:00:00', '2024-05-16 16:00:00'),
(1, 1, 'Reservado', '2024-05-16 08:00:00', '2024-05-16 09:00:00'),
(1, 2, 'Reservado', '2024-05-16 09:00:00', '2024-05-16 10:00:00'),
(2, 1, 'Reservado', '2024-05-16 14:00:00', '2024-05-16 15:00:00'),
(2, 2, 'Reservado', '2024-05-16 15:00:00', '2024-05-16 16:00:00'),
(1, 1, 'Reservado', '2024-05-16 18:00:00', '2024-05-16 19:00:00'),
(2, 2, 'Reservado', '2024-05-16 19:00:00', '2024-05-16 20:00:00');