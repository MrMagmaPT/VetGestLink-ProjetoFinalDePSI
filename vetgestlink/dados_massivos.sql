-- =====================================================
-- SCRIPT DE DADOS MASSIVOS - VETGESTLINK
-- =====================================================
-- Este script insere:
-- - 1 Admin
-- - 2 Recepcionistas
-- - 5 Veterinários
-- - 10 Clientes
-- - 10 Animais (1 por cliente)
-- - 10 Marcações (1 por cliente)
-- - 10 Faturas (1 por cliente)
-- - 20 Linhas de Fatura (2 por fatura)
-- - Medicamentos e dados base
-- =====================================================

USE `vetgestdb`;

-- =====================================================
-- SENHAS DOS USUÁRIOS
-- =====================================================
-- Admin: admin123
-- Veterinário: vet123
-- Recepcionista: recep123
-- Cliente: cliente123
-- =====================================================

-- =====================================================
-- DADOS BASE
-- =====================================================

-- Métodos de Pagamento
INSERT INTO `metodospagamentos` (`nome`, `vigor`, `eliminado`) VALUES
('Dinheiro', 1, 0),
('Multibanco', 1, 0),
('MBWay', 1, 0),
('Cartão de Crédito', 1, 0);

-- Categorias de Medicamentos
INSERT INTO `categorias` (`nome`, `eliminado`) VALUES
('Antibióticos', 0),
('Anti-inflamatórios', 0),
('Antiparasitários', 0),
('Vacinas', 0),
('Suplementos', 0),
('Analgésicos', 0);

-- Espécies
INSERT INTO `especies` (`nome`, `eliminado`) VALUES
('Cão', 0),
('Gato', 0),
('Ave', 0),
('Coelho', 0),
('Roedor', 0);

-- Raças de Cães (especie_id = 1)
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

-- Raças de Gatos (especie_id = 2)
INSERT INTO `racas` (`nome`, `especies_id`, `eliminado`) VALUES
('Persa', 2, 0),
('Siamês', 2, 0),
('Maine Coon', 2, 0),
('British Shorthair', 2, 0),
('Comum Europeu', 2, 0),
('Sem Raça Definida', 2, 0);

-- Serviços
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

-- Medicamentos
INSERT INTO `medicamentos` (`nome`, `descricao`, `preco`, `quantidade`, `categorias_id`, `eliminado`) VALUES
('Amoxicilina 500mg', 'Antibiótico de largo espectro', 12.50, 100, 1, 0),
('Carprofeno 75mg', 'Anti-inflamatório não esteroide', 18.75, 80, 2, 0),
('Ivermectina 1%', 'Antiparasitário interno e externo', 22.00, 60, 3, 0),
('Vacina Polivalente', 'Proteção contra múltiplas doenças', 35.00, 50, 4, 0),
('Ômega-3 Cápsulas', 'Suplemento para saúde articular', 15.50, 120, 5, 0),
('Tramadol 50mg', 'Analgésico opioide', 28.00, 40, 6, 0),
('Cefalexina 250mg', 'Antibiótico cefalosporina', 14.00, 70, 1, 0),
('Meloxicam 1.5mg', 'Anti-inflamatório', 16.50, 90, 2, 0);

-- =====================================================
-- USUÁRIOS E PERFIS
-- =====================================================

