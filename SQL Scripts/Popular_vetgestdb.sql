-- ======================================================
-- SCRIPT COMPLETO DE POPULAÇÃO - vetgestdb
-- Inclui utilizadores, papéis (roles) e dados de exemplo
-- Autor: ChatGPT (GPT-5)
-- ======================================================

USE vetgestdb;
SET FOREIGN_KEY_CHECKS = 0;

-- ======================================================
-- 1) ROLES (auth_item)
-- ======================================================
INSERT IGNORE INTO auth_item
  (name, type, description, rule_name, data, created_at, updated_at)
VALUES
  ('admin', 1, 'Papel de administrador do sistema', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
  ('cliente', 1, 'Papel de cliente/utente', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
  ('rececionista', 1, 'Papel de rececionista/atendimento', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
  ('veterinario', 1, 'Papel de médico veterinário', NULL, NULL, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- ======================================================
-- 2) UTILIZADORES
-- ======================================================
-- Passwords para teste:
-- admin: AdminPass123!
-- cliente: ClientPass123!
-- rececionista: RecepPass123!
-- veterinario: VetPass123!

-- Admin
INSERT INTO user (username, auth_key, password_hash, email, status, created_at, updated_at, verification_token)
VALUES (
  'admin',
  'ak_admin_00000000000000000000000000',
  '$2y$12$8mbFFUJT1ayT31ISuKk2rOCdgA4qfuyBiew369CKmjCzmUYHT.rHS',
  'admin@vetgest.pt',
  10,
  UNIX_TIMESTAMP(),
  UNIX_TIMESTAMP(),
  NULL
);
SET @id_admin = LAST_INSERT_ID();

-- Cliente
INSERT INTO user (username, auth_key, password_hash, email, status, created_at, updated_at, verification_token)
VALUES (
  'cliente',
  'ak_cliente_000000000000000000000000',
  '$2y$12$uOB/gmXNbiqGCJDAAr62GeijghRLZQpiDpXhN0KcT1ONDAu4b3yxC',
  'cliente@vetgest.pt',
  10,
  UNIX_TIMESTAMP(),
  UNIX_TIMESTAMP(),
  NULL
);
SET @id_cliente = LAST_INSERT_ID();

-- Rececionista
INSERT INTO user (username, auth_key, password_hash, email, status, created_at, updated_at, verification_token)
VALUES (
  'rececionista',
  'ak_rececionista_00000000000000000000',
  '$2y$12$RCier3UuRzUZP5l7cIleCenp5iVhRSO7h5VcXFT3wyrIYG7TlSgDK',
  'rececionista@vetgest.pt',
  10,
  UNIX_TIMESTAMP(),
  UNIX_TIMESTAMP(),
  NULL
);
SET @id_recepcionista = LAST_INSERT_ID();

-- Veterinário
INSERT INTO user (username, auth_key, password_hash, email, status, created_at, updated_at, verification_token)
VALUES (
  'veterinario',
  'ak_veterinario_000000000000000000000000',
  '$2y$12$ljRyGNSetL0iqRX2CrpH7OOtBKFlV36vsb5PP0Wzu2xnHbpYQU4mO',
  'veterinario@vetgest.pt',
  10,
  UNIX_TIMESTAMP(),
  UNIX_TIMESTAMP(),
  NULL
);
SET @id_veterinario = LAST_INSERT_ID();

-- ======================================================
-- 3) AUTH ASSIGNMENT
-- ======================================================
INSERT INTO auth_assignment (item_name, user_id, created_at)
VALUES
  ('admin', CAST(@id_admin AS CHAR), UNIX_TIMESTAMP()),
  ('cliente', CAST(@id_cliente AS CHAR), UNIX_TIMESTAMP()),
  ('rececionista', CAST(@id_recepcionista AS CHAR), UNIX_TIMESTAMP()),
  ('veterinario', CAST(@id_veterinario AS CHAR), UNIX_TIMESTAMP());

-- ======================================================
-- 4) ESPÉCIES
-- ======================================================
INSERT INTO especies (nome, eliminado)
VALUES
('Cão', 0),
('Gato', 0);

-- ======================================================
-- 5) RAÇAS
-- ======================================================
INSERT INTO racas (nome, especies_id, eliminado)
VALUES
('Labrador Retriever', 1, 0),
('Pastor Alemão', 1, 0),
('Siamês', 2, 0),
('Persa', 2, 0);

-- ======================================================
-- 6) PERFIS DE UTILIZADOR
-- ======================================================
INSERT INTO userprofiles (nomecompleto, nif, telemovel, dtanascimento, foto, eliminado, user_id)
VALUES
('Administrador VetGest', '123456789', '910000000', '1985-01-01', NULL, 0, @id_admin),
('Maria Silva', '987654321', '919999999', '1990-05-20', NULL, 0, @id_cliente),
('Ana Costa', '112233445', '911223344', '1992-03-15', NULL, 0, @id_recepcionista),
('Dr. João Sousa', '556677889', '912345678', '1980-08-10', NULL, 0, @id_veterinario);

SET @id_admin_prof = (SELECT id FROM userprofiles WHERE user_id=@id_admin);
SET @id_cliente_prof = (SELECT id FROM userprofiles WHERE user_id=@id_cliente);
SET @id_recepcionista_prof = (SELECT id FROM userprofiles WHERE user_id=@id_recepcionista);
SET @id_vet_prof = (SELECT id FROM userprofiles WHERE user_id=@id_veterinario);

