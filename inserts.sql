INSERT INTO turma (nome_identificacao, curso, ano_inicio, ano_conclusao, serie)
VALUES
  ('1º A - Enfermagem', 'Enfermagem', 2024, 2026, 1),
  ('1º B - Informática', 'Informática', 2024, 2026, 1),
  ('1º C - Comércio', 'Comércio', 2024, 2026, 1),
  ('1º D - Administração', 'Administração', 2024, 2026, 1),
  ('2º A - Enfermagem', 'Enfermagem', 2023, 2025, 1),
  ('2º B - Informática', 'Informática', 2023, 2025, 1),
  ('2º C - Comércio', 'Comércio', 2023, 2025, 1),
  ('2º D - Administração', 'Administração', 2023, 2025, 1);
  ('3º A - Enfermagem', 'Enfermagem', 2022, 2024, 1),
  ('3º B - Informática', 'Informática', 2022, 2024, 1),
  ('3º C - Comércio', 'Comércio', 2022, 2024, 1),
  ('3º D - Administração', 'Administração', 2022, 2024, 1);


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
  ((SELECT id FROM aluno WHERE matricula = '123456'), '123456', 'Livro de Informática', '123456', 'Informática', '1', '2023-03-01 10:00:00', '2023-03-15 10:00:00', 'Bibliotecário 1', 'Emprestado'),
  ((SELECT id FROM aluno WHERE matricula = '234567'), '234567', 'Livro de Administração', '234567', 'Administração', '2', '2023-04-01 10:00:00', '2023-04-15 10:00:00', 'Bibliotecário 2', 'Emprestado'),
  ((SELECT id FROM aluno WHERE matricula = '345678'), '345678', 'Livro de Engenharia', '345678', 'Engenharia', '3', '2023-05-01 10:00:00', '2023-05-15 10:00:00', 'Bibliotecário 3', 'Emprestado');

