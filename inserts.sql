INSERT INTO turma (nome_identificacao, curso, ano_inicio, ano_conclusao, serie)
VALUES
  ('Turma A', 'Informática', 2020, 2023, 1),
  ('Turma B', 'Administração', 2021, 2024, 2),
  ('Turma C', 'Engenharia', 2022, 2025, 3);

INSERT INTO aluno (numero, matricula, cpf, nome_completo, sala_identificacao, curso, serie)
VALUES
  (1, 123456, 123456789, 'João Pedro', 'Turma A', 'Informática', '1'),
  (2, 234567, 234567890, 'Maria Luiza', 'Turma B', 'Administração', '2'),
  (3, 345678, 345678901, 'Pedro Henrique', 'Turma C', 'Engenharia', '3');

INSERT INTO livros (titulo_livro, autor, editora, ano_aquisicao, origem, local, genero, cdd, cdu, numero_registro)
VALUES
  ('Livro de Informática', 'Autor 1', 'Editora 1', 2020, 'Compra', 'Biblioteca', 'Tecnologia', '123', '456', '123456'),
  ('Livro de Administração', 'Autor 2', 'Editora 2', 2021, 'Doação', 'Biblioteca', 'Negócios', '789', '012', '234567'),
  ('Livro de Engenharia', 'Autor 3', 'Editora 3', 2022, 'Compra', 'Biblioteca', 'Tecnologia', '345', '678', '345678');

INSERT INTO emprestimos (aluno_id, matricula, titulo_livro, numero_registro, curso, serie, data_emprestimo, data_devolucao, nome_bibliotecario, status)
VALUES
  (1, '123456', 'Livro de Informática', '123456', 'Informática', '1', '2023-03-01 10:00:00', '2023-03-15 10:00:00', 'Bibliotecário 1', 'Emprestado'),
  (2, '234567', 'Livro de Administração', '234567', 'Administração', '2', '2023-04-01 10:00:00', '2023-04-15 10:00:00', 'Bibliotecário 2', 'Emprestado'),
  (3, '345678', 'Livro de Engenharia', '345678', 'Engenharia', '3', '2023-05-01 10:00:00', '2023-05-15 10:00:00', 'Bibliotecário 3', 'Emprestado');
