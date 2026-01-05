# Base de Dados VetGestLink - Schema SQL Completo

## Instruções de Instalação

Execute os comandos SQL abaixo na ordem apresentada para criar a base de dados completa.

---

## ⚠️ LIMPEZA DA BASE DE DADOS (USE COM CUIDADO!)

**AVISO**: Este script elimina TODAS as tabelas da base de dados. Use apenas se quiser começar do zero!

```sql
-- =====================================================
-- DROP DE TODAS AS TABELAS (ORDEM INVERSA)
-- Execute este bloco apenas se quiser LIMPAR TUDO
-- =====================================================
USE `vetgestdb`;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `auth_assignment`;
DROP TABLE IF EXISTS `auth_item_child`;
DROP TABLE IF EXISTS `auth_item`;
DROP TABLE IF EXISTS `auth_rule`;
DROP TABLE IF EXISTS `lembretes`;
DROP TABLE IF EXISTS `linhasfaturas`;
DROP TABLE IF EXISTS `faturas`;
DROP TABLE IF EXISTS `metodospagamentos`;
DROP TABLE IF EXISTS `medicamentos`;
DROP TABLE IF EXISTS `categorias`;
DROP TABLE IF EXISTS `marcacoes`;
DROP TABLE IF EXISTS `servicos`;
DROP TABLE IF EXISTS `notas`;
DROP TABLE IF EXISTS `animais`;
DROP TABLE IF EXISTS `racas`;
DROP TABLE IF EXISTS `especies`;
DROP TABLE IF EXISTS `moradas`;
DROP TABLE IF EXISTS `userprofiles`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `migration`;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- RESETAR AUTO_INCREMENT DAS TABELAS
-- Execute se quiser apenas limpar dados mas manter estrutura
-- =====================================================
-- USE `vetgestdb`;
-- 
-- SET FOREIGN_KEY_CHECKS = 0;
-- 
-- TRUNCATE TABLE `auth_assignment`;
-- TRUNCATE TABLE `auth_item_child`;
-- TRUNCATE TABLE `auth_item`;
-- TRUNCATE TABLE `auth_rule`;
-- 
-- TRUNCATE TABLE `lembretes`;
-- ALTER TABLE `lembretes` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `linhasfaturas`;
-- ALTER TABLE `linhasfaturas` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `faturas`;
-- ALTER TABLE `faturas` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `metodospagamentos`;
-- ALTER TABLE `metodospagamentos` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `medicamentos`;
-- ALTER TABLE `medicamentos` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `categorias`;
-- ALTER TABLE `categorias` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `marcacoes`;
-- ALTER TABLE `marcacoes` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `servicos`;
-- ALTER TABLE `servicos` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `notas`;
-- ALTER TABLE `notas` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `animais`;
-- ALTER TABLE `animais` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `racas`;
-- ALTER TABLE `racas` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `especies`;
-- ALTER TABLE `especies` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `moradas`;
-- ALTER TABLE `moradas` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `userprofiles`;
-- ALTER TABLE `userprofiles` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `user`;
-- ALTER TABLE `user` AUTO_INCREMENT = 1;
-- 
-- TRUNCATE TABLE `migration`;
-- 
-- SET FOREIGN_KEY_CHECKS = 1;
```

---

## 📋 CRIAÇÃO DA BASE DE DADOS

```sql
-- =====================================================
-- CRIAR BASE DE DADOS
-- =====================================================
CREATE DATABASE IF NOT EXISTS `vetgestdb`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `vetgestdb`;

-- =====================================================
-- TABELA: migration
-- Controlo de migrações do Yii2
-- =====================================================
CREATE TABLE `migration` (
  `version` VARCHAR(180) NOT NULL,
  `apply_time` INT NULL DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: user
-- Sistema de autenticação Yii2
-- =====================================================
CREATE TABLE `user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `auth_key` VARCHAR(32) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `password_reset_token` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(255) NOT NULL,
  `status` SMALLINT NOT NULL DEFAULT 10,
  `created_at` INT NOT NULL,
  `updated_at` INT NOT NULL,
  `verification_token` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `password_reset_token_UNIQUE` (`password_reset_token` ASC)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: userprofiles