-- ======================================================
-- 7) MORADAS (SEM campo eliminado)
-- ======================================================
INSERT INTO moradas (rua, nporta, andar, cdpostal, localidade, cidade, cxpostal, principal, userprofiles_id)
VALUES
('Rua das Flores', '12', 'R/C', '1000-001', 'Lisboa', 'Lisboa', NULL, 1, @id_admin_prof),
('Av. Central', '45', '3º Esq', '4000-200', 'Porto', 'Porto', NULL, 1, @id_cliente_prof),
('Rua do Comércio', '33', '2º', '1000-100', 'Lisboa', 'Lisboa', NULL, 1, @id_recepcionista_prof),
('Rua Veterinária', '21', NULL, '1500-050', 'Lisboa', 'Lisboa', NULL, 1, @id_vet_prof);

-- ======================================================
-- 8) ANIMAIS
-- ======================================================
INSERT INTO animais (nome, dtanascimento, peso, microship, sexo, foto, especies_id, racas_id, userprofiles_id, eliminado)
VALUES
('Bobby', '2020-03-10', 12.5, 1, 'M', NULL, 1, 1, @id_cliente_prof, 0),
('Luna', '2021-06-22', 4.2, 0, 'F', NULL, 2, 3, @id_cliente_prof, 0);

-- ======================================================
-- 9) CATEGORIAS
-- ======================================================
INSERT INTO categorias (nome, eliminado)
VALUES
('Antibióticos', 0),
('Analgésicos', 0),
('Vacinas', 0),
('Antiparasitários', 0),
('Suplementos', 0);

-- ======================================================
-- 10) MEDICAMENTOS
-- ======================================================
INSERT INTO medicamentos (nome, descricao, preco, quantidade, categorias_id, eliminado)
VALUES
('Amoxicilina', 'Antibiótico de largo espectro', 12.50, 100, 1, 0),
('Carprofeno', 'Analgésico e anti-inflamatório', 9.90, 80, 2, 0),
('Vacina Antirrábica', 'Vacina contra a raiva', 15.00, 50, 3, 0),
('Ivermectina', 'Antiparasitário oral', 7.50, 120, 4, 0),
('Suplemento Omega 3', 'Melhora a pelagem e imunidade', 11.00, 60, 5, 0);

-- ======================================================
-- 11) MÉTODOS DE PAGAMENTO
-- ======================================================
INSERT INTO metodospagamentos (nome, vigor, eliminado)
VALUES
('Dinheiro', 1, 0),
('Cartão de Crédito', 1, 0),
('MB Way', 1, 0),
('Transferência Bancária', 1, 0);

-- ======================================================
-- 12) SERVIÇOS
-- ======================================================
INSERT INTO servicos (nome, valor, eliminado)
VALUES
('Consulta Geral', 35.00, 0),
('Vacinação', 25.00, 0),
('Banho e Tosa', 20.00, 0),
('Desparasitação', 18.00, 0),
('Cirurgia', 120.00, 0);

-- ======================================================
-- 13) MARCAÇÕES
-- ======================================================
INSERT INTO marcacoes (data, horainicio, horafim, diagnostico, estado, created_at, updated_at, servicos_id, animais_id, userprofiles_id, eliminado)
VALUES
('2025-11-15', '10:00:00', '10:30:00', 'Consulta de rotina - animal saudável', 'realizada', NOW(), NOW(), 1, 1, @id_cliente_prof, 0),
('2025-11-20', '15:00:00', '15:30:00', NULL, 'pendente', NOW(), NOW(), 2, 2, @id_cliente_prof, 0);

-- ======================================================
-- 14) FATURAS
-- ======================================================
INSERT INTO faturas (total, estado, created_at, metodospagamentos_id, userprofiles_id, eliminado)
VALUES
(35.00, 1, NOW(), 1, @id_cliente_prof, 0),
(25.00, 0, NOW(), 3, @id_cliente_prof, 0);

-- ======================================================
-- 15) LINHAS DE FATURA
-- ======================================================
INSERT INTO linhasfaturas (total, quantidade, vendidoemconsulta, faturas_id, medicamentos_id, marcacoes_id, eliminado)
VALUES
(12.50, 1, 0, 1, 1, NULL, 0),
(25.00, 1, 1, 2, NULL, 2, 0);

-- ======================================================
-- 16) NOTAS
-- ======================================================
INSERT INTO notas (nota, created_at, updated_at, userprofiles_id, animais_id)
VALUES
('Animal apresentou sintomas leves de alergia.', NOW(), NOW(), @id_cliente_prof, 1),
('Recomendado reforço de vacina em 6 meses.', NOW(), NOW(), @id_cliente_prof, 2);

-- ======================================================
-- 17) LEMBRETES (opcional, se necessário)
-- ======================================================
INSERT INTO lembretes (descricao, created_at, updated_at, userprofiles_id)
VALUES
('Lembrar cliente sobre reforço de vacina', NOW(), NOW(), @id_vet_prof),
('Verificar stock de Amoxicilina', NOW(), NOW(), @id_recepcionista_prof);

SET FOREIGN_KEY_CHECKS = 1;

-- ======================================================
-- FIM DO SCRIPT
-- ======================================================
SELECT 'Base vetgestdb populada com sucesso (utilizadores, roles e dados de exemplo)' AS resultado;