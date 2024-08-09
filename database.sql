CREATE DATABASE IF NOT EXISTS `eeepma26_biblioteca`;
USE `eeepma26_biblioteca`;

-- Criar a tabela bibliotecario
CREATE TABLE IF NOT EXISTS bibliotecario(
  id INT AUTO_INCREMENT,
  usuario VARCHAR(50) NOT NULL,
  senha VARCHAR(50) NOT NULL,
  PRIMARY KEY (id)
);

-- Criar a tabela turma
CREATE TABLE IF NOT EXISTS turma(
  id INT AUTO_INCREMENT,
  nome_identificacao VARCHAR(30),
  curso VARCHAR(30),
  ano_inicio YEAR(4),
  ano_conclusao YEAR(4),
  atividade INT,
  serie INT,
  PRIMARY KEY (id),
  UNIQUE (nome_identificacao, curso, serie)
);

-- Criar a tabela aluno com a chave estrangeira correta
CREATE TABLE IF NOT EXISTS aluno(
  id INT AUTO_INCREMENT,
  numero INT(2),
  matricula INT(10),
  cpf VARCHAR(14),
  nome_completo VARCHAR(55),
  sala_identificacao VARCHAR(55),
  curso VARCHAR(15),
  serie VARCHAR(1),
  id_turma INT NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id_turma) REFERENCES turma(id)
);

-- Criar tabela Livros
CREATE TABLE IF NOT EXISTS livros(
  id INT AUTO_INCREMENT,
  titulo_livro VARCHAR(300) NOT NULL,
  autor VARCHAR(255) NOT NULL,
  editora VARCHAR(255) NOT NULL,
  ano_aquisicao YEAR(4),
  origem VARCHAR(100),
  local VARCHAR(255),
  genero VARCHAR(100),
  cdd VARCHAR(10),
  cdu VARCHAR(10),
  numero_registro VARCHAR(10) NOT NULL,
  imagem LONGBLOB,
  PRIMARY KEY (id),
  UNIQUE (titulo_livro, numero_registro)
);

-- Criar tabela Emprestimos
CREATE TABLE IF NOT EXISTS emprestimos(
  id INT AUTO_INCREMENT,
  aluno_id INT,
  matricula VARCHAR(7) NOT NULL,
  titulo_livro VARCHAR(300) NOT NULL,
  numero_registro VARCHAR(10) NOT NULL,
  curso VARCHAR(30) NOT NULL,
  serie VARCHAR(1) NOT NULL,
  data_emprestimo DATETIME,
  data_devolucao DATETIME,
  data_rascunho DATETIME,
  descricao VARCHAR(750),
  nome_bibliotecario VARCHAR(255) NOT NULL,
  status VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (aluno_id) REFERENCES aluno(id),
  FOREIGN KEY (titulo_livro, numero_registro) REFERENCES livros(titulo_livro, numero_registro)
);
