-- Adicionar coluna tipo à tabela bibliotecario
ALTER TABLE bibliotecario ADD tipo ENUM('bibliotecario', 'aluno') NOT NULL DEFAULT 'bibliotecario';

-- Adicionar coluna tipo à tabela aluno (opcional, se você quiser uma coluna separada)
ALTER TABLE aluno ADD tipo ENUM('aluno') NOT NULL DEFAULT 'aluno';
