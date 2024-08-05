<div align="center">
  <h1 align="center">
     Sistema_Biblioteca
    <br />
    <br />
      <img src="(https://github.com/user-attachments/assets/7389160b-f082-4a3c-b7ce-be2a743d1bf0)" width"700px>
    </a>
  </h1>
</div>

![P-Final3_20240802193823](https://github.com/user-attachments/assets/7389160b-f082-4a3c-b7ce-be2a743d1bf0)

# Líder do projeto <br />
[![GitHub](https://img.shields.io/badge/GitHub-Henry-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/HenryV042) <br>

# Projeto de software <br />
[![GitHub](https://img.shields.io/badge/GitHub-Luiza-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/Nalu2) <br>
Jhonatta <br />
Vanessa <br /> 
Pedro Wesley <br />
Julia <br />
[![GitHub](https://img.shields.io/badge/GitHub-Alanna-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/AlanaLopes) <br>
Érica <br />
Maria clara <br />

# Colaboradores do design <br />
Kauã <br />
Karol<br /> 
Paulo Iago <br /> 
Kelly <br /> 
Ruan <br />
Samuel <br />
Angélica <br />

# Colaboradores do desenvolvimento
[![GitHub](https://img.shields.io/badge/GitHub-Matheus-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/Matheus) <br>
Paulo Henrique
<br />
Felipe
<br />
Gustavo Henrique
<br />
Josué
<br />
José Luiz
<br />
Anderson
<br />
[![GitHub](https://img.shields.io/badge/GitHub-Emerson-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/emerson096) <br>
Kevin
<br />
David
<br />


# Colaboradores de implementação
Breno 
 <br />
Gustavo Bandeira
 <br />
Francisco Flavio
 <br />
Gabriel Pinto
 <br />
Ciro 
 <br />
Nagila
<br />
Ycaro

# Colaboradores de documentação
[![GitHub](https://img.shields.io/badge/GitHub-Livia-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/liviacarvalho07) <br>
Gabriel Lima <br />
Pedro Iure <br />
Mylena <br />
Ytalo <br />
Arthur Oliveira <br />
[![GitHub](https://img.shields.io/badge/GitHub-Kalel-181717?style=for-the-badge&logo=github&logoColor=white)](https://github.com/KalelOliveira) <br>

# Descrição <br />
Esta equipe é responsável por registrar não somente a apresentação geral do projeto social e os objetivos da turma ao desenvolvê-lo, mas também pela descrição de todo o processo de criação do sistema. Os alunos envolvidos neste processo assumiram a responsabilidade de representar todos os grupos, visto que esta é uma parte imprescindível para garantir a validação do esforço de cada componente deste projeto. O documento descreve minuciosamente cada fase, sendo organizado para apresentar os objetivos e cada etapa do desenvolvimento, detalhando os seus autores, datas de início e término, entre outras singularidades. Resumidamente, a documentação é o processo final de todo e qualquer projeto e foi elaborada com pleno compromisso em torná-la o mais compreensível possível para todas as partes interessadas, garantindo que o trabalho realizado possa ser avaliado e compreendido com clareza e precisão.

#




Criando o `Banco de Dados` 


```sql
CREATE DATABASE IF NOT EXISTS `biblioteca`;
USE `biblioteca`
````
Criando as `Tabelas`
```sql
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
  serie INT(1),
  PRIMARY KEY (id),
  UNIQUE (nome_identificacao, curso, serie)
);

-- Criar a tabela aluno com a chave estrangeira composta
CREATE TABLE IF NOT EXISTS aluno(
  id INT AUTO_INCREMENT,
  numero INT(2),
  matricula INT(10),
  cpf INT(11),
  nome_completo VARCHAR(55),
  sala_identificacao VARCHAR(55),
  curso VARCHAR(15),
  serie VARCHAR(1),
  PRIMARY KEY (id),
  FOREIGN KEY (sala_identificacao, curso) REFERENCES turma(nome_identificacao, curso)
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
  numero_registro VARCHAR(10),
  curso VARCHAR(30) NOT NULL,
  serie VARCHAR(1) NOT NULL,
  data_emprestimo TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  data_devolucao DATETIME,
  data_rascunho DATETIME,
  descricao VARCHAR(750),
  nome_bibliotecario VARCHAR(255) NOT NULL,
  status VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (aluno_id) REFERENCES aluno(id),
  FOREIGN KEY (titulo_livro, numero_registro) REFERENCES livros(titulo_livro, numero_registro)
);
```
