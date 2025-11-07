-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema vetgestdb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema vetgestdb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `vetgestdb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `vetgestdb` ;

-- -----------------------------------------------------
-- Table `vetgestdb`.`especies`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`especies` (
`id` INT NOT NULL AUTO_INCREMENT,
`nome` VARCHAR(45) NOT NULL,
`eliminado` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE INDEX `idespecies_UNIQUE` (`id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`racas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`racas` (
`id` INT NOT NULL AUTO_INCREMENT,
`nome` VARCHAR(45) NOT NULL,
`especies_id` INT NOT NULL,
`eliminado` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE INDEX `idracas_UNIQUE` (`id` ASC),
INDEX `fk_racas_especies_idx` (`especies_id` ASC),
CONSTRAINT `fk_racas_especies`
FOREIGN KEY (`especies_id`)
REFERENCES `vetgestdb`.`especies` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`user` (
`id` INT NOT NULL AUTO_INCREMENT,
`username` VARCHAR(255) CHARACTER SET 'utf8mb3' NOT NULL,
`auth_key` VARCHAR(32) CHARACTER SET 'utf8mb3' NOT NULL,
`password_hash` VARCHAR(255) CHARACTER SET 'utf8mb3' NOT NULL,
`password_reset_token` VARCHAR(255) CHARACTER SET 'utf8mb3' NULL DEFAULT NULL,
`email` VARCHAR(255) CHARACTER SET 'utf8mb3' NOT NULL,
`status` SMALLINT NOT NULL DEFAULT '10',
`created_at` INT NOT NULL,
`updated_at` INT NOT NULL,
`verification_token` VARCHAR(255) CHARACTER SET 'utf8mb3' NULL DEFAULT NULL,
PRIMARY KEY (`id`),
UNIQUE INDEX `username` (`username` ASC),
UNIQUE INDEX `email` (`email` ASC),
UNIQUE INDEX `password_reset_token` (`password_reset_token` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `vetgestdb`.`userprofiles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`userprofiles` (
`id` INT NOT NULL AUTO_INCREMENT,
`nomecompleto` VARCHAR(45) NOT NULL,
`nif` VARCHAR(9) NOT NULL,
`telemovel` VARCHAR(9) NOT NULL,
`dtanascimento` DATE NOT NULL,
`eliminado` TINYINT NOT NULL DEFAULT '0',
`user_id` INT NOT NULL,
PRIMARY KEY (`id`),
UNIQUE INDEX `iduserprofiles_UNIQUE` (`id` ASC),
INDEX `fk_userprofiles_user1_idx` (`user_id` ASC),
UNIQUE INDEX `nif_UNIQUE` (`nif` ASC),
CONSTRAINT `fk_userprofiles_user1`
FOREIGN KEY (`user_id`)
REFERENCES `vetgestdb`.`user` (`id`)
ON DELETE NO ACTION
ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`animais`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`animais` (
`id` INT NOT NULL AUTO_INCREMENT,
`nome` VARCHAR(45) NOT NULL,
`dtanascimento` DATE NOT NULL,
`peso` FLOAT NOT NULL,
`microship` TINYINT NOT NULL,
`sexo` ENUM('M', 'F') NOT NULL,
`especies_id` INT NOT NULL,
`userprofiles_id` INT NOT NULL,
`racas_id` INT NULL DEFAULT NULL,
`eliminado` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE INDEX `idanimais_UNIQUE` (`id` ASC),
INDEX `fk_animais_especies_idx` (`especies_id` ASC),
INDEX `fk_animais_userprofiles_idx` (`userprofiles_id` ASC),
INDEX `fk_animais_racas1_idx` (`racas_id` ASC),
CONSTRAINT `fk_animais_especies`
FOREIGN KEY (`especies_id`)
REFERENCES `vetgestdb`.`especies` (`id`),
CONSTRAINT `fk_animais_racas1`
FOREIGN KEY (`racas_id`)
REFERENCES `vetgestdb`.`racas` (`id`),
CONSTRAINT `fk_animais_userprofiles`
FOREIGN KEY (`userprofiles_id`)
REFERENCES `vetgestdb`.`userprofiles` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`auth_rule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`auth_rule` (
`name` VARCHAR(64) CHARACTER SET 'utf8mb3' NOT NULL,
`data` BLOB NULL DEFAULT NULL,
`created_at` INT NULL DEFAULT NULL,
`updated_at` INT NULL DEFAULT NULL,
PRIMARY KEY (`name`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `vetgestdb`.`auth_item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`auth_item` (
`name` VARCHAR(64) CHARACTER SET 'utf8mb3' NOT NULL,
`type` SMALLINT NOT NULL,
`description` TEXT CHARACTER SET 'utf8mb3' NULL DEFAULT NULL,
`rule_name` VARCHAR(64) CHARACTER SET 'utf8mb3' NULL DEFAULT NULL,
`data` BLOB NULL DEFAULT NULL,
`created_at` INT NULL DEFAULT NULL,
`updated_at` INT NULL DEFAULT NULL,
PRIMARY KEY (`name`),
INDEX `rule_name` (`rule_name` ASC),
INDEX `idx-auth_item-type` (`type` ASC),
CONSTRAINT `auth_item_ibfk_1`
FOREIGN KEY (`rule_name`)
REFERENCES `vetgestdb`.`auth_rule` (`name`)
ON DELETE SET NULL
ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `vetgestdb`.`auth_assignment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`auth_assignment` (
`item_name` VARCHAR(64) CHARACTER SET 'utf8mb3' NOT NULL,
`user_id` VARCHAR(64) CHARACTER SET 'utf8mb3' NOT NULL,
`created_at` INT NULL DEFAULT NULL,
PRIMARY KEY (`item_name`, `user_id`),
INDEX `idx-auth_assignment-user_id` (`user_id` ASC),
CONSTRAINT `auth_assignment_ibfk_1`
FOREIGN KEY (`item_name`)
REFERENCES `vetgestdb`.`auth_item` (`name`)
ON DELETE CASCADE
ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `vetgestdb`.`auth_item_child`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`auth_item_child` (
`parent` VARCHAR(64) CHARACTER SET 'utf8mb3' NOT NULL,
`child` VARCHAR(64) CHARACTER SET 'utf8mb3' NOT NULL,
PRIMARY KEY (`parent`, `child`),
INDEX `child` (`child` ASC),
CONSTRAINT `auth_item_child_ibfk_1`
FOREIGN KEY (`parent`)
REFERENCES `vetgestdb`.`auth_item` (`name`)
ON DELETE CASCADE
ON UPDATE CASCADE,
CONSTRAINT `auth_item_child_ibfk_2`
FOREIGN KEY (`child`)
REFERENCES `vetgestdb`.`auth_item` (`name`)
ON DELETE CASCADE
ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `vetgestdb`.`categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`categorias` (
`id` INT NOT NULL AUTO_INCREMENT,
`nome` VARCHAR(100) NOT NULL,
`eliminado` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`metodospagamentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`metodospagamentos` (
`id` INT NOT NULL AUTO_INCREMENT,
`nome` VARCHAR(45) NOT NULL,
`vigor` TINYINT NOT NULL,
`eliminado` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE INDEX `idmetodos_pagamentos_UNIQUE` (`id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`faturas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`faturas` (
`id` INT NOT NULL AUTO_INCREMENT,
`total` FLOAT NOT NULL,
`estado` TINYINT NOT NULL,
`created_at` DATETIME NOT NULL,
`metodospagamentos_id` INT NOT NULL,
`userprofiles_id` INT NOT NULL,
`eliminado` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE INDEX `idfaturas_UNIQUE` (`id` ASC),
INDEX `fk_faturas_metodospagamentos_idx` (`metodospagamentos_id` ASC),
INDEX `fk_faturas_userprofiles_idx` (`userprofiles_id` ASC),
CONSTRAINT `fk_faturas_metodospagamentos`
FOREIGN KEY (`metodospagamentos_id`)
REFERENCES `vetgestdb`.`metodospagamentos` (`id`),
CONSTRAINT `fk_faturas_userprofiles`
FOREIGN KEY (`userprofiles_id`)
REFERENCES `vetgestdb`.`userprofiles` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`marcacoes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`marcacoes` (
`id` INT NOT NULL AUTO_INCREMENT,
`data` DATE NOT NULL,
`horainicio` TIME NOT NULL,
`horafim` TIME NOT NULL,
`diagnostico` VARCHAR(500) NULL DEFAULT NULL,
`preco` FLOAT NOT NULL,
`estado` ENUM('pendente', 'cancelada', 'realizada') NOT NULL,
`tipo` ENUM('consulta', 'cirurgia', 'operacao') NOT NULL,
`created_at` DATETIME NOT NULL,
`updated_at` DATETIME NOT NULL,
`animais_id` INT NOT NULL,
`userprofiles_id` INT NOT NULL,
`eliminado` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE INDEX `idagenda_UNIQUE` (`id` ASC),
INDEX `fk_agendas_animais1_idx` (`animais_id` ASC),
INDEX `fk_agendas_userprofiles1_idx` (`userprofiles_id` ASC),
CONSTRAINT `fk_agendas_animais`
FOREIGN KEY (`animais_id`)
REFERENCES `vetgestdb`.`animais` (`id`),
CONSTRAINT `fk_agendas_userprofiles`
FOREIGN KEY (`userprofiles_id`)
REFERENCES `vetgestdb`.`userprofiles` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`medicamentos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`medicamentos` (
`id` INT NOT NULL AUTO_INCREMENT,
`nome` VARCHAR(45) NOT NULL,
`descricao` VARCHAR(250) NOT NULL,
`preco` FLOAT NOT NULL,
`quantidade` INT NOT NULL,
`categorias_id` INT NOT NULL,
`eliminado` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE INDEX `idmedicamentos_UNIQUE` (`id` ASC),
INDEX `fk_medicamentos_categorias_idx` (`categorias_id` ASC),
CONSTRAINT `fk_medicamentos_categorias`
FOREIGN KEY (`categorias_id`)
REFERENCES `vetgestdb`.`categorias` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`linhasfaturas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`linhasfaturas` (
`id` INT NOT NULL AUTO_INCREMENT,
`total` FLOAT NOT NULL,
`quantidade` INT NOT NULL DEFAULT '1',
`vendidoemconsulta` TINYINT NOT NULL DEFAULT '0',
`faturas_id` INT NOT NULL,
`medicamentos_id` INT NULL DEFAULT NULL,
`marcacoes_id` INT NULL DEFAULT NULL,
`eliminado` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE INDEX `idlinhasfaturas_UNIQUE` (`id` ASC),
INDEX `fk_linhasfaturas_faturas_idx` (`faturas_id` ASC),
INDEX `fk_linhasfaturas_medicamentos_idx` (`medicamentos_id` ASC),
INDEX `fk_linhasfaturas_marcacoes1_idx` (`marcacoes_id` ASC),
CONSTRAINT `fk_linhasfaturas_faturas`
FOREIGN KEY (`faturas_id`)
REFERENCES `vetgestdb`.`faturas` (`id`),
CONSTRAINT `fk_linhasfaturas_marcacoes1`
FOREIGN KEY (`marcacoes_id`)
REFERENCES `vetgestdb`.`marcacoes` (`id`),
CONSTRAINT `fk_linhasfaturas_medicamentos`
FOREIGN KEY (`medicamentos_id`)
REFERENCES `vetgestdb`.`medicamentos` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`migration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`migration` (
`version` VARCHAR(180) NOT NULL,
`apply_time` INT NULL DEFAULT NULL,
PRIMARY KEY (`version`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`moradas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`moradas` (
`id` INT NOT NULL AUTO_INCREMENT,
`rua` VARCHAR(45) NOT NULL,
`nporta` VARCHAR(45) NOT NULL,
`andar` VARCHAR(45) NULL DEFAULT NULL,
`cdpostal` VARCHAR(45) NOT NULL,
`localidade` VARCHAR(45) NOT NULL,
`cidade` VARCHAR(45) NOT NULL,
`cxpostal` VARCHAR(45) NULL DEFAULT NULL,
`principal` TINYINT NOT NULL,
`userprofiles_id` INT NOT NULL,
`eliminado` TINYINT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`),
UNIQUE INDEX `id_UNIQUE` (`id` ASC),
INDEX `fk_moradas_userprofiles_idx` (`userprofiles_id` ASC),
CONSTRAINT `fk_moradas_userprofiles`
FOREIGN KEY (`userprofiles_id`)
REFERENCES `vetgestdb`.`userprofiles` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb3;


-- -----------------------------------------------------
-- Table `vetgestdb`.`notas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `vetgestdb`.`notas` (
`id` INT NOT NULL AUTO_INCREMENT,
`nota` VARCHAR(500) NOT NULL,
`created_at` DATETIME NOT NULL,
`updated_at` DATETIME NOT NULL,
`userprofiles_id` INT NOT NULL,
`animais_id` INT NOT NULL,
PRIMARY KEY (`id`),
INDEX `fk_notas_userprofiles1_idx` (`userprofiles_id` ASC),
INDEX `fk_notas_animais1_idx` (`animais_id` ASC),
CONSTRAINT `fk_notas_userprofiles1`
FOREIGN KEY (`userprofiles_id`)
REFERENCES `vetgestdb`.`userprofiles` (`id`)
ON DELETE NO ACTION
ON UPDATE NO ACTION,
CONSTRAINT `fk_notas_animais1`
FOREIGN KEY (`animais_id`)
REFERENCES `vetgestdb`.`animais` (`id`)
ON DELETE NO ACTION
ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