-- 1 Admin
INSERT INTO `user` (`username`, `email`, `password_hash`, `auth_key`, `status`, `created_at`, `updated_at`) VALUES
('admin', 'admin@vetgestlink.pt', '$2y$13$/lPqz2JI6guC/7ToAI7UjeBSj2xfMBwiO9YN23N3TcIDiDitJKKbK', 'K8Hm3vTpR9sXwQ5nL2cV7jF4bN6gY0zA', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO `userprofiles` (`user_id`, `nomecompleto`, `dtanascimento`, `nif`, `telemovel`, `created_at`, `updated_at`, `eliminado`) VALUES
(1, 'Administrador Sistema', '1980-01-01', '123456789', '911111111', NOW(), NOW(), 0);

INSERT INTO `moradas` (`userprofiles_id`, `rua`, `nporta`, `cdpostal`, `localidade`, `cidade`, `principal`, `created_at`, `updated_at`) VALUES
(1, 'Rua Principal', '1', '1000-001', 'Centro', 'Lisboa', 1, NOW(), NOW());

-- 2 Recepcionistas
INSERT INTO `user` (`username`, `email`, `password_hash`, `auth_key`, `status`, `created_at`, `updated_at`) VALUES
('recep1', 'recep1@vetgestlink.pt', '$2y$13$i5jXCSQl8f31Ae496q0cz.jlpP3bF5ew/wZlj1z52JgkyWt3fMdpS', 'P4mR8tL3hW6vK2nF9sX5cQ7jY0bN1gZ', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('recep2', 'recep2@vetgestlink.pt', '$2y$13$i5jXCSQl8f31Ae496q0cz.jlpP3bF5ew/wZlj1z52JgkyWt3fMdpS', 'T9sX5cQ7jY0bN1gZ4mR8tL3hW6vK2nF', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO `userprofiles` (`user_id`, `nomecompleto`, `dtanascimento`, `nif`, `telemovel`, `created_at`, `updated_at`, `eliminado`) VALUES
(2, 'Maria Recepcionista', '1992-03-15', '234567890', '922222222', NOW(), NOW(), 0),
(3, 'João Recepcionista', '1988-07-22', '145678901', '933333333', NOW(), NOW(), 0);

INSERT INTO `moradas` (`userprofiles_id`, `rua`, `nporta`, `cdpostal`, `localidade`, `cidade`, `principal`, `created_at`, `updated_at`) VALUES
(2, 'Av. República', '15', '1050-100', 'Saldanha', 'Lisboa', 1, NOW(), NOW()),
(3, 'Rua dos Anjos', '23', '1150-050', 'Anjos', 'Lisboa', 1, NOW(), NOW());

-- 5 Veterinários
INSERT INTO `user` (`username`, `email`, `password_hash`, `auth_key`, `status`, `created_at`, `updated_at`) VALUES
('vet1', 'vet1@vetgestlink.pt', '$2y$13$0kwz6EJdzCrGd.XSQREBKeGkz90uAVmfoHjpDNJXjl21OHhlC4mPG', 'W6vK2nF9sX5cQ7jY0bN1gZ4mR8tL3hP', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('vet2', 'vet2@vetgestlink.pt', '$2y$13$0kwz6EJdzCrGd.XSQREBKeGkz90uAVmfoHjpDNJXjl21OHhlC4mPG', 'X5cQ7jY0bN1gZ4mR8tL3hW6vK2nF9sP', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('vet3', 'vet3@vetgestlink.pt', '$2y$13$0kwz6EJdzCrGd.XSQREBKeGkz90uAVmfoHjpDNJXjl21OHhlC4mPG', 'Y0bN1gZ4mR8tL3hW6vK2nF9sX5cQ7jP', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('vet4', 'vet4@vetgestlink.pt', '$2y$13$0kwz6EJdzCrGd.XSQREBKeGkz90uAVmfoHjpDNJXjl21OHhlC4mPG', 'Z4mR8tL3hW6vK2nF9sX5cQ7jY0bN1gP', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('vet5', 'vet5@vetgestlink.pt', '$2y$13$0kwz6EJdzCrGd.XSQREBKeGkz90uAVmfoHjpDNJXjl21OHhlC4mPG', 'R8tL3hW6vK2nF9sX5cQ7jY0bN1gZ4mP', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO `userprofiles` (`user_id`, `nomecompleto`, `dtanascimento`, `nif`, `telemovel`, `created_at`, `updated_at`, `eliminado`) VALUES
(4, 'Dr. Carlos Veterinário', '1985-05-10', '256789012', '944444444', NOW(), NOW(), 0),
(5, 'Dra. Ana Veterinária', '1990-08-20', '167890123', '955555555', NOW(), NOW(), 0),
(6, 'Dr. Pedro Veterinário', '1987-12-05', '278901234', '966666666', NOW(), NOW(), 0),
(7, 'Dra. Sofia Veterinária', '1993-02-18', '189012345', '977777777', NOW(), NOW(), 0),
(8, 'Dr. Miguel Veterinário', '1989-11-30', '290123456', '988888888', NOW(), NOW(), 0);

INSERT INTO `moradas` (`userprofiles_id`, `rua`, `nporta`, `cdpostal`, `localidade`, `cidade`, `principal`, `created_at`, `updated_at`) VALUES
(4, 'Rua Veterinária', '10', '1200-100', 'Campo de Ourique', 'Lisboa', 1, NOW(), NOW()),
(5, 'Av. Liberdade', '200', '1250-150', 'Avenidas Novas', 'Lisboa', 1, NOW(), NOW()),
(6, 'Rua das Flores', '45', '1300-200', 'Belém', 'Lisboa', 1, NOW(), NOW()),
(7, 'Praça Central', '8', '1350-250', 'Estrela', 'Lisboa', 1, NOW(), NOW()),
(8, 'Rua dos Médicos', '33', '1400-300', 'Benfica', 'Lisboa', 1, NOW(), NOW());

-- 10 Clientes
INSERT INTO `user` (`username`, `email`, `password_hash`, `auth_key`, `status`, `created_at`, `updated_at`) VALUES
('cliente1', 'cliente1@email.pt', '$2y$13$5tf.6VjngjXbZDdO/1Xqgufah6Ux8HIFxiR2R1MvegOoQDalVfyZm', 'L3hW6vK2nF9sX5cQ7jY0bN1gZ4mR8tP', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('cliente2', 'cliente2@email.pt', '$2y$13$5tf.6VjngjXbZDdO/1Xqgufah6Ux8HIFxiR2R1MvegOoQDalVfyZm', 'M4gL2fD9wXyZe3vC6nR1tP8sQ0xB5cH', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('cliente3', 'cliente3@email.pt', '$2y$13$5tf.6VjngjXbZDdO/1Xqgufah6Ux8HIFxiR2R1MvegOoQDalVfyZm', 'N3gM1fC8wVxYe2vB5nQ0tO7rP9xA4bG', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('cliente4', 'cliente4@email.pt', '$2y$13$5tf.6VjngjXbZDdO/1Xqgufah6Ux8HIFxiR2R1MvegOoQDalVfyZm', 'Q0tO7rP9xA4bG6mI3vJ5oK1pNaL4hB', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('cliente5', 'cliente5@email.pt', '$2y$13$5tf.6VjngjXbZDdO/1Xqgufah6Ux8HIFxiR2R1MvegOoQDalVfyZm', 'R1tP8sQ0xB5cH7mJ4vK6oL2pQaNp5kC', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('cliente6', 'cliente6@email.pt', '$2y$13$5tf.6VjngjXbZDdO/1Xqgufah6Ux8HIFxiR2R1MvegOoQDalVfyZm', 'S2uQ9tP0yX6aZ8bC5dE3fG1hJ4kL7mD', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('cliente7', 'cliente7@email.pt', '$2y$13$5tf.6VjngjXbZDdO/1Xqgufah6Ux8HIFxiR2R1MvegOoQDalVfyZm', 'T3vR0wS7xZ9aC2bD5eF8gH1iJ4kL6mE', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('cliente8', 'cliente8@email.pt', '$2y$13$5tf.6VjngjXbZDdO/1Xqgufah6Ux8HIFxiR2R1MvegOoQDalVfyZm', 'U4wS1xT8yZ0aB3cD6eF9gH2iJ5kL7mF', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('cliente9', 'cliente9@email.pt', '$2y$13$5tf.6VjngjXbZDdO/1Xqgufah6Ux8HIFxiR2R1MvegOoQDalVfyZm', 'V5xT2yU9zZ1aB4cD7eF0gH3iJ6kL8mG', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('cliente10', 'cliente10@email.pt', '$2y$13$5tf.6VjngjXbZDdO/1Xqgufah6Ux8HIFxiR2R1MvegOoQDalVfyZm', 'W6yU3zV0aZ2bB5cD8eF1gH4iJ7kL9mH', 10, UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO `userprofiles` (`user_id`, `nomecompleto`, `dtanascimento`, `nif`, `telemovel`, `created_at`, `updated_at`, `eliminado`) VALUES
(9, 'António Silva', '1975-04-12', '201234567', '912345678', NOW(), NOW(), 0),
(10, 'Beatriz Costa', '1982-09-25', '112345678', '923456789', NOW(), NOW(), 0),
(11, 'Carlos Pereira', '1978-06-18', '223456789', '934567890', NOW(), NOW(), 0),
(12, 'Diana Santos', '1995-11-03', '134567890', '945678901', NOW(), NOW(), 0),
(13, 'Eduardo Oliveira', '1988-02-14', '245678901', '956789012', NOW(), NOW(), 0),
(14, 'Francisca Martins', '1992-07-29', '156789012', '967890123', NOW(), NOW(), 0),
(15, 'Gabriel Rodrigues', '1980-12-08', '267890123', '978901234', NOW(), NOW(), 0),
(16, 'Helena Fernandes', '1986-05-21', '178901234', '989012345', NOW(), NOW(), 0),
(17, 'Igor Almeida', '1991-10-16', '289012345', '990123456', NOW(), NOW(), 0),
(18, 'Joana Sousa', '1984-03-27', '190123456', '901234567', NOW(), NOW(), 0);

INSERT INTO `moradas` (`userprofiles_id`, `rua`, `nporta`, `cdpostal`, `localidade`, `cidade`, `principal`, `created_at`, `updated_at`) VALUES
(9, 'Rua das Acácias', '12', '2700-100', 'Centro', 'Amadora', 1, NOW(), NOW()),
(10, 'Av. Brasil', '45', '2750-200', 'Damaia', 'Amadora', 1, NOW(), NOW()),
(11, 'Rua do Sol', '78', '2800-300', 'Pragal', 'Almada', 1, NOW(), NOW()),
(12, 'Praça da Alegria', '3', '2850-400', 'Laranjeiro', 'Almada', 1, NOW(), NOW()),
(13, 'Rua Verde', '22', '2900-500', 'Centro', 'Setúbal', 1, NOW(), NOW()),
(14, 'Av. 5 de Outubro', '67', '2950-600', 'Quinta do Conde', 'Setúbal', 1, NOW(), NOW()),
(15, 'Rua Azul', '90', '3000-700', 'Baixa', 'Coimbra', 1, NOW(), NOW()),
(16, 'Largo Central', '15', '3050-800', 'Celas', 'Coimbra', 1, NOW(), NOW()),
(17, 'Rua Nova', '44', '4000-900', 'Baixa', 'Porto', 1, NOW(), NOW()),
(18, 'Av. dos Aliados', '88', '4050-100', 'Centro', 'Porto', 1, NOW(), NOW());

-- =====================================================
-- ATRIBUIÇÃO DE ROLES (auth_assignment)
-- =====================================================

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
('admin', 1, UNIX_TIMESTAMP()),
('rececionista', 2, UNIX_TIMESTAMP()),
('rececionista', 3, UNIX_TIMESTAMP()),
('veterinario', 4, UNIX_TIMESTAMP()),
('veterinario', 5, UNIX_TIMESTAMP()),
('veterinario', 6, UNIX_TIMESTAMP()),
('veterinario', 7, UNIX_TIMESTAMP()),
('veterinario', 8, UNIX_TIMESTAMP()),
('cliente', 9, UNIX_TIMESTAMP()),
('cliente', 10, UNIX_TIMESTAMP()),
('cliente', 11, UNIX_TIMESTAMP()),
('cliente', 12, UNIX_TIMESTAMP()),
('cliente', 13, UNIX_TIMESTAMP()),
('cliente', 14, UNIX_TIMESTAMP()),
('cliente', 15, UNIX_TIMESTAMP()),
('cliente', 16, UNIX_TIMESTAMP()),
('cliente', 17, UNIX_TIMESTAMP()),
('cliente', 18, UNIX_TIMESTAMP());

-- =====================================================
-- ANIMAIS (1 por cliente)
-- =====================================================

INSERT INTO `animais` (`nome`, `peso`, `dtanascimento`, `microship`, `especies_id`, `userprofiles_id`, `racas_id`, `created_at`, `updated_at`, `eliminado`) VALUES
('Rex', 25.5, '2020-03-15', 1, 1, 9, 1, NOW(), NOW(), 0),
('Mimi', 4.2, '2019-07-22', 1, 2, 10, 10, NOW(), NOW(), 0),
('Thor', 30.0, '2018-11-10', 1, 1, 11, 3, NOW(), NOW(), 0),
('Luna', 6.8, '2021-05-18', 1, 1, 12, 5, NOW(), NOW(), 0),
('Bobby', 12.5, '2020-09-25', 1, 1, 13, 2, NOW(), NOW(), 0),
('Nina', 3.5, '2022-01-30', 1, 2, 14, 11, NOW(), NOW(), 0),
('Max', 28.0, '2019-04-12', 1, 1, 15, 6, NOW(), NOW(), 0),
('Bella', 5.2, '2021-08-08', 1, 2, 16, 12, NOW(), NOW(), 0),
('Rocky', 35.0, '2018-06-20', 1, 1, 17, 3, NOW(), NOW(), 0),
('Laika', 8.5, '2020-12-15', 1, 1, 18, 7, NOW(), NOW(), 0);

-- =====================================================
-- MARCAÇÕES (1 por cliente) - Diferentes datas
-- =====================================================

INSERT INTO `marcacoes` (`data`, `horainicio`, `horafim`, `diagnostico`, `estado`, `created_at`, `updated_at`, `servicos_id`, `animais_id`, `userprofiles_id`, `eliminado`) VALUES
('2026-01-05', '10:00', '10:30', 'Consulta de rotina realizada', 'realizada', '2026-01-05 10:30:00', '2026-01-05 10:30:00', 1, 1, 4, 0),
('2026-01-05', '11:00', '11:30', 'Vacinação anual aplicada', 'realizada', '2026-01-05 11:30:00', '2026-01-05 11:30:00', 2, 2, 5, 0),
('2026-01-06', '14:00', '14:45', 'Desparasitação completa', 'realizada', '2026-01-06 14:45:00', '2026-01-06 14:45:00', 3, 3, 6, 0),
('2026-01-07', '09:00', '09:30', 'Check-up geral sem alterações', 'realizada', '2026-01-07 09:30:00', '2026-01-07 09:30:00', 1, 4, 4, 0),
('2026-01-07', '15:00', '15:30', 'Consulta preventiva', 'realizada', '2026-01-07 15:30:00', '2026-01-07 15:30:00', 1, 5, 7, 0),
('2026-01-08', '10:30', '11:00', 'Vacinação polivalente', 'realizada', '2026-01-08 11:00:00', '2026-01-08 11:00:00', 2, 6, 8, 0),
('2026-01-08', '16:00', '16:45', 'Análises clínicas realizadas', 'realizada', '2026-01-08 16:45:00', '2026-01-08 16:45:00', 6, 7, 5, 0),
('2026-01-09', '11:00', '11:30', 'Consulta preventiva sem anomalias', 'realizada', '2026-01-09 11:30:00', '2026-01-09 11:30:00', 1, 8, 6, 0),
('2026-01-09', '14:30', '15:00', 'Check-up completo', 'realizada', '2026-01-09 15:00:00', '2026-01-09 15:00:00', 1, 9, 4, 0),
('2026-01-10', '09:30', '10:00', 'Vacinação aplicada com sucesso', 'realizada', '2026-01-10 10:00:00', '2026-01-10 10:00:00', 2, 10, 7, 0);

-- =====================================================
-- FATURAS (1 por cliente)
-- =====================================================

INSERT INTO `faturas` (`total`, `estado`, `created_at`, `userprofiles_id`, `metodospagamentos_id`, `eliminado`) VALUES
(70.00, 1, '2026-01-05 10:30:00', 9, 1, 0),
(53.75, 1, '2026-01-05 11:30:00', 10, 2, 0),
(45.50, 0, '2026-01-06 14:45:00', 11, 3, 0),
(62.50, 1, '2026-01-07 09:30:00', 12, 1, 0),
(70.00, 0, '2026-01-07 15:30:00', 13, 2, 0),
(43.75, 1, '2026-01-08 11:00:00', 14, 4, 0),
(100.00, 0, '2026-01-08 16:45:00', 15, 1, 0),
(51.50, 1, '2026-01-09 11:30:00', 16, 3, 0),
(63.00, 0, '2026-01-09 15:00:00', 17, 2, 0),
(60.00, 1, '2026-01-10 10:00:00', 18, 1, 0);

-- =====================================================
-- LINHAS DE FATURA (2 por fatura)
-- =====================================================

-- Fatura 1 (cliente 1)
INSERT INTO `linhasfaturas` (`quantidade`, `total`, `vendidoemconsulta`, `faturas_id`, `marcacoes_id`, `medicamentos_id`, `eliminado`) VALUES
(1, 35.00, 1, 1, 1, NULL, 0),
(2, 35.00, 1, 1, NULL, 1, 0);

-- Fatura 2 (cliente 2)
INSERT INTO `linhasfaturas` (`quantidade`, `total`, `vendidoemconsulta`, `faturas_id`, `marcacoes_id`, `medicamentos_id`, `eliminado`) VALUES
(1, 25.00, 1, 2, 2, NULL, 0),
(1, 28.75, 1, 2, NULL, 2, 0);

-- Fatura 3 (cliente 3)
INSERT INTO `linhasfaturas` (`quantidade`, `total`, `vendidoemconsulta`, `faturas_id`, `marcacoes_id`, `medicamentos_id`, `eliminado`) VALUES
(1, 15.00, 1, 3, 3, NULL, 0),
(1, 30.50, 1, 3, NULL, 3, 0);

-- Fatura 4 (cliente 4)
INSERT INTO `linhasfaturas` (`quantidade`, `total`, `vendidoemconsulta`, `faturas_id`, `marcacoes_id`, `medicamentos_id`, `eliminado`) VALUES
(1, 35.00, 1, 4, 4, NULL, 0),
(1, 27.50, 1, 4, NULL, 4, 0);

-- Fatura 5 (cliente 5)
INSERT INTO `linhasfaturas` (`quantidade`, `total`, `vendidoemconsulta`, `faturas_id`, `marcacoes_id`, `medicamentos_id`, `eliminado`) VALUES
(1, 35.00, 1, 5, 5, NULL, 0),
(2, 35.00, 1, 5, NULL, 5, 0);

-- Fatura 6 (cliente 6)
INSERT INTO `linhasfaturas` (`quantidade`, `total`, `vendidoemconsulta`, `faturas_id`, `marcacoes_id`, `medicamentos_id`, `eliminado`) VALUES
(1, 25.00, 1, 6, 6, NULL, 0),
(1, 18.75, 1, 6, NULL, 6, 0);

-- Fatura 7 (cliente 7)
INSERT INTO `linhasfaturas` (`quantidade`, `total`, `vendidoemconsulta`, `faturas_id`, `marcacoes_id`, `medicamentos_id`, `eliminado`) VALUES
(1, 50.00, 1, 7, 7, NULL, 0),
(2, 50.00, 1, 7, NULL, 7, 0);

-- Fatura 8 (cliente 8)
INSERT INTO `linhasfaturas` (`quantidade`, `total`, `vendidoemconsulta`, `faturas_id`, `marcacoes_id`, `medicamentos_id`, `eliminado`) VALUES
(1, 35.00, 1, 8, 8, NULL, 0),
(1, 16.50, 1, 8, NULL, 8, 0);

-- Fatura 9 (cliente 9)
INSERT INTO `linhasfaturas` (`quantidade`, `total`, `vendidoemconsulta`, `faturas_id`, `marcacoes_id`, `medicamentos_id`, `eliminado`) VALUES
(1, 35.00, 1, 9, 9, NULL, 0),
(2, 28.00, 1, 9, NULL, 1, 0);

-- Fatura 10 (cliente 10)
INSERT INTO `linhasfaturas` (`quantidade`, `total`, `vendidoemconsulta`, `faturas_id`, `marcacoes_id`, `medicamentos_id`, `eliminado`) VALUES
(1, 25.00, 1, 10, 10, NULL, 0),
(1, 35.00, 1, 10, NULL, 4, 0);

-- =====================================================
-- FIM DO SCRIPT
-- =====================================================

-- RESUMO DE CREDENCIAIS:
-- =======================
-- Admin:
--   Username: admin
--   Password: admin123
--   Email: admin@vetgestlink.pt
--
-- Recepcionista:
--   Username: recep1 / recep2
--   Password: recep123
--   Email: recep1@vetgestlink.pt / recep2@vetgestlink.pt
--
-- Veterinário:
--   Username: vet1 / vet2 / vet3 / vet4 / vet5
--   Password: vet123
--   Email: vet1@vetgestlink.pt / ... / vet5@vetgestlink.pt
--
-- Cliente (exemplo):
--   Username: cliente1
--   Password: cliente123
--   Email: cliente1@email.pt
-- =======================
