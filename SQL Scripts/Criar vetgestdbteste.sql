-- =====================================================
-- CRIAR BASE DE DADOS DE TESTE
-- =====================================================
CREATE DATABASE IF NOT EXISTS `vetgestdbteste`
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE `vetgestdbteste`;

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
  `metodospagamentos_id` INT NULL,
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