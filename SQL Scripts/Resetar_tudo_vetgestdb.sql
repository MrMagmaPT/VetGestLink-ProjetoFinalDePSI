-- ======================================================
-- SCRIPT: Limpeza completa da base de dados vetgestdb
-- Descrição: Remove todos os dados das tabelas,
--             reinicia AUTO_INCREMENT e mantém estrutura.
--             As tabelas auth_item e auth_item_child são preservadas.
-- ======================================================

USE vetgestdb;

-- ------------------------------------------------------
-- Desativar verificações temporárias de integridade
-- ------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------
-- Limpar tabelas relacionadas a permissões e RBAC (exceto auth_item e auth_item_child)
-- ------------------------------------------------------
TRUNCATE TABLE auth_assignment;
TRUNCATE TABLE auth_rule;

-- ------------------------------------------------------
-- Limpar tabelas de lembretes e anotações
-- ------------------------------------------------------
TRUNCATE TABLE lembretes;
TRUNCATE TABLE notas;

-- ------------------------------------------------------
-- Limpar tabelas de faturas e relacionamentos
-- ------------------------------------------------------
TRUNCATE TABLE linhasfaturas;
TRUNCATE TABLE faturas;

-- ------------------------------------------------------
-- Limpar tabelas de marcações, serviços e pagamentos
-- ------------------------------------------------------
TRUNCATE TABLE marcacoes;
TRUNCATE TABLE servicos;
TRUNCATE TABLE metodospagamentos;

-- ------------------------------------------------------
-- Limpar tabelas de medicamentos e categorias
-- ------------------------------------------------------
TRUNCATE TABLE medicamentos;
TRUNCATE TABLE categorias;

-- ------------------------------------------------------
-- Limpar tabelas de moradas
-- ------------------------------------------------------
TRUNCATE TABLE moradas;

-- ------------------------------------------------------
-- Limpar tabelas de animais e dependências
-- ------------------------------------------------------
TRUNCATE TABLE animais;
TRUNCATE TABLE racas;
TRUNCATE TABLE especies;

-- ------------------------------------------------------
-- Limpar tabelas de utilizadores e perfis
-- ------------------------------------------------------
TRUNCATE TABLE userprofiles;
TRUNCATE TABLE user;

-- ------------------------------------------------------
-- Limpar tabela de migrações (se necessário)
-- ------------------------------------------------------
TRUNCATE TABLE migration;

-- ------------------------------------------------------
-- Reiniciar AUTO_INCREMENT manualmente (garantia extra)
-- ------------------------------------------------------
ALTER TABLE auth_assignment AUTO_INCREMENT = 1;
ALTER TABLE auth_rule AUTO_INCREMENT = 1;
ALTER TABLE auth_item_child AUTO_INCREMENT = 1;
ALTER TABLE auth_item AUTO_INCREMENT = 1;

ALTER TABLE lembretes AUTO_INCREMENT = 1;
ALTER TABLE notas AUTO_INCREMENT = 1;
ALTER TABLE linhasfaturas AUTO_INCREMENT = 1;
ALTER TABLE faturas AUTO_INCREMENT = 1;
ALTER TABLE marcacoes AUTO_INCREMENT = 1;
ALTER TABLE medicamentos AUTO_INCREMENT = 1;
ALTER TABLE categorias AUTO_INCREMENT = 1;
ALTER TABLE servicos AUTO_INCREMENT = 1;
ALTER TABLE metodospagamentos AUTO_INCREMENT = 1;
ALTER TABLE moradas AUTO_INCREMENT = 1;
ALTER TABLE animais AUTO_INCREMENT = 1;
ALTER TABLE racas AUTO_INCREMENT = 1;
ALTER TABLE especies AUTO_INCREMENT = 1;
ALTER TABLE userprofiles AUTO_INCREMENT = 1;
ALTER TABLE user AUTO_INCREMENT = 1;
ALTER TABLE migration AUTO_INCREMENT = 1;

-- ------------------------------------------------------
-- Reativar verificações de integridade
-- ------------------------------------------------------
SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------
-- Fim do Script
-- ------------------------------------------------------
SELECT '✅ Base de dados vetgestdb limpa (exceto auth_item e auth_item_child) e AUTO_INCREMENT reiniciado!' AS resultado;