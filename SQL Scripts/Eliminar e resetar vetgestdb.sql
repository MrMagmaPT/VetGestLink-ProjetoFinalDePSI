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
 USE `vetgestdb`;

 SET FOREIGN_KEY_CHECKS = 0;
 
 TRUNCATE TABLE `auth_assignment`;
 TRUNCATE TABLE `auth_item_child`;
 TRUNCATE TABLE `auth_item`;
 TRUNCATE TABLE `auth_rule`;

TRUNCATE TABLE `lembretes`;
ALTER TABLE `lembretes` AUTO_INCREMENT = 1;

TRUNCATE TABLE `linhasfaturas`;
ALTER TABLE `linhasfaturas` AUTO_INCREMENT = 1;

TRUNCATE TABLE `faturas`;
 ALTER TABLE `faturas` AUTO_INCREMENT = 1;
 
TRUNCATE TABLE `metodospagamentos`;
ALTER TABLE `metodospagamentos` AUTO_INCREMENT = 1;
 
TRUNCATE TABLE `medicamentos`;
ALTER TABLE `medicamentos` AUTO_INCREMENT = 1;

TRUNCATE TABLE `categorias`;
 ALTER TABLE `categorias` AUTO_INCREMENT = 1;

TRUNCATE TABLE `marcacoes`;
ALTER TABLE `marcacoes` AUTO_INCREMENT = 1;

TRUNCATE TABLE `servicos`;
ALTER TABLE `servicos` AUTO_INCREMENT = 1;

TRUNCATE TABLE `notas`;
ALTER TABLE `notas` AUTO_INCREMENT = 1;

TRUNCATE TABLE `animais`;
ALTER TABLE `animais` AUTO_INCREMENT = 1;

TRUNCATE TABLE `racas`;
ALTER TABLE `racas` AUTO_INCREMENT = 1;

TRUNCATE TABLE `especies`;
ALTER TABLE `especies` AUTO_INCREMENT = 1;

TRUNCATE TABLE `moradas`;
ALTER TABLE `moradas` AUTO_INCREMENT = 1;

TRUNCATE TABLE `userprofiles`;
ALTER TABLE `userprofiles` AUTO_INCREMENT = 1;
 
TRUNCATE TABLE `user`;
ALTER TABLE `user` AUTO_INCREMENT = 1;

TRUNCATE TABLE `migration`;

SET FOREIGN_KEY_CHECKS = 1;