-- Perfis de utilizadores (clientes/veterinários)
-- =====================================================
CREATE TABLE `userprofiles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nomecompleto` VARCHAR(45) NOT NULL,
  `nif` VARCHAR(9) NOT NULL,
  `telemovel` VARCHAR(9) NOT NULL,
  `dtanascimento` DATE NOT NULL,
  `user_id` INT NOT NULL,
  `eliminado` TINYINT NOT NULL DEFAULT 0,
  `foto` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nif_UNIQUE` (`nif` ASC),
  INDEX `fk_userprofiles_user_idx` (`user_id` ASC),
  CONSTRAINT `fk_userprofiles_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: moradas
-- Endereços dos utilizadores
-- =====================================================
CREATE TABLE `moradas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `rua` VARCHAR(45) NOT NULL,
  `nporta` VARCHAR(45) NOT NULL,
  `andar` VARCHAR(45) NULL DEFAULT NULL,
  `cdpostal` VARCHAR(45) NOT NULL,
  `cidade` VARCHAR(45) NOT NULL,
  `cxpostal` VARCHAR(45) NULL DEFAULT NULL,
  `localidade` VARCHAR(45) NOT NULL,
  `principal` TINYINT NOT NULL DEFAULT 0,
  `userprofiles_id` INT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_moradas_userprofiles_idx` (`userprofiles_id` ASC),
  CONSTRAINT `fk_moradas_userprofiles`
    FOREIGN KEY (`userprofiles_id`)
    REFERENCES `userprofiles` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: especies
-- Espécies de animais (cão, gato, etc)
-- =====================================================
CREATE TABLE `especies` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `eliminado` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: racas
-- Raças de animais por espécie
-- =====================================================
CREATE TABLE `racas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `especies_id` INT NOT NULL,
  `eliminado` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_racas_especies_idx` (`especies_id` ASC),
  CONSTRAINT `fk_racas_especies`
    FOREIGN KEY (`especies_id`)
    REFERENCES `especies` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: animais
-- Animais dos clientes
-- =====================================================
CREATE TABLE `animais` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `dtanascimento` DATE NOT NULL,
  `peso` FLOAT NOT NULL,
  `microship` INT NOT NULL,
  `sexo` ENUM('M', 'F') NOT NULL,
  `especies_id` INT NOT NULL,
  `userprofiles_id` INT NOT NULL,
  `racas_id` INT NULL DEFAULT NULL,
  `eliminado` TINYINT NOT NULL DEFAULT 0,
  `foto` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_animais_especies_idx` (`especies_id` ASC),
  INDEX `fk_animais_userprofiles_idx` (`userprofiles_id` ASC),
  INDEX `fk_animais_racas_idx` (`racas_id` ASC),
  CONSTRAINT `fk_animais_especies`
    FOREIGN KEY (`especies_id`)
    REFERENCES `especies` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_animais_userprofiles`
    FOREIGN KEY (`userprofiles_id`)
    REFERENCES `userprofiles` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_animais_racas`
    FOREIGN KEY (`racas_id`)
    REFERENCES `racas` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: notas
-- Notas sobre animais
-- =====================================================
CREATE TABLE `notas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nota` VARCHAR(500) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `userprofiles_id` INT NOT NULL,
  `animais_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_notas_userprofiles_idx` (`userprofiles_id` ASC),
  INDEX `fk_notas_animais_idx` (`animais_id` ASC),
  CONSTRAINT `fk_notas_userprofiles`
    FOREIGN KEY (`userprofiles_id`)
    REFERENCES `userprofiles` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_notas_animais`
    FOREIGN KEY (`animais_id`)
    REFERENCES `animais` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: servicos
-- Serviços prestados pela clínica
-- =====================================================
CREATE TABLE `servicos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `valor` FLOAT NOT NULL,
  `eliminado` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: marcacoes
-- Marcações/Consultas veterinárias
-- =====================================================
CREATE TABLE `marcacoes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `data` DATE NOT NULL,
  `horainicio` TIME NOT NULL,
  `horafim` TIME NOT NULL,
  `diagnostico` VARCHAR(500) NULL DEFAULT NULL,
  `estado` ENUM('pendente', 'cancelada', 'realizada') NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `servicos_id` INT NOT NULL,
  `animais_id` INT NOT NULL,
  `userprofiles_id` INT NOT NULL,
  `eliminado` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_marcacoes_servicos_idx` (`servicos_id` ASC),
  INDEX `fk_marcacoes_animais_idx` (`animais_id` ASC),
  INDEX `fk_marcacoes_userprofiles_idx` (`userprofiles_id` ASC),
  CONSTRAINT `fk_marcacoes_servicos`
    FOREIGN KEY (`servicos_id`)
    REFERENCES `servicos` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_marcacoes_animais`
    FOREIGN KEY (`animais_id`)
    REFERENCES `animais` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_marcacoes_userprofiles`
    FOREIGN KEY (`userprofiles_id`)
    REFERENCES `userprofiles` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: categorias
-- Categorias de medicamentos
-- =====================================================
CREATE TABLE `categorias` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NOT NULL,
  `eliminado` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: medicamentos
-- Medicamentos disponíveis
-- IMPORTANTE: quantidade = stock disponível
-- =====================================================
CREATE TABLE `medicamentos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(250) NOT NULL,
  `preco` FLOAT NOT NULL,
  `quantidade` INT NOT NULL,
  `categorias_id` INT NOT NULL,
  `eliminado` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_medicamentos_categorias_idx` (`categorias_id` ASC),
  CONSTRAINT `fk_medicamentos_categorias`
    FOREIGN KEY (`categorias_id`)
    REFERENCES `categorias` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: metodospagamentos
-- Métodos de pagamento disponíveis
-- =====================================================
CREATE TABLE `metodospagamentos` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `vigor` TINYINT NOT NULL DEFAULT 1,
  `eliminado` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: faturas
-- Faturas/Invoices
-- =====================================================
CREATE TABLE `faturas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `total` FLOAT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `estado` VARCHAR(45) NOT NULL,
  `metodospagamentos_id` INT NOT NULL,
  `userprofiles_id` INT NOT NULL,
  `eliminado` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_faturas_metodospagamentos_idx` (`metodospagamentos_id` ASC),
  INDEX `fk_faturas_userprofiles_idx` (`userprofiles_id` ASC),
  CONSTRAINT `fk_faturas_metodospagamentos`
    FOREIGN KEY (`metodospagamentos_id`)
    REFERENCES `metodospagamentos` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_faturas_userprofiles`
    FOREIGN KEY (`userprofiles_id`)
    REFERENCES `userprofiles` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: linhasfaturas (TABELA POLIMÓRFICA)
-- Linhas de fatura - pode conter:
-- - Medicamentos vendidos em consulta
-- - Serviços faturados
-- - Relação com marcações
-- IMPORTANTE: vendidoemconsulta = 1 indica medicamento usado em consulta
-- =====================================================
CREATE TABLE `linhasfaturas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `total` FLOAT NOT NULL,
  `quantidade` INT NOT NULL DEFAULT 1,
  `vendidoemconsulta` TINYINT NOT NULL DEFAULT 0,
  `faturas_id` INT NOT NULL,
  `medicamentos_id` INT NULL DEFAULT NULL,
  `servicos_id` INT NULL DEFAULT NULL,
  `marcacoes_id` INT NULL DEFAULT NULL,
  `eliminado` TINYINT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  INDEX `fk_linhasfaturas_faturas_idx` (`faturas_id` ASC),
  INDEX `fk_linhasfaturas_medicamentos_idx` (`medicamentos_id` ASC),
  INDEX `fk_linhasfaturas_servicos_idx` (`servicos_id` ASC),
  INDEX `fk_linhasfaturas_marcacoes_idx` (`marcacoes_id` ASC),
  CONSTRAINT `fk_linhasfaturas_faturas`
    FOREIGN KEY (`faturas_id`)
    REFERENCES `faturas` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_linhasfaturas_medicamentos`
    FOREIGN KEY (`medicamentos_id`)
    REFERENCES `medicamentos` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_linhasfaturas_servicos`
    FOREIGN KEY (`servicos_id`)
    REFERENCES `servicos` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_linhasfaturas_marcacoes`
    FOREIGN KEY (`marcacoes_id`)
    REFERENCES `marcacoes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELA: lembretes
-- Lembretes/reminders dos utilizadores
-- =====================================================
CREATE TABLE `lembretes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  `userprofiles_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_lembretes_userprofiles_idx` (`userprofiles_id` ASC),
  CONSTRAINT `fk_lembretes_userprofiles`
    FOREIGN KEY (`userprofiles_id`)
    REFERENCES `userprofiles` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- TABELAS RBAC (Role-Based Access Control)
-- Sistema de controlo de acesso baseado em funções
-- =====================================================

-- TABELA: auth_rule
-- Regras de negócio para RBAC
CREATE TABLE `auth_rule` (
  `name` VARCHAR(64) NOT NULL,
  `data` BLOB NULL DEFAULT NULL,
  `created_at` INT NULL DEFAULT NULL,
  `updated_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- TABELA: auth_item
-- Permissões e roles do sistema
CREATE TABLE `auth_item` (
  `name` VARCHAR(64) NOT NULL,
  `type` SMALLINT NOT NULL,
  `description` TEXT NULL DEFAULT NULL,
  `rule_name` VARCHAR(64) NULL DEFAULT NULL,
  `data` BLOB NULL DEFAULT NULL,
  `created_at` INT NULL DEFAULT NULL,
  `updated_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`name`),
  INDEX `rule_name` (`rule_name` ASC),
  INDEX `idx-auth_item-type` (`type` ASC),
  CONSTRAINT `auth_item_ibfk_1`
    FOREIGN KEY (`rule_name`)
    REFERENCES `auth_rule` (`name`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- TABELA: auth_item_child
-- Hierarquia de permissões (permissões filhas)
CREATE TABLE `auth_item_child` (
  `parent` VARCHAR(64) NOT NULL,
  `child` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`parent`, `child`),
  INDEX `child` (`child` ASC),
  CONSTRAINT `auth_item_child_ibfk_1`
    FOREIGN KEY (`parent`)
    REFERENCES `auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2`
    FOREIGN KEY (`child`)
    REFERENCES `auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- TABELA: auth_assignment
-- Atribuição de roles aos utilizadores
CREATE TABLE `auth_assignment` (
  `item_name` VARCHAR(64) NOT NULL,
  `user_id` VARCHAR(64) NOT NULL,
  `created_at` INT NULL DEFAULT NULL,
  PRIMARY KEY (`item_name`, `user_id`),
  INDEX `idx-auth_assignment-user_id` (`user_id` ASC),
  CONSTRAINT `auth_assignment_ibfk_1`
    FOREIGN KEY (`item_name`)
    REFERENCES `auth_item` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- =====================================================
-- DADOS INICIAIS
-- =====================================================

-- Inserir método de pagamento padrão
INSERT INTO `metodospagamentos` (`nome`, `vigor`, `eliminado`) VALUES
('Dinheiro', 1, 0),
('Multibanco', 1, 0),
('MBWay', 1, 0),
('Cartão de Crédito', 1, 0);

-- Inserir categorias de medicamentos
INSERT INTO `categorias` (`nome`, `eliminado`) VALUES
('Antibióticos', 0),
('Anti-inflamatórios', 0),
('Antiparasitários', 0),
('Vacinas', 0),
('Suplementos', 0),
('Analgésicos', 0);

-- Inserir espécies comuns
INSERT INTO `especies` (`nome`, `eliminado`) VALUES
('Cão', 0),
('Gato', 0),
('Ave', 0),
('Coelho', 0),
('Roedor', 0);

-- Inserir raças comuns de cães (especie_id = 1)
INSERT INTO `racas` (`nome`, `especies_id`, `eliminado`) VALUES
('Labrador', 1, 0),
('Golden Retriever', 1, 0),
('Pastor Alemão', 1, 0),
('Bulldog', 1, 0),
('Beagle', 1, 0),
('Poodle', 1, 0),
('Yorkshire', 1, 0),
('Chihuahua', 1, 0),
('Sem Raça Definida', 1, 0);

-- Inserir raças comuns de gatos (especie_id = 2)
INSERT INTO `racas` (`nome`, `especies_id`, `eliminado`) VALUES
('Persa', 2, 0),
('Siamês', 2, 0),
('Maine Coon', 2, 0),
('British Shorthair', 2, 0),
('Comum Europeu', 2, 0),
('Sem Raça Definida', 2, 0);

-- Inserir serviços básicos
INSERT INTO `servicos` (`nome`, `valor`, `eliminado`) VALUES
('Consulta Geral', 35.00, 0),
('Vacinação', 25.00, 0),
('Desparasitação', 15.00, 0),
('Cirurgia Simples', 150.00, 0),
('Cirurgia Complexa', 350.00, 0),
('Análises Clínicas', 50.00, 0),
('Radiografia', 40.00, 0),
('Ecografia', 60.00, 0),
('Limpeza Dentária', 80.00, 0),
('Internamento (por dia)', 45.00, 0);

-- =====================================================
-- VIEWS E QUERIES ÚTEIS
-- =====================================================

-- Query para ver medicamentos utilizados numa marcação
-- SELECT
--     m.nome AS medicamento,
--     lf.quantidade,
--     m.preco AS preco_unitario,
--     lf.total AS total_linha
-- FROM linhasfaturas lf
-- INNER JOIN medicamentos m ON lf.medicamentos_id = m.id
-- WHERE lf.marcacoes_id = [ID_MARCACAO]
--   AND lf.vendidoemconsulta = 1
--   AND lf.eliminado = 0;

-- Query para ver stock atual de medicamentos
-- SELECT
--     m.id,
--     m.nome,
--     m.quantidade AS stock_disponivel,
--     m.preco,
--     c.nome AS categoria
-- FROM medicamentos m
-- INNER JOIN categorias c ON m.categorias_id = c.id
-- WHERE m.eliminado = 0
-- ORDER BY m.nome;

-- Query para ver fatura de uma marcação
-- SELECT
--     f.id AS fatura_id,
--     f.total AS total_fatura,
--     f.estado AS estado_fatura,
--     f.created_at,
--     mp.nome AS metodo_pagamento
-- FROM faturas f
-- INNER JOIN linhasfaturas lf ON f.id = lf.faturas_id
-- INNER JOIN metodospagamentos mp ON f.metodospagamentos_id = mp.id
-- WHERE lf.marcacoes_id = [ID_MARCACAO]
--   AND lf.vendidoemconsulta = 1
--   AND f.eliminado = 0
-- GROUP BY f.id;

-- =====================================================
-- FIM DO SCHEMA
-- =====================================================
```

---

## Sistema de Gestão de Medicamentos em Consultas

### Como Funciona

Quando um médico atribui medicamentos a uma marcação no estado "realizada":

1. **Verificação de Stock**: Sistema verifica se `medicamentos.quantidade >= quantidade_solicitada`

2. **Criação/Busca de Fatura**:

   - Procura fatura existente através de `linhasfaturas` com `marcacoes_id`
   - Se não existir, cria nova fatura automática

3. **Criação de Linha de Fatura**:

   ```sql
   INSERT INTO linhasfaturas (
     medicamentos_id,
     marcacoes_id,
     faturas_id,
     quantidade,
     total,
     vendidoemconsulta
   ) VALUES (
     [ID_MEDICAMENTO],
     [ID_MARCACAO],
     [ID_FATURA],
     [QUANTIDADE],
     quantidade * preco,
     1  -- FLAG: vendido em consulta
   );
   ```

4. **Decremento Automático de Stock**:

   ```sql
   UPDATE medicamentos
   SET quantidade = quantidade - [QUANTIDADE_USADA]
   WHERE id = [ID_MEDICAMENTO];
   ```

5. **Atualização do Total da Fatura**:
   ```sql
   UPDATE faturas
   SET total = (
     SELECT SUM(total)
     FROM linhasfaturas
     WHERE faturas_id = [ID_FATURA]
     AND eliminado = 0
   )
   WHERE id = [ID_FATURA];
   ```

### Remoção de Medicamentos

Quando um medicamento é removido:

1. **Restauro de Stock**:

   ```sql
   UPDATE medicamentos
   SET quantidade = quantidade + [QUANTIDADE_ANTERIOR]
   WHERE id = [ID_MEDICAMENTO];
   ```

2. **Eliminação da Linha**:
   ```sql
   DELETE FROM linhasfaturas
   WHERE id = [ID_LINHA]
   AND marcacoes_id = [ID_MARCACAO];
   ```

---

## Notas Importantes

### Flag `vendidoemconsulta`

- **1** = Medicamento vendido durante consulta (associado a marcação)
- **0** = Medicamento vendido avulso (venda direta, sem consulta)

### Soft Delete

Todas as tabelas usam `eliminado = 0` (ativo) ou `1` (eliminado) em vez de DELETE físico.

### Integridade Referencial

- ✅ Foreign keys com constraints
- ✅ Cascades configurados adequadamente
- ✅ RESTRICT onde apropriado para prevenir eliminação acidental

---

**Versão**: 1.0  
**Data**: Janeiro 2026  
**Última Atualização**: Sistema de gestão de medicamentos implementado
